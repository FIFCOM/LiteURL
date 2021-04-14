<?php
require_once("config/lurlConfig.php");
require_once("pages/lurlFunctions.php");
$SvrName = $_SERVER['HTTP_HOST'] . str_replace('/lurlRedirect.php', '', $_SERVER['PHP_SELF']);

$lurlRedirectAlias = isset($_REQUEST['alias']) ? substr($_REQUEST['alias'], 1, strlen($_REQUEST['alias'])) : 0;
$lurlRedirectKey = isset($_REQUEST['key']) && strlen($_REQUEST['key']) > 0 ? $_REQUEST['key'] : 0;

if ($lurlRedirectAlias) {
    if ($lurlRedirectKey) {
        $lurlRedirectUri = lurlGet($lurlRedirectAlias, $lurlRedirectKey, "1");
        if ($lurlRedirectUri == -1) {
            require_once("pages/lurlRedirectKeyError.html.php");
            exit();
        } else if ($lurlRedirectUri) {
            header('Location: ' . $lurlRedirectUri);
        } else {
            require_once("pages/lurlRedirectAliasDoesntExist.html.php");
            exit();
        }
    } else {
        $lurlRedirectUri = lurlGet($lurlRedirectAlias, "1", "1");
        if ($lurlRedirectUri == -1) {
            require_once("pages/lurlRedirectKeyValidate.html.php");
            exit();
        } else if ($lurlRedirectUri) {
            header('Location: ' . $lurlRedirectUri);
        } else {
            require_once("pages/lurlRedirectAliasNotExist.html.php");
            exit();
        }
    }
} else {
    header('Location: ' . $lurlTLSEncryption . $SvrName . '/index.php');
}
?>

<!--
LiteURL
https://github.com/FIFCOM/LiteURL
-->