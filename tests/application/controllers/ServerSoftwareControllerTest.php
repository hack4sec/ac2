<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class ServerSoftwareControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'server-software.sql';
    protected $_controller = 'server-software';

    protected function _addObjectsByCount($count) {
        for ($i = 0; $i <= $count; $i++) {
            $this->_db->query(
                "INSERT INTO `servers_software`
                 (`id`, `server_id`, `name`, `version`, `version_unknown`, `version_old`, `vendor_site`, `banner`,
                 `proto`, `port`, `ghost`, `checked`, `comment`, `updated`, `when_add`) VALUES
                 (0, 1, 'PO$i', '', 1, 0, 'http://apache.org', '', 'tcp', 80, 0, 0, 'POComment$i', 0, 0)"
            );
        }
    }
    
    public function testAddSpoFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);

        $this->_go('add', '', ['name' => '', 'server_id' => '1', 'comment' => 'testcomment']);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);
    }

    public function testAddSpoFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);

        $this->_go('add', '', ['name' => 'Apache', 'server_id' => '1', 'comment' => 'testcomment']);

        $this->assertContains($this->_t('L_SPO_YET_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);
    }

    public function testAddSpoGood() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);

        $oldTasksCount = $this->_db->fetchOne("SELECT COUNT(id) FROM tasks");

        $postData = [
            'name' => 'Test',
            'server_id' => '1',
            'comment' => 'testcomment',
            'version' => '1.0a',
            'version_unknown' => '0',
            'version_old' => '1',
            'vendor_site' => 'http://example.com/',
            'banner' => 'spo banner',
            'proto' => 'udp',
            'port' => '21',
            'ghost' => '0',
            'checked' => '0',
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 3);

        $this->assertEquals(
            $this->_db->fetchRow('SELECT id,
                            name, server_id, comment, version, version_unknown, version_old, vendor_site, proto,
                            banner, port, ghost, checked
                           FROM servers_software WHERE id=3
                '),
                [
                    'id' => '3',
                    'name' => 'Test',
                    'server_id' => '1',
                    'comment' => 'testcomment',
                    'version' => '1.0a',
                    'version_unknown' => '0',
                    'version_old' => '1',
                    'vendor_site' => 'http://example.com/',
                    'proto' => 'udp',
                    'banner' => 'spo banner',
                    'port' => '21',
                    'ghost' => '0',
                    'checked' => '0',
                ]
        );

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), $oldTasksCount+1);
        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT type, name, description, status, object_id FROM tasks ORDER BY id DESC LIMIT 1"
            ),
            [
                'type' => 'server-software',
                'name' => 'test task',
                'description' => 'test task description',
                'status' => '2',
                'object_id' => '3',
            ]
        );
    }

    public function testEditSpoFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);

        $this->_go('edit', '', ['id' => '1', 'name' => '', 'server_id' => '1', 'comment' => 'testcomment']);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);
    }

    public function testEditSpoFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);

        $this->_go('edit', '', ['id' => '1', 'name' => 'MySQL', 'server_id' => '1', 'comment' => 'testcomment']);

        $this->assertContains($this->_t('L_SPO_YET_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);
    }

    public function testEditSpoGood() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);

        $postData = [
            'id' => '1',
            'name' => 'Apache2',
            'server_id' => '1',
            'version' => '1.0a',
            'comment' => 'testcomment',
            'version_unknown' => '1',
            'version_old' => '0',
            'vendor_site' => 'http://example.com/',
            'banner' => 'spo banner',
            'port' => '22',
            'proto' => 'udp',
            'ghost' => '1',
            'checked' => '1',
        ];
        $this->_go('edit', '', $postData);

        $this->assertEquals(
            $this->_db->fetchRow('SELECT id,
                            name, server_id, comment, version, version_unknown, version_old, vendor_site, proto,
                            banner, port, ghost, checked
                           FROM servers_software WHERE id=1
                '),
            [
                'id' => '1',
                'name' => 'Apache2',
                'server_id' => '1',
                'comment' => 'testcomment',
                'version' => '1.0a',
                'version_unknown' => '1',
                'version_old' => '0',
                'vendor_site' => 'http://example.com/',
                'banner' => 'spo banner',
                'port' => '22',
                'proto' => 'udp',
                'ghost' => '1',
                'checked' => '1',
            ]
        );
    }

    public function testDelSpo() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 2);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM servers_software"), 1);
    }

    public function testView() {
        $this->_testView("SELECT name, version, comment FROM servers_software WHERE id=1");
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT name, comment FROM servers_software ORDER BY name ASC LIMIT 0,8",
            "SELECT name, comment FROM servers_software ORDER BY name ASC LIMIT 8,8",
            'server_id'
        );
    }

    public function testAjaxListWoServer() {
        $this->_testAjaxList(
            "SELECT name, comment FROM servers_software ORDER BY name ASC LIMIT 0,8",
            "SELECT name, comment FROM servers_software ORDER BY name ASC LIMIT 8,8",
            'tmpparam'
        );
        $this->assertContains("[server 1]", $this->getResponse()->getBody());
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'server_id',
            'MySQL',
            'Apache'
        );
    }

    public function testOneInList() {
        $this->_testOneInList("SELECT name, version, comment FROM servers_software WHERE id=1");
    }

    public function testOpenEditForm() {
        $this->_testOpenEditForm("SELECT name, version, comment FROM servers_software WHERE id=1");
    }

    //FIXME этих много, объединить
    public function testIndex() {
        $this->_go('index', '/project_id/1/');

        $servers = $this->_db->fetchPairs("SELECT id, name FROM servers WHERE project_id = 1 ORDER BY name ASC");
        foreach ($servers as $serverId => $serverName) {
            $this->assertContains("<option value=\"$serverId\">$serverName</option>", $this->getResponse()->getBody());
        }
    }
}

