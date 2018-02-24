<?php

class zl_dao extends zl_pdo
{
    /**
     * @return zl_dao
     */
    public static function dao()
    {
        $className = get_called_class();
        return new $className;
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