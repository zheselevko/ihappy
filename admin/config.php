<?php
// HTTP
define('HTTP_SERVER', 'http://' . $_SERVER['SERVER_NAME'] . '/admin/');
define('HTTP_CATALOG', 'http://' . $_SERVER['SERVER_NAME'] . '/');


// HTTPS

// HTTPS
define('HTTPS_SERVER', 'http://' . $_SERVER['SERVER_NAME'] . '/admin/');
define('HTTPS_CATALOG',  'http://' . $_SERVER['SERVER_NAME'] . '/');

// DIR
define('DIR_APPLICATION', $_SERVER['DOCUMENT_ROOT'] . '/admin/');
define('DIR_SYSTEM', $_SERVER['DOCUMENT_ROOT'] . '/system/');
define('DIR_LANGUAGE', $_SERVER['DOCUMENT_ROOT'] . '/admin/language/');
define('DIR_TEMPLATE', $_SERVER['DOCUMENT_ROOT'] . '/admin/view/template/');
define('DIR_CONFIG', $_SERVER['DOCUMENT_ROOT'] . '/system/config/');
define('DIR_IMAGE', $_SERVER['DOCUMENT_ROOT'] . '/image/');
define('DIR_CACHE', $_SERVER['DOCUMENT_ROOT'] . '/system/storage/cache/');
define('DIR_DOWNLOAD', $_SERVER['DOCUMENT_ROOT'] . '/system/storage/download/');
define('DIR_UPLOAD', $_SERVER['DOCUMENT_ROOT'] . '/system/storage/upload/');
define('DIR_MODIFICATION', $_SERVER['DOCUMENT_ROOT'] . '/system/storage/modification/');
define('DIR_LOGS', $_SERVER['DOCUMENT_ROOT'] . '/system/storage/logs/');
define('DIR_CATALOG', $_SERVER['DOCUMENT_ROOT'] . '/catalog');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'ihappy');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');
