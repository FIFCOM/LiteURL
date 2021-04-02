<?php
/* Basic settings */
define('PRIMARY_THEME', 'teal');
define('ACCENT_THEME', 'indigo');
define('ICON_URL', 'https://q.qlogo.cn/headimg_dl?dst_uin=1280874899&spec=640');
/*                */

/* DB settings */
define('LURL_DB_HOSTNAME', 'localhost');
define('LURL_DB_USERNAME', 'root');
define('LURL_DB_PASSWORD', 'localhost');
define('LURL_DB_NAME', 'liteurl');
define('LURL_MIN_ALIAS_LENGTH', '6');
define('LURL_MAX_ALIAS_LENGTH', '16');
/*             */

/* Advanced settings */
define('TLS_ENCRYPT', 'enable');
define('LURL_API_KEY', '00000000000000000000000000000000');
define('LURL_ADMIN_PWD', '00000000000000000000000000000000');
define('LURL_CRYPT_IV', '1234567890123456');
define('LITEURL_VERSION', '0.9.9b');
/*                                   */
error_reporting(E_ALL);
$_ERROR = array();