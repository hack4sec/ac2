<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class NotesControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'notes.sql';
    protected $_controller = 'notes';

    public function testCount() {
        $this->_go('count', '/project_id/1');

        $this->assertEquals(
            $this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE project_id = 1 "),
            $this->getResponse()->getBody()
        );
    }

    public function testList() {
        $this->_go('get-list', '/project_id/1');

        foreach ($this->_db->fetchCol("SELECT content FROM notes WHERE project_id = 1 ") as $note) {
            $this->assertContains($note, $this->getResponse()->getBody());
        }
    }

    public function testDelete() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE id = 1 "), 1);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE project_id = 1 "), 6);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE id = 1 "), 0);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE project_id = 1 "), 5);
    }

    public function testGetOne() {
        $this->_go('get-one', '/id/1');

        $this->assertContains(
            $this->_db->fetchOne("SELECT content FROM notes WHERE id = 1 "),
            $this->getResponse()->getBody()
        );
    }

    public function testSave() {
        $this->assertEquals($this->_db->fetchOne("SELECT content FROM notes WHERE id = 1"), 'note 1 note 1 note 1');

        $this->_go('save', '', ['id' => '1', '', 'content' => 'note 11 note 11 note 11',]);

        $this->assertEquals($this->_db->fetchOne("SELECT content FROM notes WHERE id = 1"), 'note 11 note 11 note 11');
    }

    public function testAdd() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE project_id = 1 "), 6);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE content = 'note 11 note 11 note 11' "), 0);

        $this->_go('add', '', ['project_id' => 1, 'content' => 'note 11 note 11 note 11',]);

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE project_id = 1 "), 7);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM notes WHERE content = 'note 11 note 11 note 11' "), 1);
    }
}

