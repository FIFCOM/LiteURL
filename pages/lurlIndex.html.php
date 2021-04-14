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
    <link rel="shortcut icon" href="<?= $lurlIcon ?>"/>
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
<body class="mdui-appbar-with-toolbar mdui-theme-primary-<?= $lurlPrimaryTheme ?> mdui-theme-accent-<?= $lurlAccentTheme ?> mdui-theme-layout-auto mdui-loaded">
<header class="mdui-appbar mdui-appbar-fixed">
    <div class="mdui-toolbar mdui-color-theme">
        <a href="./" class="mdui-typo-headline"><?= SITE_NAME ?></a>
        <a href="./login" style="position: absolute; right: 5px; border-radius: 100%"
           class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-purple"
           mdui-tooltip="{content: '登录', position: 'bottom'}"><i class="mdui-icon material-icons">account_circle</i></a>
    </div>
    <div class="mdui-toolbar-spacer"></div>
</header>
<div class="mdui-container doc-container">
    <?php if (!$lurlShortURL) echo '<!--'; ?>
    <div class="mdui-card" style="margin-top: 15px;border-radius:10px"><br>
        <div class="mdui-card-content" style="margin-top: -35px">
            <div>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <i class="mdui-icon material-icons">near_me</i>
                    <label class="mdui-textfield-label">您生成的短网址</label>
                    <input class="mdui-textfield-input" type="text" id="lurlShortURL" onclick=copyInput()
                           value="<?= $lurlShortURL ?>"/>
                    <div class="mdui-textfield-helper">轻点或Ctrl+c即可复制</div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!$lurlShortURL) echo '-->'; ?>
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
                    <div class="mdui-textfield mdui-textfield-floating-label">
                        <i class="mdui-icon material-icons">code</i>
                        <label class="mdui-textfield-label">自定义短网址</label>
                        <input class="mdui-textfield-input" type="text" name="customAlias"
                               value="<?= $lurlDefaultCustomAlias ?>" maxlength="<?= LURL_MAX_ALIAS_LENGTH ?>"/>
                        <div class="mdui-textfield-helper">不少于<?= LURL_MIN_ALIAS_LENGTH ?>
                            个字母,默认为<?= $lurlDefaultCustomAlias ?></div>
                    </div>
                    </br>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="6408"/>
                        <i class="mdui-radio-icon"></i>
                        7天有效
                    </label>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="25920" checked/>
                        <i class="mdui-radio-icon"></i>
                        30天有效
                    </label>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="315360"/>
                        <i class="mdui-radio-icon"></i>
                        一年有效
                    </label>

                    <label class="mdui-radio">
                        <input type="radio" name="customExpire" value="0" <?= $lurlNeverExpireStatus ?>/>
                        <i class="mdui-radio-icon"></i>
                        永久有效
                    </label>
                    <p><input style="float: right;" class="mdui-btn mdui-color-theme-accent mdui-ripple" id="mode"
                              type="submit" value="生成短网址"></p>
                    </br>
                </form>

                </br>

            </div>
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
<script>
    !function (n, e) {
        function t(n, e, t) {
            return n.getAttribute(e) || t
        }

        function o(n) {
            return e.getElementsByTagName(n)
        }

        function a() {
            var n = e.currentScript;
            return {
                c: t(n, "color", "0,0,0"),
                n: t(n, "count", 99)
            }
        }

        function i() {
            u = x.width = n.innerWidth, c = x.height = n.innerHeight
        }

        function r() {
            m.clearRect(0, 0, u, c);
            var n, e, t, o, a, i;
            s.forEach(function (r, x) {
                for (r.x += r.xa, r.y += r.ya, r.xa *= r.x > u || r.x < 0 ? -1 : 1, r.ya *= r.y > c || r.y < 0 ?
                    -1 : 1, m.fillRect(r.x - .5, r.y - .5, 1, 1), e = x + 1; e < l.length; e++) n = l[e],
                null !== n.x && null !== n.y && (o = r.x - n.x, a = r.y - n.y, i = o * o + a * a, i < n
                    .max && (n === d && i >= n.max / 2 && (r.x -= .03 * o, r.y -= .03 * a), t = (n.max -
                    i) / n.max, m.beginPath(), m.lineWidth = t / 2, m.strokeStyle = "rgba(" + y.c +
                    "," + (t + .2) + ")", m.moveTo(r.x, r.y), m.lineTo(n.x, n.y), m.stroke()))
            }), f(r)
        }

        var u, c, l, x = e.createElement("canvas"),
            y = a(),
            m = x.getContext("2d"),
            f = n.requestAnimationFrame || function (e) {
                n.setTimeout(e, 1e3 / 60)
            },
            h = Math.random,
            d = {
                x: null,
                y: null,
                max: 2e4
            };
        o("body")[0].appendChild(x), i(), n.onresize = i, n.onmousemove = function (e) {
            e = e || n.event, d.x = e.clientX, d.y = e.clientY
        }, n.onmouseout = function () {
            d.x = null, d.y = null
        };
        for (var s = [], g = 0; y.n > g; g++) {
            var v = h() * u,
                b = h() * c,
                p = 2 * h() - 1,
                T = 2 * h() - 1;
            s.push({
                x: v,
                y: b,
                xa: p,
                ya: T,
                max: 6e3
            })
        }
        l = s.concat([d]), f(r)
    }(window, document)
</script>
<script language="JavaScript">
    function copyInput() {
        var input = document.getElementById("lurlShortURL");
        input.select();
        document.execCommand("Copy");
    }
</script>
<script><?= $lurlConsoleCopy ?></script>
</body>
</html>

<!--
LiteURL
https://github.com/FIFCOM/LiteURL
-->