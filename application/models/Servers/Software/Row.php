<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Servers_Software_Row extends Zend_Db_Table_Row
{
    protected $_tableClass = 'Servers_Software';

    public function getCssClassByVulns() {
        return $this->getTable()->getAdapter()->fetchOne(
            "SELECT css_class FROM `vulns` v, risk_levels rl, servers_software ss
             WHERE ss.id = {$this->id} AND v.type='server-software' AND v.object_id = ss.id AND v.risk_level_id = rl.id
             GROUP BY rl.sort
             ORDER BY rl.sort DESC
             LIMIT 1");
    }

    public function getParentsTextImplementation($isServerNeed) {
        $text = "";

        if ($isServerNeed) {
            $server = Zend_Registry::get('mainModels')['servers']->get($this->server_id);
            $text .= "[{$server->name}]";
        }

        return $text;
    }
}