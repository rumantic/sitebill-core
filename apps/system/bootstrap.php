<?php
require_once __DIR__.'/lib/system/sitebill_autoload.php';
require_once __DIR__.'/lib/system/globalizator.php';
require_once(SITEBILL_DOCUMENT_ROOT . '/apps/third/vendor/smarty/smarty/libs/Smarty.class.php');
$app = new factory\Foundation\Application;
return $app;
