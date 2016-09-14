<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Projects_Add extends Forms_Projects_Abstract {
    protected $_viewScript = 'projects/forms/add.phtml';

    public function init() {
        parent::init();
        $this->button($this->_t('L_CREATE'), 'sendProjectAddForm()', 'projectForm_button');
    }
} 