<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Notes extends Common
{
    protected $_name = 'notes';

    public function getListByProjectId($projectId) {
        return $this->fetchAll(
            $this->select()->where("project_id = ?", $projectId)->order('id DESC')
        );
    }

    public function getCountByProjectId($projectId) {
        return $this->getAdapter()->fetchOne("SELECT COUNT(id) FROM notes WHERE project_id = $projectId");
    }
}