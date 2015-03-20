<?php
class ext_banxian_install_site_controller extends zl_ext_controller
{
    public $layout = "install";
    function index(){
        $this->display();
    }

    function check(){
        $paths = array(
            "data"=>array(ROOT_PATH."/data","执行linux命令:chmod -R 0777 ".ROOT_PATH."/data"),
            "public/images/face"=>array(ROOT_PATH."/public/images/face","执行linux命令:chmod -R 0777 ".ROOT_PATH."/public/images/face"),
            "config"=>array(ROOT_PATH."/config","执行linux命令:chmod -R 0777 ".ROOT_PATH."/config"),
        );
        $result = array();
        foreach ($paths as $k=>$v){
            $mode = ext_banxian_install_site_service::service()->fileModeInfo($v[0]);
            $result[$k] = array($mode,$v[1]);
        }
        $this->path = $result;
        $this->display();
    }

    function config(){
        error_reporting(0);
        set_time_limit(0);
        $this->dbHost = "";
        $this->dbPort = "";
        $this->dbName = "";
        $this->dbUser = "";
        $this->dbPwd = "";
        $this->dbPre = "";
        $this->admin = "";
        $this->adminPwd = "";
        if(isPost()){
            $dbHost = $this->getParam("DB_HOST");
            $dbPort = $this->getParam("DB_PORT");
            $dbName = $this->getParam("DB_NAME");
            $dbUser = $this->getParam("DB_USER");
            $dbPwd = $this->getParam("DB_PWD");
            $dbPre = $this->getParam("DB_PRE");

            $admin = $this->getParam("admin");
            $adminPwd = $this->getParam("adminPwd");

            $this->dbHost = $dbHost;
            $this->dbPort = $dbPort;
            $this->dbName = $dbName;
            $this->dbUser = $dbUser;
            $this->dbPwd = $dbPwd;
            $this->dbPre = $dbPre;
            $this->admin = $admin;
            $this->adminPwd = $adminPwd;
            if(!$dbHost){
                zl_tool_error::add("数据库地址为空!");
            }elseif(!$dbPort){
                zl_tool_error::add("数据库端口为空!");
            }elseif(!$dbName){
                zl_tool_error::add("数据库名称为空!");
            }elseif(!$dbUser){
                zl_tool_error::add("数据库用户名为空!");
            }elseif(!$dbPwd){
                zl_tool_error::add("数据库密码为空!");
            }elseif(!$dbPre){
                zl_tool_error::add("数据库前缀为空!");
            }elseif(!$admin){
                zl_tool_error::add("管理员账户为空!");
            }elseif(!check_email($admin)){
                zl_tool_error::add("管理员账户必须是邮箱地址!");
            }elseif(!$adminPwd){
                zl_tool_error::add("管理员密码为空!");
            }

            if(!zl_tool_error::lastError()){
                ext_banxian_install_site_service::service()->checkDbConn($dbHost, $dbPort, $dbUser, $dbPwd, $dbName);
                if(!zl_tool_error::lastError()){
                    ext_banxian_install_site_service::service()->install($dbHost,$dbPort,$dbUser,$dbPwd,$dbPre,$dbName,$admin,$adminPwd);
                }
            }
            if(!zl_tool_error::lastError()){
                $this->redirect("install/success");
            }

        }
        $this->display();
    }

    function success(){
        $this->display();
    }

}