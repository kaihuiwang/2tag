<?php

class zl_service
{
    protected static $_instance = null;

    /**
     * @return zl_service
     */
    public static function service()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {
            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    function json($str)
    {
        return zl::json($str);
    }

    function jsonp($str, $callback = 'jsonpcallback')
    {
        return zl::jsonp($str, $callback);
    }

    function redirect($url, $code = 200)
    {
        return zl::redirect(url($url), $code);
    }

    function showJson($status=1,$data=array()){
        echo $this->json(array("status"=>$status,"data"=>$data));
        exit;
    }

    function showJsonp($status=1,$data=array()){
        echo $this->jsonp(array("status"=>$status,"data"=>$data));
        exit;
    }

}