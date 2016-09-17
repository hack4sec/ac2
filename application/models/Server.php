<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Server extends Zend_Db_Table_Row
{
    protected $_tableClass = 'Servers';

    public function getCssClassByVulns() {
        return $this->getTable()->getAdapter()->fetchOne(
            "SELECT css_class, rl.sort FROM `vulns` v, risk_levels rl, servers_software ss, servers s
             WHERE s.id = {$this->id} AND ss.server_id = s.id AND v.type='server-software'
                   AND v.object_id = ss.id AND v.risk_level_id = rl.id
             GROUP BY rl.sort
             UNION
             SELECT css_class, rl.sort FROM `vulns` v, risk_levels rl, web_apps wa, domains d, servers s
             WHERE s.id = {$this->id} AND d.server_id = s.id AND d.id = wa.domain_id AND v.type='web-app' AND v.object_id = wa.id
                   AND v.risk_level_id = rl.id
             GROUP BY rl.sort
             ORDER BY sort DESC
             LIMIT 1");
    }
}