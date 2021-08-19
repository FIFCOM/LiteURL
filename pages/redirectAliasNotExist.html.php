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
    <title><?= SITE_NAME ?> - 短网址生成</title>
</head>
<body class="mdui-appbar-with-toolbar mdui-theme-primary-<?= $lurlPrimaryTheme ?> mdui-theme-accent-<?= $lurlAccentTheme ?> mdui-theme-layout-auto mdui-loaded">
<div class="mdui-container doc-container">
    <div class="mdui-card" style="margin-top: 15px;border-radius:10px">
        <div class="mdui-card-primary">
            <div class="mdui-card-primary-title"><?= SITE_NAME ?> - 短链不存在或已失效</div>
        </div>
        <div class="mdui-card-content" style="margin-top: -35px">
            <div>
                <br>
                <p><a href="<?php echo $lurlTLSEncryption . $SvrName . '/index.php'; ?>" style="float: right;"
                      class="mdui-btn mdui-color-theme-accent mdui-ripple" id="mode"
                    >返回首页</a></p>
                <br>

                <br>

            </div>
        </div>
    </div>

    <br>
    <div style="text-align:center; margin: 0 auto;"><span
                style="color: gray;">Copyright <?= date("20y") ?> <?= SITE_NAME ?></span></div>
</div>

<script
        src="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/js/mdui.min.js"
        integrity="sha384-gCMZcshYKOGRX9r6wbDrvF+TcCCswSHFucUzUPwka+Gr+uHgjlYvkABr95TCOz3A"
        crossorigin="anonymous"
></script>
<script>
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