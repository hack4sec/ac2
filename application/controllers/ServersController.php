<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class ServersController extends CommonController{
    public function init() {
        parent::init();
        $this->_model = new Servers;
        $this->view->os = (new Os())->getList();
    }

    public function getListJsonAction() {
        $this->_helper->json(
            $this->_model->getPairsList($this->_getParam('project_id'))
        );
    }

    public function indexAction() {
        $this->view->projectsList = (new Projects())->getList("name");
        $this->view->upMenuData = [
            'project' => $this->view->projectData
        ];
        $this->view->projectId = $this->_getParam('project_id');
        $this->_helper->layout->disableLayout();
    }

    public function ajaxListAction() {
        $this->view->projectId = $this->_getParam('project_id');
        if ($this->_getParam('project_id')) {
            $this->view->paginator = $this->_model->getListPaginator(
                $this->_getParam('project_id'),
                $this->_getParam('search'),
                $this->_getParam('page', 1)
            );
        }
        $this->_helper->layout->disableLayout();
    }

    public function viewAction() {
        $this->view->server = $this->_model->get($this->_getParam('id'));
        $this->view->domainsCount = (new Domains())->getCountByServerId($this->_getParam('id'));
        $this->view->spoCount = (new Servers_Software())->getCountByServerId($this->_getParam('id'));
        $this->view->usersCount = (new Users())->getCountByTypeAndId("server", $this->_getParam('id'));
        $this->view->tasksCount = (new Tasks())->getCountByTypeAndId("server", $this->_getParam('id'));
        $this->view->filesCount = (new Files())->getCountByTypeAndId("server", $this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function addAction() {
        $form = new Forms_Servers_Add();
        $form->setProjectId($this->_getParam('project_id'));
        $form->name->getValidator('Forms_Validate_Servers_Name')->setProjectId($this->_getParam('project_id'));
        $form->ip->getValidator('Forms_Validate_Servers_Ip')->setProjectId($this->_getParam('project_id'));
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_Servers_Edit();
        $form->name->getValidator('Forms_Validate_Servers_Name')->setProjectId($this->_getParam('project_id'));
        $form->ip->getValidator('Forms_Validate_Servers_Ip')->setProjectId($this->_getParam('project_id'));
        if ($this->_request->isPost()) {
            $form->name->getValidator('Forms_Validate_Servers_Name')->setExcludeId($this->_getParam('id'));
            $form->ip->getValidator('Forms_Validate_Servers_Ip')->setExcludeId($this->_getParam('id'));
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $form->populate($this->_model->get($this->_getParam('id'))->toArray());
        }
        $this->view->form = $form;
        $this->_helper->layout->disableLayout();
    }

    public function deleteAction() {
        $this->_model->get($this->_getParam('id'))->delete();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function oneInListAction() {
        $this->view->server = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function listImportAction() {
        $form = new Forms_Servers_ListImport();

        if ($this->_request->isPost() and $form->file->receive()) {
            $this->_model->listImport(
                $form->file->getFileName(NULL, false),
                $this->_getParam('project_id')
            );
            $this->redirect(
                '/projects/view/project_id/' . $this->_getParam('project_id')
            );
        } else {
            $form->setProjectId($this->_getParam('project_id'));
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
            ->setHeader('Content-Disposition', "attachment; filename=servers-export.txt")
            ->clearBody();
        $this->getResponse()->sendHeaders();

        foreach ($this->_model->getFullList($this->_getParam('project_id')) as $ip) {
            print $ip->ip . "\n";
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }
} 