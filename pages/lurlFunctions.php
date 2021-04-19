<?php
if (!defined('LITEURL_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

function lurlRandomToken($strLength): string
{
    $str = 'qwertyuiopasdfghjklzxcvbnm';
    $str .= 'QWERTYUIOPASDFGHJKLZXCVBNM';
    $str .= '1234567890';
    $token = '';
    for ($it = 0; $it < $strLength; $it++) try {
        $token .= $str[random_int(0, strlen($str) - 1)];
    } catch (Exception $e) {
    }
    return $token;
}

/*
function lurlQRUri($string): string
{
    return 'https://www.zhihu.com/qrcode?url=' . urlencode($string);
}
*/

function lurlSet($uri, $alias, $key, $expire): int
{
    if (lurlIsAliasExist($alias)) return 0;
    if (substr($uri, 0, 7) != "http://" && substr($uri, 0, 8) != "https://") return 0;
    $key = hash("ripemd128", $key);
    $alias = hash("ripemd128", $alias);
    $encryptedUri = base64_encode(openssl_encrypt($uri, 'aes-128-cbc', "$key", OPENSSL_RAW_DATA, LURL_CRYPT_IV));
    $expire = $expire ? ($expire + date("y") * 366 + date("m") * 31 + date("d")) : "999999";
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

function lurlGet($alias, $key)
{
    $key = hash("ripemd128", $key);
    $rawAlias = $alias;
    $alias = hash("ripemd128", $alias);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $result = mysqli_query($conn, "SELECT * FROM lurl WHERE alias='$alias'");
    $row = mysqli_fetch_array($result);
    if (!$row) return 0;
    $encryptedUri = base64_decode($row['uri']);
    $expire = $row['expire'] - date("y") * 366 + date("m") * 31 + date("d");
    if (!$encryptedUri) return 0;
    mysqli_close($conn);
    $uri = openssl_decrypt(base64_decode($row['uri']), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, LURL_CRYPT_IV);
    if ($expire <= 0) {
        lurlDelete($rawAlias);
        return 0;
    }
    lurlCount($rawAlias);
    if (!$uri) return -1;
    else return $uri;
}

function lurlIsAliasExist($alias): int
{
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $alias = hash("ripemd128", $alias);
    $result = mysqli_query($conn, "SELECT * FROM lurl WHERE alias='$alias'");
    $row = mysqli_fetch_array($result);
    if ($row) return 1; else return 0;
}

function lurlDelete($alias): int
{
    $alias = hash("ripemd128", $alias);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    mysqli_query($conn, "DELETE FROM lurl WHERE alias='$alias'");
    mysqli_close($conn);
    return 1;
}

function lurlCount($alias): int
{
    $alias = hash("ripemd128", $alias);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $result = mysqli_query($conn, "SELECT count FROM lurl WHERE alias='$alias'");
    $row = mysqli_fetch_assoc($result);
    $count = $row["count"] + 1;
    if (!$count) return 0;
    mysqli_query($conn, "UPDATE lurl SET count='$count' WHERE alias='$alias'");
    mysqli_close($conn);
    return 1;
}

function lurlUserPermissionGroup($username): int
{
    $username = hash("ripemd128", $username);
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $result = mysqli_query($conn, "SELECT permission_group FROM lurl_username WHERE username='$username'");
    //$row = mysqli_fetch_array($result);
    mysqli_close($conn);
    $pg = $result;
    if (!$pg) return 0;
    else return $pg;
//  SuperAdmin : 3
//  Admin : 2
//  User : 1
//  Guest (not logged in) : 0
}

function lurlUserGetApiToken($username, $password): string
{
    $username = hash("ripemd128", $username);
    $password = hash("ripemd128", $password);

    return "string";
}

function lurlUserLogin(): int
{
    return 0;
}

function lurlUserReg(): int
{
    return 0;
}

$lurlIcon = ICON_URL ?: "https://q.qlogo.cn/headimg_dl?dst_uin=1280874899&spec=640";
$lurlTLSEncryption = TLS_ENCRYPT == "enable" ? "https://" : "http://";
$lurlPrimaryTheme = $_COOKIE['lurlPrimaryTheme'] ?? PRIMARY_THEME;
$lurlAccentTheme = $_COOKIE['lurlAccentTheme'] ?? ACCENT_THEME;
$lurlConsoleCopy = 'console.log(\'%cLiteURL  %c  ' . LITEURL_VERSION . '%cGNU GPL v3\', \'color: #fff; background: #0D47A1; font-size: 15px;border-radius:5px 0 0 5px;padding:10px 0 10px 20px;\',\'color: #fff; background: #42A5F5; font-size: 15px;border-radius:0;padding:10px 15px 10px 0px;\',\'color: #fff; background: #00695C; font-size: 15px;border-radius:0 5px 5px 0;padding:10px 20px 10px 15px;\');console.log(\'%c https://github.com/FIFCOM/LiteURL\', \'font-size: 12px;border-radius:5px;padding:3px 10px 3px 10px;border:1px solid #00695C;\');';
?>

<!--
LiteURL
https://github.com/FIFCOM/LiteURL
-->