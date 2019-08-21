<?php
require __DIR__.'/../apps/system/lib/system/sitebill_autoload.php';
$settings = parse_ini_file(__DIR__.'/../settings.ini.php', true);
if (isset($settings['Settings']['estate_folder'])AND ( $settings['Settings']['estate_folder'] != '')) {
    $folder = '/' . $settings['Settings']['estate_folder'];
} else {
    $folder = '';
}
$sitebill_document_root = rtrim(__DIR__.'/../', '/') . $folder;
$_SERVER['DOCUMENT_ROOT'] = $sitebill_document_root;


require_once __DIR__.'/../apps/system/lib/system/globalizator.php';

use system\lib\SiteBill;
use system\lib\system\multilanguage\Multilanguage;
error_reporting(E_ERROR | E_WARNING);
ini_set('display_errors', 'On');


require_once(SITEBILL_DOCUMENT_ROOT . '/apps/third/vendor/smarty/smarty/libs/Smarty.class.php');
