<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Users_Import extends Forms_Abstract
{
    protected $_viewScript = 'users/forms/import.phtml';

    public function init() {
        parent::init();

        $this->addElement(
            'hidden',
            'group_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'importForm_group_id']
            ]
        );

        $this->_checkbox('login', $this->_t('L_LOGIN'), 'importForm_login');
        $this->_checkbox('email', 'E-mail', 'importForm_email');
        $this->_checkbox('vip', 'VIP', 'importForm_vip');
        $this->_checkbox('hash', $this->_t('L_HASH'), 'importForm_hash');
        $this->_checkbox('password', $this->_t('L_PASSWORD'), 'importForm_password');
        $this->_checkbox('salt', $this->_t('L_SALT'), 'importForm_salt');
        $this->_checkbox('alg', $this->_t('L_HASH_ALG'), 'importForm_alg');
        $this->_checkbox('wpasswords', $this->_t('L_ONLY_W_PASSWORDS'), 'importForm_wpasswords');
        $this->_checkbox('wopasswords', $this->_t('L_ONLY_WO_PASSWORDS'), 'importForm_wopasswords');

        $this->hash->setAttrib('onchange', 'hashAndAlgCheck()');

        $this->addElement(
            'text',
            'delimiter',
            [
                'label' => $this->_t('L_DELIMITER'),
                'value' => ':',
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'importForm_delimiter', 'class' => 'inputElements']
            ]
        );

        $config = Zend_Registry::get('config');
        $this->addElement(
            'file',
            'file',
            [
                'label' => 'Файл',
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'importForm_file', 'class' => 'inputElements'],
                'destination' => $config->paths->storage,
            ]
        );

        $this->addElement(
            'button',
            'submit',
            [
                'label' => $this->_t('L_IMPORT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'importForm_button', 'onclick' => 'sendImportForm()']
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
            $this->_checkbox('home_dir', $this->_t('L_HOME_DIR'), 'importForm_home_dir');
            $this->_checkbox('shell', $this->_t('L_SHELL'), 'importForm_shell');
        }
    }
}