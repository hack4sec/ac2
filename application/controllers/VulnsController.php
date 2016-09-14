<?php
/**
 * @package Analytical Center 2
 * @see for US http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (us)
 * @author Anton Kuzmin
 */
class VulnsController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_model = new Vulns();
    }

    public function addAction() {
        $form = new Forms_Vulns_Add();
        $form->setObjectId($this->_getParam('object_id'));
        $form->setType($this->_getParam('type'));
        $form->addVulnTypeSelect($this->_getParam('type'));
        if ($this->_request->isPost() and $form->isValid($_POST)) {
            $this->_model->add($_POST);
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->form = $form;

        }
        $this->_helper->layout->disableLayout();
    }

    public function editAction() {
        $form = new Forms_Vulns_Edit();

        if ($this->_request->isPost()) {
            if ($form->isValid($_POST)) {
                $this->_model->edit($_POST);
                $this->_helper->viewRenderer->setNoRender(true);
            } else {
                $form->addVulnTypeSelect($this->_getParam('type'));
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

        $this->view->risks = (new RiskLevels())->getListCssClasses();
        $this->view->types = (new Vulns_Types())->getListByType($this->_getParam('type'));
        $this->_helper->layout->disableLayout();
    }

    public function viewAction() {
        $this->view->vuln = $this->_model->get($this->_getParam('id'));
        $this->_helper->layout->disableLayout();
    }

    public function oneInListAction() {
        $this->view->vuln = $this->_model->get($this->_getParam('id'));
        $this->view->risks = (new RiskLevels())->getListCssClasses();
        $this->view->types = (new Vulns_Types())->getListByType($this->view->vuln['type']);
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