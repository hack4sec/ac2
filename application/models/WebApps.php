<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class WebApps extends Common
{
    protected $_name = 'web_apps';
    protected $_rowClass = 'WebApp';

    public function getCountByProjectId($id) {
        return $this->getAdapter()->fetchOne(
            "SELECT COUNT(a.id) FROM web_apps a
             JOIN domains d ON a.domain_id = d.id
             JOIN servers s ON d.server_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
    }

    public function exists($domainId, $name) {
        return (bool)$this->fetchRow("domain_id = {$domainId} AND name = {$this->getAdapter()->quote($name)}");
    }

    public function getCountByDomainId($domainId) {
        return $this->getAdapter()->fetchOne("SELECT COUNT(id) FROM {$this->_name} WHERE domain_id = $domainId");
    }

    public function getPairsList($domainId, $order = "id") {
        return $this->getAdapter()->fetchPairs(
            "SELECT id, name FROM {$this->_name} WHERE domain_id = $domainId ORDER BY $order DESC"
        );
    }

    public function getListPaginator($projectId, $parent, $domainId, $search, $page) {
        if (!$parent and !$domainId) {
            $select = $this->_getListAll($projectId);
        } elseif ($parent and !$domainId) {
            $select = $this->_getListByParent($projectId, $parent);
        } else {
            $select = $this->_getListByDomainId($projectId, $domainId);
        }
        $select->order(["checked DESC", "name ASC"]);
        if (strlen($search)) {
            $select->where("w.name LIKE ? OR w.comment LIKE ?", "%$search%", "%$search%");
        }

        $paginator = Zend_Paginator::factory(
            $select
        )->setItemCountPerPage(Zend_Registry::get('config')->pagination->webapps)
            ->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );
        $paginator->setView($view);
        return $paginator;
    }

    private function _getListByDomainId($projectId, $domainId) {
        return $this->_getListAll($projectId)->where('domain_id = ?', $domainId);
    }

    private function _getListByParent($projectId, $parent) {
        return $this->_getListAll($projectId)->where("s.id = $parent");
    }

    private function _getListAll($projectId) {
        $select = $this->getAdapter()->select()
            ->from(['w' => 'web_apps'], ['*'])
            ->join(['d' => 'domains'], 'w.domain_id = d.id', [])
            ->join(['s' => 'servers'], "d.server_id = s.id AND s.project_id = $projectId", []);
        return $select;
    }
}