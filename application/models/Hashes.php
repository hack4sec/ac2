<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Hashes extends Common
{
    protected $_name = 'hashes';

    protected $_rowClass = 'Hash';

    const LIST_TYPE_HASH_AND_PASS = 0;
    const LIST_TYPE_HASH_AND_SALT_AND_PASS = 1;

    public function loadManyFromFile($listType, $algId, $delimiter, $file) {
        if (!$file or !file_exists($file)) {
            throw new \Exception("File wasn`t loaded!");
        }
        $result = [
            'err_strs' => [],
            'err_find' => [],
            'found' => []
        ];
        $list = array_map(
            'trim',
            array_unique(
                file($file)
            )
        );
        foreach ($list as $row) {
            $arr = explode($delimiter, $row);
            if ($listType == self::LIST_TYPE_HASH_AND_PASS) {
                if (count($arr) != 2) {
                    $result['err_strs'][] = $row;
                    continue;
                }
                list($hash, $pass) = $arr;
                $salt = '';
            } else {
                if (count($arr) != 3) {
                    $result['err_strs'][] = $row;
                    continue;
                }
                list($hash, $salt, $pass) = $arr;
            }

            $hashes = $this->fetchAll(
                "`hash` = {$this->getAdapter()->quote($hash)}
                 AND `salt` = {$this->getAdapter()->quote($salt)}
                 AND `password` = ''
                 AND !`cracked`"
            );

            if (!count($hashes)) {
                $result['err_find'][] = $row;
                continue;
            }

            foreach ($hashes as $hashRow) {
                $hashRow->password = $pass;
                $hashRow->cracked = 1;
                $hashRow->save();

                $textData = $hashRow->getDataForTextPathImplementation();
                if (!isset($result['found'][$textData['project']])) {
                    $result['found'][$textData['project']] = [];
                }

                $result['found'][$textData['project']][] = $textData;
            }
        }

        return $result;
    }

    public function markAllHashesByOne($hash) {
        $hashes = $this->fetchAll(
            "`hash` = {$this->getAdapter()->quote($hash->hash)}
                 AND `salt` = {$this->getAdapter()->quote($hash->salt)}
                 AND `alg_id` = '{$hash->alg_id}'
                 AND `password` = ''
                 AND !`cracked`"
        );

        foreach ($hashes as $hashRow) {
            $hashRow->password = $hash->password;
            $hashRow->cracked = 1;
            $hashRow->save();
        }
    }
}