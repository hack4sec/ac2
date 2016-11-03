<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
if (isset($_SERVER['HTTP_HOST']) and $_SERVER['HTTP_HOST'] == 'ac2t') {
    defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));
} else {
    defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/models'),
    realpath(APPLICATION_PATH . '/classes'),
    realpath(APPLICATION_PATH . '/views'),
    get_include_path(),
)));

// Work options check
foreach(array("/storage/", "/tmp/") as $checkPath) {
    if(!is_writable(APPLICATION_PATH . $checkPath))
        die("ERROR: " . APPLICATION_PATH . $checkPath . "/ must be writable!");
}

if (!in_array(strtolower(ini_get('short_open_tag')), ['1', 'on'])) {
    die("Option short_open_tag in php.ini must be enabled (1 or 'on')");
}

if (!class_exists('ZipArchive')) {
    die("Not found class ZipArchive, please install it. See http://php.net/manual/ru/book.zip.php");
}
// Work options check end


require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
Zend_Registry::set('config', $config);

mb_internal_encoding("UTF-8");

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();