<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class TasksController extends CommonController {
    public function init() {
        parent::init();
        $this->_model = new Tasks();
    }

    public function addAction() {
        $form = new Forms_Tasks_Add();
        $form->setObjectId($this->_getParam('object_id'));
        $form->setType($this->_getParam('type'));
        $form->name->getValidator('Forms_Validate_Tasks_Name')->setObjectId($this->_getParam('object_id'));
        $form->name->getValidator('Forms_Validate_Tasks_Name')->setType($this->_getParam('type'));
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_Tasks_Edit();

        if ($this->_request->isPost()) {
            $form->name->getValidator('Forms_Validate_Tasks_Name')->setObjectId($this->_getParam('object_id'));
            $form->name->getValidator('Forms_Validate_Tasks_Name')->setType($this->_getParam('type'));
            $form->name->getValidator('Forms_Validate_Tasks_Name')->setExcludeId($this->_getParam('id'));
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $task = $this->_model->get($this->_getParam('id'));
            $form->populate($task->toArray());
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
        $this->_helper->layout->disableLayout();
    }

    public function ajaxListAction() {
        $this->view->paginator = $this->_model->getListPaginator(
            $this->_getParam('project_id'),
            $this->_getParam('type'),
            $this->_getParam('parent'),
            $this->_getParam('object_id'),
            $this->_getParam('search'),
            $this->_getParam('page', 1)
        );

        $this->view->hasType = (bool)$this->_getParam('type');
        $this->view->hasParent = (bool)$this->_getParam('parent');
        $this->view->hasObject = (bool)$this->_getParam('object_id');

        $this->view->cssClasses = (new TasksStatuses())->getListCssClasses();
        $this->_helper->layout->disableLayout();
    }

    public function oneInListAction() {
        $this->view->task = $this->_model->get($this->_getParam('id'));
        $this->view->cssClasses = (new TasksStatuses())->getListCssClasses();
        $this->_helper->layout->disableLayout();
    }


    public function parentsListJsonAction() {
        if ($this->_getParam('type')) {
            $this->_helper->json(
                $this->_model->getParentsPairsList(
                    $this->_getParam('project_id'),
                    $this->_getParam('type')
                )
            );
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
        }
    }

    public function objectsListJsonAction() {
        if ($this->_getParam('type')) {
            $this->_helper->json(
                $this->_model->getObjectsPairsList(
                    $this->_getParam('type'),
                    $this->_getParam('parent_id')
                )
            );
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
        }
    }

    public function viewAction() {
        $this->view->task = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

} 