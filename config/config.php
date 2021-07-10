<?php
/* Basic settings */
const PRIMARY_THEME = 'teal';
const ACCENT_THEME = 'indigo';
const SITE_NAME = 'LiteURL';
const ICON_URL = 'https://fifcom.cn/avatar/?transparent=1';

/* Advanced settings */
const TLS_ENCRYPT = 'disable';
const LURL_CRYPT_IV = '1234567890123456';
const LITEURL_VERSION = '0.9.9b';

/* DB settings */
const LURL_DB_HOSTNAME = 'localhost';
const LURL_DB_USERNAME = 'root';
const LURL_DB_PASSWORD = 'localhost';
const LURL_DB_NAME = 'pastebin';
const LURL_MIN_ALIAS_LENGTH = '6';
const LURL_MAX_ALIAS_LENGTH = '16';

error_reporting(E_ALL);
$_ERROR = array();