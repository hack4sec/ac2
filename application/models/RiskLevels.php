<?php
/**
 * @package Analytical Center 2
 * @see for US http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class RiskLevels extends Common
{
    protected $_name = 'risk_levels';

    public function getList() {
        return $this->getAdapter()->fetchPairs(
            "SELECT id, name FROM {$this->_name} ORDER BY `sort` ASC"
        );
    }

    public function getListCssClasses() {
        return $this->getAdapter()->fetchPairs(
            "SELECT id, css_class FROM {$this->_name} ORDER BY `sort` ASC"
        );
    }
}