<?php

/**
 *
 * 处理dao连接，读写分离等
 * @author kaihui_wang
 *
 */
class zl_pdo
{
    static $hasTran = 0;//是否有事务
    private $_total = 0;
    protected $tablename = null;
    protected static $_sql = array();
    static $instance = array();
    static $sqlinstance=array();

    function getTableName()
    {
        return getConfig("app.db.prefix") . $this->tablename;
    }

    static function tableName($tableName)
    {
        return getConfig("app.db.prefix") . $tableName;
    }

    public function getAdapter($dnType = 0,$userStatic=1)
    {
        if(isset(self::$instance[$dnType]) && self::$instance[$dnType] && $userStatic) return self::$instance[$dnType];

        $dbh = null;
        $slaveDBH = null;
        $dbconfig = zl::config()->get("app.db");
        if (isset($dbconfig['master']) && isset($dbconfig['slave'])) {
            $masterConfig = $dbconfig['master'];
            $slaveConfig = $dbconfig['slave'];
            zl::register('db', 'PDO',
                array('mysql:host=' . $masterConfig['host'] . ';port=' . $masterConfig['port'] . ';dbname=' . $masterConfig['db_name'] . '', $masterConfig['user'], $masterConfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));
            zl::register('readdb', 'PDO',
                array('mysql:host=' . $slaveConfig['host'] . ';port=' . $slaveConfig['port'] . ';dbname=' . $slaveConfig['db_name'] . '', $slaveConfig['user'], $slaveConfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));
            $dbh = zl::db();
            $slaveDBH = zl::readdb();
        } else {
            zl::register('db', 'PDO',
                array('mysql:host=' . $dbconfig['host'] . ';port=' . $dbconfig['port'] . ';dbname=' . $dbconfig['db_name'] . '', $dbconfig['user'], $dbconfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));
            $dbh = zl::db();
        }

        if ($dnType == 0) {
            $connection = $dbh;
        } else {
            $connection = $slaveDBH ? $slaveDBH : $dbh;
        }

        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        self::$instance[$dnType] = $connection;

        return $connection;
    }


    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function startTrans()
    {
        if (!self::$hasTran) {
            $this->exec("BEGIN", null, 0);
        }
        self::$hasTran++;
        return true;
    }

    /**
     * 提交事务
     * @access public
     * @return boolean
     */
    public function commit()
    {
        if (self::$hasTran) self::$hasTran--;
        if (!self::$hasTran) {
            return $this->exec("COMMIT", null, 0);
        }
    }

    /**
     * 事务回滚
     * @access public
     * @return boolean
     */
    public function rollback()
    {
        self::$hasTran = 0;
        return $this->exec("ROLLBACK", null, 0);
    }

    /**
     * invokes the read/write connection
     */
    public function insert(array $data)
    {
        $tableName = $this->getTableName();

        if (getConfig("app.db.auto_time")) {
            $data['ctime'] = date('Y-m-d H:i:s');
            $data['mtime'] = date('Y-m-d H:i:s');
        }

        $keys = array_keys($data);
        $sql = "INSERT INTO " . $tableName . "(" . implode(',', $keys) . ")
                VALUES(:" . implode(', :', $keys) . ") ";

        foreach ($data as $k => $v) {
            $v = $this->getAdapter()->quote($v);
            $sql = str_replace(":" . $k, $v, $sql);
        }

        $this->getAdapter()->exec($sql);
        $lastInsertId = $this->getAdapter()->lastInsertId();
        self::$_sql[] = $sql;
        d($sql,"sql");
        return $lastInsertId;
    }

