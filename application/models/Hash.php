<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Hash extends Zend_Db_Table_Row
{
    protected $_tableClass = 'Hashes';

    private $_foundSimilarOnSave = true;

    public function setFoundSimilarOnSave($value) {
        $this->_foundSimilarOnSave = $value;
    }

    public function save() {
        $this->cracked = (int)(bool)strlen($this->password);

        if ($this->id) {
            $oldData = $this->getTable()->get($this->id);

            parent::save();

            if ($this->_foundSimilarOnSave and (!$oldData->cracked and $this->cracked)) {
                $this->getTable()->markAllHashesByOne($this);
            }
        } else {
            parent::save();
        }
    }

    public function getUser() {
        return (new Users())->get($this->user_id);
    }

    public function getDataForTextPathImplementation() {
        $user = $this->getUser();
        $userGroup = $user->getGroup();
        switch ($userGroup->type) {
            case 'web-app':
                $app = (new WebApps())->get($userGroup->object_id);
                $domain = (new Domains())->get($app->domain_id);
                $server = (new Servers())->get($domain->id);
                $projectId = $server->project_id;

                $userGroupObjectType = 'Веб-приложение';
                $userGroupObjectName = $app->name;
                $str = "Веб-приложение {$app->name} (Домен {$domain->name}, Сервер {$server->name})";
                break;
            case 'server':
                $server = (new Servers())->get($userGroup->object_id);
                $projectId = $server->project_id;
                $str = "Сервер {$server->name}";
                break;
            case 'server-software':
                $serverSoftware = (new Servers_Software())->get($userGroup->object_id);
                $server = (new Servers())->get($serverSoftware->server_id);
                $projectId = $server->project_id;
                $str = "Серверное ПО {$serverSoftware->name} (Сервер {$server->name})";
                break;
        }
        return [
            'hash' => $this->hash,
            'salt' => $this->salt,
            'password' => $this->password,
            'user' => $user->login,
            'group' => $userGroup->name,
            'str' => $str,
            'project' => $projectId,
        ];
    }
}