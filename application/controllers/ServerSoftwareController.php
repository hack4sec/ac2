<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class ServerSoftwareController extends CommonController
{
    public function init() {
        parent::init();
        $this->_model = new Servers_Software();
    }

    public function addAction() {
        $form = new Forms_ServerSoftware_Add();
        $form->setServerId($this->_getParam('server_id'));
        $form->name->getValidator('Forms_Validate_Servers_Software_Name')->setServerId($this->_getParam('server_id'));
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_ServerSoftware_Edit();
        $form->setServerId($this->_getParam('server_id'));

        if ($this->_request->isPost()) {
            $form->name->getValidator('Forms_Validate_Servers_Software_Name')->setServerId($this->_getParam('server_id'));
            $form->name->getValidator('Forms_Validate_Servers_Software_Name')->setExcludeId($this->_getParam('id'));
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $user = $this->_model->get($this->_getParam('id'));
            $form->populate($user->toArray());
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

    public function ajaxListAction() {
        $this->view->paginator = $this->_model->getListPaginator(
            $this->_getParam('project_id'),
            $this->_getParam('server_id'),
            $this->_getParam('search'),
            $this->_getParam('page', 1)
        );
        $this->view->hasServer = (bool)$this->_getParam('server_id');
        $this->view->serversList = (new Servers())->getFullList($this->_getParam('project_id'));
        $this->_helper->layout->disableLayout();
    }

    public function oneInListAction() {
        $this->view->spo = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function nmapImportAction() {
        $form = new Forms_ServerSoftware_NmapImport();

        if ($this->_request->isPost() and $form->file->receive()) {
            $this->_model->nmapImport(
                $form->file->getFileName(NULL, false),
                $this->_getParam('server_id'),
                $this->_getParam('all', false),
                $this->_getParam('ignore_blank', true)
            );
            if ($this->_getParam('all', false)) {
                $this->redirect(
                    '/projects/view/project_id/' . (new Servers())->get($this->_getParam('server_id'))['project_id']
                );
            } else {
                $this->redirect(
                    '/projects/view/project_id/' . (new Servers())->get($this->_getParam('server_id'))['project_id'] .
                    '#server-view-' . $this->_getParam('server_id')
                );
            }
        } else {
            $form->setServerId($this->_getParam('server_id'));
            $this->view->form = $form;
            $this->_helper->layout->disableLayout();
        }
    }

    public function viewAction() {
        $this->view->spo = $this->_model->get($this->_getParam('id'));
        $this->view->server = (new Servers())->get($this->view->spo['server_id']);
        $this->view->usersCount = (new Users())->getCountByTypeAndId("server-software", $this->_getParam('id'));
        $this->view->vulnsCount = (new Vulns())->getCountByTypeAndId("server-software", $this->_getParam('id'));
        $this->view->tasksCount = (new Tasks())->getCountByTypeAndId("server-software", $this->_getParam('id'));
        $this->view->filesCount = (new Files())->getCountByTypeAndId("server-software", $this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }
}