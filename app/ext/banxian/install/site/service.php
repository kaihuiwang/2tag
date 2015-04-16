<?php
class ext_banxian_install_site_service extends zl_ext_service{
    function checkInstall(){
        $urlInfo = parse_url(getCurrentUrl());
        if($urlInfo['path'] == url("install")){
            $this->check();
            zl::set("ext_name","banxian/install");
            zl::set("current_route_name","ext.banxian.install.site.index");
            $obj=new ext_banxian_install_site_controller();
            $obj->index();
            exit;
        }
        if($urlInfo['path'] == url("install/config")){
            $this->check();
            zl::set("ext_name","banxian/install");
            zl::set("current_route_name","ext.banxian.install.site.config");
            $obj=new ext_banxian_install_site_controller();
            $obj->config();
            exit;
        }
        if($urlInfo['path'] == url("install/check")){
            $this->check();
            zl::set("ext_name","banxian/install");
            zl::set("current_route_name","ext.banxian.install.site.check");
            $obj=new ext_banxian_install_site_controller();
            $obj->check();
            exit;
        }
        if($urlInfo['path'] == url("install/success")){
            zl::set("ext_name","banxian/install");
            zl::set("current_route_name","ext.banxian.install.site.success");
            $obj=new ext_banxian_install_site_controller();
            $obj->success();
            exit;
        }
        $path = ROOT_PATH."/config";
        $fileNames = getDirName($path);

        if(in_array(ENVIRONMENT,$fileNames)){
            $newPath = ROOT_PATH."/config/".ENVIRONMENT."/app.php";
            $configExist = is_file($newPath);
            if($configExist){
                return true;
            }
        }
        $newPath = ROOT_PATH."/config/".ENVIRONMENT;
        mkdir($newPath,0777,true);
        zl::redirect(urlnodir("install"), 200);
    }

    function check(){
        $newPath = ROOT_PATH."/config/".ENVIRONMENT."/app.php";
        $configExist = is_file($newPath);
        if($configExist){
            exit("请先删除"."/config/".ENVIRONMENT."目录，才能重新安装!");
        }
    }

    function fileModeInfo($file_path)
    {
        if (!file_exists($file_path))
        {
            return false;
        }
        $mark = 0;
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
        {
            $test_file = $file_path . '/cf_test.txt';
            if (is_dir($file_path))
            {
                $dir = @opendir($file_path);
                if ($dir === false)
                {
                    return $mark;
                }
                if (@readdir($dir) !== false)
                {
                    $mark ^= 1;
                }
                @closedir($dir);
                $fp = @fopen($test_file, 'wb');
                if ($fp === false)
                {
                    return $mark;
                }
                if (@fwrite($fp, 'directory access testing.') !== false)
                {
                    $mark ^= 2;
                }
                @fclose($fp);
                @unlink($test_file);
                $fp = @fopen($test_file, 'ab+');
                if ($fp === false)
                {
                    return $mark;
                }
                if (@fwrite($fp, "modify test.\r\n") !== false)
                {
                    $mark ^= 4;
                }
                @fclose($fp);
                if (@rename($test_file, $test_file) !== false)
                {
                    $mark ^= 8;
                }
                @unlink($test_file);
            }
            elseif (is_file($file_path))
            {
                $fp = @fopen($file_path, 'rb');
                if ($fp)
                {
                    $mark ^= 6;
                }
                @fclose($fp);
                /* 试着修改文件 */
                $fp = @fopen($file_path, 'ab+');
                if ($fp && @fwrite($fp, '') !== false)
                {
                    $mark ^= 8;
                }
                @fclose($fp);
            }
        }
        else
        {
            if (@is_readable($file_path))
            {
                $mark ^= 1;
            }
            if (@is_writable($file_path))
            {
                $mark ^= 14;
            }
        }
        return $mark;
    }

    function checkDbConn($host,$port,$user,$pwd,$dbName){
        try{
            $link= mysql_connect($host.':'.$port,$user,$pwd);
            if(!$link){
                return zl_tool_error::add('数据库连接失败，请检查连接信息是否正确！');
            }
            $status= mysql_select_db($dbName,$link);
            if(!$status){
                return zl_tool_error::add('数据库连接成功，请先建立数据库！');
            }
        }catch (Exception $e){
            return zl_tool_error::add($e->getMessage());
        }
        return true;
    }

    function install($host,$port,$user,$pwd,$pre,$dbName,$admin,$adminPwd){
        set_time_limit(0);
        $data['app.db.host'] = $host;
        $data['app.db.port'] = $port;
        $data['app.db.db_name'] = $dbName;
        $data['app.db.user'] = $user;
        $data['app.db.password'] = $pwd;
        $data['app.db.prefix'] = $pre;
        $data['app.db.encode'] = 'utf8';
        $data['app.super_admin'] = $admin;
        $data['app.salt'] = md5(time());
        $data['app.debug'] = 1;

        foreach($data as $k=>$v){
            zl::config()->set($k,$v);
        }
        try{
            $ext = zl::config()->get("ext");
            $extNames = $ext['names'];
            if($extNames){
                foreach($extNames as $v){
                    $path = ROOT_PATH."/app/ext/".$v."/data/sql.sql";
                    if(is_file($path)){
                        zl::dao()->import($path,"`2tag_","`$pre");
                    }
                }
            }
            $data=array();
            $data[] = "truncate table ".$pre."admin_user";
            $data[] = "truncate table ".$pre."access";
            $data[] = "truncate table ".$pre."arc";
            $data[] = "truncate table ".$pre."data";
            $data[] = "truncate table ".$pre."dept";
            $data[] = "truncate table ".$pre."email_token";
            $data[] = "truncate table ".$pre."linked_user";
            $data[] = "truncate table ".$pre."menu";
            $data[] = "truncate table ".$pre."msg_data";
            $data[] = "truncate table ".$pre."msg_notice";
            $data[] = "truncate table ".$pre."msg_notice_read_log";
            $data[] = "truncate table ".$pre."notice";
            $data[] = "truncate table ".$pre."reply";
            $data[] = "truncate table ".$pre."role";
            $data[] = "truncate table ".$pre."tag";
            $data[] = "truncate table ".$pre."tag_ext";
            $data[] = "truncate table ".$pre."user";
            foreach($data as $sql){
                zl::dao()->getAdapter(0,0)->exec($sql);
            }
            $sql = "INSERT INTO ".$pre."admin_user SET email = '".$admin."', pwd='".zl_md5($adminPwd)."',
            real_name='管理员',pinyin='guanliyuan',nickname='管理员',email_validate=1,is_admin=1,join_date='".date('Y-m-d')."',mtime='".date('Y-m-d H:i:s')."',ctime='".date('Y-m-d H:i:s')."'";
            zl::dao()->getAdapter(0,0)->exec($sql);
        }catch(Exception $e){
            zl_tool_error::add($e->getMessage());
        }
        return true;
    }

}