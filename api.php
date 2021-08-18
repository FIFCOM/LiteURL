<?php
include "config/config.php";
include "pages/lurl.php";
// TODO : verify access token
if (!empty($_REQUEST['token'])) {
    $token = $_REQUEST['token'];
} else if (!empty($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
} else {
    $token = "0";
}
$action = empty($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action === 'create') {
    $svr_name = $_SERVER['HTTP_HOST'] . str_replace('/api.php', '', $_SERVER['PHP_SELF']);
    $uri = !empty($_REQUEST['uri']) && filter_var($_REQUEST['uri'], FILTER_VALIDATE_URL) && strlen($_REQUEST['uri']) < 2048 ? $_REQUEST['uri'] : null;
    if (lurl::userPermission(lurl::apiQueryUser())) {
        $alias = $_REQUEST['alias'] ?? null;
    } else {
        $alias = lurl::rand_str(LURL_MIN_ALIAS_LENGTH);
    }
    $key = isset($_REQUEST['key']) && strlen($_REQUEST['key']) > 0 && strlen($_REQUEST['key']) <= 16 ? $_REQUEST['key'] : false;
    $expire = !empty($_REQUEST['expire']) && $_REQUEST['expire'] >= 0 && $_REQUEST['expire'] <= 366 ? $_REQUEST['expire'] : -1;

    if ($alias && $uri && $expire) {
        if (lurl::userPermission(lurl::apiQueryUser())) {
            if (strlen($alias) >= LURL_MIN_ALIAS_LENGTH
                && strlen($uri) < 2048
            ) {
                $lurlSetStatus = lurl::set($uri, $alias, "$key", $expire);
                if ($lurlSetStatus) $lurlShortURL = $GLOBALS['lurlScheme'] . $svr_name . '/~' . $alias;
                else $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
            } else {
                $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
            }
        } else {
            if (strlen($alias) < LURL_MAX_ALIAS_LENGTH
                && strlen($alias) >= LURL_MIN_ALIAS_LENGTH
                && strlen($uri) < 2048
                && $expire < 367) {
                $lurlSetStatus = lurl::set($uri, $alias, "$key", $expire);
                if ($lurlSetStatus) $lurlShortURL = $GLOBALS['lurlScheme'] . $svr_name . '/~' . $alias;
                else $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
            } else {
                $lurlCardMessage = base64_decode('6ZSZ6K+v77yM6K+36YeN5paw6L6T5YWl5oKo6KaB57yp55+t55qE572R5Z2A');
            }
            require_once("pages/index.html.php");
            exit();
        }
    } else {

    }
}


?>