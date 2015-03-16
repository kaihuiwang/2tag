<?php

class zl_dao extends zl_pdo
{
    protected static $_instance = null;

    /**
     * @return zl_dao
     */
    public static function dao()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    /**
     * @param $name
     * @return zl_pdo
     */
    function setTable($table = '')
    {
        $this->tablename = $table;
        return $this;
    }

}