    /**
     * 根据条件，有则更新，无则插入
     */
    public function insertIf(array $data, array $where)
    {
        if (!$data || !$where) return false;
        $tableName = $this->getTableName();
        $whereSql = $this->parseWhere($where);
        if (getConfig("app.db.auto_time")) {
            $data['ctime'] = date('Y-m-d H:i:s');
            $data['mtime'] = date('Y-m-d H:i:s');
        }
        $keys = array_keys($data);
        $sql = "INSERT INTO " . $tableName . "(" . implode(',', $keys) . ")
                VALUES(:" . implode(', :', $keys) . ")
                ON DUPLICATE KEY UPDATE " . $whereSql;

        foreach ($data as $k => $v) {
            $v = $this->getAdapter()->quote($v);
            $sql = str_replace(":" . $k, $v, $sql);
        }

        $this->getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        d($sql,"sql");
        return true;
    }

    /**
     * invokes the read/write connection
     */
    public function delete($where = array())
    {
        $tableName = $this->getTableName();

        $whereStr = $this->parseWhere($where);
        $whereStr = $whereStr ? " WHERE " . $whereStr : "";

        $sql = "DELETE FROM `" . $tableName . "`" . $whereStr;
        $return = $this->getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        d($sql,"sql");
        return $return;
    }


    /**
     * Invokes the read/write connection
     */
    public function update(array $data, $where = array())
    {
        $tableName = $this->getTableName();

        $whereStr = $this->parseWhere($where);
        $whereStr = $whereStr ? " WHERE " . $whereStr : "";

        if (getConfig("app.db.auto_time")) {
            $data['mtime'] = date('Y-m-d H:i:s');
        }

        $sql = "UPDATE `" . $tableName . "` SET ";
        foreach ($data as $k => $v) {
            $v = $this->getAdapter()->quote($v);
            $sql .= $k . "=" . $v . ",";
        }
        $sql = rtrim($sql, ',');
        $sql .= $whereStr;
        self::$_sql[] = $sql;
        d($sql,"sql");
        return $this->getAdapter()->exec($sql);
    }

    /**
     *
     * 自增
     * @param string $field
     * @param array $where
     */
    public function inCrease($field, $where = array(), $number = 1)
    {
        $tableName = $this->getTableName();

        $whereSql = $this->parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";

        $sql = "UPDATE `{$tableName}` SET {$field} = {$field} +{$number} " . $whereSql;
//        zl_Tool::debug($sql);
        $this->getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        d($sql,"sql");
        return true;
    }


    /**
     *
     * 自减
     * @param string $field
     * @param array $where
     */
    public function deCrement($field, $where = array(), $number = 1)
    {
        $tableName = $this->getTableName();

        $whereSql = $this->parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";

        $sql = "UPDATE `{$tableName}` SET {$field} = {$field} - {$number} " . $whereSql;
        $this->getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        d($sql,"sql");
        return true;
    }

    /**
     *
     * 更新，插入，删除sql执行,只返回受影响的行数
     * @param unknown_type $sql
     * @param unknown_type $data
     */
    function exec($sql, $data = array(), $check = 1)
    {
        if (empty($sql)) return false;
        if ($check) {
            if (!(stristr($sql, "insert") || stristr($sql, "update") || stristr($sql, "delete") || stristr($sql, "drop"))) throw new Exception("此函数只能用于添加，更新，删除数据库操作");
        }
        if ($data) {
            foreach ($data as $k => $v) {
                $v = $this->getAdapter()->quote($v);
                $sql = str_replace(":" . $k, $v, $sql);
            }
        }
//    	echo $sql;exit;
        $result = $this->getAdapter()->exec($sql);
        if(!$result){
            if ($this->getAdapter()->errorCode() != '00000'){
                $error = $this->getAdapter()->errorInfo();
                throw new Exception('错误: ['.$error['1'].'] '.$error['2']);
            }
        }
        self::$_sql[] = $sql;
        d($sql,"sql");
        return $result;
    }


    function query($sql){
        if (empty($sql)) return false;
        $result =  $this->getAdapter(1)->query($sql);
        if(!$result){
            if ($this->getAdapter(1)->errorCode() != '00000'){
                $error = $this->getAdapter(1)->errorInfo();
                throw new Exception('错误: ['.$error['1'].'] '.$error['2']."\r\n<br>sql:".$sql."");
            }
        }
        self::$_sql[] = $sql;
        d($sql,"sql");
        return $result;
    }

