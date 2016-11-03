<?php

class CommonController extends Zend_Controller_Action
{
    public function init()
    {
        $mainModels = [
            'tasks' => new Tasks(),
            'domains' => new Domains(),
            'vulns' => new Vulns(),
            'servers' => new Servers(),
            'serversSoftware' => new Servers_Software(),
            'projects' => new Projects(),
            'webApps' => new WebApps(),
        ];

        Zend_Registry::set('mainModels', $mainModels);

        parent::init();
    }
}