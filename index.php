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
} else {
    if (strlen($lurlCustomAlias) < LURL_ALIAS_MAX_LENGTH && strlen($title) < TITLE_MAX_LENGTH){
        if ($fidCookieCallbackURI == "pages/pastebinPlainEditor.html.php") {$type = "1";} elseif ($fidCookieCallbackURI == "pages/pastebinMarkdownEditor.html.php") {$type = "2";}
        $pastebinURL = pastebinWrite($pastebin, $title, $type);
        if ($pastebinRefID) pastebinSenderWrite($pastebinURL, $pastebinRefID);
        $pastebinQR = pastebinQRUri($pastebinTLSEncryption.$SvrName.'/'.$pastebinURL, '1');
        $pastebinQRRawURL = pastebinQRUri($pastebinTLSEncryption.$SvrName.'/'.$pastebinURL, '0');
        $pastebinCardMessage = '创建成功! 链接: <span><code><a href="'.$pastebinTLSEncryption.$SvrName.'/'.$pastebinURL.'" target="_blank"><abbr title="打开链接">'.$pastebinTLSEncryption.$SvrName.'/'.$pastebinURL.'</abbr></a></code></span>';
    } else {
    $pastebinCardMessage = '标题过长(不超过'.TITLE_MAX_LENTH.'字)或内容过大(不大于'. PASTEBIN_MAX_LENTH/1024 .'KB)[PB_ERR_TOO_BIG]';
    }
    require_once("pages/lurlIndex.php");
}

?>