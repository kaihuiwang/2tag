<?php
class zl_logger extends Katzgrau\KLogger\Logger{
    protected static $_instance = null;

    /**
     * @return Katzgrau\KLogger\Logger
     */
    public static function logger()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {
            $dir = zl_config::config()->get("app.logger_path");
            self::$_instance[$className] = new $className($dir);
        }
        return self::$_instance[$className];
    }
}