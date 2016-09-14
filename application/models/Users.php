<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Users extends Common
{
    protected $_rowClass = 'User';
    protected $_name = 'users';

    public function exists($groupId, $name) {
        return (bool) $this->getAdapter()->fetchOne(
            "SELECT COUNT(id) FROM `users` WHERE group_id = $groupId AND login = {$this->getAdapter()->quote($name)}"
        );
    }

    public function getListPaginator($groupId, $search, $page) {
        $select = $this->select()->where("group_id = $groupId")->order(["vip DESC", "login ASC"]);
        if (strlen($search)) {
            $select->where("login LIKE ? OR email LIKE ?", "%$search%", "%$search%");
        }
        $paginator = Zend_Paginator::factory(
            $select
        )->setItemCountPerPage(8)
            ->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );
        $paginator->setView($view);
        return $paginator;
    }

    public function add($data)
    {
        $user = parent::add($data);
        $Hashes = new Hashes;
        $hashData = [
            'user_id' => $user['id'],
            'hash' => $data['hash'],
            'alg_id' => $data['alg_id'],
            'salt' => $data['salt'],
            'password' => $data['password'],
            'cracked' => (int)(bool)strlen($data['password']),
        ];
        $Hashes->createRow($hashData)->save();
        return $user;
    }

    public function edit($data)
    {
        parent::edit($data);

        $Hashes = new Hashes;
        $hashRow = $Hashes->fetchRow("user_id = {$data['id']}");
        $hashRow->setFromArray($data);
        $hashRow->save();
    }

    public function getFullData($id) {
        $data = $this->get($id)->toArray();

        $Hashes = new Hashes;
        $hashData = $Hashes->fetchRow("user_id = {$data['id']}");
        return $data + [
            'password' => $hashData['password'],
            'salt' => $hashData['salt'],
            'hash' => $hashData['hash'],
            'alg_id' => $hashData['alg_id']
        ];
    }

    public function printExportList($data) {
        $Hashes = new Hashes;
        $Algs = new HashAlgs;
        $Shells = new Shells;

        $users = $this->fetchAll($data['group_id'] ? "group_id = {$data['group_id']}" : null, "login ASC");
        foreach ($users as $user) {
            $exportData = [];

            $passwordData = $Hashes->fetchRow("user_id = {$user->id}");
            if ($data['wopasswords'] and strlen($passwordData['password']) or
                $data['wpasswords'] and !strlen($passwordData['password']) or
                $data['only_vip'] and !$user['vip']) {
                continue;
            }

            if ($data['login']) {
                $exportData[] = $user['login'];
            }
            if ($data['email']) {
                $exportData[] = $user['email'];
            }
            if (isset($data['home_dir']) && $data['home_dir']) {
                $exportData[] = $user['home_dir'];
            }
            if (isset($data['shell']) && $data['shell']) {
                $exportData[] = $Shells->get($user['shell'])->name;
            }
            if ($data['vip']) {
                $exportData[] = $user['vip'];
            }
            if ($data['alg']) {
                $exportData[] = $Algs->get($passwordData['alg_id'])->name;
            }
            if ($data['hash']) {
                $exportData[] = $passwordData['hash'];
            }
            if ($data['salt']) {
                $exportData[] = $passwordData['salt'];
            }
            if ($data['password']) {
                $exportData[] = $passwordData['password'];
            }

            print implode($data['delimiter'], $exportData) . "\n";
        }
    }

    public function importFromFile($file, $post, $groupId) {
        $config = Zend_Registry::get('config');

        $Shells = new Shells();
        $HashAlgs = new HashAlgs();
        $Hashes = new Hashes();

        $possibleFields = ['login', 'email', 'home_dir', 'shell', 'vip', 'alg', 'hash', 'salt', 'password'];
        $columns = [];
        foreach ($possibleFields as $possibleField) {
            if (in_array($possibleField, array_keys($post)) && $post[$possibleField] == '1') {
                $columns[] = $possibleField;
            }
        }

        foreach (file($config->paths->storage . "/" . $file) as $str) {
            $arr = explode($post['delimiter'], trim($str));

            if (count($arr) != count($columns)) {
                continue;
            }

            $newUser = $this->createRow();
            $newUser->group_id = $groupId;
            $newHash = $Hashes->createRow();

            foreach ($columns as $column) {
                switch ($column) {
                    case 'login':
                    case 'email':
                    case 'home_dir':
                    case 'vip':
                        $newUser->{$column} = array_shift($arr);
                        break;
                    case 'shell':
                        $shell = array_shift($arr);
                        $shell = $Shells->fetchRow("name = {$Shells->getAdapter()->quote($shell)}");
                        $newUser->shell_id = $shell->id;
                        break;
                    case 'hash':
                    case 'salt':
                    case 'password':
                        $newHash->{$column} = array_shift($arr);
                        break;
                    case 'alg':
                        $alg = array_shift($arr);
                        $algRow = $HashAlgs->fetchRow("name = {$HashAlgs->getAdapter()->quote($alg)}");
                        if (!$algRow) {
                            throw new Exception("Unknown alg '{$alg}' - not found in DB. Line '" . trim($str) . "'.");
                        }
                        $newHash->alg_id = $algRow->id;
                        break;
                }
            }

            $newUser->save();
            $newHash->user_id = $newUser->id;
            $newHash->save();
            $newUser->hash_id = $newHash->id;
            $newUser->save();
        }
    }

    public function pairsLoad($file, $data) {
        $config = Zend_Registry::get('config');

        $Hashes = new Hashes();

        $results = [
            'founded' => 0,
            'not_founded' => 0,
            'bad_strings' => 0,
        ];
        foreach (file($config->paths->storage . "/" . $file) as $item) {
            if (substr_count($item, $data['delimiter']) == 2) {
                list($hash, $salt, $pass) = explode($data['delimiter'], trim($item));
            } elseif (substr_count($item, $data['delimiter']) == 1) {
                list($hash, $pass) = explode($data['delimiter'], trim($item));
                $salt = '';
            } else {
                $results['bad_strings']++;
            }

            $hashRowset = $Hashes->fetchAll(
                "`alg_id` = '{$data['alg_id']}'
                 AND `hash`={$Hashes->getAdapter()->quote($hash)}
                 AND `salt`={$Hashes->getAdapter()->quote($salt)}"
            );

            if (!count($hashRowset)) {
                $results['not_founded']++;
                continue;
            }

            foreach ($hashRowset as $hashRow) {
                $hashRow->password = $pass;
                $hashRow->save();
                $results['founded']++;
            }
        }
        return $results;
    }

    public function notFoundHashesExportZip() {
        $config = Zend_Registry::get('config');

        $stmt = $this->getAdapter()->query(
            "SELECT a.name, IF(LENGTH(salt), CONCAT(hash, ':', salt), hash) as `line`
             FROM `hashes` h
             JOIN hash_algs a ON a.id = h.alg_id
             JOIN users u ON u.id = h.user_id
             JOIN users_groups g ON g.id = u.group_id
             WHERE !h.cracked"
        );

        if (!$stmt->rowCount()) {
            return false;
        }

        $tmpDir = $config->paths->tmp . "/" . md5(time());
        mkdir($tmpDir);

        $fhs = [];
        while ($row = $stmt->fetch()) {
            $name = preg_replace("#[^a-z0-9\-_\.\(\)]#i", "_", $row['name']);
            if (!in_array($name, array_keys($fhs))) {
                $fhs[$name] = fopen("$tmpDir/$name.txt", "w");
            }
            fwrite($fhs[$name], $row['line'] . "\n");
        }

        foreach ($fhs as $name => $fh) {
            fclose($fh);
        }

        $zip = new PclZip("$tmpDir/hashes.zip");
        $zip->add("$tmpDir/", PCLZIP_OPT_REMOVE_ALL_PATH);

        return "$tmpDir/hashes.zip";
    }

    public function getObjectsPairsList($type, $parentId) {
        switch ($type) {
            case 'server-software':
                $list = (new Servers_Software())->getPairsList($parentId, "name");
                break;
            case 'web-app':
                $list = (new WebApps())->getPairsList($parentId, "name");
                break;
            case 'domain':
                $list = (new Domains())->getPairsList($parentId, "name");
                break;
            case 'server':
                $list = (new Servers())->getPairsList($parentId, "name");
                break;
        }
        return $list;
    }

    public function getParentsPairsList($projectId, $type) {
        switch ($type) {
            case 'server-software':
                $list = (new Servers())->getPairsList($projectId, "name");
                break;
            case 'web-app':
                $list = (new Domains())->getPairsListByProjectId($projectId, "name");
                break;
            case 'domain':
                $list = (new Servers())->getPairsList($projectId, "name");
                break;
        }
        return $list;
    }

    public function getCountByProjectId($id) {
        $server = $this->getAdapter()->fetchOne(
            "SELECT COUNT(u.id) FROM users u
             JOIN users_groups g ON u.group_id = g.id AND g.type = 'server'
             JOIN servers s ON g.object_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
        $webApps = $this->getAdapter()->fetchOne(
            "SELECT COUNT(u.id) FROM users u
             JOIN users_groups g ON u.group_id = g.id AND g.type = 'web-app'
			 JOIN web_apps a ON g.object_id = a.id
             JOIN domains d ON a.domain_id = d.id
             JOIN servers s ON d.server_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
        $spo = $this->getAdapter()->fetchOne(
            "SELECT COUNT(u.id) FROM users u
             JOIN users_groups g ON u.group_id = g.id AND g.type = 'server-software'
			 JOIN servers_software ss ON g.object_id = ss.id
             JOIN servers s ON ss.server_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
        return $webApps + $spo + $server;
    }

    public function getCountByTypeAndId($type, $id) {
        return $this->getAdapter()->fetchOne(
            "SELECT COUNT(u.id) FROM users u, users_groups ug
             WHERE ug.id = u.group_id AND ug.type='$type' AND ug.object_id = $id"
        );
    }
}