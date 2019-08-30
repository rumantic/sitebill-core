<?php
//error_reporting(E_ALL);
ini_set('display_errors', 'On');

//Создаем приложение
require_once __DIR__.'/apps/system/bootstrap.php';
use factory\Foundation\Application;
require_once __DIR__.'/apps/system/global_functions.php';
$app = new Application();
$app->make();
$app->sendResponse();
