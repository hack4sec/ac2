<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
abstract class Forms_ServerSoftware_Abstract extends Forms_Abstract {
    protected $_serverId;

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'server_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'serverSoftwareForm_server_id'],
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_TITLE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Servers_Software_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'serverSoftwareForm_name']
            ]
        );

        $this->addElement(
            'text',
            'version',
            [
                'label' => $this->_t('L_VERSION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'serverSoftwareForm_version']
            ]
        );

        $this->addElement(
            'checkbox',
            'version_unknown',
            [
                'label' => $this->_t('L_VERSION_UNKNOWN'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'serverSoftwareForm_version_unknown']
            ]
        );

        $this->addElement(
            'checkbox',
            'version_old',
            [
                'label' => $this->_t('L_OLD_VERSION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'serverSoftwareForm_version_old']
            ]
        );

        $this->addElement(
            'text',
            'vendor_site',
            [
                'label' => $this->_t('L_DEVELOPER'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'serverSoftwareForm_vendor_site']
            ]
        );

        $this->addElement(
            'textarea',
            'banner',
            [
                'label' => $this->_t('L_BANNER'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => 10, 'class' => 'textareaElements', 'id' => 'serverSoftwareForm_banner']
            ]
        );

        $this->addElement(
            'text',
            'port',
            [
                'label' => $this->_t('L_PORT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'serverSoftwareForm_port']
            ]
        );

        $this->addElement(
            'select',
            'proto',
            [
                'label' => $this->_t('L_PROTO'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'serverSoftwareForm_proto'],
                'multiOptions' => ['tcp' => 'tcp', 'udp' => 'udp']
            ]
        );

        $this->addElement(
            'textarea',
            'comment',
            [
                'label' => $this->_t('L_COMMENT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => 10, 'class' => 'textareaElements', 'id' => 'serverSoftwareForm_comment']
            ]
        );

        $this->addElement(
            'checkbox',
            'ghost',
            [
                'label' => $this->_t('L_GHOST'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'serverSoftwareForm_ghost']
            ]
        );

        $this->addElement(
            'checkbox',
            'checked',
            [
                'label' => $this->_t('L_CHECKED'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'serverSoftwareForm_checked']
            ]
        );
    }

    public function setServerId($id) {
        $this->_serverId = $id;
        $this->server_id->setValue($id);
    }
}