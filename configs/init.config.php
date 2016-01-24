<?php
error_reporting(E_ALL);
ini_set('display_errors',true);

define('__MINITALK__',true);
define('__MINITALK_VERSION__','7.0.0');
define('__MINITALK_DB_PREFIX__','minitalk_');
if (defined('__MINITALK_PATH__') == false) define('__MINITALK_PATH__',str_replace(DIRECTORY_SEPARATOR.'configs','',__DIR__));
if (defined('__MINITALK_DIR__') == false) define('__MINITALK_DIR__',str_replace($_SERVER['DOCUMENT_ROOT'],'',__MINITALK_PATH__));

$domain = isset($_SERVER['HTTPS']) == true ? 'https://' : 'http://';
$domain.= $_SERVER['HTTP_HOST'].__MINITALK_DIR__;
define('__MINITALK_DOMAIN__',$domain);

REQUIRE_ONCE __MINITALK_PATH__.'/classes/functions.php';

$_CONFIGS = new stdClass();
$_ENV = new stdClass();

try {
	$_CONFIGS->key = isset($_CONFIGS->key) == true ? $_CONFIGS->key : FileReadLine(__MINITALK_PATH__.'/configs/key.config.php',1);
	$_CONFIGS->db = isset($_CONFIGS->db) == true ? $_CONFIGS->db : json_decode(Decoder(FileReadLine(__MINITALK_PATH__.'/configs/db.config.php',1)));
} catch (Exception $e) {

}

function __autoload($class) {
	if (file_exists(__MINITALK_PATH__.'/classes/'.$class.'.class.php') == true) REQUIRE_ONCE __MINITALK_PATH__.'/classes/'.$class.'.class.php';
}

session_start();
?>