<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Vulns_Abstract extends Forms_Abstract
{
    protected $_vulnTypes;

    public function init() {
        parent::init();

        $this->_vulnTypes = new Vulns_Types();


        $this->addElement(
            'hidden',
            'type',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'vulnForm_type'],
            ]
        );

        $this->addElement(
            'hidden',
            'object_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'vulnForm_object_id'],
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_TITLE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'validators' => [
                    //new Forms_Validate_Tasks_Name,
                ],
                'attribs' => ['class' => 'inputElements', 'id' => 'vulnForm_name']
            ]
        );

        $this->addElement(
            'text',
            'exploit_link',
            [
                'label' => $this->_t('L_EXPLOIT_LINK'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    //new Forms_Validate_Tasks_Name,
                ],
                'attribs' => ['class' => 'inputElements', 'id' => 'vulnForm_exploit_link']
            ]
        );

        $riskLevels = new RiskLevels();
        $levels = $riskLevels->getList();
        foreach ($levels as $k => $level) {
            $levels[$k] = $this->_t($level);
        }
        $this->addElement(
            'select',
            'risk_level_id',
            [
                'label' => $this->_t('L_RISK'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['class' => 'selectElement', 'id' => 'vulnForm_risk_level_id'],
                'multiOptions' => $levels
            ]
        );

        $this->addElement(
            'textarea',
            'description',
            [
                'label' => $this->_t('L_DESCRIPTION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'vulnForm_description']
            ]
        );
    }

    public function setObjectId($objectId) {
        $this->object_id->setValue($objectId);
    }

    public function setType($type) {
        $this->type->setValue($type);
    }

    public function addVulnTypeSelect($type) {
        $vTypes = $this->_vulnTypes->getListByType($type);
        foreach ($vTypes as $k => $vType) {
            $vTypes[$k] = $this->_t($vType);
        }

        $this->addElement(
            'select',
            'vuln_type_id',
            [
                'label' => $this->_t('L_VULN_TYPE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'vulnForm_vuln_type_id', 'class' => 'selectElement'],
                'multiOptions' => $vTypes
            ]
        );
    }
}