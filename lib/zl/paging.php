<?php
include_once ROOT_PATH . "/lib/thirdpart/pagination/Pagination.class.php";

class zl_paging extends Pagination
{
    protected static $_instance = null;

    /**
     * @return Pagination
     */
    public static function paging()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {
            self::$_instance[$className] = new $className;
            self::$_instance[$className]->setRPP(zl::$configApp['page_size']);
            self::$_instance[$className]->setKey("p");
            self::$_instance[$className]->setNext("下一页 &raquo;");
            self::$_instance[$className]->setPrevious("&laquo; 上一页");
            self::$_instance[$className]->alwaysShowPagination();
        }
        return self::$_instance[$className];
    }

    function parse(){
        $pager = parent::parse();

    }
}