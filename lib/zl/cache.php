<?php
include_once ROOT_PATH . "/lib/thirdpart/php_fast_cache.php";

class zl_cache extends phpFastCache
{
    protected static $_instance = null;

    /**
     * @return zl_cache
     */
    public static function cache()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {
            $cacheConfig = zl::config()->get("cache");
            if(!is_dir(ROOT_PATH."/".$cacheConfig['path'])) mkdir(ROOT_PATH."/".$cacheConfig['path'],0777 ,true);
            self::$_instance[$className] = new $className;
            zl_cache::$storage =$cacheConfig['storage'];
            zl_cache::$securityKey =$cacheConfig['path'];
            zl_cache::$path = ROOT_PATH;
        }
        return self::$_instance[$className];
    }

}