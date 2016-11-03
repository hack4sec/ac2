<?php

/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class User extends Zend_Db_Table_Row
{
    protected $_tableClass = 'Users';

    private $_hashData = null;
    private $_shellName = null;

    private function _getHashData() {
        if ($this->_hashData === null) {
            $Hashes = new Hashes();
            $HashAlgs = new HashAlgs();
            $hash = $Hashes->fetchRow("user_id = {$this->id}");
            $alg = $HashAlgs->fetchRow("id = {$hash['alg_id']}");
            $this->_hashData = $hash->toArray();
            $this->_hashData['alg'] = $alg ? $alg->toArray() : false;
        }
        return $this->_hashData;
    }

    public function getHash() {
        return $this->_getHashData()['hash'];
    }

    public function getPassword() {
        return $this->_getHashData()['password'];
    }

    public function getSalt() {
        return $this->_getHashData()['salt'];
    }

    public function getAlg() {
        return $this->_getHashData()['alg']['name'];
    }

    public function getShell() {
        if ($this->_shellName === null and $this->shell_id) {
            $Shells = new Shells();
            $shell = $Shells->find($this->shell_id)->current();
            $this->_shellName = ($shell ? $shell['name'] : '');
        }
        return $this->_shellName;
    }

    public function getGroup() {
        return (new Users_Groups())->get($this->group_id);
    }


    public function getParentsTextImplementation($isTypeNeed, $isParentNeed, $isObjectNeed, $isGroupNeed) {
        $text = "";
        $group = $this->getGroup();
        $type = $group->type;

        if ($isTypeNeed) {
            $text .= "[{$type}]";
        }

        if ($type == 'web-app' && $isParentNeed) {
            $webApp = Zend_Registry::get('mainModels')['webApps']->get($group->object_id);
            $domain = Zend_Registry::get('mainModels')['domains']->get($webApp->domain_id);
            $server = Zend_Registry::get('mainModels')['servers']->get($domain->server_id);
            $text .= "[{$server->name}]";
            $text .= "[{$domain->name}]";
        } elseif ($type == 'server-software' && $isParentNeed) {
            $spo = Zend_Registry::get('mainModels')['serversSoftware']->get($group->object_id);
            $server = Zend_Registry::get('mainModels')['servers']->get($spo->server_id);
            $text .= "[{$server->name}]";
        }

        if ($isObjectNeed) {
            switch ($type) {
                case 'web-app':
                    $webApp = Zend_Registry::get('mainModels')['webApps']->get($group->object_id);
                    $text .= "[{$webApp->name}]";
                    break;
                case 'server':
                    $server = Zend_Registry::get('mainModels')['servers']->get($group->object_id);
                    $text .= "[{$server->name}]";
                    break;
                case 'server-software':
                    $spo = Zend_Registry::get('mainModels')['serversSoftware']->get($group->object_id);
                    $text .= "[{$spo->name}]";
                    break;
            }
        }

        if ($isGroupNeed) {
            $text .= "[{$this->getGroup()->name}]";
        }

        return $text;
    }
}