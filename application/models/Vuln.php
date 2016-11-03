<?php

class Vuln extends Zend_Db_Table_Row
{
    protected $_tableClass = 'Vulns';

    public function getParentsTextImplementation($isTypeNeed, $isParentNeed, $isObjectNeed) {
        $text = "";

        if ($isTypeNeed) {
            $text .= "[{$this->type}]";
        }

        if ($this->type == 'web-app' && $isParentNeed) {
            $webApp = Zend_Registry::get('mainModels')['webApps']->get($this->object_id);
            $domain = Zend_Registry::get('mainModels')['domains']->get($webApp->domain_id);
            $server = Zend_Registry::get('mainModels')['servers']->get($domain->server_id);
            $text .= "[{$server->name}]";
            $text .= "[{$domain->name}]";
        } elseif ($this->type == 'server-software' && $isParentNeed) {
            $spo = Zend_Registry::get('mainModels')['serversSoftware']->get($this->object_id);
            $server = Zend_Registry::get('mainModels')['servers']->get($spo->server_id);
            $text .= "[{$server->name}]";
        }

        if ($isObjectNeed) {
            switch ($this->type) {
                case 'web-app':
                    $webApp = Zend_Registry::get('mainModels')['webApps']->get($this->object_id);
                    $text .= "[{$webApp->name}]";
                    break;
                case 'server-software':
                    $spo = Zend_Registry::get('mainModels')['serversSoftware']->get($this->object_id);
                    $text .= "[{$spo->name}]";
                    break;
            }
        }

        return $text;
    }
}