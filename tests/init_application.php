<?php
require __DIR__.'/../apps/system/lib/system/sitebill_autoload.php';

use system\lib\SiteBill;
use system\lib\system\multilanguage\Multilanguage;
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


Sitebill::setLangSession();
Multilanguage::start('backend', $_SESSION['_lang']);
