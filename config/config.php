<?php
if( !defined('LITEURL_VERSION' ) ) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}
/* Basic settings */
define('PRIMARY_THEME', 'teal');
define('ACCENT_THEME', 'indigo');
define('ICON_URL', 'https://q.qlogo.cn/headimg_dl?dst_uin=1280874899&spec=640');
/*                */

/* DB settings */
define('LURL_DB_HOSTNAME', 'localhost');
define('LURL_DB_USERNAME', 'root');
define('LURL_DB_PASSWORD', 'root');
define('LURL_DB_NAME', 'liteurl');
/*             */

/* Advanced settings - DO NOT MODIFY */
define('TLS_ENCRYPT', 'enable');
define('LURL_API_KEY', '00000000000000000000000000000000');
define('LURL_ADMIN_PWD', '00000000000000000000000000000000');
define('LITEURL_VERSION', '0.9.9b');
/*                                   */
error_reporting(E_ALL);
$_ERROR = array();