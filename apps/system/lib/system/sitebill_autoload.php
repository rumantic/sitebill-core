<?php
spl_autoload_register(function ($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once $_SERVER['DOCUMENT_ROOT'] . '/apps/' . $className . '.php';
});

require_once SITEBILL_DOCUMENT_ROOT . '/apps/third/vendor/autoload.php';
