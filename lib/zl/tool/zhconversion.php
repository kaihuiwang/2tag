<?php
include_once ROOT_PATH . "/lib/thirdpart/big2gb/convert.php";
class zl_tool_zhconversion{

    static function zhconversion_cn($str){
        return zhconversion($str,"zh-hans");
    }

}