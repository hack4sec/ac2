<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class DomainsController extends Zend_Controller_Action {
    public function init() {
        parent::init();
        $this->_model = new Domains();
    }

    public function getListJsonAction() {
        $this->_helper->json(
            $this->_model->getPairsList($this->_getParam('server_id'))
        );
    }

    public function addAction() {
        $form = new Forms_Domains_Add();
        $form->setServerId($this->_getParam('server_id'));
        $form->name->getValidator('Forms_Validate_Domains_Name')->setServerId($this->_getParam('server_id'));
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_Domains_Edit();
        $form->setServerId($this->_getParam('server_id'));
        if ($this->_request->isPost()) {
            $form->name->getValidator('Forms_Validate_Domains_Name')->setServerId($this->_getParam('server_id'));
            $form->name->getValidator('Forms_Validate_Domains_Name')->setExcludeId($this->_getParam('id'));
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

    public function indexAction() {
        $this->view->servers = (new Servers())->getFullList($this->_getParam('project_id'), "name");
        $this->_helper->layout->disableLayout();
    }

    public function viewAction() {
        $this->view->domain = $this->_model->get($this->_getParam('id'));
        $this->view->appsCount = (new WebApps())->getCountByDomainId($this->_getParam('id'));
        $this->view->tasksCount = (new Tasks())->getCountByTypeAndId("domain", $this->_getParam('id'));
        $this->view->filesCount = (new Files())->getCountByTypeAndId("domain", $this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function ajaxListAction() {
        $this->view->paginator = $this->_model->getListPaginator(
            $this->_getParam('project_id'),
            $this->_getParam('server_id'),
            $this->_getParam('search'),
            $this->_getParam('page', 1)
        );

        $this->view->serversList = (new Servers())->getFullList($this->_getParam('project_id'));
        $this->_helper->layout->disableLayout();
    }

    public function oneInListAction() {
        $this->view->domain = $this->_model->get($this->_getParam('id')) ;
        $this->_helper->layout->disableLayout();
    }

    public function listImportAction() {
        $form = new Forms_Domains_ListImport();

        if ($this->_request->isPost() and $form->file->receive()) {
            $this->_model->listImport(
                $form->file->getFileName(NULL, false),
                $this->_getParam('server_id'),
                $this->_getParam('lookup', false)
            );

            $this->redirect(
                '/projects/view/project_id/' .
                (new Servers())->get($this->_getParam('server_id'))['project_id'] .
                '#domain-openlist-FILTER-' . $this->_getParam('server_id')
            );
        } else {
            $form->setServerId($this->_getParam('server_id'));
            $this->view->form = $form;
            $this->_helper->layout->disableLayout();
        }
    }

    public function listExportAction() {
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', 'application/unknown;charset=utf-8', true)
            ->setHeader('Content-Disposition', "attachment; filename=domains-export.txt")
            ->clearBody();
        $this->getResponse()->sendHeaders();

        foreach ($this->_model->getPairsList($this->_getParam('server_id')) as $domain) {
            print $domain . "\n";
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

} 