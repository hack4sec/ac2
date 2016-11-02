<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Domains extends Common {
    protected $_name = 'domains';
    protected $_rowClass = 'Domain';

    public function getCountByProjectId($id) {
        return $this->getAdapter()->fetchOne(
            "SELECT COUNT(d.id) FROM domains d
             JOIN servers s  ON d.server_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
    }

    public function getListPaginator($projectId, $serverId, $search, $page) {
        if ($serverId) {
            $select = $this->getAdapter()->select()
                ->from(['d' => 'domains'])
                ->where("server_id = $serverId");
        } else {
            $select = $this->getAdapter()->select()
                ->from(['d' => 'domains'])
                ->join(['s' => 'servers'], 'd.server_id = s.id', [])
                ->where("s.project_id = ?", $projectId);
        }
        $select->order(["checked DESC", "name ASC"]);
        if (strlen($search)) {
            $select->where("d.name LIKE ? OR d.comment LIKE ?", "%$search%", "%$search%");
        }
        $paginator = Zend_Paginator::factory(
            $select
        )->setItemCountPerPage(Zend_Registry::get('config')->pagination->domains)
            ->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );
        $paginator->setView($view);
        return $paginator;
    }

    public function exists($serverId, $name) {
        return (bool)$this->fetchRow("server_id = {$serverId} AND name = {$this->getAdapter()->quote($name)}");
    }

    public function getCountByServerId($serverId) {
        return $this->getAdapter()->fetchOne("SELECT COUNT(id) FROM {$this->_name} WHERE server_id = $serverId");
    }

    public function getPairsList($serverId, $order = "id") {
        return $this->getAdapter()->fetchPairs(
            "SELECT id, name FROM {$this->_name} WHERE server_id = $serverId ORDER BY $order DESC"
        );
    }

    public function getPairsListByProjectId($projectId, $order = "id") {
        return $this->getAdapter()->fetchPairs(
            "SELECT d.id, d.name
             FROM `domains` d, servers s
             WHERE d.server_id = s.id AND s.project_id = {$projectId}
             ORDER BY d.$order DESC"
        );
    }

    public function listImport($fileName, $origServerId, $lookup) {
        $Servers = new Servers();
        $origServer = $Servers->get($origServerId);

        $config = Zend_Registry::get('config');
        $data = array_map('trim', array_unique(file($config->paths->tmp . "/$fileName")));
        foreach ($data as $domain) {
            if (strlen($domain)) {
                if ($lookup) {
                    if (($serverIp = gethostbyname($domain)) AND $serverIp != $domain) {
                        if ($Servers->existsByIp($origServer->project_id, $serverIp)) {
                            $serverId = $Servers->getByIp($origServer->project_id, $serverIp)->id;
                        } else {
                            $serverId = $Servers->add(
                                [
                                    "project_id" => $origServer->project_id,
                                    "ip" => $serverIp,
                                    "name" => $serverIp
                                ]
                            )->id;
                        }
                    }
                } else {
                    $serverId = $origServerId;
                }

                $validator = new Forms_Validate_Domains_Name();
                $validator->setServerId($serverId);
                if ($validator->isValid($domain)) {
                    $this->createRow(['name' => $domain, 'server_id' => $serverId])->save();
                }

            }
        }
        unlink($config->paths->tmp . "/$fileName");
    }

    public function getListOfIpsAndDomainsOnThem($projectId) {
        $result = [];
        $all = $this->getDefaultAdapter()->fetchAll(
            "SELECT s.ip, d.name FROM `servers` s JOIN `domains` d ON d.server_id = s.id WHERE s.project_id = $projectId"
        );
        foreach ($all as $row) {
            if (!isset($result[$row['ip']])) {
                $result[$row['ip']] = [];
            }
            $result[$row['ip']][] = $row['name'];
        }
        return $result;
    }
} 