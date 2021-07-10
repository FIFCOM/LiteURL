<?php
require_once("config/config.php");
require_once("pages/functions.php");
$SvrName = $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['PHP_SELF']);
// $lurlDefaultCustomAlias = lurlRandomToken(LURL_MIN_ALIAS_LENGTH);  -- Deprecated
$lurlCustomUri = $_REQUEST['customUri'] ?? 0;
$lurlCustomAlias = isset($_REQUEST['customAlias']) && strlen($_REQUEST['customAlias']) > 0 ? $_REQUEST['customAlias'] : lurlRandomToken(LURL_MIN_ALIAS_LENGTH);
$lurlCustomKey = isset($_REQUEST['customKey']) && strlen($_REQUEST['customKey']) > 0 ? $_REQUEST['customKey'] : "1";
$lurlCustomExpire = $_REQUEST['customExpire'] ?? 0;
if (!lurlUserPermissionGroup("test")) $lurlNeverExpireStatus = "disabled"; else $lurlNeverExpireStatus = "";

if (!$lurlCustomAlias || !$lurlCustomUri || !$lurlCustomExpire) {
    $lurlCardMessage = base64_decode('6K+36L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
    require_once("pages/index.html.php");
    exit();
} else {
    if (lurlUserPermissionGroup("test")) {
        if (strlen($lurlCustomAlias) >= LURL_MIN_ALIAS_LENGTH
            && strlen($lurlCustomUri) < 2048
            && strlen($lurlCustomKey) < 16) {
            $lurlSetStatus = lurlSet($lurlCustomUri, $lurlCustomAlias, "$lurlCustomKey", $lurlCustomExpire);
            if ($lurlSetStatus) $lurlShortURL = $lurlTLSEncryption . $SvrName . '/~' . $lurlCustomAlias;
            else $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
        } else {
            $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
        }
    } else {
        if (strlen($lurlCustomAlias) < LURL_MAX_ALIAS_LENGTH
            && strlen($lurlCustomAlias) >= LURL_MIN_ALIAS_LENGTH
            && strlen($lurlCustomUri) < 2048
            && strlen($lurlCustomKey) < 16
            && $lurlCustomExpire < 367) {
            $lurlSetStatus = lurlSet($lurlCustomUri, $lurlCustomAlias, "$lurlCustomKey", $lurlCustomExpire);
            if ($lurlSetStatus) $lurlShortURL = $lurlTLSEncryption . $SvrName . '/~' . $lurlCustomAlias;
            else $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
        } else {
            $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
        }
        require_once("pages/index.html.php");
        exit();
    }
}

?>

<!--
LiteURL
https://github.com/FIFCOM/LiteURL
-->
