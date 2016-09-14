<?php
/**
 * @package Analytical Center 2
 * @see for US http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (us)
 * @author Anton Kuzmin
 */
class NotesController extends Zend_Controller_Action {
    public function init() {
        parent::init();
        $this->_model = new Notes();
    }

    public function countAction() {
        print $this->_model->getCountByProjectId($this->_getParam('project_id'));
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function getListAction() {
        $this->view->notes = $this->_model->getListByProjectId($this->_getParam('project_id'));
        $this->_helper->layout->disableLayout();
    }

    public function deleteAction() {
        $this->_model->get($this->_getParam('id'))->delete();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function getOneAction() {
        $this->view->note = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function saveAction() {
        $note = $this->_model->get($this->_getParam('id'));
        $note->content = $this->_getParam('content');
        $note->save();

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function addAction() {
        $note = $this->_model->createRow();
        $note->content = $this->_getParam('content');
        $note->project_id = $this->_getParam('project_id');
        $note->save();

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }
}