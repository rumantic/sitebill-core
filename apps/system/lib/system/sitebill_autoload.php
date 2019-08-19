<?php
spl_autoload_register(function ($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $file_name = $document_root . '/apps/' . $className . '.php';

    if ( $className == 'system\lib\model\Data_Model' ) {
        include_once $document_root . '/apps/system/lib/model/model.php';
        return;
    }
    //echo $className.'<br>';
    if ( @file_exists($file_name) ) {
        include_once $file_name;
    } else {
        $file_name = $document_root . '/apps/' . $className . '.class.php';
        if ( @file_exists($file_name) ) {
            include_once $file_name;
        } else {
            if ( preg_match('/API_/', $className) ) {
                $className = strtolower($className);
                $className = str_replace('api_', 'class.', $className);
                $file_name = $document_root . '/apps/' . $className . '.php';
                if ( @file_exists($file_name) ) {
                    include_once $file_name;
                }
            }
        }
    }

});
if ( defined(SITEBILL_DOCUMENT_ROOT) ) {
    require_once SITEBILL_DOCUMENT_ROOT . '/apps/third/vendor/autoload.php';
}
