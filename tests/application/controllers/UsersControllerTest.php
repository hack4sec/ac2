<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class UsersControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'users.sql';
    protected $_controller = 'users';

    protected function _addObjectsByCount($count) {
        $this->_db->query(
            "INSERT INTO `users` (`id`, `group_id`, `login`, `email`, `hash_id`, `shell_id`, `home_dir`, `vip`, `updated`) VALUES
(11, 1, 'User11', 'email11@example.com', 1, NULL, '', 0, 0),
(12, 1, 'User12', 'email12@example.com', 1, NULL, '', 0, 0),
(13, 1, 'User13', 'email13@example.com', 1, NULL, '', 0, 0),
(14, 1, 'User14', 'email14@example.com', 1, NULL, '', 0, 0),
(15, 1, 'User15', 'email15@example.com', 1, NULL, '', 0, 0),
(16, 1, 'User16', 'email16@example.com', 1, NULL, '', 0, 0),
(17, 1, 'User17', 'email17@example.com', 1, NULL, '', 0, 0),
(18, 1, 'User18', 'email18@example.com', 1, NULL, '', 0, 0),
(19, 1, 'User19', 'email19@example.com', 1, NULL, '', 0, 0),
(20, 1, 'User20', 'email20@example.com', 1, NULL, '', 0, 0);"
        );
        $this->_db->query(
            "INSERT INTO `hashes` (`id`, `hash`, `salt`, `password`, `user_id`, `alg_id`) VALUES
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 11, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 12, 1),
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 13, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 14, 1),
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 15, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 16, 1),
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 17, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 18, 1),
(NULL, '111111111122222222223333333333aa', '', 'MARKpass', 19, 1),
(NULL, '111111111122222222223333333333bb', '', 'pa$$', 20, 1);"
        );
    }

    public function testAddGroupFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);

        $this->_go('add-group', '', ['name' => '', 'type' => 'server', 'object_id' => '1', 'comment' => 'testcomment',]);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);
    }
