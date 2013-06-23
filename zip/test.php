<?php
$zip = new ZipArchive();
$b = $zip->open("./zip/2013-06-16.zip");
$a = $zip->extractTo("./newdir/");
var_dump($a);
$zip->close();
