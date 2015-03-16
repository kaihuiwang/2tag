<?php
class zl_tool_mqueue {

    private static $mem = NULL;
    protected static $_instance = null;
    private function __construct() {}

    private function __clone() {}

    public static function tool_mqueue()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    private static function getInstance() {
        if(!self::$mem) self::init();
        return self::$mem;
    }

    private static function init() {
        $mem = new Memcached;
        $serversStr = zl::config()->get("app.memcached");
        if(!$serversStr){
            throwException(" memcached not found");
        }
		$servers = explode(',',$serversStr);
        foreach($servers as $server) {
            list($host, $port) = explode(":", $server);
            $mem->addServer($host, $port);
        }
        self::$mem = $mem;
    }

    public static function is_empty($queue) {
        $mem = self::getInstance();
        $head = $mem->get($queue."_head");
        $tail = $mem->get($queue."_tail");

        if($head >= $tail || $head === FALSE || $tail === FALSE)
            return TRUE;
        else
            return FALSE;
    }

    public static function dequeue($queue, $after_id=FALSE, $till_id=FALSE) {
        $mem = self::getInstance();

        if($after_id === FALSE && $till_id === FALSE) {
            $tail = $mem->get($queue."_tail");
            if(($id = $mem->increment($queue."_head")) === FALSE)
                return FALSE;

            if($id <= $tail) {
                return $mem->get($queue."_".($id-1));
            }
            else {
                $mem->decrement($queue."_head");
                return FALSE;
            }
        }
        else if($after_id !== FALSE && $till_id === FALSE) {
            $till_id = $mem->get($queue."_tail");
        }

        $item_keys = array();
        for($i=$after_id+1; $i<=$till_id; $i++)
            $item_keys[] = $queue."_".$i;
        $null = NULL;

        return $mem->getMulti($item_keys, $null, Memcached::GET_PRESERVE_ORDER);
    }

    public static function enqueue($queue, $item) {
        $mem = self::getInstance();

        $id = $mem->increment($queue."_tail");
        if($id === FALSE) {
            if($mem->add($queue."_tail", 1, 0) === FALSE) {
                $id = $mem->increment($queue."_tail");
                if($id === FALSE)
                    return FALSE;
            }
            else {
                $id = 1;
                $mem->add($queue."_head", $id, 0);
            }
        }

        if($mem->add($queue."_".$id, $item, 0) === FALSE)
            return FALSE;

        return $id;
    }
}