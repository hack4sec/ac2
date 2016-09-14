<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_WebApps_Abstract extends Forms_Abstract
{
    protected $_viewScript, $_serverId;

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'domain_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'webAppForm_domain_id']
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_TITLE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_WebApps_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'webAppForm_name']
            ]
        );

        $this->addElement(
            'text',
            'url',
            [
                'label' => 'URL',
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [

                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'webAppForm_url']
            ]
        );


        $this->addElement(
            'text',
            'version',
            [
                'label' => $this->_t('L_VERSION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'webAppForm_version']
            ]
        );

        $this->addElement(
            'checkbox',
            'version_unknown',
            [
                'label' => $this->_t('L_VERSION_UNKNOWN'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'webAppForm_version_unknown']
            ]
        );

        $this->addElement(
            'checkbox',
            'version_old',
            [
                'label' => $this->_t('L_OLD_VERSION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => [ 'id' => 'webAppForm_version_old']
            ]
        );

        $this->addElement(
            'text',
            'vendor_site',
            [
                'label' => $this->_t('L_DEVELOPER'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'webAppForm_vendor_site']
            ]
        );

        $this->addElement(
            'checkbox',
            'need_auth',
            [
                'label' => $this->_t('L_NEED_AUTH'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'webAppForm_need_auth']
            ]
        );

        $this->addElement(
            'checkbox',
            'url_rewrite',
            [
                'label' => $this->_t('L_URL_REWRITE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'webAppForm_url_rewrite']
            ]
        );

        $this->addElement(
            'checkbox',
            'ghost',
            [
                'label' => $this->_t('L_GHOST'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'webAppForm_ghost']
            ]
        );

        $this->addElement(
            'checkbox',
            'checked',
            [
                'label' => $this->_t('L_CHECKED'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'webAppForm_checked']
            ]
        );

        $this->addElement(
            'textarea',
            'comment',
            [
                'label' => $this->_t('L_COMMENT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'webAppForm_comment']
            ]
        );
    }

    public function setDomainId($id) {
        $this->domain_id->setValue($id);
    }
}