<?php
date_default_timezone_set('Asia/Shanghai');
include_once ROOT_PATH . "/function.php";
include_once ROOT_PATH . "/lib/zl/config.php";
include_once ROOT_PATH . "/lib/zl/zl.php";
zl::$configApp = zl_config::config()->get("app");

$namespaces = zl::$configApp['namespaces'];
$apps = zl::$configApp['apps'];

class zl_init
{
    private static $_instance = null;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    function create()
    {
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        try {
            $this->hook();
            zl_hook::run("system_start");
            $this->initialize();
            $this->cache();
            $this->route();
            zl_hook::run("system_view");
            $this->cronJob();
            zl::start();
        } catch (Exception $e) {
            $obj = new zl_controller();
            $errors = $e->getMessage()."\r\n";
            $errors .= $e->getTraceAsString();
            zl_logger::logger()->error($errors);
            if($this->isdebug()) {
                $obj->display("error", array("msg" => $e->getMessage(), "trace" => $e->getTraceAsString(), "isdebug" => $this->isdebug()));
            }
        }
        zl::stop();
    }


    function cronJob(){
        //crontab 执行
        $randJob = zl::config()->get("app.rand_job");
        $randJob = $randJob?floor(1/$randJob):10;
        $rand = mt_rand(1,$randJob);
        if($rand === 1){
            zl_hook::run("job");
        }
        zl_hook::run("site_end");
    }

    function initialize()
    {
        zl_session::start();
        //输入过滤
        if (!get_magic_quotes_gpc()) {
            !empty($_POST) && add_s($_POST);
            !empty($_GET) && add_s($_GET);
            !empty($_COOKIE) && add_s($_COOKIE);
            !empty($_FILES) && add_s($_FILES);
        }

        zl::set('flight.log_errors', $this->isdebug());
        zl::set('flight.views.path', ROOT_PATH . "/view");

        $url = isset($_SERVER["REQUEST_URI"])? $_SERVER["REQUEST_URI"] : "";
        if(!trim($url,"/")){
            $_SERVER["REQUEST_URI"] = url("/");
        }
//        dd( $_SERVER["REQUEST_URI"]);
        $url = ltrim($url,"/index.php/");
        $sub = substr($url,0,5);
        if (zl::config()->get("app.siteHalt") && ($sub!='admin')) zl::halt(200, zl::config()->get("app.haltMsg"));
    }


    function hook()
    {
        $ext = zl::config()->get("ext.names");
        if ($ext) {
            foreach ($ext as $v) {
				$hookPath = ROOT_PATH . "/app/ext/" . $v . "/hook.php";
                if(is_file($hookPath)) include_once $hookPath;
            }
        }
        include_once ROOT_PATH . "/app/hook.php";
    }

    function route()
    {
        $cachePath = $this->getRoutePath();
        if (is_file($cachePath) && (!$this->isdebug())) {
            include $cachePath;
            zl::start();
            return;
        }
        $routePhpstr = "";
        $ext = zl::config()->get("ext.names");
        if ($ext) {
            foreach ($ext as $v) {
                $routeFilePath = ROOT_PATH . "/app/ext/" . $v . "/config/route.php";
                if(!is_file($routeFilePath)) continue;
                $extConfig = include $routeFilePath;
                if (!$extConfig) continue;
                foreach ($extConfig as $routeExtName => $extRoute) {
                    $routePhpstr .= controller($routeExtName, $extRoute);
                }
            }
        }
        $routeConfig = zl::config()->get("route");
        if ($routeConfig) {
            foreach ($routeConfig as $routeName => $route) {
                $routePhpstr .= controller($routeName, $route);
            }
        }
        if ($this->isdebug()) {
            @unlink($cachePath);
        }
        writePhp($cachePath, $routePhpstr);
        include $cachePath;
    }

    function getRoutePath()
    {
        if(!is_dir(ROOT_PATH . "/data/cache")) mkdir(ROOT_PATH . "/data/cache/",0777,true);
        return ROOT_PATH . "/data/cache/route.php";
    }


    function cache()
    {
        $config = zl::config()->get("cache");
        $memcacheSerConfig = zl::config()->get("app.memcached");
        $memcacheSer = array();
        $memcacheSerConfig = explode(",",$memcacheSerConfig);
        if($memcacheSerConfig){
            foreach($memcacheSerConfig as $v){
                list($ip,$port) = explode(":",$v);
                $memcacheSer[]= array($ip,$port,100);
            }
        }

        zl_cache::$storage = $config['storage'];
        zl_cache::$path = $config['path'];
        zl_cache::$securityKey = "storage";
        zl_cache::$files_cleanup_after = 1;
        zl_cache::$server = $memcacheSer;
        zl_cache::$useTmpCache = false;
        zl_cache::$securityHtAccess = true;
        zl_cache::$debugging = zl::config()->get("app.debug");
    }

    function isdebug()
    {
        return zl::config()->get("app.debug");
    }

    function loader($className)
    {
        global $namespaces, $apps;
        $libpath = dirname(__FILE__) . "/../../lib";
        $apppath = dirname(__FILE__) . "/../../app";
        if (strstr($className, '_')) {
            $pathArr = explode('_', $className);
            if ($pathArr) {
                if (in_array($pathArr[0], $namespaces)) {
                    $path = "";
                    foreach ($pathArr as $v) {
                        $path .= $v . "/";
                    }
                    $path = trim($path, "/");
                    include_once $libpath . "/" . $path . ".php";
                }
                if (in_array($pathArr[0], $apps)) {
                    $path = "";
                    foreach ($pathArr as $v) {
                        $path .= $v . "/";
                    }
                    $path = trim($path, "/");
                    include_once $apppath . "/" . $path . ".php";
                }
            }
        } else {
            if (!in_array($className, $namespaces)) return true;
            include_once $libpath . "/" . $className . "/" . $className . ".php";
        }
    }

    function removeUTF8Bom($string)
    {
        if (substr($string, 0, 3) == pack('CCC', 239, 187, 191)) return substr($string, 3);
        return $string;
    }

}