<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Vulns_Edit extends Forms_Vulns_Abstract
{
    protected $_viewScript = 'vulns/forms/edit.phtml';

    public function init() {
        $this->addElement(
            'hidden',
            'id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'vulnForm_id'],
            ]
        );
        parent::init();
        $this->button($this->_t('L_SAVE'), 'sendVulnEditForm()', 'vulnForm_button');
    }

    public function populate(array $values)
    {
        $this->addVulnTypeSelect($values['type']);
        return parent::populate($values);

    }
}