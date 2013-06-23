<?php

$url = "http://localhost/mom/test/update.php";

$ch = curl_init($url);

$opt = array(
    CURLOPT_RETURNTRANSFER => 1,
);

curl_setopt_array($ch,$opt);

$ret = curl_exec($ch);

echo $ret;
