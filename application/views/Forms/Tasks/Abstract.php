<?php
abstract class Forms_Tasks_Abstract extends Forms_Abstract {
    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'object_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'taskForm_object_id']
            ]
        );

        $this->addElement(
            'hidden',
            'type',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'taskForm_type']
            ]
        );

        $this->addElement(
            'text',
            'name',
            [
                'label' => $this->_t('L_TITLE'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Tasks_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'taskForm_name']
            ]
        );

        $tasksStatuses = new TasksStatuses();
        $this->addElement(
            'select',
            'status',
            [
                'label' => $this->_t('L_STATUS'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['class' => 'selectElement', 'id' => 'taskForm_status'],
                'multiOptions' => $tasksStatuses->getTranslatedList()
            ]
        );

        $this->addElement(
            'textarea',
            'description',
            [
                'label' => $this->_t('L_DESCRIPTION'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['rows' => '10', 'class' => 'textareaElements', 'id' => 'taskForm_description']
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