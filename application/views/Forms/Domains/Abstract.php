<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Domains_Abstract extends Forms_Abstract
{
    protected $_viewScript, $_serverId;

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'server_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'domainForm_server_id']
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_DOMAIN'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Domains_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'domainForm_name']
            ]
        );

        $this->addElement(
            'checkbox',
            'checked',
            [
                'label' => $this->_t('L_CHECKED'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'domainForm_checked']
            ]
        );

        $this->addElement(
            'textarea',
            'comment',
            [
                'label' => $this->_t('L_COMMENT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'domainForm_comment']
            ]
        );
    }

    public function setServerId($id) {
        $this->server_id->setValue($id);
    }
}