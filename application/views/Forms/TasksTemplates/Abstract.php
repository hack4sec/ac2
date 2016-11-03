<?php
abstract class Forms_TasksTemplates_Abstract extends Forms_Abstract {
    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'project_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'taskTemplatesForm_object_id']
            ]
        );

        $this->addElement(
            'hidden',
            'type',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'taskTemplatesForm_type']
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_TITLE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_TasksTemplates_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'taskTemplatesForm_name']
            ]
        );

        $this->addElement(
            'textarea',
            'description',
            [
                'label' => $this->_t('L_DESCRIPTION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'taskTemplatesForm_description']
            ]
        );

    }

    public function setProjectId($id) {
        $this->project_id->setValue($id);
    }

    public function setType($type) {
        $this->type->setValue($type);
    }
}