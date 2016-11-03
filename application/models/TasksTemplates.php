<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class TasksTemplates extends Common {
    protected $_name = 'tasks_templates';

    public function exists($projectId, $type, $name) {
        return (bool)$this->fetchRow(
            "project_id = {$projectId}
             AND type = {$this->getAdapter()->quote($type)}
             AND name = {$this->getAdapter()->quote($name)}"
        );
    }

    public function getListPaginator($projectId, $type, $page) {
        $select = $this->getAdapter()->select()->from($this->_name)->where('project_id = ?', $projectId)->where('type = ?', $type)->order("name ASC");

        $paginator = Zend_Paginator::factory(
            $select
        )->setItemCountPerPage(Zend_Registry::get('config')->pagination->tasks)
            ->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );
        $paginator->setView($view);

        return $paginator;
    }


    public function getCountByProjectId($id) {
        return $this->getAdapter()->fetchOne(
            "SELECT COUNT(id) FROM tasks_templates WHERE project_id = $id"
        );
    }
} 