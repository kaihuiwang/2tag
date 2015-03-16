<?php
define("ROOT_PATH", __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define("ENVIRONMENT", isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : "my");
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . "/lib/zl/init.php";
zl_init::getInstance()->create();
