<?php
if (!defined('LITEURL_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

/**
 * 生成指定长度的字符串
 * @param $strLength
 * @return string
 */
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

/**
 * LiteURL Execute SQL Statement <br>
 * 执行SQL语句, 返回数组
 * @param $statement string
 * @return array
 */
function lurlExecSqlStmt(string $statement): array
{
    $conn = mysqli_connect(LURL_DB_HOSTNAME, LURL_DB_USERNAME, LURL_DB_PASSWORD, LURL_DB_NAME);
    if (mysqli_connect_errno()) echo "Lite URL MySQL Connect Error : " . mysqli_connect_error();
    $result = $conn->query($statement);
    // $result 的类型 : 查询成功为 object, 否则为 boolean
    if (gettype($result) !== "boolean") {
        // 所以只要查询成功，length一定 >= 0 且不为null
        $res['length'] = mysqli_num_rows($result) + 0;
        if ($res['length'] == 0) {
            $res['result'] = null;
        } else {
            for ($l = 0; $row = mysqli_fetch_array($result); $l++)
                foreach ($row as $key => $value)
                    $res['result'][$l][$key] = $value;
        }
    } else {
        // 查询失败则 length === null
        $res['length'] = null;
        $res['result'] = null;
    }
    mysqli_close($conn);
    return $res;
}

/**
 * 返回string的二维码链接
 * @param $string
 * @return string
 */
function lurlQRUri($string): string
{
    return 'https://www.zhihu.com/qrcode?url=' . urlencode($string);
}

function lurlSet($uri, $alias, $key, $expire): int
{
    if (lurlIsAliasExist($alias)) return 0;
    if (substr($uri, 0, 7) != "http://" && substr($uri, 0, 8) != "https://") return 0;
    $key = hash("ripemd128", $key);
    $alias = hash("ripemd128", $alias);
    $encryptedUri = base64_encode(openssl_encrypt($uri, 'aes-128-cbc', "$key", OPENSSL_RAW_DATA, LURL_SECRET_KEY));
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
    $res = lurlExecSqlStmt("SELECT * FROM lurl WHERE alias='$alias'");
    if (!$res['length']) return 0;
    $encryptedUri = base64_decode($res['result'][0]['uri']);
    $expire = $res['result'][0]['expire'] - date("y") * 366 + date("m") * 31 + date("d");
    if (!$encryptedUri) return 0;
    $uri = openssl_decrypt(base64_decode($res['result'][0]['uri']), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, LURL_SECRET_KEY);
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
    $alias = hash("ripemd128", $alias);
    $result = lurlExecSqlStmt("SELECT * FROM lurl WHERE alias='$alias'");
    if ($result['length']) return 1; else return 0;
}

function lurlDelete($alias): int
{
    $alias = hash("ripemd128", $alias);
    lurlExecSqlStmt("DELETE FROM lurl WHERE alias='$alias'");
    return 1;
}

function lurlCount($alias): int
{
    $alias = hash("ripemd128", $alias);
    $result = lurlExecSqlStmt("SELECT count FROM lurl WHERE alias='$alias'");
    $count = $result['result'][0]["count"] + 1;
    if (!$count) return 0;
    lurlExecSqlStmt("UPDATE lurl SET count='$count' WHERE alias='$alias'");
    return 1;
}

function lurlUserPermissionGroup($username): int
{
    $username = hash("ripemd128", $username);
    $result = lurlExecSqlStmt("SELECT * FROM lurl_username WHERE username='$username'");
    if (!$result['length']) return 0;
    return $result['result'][0]["permission_group"];
}

function lurlGetApiToken($username, $password)
{
    $username = hash("ripemd128", $username);
    $password = hash("ripemd128", $password . $username);
    $result = lurlExecSqlStmt("SELECT api_token FROM lurl_userdata WHERE username='$username' AND password='$password'");
    if (!$result['length']) return 0;
    return $result['result'][0]["api_token"];
}

function lurlRenewApiToken($username, $password)
{
    $username = hash("ripemd128", $username);
    $password = hash("ripemd128", $password . $username);
    $result = lurlExecSqlStmt("SELECT api_token FROM lurl_userdata WHERE username='$username' AND password='$password'");
    if (!$result['length']) return 0;
    $api_token = hash("ripemd128", lurlRandomToken(32));
    lurlExecSqlStmt("UPDATE lurl_userdata SET api_token='$api_token' WHERE username='$username' AND password='$password'");
    return $api_token;
}

function lurlUserLogin($username, $password)
{
    $rawUsername = $username;
    $rawPassword = $password;
    $username = hash("ripemd128", $username);
    $password = hash("ripemd128", $password . $username);
    $result = lurlExecSqlStmt("SELECT * FROM lurl_userdata WHERE username='$username' AND password='$password'");
    if (!$result['length']) return 0;
    else return base64_encode(openssl_encrypt("!$rawUsername" . "?" . "$rawPassword" . '$', 'aes-128-cbc', hash("ripemd128", $_SERVER['REMOTE_ADDR']), OPENSSL_RAW_DATA, LURL_SECRET_KEY));
}

function lurlUserReg($username, $password, $permission_group): int
{
    $api_token = hash("ripemd128", lurlRandomToken(32));
    $username = hash("ripemd128", $username);
    $password = hash("ripemd128", $password . $username);
    $result = lurlExecSqlStmt("SELECT * FROM lurl_userdata WHERE username='$username'");
    if (!$result['length']) {
        lurlExecSqlStmt("INSERT INTO lurl_userdata (username, password, permission_group, api_token) VALUES ('$username', '$password', '$permission_group', '$api_token')");
    }
    return 0;
}

function lurlUserDelete($username, $password): int
{
    $username = hash("ripemd128", $username);
    $password = hash("ripemd128", $password . $username);
    $result = lurlExecSqlStmt("SELECT * FROM lurl_userdata WHERE username='$username' AND password='$password'");
    if ($result['length']) {
        lurlExecSqlStmt("DELETE FROM lurl_userdata WHERE username='$username' AND password='$password'");
    }
    return 0;
}

$lurlIcon = ICON_URL ?: "https://q.qlogo.cn/headimg_dl?dst_uin=1280874899&spec=640";
$lurlTLSEncryption = TLS_ENCRYPT == "enable" ? "https://" : "http://";
$lurlPrimaryTheme = $_COOKIE['lurlPrimaryTheme'] ?? PRIMARY_THEME;
$lurlAccentTheme = $_COOKIE['lurlAccentTheme'] ?? ACCENT_THEME;
$lurlConsoleCopy = 'console.log(\'%cLiteURL  %c  ' . LITEURL_VERSION . '%cGNU GPL v3\', \'color: #fff; background: #0D47A1; font-size: 15px;border-radius:5px 0 0 5px;padding:10px 0 10px 20px;\',\'color: #fff; background: #42A5F5; font-size: 15px;border-radius:0;padding:10px 15px 10px 0px;\',\'color: #fff; background: #00695C; font-size: 15px;border-radius:0 5px 5px 0;padding:10px 20px 10px 15px;\');console.log(\'%c https://github.com/FIFCOM/LiteURL\', \'font-size: 12px;border-radius:5px;padding:3px 10px 3px 10px;border:1px solid #00695C;\');';