<?php
/**
 * 分页类
 */
class zl_paginator{
    protected static $_instance = null;

    /**
     * @return zl_paginator
     */
    public static function paginator()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {
            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    public function parse($pageSize, $page, $totalNum,$currentUrl=""){
        $data['page'] = (int) $page;
        $data['pageSize'] = (int) $pageSize;
        $data['totalNum'] = (int) $totalNum;
        $data['page'] = $data['page'] < 0 ? 1 : $data['page'];
        $data['pageCount'] = ($data['totalNum'] % $data['pageSize']) == 0 ?  ($data['totalNum'] / $data['pageSize']) : ceil($data['totalNum'] / $data['pageSize']);
        $data['previous'] = $data['page']-1 < 0 ? 1 : $data['page']-1;
        $data['next'] = $data['page']+1 < $data['pageCount'] ? $data['page']+1 : $data['pageCount'];
        $data['current'] = $data['page'];
        $data['totalData'] = $data['totalNum'];
        $data['currentUrl'] = $currentUrl;
        return zl_widget::widget()->create("paginator",$data);
    }
}