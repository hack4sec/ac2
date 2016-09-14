<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class ProjectsControllerTests extends Tests_CommonControllerTestCase
{
    protected $_sql = 'projects.sql';
    protected $_controller = 'projects';
    
    public function testProjectsDisplay() {
        $this->_go('index');

        $this->assertContains('test1', $this->getResponse()->getBody());
        $this->assertContains('<p class="info">test comment</p>', $this->getResponse()->getBody());
        $this->assertContains('<p class="info">test comment 2</p>', $this->getResponse()->getBody());
        $this->assertContains('test2', $this->getResponse()->getBody());
        $this->assertNotContains('Exception information', $this->getResponse()->getBody());
    }

    public function testAddProjectFailBlankName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 2);

        $this->_go('add', '', ['name' => '', 'comment' => 'testcomment']);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 2);
    }

    public function testAddProjectFailDublName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 2);

        $this->_go('add', '', ['name' => 'test1', 'comment' => 'testcomment']);

        $this->assertContains($this->_t('L_PROJECT_YET_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 2);
    }

    public function testAddProjectGoodBlankComment() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 2);

        $this->_go('add', '', ['name' => 'test3', 'comment' => '']);

        $this->assertEquals($this->getResponse()->getBody(), "");
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 3);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test3' AND comment=''"), 1);
    }

    public function testAddProjectGoodWithComment() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 2);

        $this->_go('add', '', ['name' => 'test3', 'comment' => 'testcomment']);

        $this->assertEquals($this->getResponse()->getBody(), "");
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 3);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test3' AND comment='testcomment'"), 1);
    }

    public function testDeleteProject() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 2);

        $this->_go('delete', '/id/1');

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects"), 1);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test1'"), 0);
    }

    public function testEditProjectFailBlankName() {
        $this->_go('edit', '', ['id' => '1', 'name' => '', 'comment' => 'testeditcomment']);

        $this->assertEquals(substr_count($this->getResponse()->getBody(), "Value is required and can't be empty"), 1);
        $this->assertContains("testeditcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE comment='testeditcomment'"), 0);
    }

    public function testEditProjectFailDublName() {
        $this->_go('edit', '', ['id' => '1', 'name' => 'test2', 'comment' => 'testeditcomment']);

        $this->assertContains($this->_t('L_PROJECT_YET_EXISTS'), $this->getResponse()->getBody());
        $this->assertContains("testeditcomment", $this->getResponse()->getBody());

        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE comment='testeditcomment'"), 0);
    }

    public function testEditProjectGood() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test1'"), 1);

        $this->_go('edit', '', ['id' => '1', 'name' => 'test3', 'comment' => 'testeditcomment']);

        $this->assertEquals($this->getResponse()->getBody(), "");
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test3' AND comment='testeditcomment'"), 1);
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test1'"), 0);
    }

    public function testEditProjectGoodSaveName() {
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test1' AND comment='testeditcomment'"), 0);

        $this->_go('edit', '', ['id' => '1', 'name' => 'test1', 'comment' => 'testeditcomment']);

        $this->assertEquals($this->getResponse()->getBody(), "");
        $this->assertEquals($this->_db->fetchOne("SELECT COUNT(id) FROM projects WHERE name='test1' AND comment='testeditcomment'"), 1);
    }

    public function testOneInList() {
        $this->_testOneInList("SELECT name, comment FROM projects WHERE id=1");
    }

    public function testOpenEditForm() {
        $this->_testOpenEditForm("SELECT name, comment FROM projects WHERE id=1");
    }

    public function testView() {
        $this->_go('view', '/project_id/1/');

        $this->assertContains("var projectId = 1;", $this->getResponse()->getBody());
        $this->assertContains("notesBlock", $this->getResponse()->getBody());
        $this->assertContains("work-space", $this->getResponse()->getBody());
    }

    public function testMenu() {
        $this->_go('menu', '/project_id/1/');

        $this->assertContains("Domains <sup>(2)</sup>", $this->getResponse()->getBody());
        $this->assertContains("Servers <sup>(3)</sup>", $this->getResponse()->getBody());
        $this->assertContains("Server software <sup>(4)</sup>", $this->getResponse()->getBody());
        $this->assertContains("Vulnerabiliries <sup>(5)</sup>", $this->getResponse()->getBody());
        $this->assertContains("Files <sup>(6)</sup>", $this->getResponse()->getBody());
        $this->assertContains("Tasks <sup>(7)</sup>", $this->getResponse()->getBody());
        $this->assertContains("Users <sup>(8)</sup>", $this->getResponse()->getBody());


    }
}