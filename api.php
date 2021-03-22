<?php
require_once("config.php");
require_once("functions.php");

$SvrName = $_SERVER['HTTP_HOST'].str_replace('/api.php','',$_SERVER['PHP_SELF']);
$apikey=isset($_GET['apikey'])?$_GET['apikey']:0;
$mode=isset($_GET['mode'])?$_GET['mode']:0;


if ($apikey==API_KEY)
{
    $uriTemp=isset($_GET['uri'])?$_GET['uri']:0;
    $uri = base64_decode($uriTemp);
    $alias=isset($_GET['alias'])?$_GET['alias']:0;
    if ($mode != "0"){
        if ($uriTemp!="0"){
            $db_connect= new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_NAME);
            $strSQL="delete from `lite_url` where `uri` = '$uri' limit 1";
            $db_connect->query($strSQL);
            exit;
        }
        if ($alias!="0"){
            $db_connect= new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_NAME);
            $strSQL="delete from `lite_url` where `alias` = '$alias' limit 1";
            $db_connect->query($strSQL);
            exit;
        }
    }
    if ($mode = "read"){

    }
    if ($alias=="0"){
        $alias = RandCRC32b();
    }
    if ($uriTemp=="0"){
        echo "204 Undefined index.(LiteURL)";
    }else{
    $db_connect= new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_NAME);
    $strSQL="select * from `lite_url` where `alias` = '$alias' limit 1";
    $result=$db_connect->query($strSQL);
    $row = mysqli_fetch_assoc($result);
    if ($row > 0) {
        echo "https://$SvrName/$alias ";
    } else {
        $db_connect->query("INSERT INTO `lite_url` VALUES ('$alias', '$uri')");
        echo "https://$SvrName/$alias";
    }}
}else
{
    echo "401 Unauthorized. (LiteURL)";
}

?>

<!--
Author: FIFCOM
https://github.com/FIFCOM/LiteURL
-->