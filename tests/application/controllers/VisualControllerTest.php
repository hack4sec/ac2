<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class VisualControllerTest extends Tests_CommonControllerTestCase
{
    protected $_sql = 'visual.sql';
    protected $_controller = 'visual';

    public function testIndex() {
        $this->_go('index', '/project_id/1/');
        //FIXME всё это получать из БД
        $this->assertContains("Zone&nbsp;google.com", $this->getResponse()->getBody());
        $this->assertContains("Zone&nbsp;yandex.ru", $this->getResponse()->getBody());
        $this->assertContains("www.yandex.ru", $this->getResponse()->getBody());
        $this->assertContains("2.2.2.0 - 2.2.2.255", $this->getResponse()->getBody());
        $this->assertContains("1.1.1.0 - 1.1.1.255", $this->getResponse()->getBody());
        $this->assertContains("1.1.1.1", $this->getResponse()->getBody());
        $this->assertContains("2.2.2.2", $this->getResponse()->getBody());
        $this->assertContains("2.2.2.3", $this->getResponse()->getBody());
    }
}