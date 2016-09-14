<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Validate_Servers_Software_Name extends Zend_Validate_Abstract {

    protected $_exclude = 0;
    protected $_serverId = 0;
    /**
     * Константы, содержащие ключи ошибок в общем массиве
     *
     * @var string
     */
    const REPEAT = 'repeat';

    /**
     * Массив ошибок валидатора
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::REPEAT => "L_SPO_YET_EXISTS"
    );

    /**
     * Метод занимающийся непосредственной проверкой
     * валидности переданного названия
     *
     * @return bool
     */
    public function isValid($value)
    {
        return !$this->repeat($value);
    }

    /**
     * Метод проверки названия на существование в базе.
     * Возвращает false если такого имени нет.
     *
     * @param string $title Проверяемое название
     * @return bool
     */
    public function repeat($name) {
        $ServersUsers = new Servers_Software();

        if($this->_exclude) {
            $editServer = $ServersUsers->get($this->_exclude);
            if($name == $editServer['name'])
                return false;
        }

        if($ServersUsers->exists($this->_serverId, $name)) {
            $this->_error(self::REPEAT);
            return true;
        }

        return false;
    }

    public function setExcludeId($id) {
        $this->_exclude = $id;
    }

    public function setServerId($id) {
        $this->_serverId = $id;
    }
}
