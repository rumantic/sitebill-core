<?php
require_once __DIR__.'/apps/third/vendor/autoload.php';
require __DIR__.'/apps/system/lib/system/sitebill_autoload.php';

//Создаем приложение
$app = require_once __DIR__.'/apps/system/bootstrap.php';
$kernel = $app->make();

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();

echo '<br>complete<br>';
