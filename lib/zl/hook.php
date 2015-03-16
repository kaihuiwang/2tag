<?php

class zl_hook
{

    protected static $_instance = null;
    static $hooks = array();

    /**
     * @return zl_hook
     */
    public static function hook()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }


    static function create($name, $callback)
    {
        return self::$hooks[$name][] = $callback;
    }

    static function run($name, $params = null)
    {
        if (isset(self::$hooks[$name]) && self::$hooks[$name]) {
            $hooks = self::$hooks[$name];
            foreach ($hooks as $v) {
                $v($params);
            }
        }
    }

}