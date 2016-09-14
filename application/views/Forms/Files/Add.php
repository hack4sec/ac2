<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Files_Add extends Forms_Files_Abstract {
    protected $_viewScript = 'files/forms/add.phtml';

    public function init() {
        parent::init();

        $this->addElement(
            'button',
            'button',
            [
                'label' => $this->_t('L_ADD'),
                'decorators' => ['ViewHelper', 'Errors'],
                'onclick' => 'sendFileAddForm()',
                'attribs' => ['id' => 'fileForm_button']
            ]
        );
    }
} 