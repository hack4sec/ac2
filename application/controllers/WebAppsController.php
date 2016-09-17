<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class WebAppsController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_model = new WebApps;
    }

    public function addAction() {
        $form = new Forms_WebApps_Add();
        $form->setDomainId($this->_getParam('domain_id'));
        $form->name->getValidator('Forms_Validate_WebApps_Name')->setDomainId($this->_getParam('domain_id'));
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }


    public function editAction() {
        $form = new Forms_WebApps_Edit();
        if ($this->_request->isPost()) {
            $form->name->getValidator('Forms_Validate_WebApps_Name')->setDomainId($this->_getParam('domain_id'));
            $form->name->getValidator('Forms_Validate_WebApps_Name')->setExcludeId($this->_getParam('id'));
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $domain = $this->_model->get($this->_getParam('id'));
            $form->populate($domain->toArray());
        }
        $this->view->form = $form;
        $this->_helper->layout->disableLayout();
    }

    public function deleteAction() {
        $this->_model->get($this->_getParam('id'))->delete();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function viewAction() {
        $this->view->webApp = $this->_model->get($this->_getParam('id'));
        $this->view->domain = (new Domains())->get($this->view->webApp['domain_id']);
        $this->view->usersCount = (new Users())->getCountByTypeAndId("web-app", $this->_getParam('id'));
        $this->view->vulnsCount = (new Vulns())->getCountByTypeAndId("web-app", $this->_getParam('id'));
        $this->view->tasksCount = (new Tasks())->getCountByTypeAndId("web-app", $this->_getParam('id'));
        $this->view->filesCount = (new Files())->getCountByTypeAndId("web-app", $this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        $this->view->servers = (new Servers())->getFullList($this->_getParam('project_id'), "name");
        $this->_helper->layout->disableLayout();
    }

    public function ajaxListAction() {
        if ($this->_getParam('domain_id')) {
            $this->view->paginator = $this->_model->getListPaginator(
                $this->_getParam('domain_id'),
                $this->_getParam('search'),
                $this->_getParam('page', 1)
            );
        }
        $this->_helper->layout->disableLayout();
    }

    public function oneInListAction() {
        $this->view->webApp = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }
}