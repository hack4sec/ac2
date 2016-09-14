<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class VulnsControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'vulns.sql';
    protected $_controller = 'vulns';

    protected function _addObjectsByCount($count) {
        for ($i = 0; $i <= $count; $i++) {
            $this->_db->query(
                "INSERT INTO `vulns` (`id`, `type`, `vuln_type_id`, `object_id`, `risk_level_id`, `name`, `description`, `exploit_link`, `updated`, `when_add`) VALUES
                 (NULL, 'web-app', 3, 1, 1, 'WVuln$i', 'WAbout$i', 'WLink$i', 0, 0)"
            );
        }
    }
    
    public function testAddVulnFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 4);

        $postData = [
            'type' => 'web-app',
            'vuln_type_id' => '3',
            'name' => '',
            'object_id' => '1',
            'risk_level_id' => 1,
            'comment' => 'testcomment',
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        //$this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 4);
    }

    public function testEditVulnFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 4);
        $postData = [
            'id' => '1',
            'type' => 'web-app',
            'vuln_type_id' => '3',
            'name' => '',
            'object_id' => '1',
            'risk_level_id' => 1,
            'comment' => 'testcomment',
        ];
        $this->_go('edit', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 4);
    }

    public function addVulnProvider() {
        return [
            [
                [
                    'data' => [
                        'name' => 'Vuln3',
                        'type' => 'web-app',
                        'vuln_type_id' => '4',
                        'risk_level_id' => '2',
                        'object_id' => '1',
                        'description' => 'About1 edited',
                        'exploit_link' => 'http://',
                    ],
                ],
            ],
            [
                [
                    'data' => [
                        'name' => 'Vuln4',
                        'type' => 'server-software',
                        'vuln_type_id' => '2',
                        'risk_level_id' => '1',
                        'object_id' => '3',
                        'description' => 'About2 edited',
                        'exploit_link' => 'http://example.com',
                    ],
                ],
            ],
        ];
    }
    /**
     *
     * @dataProvider addVulnProvider
     */
    public function testAddVulnGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 4);

        $this->_go('add', '', $data['data']);

        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT name, type, vuln_type_id, risk_level_id, description, exploit_link, object_id
                 FROM vulns WHERE id = 5"
            ),
            $data['data']
        );

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 5);
    }

    public function editVulnProvider() {
        return [
            [
                [
                    'data' => [
                        'id' => '1',
                        'name' => 'Vuln3',
                        'type' => 'web-app',
                        'vuln_type_id' => '4',
                        'object_id' => '1',
                        'risk_level_id' => '2',
                        'description' => 'About1 edited',
                        'exploit_link' => 'http://',
                    ],
                    'url' => 'http://ac2t/web-apps/index/id/1',
                    'vulnId' => '1'
                ],
            ],
            [
                [
                    'data' => [
                        'id' => '4',
                        'name' => 'Vuln4',
                        'type' => 'server-software',
                        'vuln_type_id' => '2',
                        'object_id' => '2',
                        'risk_level_id' => '1',
                        'description' => 'About2 edited',
                        'exploit_link' => 'http://example.com',
                    ],
                    'url' => 'http://ac2t/server-software/index/id/1',
                    'vulnId' => '4'
                ],
            ],
        ];
    }

    /**
     * @dataProvider editVulnProvider
     */
    public function testEditVulnGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 4);

        $this->_go('edit', '', $data['data']);

        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT id, name, type, vuln_type_id, risk_level_id, description, exploit_link, object_id
                 FROM vulns WHERE id = {$data['vulnId']}"
            ),
            $data['data']
        );
    }

    public function testDeleteVuln() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 4);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM vulns"), 3);
    }

    public function testColorsOfVulnsProvider() {
        return [
            [
                [
                    'type' => 'web-app',
                ],
            ],
            [
                [
                    'type' => 'server-software',
                ],
            ],
        ];
    }

    /**
     * @dataProvider testColorsOfVulnsProvider
     */
    public function testColorsOfVulns($data) {
        $this->_go('ajax-list', "/type/{$data['type']}/object_id/1");

        $this->assertEquals(substr_count($this->getResponse()->getBody(), 'rclass1'), 1);
        $this->assertEquals(substr_count($this->getResponse()->getBody(), 'rclass2'), 1);
    }

    public function testColorsOfVulnsObjectsProvider() {
        return [
            [
                [
                    'controller' => 'server-software',
                    'url' => "/server-software/ajax-list/server_id/1/page/1",
                ],
            ],
            [
                [
                    'controller' => 'web-apps',
                    'url' => "/web-apps/ajax-list/server_id/1/domain_id/1/page/1",
                ],
            ],
        ];
    }

    /**
     * @dataProvider testColorsOfVulnsObjectsProvider
     */
    public function testColorsOfVulnsObjects($data) {
        $this->_db->query("UPDATE vulns SET object_id = 2 WHERE id IN(2, 4)");


        $this->dispatch($data['url']);
        $this->assertController($data['controller']);
        $this->assertAction('ajax-list');

        $this->assertEquals(substr_count($this->getResponse()->getBody(), 'rclass1'), 1);
        $this->assertEquals(substr_count($this->getResponse()->getBody(), 'rclass2'), 1);
    }

    public function testView() {
        $this->_testView("SELECT name, description, exploit_link FROM vulns WHERE id=1");
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT name, description, exploit_link FROM vulns WHERE `type`='web-app' AND object_id=1 ORDER BY sort DESC LIMIT 0,8",
            "SELECT name, description, exploit_link FROM vulns WHERE `type`='web-app' AND object_id=1 ORDER BY sort DESC LIMIT 8,8",
            'object_id',
            'type/web-app'
        );
    }

    public function testAjaxList2() {
        $this->_testAjaxList(
            "SELECT name, description, exploit_link FROM vulns WHERE `type`='server-software' AND object_id=1 ORDER BY sort DESC LIMIT 0,8",
            "SELECT name, description, exploit_link FROM vulns WHERE `type`='server-software' AND object_id=1 ORDER BY sort DESC LIMIT 8,8",
            'object_id',
            'type/server-software'
        );
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'object_id',
            'Vuln2',
            'Vuln1',
            'type/web-app'
        );
    }

    public function testOneInList() {
        $this->_testOneInList("SELECT name, description, exploit_link FROM vulns WHERE id=1");
    }

    public function testParentsListJson() {
        $this->_testParentsListJson('web-app', "SELECT id, name FROM domains ORDER BY name ASC");
        $this->_testParentsListJson('server-software', "SELECT id, name FROM servers ORDER BY name ASC");
    }

    public function testObjectsListJson() {
        $this->_testObjectsListJson('web-app', "SELECT id, name FROM web_apps ORDER BY name ASC");
        $this->_testObjectsListJson('server-software', "SELECT id, name FROM servers_software ORDER BY name ASC");
    }
}

