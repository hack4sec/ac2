<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class WebApp extends Zend_Db_Table_Row
{
    protected $_tableClass = 'WebApps';

    public function getCssClassByVulns() {
        return $this->getTable()->getAdapter()->fetchOne(
            "SELECT css_class FROM `vulns` v, risk_levels rl, web_apps wa
             WHERE wa.id = {$this->id} AND v.type='web-app' AND v.object_id = wa.id AND v.risk_level_id = rl.id
             GROUP BY rl.sort
             ORDER BY rl.sort DESC
             LIMIT 1");
    }

    public function getParentsTextImplementation($isParentNeed, $isObjectNeed) {
        $text = "";

        if ($isParentNeed) {
            $domain = Zend_Registry::get('mainModels')['domains']->get($this->domain_id);
            $server = Zend_Registry::get('mainModels')['servers']->get($domain->server_id);
            $text .= "[{$server->name}]";
        }

        if ($isObjectNeed) {
            $domain = Zend_Registry::get('mainModels')['domains']->get($this->domain_id);
            $text .= "[{$domain->name}]";
        }

        return $text;
    }
}