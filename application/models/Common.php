<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Common extends Zend_Db_Table_Abstract {
    public function get($id) {
        return $this->find($id)->current();
    }

    public function add($data) {
        $row = $this->createRow($data + ['updated' => time(), 'when_add' => time()]);
        $row->save();

        if (isset($this->_taskType)) {
            $TaskTemplates = new TasksTemplates();
            $templates = $TaskTemplates->getListByType($this->_taskType);

            if ($templates) {
                $Tasks = new Tasks();

                foreach ($templates as $template) {
                    $Tasks->createRow(
                        [
                            'object_id' => $row->id,
                            'type' => $this->_taskType,
                            'name' => $template->name,
                            'description' => $template->description,
                            'done' => 0,
                            'status' => 2,
                        ]
                    )->save();
                }
            }
        }

        return $row;
    }

    public function edit($data) {
        $this->get($data['id'])->setFromArray($data + ['updated' => time()])->save();
    }

    public function _t($phrase) {
        return Zend_Registry::get('Zend_Translate')->translate($phrase);
    }
} 