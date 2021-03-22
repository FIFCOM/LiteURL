<?php
require_once("config.php");
$SvrName = $_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
$action = isset($_GET['action'])?$_GET['action']:0;

if ($action){

}



















$aliasGET=isset($_GET['alias'])?$_GET['alias']:0;
$aliasPOST=isset($_POST['alias'])?$_POST['alias']:0;
$uri=isset($_POST['alias'])?$_POST['alias']:0;
$fid=isset($_GET['fid'])?$_GET['fid']:0;
//fid
$guestFID = md5($_SERVER['REMOTE_ADDR']);
$adminFID = md5(USER_TOKEN);
