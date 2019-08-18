<?php
error_reporting(E_ERROR | E_WARNING);
ini_set('display_errors', 'On');

session_start();
require_once("../inc/db.inc.php");

$settings = parse_ini_file('../settings.ini.php', true);
if (isset($settings['Settings']['estate_folder'])AND ( $settings['Settings']['estate_folder'] != '')) {
    $folder = '/' . $settings['Settings']['estate_folder'];
} else {
    $folder = '';
}
$sitebill_document_root = rtrim(__DIR__.'/../', '/') . $folder;

define('SITEBILL_DOCUMENT_ROOT', $sitebill_document_root);
define('SITEBILL_MAIN_URL', $folder);
define('DB_PREFIX', $__db_prefix);

ini_set("include_path", $include_path);
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/third/vendor/smarty/smarty/libs/Smarty.class.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/init.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/db/MySQL.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/sitebill.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/uploadify/uploadify.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/admin/object_manager.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/system/lib/system/multilanguage/multilanguage.class.php');

$sitebill = new SiteBill();
//$sitebill->writeLog(__METHOD__.', '. var_export($_REQUEST, true));

$smarty->template_dir = SITEBILL_DOCUMENT_ROOT . '/apps/admin/admin/template1';
$smarty->cache_dir = SITEBILL_DOCUMENT_ROOT . '/cache/smarty';
$smarty->compile_dir = SITEBILL_DOCUMENT_ROOT . '/cache/compile';

Sitebill::setLangSession();
Multilanguage::start('backend', $_SESSION['_lang']);

require_once(SITEBILL_DOCUMENT_ROOT . '/apps/api/classes/class.common.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/api/classes/class.controller.php');
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/api/classes/class.static_data.php');
$api_controller = new API_Controller();
$api_controller->main();
