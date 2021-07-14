<?php
if (!defined('LITEURL_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}
ini_set('display_errors', 0);
?>
<!doctype html>
<html lang="zh-cmn-Hans">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="LiteURL developed by FIFCOM"/>
    <link rel="shortcut icon" href="<?= $GLOBALS['lurlIcon'] ?>"/>
    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
            integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw"
            crossorigin="anonymous"
    />
    <style>
        html,
        body {
            height: 100%;
            background: rgba(0, 0, 0, .05);
            margin: 0
        }

        section {
            font-family: Roboto, Helvetica, Arial, sans-serif;
            letter-spacing: 2px;
            text-shadow: 1px 1px 2px #d5d5d5;
            color: #333;
            text-align: center;
            -webkit-user-select: none;
            -ms-user-select: none;
            position: relative;
            top: 50%;
            transform: translateY(-50%)
        }

        a {
            color: #333;
            transition: all .5s ease-in-out
        }

        a:hover {
            color: rgba(0, 0, 0, .05)
        }

        canvas {
            pointer-events: none;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: .5
        }
    </style>
    <title><?= SITE_NAME ?> - 短网址生成</title>
</head>
<body class="mdui-appbar-with-toolbar mdui-theme-primary-<?= $GLOBALS['lurlPrimaryTheme'] ?> mdui-theme-accent-<?= $GLOBALS['lurlAccentTheme'] ?> mdui-theme-layout-auto mdui-loaded">
<header class="mdui-appbar mdui-appbar-fixed">
    <div class="mdui-toolbar mdui-color-theme">
        <a href="./" class="mdui-typo-headline"><?= SITE_NAME ?></a>
        <button style="position: absolute; right: 5px; border-radius: 100%"
                class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-purple"
                mdui-tooltip="{content: '登录', position: 'bottom'}" mdui-dialog="{target: '#login'}" type="button"><i
                    class="mdui-icon material-icons">account_circle</i></button>
    </div>
    <div class="mdui-toolbar-spacer"></div>
</header>
<div class="mdui-container doc-container">
    <?php if (!$GLOBALS['lurlShortURL']) echo '<!--'; ?>
    <div class="mdui-card" style="margin-top: 15px;border-radius:10px"><br>
        <div class="mdui-card-content" style="margin-top: -35px">
            <div>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <i class="mdui-icon material-icons">near_me</i>
                    <label class="mdui-textfield-label">您生成的短网址</label>
                    <input class="mdui-textfield-input" type="text" id="lurlShortURL" onclick=copyInput()
                           value="<?= $GLOBALS['lurlShortURL'] ?>"/>
                    <div class="mdui-textfield-helper">轻点或Ctrl+c即可复制</div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!$GLOBALS['lurlShortURL']) echo '-->'; ?>
    <div class="mdui-card" style="margin-top: 15px;border-radius:10px">
        <div class="mdui-card-primary">
            <div class="mdui-card-primary-title"><?= SITE_NAME ?> - 短网址生成</div>
            <div class="mdui-card-primary-subtitle" style="margin-top: 5px"><?= $lurlCardMessage ?></div>
        </div>
        <div class="mdui-card-content" style="margin-top: -35px">
            <div>
                <form action="/index.php#" method="post">
                    <div class="mdui-textfield mdui-textfield-floating-label">
                        <i class="mdui-icon material-icons">add</i>
                        <label class="mdui-textfield-label">网址</label>
                        <input class="mdui-textfield-input" type="text" name="customUri" maxlength="2048"/>
                        <div class="mdui-textfield-helper">(*必填)请加上https://或http://</div>
                    </div>
                    <div class="mdui-textfield mdui-textfield-floating-label">
                        <i class="mdui-icon material-icons">lock</i>
                        <label class="mdui-textfield-label">访问密码</label>
                        <input class="mdui-textfield-input" type="text" name="customKey" maxlength="16"/>
                        <div class="mdui-textfield-helper">默认无密码</div>
                    </div>
                    <?php if (!lurlFn::loginStats()) echo '<!--'; ?>
                    <div class="mdui-textfield mdui-textfield-floating-label">
                        <i class="mdui-icon material-icons">code</i>
                        <label class="mdui-textfield-label">自定义短网址</label>
                        <input class="mdui-textfield-input" type="text" name="customAlias"
                               value="" maxlength="<?= LURL_MAX_ALIAS_LENGTH ?>"/>
                        <div class="mdui-textfield-helper">不少于<?= LURL_MIN_ALIAS_LENGTH ?>
                            个字母
                        </div>
                    </div>
                    <?php if (!lurlFn::loginStats()) echo '-->'; ?>
                    <br>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="7"/>
                        <i class="mdui-radio-icon"></i>
                        一周有效
                    </label>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="31" checked/>
                        <i class="mdui-radio-icon"></i>
                        一个月有效
                    </label>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="366"/>
                        <i class="mdui-radio-icon"></i>
                        一年有效
                    </label>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="0" <?= $lurlNeverExpireStatus ?>/>
                        <i class="mdui-radio-icon"></i>
                        长期有效
                    </label>
                    <p><input style="float: right;" class="mdui-btn mdui-color-theme-accent mdui-ripple" id="mode"
                              type="submit" value="生成短网址"></p>
                    <br>
                </form>

                <br>

            </div>
        </div>
    </div>

    <div class="mdui-dialog" id="login">
        <div class="mdui-dialog-title">登录 / 注册</div>
        <div class="mdui-dialog-content">
            未注册用户将自动创建账户
            <div class="mdui-textfield mdui-textfield-floating-label">
                <i class="mdui-icon material-icons">account_circle</i>
                <label class="mdui-textfield-label">用户名</label>
                <input class="mdui-textfield-input" type="text" required/>
                <div class="mdui-textfield-error">*用户名不能为空</div>
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <i class="mdui-icon material-icons">lock</i>
                <label class="mdui-textfield-label">密码</label>
                <input class="mdui-textfield-input" type="password" required/>
                <div class="mdui-textfield-error">*密码不能为空</div>
            </div>
        </div>
        <div class="mdui-dialog-actions">
            <button class="mdui-btn mdui-ripple" type="submit">登录 / 注册</button>
            <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
        </div>
    </div>

    </br>
    <div style="text-align:center; margin: 0px auto;"><span
                style="color: gray;">Copyright <?= date("20y") ?> <?= SITE_NAME ?></span></div>
</div>

<script
        src="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/js/mdui.min.js"
        integrity="sha384-gCMZcshYKOGRX9r6wbDrvF+TcCCswSHFucUzUPwka+Gr+uHgjlYvkABr95TCOz3A"
        crossorigin="anonymous"
></script>
<script src="https://cdn.staticfile.org/blueimp-md5/2.18.0/js/md5.min.js"></script>
<script>
    function copyInput() {
        let input = document.getElementById("lurlShortURL");
        input.select();
        document.execCommand("Copy");
    }
</script>
<script src="pages/bg_anime.js"></script>
<script><?= $GLOBALS['lurlConsoleCopy'] ?></script>
</body>
</html>

<!--
LiteURL
https://github.com/FIFCOM/LiteURL
-->