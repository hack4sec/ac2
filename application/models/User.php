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
            $this->_hashData['alg'] = $alg->toArray();
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
}