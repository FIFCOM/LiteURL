<?php
require_once("config/lurlConfig.php");
require_once("pages/lurlFunctions.php");
$SvrName = $_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
$lurlDefaultCustomAlias = lurlRandomToken(LURL_MIN_ALIAS_LENGTH);
$lurlCustomUri = isset($_REQUEST['customUri'])?$_REQUEST['customUri']:0;
$lurlCustomAlias = isset($_REQUEST['customAlias'])?$_REQUEST['customAlias']:0;
$lurlCustomKey = isset($_REQUEST['customKey'])?$_REQUEST['customKey']:0;
$lurlCustomExpire = isset($_REQUEST['customExpire'])?$_REQUEST['customExpire']:0;

if (!$lurlCustomAlias || !$lurlCustomKey || !$lurlCustomUri || !$lurlCustomExpire) {
    if (!lurlIsAdmin()) $lurlNeverExpireStatus = "disabled"; else $lurlNeverExpireStatus = "";
    $lurlCardMessage = '请输入您要缩短的网址';
    require_once("pages/lurlIndex.php");
    exit();
} else {
    if (lurlIsAdmin())
    {
        if (strlen($lurlCustomAlias) > LURL_MIN_ALIAS_LENGTH 
            && strlen($lurlCustomUri) < 2048 
            && strlen($lurlCustomKey) < 16)
        {
            // TODO lurlSet
        }
    } else {
        if (strlen($lurlCustomAlias) < LURL_MAX_ALIAS_LENGTH 
            && strlen($lurlCustomAlias) > LURL_MIN_ALIAS_LENGTH 
            && strlen($lurlCustomUri) < 2048 
            && strlen($lurlCustomKey) < 16 
            && $lurlCustomExpire < 315360)
        {
            // TODO lurlSet
        }
    }
}

?>