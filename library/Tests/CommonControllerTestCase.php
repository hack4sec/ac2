<?php

abstract class Tests_CommonControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase {
    protected $_db;
    protected $_controller;
    protected $_sql;

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->loadSql($this->_sql);
    }

    protected function _go($action, $params = '', $postData = []) {
        if ($postData) {
            $this->getRequest()
                ->setMethod('POST')
                ->setPost($postData);
        }
        $this->dispatch("/{$this->_controller}/$action$params");

        $this->assertController($this->_controller);
        $this->assertAction($action);
    }

    public function loadSql($fileName) {
        $sqlDir = dirname(APPLICATION_PATH) . "/tests/sql/";
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $db->query("SET foreign_key_checks = 0");
        $db->query(file_get_contents("$sqlDir$fileName"));
        $db->query("SET foreign_key_checks = 1");
    }

    protected function _t($phrase) {
        return Zend_Registry::get('Zend_Translate')->translate($phrase);
    }

    protected function _testView($fieldsQuery) {
        $this->loadSql($this->_sql);
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $this->dispatch("/{$this->_controller}/view/id/1/");
        $this->assertController($this->_controller);
        $this->assertAction('view');

        foreach ($db->fetchRow($fieldsQuery) as $field) {
            $this->assertContains($field, $this->getResponse()->getBody());
        }
    }

    protected function _testAjaxListSearch($paramName, $search, $mustNotBePhrase, $additionalParams = '') {
        $this->loadSql($this->_sql);
        $this->_addObjectsByCount(10);

        $this->dispatch(
            "/{$this->_controller}/ajax-list/" .
            (strlen($additionalParams) ? "$additionalParams/" : "") .
            "$paramName/1/page/1/search/"
        );

        $this->assertController($this->_controller);
        $this->assertAction('ajax-list');

        $this->assertContains($mustNotBePhrase, $this->getResponse()->getBody());

        $this->resetRequest()->resetResponse();

        $this->dispatch(
            "/{$this->_controller}/ajax-list/" .
            (strlen($additionalParams) ? "$additionalParams/" : "") .
            "$paramName/1/page/1/search/$search"
        );

        $this->assertController($this->_controller);
        $this->assertAction('ajax-list');

        $this->assertNotContains($mustNotBePhrase, $this->getResponse()->getBody());
        $this->assertContains($search, $this->getResponse()->getBody());

    }

    protected function _testAjaxList($page1Req, $page2Req, $paramName, $additionalParams = '') {
        $this->loadSql($this->_sql);
        $this->_addObjectsByCount(10);
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $this->dispatch(
            "/{$this->_controller}/ajax-list/" .
            (strlen($additionalParams) ? "$additionalParams/" : "") .
            "$paramName/1/page/1/search/"
        );
        $this->assertController($this->_controller);
        $this->assertAction('ajax-list');

        foreach ($db->fetchAll($page1Req) as $data) {
            foreach ($data as $value) {
                $this->assertContains($value, $this->getResponse()->getBody());
            }
        }

        $this->resetRequest()->resetResponse();

        $this->dispatch(
            "/{$this->_controller}/ajax-list/" .
            (strlen($additionalParams) ? "$additionalParams/" : "") .
            "$paramName/1/page/2/search/"
        );
        $this->assertController($this->_controller);
        $this->assertAction('ajax-list');

        foreach ($db->fetchAll($page2Req) as $data) {
            foreach ($data as $value) {
                $this->assertContains($value, $this->getResponse()->getBody());
            }
        }
    }

    protected function _testOneInList($fieldsQuery) {
        $this->loadSql($this->_sql);
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $this->dispatch("/{$this->_controller}/one-in-list/id/1");
        $this->assertController($this->_controller);
        $this->assertAction('one-in-list');

        foreach ($db->fetchRow($fieldsQuery) as $field) {
            $this->assertContains($field, $this->getResponse()->getBody());
        }
    }

    protected function _testListExport($selectQuery, $paramName) {
        $this->loadSql($this->_sql);
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $this->_addObjectsByCount(10);

        $this->dispatch("/{$this->_controller}/list-export/$paramName/1/");
        $this->assertController($this->_controller);
        $this->assertAction('list-export');

        $this->assertEquals(
            implode("\n", $db->fetchCol($selectQuery)),
            trim($this->getResponse()->getBody())
        );
    }

    protected function _testParentsListJson($parentsType, $parentsQuery) {
        $this->loadSql($this->_sql);

        $this->resetRequest()->resetResponse();


        $this->dispatch("/{$this->_controller}/parents-list-json/type/$parentsType/project_id/1");
        $this->assertController($this->_controller);
        $this->assertAction('parents-list-json');

        $this->assertEquals(
            json_decode($this->getResponse()->getBody(), true),
            $this->_db->fetchPairs($parentsQuery)
        );
    }

    protected function _testObjectsListJson($objsType, $objsQuery) {
        $this->loadSql($this->_sql);

        $this->resetRequest()->resetResponse();

        $this->dispatch("/{$this->_controller}/objects-list-json/type/$objsType/parent_id/1");
        $this->assertController($this->_controller);
        $this->assertAction('objects-list-json');

        $this->assertEquals(
            json_decode($this->getResponse()->getBody(), true),
            $this->_db->fetchPairs($objsQuery)
        );
    }

    protected function _testOpenEditForm($dataQuery) {
        $this->dispatch("/{$this->_controller}/edit/id/1/");
        $this->assertController($this->_controller);
        $this->assertAction('edit');

        $testData = $this->_db->fetchRow($dataQuery);
        foreach ($testData as $value) {
            $this->assertContains($value, $this->getResponse()->getBody());
        }

        $this->assertContains("<form", $this->getResponse()->getBody());
    }

    protected function _testIndex() {
        $this->dispatch("/{$this->_controller}/index/");
        $this->assertController($this->_controller);
        $this->assertAction('index');
    }

    protected function _testGetListJson($dataQuery, $paramName) {
        $this->loadSql($this->_sql);
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $this->dispatch("/{$this->_controller}/get-list-json/$paramName/1/");
        $this->assertController($this->_controller);
        $this->assertAction('get-list-json');

        $this->assertEquals(
            json_decode($this->getResponse()->getBody(), true),
            $db->fetchPairs($dataQuery)
        );
    }
} 