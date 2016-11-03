<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class ProjectsController extends CommonController {
    public function init() {
        parent::init();
        $this->_model = new Projects();
        $this->_helper->layout->setLayout('layout-projects');
    }

    public function indexAction() {
        $this->view->projects = $this->_model->getList();
    }

    public function addAction() {
        $form = new Forms_Projects_Add();
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_Projects_Edit();
        if ($this->_request->isPost()) {
            $form->name->getValidator('Forms_Validate_Projects_Name')->setExcludeId($this->_getParam('id'));
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $project = $this->_model->get($this->_getParam('id'));
            $form->populate($project->toArray());
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
        $this->view->project = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function hashesLoadAction() {
        $result = (new Hashes())->loadManyFromFile(
            $this->_getParam('list_type'),
            $this->_getParam('alg'),
            $this->_getParam('delimiter'),
            $_FILES['file']['tmp_name']
        );
        $this->view->result = $result;
        $this->_helper->layout->setLayout('layout-pairsload');
    }

    public function viewAction() {
        $this->_helper->layout->setLayout('layout-project-view');
        $this->view->project = $this->_model->get($this->_getParam('project_id'));
    }

    public function menuAction() {
        $this->view->serversCount = (new Servers())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->domainsCount = (new Domains())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->spoCount     = (new Servers_Software())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->webAppsCount = (new WebApps())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->vulnsCount   = (new Vulns())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->filesCount   = (new Files())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->tasksCount   = (new Tasks())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->tasksTemplatesCount   = (new TasksTemplates())->getCountByProjectId($this->_getParam('project_id'));
        $this->view->usersCount   = (new Users())->getCountByProjectId($this->_getParam('project_id'));

        $this->view->projectId = $this->_getParam('project_id');

        $this->_helper->layout->disableLayout();
    }
} 