<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class DomainsControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'domains.sql';
    protected $_controller = 'domains';
    
    protected function _addObjectsByCount($count) {
        for ($i = 0; $i < $count; $i++) {
            $this->_db->query(
                "INSERT INTO `domains` (`id`, `server_id`, `name`, `checked`, `comment`, `updated`, `when_add`)
                 VALUES (NULL, 1, 'Domain$i', 0, 'Comment$i', 0, 123);"
            );
        }
    }

    public function testAddDomainFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 2);

        $this->_go('add', '', ['name' => '', 'server_id' => '1', 'comment' => 'testcomment',]);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 2);
    }

    public function testAddDomainFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 2);

        $this->_go('add', '', ['name' => 'domain 1', 'server_id' => '1', 'comment' => 'testcomment',]);

        $this->assertContains($this->_t('L_DOMAIN_YET_EXISTS_ON_SERVER'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 2);
    }

    public function testAddDomainGood() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 2);

        $this->_go('add', '', ['name' => 'domain 3', 'server_id' => '1', 'comment' => 'testcomment',]);

        $this->assertEquals("", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 3);
        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT id, checked, name, comment, server_id FROM domains WHERE id = 3"
            ),
            [
                'id' => '3',
                'checked' => '0',
                'name' => 'domain 3',
                'comment' => 'testcomment',
                'server_id' => '1'
            ]
        );
    }

    public function testEditDomainFailBlankName() {
        $this->_go('edit', '', ['id' => '1', 'name' => '', 'server_id' => '1', 'comment' => 'testcomment']);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());
    }

    public function testEditDomainFailDublName() {
        $this->_go('edit', '', ['id' => '1', 'name' => 'domain 2', 'server_id' => '1', 'comment' => 'testcomment']);

        $this->assertContains($this->_t('L_DOMAIN_YET_EXISTS_ON_SERVER'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());
    }

    public function testEditDomainGood() {
        $this->_go('edit', '', ['id' => '1', 'name' => 'domain 3', 'server_id' => '1', 'comment' => 'testcomment', 'checked' => 1]);

        $this->assertEquals("", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 2);
        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT id, checked, when_add, name, comment, server_id FROM domains WHERE id = 1"
            ),
            [
                'id' => '1',
                'when_add' => '123',
                'checked' => '1',
                'name' => 'domain 3',
                'comment' => 'testcomment',
                'server_id' => '1'
            ]
        );
    }

    public function testDeleteDomain() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 2);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains"), 1);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM domains WHERE name='comment domain 1'"), 0);
    }

    public function testGetListJson() {
        $this->_testGetListJson("SELECT id, name FROM domains ORDER BY name ASC", "server_id");
    }

    public function testIndex() {
        $this->_go('index', '/project_id/1/');

        $servers = $this->_db->fetchPairs("SELECT id, name FROM servers WHERE project_id = 1 ORDER BY name ASC");
        foreach ($servers as $serverId => $serverName) {
            $this->assertContains("<option value=\"$serverId\">$serverName</option>", $this->getResponse()->getBody());
        }
    }

    public function testView() {
        $this->_testView("SELECT name, comment FROM domains WHERE id=1");
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT name, comment FROM domains ORDER BY name ASC LIMIT 0,8",
            "SELECT name, comment FROM domains ORDER BY name ASC LIMIT 8,8",
            'server_id'
        );
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'server_id',
            'domain 2',
            'domain 1'
        );
    }

    public function testOneInList() {
        $this->_testOneInList("SELECT name, comment FROM domains WHERE id=1", "server_id");
    }

    public function testListExport() {
        $this->_testListExport("SELECT name FROM domains ORDER BY id DESC", 'server_id');
    }

    public function testOpenEditForm() {
        $this->_testOpenEditForm("SELECT name, comment FROM domains WHERE id=1");
    }
}

