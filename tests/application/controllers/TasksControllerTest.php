<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class TasksControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'tasks.sql';
    protected $_controller = 'tasks';

    protected function _addObjectsByCount($count) {
        for ($i = 0; $i <= $count; $i++) {
            $this->_db->query(
                "INSERT INTO `tasks` (`id`, `object_id`, `type`, `name`, `description`, `status`, `updated`, `when_add`) VALUES
                 (NULL, 1, 'server', 'Task$i', 'Comment$i', 2, 0, 0)"
            );
        }
    }

    public function testAddTaskFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);

        $postData = [
            'name' => '',
            'status' => '1',
            'type' => 'server',
            'object_id' => '1',
            'description' => 'test description',
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);

        $this->assertContains("test description", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);
    }

    public function testAddTaskFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);

        $postData = [
            'name' => 'task 1',
            'status' => '1',
            'type' => 'server',
            'object_id' => '1',
            'description' => 'test description',
        ];
        $this->_go('add', '', $postData);

        $this->assertContains($this->_t("L_TASK_YET_EXISTS"), $this->getResponse()->getBody());
        $this->assertContains("test description", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);
    }

    public function addTaskProvider() {
        return [
            [
                'data' => [
                    'name' => 'task 11',
                    'status' => '1',
                    'type' => 'server',
                    'object_id' => '1',
                    'description' => 'test description',
                ]
            ],
            [
                'data' => [
                    'name' => 'task 11',
                    'status' => '1',
                    'type' => 'server-software',
                    'object_id' => '1',
                    'description' => 'test description',
                ]
            ],
            [
                'data' => [
                    'name' => 'task 11',
                    'status' => '1',
                    'type' => 'web-app',
                    'object_id' => '1',
                    'description' => 'test description',
                ]
            ],
        ];
    }
    /**
     * @dataProvider addTaskProvider
     */
    public function testAddTaskGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);

        $this->_go('add', '', $data);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 11);
        $this->assertEquals(
            $this->_db->fetchRow("SELECT name, type, object_id, description, status FROM tasks WHERE id = 11"),
            $data
        );
    }

    public function testEditTaskFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);

        $postData = [
            'id' => '1',
            'name' => '',
            'status' => '1',
            'type' => 'server',
            'object_id' => '1',
            'description' => 'test description',
        ];
        $this->_go('edit', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("test description", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);
    }

    public function testEditTaskFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);

        $postData = [
            'id' => '1',
            'name' => 'task 2',
            'status' => '1',
            'type' => 'server',
            'object_id' => '1',
            'description' => 'test description',
        ];
        $this->_go('edit', '', $postData);

        $this->assertContains($this->_t("L_TASK_YET_EXISTS"), $this->getResponse()->getBody());
        $this->assertContains("test description", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);
    }

    public function editTaskProvider() {
        return [
            [
                'data' =>  [
                    'id' => '1',
                    'name' => 'task 3',
                    'status' => '1',
                    'type' => 'server',
                    'object_id' => '1',
                    'description' => 'test description',
                ]
            ],
            [
                'data' =>  [
                    'id' => '1',
                    'name' => 'task 3',
                    'status' => '1',
                    'type' => 'server-software',
                    'object_id' => '3',
                    'description' => 'test description',
                ]
            ],
            [
                'data' =>  [
                    'id' => '1',
                    'name' => 'task 3',
                    'status' => '1',
                    'type' => 'web-app',
                    'object_id' => '5',
                    'description' => 'test description',
                ]
            ],
        ];
    }

    /**
     * @dataProvider editTaskProvider
     */
    public function testEditTaskGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);

        $this->_go('edit', '', $data);

        $this->assertEquals(
            $this->_db->fetchRow("SELECT id, name, type, object_id, description, status FROM tasks WHERE id = " . $data['id']),
            $data
        );
    }

    public function testDeleteTask() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 10);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks"), 9);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM tasks WHERE name='comment task 1'"), 0);
    }

    public function testParentsListJson() {
        $this->_testParentsListJson('web-app', "SELECT id, name FROM domains ORDER BY name ASC");
        $this->_testParentsListJson('server-software', "SELECT id, name FROM servers ORDER BY name ASC");
        $this->_testParentsListJson('domain', "SELECT id, name FROM servers ORDER BY name ASC");
    }

    public function testObjectsListJson() {
        $this->_testObjectsListJson('web-app', "SELECT id, name FROM web_apps ORDER BY name ASC");
        $this->_testObjectsListJson('server-software', "SELECT id, name FROM servers_software ORDER BY name ASC");
        $this->_testObjectsListJson('domain', "SELECT id, name FROM domains ORDER BY name ASC");
        $this->_testObjectsListJson('server', "SELECT id, name FROM servers ORDER BY name ASC");
    }

    public function testOpenEditForm() {
        $this->_testOpenEditForm("SELECT name, description FROM tasks WHERE id=1");
    }


    public function testView() {
        $this->_testView("SELECT name, description FROM tasks WHERE id=1");
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT name, description FROM tasks WHERE object_id = 1 AND type='server' ORDER BY status ASC, name ASC LIMIT 0,8",
            "SELECT name, description FROM tasks WHERE object_id = 1 AND type='server' ORDER BY status ASC, name ASC LIMIT 8,8",
            'object_id',
            'type/server'
        );
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'object_id',
            'task 1',
            'task 2',
            'type/server'
        );
    }

    public function testAjaxListWoObject() {
        $this->_testAjaxList(
            "SELECT name, description FROM tasks WHERE `type`='server-software' AND object_id=1 ORDER BY name ASC LIMIT 0,8",
            "SELECT name, description FROM tasks WHERE `type`='server-software' AND object_id=1 ORDER BY name ASC LIMIT 8,8",
            'tmpparam',
            'type/server-software'
        );
        $this->assertContains("[server 1]", $this->getResponse()->getBody());
    }

    public function testAjaxListWoObjectWServer() {
        $this->_testAjaxList(
            "SELECT name, description FROM tasks WHERE `type`='web-app' AND object_id=1 ORDER BY name ASC LIMIT 0,8",
            "SELECT name, description FROM tasks WHERE `type`='web-app' AND object_id=1 ORDER BY name ASC LIMIT 8,8",
            'tmpparam',
            'type/web-app/server_id/1'
        );
        $this->assertContains("[domain 1]", $this->getResponse()->getBody());
    }

    public function testAjaxListWoType() {
        $testSql = "SELECT `f`.name, `f`.description FROM `tasks` AS `f`
 INNER JOIN `web_apps` AS `w` ON f.object_id = w.id
 INNER JOIN `domains` AS `d` ON w.domain_id = d.id
 INNER JOIN `servers` AS `s` ON d.server_id = s.id AND s.project_id = 1 WHERE (f.type = 'web-app') 
 UNION 
 SELECT `f`.`name`, `f`.`description` FROM `tasks` AS `f`
 INNER JOIN `servers` AS `s` ON s.project_id = 1
 INNER JOIN `servers_software` AS `ss` ON ss.server_id = s.id AND ss.id = f.object_id WHERE (f.type = 'server-software') 
 UNION 
 SELECT `f`.`name`, `f`.`description` FROM `tasks` AS `f`
 INNER JOIN `servers` AS `s` ON s.project_id = 1 AND s.id = f.object_id WHERE (f.type = 'server') 
 UNION 
 SELECT `f`.`name`, `f`.`description` FROM `tasks` AS `f` WHERE (f.type = 'project') AND (f.object_id = 1) ORDER BY `name` ASC ";
        $this->_testAjaxList(
            "$testSql LIMIT 0,8",
            "$testSql LIMIT 8,8",
            'tmpparam',
            'type/0'
        );
        $this->assertContains("[server 1]", $this->getResponse()->getBody());
        $this->assertContains("[server]", $this->getResponse()->getBody());
    }

    public function testOneInList() {
        $this->_testOneInList("SELECT name, description FROM tasks WHERE id=1", "server_id");
    }

    public function testIndex() {
        $this->_testIndex();
    }
}

