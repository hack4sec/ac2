<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class FilesControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'files.sql';
    protected $_controller = 'files';

    protected function _addObjectsByCount($count) {
        for ($i = 0; $i <= $count; $i++) {
            $this->_db->query(
                "INSERT INTO `files` (`id`, `hash`, `name`, `object_id`, `type`, `comment`, `updated`, `when_add`) VALUES
                 (NULL, 'MD5(SHA1($i))', 'file$i.txt', 1, 'server', 'Comment$i', 0, 0)"
            );
        }
    }

    public function testDeleteFile() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM files"), 10);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM files"), 9);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM files WHERE name='comment file 1'"), 0);
    }

    public function editFileProvider() {
        return [
            [
                [
                    'id' => '1',
                    'type' => 'server',
                    'comment' => 'testcomment',
                ],
            ],
            [
                [
                    'id' => '3',
                    'type' => 'server-software',
                    'comment' => 'testcomment2',
                ],
            ],
            [
                [
                    'id' => '5',
                    'type' => 'web-app',
                    'comment' => 'testcomment3',
                ],
            ],
        ];
    }

    /**
     * @dataProvider editFileProvider
     */
    public function testEditFileGood($data) {
        $this->_go('edit', '', ['id' => $data['id'], 'object_id' => '1', 'type' => $data['type'], 'comment' => $data['comment'],]);

        $this->assertEquals(
            $this->_db->fetchRow("SELECT id, type, comment FROM files WHERE id = " . $data['id']),
            $data
        );
    }

    public function testView() {
        $this->_testView("SELECT name, comment FROM files WHERE id=1");
    }

    public function testAjaxList() {
        $this->_testAjaxList(
            "SELECT name, comment FROM files WHERE `type`='server' AND object_id=1 ORDER BY name ASC LIMIT 0,8",
            "SELECT name, comment FROM files WHERE `type`='server' AND object_id=1 ORDER BY name ASC LIMIT 8,8",
            'object_id',
            'type/server'
        );
    }

    public function testAjaxListSearch() {
        $this->_testAjaxListSearch(
            'object_id',
            'file2',
            'file1.txt',
            'type/server'
        );
    }

    public function testOneInList() {
        $this->_testOneInList("SELECT name, comment FROM files WHERE id=1");
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
        $this->_testOpenEditForm("SELECT comment FROM files WHERE id=1");
    }

    public function testIndex() {
        $this->_testIndex();
    }

    public function testDownload() {
        if (file_exists('/tmp/file5.txt')) {
            unlink('/tmp/file5.txt');
        }

        file_put_contents(APPLICATION_PATH . '/storage/11111111111111111111111111111111', 'abcabc');

        $this->_go('download', '/id/5');

        $this->assertEquals($this->getResponse()->getBody(), 'abcabc');
    }

}

