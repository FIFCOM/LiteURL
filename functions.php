<?php
function Randomstr()
{
    //random crc32b string
    $time = uniqid();
    return dechex(crc32("$time"));
}