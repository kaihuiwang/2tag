<?php

class zl_form
{

    protected static $_instance = null;

    /**
     * @return zl_form
     */
    public static function form()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    static function getCsrf($key = '')
    {
        $rand = guid();
        if(!$key){
            $key =  zl::get('current_route_name');
        }
        zl_session::session()->set($key . 'csrf', $rand);
        $str = "<input type='hidden' name='csrf' value='" . $rand . "'>";
        return $str;
    }

    static function csrfValidate($isReturn=0,$key = '')
    {
        $msg = "重复提交或者表单数据已过期,请重试";
        if(!$key){
            $key =  zl::get('current_route_name');
        }
        $csrfValue = zl::getParam("csrf");
        if (!$csrfValue){
            if($isReturn) return errorReturn($msg);
            zl::showMsg($msg);
        }
        $sessionCsrf = zl_session::session()->get($key . 'csrf');
        if ($sessionCsrf == $csrfValue) {
            zl_session::del("csrf");
            return true;
        }
        if($isReturn) return errorReturn($msg);
        zl::showMsg($msg);
    }

}