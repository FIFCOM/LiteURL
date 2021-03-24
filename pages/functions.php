<?php
if( !defined('LITEURL_VERSION' ) ) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

function lurlRandomStr($strLength)
{
    if (!isset($strLength)) $strLength = 8;
    $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    return substr(str_shuffle($str), 1, $strLength);
}

function lurlQRUri($string){
    return 'https://www.zhihu.com/qrcode?url='.urlencode($string);
}

function lurlWrite($string){
    return 0;
}

function lurlRead($string){
    return 0;
}

function lurlAccessKeyValidat($string){
    return 0;
}

# SQL format : uri(http(s)://***.**/) alias(/******) expire($time) key(?key=customAccessKey-RIPEMD-128-Crypt) 

$lurlIcon = ICON_URL?ICON_URL:"https://q.qlogo.cn/headimg_dl?dst_uin=1280874899&spec=640";
$lurlTLSEncryption = TLS_ENCRYPT == "enable"?"https://":"http://";
$lurlPrimaryTheme = isset($_COOKIE['lurlPrimaryTheme'])?$_COOKIE['lurlPrimaryTheme']:PRIMARY_THEME;
$lurlAccentTheme = isset($_COOKIE['lurlAccentTheme'])?$_COOKIE['lurlAccentTheme']:ACCENT_THEME;