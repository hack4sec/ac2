<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Validate_Servers_Ip extends Zend_Validate_Abstract {
    protected $_exclude = 0;
    protected $_projectId = 0;

    /**
     * Констранты, содержащие ключи ошибок в общем массиве
     *
     * @var string
     */
    const INVALID = 'invalid';
    const REPEAT = 'repeat';

    /**
     * Массив ошибок валидатора
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => "L_WRONG_IP",
        self::REPEAT => "L_SERVER_WITH_THIS_IP_ALREADY_EXISTS"
    );

    /**
     * Метод занимающийся непосредственной проверкой
     * валидности переданного адреса.
     *
     * @return bool
     */
    public function isValid($value)
    {
        return $this->regexp($value) AND !$this->repeat($value);
    }

    /**
     * Метод проверки IP регулярным выражением
     *
     * @param sring $ip Проверяемый IP-адрес
     * @return bool
     */
    public function regexp($ip) {
        if(!preg_match("#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#", $ip)) {
            $this->_error(self::INVALID);
            return false;
        }
        return true;
    }
    /**
     * Метод проверки IP на существование в базе.
     * Возвращает false если такого адреса нет.
     *
     * @param sring $ip Проверяемый IP-адрес
     * @return bool
     */
    public function repeat($ip) {
        $Servers = new Servers;

        if($this->_exclude) {
            $editServer = $Servers->get($this->_exclude);
            if($ip == $editServer['ip'])
                return false;
        }

        if($Servers->existsByIp($this->_projectId, $ip)) {
            $this->_error(self::REPEAT);
            return true;
        }

        return false;
    }


    public function setExcludeId($id) {
        $this->_exclude = $id;
    }

    public function setProjectId($id) {
        $this->_projectId = $id;
    }
}
