<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Domains_ListImport extends Forms_Abstract
{
    protected $_viewScript = 'domains/forms/list-import.phtml';

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'server_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'listImportForm_server_id']
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
                'attribs' => ['id' => 'listImportForm_file'],
                'destination' => $config->paths->tmp,
            ]
        );

        $this->addElement(
            'checkbox',
            'lookup',
            [
                'label' => $this->_t('L_DOMAINS_LOOKUP'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'listImportForm_lookup']
            ]
        );

        $this->addElement(
            'submit',
            'button',
            [
                'label' => $this->_t('L_IMPORT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'listImportForm_button']
            ]
        );
    }

    public function setServerId($id) {
        $this->server_id->setValue($id);
    }
}