    /**
     * sql语句获取数据库数据
     * @param string $sql
     * @param array $data
     */
    function selectRow($sql, $data = array())
    {
        if (empty($sql)) return false;
        if (stristr($sql, "insert") || stristr($sql, "update") || stristr($sql, "delete")) throw new Exception("此函数不能用于添加，更新，删除数据库操作");
        if ($data) {
            foreach ($data as $k => $v) {
                $v = $this->getAdapter(1)->quote($v);
                $sql = str_replace(":" . $k, $v, $sql);
            }
        }
        self::$_sql[] = $sql;
        return $this->query($sql)->fetch();
    }

    /**
     *
     * 用sql语句获取所有
     * @param string $sql
     * @param array $data
     * @param $returnCount //SQL_CALC_FOUND_ROWS
     */
    function selectAll($sql, $data = array(), $returnCount = false)
    {
        if (empty($sql)) return false;
        if (stristr($sql, "insert") || stristr($sql, "update") || stristr($sql, "delete")) throw new Exception("此函数不能用于添加，更新，删除数据库操作");
        if ($data) {
            foreach ($data as $k => $v) {
                $v = $this->getAdapter(1)->quote($v);
                $sql = str_replace(":" . $k, $v, $sql);
            }
        }
//    	zl_Tool::debug($sql);
        self::$_sql[] = $sql;
        $rs = $this->query($sql)->fetchAll();
        if ($returnCount) {
            $sqlCount = 'SELECT FOUND_ROWS() as cnt';
            $rsCount = $this->query($sqlCount)->fetch();
            $this->_total = $rsCount['cnt'];
        }
        return $rs;
    }

    function getTotal()
    {
        return $this->_total;
    }

    //获取单个字段的数据
    function selectAllByField($sql, $field = "id", $data = array(), $returnCount = false)
    {
        $list = $this->selectAll($sql, $data, $returnCount);
        $rs = array();
        if ($list) {
            foreach ($list as $v) {
                $rs[] = $v[$field];
            }
        }
        return $rs;
    }


    /**
     *
     * 解析where数据
     * @param array $where
     */
    protected function parseWhere($where = array())
    {

        if (count($where) < 1) return "";
        $whereSql = "";
        foreach ($where as $k => $v) {
            $param = $k . " = ? ";
            $andOR = "AND";
            if (is_array($v)) {
                list($sign, $vl) = $v;

                if(isset($v[2]) && $v[2]) $andOR = $v[2];
                if($vl !==''){
                    $param = $k . " " . $sign . " ? ";
                    $sign = strtolower($sign);
                    if ($sign == 'in') {
                        $param = $k . " " . $sign . " (?) ";
                    }
                    $v = $vl;
                }else{
                    $param = $k . " " . $sign . " ";
                }
                $andOR = strtoupper($andOR);
            }
            $whereSql .= " $andOR (" . $this->quoteInto($param, $v).")";
        }
        $whereSql = trim($whereSql);
        $whereSql = trim($whereSql,"OR");
        $whereSql = trim($whereSql,"AND");
        return $whereSql;
    }

    function quoteInto($param, $v)
    {
        if(is_array($v)){
            $str = "";
            foreach($v as $v1){
                $str .= $this->getAdapter(1)->quote($v1).",";
            }
            $str = trim($str,",");
            return str_replace('?', $str, $param);
        }else{
            return str_replace('?', $this->getAdapter(1)->quote($v), $param);
        }
    }

