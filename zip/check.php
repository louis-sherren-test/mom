<?php
$config = require "../conf/core.php";
$version = $config["VERSION"];
$url = "http://localhost/mom/test/check.php";

$ch = curl_init($url);
$opt = array(
    CURLOPT_RETURNTRANSFER => 1,
);

curl_setopt_array($ch,$opt);
$ret = curl_exec($ch);
$json = json_decode($ret,true);
$verarr = explode(".","$version");
$verarr_e = explode(".",$json["version"]);

$i = 0;
$hasNew = 0;
while(true) {
    if (!isset($verarr_e["$i"])) {
        $newVer = 0;
        break;
    }
    $verarr[$i] = isset($verarr["$i"]) ? $verarr["$i"] : 0;
    if ($verarr_e["$i"] < $verarr["$i"]) {
        $newVer = 0;
        break;
    }
    if ($verarr_e["$i"] == $verarr["$i"]) {
        $i++;
        continue;
    }
    if ($verarr_e["$i"] > $verarr["$i"]) {
        $newVer = 1;
        break;
    }
}

echo json_encode(array("new"=>"$newVer"));
