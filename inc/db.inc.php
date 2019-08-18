<?php
$__server = 'localhost';
$__user = 'root';
$__password = '';

if(!defined('DEBUG_ENABLED')){
	define('DEBUG_ENABLED',false);
}
if(!defined('LOG_ENABLED')){
	define('LOG_ENABLED',false);
}
if(!defined('LOGGER_FILE')){
	define('LOGGER_FILE',$_SERVER['DOCUMENT_ROOT'].'/debug.txt');
}
if(!defined('STR_MEDIA')){
	define('STR_MEDIA',true);
}
if(!defined('STR_MEDIA_FOLDERFDAYS')){
    
    define('STR_MEDIA_FOLDERFDAYS', 1);
}

$__db = 'broker';
$__db_prefix = 're'; // не менять
$__document_root = $_SERVER['DOCUMENT_ROOT'];
define('SITE_ENCODING', 'UTF-8');
define('DB_ENCODING', 'utf8');
define('DEBUG_ENABLED', '0');
?>
