<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Servers extends Common {
    protected $_name = 'servers';
    protected $_rowClass = 'Server';
    protected $_taskType = 'server';

    public function getListPaginator($projectId, $search, $page) {
        $select = $this->getAdapter()->select()
            ->from('servers')
            ->where("project_id = $projectId")
            ->order(["checked DESC", "name ASC"]);
        if (strlen($search)) {
            $select->where("name LIKE ? OR comment LIKE ?", "%$search%", "%$search%");
        }

        $paginator = Zend_Paginator::factory(
            $select
        )->setItemCountPerPage(Zend_Registry::get('config')->pagination->servers)
         ->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );
        $paginator->setView($view);
        return $paginator;
    }

    public function getFullList($projectId, $order="id") {
        return $this->fetchAll(
            [
                "project_id = $projectId",
            ],
            "$order ASC"
        );
    }

    public function exists($projectId, $name) {
        return (bool)$this->fetchRow("project_id = {$projectId} AND name = {$this->getAdapter()->quote($name)}");
    }

    public function existsByIp($projectId, $ip) {
        return (bool)$this->fetchRow("project_id = {$projectId} AND ip = {$this->getAdapter()->quote($ip)}");
    }

    public function getPairsList($project_id, $order = "id") {
        return $this->getAdapter()->fetchPairs(
            "SELECT id, name FROM {$this->_name} WHERE project_id = $project_id ORDER BY $order DESC"
        );
    }

    public function getCountByProjectId($id) {
        return $this->getAdapter()->fetchOne(
            "SELECT COUNT(id) FROM {$this->_name} WHERE project_id = $id"
        );
    }

    public function listImport($fileName, $projectId) {
        $validator = new Forms_Validate_Servers_Ip();
        $validator->setProjectId($projectId);
        $config = Zend_Registry::get('config');
        $data = array_map('trim', array_unique(file($config->paths->tmp . "/$fileName")));
        foreach ($data as $ip) {
            if ($validator->isValid($ip)) {
                $this->createRow(['ip' => $ip, 'name' => $ip, 'project_id' => $projectId])->save();
            }
        }
        unlink($config->paths->tmp . "/$fileName");
    }

    public function getByIp($projectId, $ip) {
        return $this->fetchRow(
            $this->select()->where('project_id = ?', $projectId)->where('ip = ?', $ip)
        );
    }

    public function getFullListIpsOnly($projectId, $order="id") {
        return $this->getDefaultAdapter()->fetchCol(
            "SELECT ip FROM {$this->_name} WHERE project_id = $projectId ORDER BY $order"
        );
    }
} 