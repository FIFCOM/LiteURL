<?php
// deprecated --- fn will move to index.php
include_once "config/config.php";
include_once "pages/lurl.php";
$SvrName = $_SERVER['HTTP_HOST'] . str_replace('/redirect.php', '', $_SERVER['PHP_SELF']);

$lurlRedirectAlias = isset($_REQUEST['alias']) ? substr($_REQUEST['alias'], 1, strlen($_REQUEST['alias'])) : 0;
$lurlRedirectKey = isset($_REQUEST['key']) && strlen($_REQUEST['key']) > 0 ? $_REQUEST['key'] : 0;

if ($lurlRedirectAlias) {
    if ($lurlRedirectKey) {
        $lurlRedirectUri = lurl::get($lurlRedirectAlias, $lurlRedirectKey);
        if ($lurlRedirectUri == -1) {
            $lurlCardMessage = "访问密码错误，请重试";
            require_once("pages/redirectKeyValidate.html.php");
            exit();
        } else if ($lurlRedirectUri) {
            header('Location: ' . $lurlRedirectUri);
        } else {
            require_once("pages/redirectAliasNotExist.html.php");
            exit();
        }
    } else {
        $lurlRedirectUri = lurl::get($lurlRedirectAlias, "1");
        if ($lurlRedirectUri == -1) {
            $lurlCardMessage = "请输入访问密码";
            require_once("pages/redirectKeyValidate.html.php");
            exit();
        } else if ($lurlRedirectUri) {
            header('Location: ' . $lurlRedirectUri);
        } else {

            require_once("pages/redirectAliasNotExist.html.php");
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