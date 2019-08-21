<?php
//Создаем приложение
require_once __DIR__.'/apps/system/bootstrap.php';
use factory\Foundation\Application;
$app = new Application();
$app->make();
$app->sendResponse();
