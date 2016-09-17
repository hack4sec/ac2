<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class FilesController extends Zend_Controller_Action {
    public function init() {
        parent::init();
        $this->_model = new Files();
    }

    public function addAction() {
        $form = new Forms_Files_Add();
        $form->setObjectId($this->_getParam('object_id'));
        $form->setType($this->_getParam('type'));
        if ($this->_request->isPost() and $form->isValid($_POST) and $form->file->receive()) {
            $_POST['name'] = $form->file->getFileName(NULL, false);
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_Files_Edit();

        if ($this->_request->isPost()) {
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            }
        } else {
            $file = $this->_model->get($this->_getParam('id'));
            $form->populate($file->toArray());
        }
        $this->view->form = $form;
        $this->_helper->layout->disableLayout();
    }

    public function deleteAction() {
        $this->_model->get($this->_getParam('id'))->delete();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function downloadAction() {
        $config = Zend_Registry::get('config');
        $file = $this->_model->get($this->_getParam('id'));
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', 'application/unknown;charset=utf-8', true)
            ->setHeader('Content-Disposition', "attachment; filename={$file['name']}")
            ->setHeader('Content-Length', filesize($config->paths->storage . "/" . $file['hash']))
            ->clearBody();
        $this->getResponse()->sendHeaders();

        //readfile($config->paths->store . "/" . $file['hash']);
        $fh = fopen($config->paths->storage . "/" . $file['hash'], 'rb');
        while(!feof($fh)) {
            print fread($fh, 1024);
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        $this->_helper->layout->disableLayout();
    }

    public function ajaxListAction() {
        if ($this->_getParam('type')) {
            $this->view->paginator = $this->_model->getListPaginator(
                $this->_getParam('type'),
                $this->_getParam('object_id'),
                $this->_getParam('search'),
                $this->_getParam('page', 1)
            );
        }
        $this->_helper->layout->disableLayout();
    }

    public function oneInListAction() {
        $this->view->file = $this->_model->get($this->_getParam('id'));
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

    public function viewAction() {
        $this->view->file = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }
} 