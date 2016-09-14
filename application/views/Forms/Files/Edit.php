<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Files_Edit extends Forms_Files_Abstract {
    protected $_viewScript = 'files/forms/edit.phtml';
    protected $_isEdit = true;

    public function init() {
        $this->addElement(
            'hidden',
            'id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'fileForm_id']
            ]
        );
        parent::init();
        $this->button($this->_t('L_SAVE'), 'sendFileEditForm()', 'fileForm_button');
    }


}