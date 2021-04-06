<?php
require_once("config/lurlConfig.php");
require_once("pages/lurlFunctions.php");
$SvrName = $_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
$lurlDefaltCustomAlias = lurlRandomToken(LURL_SHORTEST_ALIAS_LENGTH);
$lurlCustomUri = isset($_REQUEST['customuri'])?$_REQUEST['customuri']:0;
$lurlCustomAlias = isset($_REQUEST['customalias'])?$_REQUEST['customalias']:0;
$lurlCustomKey = isset($_REQUEST['customkey'])?$_REQUEST['customkey']:0;
$lurlCustomExpire = isset($_REQUEST['customexpire'])?$_REQUEST['customexpire']:0;

if (!$lurlCustomAlias || !$lurlCustomKey || !$lurlCustomUri || !$lurlCustomExpire) {
    $lurlCardMessage = '请输入您要缩短的网址';
    require_once("pages/lurlIndex.php");
    exit();
} else {
    if (lurlIsAdmin())
    {
        
    } else {
        if (strlen($lurlCustomAlias) < LURL_MAX_ALIAS_LENGTH 
            && strlen($lurlCustomAlias) > LURL_MIN_ALIAS_LENGTH 
            && strlen($lurlCustomUri) < 2048 
            && strlen($lurlCustomKey) < 16 
            && strlen($lurlCustomKey) < 16){
            
        }
    }
}

?>