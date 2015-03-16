<?php

class zl_session
{
    protected static $_instance = null;

    /**
     * @return zl_session
     */
    public static function session()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    static function start()
    {
        if (session_id()) {
            session_commit();
        }
        session_start();
    }

    static function get($str = '')
    {
        if ($str == '') {
            return $_SESSION;
        } else {
            $rs = isset($_SESSION[$str]) ? unserialize($_SESSION[$str]) : null;
            return $rs;
        }
    }

    static function set($k, $v)
    {
        $_SESSION[$k] = serialize($v);
    }


    static function flash_set($k, $v)
    {
        $k = "flash_" . $k;
        return self::set($k, $v);
    }

    static function flash_get($str)
    {
        $str = "flash_" . $str;
        $result = self::get($str);
        self::del($str);
        return $result;
    }

    static function del($k = null)
    {
        if ($k == null) {
            session_destroy();
        } else {
            $_SESSION[$k] = null;
        }
    }

}