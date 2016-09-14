<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
abstract class Forms_Users_Groups_Abstract extends Forms_Abstract {
    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'object_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'userGroupForm_object_id'],
            ]
        );

        $this->addElement(
            'hidden',
            'type',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'userGroupForm_type'],
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_TITLE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Users_Groups_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'userGroupForm_name']
            ]
        );

    }

    public function setObjectId($id) {
        $this->object_id->setValue($id);
    }

    public function setType($type) {
        $this->type->setValue($type);
    }
}