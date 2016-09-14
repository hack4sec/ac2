<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Users_Edit extends Forms_Users_Abstract
{
    protected $_viewScript = 'users/forms/edit.phtml';

    public function init() {
        $this->addElement(
            'hidden',
            'id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'userForm_id'],
            ]
        );
        parent::init();
        $this->button($this->_t('L_SAVE'), 'sendUserEditForm()', 'userForm_button');
    }

    public function populate(array $values)
    {
        $this->elsByGroupId($values['group_id']);

        $Groups = new Users_Groups();
        $group = $Groups->get($values['group_id']);
        $this->removeElement('group_id');
        $this->addGroupSelect($group['type'], $group['object_id']);

        return parent::populate($values);
    }
}