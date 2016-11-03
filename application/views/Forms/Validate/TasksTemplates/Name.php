<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Validate_TasksTemplates_Name extends Zend_Validate_Abstract {

    protected $_exclude = 0;
    protected $_projectId = 0;
    protected $_type = 0;
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
        self::REPEAT => "L_TASK_TEMPLATE_YET_EXISTS"
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
        $Tasks = new TasksTemplates();

        if($this->_exclude) {
            $editServer = $Tasks->get($this->_exclude);
            if($title == $editServer['name'])
                return false;
        }

        if($Tasks->exists($this->_projectId, $this->_type, $title)) {
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

    public function setType($id) {
        $this->_type = $id;
    }
}
