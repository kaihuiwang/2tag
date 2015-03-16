<?php

class zl_config
{
    protected static $_instance = null;
    public static $config = array();

    /**
     * @return zl_config
     */
    public static function config()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }


    function get($str, $path = "")
    {
        if (!$str) return null;
        $configPath = dirname(__FILE__) . "/../../config";
        if ($path) $configPath = $path;
        $configPathEnv = "";
        if (ENVIRONMENT) $configPathEnv = $configPath . "/" . ENVIRONMENT;
        $arr = array();
        $configs = array();
        $configPathTmp = "";
        if (strstr($str, ".")) {
            $arr = explode(".", $str);
            $configPathTmp = array_shift($arr);
            $configPathTmp .= ".php";
        } else {
            $configPathTmp .= $str . ".php";
        }

        $envPath = $configPathEnv . "/" . $configPathTmp;
        $commonPath = $configPath . "/" . $configPathTmp;

        $md5envPath = md5($envPath . $str);
        $md5commonPath = md5($commonPath . $str);

        if (isset(self::$config[$md5envPath])) return self::$config[$md5envPath];
        if (isset(self::$config[$md5commonPath])) return self::$config[$md5commonPath];


        if (ENVIRONMENT && is_file($envPath)) {
            $configs = include($envPath);

            if ($arr) {
                foreach ($arr as $v) {
                    if (!$configs) break;
                    $configs = isset($configs[$v]) ? $configs[$v] : null;
                }
            }

            if ($configs !== null) {
                if (strstr($str, ".")) {
                    self::$config[$md5envPath] = $configs;
                    return $configs;
                } else {
                    $configs2 = include($commonPath);
                    $rs = array_merge($configs2, $configs);
                    self::$config[$md5commonPath] = $rs;
                    return $rs;
                }
            }
        }

        $configs = include($commonPath);
        if ($arr) {
            foreach ($arr as $v) {
                if (!$configs) break;
                $configs = isset($configs[$v]) ? $configs[$v] : null;
            }
        }
        self::$config[$md5commonPath] = $configs;
        return $configs;
    }

    function set($str, $value, $path = "")
    {
        if (!$str) return false;
        $configPath = dirname(__FILE__) . "/../../config";
        if ($path) $configPath = $path;

        $configPathEnv = "";
        if (ENVIRONMENT) $configPathEnv = $configPath . "/" . ENVIRONMENT;
        $arr = array();
        $key = "";
        if (strstr($str, ".")) {
            $arr = explode(".", $str);
            $configPathTmp = array_shift($arr);
            $configPathTmp .= ".php";
        } else {
            $configPathEnv = "";
            if (ENVIRONMENT) $configPathEnv = $configPath . "/" . ENVIRONMENT;
            $configPathTmp = "/app.php";
            $key = $str;
        }

        if (ENVIRONMENT) {
            $configRealPath = $configPathEnv . "/" . $configPathTmp;
            if(!is_file($configRealPath)) {
                $phpstr = "return array();";
                writePhp($configRealPath, $phpstr);
            }
            $configs = include($configRealPath);

            $str = '$configs';
            foreach ($arr as $v) {
                $str .= "['" . $v . "']";
            }
            $str .= '=$value;';
            eval($str);
            $phpstr = '<?php' . PHP_EOL . 'return ' . var_export($configs, true) . ";" . PHP_EOL;;
            file_put_contents($configRealPath, $phpstr, LOCK_EX);
            return true;
        }

        $configRealPath = $configPath . "/" . $configPathTmp;
        $configs = include($configRealPath);
        if ($arr) {
            $str = '$configs';
            foreach ($arr as $v) {
                $str .= "['" . $v . "']";
            }
            $str .= '=$value;';
            eval($str);
        }

        $phpstr = 'return ' . var_export($configs, true) . ";";

        writePhp($configRealPath, $phpstr);
        return true;
    }


    function getExt($str){
        $extname = zl::get("ext_name");
        $path = ROOT_PATH . "/app/ext/".$extname."/config";
        return $this->get($str, $path);
    }

    function setExt($str, $value){
        $extname = zl::get("ext_name");
        $path = ROOT_PATH . "/app/ext/".$extname."/config";
        return $this->set($str, $value,$path);
    }

}