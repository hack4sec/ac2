<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
abstract class Forms_Abstract extends Zend_Form {
    protected $_viewScript;

    protected function _t($phrase) {
        return $this->getDefaultTranslator()->translate($phrase);
    }

    public function init() {
        parent::init();

        $this->setDecorators(
            [
                [
                    'viewScript',
                    [
                        'viewScript' => $this->_viewScript
                    ]
                ]
            ]
        );
    }

    public function button($title, $jsFunc, $id) {
        $this->addElement(
            'button',
            'button',
            [
                'label' => $title,
                'decorators' => ['ViewHelper', 'Errors'],
                'onclick' => $jsFunc,
                'attribs' => ['id' => $id, 'class' => 'buttonElement']
            ]
        );
    }
}