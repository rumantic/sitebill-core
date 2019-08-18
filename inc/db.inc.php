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


//$__db = 'estateutf';
//$__db = 'spb';
//$__db = 'neubau';
//$__db = 'grspr';
//$__db = 'dvga';
//$__db = 'xlinfo';
//$__db = 'domakvart';
//$__db = 'noviydom';
//$__db = 'etown';
//$__db = 'kvartiru-dom';
//$__db = 'krasnodar-invest';
//$__db = 'cntop';
//$__db = 'krd';
//$__db = 'realtydomod';
//$__db = 'etown_beta';
//$__db = 'gm36';

//$__db = 'novosel';
//$__db = 'billing';
//$__db = 'kvartira61';
//$__db = 'domanayge';
$__db = 'broker';
//$__db = 'ilimdom';
//$__db = 'perspektiwa';

//$__db = 'estatenewspaper';
//$__db = 'ipn';
//$__db = 'dm';
//$__db = 'chernomor';

//$__db = 'kharkovreal';
//$__db = 'sezon-z';
//$__db = 'pereustipkivspb';
//$__db = 'dombgrf';
//$__db = 'brusnikadom';
//$__db = 'novoseldelete';
//$__db = 'niklitvinov';
//$__db = 'praga';
//$__db = 'tvoyvibor';
//$__db = 'kvartira29';
//$__db = 'tvoe';
//$__db = 'tvoe';
//$__db = 'adlervip';
//$__db = 'apartment';
//$__db = 'nikapsitebill';
//$__db = 'metrpro';
//$__db = 'realia';
//$__db = 'penati';
//$__db = 'whmcs';
//$__db = 'parserxml';
//$__db = 'sitebillutf';
//$__db = 'rassilaika';
$__db_prefix = 're'; // не менять
$__document_root = $_SERVER['DOCUMENT_ROOT'];
define('SITE_ENCODING', 'UTF-8');
define('DB_ENCODING', 'utf8');
define('DEBUG_ENABLED', '0');

/*if (!isset($__connection))
{
	$__connection = mysql_connect($__server, $__user, $__password) 
		or die('Не удалось поключиться к серверу БД (' . mysql_error() . ')');
	mysql_select_db($__db)
		or die ('Не удалось подключиться к БД(' . mysql_error() . ')');
mysql_query("SET NAMES utf8");
}*/


?>
