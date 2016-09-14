<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_ServerSoftware_NmapImport extends Forms_Abstract
{
    protected $_viewScript = 'server-software/forms/nmap-import.phtml';

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'server_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'nmapImportForm_server_id']
            ]
        );

        $config = Zend_Registry::get('config');
        $this->addElement(
            'file',
            'file',
            [
                'label' => 'XML',
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'nmapImportForm_file'],
                'destination' => $config->paths->tmp,
            ]
        );

        $this->addElement(
            'checkbox',
            'all',
            [
                'label' => $this->_t('L_NMAP_ALL'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'nmapImportForm_all']
            ]
        );

        $this->addElement(
            'checkbox',
            'ignore_blank',
            [
                'label' => $this->_t('L_NMAP_IGNORE_BLANK_HOSTS'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'checked' => true,
                'attribs' => ['id' => 'nmapImportForm_ignore_blank']
            ]
        );

        $this->addElement(
            'submit',
            'button',
            [
                'label' => $this->_t('L_IMPORT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'nmapImportForm_button']
            ]
        );
    }

    public function setServerId($id) {
        $this->server_id->setValue($id);
    }
}