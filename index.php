<?php
require_once("config/config.php");
require_once("pages/functions.php");
$SvrName = $_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
$lurlDefaltCustomAlias = lurlRandomToken(LURL_SHORTEST_ALIAS_LENGTH);
//lurlSet("https://bilibili.com/", "bili", "0", 0);
lurlDelete("bili");

if (isset($_REQUEST['alias'])) {
    if (isset($_REQUEST['key']))
    {
        $redirectUri = lurlGet($_REQUEST['alias'], $_REQUEST['key'], "1");
        if ($redirectUri) {
            header('Location: '.$redirectUri);
        } else {
            header('Location: '.$lurlTLSEncryption.$SvrName.'/?xxx');
        }
        
    } else {
        $redirectUri = lurlGet($_REQUEST['alias'], 0, "1");
        header('Location: '.$redirectUri);
    }
}

require_once("pages/lurlIndex.php");
?>