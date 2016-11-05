<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class ServersControllerTests extends Tests_CommonControllerTestCase
{
    protected $_sql = 'servers.sql';
    protected $_controller = 'servers';
    
    protected function _addObjectsByCount($count) {
        for ($i = 0; $i <= $count; $i++) {
            $this->_db->query(
                "INSERT INTO `servers`
                 (`id`, `project_id`, `ip`, `name`, `os_id`, `nmap_result`, `comment`, `checked`, `when_add`, `updated`)
                 VALUES (NULL, '1', '$i$i.$i$i.$i$i.$i$i', 'Server$i', '1', NULL, 'ServerComment$i', '0',
                         UNIX_TIMESTAMP(), UNIX_TIMESTAMP())"
            );
        }
    }

    public function testAddServerFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'name' => '',
            'ip' => '3.3.3.3',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testAddServerFailBlankIp() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'name' => 'test server 3',
            'ip' => '',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testAddServerFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'name' => 'server 1',
            'ip' => '3.3.3.3',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('add', '', $postData);

        $this->assertContains($this->_t('L_THIS_SERVER_YET_EXISTS_IN_THIS_PROJECT'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testAddServerFailDublIp() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'name' => 'test server 3',
            'ip' => '1.1.1.1',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('add', '', $postData);

        $this->assertContains($this->_t('L_SERVER_WITH_THIS_IP_ALREADY_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testAddServerFailWrongIp() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'name' => 'test server 3',
            'ip' => 'a1.1.1.1a',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('add', '', $postData);

        $this->assertContains($this->_t('L_WRONG_IP'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testAddServerGood() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $oldTasksCount = $this->_db->fetchOne("SELECT COUNT(id) FROM tasks");

        $postData = [
            'name' => 'test server 3',
            'ip' => '3.3.3.3',
            'comment' => 'test comment 3',
            'os_id' => '1',
            'checked' => '0',
            'project_id' => '1'
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 3);
        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT id, project_id, checked, name, comment, os_id, ip FROM servers WHERE id = 3"
            ),
            [
                'id' => '3',
                'project_id' => '1',
                'checked' => '0',
                'name' => 'test server 3',
                'comment' => 'test comment 3',
                'os_id' => '1',
                'ip' => '3.3.3.3',
            ]
        );

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), $oldTasksCount+1);
        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT type, name, description, status, object_id FROM tasks ORDER BY id DESC LIMIT 1"
            ),
            [
                'type' => 'server',
                'name' => 'test task',
                'description' => 'test task description',
                'status' => '2',
                'object_id' => '3',
            ]
        );
    }

    public function testDeleteServer() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 1);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers WHERE name='test server 1'"), 0);
    }

    public function testEditServerFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'id' => '1',
            'name' => '',
            'ip' => '3.3.3.3',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('edit', '', $postData);

        $this->assertContains("Value is required and can't be empty", $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testEditServerFailBlankIp() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'id' => '1',
            'name' => 'test server 3',
            'ip' => '',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('edit', '', $postData);

        $this->assertContains("Value is required and can't be empty", $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testEditServerFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'id' => '1',
            'name' => 'server 2',
            'ip' => '3.3.3.3',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('add', '', $postData);

        $this->assertContains($this->_t('L_THIS_SERVER_YET_EXISTS_IN_THIS_PROJECT'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testEditServerFailDublIp() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'id' => '1',
            'name' => 'test server 3',
            'ip' => '2.2.2.2',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('edit', '', $postData);

        $this->assertContains($this->_t('L_SERVER_WITH_THIS_IP_ALREADY_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testEditServerFailWrongIp() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);

        $postData = [
            'id' => '1',
            'name' => 'test server 3',
            'ip' => 'a1.1.1.1a',
            'comment' => 'testcomment',
            'os_id' => '1',
            'project_id' => '1'
        ];
        $this->_go('edit', '', $postData);

        $this->assertContains($this->_t('L_WRONG_IP'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers"), 2);
    }

    public function testEditServerGood() {
        $postData = [
            'id' => '1',
            'name' => 'test server 3',
            'ip' => '3.3.3.3',
            'comment' => 'test comment 3',
            'os_id' => '1',
            'project_id' => '1',
            'checked' => '1'
        ];
        $this->_go('edit', '', $postData);

        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT id, project_id, checked, name, comment, os_id, ip FROM servers WHERE id = 1"
            ),
            [
                'id' => '1',
                'project_id' => '1',
                'checked' => '1',
                'name' => 'test server 3',
                'comment' => 'test comment 3',
                'os_id' => '1',
                'ip' => '3.3.3.3'
            ]
        );
    }

    public function testView() {
        $this->_testView(
            "SELECT servers.name, comment, os.name as os_name FROM servers, os WHERE os.id = servers.os_id AND servers.id=1"
        );
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT servers.name, comment, os.name as os_name FROM servers, os
             WHERE os.id = servers.os_id AND servers.id=1 ORDER BY checked DESC, name ASC LIMIT 0,8",
            "SELECT servers.name, comment, os.name as os_name FROM servers, os
             WHERE os.id = servers.os_id AND servers.id=1 ORDER BY checked DESC, name ASC LIMIT 8,8",
            'tmpparam' // Здесь должен быть project_id, но он уже подставляется в родительском методе т.к. обязателен
        );
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'tmpparam', // Здесь должен быть project_id, но он уже подставляется в родительском методе т.к. обязателен
            'server 2',
            'server 1'
        );
    }

    public function testOneInList() {
        $this->_testOneInList(
            "SELECT servers.name, comment, os.name as os_name FROM servers, os
             WHERE os.id = servers.os_id AND servers.id=1"
        );
    }

    public function testListExport() {
        $this->_testListExport("SELECT ip FROM servers ORDER BY id ASC", 'project_id');
    }

    public function testGetListJson() {
        $this->_testGetListJson("SELECT id, name FROM servers ORDER BY name ASC", "project_id");
    }

    public function testIndex() {
        $this->_testIndex();
    }

    public function testOpenEditForm() {
        $this->_testOpenEditForm("SELECT name, comment FROM servers WHERE id=1");
    }
}