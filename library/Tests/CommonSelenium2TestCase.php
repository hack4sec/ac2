<?php

abstract class Tests_CommonSelenium2TestCase extends PHPUnit_Extensions_Selenium2TestCase {
    protected $_db;
    protected $_tearDownChecks = true;

    public function setUp()
    {
        parent::setUp();
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://www.google.com/');

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
        $dbConf = $config->toArray()['testing']['resources']['db'];
        $this->_db = Zend_Db::factory($dbConf['adapter'], $dbConf['params']);

    }

    public function loadSql($fileName) {
        $sqlDir = dirname(APPLICATION_PATH) . "/tests/sql/";
        $this->_db->query("SET foreign_key_checks = 0");
        $this->_db->query(file_get_contents("$sqlDir$fileName"));
        $this->_db->query("SET foreign_key_checks = 1");
    }

    public function printInElement($elId, $text, $clean = True) {
        if ($clean) {
            $this->byId($elId)->clear();
        }
        $this->clickOnElement($elId);
        $this->keys($text);
    }

    public function elementExistsById($id) {
        try {
            $this->byId($id);
        } catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
        return true;
    }

    public function clickWithSleep($elId, $sleep = 1) {
        $this->clickOnElement($elId);
        sleep($sleep);
    }

    public function url($url=NULL) {
        parent::url($url);

        $this->currentWindow()->maximize();
        sleep(1);
    }

    protected function tearDown() {
        parent::tearDown();
        if ($this->_tearDownChecks) {
            $this->assertFalse(substr_count($this->source(), " in ") && substr_count($this->source(), " on line "), "PHP Error on page");
            $this->assertTrue($this->elementExistsById("errorlog"));
            $this->assertEquals($this->byId("errorlog")->attribute("innerHTML"), "", $this->byId("errorlog")->attribute("innerHTML"));
        }
    }

    protected function _t($phrase) {
        return Zend_Registry::get('Zend_Translate')->translate($phrase);
    }

    abstract protected function _openTestList();
    abstract protected function _addObjectsByCount($count);

    public function _testPagination($reqTestObjectPage1, $reqTestObjectPage2) {
        $this->loadSql($this->_sql);
        $this->_openTestList();
        $this->assertNotContains("changePage(2)", $this->source());
        $this->_addObjectsByCount(10);

        $this->_openTestList();

        $this->assertContains("changePage(2)", $this->source());
        $this->elementExistsById("page2");

        $testObjects = $this->_db->fetchPairs($reqTestObjectPage1);
        foreach ($testObjects as $testName => $testComment) {
            $this->assertContains($testName, $this->source());
            $this->assertContains($testComment, $this->source());
        }

        $this->clickWithSleep('page2');

        $testObjects = $this->_db->fetchPairs($reqTestObjectPage2);
        foreach ($testObjects as $testName => $testComment) {
            $this->assertContains($testName, $this->source());
            $this->assertContains($testComment, $this->source());
        }
    }
}