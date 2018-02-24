<?php
include_once ROOT_PATH . "/lib/thirdpart/UploadFile.class.php";
class zl_fupload extends UploadFile{
    protected static $_instance = null;

    /**
     * @return zl_mail
     */
    public static function fupload()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }
}