    /**
     * 根据条件获取多个
     * @param array $where
     */
    public function gets($where = array(), $orderBy = "", $limit = "", $offset = "", $groupBy = "", $returnCount = false)
    {
        $whereSql = $this->parseWhere($where);

        $whereSql = $whereSql ? " WHERE " . $whereSql : "";
        $tableName = $this->getTableName();
        $orderBySql = "";
        $groupBySql = "";
        $limitSql = "";
        if ($groupBy) $groupBySql = " GROUP BY " . $groupBy;
        if ($orderBy) $orderBySql = " ORDER BY " . $orderBy;

        if ($offset) {
            $limit = intval($limit);
            $offset = intval($offset);
            $limitSql = " LIMIT {$limit} , {$offset} ";
        }


        $sql = "SELECT *  FROM `{$tableName}` " . $whereSql . $groupBySql . $orderBySql . $limitSql;
// 		zl_Tool::debug($sql);
        self::$_sql[] = $sql;

        $key = base64_encode($sql).$returnCount;
        if(isset(self::$sqlinstance[$key]) && self::$sqlinstance[$key]) return self::$sqlinstance[$key];

        $rs = $this->query($sql)->fetchAll();
        if ($returnCount) {
            $sqlCount = "SELECT count(*) as cnt FROM  `{$tableName}` " . $whereSql;
            $rsCount = $this->query($sqlCount)->fetch();
            $this->_total = $rsCount['cnt'];
        }
        self::$sqlinstance[$key] = $rs;
        return $rs;
    }


    function pager($where = array(), $orderBy = "", $groupBy = "",$page=0,$currentUrl="",$pageSize=0){
        if(!$page){
            $page = (int) zl::getParam("p");
            $page = $page ? $page:(int) getMatchRequest("p",1);
        }

        $pageSize = $pageSize?$pageSize:zl::$configApp['page_size'];
        $limit = ($page-1)*$pageSize;

        $list = $this->gets($where,$orderBy,$limit,$pageSize,$groupBy,true);
        $count = $this->getTotal();

        $markup = zl_paginator::paginator()->parse($pageSize,$page,$count,$currentUrl);
        return array($list,$markup);
    }

    function pagerSql($sql,$page=0,$currentUrl=""){
        if(!$page){
            $page = (int) zl::getParam("p");
            $page = $page ? $page:(int) getMatchRequest("p",1);
        }

        $pageSize = zl::$configApp['page_size'];
        $limit = ($page-1)*$pageSize;
        $sql .= " LIMIT ".$limit.", ".$pageSize;
        $list = $this->selectAll($sql,array(),true);
        $count = $this->getTotal();

        $markup = zl_paginator::paginator()->parse($pageSize,$page,$count,$currentUrl);
        return array($list,$markup);
    }

    /**
     * 得到单个字段
     * @param  [type]  $field   [description]
     * @param  [type]  $where   [description]
     * @param  boolean $isMore [description]
     * @param  string $orderBy [description]
     * @param  string $limit [description]
     * @param  string $offset [description]
     * @param  string $groupBy [description]
     * @return [type]           [description]
     */
    function getField($field, $where, $isMore = false, $orderBy = "", $limit = "", $offset = "", $groupBy = "")
    {
        $whereSql = $this->parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";
        $tableName = $this->getTableName();
        $orderBySql = "";
        $groupBySql = "";
        $limitSql = "";
        if ($groupBy) $groupBySql = " GROUP BY " . $groupBy;
        if ($orderBy) $orderBySql = " ORDER BY " . $orderBy;

        if ($offset) {
            $limit = intval($limit);
            $offset = intval($offset);
            $limitSql = " LIMIT {$limit} , {$offset} ";
        }


        $sql = "SELECT " . $field . "  FROM `{$tableName}` " . $whereSql . $groupBySql . $orderBySql . $limitSql;
//      zl_Tool::debug($sql);
        self::$_sql[] = $sql;
        $key = base64_encode($sql).$field.$isMore;
        if(isset(self::$sqlinstance[$key]) && self::$sqlinstance[$key]) return self::$sqlinstance[$key];
        if ($isMore) {
            $rs = $this->query($sql)->fetchAll();
            if (!$rs) return array();
            $result = array();
            foreach ($rs as $key => $value) {
                $result[] = $value[$field];
            }
            self::$sqlinstance[$key] = $result;
            return $result;
        } else {
            $rs = $this->query($sql)->fetch();
            $result= $rs ? $rs[$field] : "";
            self::$sqlinstance[$key] = $result;
            return $result;
        }

    }


