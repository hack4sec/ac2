<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Vulns extends Common
{
    protected $_name = 'vulns';

    public function add($data) {
        $data['sort'] = (new RiskLevels())->get($data['risk_level_id'])->sort;
        parent::add($data);
    }

    public function getListPaginator($type, $objectId, $search, $page) {
        $select = $this->select()->where("type = {$this->getAdapter()->quote($type)} AND object_id = $objectId")->order("sort DESC");
        if (strlen($search)) {
            $select->where("name LIKE ? OR description LIKE ?", "%$search%", "%$search%");
        }
        $paginator = Zend_Paginator::factory(
            $select
        )->setItemCountPerPage(Zend_Registry::get('config')->pagination->vulns)
            ->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );
        $paginator->setView($view);
        return $paginator;
    }

    public function getObjectsPairsList($type, $parentId) {
        if ($type == 'server-software') {
            $list = (new Servers_Software())->getPairsList($parentId, "name");
        } elseif ($type == 'web-app') {
            $list = (new WebApps())->getPairsList($parentId, "name");
        }
        return $list;
    }

    public function getParentsPairsList($projectId, $type) {
        if ($type == 'server-software') {
            $list = (new Servers())->getPairsList($projectId, "name");
        } elseif ($type == 'web-app') {
            $list = (new Domains())->getPairsListByProjectId($projectId, "name");
        }
        return $list;
    }

    public function getCountByProjectId($id) {
        $webApps = $this->getAdapter()->fetchOne(
            "SELECT COUNT(v.id) FROM vulns v
			 JOIN web_apps a ON v.object_id = a.id AND v.type='web-app'
             JOIN domains d ON a.domain_id = d.id
             JOIN servers s ON d.server_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
        $spo = $this->getAdapter()->fetchOne(
            "SELECT COUNT(v.id) FROM vulns v
			 JOIN servers_software ss ON v.object_id = ss.id AND v.type = 'server-software'
             JOIN servers s ON ss.server_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
        return $webApps + $spo;
    }

    public function getCountByTypeAndId($type, $id) {
        return $this->getAdapter()->fetchOne(
            "SELECT COUNT(id) FROM {$this->_name} WHERE type='$type' AND object_id = $id"
        );
    }
}