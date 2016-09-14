<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Servers_ListImport extends Forms_Abstract
{
    protected $_viewScript = 'servers/forms/list-import.phtml';

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'project_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'listImportForm_project_id']
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
            'submit',
            'button',
            [
                'label' => $this->_t('L_IMPORT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'listImportForm_button']
            ]
        );
    }

    public function setProjectId($id) {
        $this->project_id->setValue($id);
    }
}