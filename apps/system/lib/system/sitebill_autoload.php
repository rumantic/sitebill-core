<?php
spl_autoload_register(function ($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $file_name = $_SERVER['DOCUMENT_ROOT'] . '/apps/' . $className . '.php';
    if ( file_exists($file_name) ) {
        include_once $file_name;
    }
});
if ( defined(SITEBILL_DOCUMENT_ROOT) ) {
    require_once SITEBILL_DOCUMENT_ROOT . '/apps/third/vendor/autoload.php';
}
