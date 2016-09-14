<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Users_Pairs extends Forms_Abstract
{
    protected $_viewScript = 'users/forms/pairs.phtml';

    public function init() {
        parent::init();

        $HashAlgs = new HashAlgs();

        $this->addElement(
            'text',
            'delimiter',
            [
                'label' => $this->_t('L_DELIMITER'),
                'value' => ':',
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'pairsForm_delimiter', 'class' => 'inputElements']
            ]
        );

        $this->addElement(
            'select',
            'alg_id',
            [
                'label' => $this->_t('L_HASH_ALG'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'pairsForm_alg_id', 'class' => 'selectElement'],
                'multiOptions' => $HashAlgs->getList()
            ]
        );

        $config = Zend_Registry::get('config');
        $this->addElement(
            'file',
            'file',
            [
                'label' => $this->_t('L_FILE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'pairsForm_file', 'class' => 'inputElements'],
                'destination' => $config->paths->storage,
            ]
        );

        $this->addElement(
            'submit',
            'submit',
            [
                'label' => $this->_t('L_EXPORT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'pairsForm_button']
            ]
        );
    }
}