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

    public function getListPaginator($projectId, $type, $parent, $objectId, $search, $page) {
        if (!$type) {
            $select = $this->_getListAll($projectId);
        } elseif ($type and !$parent and !$objectId) {
            $select = $this->_getListAllByType($projectId, $type);
        } elseif ($type and $parent and !$objectId) {
            $select = $this->_getListByTypeAndParent($projectId, $type, $parent);
        } else {
            $select = $this->_getListByObjectId($projectId, $type, $objectId);
        }

        $select->order("sort DESC");
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

    private function _getListByObjectId($projectId, $type, $objectId) {
        return $this->_getListAllByType($projectId, $type)->where('object_id = ?', $objectId);
    }

    private function _getListByTypeAndParent($projectId, $type, $parent) {
        $select = $this->_getListAllByType($projectId, $type);
        switch ($type) {
            case 'web-app':
                $select->where("d.id = ?", $parent);
                break;
            case 'server-software':
                $select->where("s.id = ?", $parent);
                break;
            default:
                throw new Exception("Unknown list type '{$type}'");
        }
        return $select;
    }

    private function _getListAllByType($projectId, $type) {
        switch ($type) {
            case 'web-app':
                $select = $this->getAdapter()->select()
                    ->from(['v' => 'vulns'], ['*'])
                    ->join(['w' => 'web_apps'], 'v.object_id = w.id', [])
                    ->join(['d' => 'domains'], 'w.domain_id = d.id', [])
                    ->join(['s' => 'servers'], "d.server_id = s.id AND s.project_id = $projectId", [])
                    ->where("v.type = 'web-app'");
                break;
            case 'server-software':
                $select = $this->getAdapter()->select()
                    ->from(['v' => 'vulns'], ['*'])
                    ->join(['s' => 'servers'], "s.project_id = $projectId", [])
                    ->join(['ss' => 'servers_software'], 'ss.server_id = s.id AND ss.id = v.object_id', [])
                    ->where("v.type = 'server-software'");
                break;
            default:
                throw new Exception("Unknown list type '{$type}'");
        }
        return $select;
    }

    private function _getListAll($projectId) {
        return $this->getAdapter()->select()->union(
            [
                $this->_getListAllByType($projectId, 'web-app'),
                $this->_getListAllByType($projectId, 'server-software'),
            ]
        );
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