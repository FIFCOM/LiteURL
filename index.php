<?php
require_once("config/config.php");
require_once("pages/functions.php");
$SvrName = $_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
$lurlDefaltCustomAlias = lurlRandomToken(LURL_SHORTEST_ALIAS_LENGTH);
//lurlSet("https://fifcom.cn/", "123", "123", 0);
//lurlDelete("123");

if (isset($_REQUEST['alias'])) {
    if (isset($_REQUEST['key']))
    {
        $redirectUri = lurlGet("$alias", "$key", "1");
        header('Location: '.$redirectUri);
    } else {
        
        exit;
    }
}

//require_once("pages/lurlIndex.php");
?>