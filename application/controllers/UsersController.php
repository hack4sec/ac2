<?php
/**
 * @package Analytical Center 2
 * @see for US http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (us)
 * @author Anton Kuzmin
 */
class UsersController extends Zend_Controller_Action
{
    public function init() {
        parent::init();
        $this->_model = new Users();
    }

    public function ajaxListAction() {
        if ($this->_getParam('group_id')) {
            $this->view->paginator = $this->_model->getListPaginator(
                $this->_getParam('group_id'),
                $this->_getParam('search'),
                $this->_getParam('page', 1)
            );
        }

        $this->_helper->layout->disableLayout();
    }

    public function addAction() {
        $form = new Forms_Users_Add();

        $form->setGroupInfo($this->_getParam('group_id'), $this->_getParam('object_id'), $this->_getParam('type'));
        if ($this->_getParam('group_id')) {
            $form->login->getValidator('Forms_Validate_Users_Name')->setGroupId($this->_getParam('group_id'));
        }
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_Users_Edit();
        if ($this->_request->isPost()) {
            $form->elsByGroupId($this->_getParam('group_id'));
            $form->login->getValidator('Forms_Validate_Users_Name')->setGroupId($this->_getParam('group_id'));
            $form->login->getValidator('Forms_Validate_Users_Name')->setExcludeId($this->_getParam('id'));
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $user = $this->_model->getFullData($this->_getParam('id'));
            $form->populate($user);
        }
        $this->view->form = $form;
        $this->_helper->layout->disableLayout();
    }

    public function addGroupAction() {
        $Groups = new Users_Groups();
        $form = new Forms_Users_Groups_Add();
        $form->setObjectId($this->_getParam('object_id'));
        $form->setType($this->_getParam('type'));
        $form->name->getValidator('Forms_Validate_Users_Groups_Name')->setObjectId($this->_getParam('object_id'));
        $form->name->getValidator('Forms_Validate_Users_Groups_Name')->setType($this->_getParam('type'));
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $Groups->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }
    public function editGroupAction() {
        $Groups = new Users_Groups();
        $form = new Forms_Users_Groups_Edit();

        $form->name->getValidator('Forms_Validate_Users_Groups_Name')->setExcludeId($this->_getParam('id'));
        if ($this->_request->isPost()) {
            $form->name->getValidator('Forms_Validate_Users_Groups_Name')->setObjectId($this->_getParam('object_id'));
            $form->name->getValidator('Forms_Validate_Users_Groups_Name')->setType($this->_getParam('type'));
            $form->name->getValidator('Forms_Validate_Users_Groups_Name')->setExcludeId($this->_getParam('id'));
            if ($form->isValid($_POST)) {
                $Groups->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $user = $Groups->get($this->_getParam('id'));
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

    public function deleteGroupAction() {
        $Groups = new Users_Groups();
        $Groups->get($this->_getParam('id'))->delete();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function exportAction() {
        $form = new Forms_Users_Export();
        $form->setGroupId($this->_getParam('group_id'));
        if ($this->_request->isPost()) {
            $this->getResponse()
                 ->setHttpResponseCode(200)
                 ->setHeader('Pragma', 'public', true)
                 ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                 ->setHeader('Content-type', 'application/unknown;charset=utf-8', true)
                 ->setHeader('Content-Disposition', "attachment; filename=users-export.txt")
                 ->clearBody();
            $this->getResponse()->sendHeaders();

            $this->_model->printExportList($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        }
        $this->view->form = $form;
        $this->_helper->layout->disableLayout();
    }

    public function importAction() {
        $form = new Forms_Users_Import();
        $form->setGroupId($this->_getParam('group_id'));
        if ($this->_request->isPost() and $form->file->receive()) {
            $this->_model->importFromFile($form->file->getFileName(NULL, false), $_POST, $this->_getParam('group_id'));
            exit;
        }
        $this->view->form = $form;
        $this->_helper->layout->disableLayout();
    }

    public function pairsLoadAction() {
        $form = new Forms_Users_Pairs();
        $this->view->loaded = false;
        if ($this->_request->isPost() and $form->file->receive()) {
            $this->view->results = $this->_model->pairsLoad($form->file->getFileName(NULL, false), $_POST);
            $this->view->loaded = true;
        } else {
            $this->view->form = $form;
            $this->_helper->layout->disableLayout();
        }
    }

    public function oneInListAction() {
        $this->view->user = $this->_model->get($this->_getParam('id'));
        $this->view->type = (new Users_Groups())->get($this->view->user->group_id)->type;
        $this->_helper->layout->disableLayout();
    }

    public function viewAction() {
        $this->view->user = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function notFoundHashesExportAction() {
        $arch = $this->_model->notFoundHashesExportZip();
        if ($arch) {
            $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Pragma', 'public', true)
                ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                ->setHeader('Content-type', 'application/unknown;charset=utf-8', true)
                ->setHeader('Content-Disposition', "attachment; filename=hashes.zip")
                ->clearBody();
            $this->getResponse()->sendHeaders();
            readfile($arch);
        } else {
            throw new Exception("No hashes for export");
        }

        exit(0);
    }

    public function groupsListJsonAction() {
        $this->_helper->json(
            (new Users_Groups())->getPairsListByTypeAndObjectId(
                $this->_getParam('type'),
                $this->_getParam('object_id')
            )
        );
    }

    public function indexAction() {
        $this->_helper->layout->disableLayout();
    }

    public function parentsListJsonAction() {
        $this->_helper->json(
            $this->_model->getParentsPairsList(
                $this->_getParam('project_id'),
                $this->_getParam('type')
            )
        );
    }

    public function objectsListJsonAction() {
        $this->_helper->json(
            $this->_model->getObjectsPairsList(
                $this->_getParam('type'),
                $this->_getParam('parent_id')
            )
        );
    }
}