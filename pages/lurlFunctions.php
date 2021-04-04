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

function lurlSet($uri, $alias, $key, $expire){
    if (lurlGet($alias, $key, "0") != 0) return 0;
    $key = hash("ripemd128", $key);
    $alias = hash("ripemd128", $alias);
    $encryptedUri = base64_encode(openssl_encrypt($uri,'aes-128-cbc', $key, OPENSSL_RAW_DATA, LURL_CRYPT_IV));
    $expire = $expire?$expire+date("ymdHis"):"999999999999";
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $sql = "INSERT INTO lurl (uri, alias, expire, count) VALUES ('$encryptedUri', '$alias', '$expire', 0)";
    if ($conn->query($sql) === TRUE) {
        mysqli_close($conn);
        return 1;
    } else {
        mysqli_close($conn);
        return 0;
    }
}

function lurlGet($alias, $key, $countpp, $action){
    $key = hash("ripemd128", $key);
    $rawAlias = $alias;
    $alias = hash("ripemd128", $alias);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $result = mysqli_query($conn,"SELECT * FROM lurl WHERE alias='$alias'");
    $row = mysqli_fetch_array($result);
    $encryptedUri = base64_decode($row['uri']); $expire = $row['expire'] - date("ymdHis"); $count = $row['count'];
    if (!$encryptedUri) return 0; //lurlRedirectAliasDoesntExist
    mysqli_close($conn);
    $uri = openssl_decrypt($encryptedUri,'aes-128-cbc', $key, OPENSSL_RAW_DATA, LURL_CRYPT_IV);
    if ($expire <= 0) {lurlDelete($rawAlias); return 0;}
    if ($countpp != "0") lurlCount($rawAlias);
    if (!$uri) return -1; //lurlRedirectKeyError
    else return $uri;
}


function lurlDelete($alias) {
    $alias = hash("ripemd128", $alias);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    mysqli_query($conn,"DELETE FROM lurl WHERE alias='$alias'");
    mysqli_close($conn);
    return 1;
}

function lurlCount($alias) {
    $alias = hash("ripemd128", $alias);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $result = mysqli_query($conn,"SELECT * FROM lurl WHERE alias='$alias'");
    $row = mysqli_fetch_array($result);
    $count = $row['count'] + 1;
    if (!$count) return 0;
    mysqli_query($conn,"UPDATE lurl SET count='$count' WHERE alias='$alias'");
    mysqli_close($conn);
    return 1;
}

$lurlIcon = ICON_URL?ICON_URL:"https://q.qlogo.cn/headimg_dl?dst_uin=1280874899&spec=640";
$lurlTLSEncryption = TLS_ENCRYPT == "enable"?"https://":"http://";
$lurlPrimaryTheme = isset($_COOKIE['lurlPrimaryTheme'])?$_COOKIE['lurlPrimaryTheme']:PRIMARY_THEME;
$lurlAccentTheme = isset($_COOKIE['lurlAccentTheme'])?$_COOKIE['lurlAccentTheme']:ACCENT_THEME;