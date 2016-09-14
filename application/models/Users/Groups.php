<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Users_Groups extends Common
{
    protected $_name = 'users_groups';

    public function getPairsListByTypeAndObjectId($type, $objectId) {
        return $this->getAdapter()->fetchPairs(
            "SELECT id, name FROM $this->_name WHERE type='$type' AND object_id = $objectId ORDER BY name"
        );
    }

    public function exists($type, $objectId, $name) {
        return (bool)$this->fetchRow(
            "type='$type' AND object_id = {$objectId} AND name = {$this->getAdapter()->quote($name)}"
        );
    }
}