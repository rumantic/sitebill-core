<?php
require_once (__DIR__.'/../../../../inc/db.inc.php');

$settings = parse_ini_file(__DIR__.'/../../../../settings.ini.php', true);
if (isset($settings['Settings']['estate_folder'])AND ( $settings['Settings']['estate_folder'] != '')) {
    $folder = '/' . $settings['Settings']['estate_folder'];
} else {
    $folder = '';
}

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}
if (!defined('DB_HOST')) {
    define('DB_HOST', $__server);
}
if (!defined('DB_PORT')) {
    define('DB_PORT', $__db_port);
}
if (!defined('DB_BASE')) {
    define('DB_BASE', $__db);
}
if (!defined('DB_USER')) {
    define('DB_USER', $__user);
}
if (!defined('DB_PREFIX')) {
    define('DB_PREFIX', $__db_prefix);
}
if (!defined('DB_PASS')) {
    define('DB_PASS', $__password);
}
if (!defined('DB_DSN')) {
    if (defined(DB_PORT) && DB_PORT != '') {
        define('DB_DSN', 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_BASE);
    } else {
        define('DB_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_BASE);
    }
}
if (!defined('DB_ENCODING')) {
    define('DB_ENCODING', 'cp1251');
}

if (!defined('SITE_ENCODING')) {
    define('SITE_ENCODING', 'windows-1251');
}

if (!defined('DEBUG_ENABLED')) {
    define('DEBUG_ENABLED', false);
}

if (!defined('LOG_ENABLED')) {
    define('LOG_ENABLED', false);
}

if (!defined('UPLOADIFY_TABLE')) {
    define('UPLOADIFY_TABLE', DB_PREFIX . '_uploadify');
}

if (!defined('IMAGE_TABLE')) {
    define('IMAGE_TABLE', DB_PREFIX . '_image');
}

if (!defined('MEDIA_FOLDER')) {
    define('MEDIA_FOLDER', SITEBILL_DOCUMENT_ROOT . '/img/data');
}



if (!defined('ESTATE_FOLDER')) {
    define('ESTATE_FOLDER', $folder);
}
if (!defined('SITEBILL_DOCUMENT_ROOT')) {
    define('SITEBILL_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . ESTATE_FOLDER);
}

if (!defined('SITEBILL_APPS_DIR')) {
    define('SITEBILL_APPS_DIR', SITEBILL_DOCUMENT_ROOT . '/apps');
}
if (!defined('SITEBILL_MAIN_URL')) {
    define('SITEBILL_MAIN_URL', ESTATE_FOLDER);
}
