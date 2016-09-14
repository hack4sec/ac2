<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
abstract class Forms_Servers_Abstract extends Forms_Abstract {
    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'project_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'serverForm_project_id']
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_SERVER_NAME'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Servers_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'serverForm_name']
            ]
        );

        $this->addElement(
            'text',
            'ip',
            [
                'label' => $this->_t('L_IP_ADDR'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Servers_Ip,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'serverForm_ip']
            ]
        );

        $Os = new Os();
        $this->addElement(
            'select',
            'os_id',
            [
                'label' => $this->_t('L_OS'),
                'decorators' => ['ViewHelper', 'Errors'],
                //'required' => true,
                'attribs' => ['id' => 'serverForm_os_id', 'class' => 'selectElement'],
                'multiOptions' => $Os->getList()
            ]
        );

        $this->addElement(
            'text',
            'os_version',
            [
                'label' => $this->_t('L_OS_VERSION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => false,
                'attribs' => ['class' => 'inputElements', 'id' => 'serverForm_os_version']
            ]
        );

        $this->addElement(
            'checkbox',
            'checked',
            [
                'label' => $this->_t('L_CHECKED'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'serverForm_checked']
            ]
        );

        $this->addElement(
            'textarea',
            'comment',
            [
                'label' => $this->_t('L_COMMENT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'serverForm_comment']
            ]
        );
    }

    public function setProjectId($id) {
        $this->project_id->setValue($id);
    }

}