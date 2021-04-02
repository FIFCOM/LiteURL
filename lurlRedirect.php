<?php
require_once("config/lurlConfig.php");
require_once("pages/lurlFunctions.php");
$SvrName = $_SERVER['HTTP_HOST'].str_replace('/lurlRedirect.php','',$_SERVER['PHP_SELF']);

$lurlRedirectAlias = isset($_REQUEST['alias'])?$_REQUEST['alias']:0;
$lurlRedirectKey = isset($_REQUEST['key'])?$_REQUEST['key']:0;

if ($lurlRedirectAlias == "login") {
    header('Location: '.$lurlTLSEncryption.$SvrName.'/lurlAdmin.php?action=login&from=redirect.php');
}

if ($lurlRedirectAlias) {
    if ($lurlRedirectKey)
    {
        $lurlRedirectUri = lurlGet($lurlRedirectAlias, $lurlRedirectKey, "1");
        if ($lurlRedirectUri == -1) {
            require_once("pages/lurlRedirectKeyError.html.php");
            exit();
        } else if ($lurlRedirectUri){
            header('Location: '.$lurlRedirectUri);
        } else {
            require_once("pages/lurlRedirectAliasDoesntExist.html.php");
            exit();
        }
    } else {
        $lurlRedirectUri = lurlGet($lurlRedirectAlias, "0", "1");
        if ($lurlRedirectUri == -1) {
            require_once("pages/lurlRedirectKeyValidat.html.php");
            exit();
        } else if ($lurlRedirectUri){
            header('Location: '.$lurlRedirectUri);
        } else {
            require_once("pages/lurlRedirectAliasNotExist.html.php");
            exit();
        }
    }
}
?>