<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Forms_Users_Abstract extends Forms_Abstract
{
    protected $_groupId;
    protected $_groups;
    protected $_type;

    protected function _elsByType($type) {
        if ($type == 'server') {
            $this->addServerElements();
        }
    }

    public function elsByGroupId($id) {
        $Groups = new Users_Groups();
        $group = $Groups->get($id);
        $this->_elsByType($group->type);
    }

    public function getType() {
        return $this->_type;
    }

    public function init() {
        parent::init();

        $this->_groups = new Users_Groups();

        $this->addElement(
            'hidden',
            'group_id',
            [
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'userForm_group_id'],
            ]
        );
        
        $this->addElement(
            'text',
            'login',
            [
                'label' => $this->_t('L_LOGIN'),
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    new Forms_Validate_Users_Name,
                ],
                'required' => true,
                'attribs' => ['class' => 'inputElements', 'id' => 'userForm_login']
            ]
        );

        $this->addElement(
            'text',
            'email',
            [
                'label' => 'E-mail',
                'decorators' => ['ViewHelper', 'Errors'],
                'validators' => [
                    //new Forms_Validate_Tasks_Name,
                ],
                'attribs' => ['class' => 'inputElements', 'id' => 'userForm_email']
            ]
        );

        $this->addElement(
            'checkbox',
            'ghost',
            [
                'label' => $this->_t('L_GHOST'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'userForm_ghost']
            ]
        );

        $this->addElement(
            'checkbox',
            'vip',
            [
                'label' => 'VIP',
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['id' => 'userForm_vip']
            ]
        );

        $HashAlgs = new HashAlgs();
        $this->addElement(
            'select',
            'alg_id',
            [
                'label' => $this->_t('L_HASH_ALG'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'userForm_alg_id', 'class' => 'selectElement'],
                'multiOptions' => $HashAlgs->getList()
            ]
        );



        $this->addElement(
            'text',
            'password',
            [
                'label' => $this->_t('L_PASSWORD'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'userForm_password']
            ]
        );

        $this->addElement(
            'text',
            'hash',
            [
                'label' => $this->_t('L_HASH'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'userForm_hash']
            ]
        );

        $this->addElement(
            'text',
            'salt',
            [
                'label' => $this->_t('L_SALT'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'userForm_salt']
            ]
        );
    }

    public function setGroupInfo($id, $objectId=false, $type=false) {
        $this->_type = $type;

        if ($id) {
            $this->_groupId = $id;
            $this->group_id->setValue($id);

            $this->elsByGroupId($id);
        } else {
            $this->addGroupSelect($type, $objectId);
            $this->_elsByType($type);
        }

        if ($type == 'server') {
            $this->addServerElements();
        }
    }

    protected function addGroupSelect($type, $objectId) {
        $Groups = new Users_Groups();
        $this->addElement(
            'select',
            'group_id',
            [
                'label' => $this->_t('L_GROUP'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'userForm_group_id', 'class' => 'selectElement'],
                'multiOptions' => $Groups->getPairsListByTypeAndObjectId($type, $objectId)
            ]
        );
    }

    protected function addServerElements() {
        $Shells = new Shells();
        $this->addElement(
            'select',
            'shell_id',
            [
                'label' => $this->_t('L_SHELL'),
                'decorators' => ['ViewHelper', 'Errors'],
                'required' => true,
                'attribs' => ['id' => 'userForm_shell_id', 'class' => 'selectElement'],
                'multiOptions' => $Shells->getList()
            ]
        );

        $this->addElement(
            'text',
            'home_dir',
            [
                'label' => $this->_t('L_HOME_DIR'),
                'decorators' => ['ViewHelper', 'Errors'],
                'attribs' => ['class' => 'inputElements', 'id' => 'userForm_home_dir']
            ]
        );
    }
}