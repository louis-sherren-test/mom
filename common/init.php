<?php
define('ROOT_PATH', dirname(dirname(__FILE__)) . '/');
define('LIB_PATH' , ROOT_PATH . 'lib/');
define('TPL_PATH' , ROOT_PATH . 'tpl/');
define('SOURCE_PATH' , ROOT_PATH . 'source/');
define('CUSTOM_PATH' , ROOT_PATH . 'custom/');
define('CONF_PATH' , ROOT_PATH . 'conf/');
define('CORE_PATH',ROOT_PATH . 'core/');
define('ACTION_PATH',ROOT_PATH . 'action/');
define('MODEL_PATH',ROOT_PATH . 'model/');
define('ON_SAE',0);
define('WEB_ROOT','http://localhost/mom');
//set 1 to run in debug mode.
define('DEBUG_MODE', 0); 
session_start();
define('DOCUMENT_ROOT',substr($_SERVER['SCRIPT_NAME'],0,-9));
require_once 'common.php';
require_once CORE_PATH . 'Loykay.class.php';
require_once ROOT_PATH . 'custom/Sae.class.php';

Loykay::init();
Loykay::run();
