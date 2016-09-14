<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
abstract class Forms_Projects_Abstract extends Forms_Abstract
{
    public function init() {
        parent::init();

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_PROJECT_NAME'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Projects_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'projectForm_name']
            ]
        );

        $this->addElement(
            'textarea',
            'comment',
            [
                'label' => $this->_t('L_COMMENT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'projectForm_comment']
            ]
        );
    }
}