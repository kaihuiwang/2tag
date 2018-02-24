<?php
include_once ROOT_PATH . "/lib/thirdpart/flight/Flight.php";

class zl extends Flight
{
    static $configApp = array();
    /**
     * @param string $table
     * @return zl_pdo
     */
    static function dao($table = '')
    {
        return zl_dao::dao()->setTable($table);
    }

    static function config()
    {
        return zl_config::config();
    }

    static function getConfigPath()
    {
        return ROOT_PATH . "/config";
    }

    static function getAppPath()
    {
        return ROOT_PATH . "/app";
    }

    static function render($path, $param = null, $alias = null, $viewpath = null)
    {
        if (!$viewpath){
            $tpl = zl::config()->get("app.tpl");
            $viewpath = ROOT_PATH . "/view/".$tpl;
        }
        return parent::render($path, $param, $alias, $viewpath);
    }


    static function renderExt($path, $param = null, $alias = null)
    {
        $tpl = zl::config()->getExt("app.tpl");
        if($tpl){
            $viewpath = ROOT_PATH . "/app/ext/" . zl::get("ext_name") . "/view/".$tpl;
        }else{
            $viewpath = ROOT_PATH . "/app/ext/" . zl::get("ext_name") . "/view";
        }
        return self::render($path, $param, $alias,$viewpath);
    }

    static function getParam($str = null,$default=null)
    {
        $all = $_REQUEST;
        if (!$str) {
            return $all;
        }
        return isset($all[$str]) ? $all[$str] : $default;
    }

    static function showMsg($msg, $redirect = "", $isJs = 0, $type = 0)
    {
        self::render("msg", array("type" => $type, "msg" => $msg, "redirect" => $redirect, "isJs" => $isJs));
        exit;
    }

    static function redirect($url){
        header("LOCATION:".$url);
        exit;
    }
}