//TODO тесты на отображение таблички юзеров и выбрать её наполнение
    public function testAddGroupFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);

        $this->_go('add-group', '', ['name' => 'Group1', 'type' => 'server', 'object_id' => '1', 'comment' => 'testcomment',]);

        $this->assertContains($this->_t("L_GROUP_YET_EXISTS"), $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);
    }

    public function addGroupProvider() {
        return [
            [
                [
                    'name' => 'Group3',
                    'type' => 'server',
                    'object_id' => '1',
                    'comment' => 'testcomment',
                ],
            ],
            [
                [
                    'name' => 'Group4',
                    'type' => 'server-software',
                    'object_id' => '2',
                    'comment' => 'testcomment',
                ],
            ],
            [
                [
                    'name' => 'Group5',
                    'type' => 'web-app',
                    'object_id' => '4',
                    'comment' => 'testcomment',
                ],
            ]
        ];
    }

    /**
     * @dataProvider addGroupProvider
     */
    public function testAddGroupGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);

        $this->_go('add-group', '', $data);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 7);
        $this->assertEquals(
            $this->_db->fetchRow("SELECT name, type, object_id, comment FROM users_groups ORDER BY id DESC LIMIT 1"),
            $data
        );
    }

    public function testEditGroupFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);

        $postData = [
            'id' => '1',
            'name' => '',
            'type' => 'server',
            'object_id' => '1',
            'comment' => 'testcomment',
        ];
        $this->_go('edit-group', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);
    }

    public function testEditGroupFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);

        $postData = [
            'id' => '1',
            'name' => 'Group2',
            'type' => 'server',
            'object_id' => '1',
            'comment' => 'testcomment',
        ];
        $this->_go('edit-group', '', $postData);

        $this->assertContains($this->_t("L_GROUP_YET_EXISTS"), $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);
    }
    public function editGroupProvider() {
        return [
            [
                'data' => [
                    'id' => '1',
                    'name' => 'Group3',
                    'type' => 'server',
                    'object_id' => '1',
                ],
            ],
            [
                'data' => [
                    'id' => '3',
                    'name' => 'Group3',
                    'type' => 'server-software',
                    'object_id' => '1',
                ],
            ],
            [
                'data' => [
                    'id' => '5',
                    'name' => 'Group3',
                    'type' => 'web-app',
                    'object_id' => '1',
                ],
            ],
        ];
    }

    /**
     * @dataProvider editGroupProvider
     */
    public function testEditGroupGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);

        $this->_go('edit-group', '', $data);

        $this->assertEquals(
            $this->_db->fetchRow("SELECT id, name, type, object_id FROM users_groups WHERE id = {$data['id']}"),
            $data
        );
    }

    public function testDelGroup() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 6);

        $this->_go('delete-group', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users_groups"), 5);
    }

    public function testAddUserFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);

        $postData = [
            'login' => '',
            'group_id' => '1',
            'shell_id' => '1',
            'alg_id' => '1',
        ];
        $this->_go('add', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);
    }

    public function testAddUserFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);

        $postData = [
            'login' => 'User1',
            'group_id' => '1',
            'shell_id' => '1',
            'alg_id' => '1',
        ];
        $this->_go('add', '', $postData);

        $this->assertContains($this->_t("L_USER_YET_EXISTS"), $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);
    }

    public function addUserProvider() {
        return [
            [
                'data' => [
                    'login' => 'User3',
                    'email' => 'a@b.c',
                    'group_id' => '1',
                    'shell_id' => '1',
                    'home_dir' => '/dev/null',
                    'vip' => '0',
                    'password' => 'pass',
                    'salt' => '',
                    'alg_id' => '1',
                    'hash' => 'somehash',
                ],
            ],
            [
                'data' => [
                    'login' => 'User3',
                    'email' => 'a@b.c',
                    'group_id' => '2',
                    'shell_id' => '2',
                    'home_dir' => '/dev/null',
                    'password' => 'pass',
                    'salt' => '',
                    'alg_id' => '2',
                    'hash' => 'somehash',
                    'vip' => '1',
                ]
            ]
        ];
    }

    /**
     * @dataProvider addUserProvider
     */
    public function testAddUserGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);

        $this->_go('add', '', $data);

        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT u.login, u.group_id, u.shell_id, u.home_dir, u.vip, u.email,
                        h.password, h.alg_id, h.hash, h.salt
                 FROM users u, hashes h WHERE u.id = 7 AND h.user_id = u.id"
            ),
            $data
        );
    }

    public function testEditUserFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);

        $postData = [
            'id' => '1',
            'login' => '',
            'group_id' => '1',
            'shell_id' => '1',
            'alg_id' => '1',
        ];
        $this->_go('edit', '', $postData);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);
    }

    public function testEditUserFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);

        $postData = [
            'id' => '1',
            'login' => 'User2',
            'group_id' => '1',
            'shell_id' => '1',
            'alg_id' => '1',
        ];
        $this->_go('edit', '', $postData);

        $this->assertContains($this->_t("L_USER_YET_EXISTS"), $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);
    }

    public function editUserProvider() {
        return [
            [
                'data' => [
                    'id' => '1',
                    'login' => 'User3',
                    'email' => 'a@b.c',
                    'group_id' => '1',
                    'shell_id' => '1',
                    'home_dir' => '/dev/null',
                    'password' => 'pass',
                    'alg_id' => '1',
                    'hash' => 'somehash',
                    'vip' => '0',
                ]
            ],
            [
                'data' => [
                    'id' => '3',
                    'login' => 'User3',
                    'email' => 'a@b.c',
                    'group_id' => '2',
                    'shell_id' => '2',
                    'home_dir' => '',
                    'password' => '',
                    'alg_id' => '2',
                    'hash' => 'somehash',
                    'vip' => '1'
                ]
            ],
            [
                'data' => [
                    'id' => '5',
                    'login' => 'User3',
                    'email' => 'a@b.c',
                    'group_id' => '3',
                    'shell_id' => '2',
                    'home_dir' => '',
                    'password' => '',
                    'alg_id' => '2',
                    'hash' => 'somehash',
                    'vip' => '1'
                ]
            ]
        ];
    }

    /**
     * @dataProvider editUserProvider
     */
    public function testEditUserGood($data) {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);

        $this->_go('edit', '', $data);

        $this->assertEquals(
            $this->_db->fetchRow(
                "SELECT u.id, u.login, u.group_id, u.shell_id, u.home_dir, u.vip, u.email,
                        h.password, h.alg_id, h.hash
                 FROM users u, hashes h WHERE u.id = {$data['id']} AND h.user_id = u.id"
            ),
            $data
        );
    }

    public function testDelUser() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 6);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM users"), 5);
    }

    public function testExportUser() {
        $postData = [
            'delimiter' => ':',
            'email' => '1',
            'login' => '1',
            'shell' => '0',
            'vip' => '0',
            'home_dir' => '0',
            'password' => '1',
            'alg' => '0',
            'hash' => '0',
            'group_id' => '1',
            'wpasswords' => '0',
            'wopasswords' => '0',
            'only_vip' => '0',
            'salt' => '0'
        ];
        $this->_go('export', '', $postData);
        $this->assertEquals(trim($this->getResponse()->getBody()), "User1:email1@example.com:MARKpass\nUser2:email2@example.com:pa\$\$");
    }


    public function testView() {
        $this->_testView(
            "SELECT u.login, u.email, h.hash, h.password
             FROM users u, hashes h
             WHERE u.id=1 AND h.user_id = u.id AND u.hash_id = h.id"
        );
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT u.login, u.email, h.password
             FROM users u, hashes h
             WHERE u.id=1 AND h.user_id = u.id AND u.hash_id = h.id ORDER BY vip DESC, login ASC LIMIT 0,8",
            "SELECT u.login, u.email, h.password
             FROM users u, hashes h
             WHERE u.id=1 AND h.user_id = u.id AND u.hash_id = h.id AND u.group_id = 1
             ORDER BY vip DESC, login ASC LIMIT 8,8",
            'group_id',
            'type/server'
        );
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'group_id',
            'User2',
            'User1',
            'type/server'
        );
    }

    public function testOneInList() {
        $this->_testOneInList(
            "SELECT u.login, u.email, h.password
             FROM users u, hashes h
             WHERE u.id=1 AND h.user_id = u.id AND u.hash_id = h.id"
        );
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

    public function testGroupsListJson() {
        $this->_go('groups-list-json', '/type/web-app/object_id/1');

        $this->assertEquals(
            json_decode($this->getResponse()->getBody(), true),
            $this->_db->fetchPairs("SELECT id, name FROM users_groups WHERE type='web-app' AND object_id=1 ORDER BY name ASC")
        );
    }

    public function testNotFoundHashesExport() {
        $this->markTestIncomplete(
            'TODO'
        );
        return;
        $this->dispatch("/users/not-found-hashes-export/type/web-app/object_id/1");
        $this->assertController("users");
        $this->assertAction('not-found-hashes-export');
    }

    public function testOpenEditForm() {
        $this->_testOpenEditForm(
            "SELECT u.login, u.email, h.password
             FROM users u, hashes h
             WHERE u.id=1 AND h.user_id = u.id AND u.hash_id = h.id AND u.id=1"
        );
    }

    public function testOpenGroupEditForm() {
        $this->_go('edit-group', '/id/1/object_id/1/type/server');
        $this->assertContains('Group1', $this->getResponse()->getBody());
    }

    public function testIndex() {
        $this->_testIndex();
    }

    public function testHashesCoCrack() {
        $this->_db->query("UPDATE hashes SET cracked = 0, password = ''");

        $this->assertEquals(
            $this->_db->fetchOne(
                "SELECT COUNT(id) FROM hashes WHERE cracked AND hash='111111111122222222223333333333aa' AND password = 'pass'"
            ),
            0
        );

        $postData = [
            'id' => '1',
            'login' => 'User3',
            'email' => 'a@b.c',
            'group_id' => '1',
            'shell_id' => '1',
            'home_dir' => '/dev/null',
            'password' => 'pass',
            'alg_id' => '1',
            'hash' => '111111111122222222223333333333aa',
            'vip' => '0',
        ];
        $this->_go('edit', '', $postData);

        $this->assertEquals(
            $this->_db->fetchOne(
                "SELECT COUNT(id) FROM hashes WHERE cracked AND hash='111111111122222222223333333333aa' AND password = 'pass'"
            ),
            3
        );

    }
}

