<?php
require_once "index.php";
function unit($param,$path,$host='tag8.com:80'){
    if(!$path || !$param) return false;
    $pathArr = explode("/",$path);
    if($param){
        foreach($param as $k=>$data){
            $_REQUEST[$k]=$_POST[$k]=$_GET[$k]=$data;
        }
    }
    list($domain,$port) = explode(":",$host);
    $_SERVER['argv'] = array(__DIR__."/index_dev.php", $pathArr[0], $pathArr[1]);
    $_SERVER['argc'] = count($_SERVER['argv']);
    $_SERVER['SERVER_PORT'] = $port;
    $_SERVER['HTTP_X_REAL_IP'] = '127.0.0.1';
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['SERVER_ADDR'] = '127.0.0.1';
    $_SERVER['LOCAL_ADDR'] = '127.0.0.1';
    $_SERVER['SERVER_NAME'] = $domain;
    $_SERVER['HTTP_HOST'] = $domain;
    $_SERVER['REQUEST_URI'] = '/'.$path;
}