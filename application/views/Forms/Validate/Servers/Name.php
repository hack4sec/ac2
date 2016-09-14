<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Validate_Servers_Name extends Zend_Validate_Abstract {

    protected $_exclude = 0;
    protected $_projectId = 0;
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
        self::REPEAT => 'L_THIS_SERVER_YET_EXISTS_IN_THIS_PROJECT',
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
    public function repeat($title) {
        $Servers = new Servers;

        if($this->_exclude) {
            $editServer = $Servers->get($this->_exclude);
            if($title == $editServer['name'])
                return false;
        }

        if($Servers->exists($this->_projectId, $title)) {
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
