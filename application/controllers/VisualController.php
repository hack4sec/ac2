<?php
/**
 * @package Analytical Center 2
 * @see for US http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (us)
 * @author Anton Kuzmin
 */
class VisualController extends Zend_Controller_Action
{
    public function indexAction() {
        $projectId = $this->_getParam('project_id');

        $Servers = new Servers();
        $Domains = new Domains();

        $this->view->project = (new Projects())->get($projectId);
        $this->view->subnets = Utils::getSubnetsByIps($Servers->getFullListIpsOnly($projectId));
        $this->view->domains = $Domains->getListOfIpsAndDomainsOnThem($projectId);

        $this->_helper->layout->setLayout('layout-visual');
    }
}