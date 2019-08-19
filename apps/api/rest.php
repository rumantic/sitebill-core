<?php
require __DIR__.'/../../apps/system/lib/system/sitebill_autoload.php';
require_once("../../inc/db.inc.php");

use system\lib\SiteBill;
use system\lib\system\uploadify\Sitebill_Uploadify;
use system\lib\system\multilanguage\multilanguage;
use system\lib\admin\Object_Manager;

/**
 * REST API
 * @author Kondin Dmitriy <kondin@etown.ru> http://www.sitebill.ru
 */
error_reporting(E_ERROR | E_WARNING);
ini_set('display_errors', 'On');
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
//header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
//cors();
//header('Access-Control-Allow-Origin: *');

session_start();

$settings = parse_ini_file('../../settings.ini.php', true);
if (isset($settings['Settings']['estate_folder'])AND ( $settings['Settings']['estate_folder'] != '')) {
    $folder = '/' . $settings['Settings']['estate_folder'];
} else {
    $folder = '';
}
$sitebill_document_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $folder;

define('SITEBILL_DOCUMENT_ROOT', $sitebill_document_root);
define('SITEBILL_MAIN_URL', $folder);
define('DB_PREFIX', $__db_prefix);

ini_set("include_path", $include_path);
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/third/vendor/smarty/smarty/libs/Smarty.class.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/init.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/db/MySQL.php');

$sitebill = new SiteBill();
//$sitebill->writeLog(__METHOD__.', '. var_export($_REQUEST, true));
Sitebill::setLangSession();
Multilanguage::start('backend', $_SESSION['_lang']);

require_once(SITEBILL_DOCUMENT_ROOT . '/apps/api/classes/class.common.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/api/classes/class.controller.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/api/classes/class.static_data.php');
$api_controller = new API_Controller();
$api_controller->main();
