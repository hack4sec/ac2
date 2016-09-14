<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
abstract class Forms_Files_Abstract extends Forms_Abstract {
    protected $_isEdit = false;

    public function init() {
        parent::init();

        $config = Zend_Registry::get('config');

        $this->addElement(
            'hidden',
            'object_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'fileForm_object_id']
            ]
        );

        $this->addElement(
            'hidden',
            'type',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'fileForm_type']
            ]
        );

        if (!$this->_isEdit) {
            $this->addElement(
                'file',
                'file',
                [
                    'label' => $this->_t('L_FILE'),
                    'decorators' => ['ViewHelper', 'Errors'],
                    'required' => true,
                    'attribs' => ['id' => 'fileForm_file'],
                    'destination' => $config->paths->storage,
                ]
            );
        }

        $this->addElement(
            'textarea',
            'comment',
            [
                'label' => $this->_t('L_COMMENT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'fileForm_comment']
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