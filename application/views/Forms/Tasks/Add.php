<?php

class Forms_Tasks_Add extends Forms_Tasks_Abstract {
    protected $_viewScript = 'tasks/forms/add.phtml';

    public function init() {
        parent::init();
        $this->button($this->_t('L_CREATE'), 'sendTaskAddForm()', 'taskForm_button');
        $this->status->setValue(2);
    }

    public function setObjectId($id) {
        $this->object_id->setValue($id);
    }

    public function setType($type) {
        $this->type->setValue($type);
    }
} 