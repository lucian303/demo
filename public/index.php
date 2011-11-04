<?php

// Define path to application directory
// this is better handled in php or apache conf but runtime is OK for portablity
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'), //for local libraries
	realpath(APPLICATION_PATH . '/models'), //for models to be accessed from our library functions
	realpath('/Users/lhontau/Desktop/htdocs/ZendFramework-1.11.7/library'), //for ZF ... would want to set this at the apache level for the different environments if possible
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();