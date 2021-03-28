<?php
if( !defined('LITEURL_VERSION' ) ) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

function lurlRandomToken($strLength)
{
    $str = 'qwertyuiopasdfghjklzxcvbnm';
    $str .= 'QWERTYUIOPASDFGHJKLZXCVBNM';
    $str .= '1234567890';
    $token = '';
    for ($it = 0;$it < $strLength;$it++) $token .= $str[random_int(0, strlen($str) - 1)];
    return $token;
}

function lurlQRUri($string){
    return 'https://www.zhihu.com/qrcode?url='.urlencode($string);
}

function lurlSet($alias, $key){
    $key = hash("ripemd128", $key);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    return 0;
}

function lurlGet($alias, $key){
    $key = hash("ripemd128", $key);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $result = mysqli_query($conn,"SELECT * FROM lurl WHERE alias='$alias'");
    $row = mysqli_fetch_array($result);
    $encryptedUri = $row['uri']; $expire = $row['expire'] - time(); $count = $row['count'];
    $uri = openssl_decrypt($encryptedUri,'aes-128-cbc', $key, OPENSSL_RAW_DATA, LURL_CRYPT_IV);
    if ($expire <= 0) lurlDelete($alias);
    lurlCount($count + 1);
    if (!$uri || $expire <= 0) return 0; else return $uri;
}

function lurlDelete($alias) {
    $key = hash("ripemd128", $key);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    return 0;
}

function lurlCount($count) {
    $key = hash("ripemd128", $key);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    return 0;
}

# SQL format : uri(http(s)://***.**/-?key=customAccessKey-RIPEMD-128-Crypt) alias(/******) expire($time)

$lurlIcon = ICON_URL?ICON_URL:"https://q.qlogo.cn/headimg_dl?dst_uin=1280874899&spec=640";
$lurlTLSEncryption = TLS_ENCRYPT == "enable"?"https://":"http://";
$lurlPrimaryTheme = isset($_COOKIE['lurlPrimaryTheme'])?$_COOKIE['lurlPrimaryTheme']:PRIMARY_THEME;
$lurlAccentTheme = isset($_COOKIE['lurlAccentTheme'])?$_COOKIE['lurlAccentTheme']:ACCENT_THEME;