<?php

define("ROOT",dirname(dirname(__FILE__)));
define("ACTION",ROOT . "/action");
define("MODEL",ROOT . "/model");
define("TEST",ROOT . "/test");
define("CUR",dirname(__FILE__));
define("ZIP",CUR . "/zip");
define("PRO_CACHE",CUR."/json_cache");
require CUR . "/OnlineUpdate.class.php";
if (isset($_GET["progress"])) {
    echo OnlineUpdate::getProgressFile();
    exit;
}

if (!$_POST["link"]) {
    die("not valid libk");
}

if (!$_POST["root"]) {
    die("no extract directory specified");
}

$a = new OnlineUpdate($_POST["root"],$_POST["link"]);
if (!$a->update()) {
    echo $a->error();
} else {
    echo "success";
}
