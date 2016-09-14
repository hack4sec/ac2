<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Users_Export extends Forms_Abstract
{
    protected $_viewScript = 'users/forms/export.phtml';

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'group_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'exportForm_group_id']
            ]
        );

        $this->_checkbox('login', $this->_t('L_LOGIN'), 'exportForm_login');
        $this->_checkbox('email', 'E-mail', 'exportForm_email');
        $this->_checkbox('vip', 'VIP', 'exportForm_vip');
        $this->_checkbox('hash', $this->_t('L_HASH'), 'exportForm_hash');
        $this->_checkbox('password', $this->_t('L_PASSWORD'), 'exportForm_password');
        $this->_checkbox('salt', $this->_t('L_SALT'), 'exportForm_salt');
        $this->_checkbox('alg', $this->_t('L_HASH_ALG'), 'exportForm_alg');
        $this->_checkbox('wpasswords', $this->_t('L_ONLY_W_PASSWORDS'), 'exportForm_wpasswords');
        $this->_checkbox('wopasswords', $this->_t('L_ONLY_WO_PASSWORDS'), 'exportForm_wopasswords');
        $this->_checkbox('only_vip', $this->_t('L_VIP_ONLY'), 'exportForm_only_vip');

        $this->addElement(
            'text',
            'delimiter',
            [
                'label' => $this->_t('L_DELIMITER'),
                'value' => ':',
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'exportForm_delimiter']
            ]
        );

        $this->addElement(
            'submit',
            'submit',
            [
                'label' => $this->_t('L_EXPORT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'exportForm_button']
            ]
        );
    }

    private function _checkbox($name, $label, $id) {
        $this->addElement(
            'checkbox',
            $name,
            [
                'label' => $label,
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => $id]
            ]
        );
    }

    public function setGroupId($id) {
        $this->group_id->setValue($id);
        $group = (new Users_Groups())->get($id);
        if ($group->type == 'server') {
            $this->_checkbox('home_dir', $this->_t('L_HOME_DIR'), 'exportForm_home_dir');
            $this->_checkbox('shell', $this->_t('L_SHELL'), 'exportForm_shell');
        }
    }
}