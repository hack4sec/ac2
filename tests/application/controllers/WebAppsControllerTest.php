<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class WebAppsControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'web-apps.sql';
    protected $_controller = 'web-apps';
    //TODO валидатор повтора и на урлы?
    //TODO web_app when_add
    //TODO ошибки искать в ответе в нужном кол-ве,а то иногда забытые поля дают 2 empty например

    protected function _addObjectsByCount($count) {
        for ($i = 0; $i <= $count; $i++) {
            $this->_db->query(
                "INSERT INTO `web_apps` (`id`, `domain_id`, `name`, `url`, `version`, `version_unknown`, `version_old`,
                  `vendor_site`, `need_auth`, `url_rewrite`, `ghost`, `checked`, `comment`, `updated`) VALUES
                 (NULL, 1, 'NWebApp$i', '/', '1.$i', 0, 0, '', 0, 0, 0, 0, 'TestComment$i', 0)"
            );
        }
    }

    public function testAddWebAppFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);

        $this->_go('add', '', ['name' => '','domain_id' => '1','comment' => 'testcomment', 'url' => '/']);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);
    }

    public function testAddWebAppFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);

        $this->_go('add', '', ['name' => 'WebApp1','domain_id' => '1','comment' => 'testcomment', 'url' => '/']);

        $this->assertContains($this->_t('L_WEBAPP_YET_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);
    }

    public function testAddWebAppGood() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);

        $oldTasksCount = $this->_db->fetchOne("SELECT COUNT(id) FROM tasks");

        $postData = [
            'name' => 'WebApp3',
            'url' => '/',
            'domain_id' => '1',
            'comment' => 'testcomment',
            'version' => '1.0',
            'version_unknown' => '0',
            'version_old' => '1',
            'comment' => 'comment',
            'vendor_site' => 'http://',
            'need_auth' => '1',
            'url_rewrite' => '1',
            'ghost' => '1',
            'checked' => '0',
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT domain_id, name, url, version, version_unknown, version_old,
                        vendor_site, need_auth, url_rewrite, ghost, checked, comment
                 FROM web_apps WHERE id = 3"
            ),
            [
                'name' => 'WebApp3',
                'domain_id' => '1',
                'url' => '/',
                'version' => '1.0',
                'version_unknown' => '0',
                'version_old' => '1',
                'comment' => 'comment',
                'vendor_site' => 'http://',
                'need_auth' => '1',
                'url_rewrite' => '1',
                'ghost' => '1',
                'checked' => '0',
            ]
        );

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 3);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), $oldTasksCount+1);
        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT type, name, description, status, object_id FROM tasks ORDER BY id DESC LIMIT 1"
            ),
            [
                'type' => 'web-app',
                'name' => 'test task',
                'description' => 'test task description',
                'status' => '2',
                'object_id' => '3',
            ]
        );
    }

    public function testEditWebAppFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);

        $this->_go('edit', '', ['name' => '', 'id' => '1', 'domain_id' => '1', 'comment' => 'testcomment', 'url' => '/']);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);
    }

    public function testEditWebAppFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);

        $this->_go('edit', '', ['name' => 'WebApp2', 'id' => '1', 'domain_id' => '1', 'comment' => 'testcomment', 'url' => '/']);

        $this->assertContains($this->_t('L_WEBAPP_YET_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);
    }

    public function testEditWebAppGood() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);
        $postData = [
            'id' => '1',
            'name' => 'WebApp3',
            'url' => '/',
            'domain_id' => '1',
            'comment' => 'testcomment',
            'version' => '1.0',
            'version_unknown' => '0',
            'version_old' => '1',
            'comment' => 'comment',
            'vendor_site' => 'http://',
            'need_auth' => '1',
            'url_rewrite' => '1',
            'ghost' => '1',
            'checked' => '0',
        ];
        $this->_go('edit', '', $postData);

        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT domain_id, name, url, version, version_unknown, version_old,
                        vendor_site, need_auth, url_rewrite, ghost, checked, comment
                 FROM web_apps WHERE id = 1"
            ),
            [
                'name' => 'WebApp3',
                'domain_id' => '1',
                'url' => '/',
                'version' => '1.0',
                'version_unknown' => '0',
                'version_old' => '1',
                'comment' => 'comment',
                'vendor_site' => 'http://',
                'need_auth' => '1',
                'url_rewrite' => '1',
                'ghost' => '1',
                'checked' => '0',
            ]
        );
    }
    //TODO объединить
    public function testDeleteApp() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 2);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps"), 1);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM web_apps WHERE name='comment domain 1'"), 0);
    }

    public function testView() {
        $this->_testView("SELECT name, version, url, comment FROM web_apps WHERE id=1");
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT name, version, comment, url FROM web_apps ORDER BY name ASC LIMIT 0,8",
            "SELECT name, version, comment, url FROM web_apps ORDER BY name ASC LIMIT 8,8",
            'domain_id'
        );
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'domain_id',
            'WebApp2',
            'WebApp1'
        );
    }

    public function testOneInList() {
        $this->_testOneInList("SELECT name, version, url, comment FROM web_apps WHERE id=1");
    }

    public function testIndex() {
        $this->_go('index', '/project_id/1/');

        $servers = $this->_db->fetchPairs("SELECT id, name FROM servers WHERE project_id = 1 ORDER BY name ASC");
        foreach ($servers as $serverId => $serverName) {
            $this->assertContains("<option value=\"$serverId\">$serverName</option>", $this->getResponse()->getBody());
        }
    }
}

