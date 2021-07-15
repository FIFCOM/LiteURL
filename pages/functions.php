<?php
if (!defined('LITEURL_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}
class  lurlFn
{
    /**
     * 生成指定长度的字符串
     * @param $strLength
     * @return string
     */
    public static function randStr($strLength): string
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
    public static function execSql(string $statement): array
    {
        // $statement 不能为空，否则在查询的时候会 Notice
        if (!$statement) {
            $res['length'] = null;
            $res['result'] = null;
            return $res;
        }
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
    public static function QR($string): string
    {
        return 'https://www.zhihu.com/qrcode?url=' . urlencode($string);
    }

    public static function set($uri, $alias, $key, $expire): int
    {
        if (lurlFn::aliasExist($alias)) return 0;
        if (substr($uri, 0, 7) != "http://" && substr($uri, 0, 8) != "https://") return 0;
        $key = hash("md5", $key);
        $alias = hash("md5", $alias);
        $encryptedUri = lurlFn::encrypt($uri, $key);
        $expire = $expire ? ($expire + date("y") * 366 + date("m") * 31 + date("d")) : "999999";
        $res = lurlFn::execSql("INSERT INTO lurl (uri, alias, expire, count) VALUES ('$encryptedUri', '$alias', '$expire', 0)");
        if ($res['length'] !== null) return 1; else return 0;
    }

    /**
     * 加密数据，传入 raw 数据，返回 base64 编码的数据
     * @param $data
     * @param string $password
     * @return string
     */
    public static function encrypt($data, string $password): string
    {
        return base64_encode(openssl_encrypt($data, 'aes-128-cbc', $password, OPENSSL_RAW_DATA, LURL_SECRET_KEY));
    }

    /**
     * 解密数据，传入 raw 数据，返回 base64 编码的数据
     * @param $data
     * @param string $password
     * @return string
     */
    public static function decrypt($data, string $password): string
    {
        return base64_encode(openssl_decrypt($data, 'aes-128-cbc', $password, OPENSSL_RAW_DATA, LURL_SECRET_KEY));
    }

    public static function get($alias, $key)
    {
        $key = hash("md5", $key);
        $rawAlias = $alias;
        $alias = hash("md5", $alias);
        $res = lurlFn::execSql("SELECT * FROM lurl WHERE alias='$alias'");
        if (!$res['length']) return 0;
        $encryptedUri = base64_decode($res['result'][0]['uri']);
        $expire = $res['result'][0]['expire'] - date("y") * 366 + date("m") * 31 + date("d");
        if (!$encryptedUri) return 0;
        $uri = lurlFn::decrypt(base64_decode($res['result'][0]['uri']), $key);
        if ($expire <= 0) {
            lurlFn::delete($rawAlias);
            return 0;
        }
        lurlFn::count($rawAlias);
        if (!$uri) return -1;
        else return base64_decode($uri);
    }

    public static function aliasExist($alias): int
    {
        $alias = hash("md5", $alias);
        $result = lurlFn::execSql("SELECT * FROM lurl WHERE alias='$alias'");
        if ($result['length']) return 1; else return 0;
    }

    public static function delete($alias): int
    {
        $alias = hash("md5", $alias);
        lurlFn::execSql("DELETE FROM lurl WHERE alias='$alias'");
        return 1;
    }

    public static function count($alias): int
    {
        $alias = hash("md5", $alias);
        $result = lurlFn::execSql("SELECT count FROM lurl WHERE alias='$alias'");
        $count = $result['result'][0]["count"] + 1;
        if (!$count) return 0;
        lurlFn::execSql("UPDATE lurl SET count='$count' WHERE alias='$alias'");
        return 1;
    }

    public static function userPermission($username): int
    {
        $username = hash("md5", $username);
        $result = lurlFn::execSql("SELECT * FROM lurl_username WHERE username='$username'");
        if (!$result['length']) return 0;
        return $result['result'][0]["permission_group"];
    }

    public static function getApiToken($username, $password)
    {
        $username = hash("md5", $username);
        $password = hash("md5", $password . $username);
        $result = lurlFn::execSql("SELECT api_token FROM lurl_userdata WHERE username='$username' AND password='$password'");
        if (!$result['length']) return 0;
        return $result['result'][0]["api_token"];
    }

    public static function renewApiToken($username, $password)
    {
        $username = hash("md5", $username);
        $password = hash("md5", $password . $username);
        $result = lurlFn::execSql("SELECT api_token FROM lurl_userdata WHERE username='$username' AND password='$password'");
        if (!$result['length']) return 0;
        $api_token = hash("md5", lurlFn::randStr(32));
        lurlFn::execSql("UPDATE lurl_userdata SET api_token='$api_token' WHERE username='$username' AND password='$password'");
        return $api_token;
    }

    public static function userLogin($username, $password)
    {
        $rawUsername = $username;
        $rawPassword = $password;
        $username = hash("md5", $username);
        $password = hash("md5", $password . $username);
        $result = lurlFn::execSql("SELECT * FROM lurl_userdata WHERE username='$username' AND password='$password'");
        if (!$result['length']) return 0;
        else return base64_encode(openssl_encrypt("!$rawUsername" . "?" . "$rawPassword" . '$', 'aes-128-cbc', hash("md5", $_SERVER['REMOTE_ADDR']), OPENSSL_RAW_DATA, LURL_SECRET_KEY));
    }

    public static function userReg($username, $password, $permission_group): int
    {
        $api_token = hash("md5", lurlFn::randStr(32));
        $username = hash("md5", $username);
        $password = hash("md5", $password . $username);
        $result = lurlFn::execSql("SELECT * FROM lurl_userdata WHERE username='$username'");
        if (!$result['length']) {
            lurlFn::execSql("INSERT INTO lurl_userdata (username, password, permission_group, api_token) VALUES ('$username', '$password', '$permission_group', '$api_token')");
        }
        return 0;
    }

    public static function userDelete($username, $password): int
    {
        $username = hash("md5", $username);
        $password = hash("md5", $password . $username);
        $result = lurlFn::execSql("SELECT * FROM lurl_userdata WHERE username='$username' AND password='$password'");
        if ($result['length']) {
            lurlFn::execSql("DELETE FROM lurl_userdata WHERE username='$username' AND password='$password'");
        }
        return 0;
    }

    public static function loginStats(): bool
    {
        return false;
    }

    public static function apiQueryUser(): string
    {
        return "";
    }
}


$lurlIcon = ICON_URL ?: "https://secure.gravatar.com/avatar/";
if (TLS_ENCRYPT == 'auto' || TLS_ENCRYPT == '') {
    $lurlScheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
} else if (TLS_ENCRYPT == 'disable') $lurlScheme = 'http://'; else if (TLS_ENCRYPT == 'enable') $lurlScheme = 'https://';
$lurlPrimaryTheme = $_COOKIE['lurlPrimaryTheme'] ?? PRIMARY_THEME;
$lurlAccentTheme = $_COOKIE['lurlAccentTheme'] ?? ACCENT_THEME;
$lurlConsoleCopy = 'console.log(\'%cLiteURL  %c  ' . LITEURL_VERSION . '%cGNU GPL v3\', \'color: #fff; background: #0D47A1; font-size: 15px;border-radius:5px 0 0 5px;padding:10px 0 10px 20px;\',\'color: #fff; background: #42A5F5; font-size: 15px;border-radius:0;padding:10px 15px 10px 0px;\',\'color: #fff; background: #00695C; font-size: 15px;border-radius:0 5px 5px 0;padding:10px 20px 10px 15px;\');console.log(\'%c https://github.com/FIFCOM/LiteURL\', \'font-size: 12px;border-radius:5px;padding:3px 10px 3px 10px;border:1px solid #00695C;\');';