<?php
require_once("config/lurlConfig.php");
require_once("pages/lurlFunctions.php");
$SvrName = $_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
$lurlDefaultCustomAlias = lurlRandomToken(LURL_MIN_ALIAS_LENGTH);
$lurlCustomUri = $_REQUEST['customUri'] ?? 0;
$lurlCustomAlias = $_REQUEST['customAlias'] ?? 0;
$lurlCustomKey = isset($_REQUEST['customKey']) && strlen($_REQUEST['customKey']) > 0?$_REQUEST['customKey']:"1";
//strlen($_REQUEST['customKey']) > 0 :: 当$_REQUEST['customKey']为空时isset($_REQUEST['customKey'])仍为真，所以要用strlen再判断一次
$lurlCustomExpire = $_REQUEST['customExpire'] ?? 0;
if (!lurlIsAdmin()) $lurlNeverExpireStatus = "disabled"; else $lurlNeverExpireStatus = "";

if (!$lurlCustomAlias || !$lurlCustomUri || !$lurlCustomExpire) {
    $lurlCardMessage = base64_decode('6K+36L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
    require_once("pages/lurlIndex.php");
    exit();
} else {
    if (lurlIsAdmin())
    {
        if (strlen($lurlCustomAlias) >= LURL_MIN_ALIAS_LENGTH
            && strlen($lurlCustomUri) < 2048 
            && strlen($lurlCustomKey) < 16)
        {
            // TODO lurlSet
        }
    } else {
        if (strlen($lurlCustomAlias) < LURL_MAX_ALIAS_LENGTH
            && strlen($lurlCustomAlias) >= LURL_MIN_ALIAS_LENGTH
            && strlen($lurlCustomUri) < 2048 
            && strlen($lurlCustomKey) < 16 
            && $lurlCustomExpire < 315360)
        {
            $lurlSetStatus = lurlSet($lurlCustomUri, $lurlCustomAlias, "$lurlCustomKey", $lurlCustomExpire);
            if ($lurlSetStatus) $lurlShortURL = $lurlTLSEncryption.$SvrName.'/~'.$lurlCustomAlias;
        } else {
            $lurlCardMessage = base64_decode('6K+36L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
        }
        require_once("pages/lurlIndex.php");
        exit();
    }

}

?>

<!--
LiteURL
https://github.com/FIFCOM/LiteURL
-->
