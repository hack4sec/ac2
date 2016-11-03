<?php

class Forms_TasksTemplates_Add extends Forms_TasksTemplates_Abstract {
    protected $_viewScript = 'tasks-templates/forms/add.phtml';

    public function init() {
        parent::init();
        $this->button($this->_t('L_CREATE'), 'sendTaskTemplateAddForm()', 'taskTemplatesForm_button');
    }

    public function setProjectId($id) {
        $this->project_id->setValue($id);
    }

    public function setType($type) {
        $this->type->setValue($type);
    }
} 