    /**
     * 根据条件获取一行
     * @param array $where
     */
    public function get($where, $orderBy = "")
    {
        $whereSql = $this->parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM `{$tableName}` " . $whereSql;
        if ($orderBy) $sql .= " ORDER BY " . $orderBy;
        self::$_sql[] = $sql;
        $key = base64_encode($sql);
        if(isset(self::$sqlinstance[$key]) && self::$sqlinstance[$key]) return self::$sqlinstance[$key];
        $result = $this->query($sql)->fetch();
        self::$sqlinstance[$key] = $result;
        return $result;
    }


    /**
     *
     * 获取数据行数
     * @param array $where
     */
    public function getCount($where = array(), $groupBy = "")
    {
        $whereSql = $this->parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";
        $tableName = $this->getTableName();
        $groupBySql = "";
        if ($groupBy) $groupBySql = " GROUP BY " . $groupBy;
        $sql = "SELECT count(*) as cnt FROM `{$tableName}` " . $whereSql . $groupBySql;
        self::$_sql[] = $sql;
        $return = $this->query($sql)->fetch();

        return $return['cnt'];
    }

    /**
     *
     * 获取sql
     */
    public function getSql()
    {
        return self::$_sql;
    }

    function backup($table){
        $db =$this->getAdapter();
        $sql = "DROP TABLE IF EXISTS $table;\n";
        $createtable = $db->query("SHOW CREATE TABLE $table");
        $create = $createtable->fetch(PDO::FETCH_NUM);
        $sql .= $create[1].";\n\n";

        $rows = $db->query("SELECT * FROM $table");
        $numfields = $rows->columnCount ();

        while ($row = $rows->fetch(PDO::FETCH_NUM)){
            $comma = "";
            $sql .= "INSERT INTO $table VALUES(";
            for ($i = 0; $i < $numfields; $i++){
                $sql .= $comma."'".addslashes($row[$i])."'";
                $comma = ",";
            }
            $sql .= ");\n";
        }
        $sql .= "\n";
        return $sql;
    }

    function import($sqlPath,$old_prefix="",$new_prefix=""){
        if(is_file($sqlPath)){
            $txt = file_get_contents($sqlPath);
            if(!$txt) return true;
            $sqlArr = $this->clearSql($txt,$old_prefix,$new_prefix);
            if($sqlArr){
                foreach ($sqlArr as $sv){
                    $this->getAdapter(0,0)->exec($sv);
                }
            }
        }
        return true;
    }

    /*
		参数：
		$old_prefix:原表前缀；
		$new_prefix:新表前缀；
		$separator:分隔符 参数可为";\n"或";\r\n"或";\r"
	*/
     function clearSql($content,$old_prefix="",$new_prefix="",$separator=";\n")
    {
        $commenter = array('#','--');
        $content = str_replace(array($old_prefix, "\r"), array($new_prefix, "\n"), $content);//替换前缀

        //通过sql语法的语句分割符进行分割
        $segment = explode($separator,trim($content));

        //去掉注释和多余的空行
        $data=array();
        foreach($segment as  $statement)
        {
            $sentence = explode("\n",$statement);
            $newStatement = array();
            foreach($sentence as $subSentence)
            {
                if('' != trim($subSentence))
                {
                    //判断是会否是注释
                    $isComment = false;
                    foreach($commenter as $comer)
                    {
                        if(preg_match("/^(".$comer.")/is",trim($subSentence)))
                        {
                            $isComment = true;
                            break;
                        }
                    }
                    //如果不是注释，则认为是sql语句
                    if(!$isComment)
                        $newStatement[] = $subSentence;
                }
            }
            $data[] = $newStatement;
        }

        //组合sql语句
        foreach($data as  $statement)
        {
            $newStmt = '';
            foreach($statement as $sentence)
            {
                $newStmt = $newStmt.trim($sentence)."\n";
            }
            if(!empty($newStmt))
            {
                $result[] = $newStmt;
            }
        }
        return $result;
    }

}
