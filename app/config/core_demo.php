
<?php
session_start();
define('ENV_PRODUCTION', false);
define('APP_HOST', $_SERVER['HTTP_HOST']);
define('APP_BASE_PATH', '/');
define('APP_URL', 'http://' . APP_HOST . '/');
define('DB_DSN', 'mysql:host=xxxx;dbname=xxxx');
define('DB_USERNAME', 'xxxx');
define('DB_PASSWORD', 'xxxx');
define('DB_ATTR_TIMEOUT', 3);
define('TK_DB_DSN', 'mysql:host=xxxxx;dbname=xxxx');
define('TK_DB_USERNAME', 'xxxx');
define('TK_DB_PASSWORD', 'xxxx');
define('BOOK_COVERS', 'book_covers/');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
ini_set('error_log', LOGS_DIR . 'php.log');
ini_set('session.auto_start', 0);
define('GOOGLE_APP_NAME', "book-management-system");
define('GOOGLE_CLIENT_ID', "xxxxx");
define('GOOGLE_CLIENT_SECRET', "xxxxx");
define('GOOGLE_CLIENT_REDIRECT', "http://bookmanagementsystem.url.ph/main/login");
define('GOOGLE_CLIENT_DEV_KEY', "xxxxx");
define('DEBUG_DUMP_EXCEPTION', 0);
