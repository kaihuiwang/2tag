<?php
include_once ROOT_PATH . "/lib/thirdpart/parsedown.php";
class zl_markdown extends Parsedown{
    protected static $_instance = null;

    /**
     * @return Parsedown
     */
    public static function markdown()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    function parse($text){
        $text = parent::parse($text);
        return removeXss($text);
    }


}