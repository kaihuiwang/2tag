<?php
function dd($str)
{
    echo "<pre>";
    var_dump($str);
    exit;
}

function d($str,$key='debug')
{
if(zl_config::config()->get("app.debug")) zl_logger::logger()->debug($key,array($str));
}

function pp($str)
{
    echo "<pre>";
    print_r($str);
}

function request($str = "", $default = null)
{
    $post = (array)$_POST;
    $get = (array)$_GET;
    $request = @array_merge($get, $post);
    if ($str == '') {
        return $request;
    } else {
        return isset($request[$str]) ? $request[$str] : $default;
    }
}

function add_s(&$array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                $array[$key] = addslashes($value);
            } else {
                add_s($array[$key]);
            }
        }
    } else {
        $array = addslashes($array);
    }
}

function getRand($salt = "")
{
    $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstyuwxyz1234567890_');
    $randStr .= uniqid($salt);
    return md5($randStr);
}

function guid()
{
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $uuid =
        substr($charid, 0, 8) .
        substr($charid, 8, 4) .
        substr($charid, 12, 4) .
        substr($charid, 16, 4) .
        substr($charid, 20, 12);
    return $uuid;
}

/**
 * 获取主域名
 */
function getMainDomain()
{
    $host = strtolower($_SERVER['HTTP_HOST']);
    if (strpos($host, '/') !== false) {
        $parse = @parse_url($host);
        $host = $parse ['host'];
    }
    $topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me');
    $str = '';
    foreach ($topleveldomaindb as $v) {
        $str .= ($str ? '|' : '') . $v;
    }

    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs ['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}


function getCurrentUrl()
{
    $url = 'http://';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $url = 'https://';
    }
    if ($_SERVER['SERVER_PORT'] != '80') {
        $url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
    } else {
        $url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }
    return $url;
}

function runMethod($methodStr)
{
    if (!$methodStr) return false;
    $arr = explode("@", $methodStr);
    $objName = array_shift($arr);
    $objMothed = array_shift($arr);
    $obj = new $objName();
    $obj->$objMothed();
}

function controller($routeName, $runActionStr)
{
    $regx = $runActionStr['route'];
    $regxArr = explode(" ", $regx);
    if (count($regxArr) == 1) {
        $regx = array_shift($regxArr);
        $urlregx = urlnodir($regx);
    } else {
        $requestMethod = strtoupper(array_shift($regxArr));
        $request = array_shift($regxArr);
        $urlrequest = urlnodir($request);

        $regx = $requestMethod . " " . $request;
        $urlregx = $requestMethod . " " . $urlrequest;
    }
    $regx = stripslashes($regx);
    $urlregx = stripslashes($urlregx);
    $runStr='';
    if($regx=='GET (/)' && ($regx!=$urlregx)){
        $runStr = '
        zl::route("GET (/)", function (){
            zl::set("current_route","GET (/)");
            zl::set("current_route_name","site.index.index");
            zl_hook::run("route_match");
            $obj=new site_index_controller();
            $controller = get_class($obj);
            $controllerArr = explode("_",$controller);
            $obj->index();
        });';
    }

    $before = null;
    $after = null;
    if (!is_array($runActionStr)) {
        $arr = explode("@", $runActionStr);
    } else {
        $use = isset($runActionStr['use']) ? $runActionStr['use'] : null;
        if (!$use) throwException("路由配置错误:参考参数-" . $regx);
        $arr = explode("@", $use);
        $before = isset($runActionStr['before']) ? $runActionStr['before'] : null;
        $after = isset($runActionStr['after']) ? $runActionStr['after'] : null;
    }
    $extname = isset($runActionStr["ext_name"])?$runActionStr["ext_name"]:"";
    $controller = array_shift($arr);
    $action = array_shift($arr);
    $str = "";
    $str .= "  zl::set(\"current_route\",'" . $regx . "');\r\n";
    $str .= "  zl::set(\"current_route_name\",'" . $routeName . "');\r\n";
    $str .= "  zl_hook::run(\"route_match\");\r\n";
    if ($before) {
        $str .= "runMethod('" . $before . "');\r\n";
    }
    $str .= "\$obj=new " . $controller . "();\r\n";
    $str .= "\$controller = get_class(\$obj);\r\n";
    $str .= "\$controllerArr = explode(\"_\",\$controller);\r\n";
    if($extname){
        $str .= "\$namespace = array_shift(\$controllerArr);\r\n";
        $str .= " if(\$namespace=='ext'){\r\n";
        $str .= "  zl::set(\"ext_name\",\"".$extname."\");\r\n";
        $str .= "   }\r\n";
    }
    $str .= "\$obj->" . $action . "(";
    if ($arr) {
        foreach ($arr as $v) {
                $str .= "$" . $v . ",";
        }
    }
    $str = trim($str, ',') . ");\r\n";
    if ($after) {
        $str .= "runMethod('" . $after . "');\r\n";
    }

    $runStr .= "\r\nzl::route(\"" . $urlregx . "\", function (";
    if ($arr) {
        foreach ($arr as $v) {
            $runStr .= "$" . $v . ",";
        }
    }
    $runStr = trim($runStr, ',') . "){\r\n" . $str . "});\r\n";
    return $runStr;
}


function errorReturn($msg)
{
    zl_tool_error::add($msg);
    return false;
}

function hasError()
{
    return zl_tool_error::hasError();
}

function getError()
{
    return zl_tool_error::lastError();
}

function throwException($msg)
{
    throw new Exception($msg);
}

function writePhp($path, $phpcode)
{
    $phpstr = '<?php' . PHP_EOL . $phpcode . PHP_EOL;
    if(!is_writable($path) && is_file($path)) throwException($path."文件不可写");
    file_put_contents($path, $phpstr, LOCK_EX);
    chmod($path,0777);
}

function apache_module_exists($module)
{
    return in_array($module, apache_get_modules());
}

function isApache()
{
    $serverInfo = apache_get_version();
    if (!$serverInfo) return false;
    $serverInfo = strtolower($serverInfo);
    return strstr($serverInfo, 'apache');
}

function url($url,$params=array())
{
    $is_rewrite = zl::config()->get("app.isRewrite");
    $dir = dirname($_SERVER['PHP_SELF']);
    $dirArr = explode("index.php",$dir);
    $dir = array_shift($dirArr);
    $dir = str_replace("\\", "/", $dir);
    $dir = str_replace("//", "/", $dir);
    $param = $params?"?".http_build_query($params):"";
//    echo $dir."|";
    if($url == '/'){
        if ($is_rewrite) {
            if (isApache()) {
                //是否开启rewrite--apache
                if (apache_module_exists("mod_rewrite")) {
                    $rurl=str_replace("//", "/", $dir . $url.$param);
                } else {
                    $rurl=str_replace("//", "/", $dir . "/index.php" . $url.$param);
                }
            } else {
                $rurl=str_replace("//", "/", $dir . $url);
            }
        } else {
            $rurl=str_replace("//", "/", $dir . "/index.php" . $url.$param);
        }
        $rurl= rtrim($rurl,"/");
        return $rurl;
    }

    $url = trim($url, "/");
    $url = $url!="/" && $url!="(/)"?"/".$url:$url;

    if ($is_rewrite) {
        if (isApache()) {
            //是否开启rewrite--apache
            if (apache_module_exists("mod_rewrite")) {
                return str_replace("//", "/", $dir . $url.$param);
            } else {
                return str_replace("//", "/", $dir . "/index.php" . $url.$param);
            }
        } else {
            return str_replace("//", "/", $dir . $url);
        }
    } else {
        return str_replace("//", "/", $dir . "/index.php" . $url.$param);
    }
}


function getDir(){
    $dir = dirname($_SERVER['PHP_SELF']);
    $dirArr = explode("index.php",$dir);
    $dir = array_shift($dirArr);
    $dir = str_replace("\\", "/", $dir);
    $dir = str_replace("//", "/", $dir);
    return $dir;
}

function urlnodir($url,$params=array()){
    $url = url($url,$params);
    $dir = getDir();
    $url = str_replace($dir,"/",$url);
    $url = str_replace("//", "/", $url);
    return $url;
}

function pagiUrl($page, $pageCount,$currentUrl=''){
    $page = intval($page);
    $page = $page > $pageCount ? $pageCount : $page;
    $page = $page < 1 ? 1 :  $page;
    if($currentUrl){
        $url = str_ireplace("@p",$page,$currentUrl);
        return url($url);
    }
    $matchRoute = zl::get("current_route_name");
    if(zl::get("ext_name")){
        $route = zl::config()->getExt("route");
    }else{
        $route = zl::config()->get("route");
    }
    $matchRoute = $route[$matchRoute]['route'];
    $matchRoute = trim(str_ireplace(array("GET","POST","|"),"",$matchRoute));

    $pattern = url($matchRoute);

    $url = isset($_SERVER["REQUEST_URI"])? $_SERVER["REQUEST_URI"] : "";

    $ids = array();
    $last_char = substr($pattern, -1);

    // Get splat
    if ($last_char === '*') {
        $n = 0;
        $len = strlen($url);
        $count = substr_count($pattern, '/');

        for ($i = 0; $i < $len; $i++) {
            if ($url[$i] == '/') $n++;
            if ($n == $count) break;
        }
    }

    // Build the regex for matching
    $regex = str_replace(array(')', '/*'), array(')?', '(/?|/.*?)'), $pattern);

    $regex = preg_replace_callback(
        '#@([\w]+)(:([^/\(\)]*))?#',
        function ($matches) use (&$ids) {
            $ids[$matches[1]] = null;
            if (isset($matches[3])) {
                return '(?P<' . $matches[1] . '>' . $matches[3] . ')';
            }
            return '(?P<' . $matches[1] . '>[^/\?]+)';
        },
        $regex
    );
    // Fix trailing slash
    if ($last_char === '/') {
        $regex .= '?';
    } // Allow trailing slash
    else {
        $regex .= '/?';
    }
    // Attempt to match route and named parameters
     $matchP=0;
    if (preg_match('#^' . $regex . '(?:\?.*)?$#i', $url, $matches)) {
        foreach ($ids as $k => $v) {
            $params[$k] = (array_key_exists($k, $matches)) ? urldecode($matches[$k]) : null;
            if($params[$k] === ""){
                $value = "";
            }else{
                $value = "/".$params[$k];
            }
            if($k=='p'){
                $matchP=1;
                $value = "/".$page;
            }
            $pattern = str_replace("(/@".$k.")",$value,$pattern);
            $pattern = str_replace("/@".$k."",$value,$pattern);
            $pattern = str_replace("@".$k."",$value,$pattern);
        }
    }

    $request = $_POST+$_GET;
    if(!$matchP){
        $request['p'] = $page;
    }
    if($request){
        $pattern .="?".http_build_query($request);
    }
    return $pattern;
}


function getMatchRequest($key,$default=''){
    $matchRoute = zl::get("current_route");
    $matchRoute = trim(str_replace(array("GET","POST","|"),"",$matchRoute));
    $pattern = url($matchRoute);
    $url = isset($_SERVER["REQUEST_URI"])? $_SERVER["REQUEST_URI"] : "";

    $ids = array();
    $last_char = substr($pattern, -1);
    // Get splat
    if ($last_char === '*') {
        $n = 0;
        $len = strlen($url);
        $count = substr_count($pattern, '/');

        for ($i = 0; $i < $len; $i++) {
            if ($url[$i] == '/') $n++;
            if ($n == $count) break;
        }
    }

    // Build the regex for matching
    $regex = str_replace(array(')', '/*'), array(')?', '(/?|/.*?)'), $pattern);

    $regex = preg_replace_callback(
        '#@([\w]+)(:([^/\(\)]*))?#',
        function ($matches) use (&$ids) {
            $ids[$matches[1]] = null;
            if (isset($matches[3])) {
                return '(?P<' . $matches[1] . '>' . $matches[3] . ')';
            }
            return '(?P<' . $matches[1] . '>[^/\?]+)';
        },
        $regex
    );
    // Fix trailing slash
    if ($last_char === '/') {
        $regex .= '?';
    } // Allow trailing slash
    else {
        $regex .= '/?';
    }
    // Attempt to match route and named parameters
    if (preg_match('#^' . $regex . '(?:\?.*)?$#i', $url, $matches)) {
        foreach ($ids as $k => $v) {
            $params[$k] = (array_key_exists($k, $matches)) ? urldecode($matches[$k]) : null;
        }
    }
    return isset($params[$key])?$params[$key]:$default;
}

function script($url,$extName='')
{
    $url = trim($url, "/");
    $dir = dirname($_SERVER['PHP_SELF']);
    $dirArr = explode("index.php",$dir);
    $dir = array_shift($dirArr);
    $dir = str_replace("\\", "/", $dir);
    if(!$extName){
        $path = $dir ."/".getConfig("app.public_path") . "/" . $url;
    }else{
        $path = $dir ."/app/ext/".$extName."/".getConfig("app.public_path") . "/" . $url;
    }
    $path = str_replace("//", "/", $path);
    $v = zl::config()->get("app.static_version");
    return "<script src=\"" . $path . "?v=" . $v . ".js\"></script>" . PHP_EOL;
}

function style($url,$extName='')
{
    $url = trim($url, "/");
    $dir = dirname($_SERVER['PHP_SELF']);
    $dirArr = explode("index.php",$dir);
    $dir = array_shift($dirArr);
    $dir = str_replace("\\", "/", $dir);
    if(!$extName){
        $path = $dir ."/".getConfig("app.public_path") . "/" . $url;
    }else{
        $path =  $dir ."/app/ext/".$extName."/".getConfig("app.public_path") . "/" . $url;
    }
    $path = str_replace("//", "/", $path);
    $v = zl::config()->get("app.static_version");
    return "<link rel=\"stylesheet\" type='text/css' href=\"" . $path . "?v=" . $v . ".css\"/>" . PHP_EOL;
}




function getGravatar($uid, $s = 48, $d = 'retro')
{
    $url = 'http://gravatar.duoshuo.com/avatar/';
    $url .= $uid;
    $url .= "?s=$s&d=$d";
//    $path = ROOT_PATH."/public/images/face_default/".$md5."_".$s.".png";
//    if(is_file($path)){
//        return img("public/images/face_default/".$md5."_".$s.".png");
//    }
//    $img = file_get_contents($url);
//    file_put_contents($path,$img);
    return $url;
}


function showImg($uid = '', $size = '32')
{
    if(is_numeric($uid)){
        $face = zl::dao("user")->getField("face",array("id"=>$uid));
    }
    if (!$uid) {
        return getGravatar(rand(1, 9999),$size);
    } elseif(!isPath($face)){
        $rand = $uid+4;
        return getGravatar($rand,$size);
    }else {
        $pathinfo = pathinfo($face);
        $ext = $pathinfo['extension'];
        return img($face . "_" . $size . "x" . $size . ".".$ext);
    }
}

function isPath($str)
{
    if (stristr($str, '/')) return true;
    if (stristr($str, '\\')) return true;
    return false;
}

function img($url)
{
    $url = trim($url, "/");
    $dir = dirname($_SERVER['PHP_SELF']);
    $dir = str_replace("\\", "/", $dir);
    $dir = str_replace("index.php", "", $dir);
    $path = $dir . "/".$url;
    $path = str_replace("//","/",$path);
    return $path;
}

function isImg($imgUrl)
{
    return preg_match("/(\.jpg)|(\.png)|(\.gif)|(\.jpeg)$/i", $imgUrl);
}

function removeXss($val)
{
    $val = stripslashes($val);
    $result = dealWithXss($val);
    return $result;
}

function dealWithXss($html, $allow_tag = array(), $allow_tag_attr = array())
{
    if (!$allow_tag) {
        $allowStr = "p,strong,a,em,table,td,tr,h1,h2,h3,h4,h5,hr,br,u,ul,ol,li,center,code,div,font,blockquote,small,caption,img,span,strike,sup,sub,b,dl,dt,dd,embed,object,param,pre,tbody";
        $allow_tag = explode(',', $allowStr);
    }
    if (!$allow_tag_attr) {
        $allow_tag_attr = array(
            '*' => array(
                'style' => '/.*/i',
                'alt' => '/.*/i',
                'width' => '/^[\w_-]+$/i',
                'height' => '/^[\w_-]+$/i',
                'class' => '/.*/i',
                'name' => '/^.*$/i',
                'value' => '/.*/i',
            ),
            "object" => array("data" => '/.*/i',
            ),
            "embed" => array(
                "type" => '/.*/i',
                'src' => '/.*/i',
            ),
            "font" => array(
                "color" => '/^[\w_-]+$/i',
                "size" => '/^[\w_-]+$/i',
            ),
            'a' => array(
                'href' => '/.*/i',
                'title' => '/.*/i',
                'target' => '/^[\w_-]+$/i',
            ),
            'img' => array(
                'src' => '/.*/i',
            ),
        );
    }
    //匹配出所有尖括号包含的字符串
    preg_match_all('/<[^>]*>/s', $html, $matches);

    if ($matches[0]) {
        $tags = $matches[0];

        foreach ($tags as $tag_k => $tag) {

            //匹配出标签名 比如 a, br, html, li, script
            preg_match_all('/^<\s{0,}\/{0,}\s{0,}([\w]+)/i', $tag, $tag_name);
            $tags[$tag_k] = array('name' => $tag_name[1][0], 'html' => $tag);
            if ($tag_name && in_array($tags[$tag_k]['name'], $allow_tag)) {

                //匹配出含等于号的属性，注，当前版本不支持readonly等无等于号的属性
                preg_match_all('/\s{0,}([a-z]+)\s{0,}=\s{0,}["\']{0,}([^\'"]+)["\']{0,}[^>]/i', $tag, $tag_matches);
                if ($tag_matches[0]) {
                    $tags[$tag_k]['attr'] = $tag_matches;
                    foreach ($tags[$tag_k]['attr'][1] as $k => $v) {
                        $attr = $tags[$tag_k]['attr'][1][$k];
                        $value = $tags[$tag_k]['attr'][2][$k];
                        $preg_attr_all = isset($allow_tag_attr['*'][$attr]) ? $allow_tag_attr['*'][$attr] : "";
                        $preg_attr = isset($allow_tag_attr[$tags[$tag_k]['name']][$attr]) ? $allow_tag_attr[$tags[$tag_k]['name']][$attr] : "";

                        //判断该属性是否允许，如不允许，则unset。
                        if (($preg_attr && preg_match($preg_attr, $value)) || ($preg_attr_all && preg_match($preg_attr_all, $value))) {
                            $tags[$tag_k]['attr'][0][$k] = "{$attr}='{$value}'";
                        } else {
                            unset($tags[$tag_k]['attr'][0][$k]);
                        }
                    }
                    $tags[$tag_k]['replace'] = '<' . $tags[$tag_k]['name'];
                    if (is_array($tags[$tag_k]['attr'][0])) $tags[$tag_k]['replace'] .= ' ' . implode(' ', $tags[$tag_k]['attr'][0]);
                    $tags[$tag_k]['replace'] .= '>';
                } else {
                    $tags[$tag_k]['replace'] = $tags[$tag_k]['html'];
                }
            } else {
                $tags[$tag_k]['replace'] = htmlentities($tags[$tag_k]['html']);
            }
            $search[$tag_k] = $tags[$tag_k]['html'];
            $replace[$tag_k] = $tags[$tag_k]['replace'];
        }
        $html = str_replace($search, $replace, $html);
    }
    return $html;
}

function canAdmin($dtstr)
{
    if (is_string($dtstr)) {
        $dtstr = strtotime($dtstr);
    }

    $treedayTime = strtotime("-7 days");
    return $treedayTime > $dtstr ? false : true;
}

function getRequestType()
{
    $accept = $_SERVER['HTTP_ACCEPT'];
    $types = explode(',', $accept);
    if (in_array("text/html", $types)) {
        return "html";
    } elseif (in_array("application/json", $types)) {
        return "json";
    } elseif (in_array("application/xml", $types)) {
        return "xml";
    } else {
        return "unknow";
    }
}

function array_format($arr, $fields)
{
    if (!$arr || !$fields) return array();
    $fieldsTmp = array_flip($fields);
    $arrTmp = array_intersect_key($arr, $fieldsTmp);
    return $arrTmp;
}

function getConfig($str)
{
    return zl::config()->get($str);
}

function setConfig($key, $value, $path = '')
{
    return zl::config()->set($key, $value, $path);
}

function zl_md5($str, $salt = "")
{
    if (!$salt) {
        $salt = getConfig("app.salt");
    }
    $pwd = md5($salt . $str);
    return $pwd;
}

function changeArray2One($arr, $tag = '.', $keyStr = "")
{
    global $getKeyResult;
    $keyTmp = $keyStr;
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            $keyStr .= $tag . $k;
            if (is_array($v)) {
                changeArray2One($v, $tag, $keyStr);
                $keyStr = $keyTmp;
            } else {
                $keyStr = trim($keyStr, $tag);
                $getKeyResult[$keyStr] = $v;
                $keyArr = explode($tag, $keyStr);
                array_pop($keyArr);
                $keyStr = implode($tag, $keyArr);
            }
        }
    } else {
        $getKeyResult = $arr;
    }
    return $getKeyResult;
}

function marskName($str, $left_length = 1, $right_length = 1)
{
    $s = mb_strlen($str, 'utf-8');
    if ($s < $left_length + $right_length) {
        return $str;
    }
    $left = mb_substr($str, 0, $left_length, 'utf-8');
    if ($s == $left_length + $right_length) {
        return $left . str_repeat('*', $right_length);
    }
    $right = mb_substr($str, $s - $right_length, $right_length, 'utf-8');
    return $left . str_repeat('*', $s - $left_length - $right_length) . $right;
}

//投票算法分数
function getVoteScore($vote, $devote)
{
    $voteDiff = $vote - $devote;
    if ($voteDiff > 0) {
        $pos = 1;
    } elseif ($voteDiff < 0) {
        $pos = -1;
    } else {
        $pos = 0;
    }
    $voteDispute = $voteDiff != 0 ? abs($voteDiff) : 1;
    $fund = strtotime('2012-12-12');
    $created = strtotime('-3 days');
    $time = $created - $fund;
    $socre = log10($voteDispute) + $pos * $time / 45000;
    return round($socre,2);
}

//自动分词获取标签
function getTags($subject, $message, $num = 3)
{
    $subjectenc = strip_tags($subject);
    $messageenc = strip_tags(preg_replace("/[\s]{2,}/", '', $message));
    $parsestr = $subjectenc . $messageenc;
    $parsestr = preg_replace("/&.*?;/i", '', $parsestr);
    include_once ROOT_PATH . "/lib/thirdpart/fenci/pscws4.class.php";
    $pathRoot = ROOT_PATH . "/lib/thirdpart/fenci";
    $pscws = new PSCWS4();
    $pscws->set_dict($pathRoot . '/scws/dict.utf8.xdb');
    $pscws->set_rule($pathRoot . '/scws/rules.utf8.ini');
    $pscws->set_ignore(true);
    $pscws->send_text($parsestr);
    $words = $pscws->get_tops($num);
    $tags = array();
    foreach ($words as $val) {
        $tags[] = $val['word'];
    }
    $pscws->close();
    return $tags;
}

function substrtxt($string, $length = 80, $etc = '')
{
    $str = mb_substr($string, 0, $length, "UTF-8");
    return $etc ? $str . $etc : $str;
}

/**
 *
 * 加密解密
 * @param string $string
 * @param string $operation
 * @param string $key
 * @param string $expiry
 */
function authcode($string, $key = '', $operation = 'DECODE', $expiry = 0)
{
    $ckey_length = 4;
    if ($key == "") return false;
    $key = md5($key ? $key : "ad^%FFGFFFF");
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

/**
 *
 * 模拟file_get_contents
 * @param string $durl
 * @param int $timeOut
 * @param array $proxyArr array("127.0.0.1:8080", "user:pwd")
 */
function curlGetContents($durl, $timeOut = 0, $proxyArr = array())
{
    if (!$durl) return false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    if ($timeOut) {
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
    }
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if ($proxyArr) {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_PROXY, $proxyArr[0]);
        if (isset($proxyArr[1])) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr[1]);
        }
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $r = curl_exec($ch);
    if (!$r) return false;
    return $r;
}

function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function getOlineIp()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    preg_match("/[\d\.]{7,15}/", $onlineip, $ipmatches);
    $onlineip = $ipmatches[0] ? $ipmatches[0] : 'unknown';
    return $onlineip;
}

#是否是ajax请求，jquery有效
function isAjax()
{
    $tag = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : "";
    return $tag == 'xmlhttprequest' ? true : false;
}

/**
 *
 * 是否提交
 */
function isPost()
{
    return strtolower($_SERVER['REQUEST_METHOD']) == "post" ? true : false;
}

function isSerialized($data)
{
    if (!is_string($data))
        return false;
    $data = trim($data);
    if ('N;' == $data)
        return true;
    if (!preg_match('/^([adObis]):/', $data, $badions))
        return false;
    switch ($badions[1]) {
        case 'a' :
        case 'O' :
        case 's' :
            if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                return true;
            break;
    }
    return false;
}

//自动转码
function safeEncoding($string, $outEncoding = 'UTF-8')
{
    $encoding = "UTF-8";
    for ($i = 0; $i < strlen($string); $i++) {
        if (ord($string{$i}) < 128)
            continue;

        if ((ord($string{$i}) & 224) == 224) {
            //第一个字节判断通过
            $char = $string{++$i};
            if ((ord($char) & 128) == 128) {
                //第二个字节判断通过
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    $encoding = "UTF-8";
                    break;
                }
            }
        }
        if ((ord($string{$i}) & 192) == 192) {
            //第一个字节判断通过
            $char = $string{++$i};
            if ((ord($char) & 128) == 128) {
                //第二个字节判断通过
                $encoding = "GBK";
                break;
            }
        }
    }
    if (strtoupper($encoding) == strtoupper($outEncoding))
        return $string;
    else
        return iconv($encoding, $outEncoding . "//ignore", $string);
}

/**
 * php 切割html字符串 自动配完整标签
 *
 * @param $s 字符串
 * @param $zi 长度
 * @param $ne 没有结束符的html标签
 */
function htmlCut($s, $zi, $ne = ',br,hr,input,img,')
{
    $s = stripslashes($s);
    $s = preg_replace('/\s{2,}/', ' ', $s);
    $os = preg_split('/<[\S\s]+?>/', $s);
    preg_match_all('/<[\S\s]+?>/', $s, $or);
    if (!$or[0]) return mb_substr($s, 0, $zi, "UTF-8");
    $s = '';
    $tag = array();
    $n = 0;
    $m = count($or[0]) - 1;
    foreach ($os as $k => $v) {
        $n = $k > $m ? $m : $k;
        if ($v != '' && $v != ' ') {
            $l = strlen($v);
            for ($i = 0; $i < $l; $i++) {
                if (ord($v[$i]) > 127) {
                    $s .= $v[$i] . $v[++$i] . $v[++$i];
                } else {
                    $s .= $v[$i];
                }
                $zi--;
                if ($zi < 1) {
                    break 2;
                }
            }
        }

        preg_match('/<\/?([^\s>]+)[\s>]{1}/', $or[0][$n], $t);
        $s .= $or[0][$n];
        if (strpos($ne, ',' . strtolower($t[1]) . ',') === false && $t[1] != '' && $t[1] != ' ') {
            $n = array_search('</' . $t[1] . '>', $tag);
            if ($n !== false) {
                unset($tag[$n]);
            } else {
                array_unshift($tag, '</' . $t[1] . '>');
            }
        }
    }
    return $s . implode('', $tag);
}

//url转码
function urlEscape($str)
{
    preg_match_all("/[\x80-\xff].|[\x01-\x7f]+/", $str, $r);
    $ar = $r[0];
    foreach ($ar as $k => $v) {
        if (ord($v[0]) < 128)
            $ar[$k] = rawurlencode($v);
        else
            $ar[$k] = "%u" . bin2hex(iconv("GB2312", "UCS-2", $v));
    }
    return join("", $ar);
}

//url反转
function urlUnescape($str)
{
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        if ($str[$i] == '%' && $str[$i + 1] == 'u') {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f) $ret .= chr($val);
            else if ($val < 0x800) $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
            else $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
            $i += 5;
        } else if ($str[$i] == '%') {
            $ret .= urldecode(substr($str, $i, 3));
            $i += 2;
        } else $ret .= $str[$i];
    }
    return $ret;
}

/**
 *
 * 几小时前
 * @param int $time
 * @return string
 */
function qtime($time)
{
    if($time =='0000-00-00 00:00:00') return "";
    if (is_string($time)) $time = strtotime($time);
    $limit = time() - $time;

    if ($limit < 60)
        $time = "{$limit}秒前";
    if ($limit >= 60 && $limit < 3600) {
        $i = floor($limit / 60);
        $_i = $limit % 60;
        $s = $_i;
        $time = "{$i}分{$s}秒前";
    }
    if ($limit >= 3600 && $limit < 3600 * 24) {
        $h = floor($limit / 3600);
        $_h = $limit % 3600;
        $i = ceil($_h / 60);
        $time = "{$h}小时{$i}分前";
    }
    if ($limit >= (3600 * 24) && $limit < (3600 * 24 * 30)) {
        $d = floor($limit / (3600 * 24));
        $time = "{$d}天前";
    }
    if ($limit >= (3600 * 24 * 30)) {
        $time = gmdate('Y-m-d H:i', $time);
    }
    return $time;
}

/**
 *
 * 来源页
 * @param string $default
 */
function getRef($default = "")
{
    return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $default;
}

/**
 * 文件大小 字符串
 * @static
 * @param $size
 * @return string
 */
function getFileSizeText($size)
{
    if ($size < 0x400) // Bytes - 1024
        return $size . ' Bytes';
    else if ($size < 0x100000) // KB - 1024
        return (round($size / 0x400 * 100) / 100) . 'KB';
    else if ($size < 0x40000000) // MB - 1024 * 1024
        return (round($size / 0x100000 * 100) / 100) . ' MB';
    else // GB - 1024 * 1024 * 1024
        return (round($size / 0x40000000 * 100) / 100) . ' GB';
}

/**
 * 取出html图片地址
 * @param string $content
 */
function getImgPath($content)
{
    //取出图片路径
    $content = str_replace("alt=\"\"", "", $content);
    $content = str_replace("alt=\'\'", "", $content);
    $content = str_replace("alt=", "", $content);
    $content = str_replace("alt", "", $content);
    preg_match_all("/<img.*?src\s*=\s*.*?([^\"'>]+\.(gif|jpg|jpeg|bmp|png))/i", $content, $match);
    $result = isset($match[1]) ? $match[1] : array();
    if ($result) return $result;
    preg_match_all("/<img.*?src=[\"|\'|\s]?(.*?)[\"|\'|\s]/i", $content, $match1);
    return isset($match1[1]) ? $match1[1] : array();
}

#获取ping code
function getPingCode($url = "http://www.baidu.com/", $proxyArr = array())
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
    if ($proxyArr) {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_PROXY, $proxyArr[0]);
        if (isset($proxyArr[1])) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr[1]);
        }
    }
    curl_exec($ch);
    return curl_getinfo($ch, CURLINFO_HTTP_CODE);
}

#检查代理是否有用
function checkProxyIp($ip)
{
    $url = "http://www.baidu.com/";
    $code = getPingCode($url, array($ip));
    return $code == 200 ? true : false;
}

#复制目录
function xCopy($source, $destination, $child = 1)
{
    if (!is_dir($source)) {
        return false;
    }
    if (!is_dir($destination)) {
        mkdir($destination, 0777, true);
    }

    $handle = dir($source);
    while ($entry = $handle->read()) {
        if (($entry != ".") && ($entry != "..")) {
            if (is_dir($source . "/" . $entry)) {
                if ($child)
                    xCopy($source . "/" . $entry, $destination . "/" . $entry, $child);
            } else {
                copy($source . "/" . $entry, $destination . "/" . $entry);
            }
        }
    }
    return true;
}


#目录下文件正则批量替换
function batchReplace($sourcePath, $reg, $replaceTo, $ext = "php,ini,phtml")
{
    if (!is_dir($sourcePath)) {
        return false;
    }

    $handle = dir($sourcePath);
    while ($entry = $handle->read()) {
        if (($entry != ".") && ($entry != "..")) {
            $tmpPath = $sourcePath . "/" . $entry;
            if (is_dir($tmpPath)) {
                batchReplace($tmpPath, $reg, $replaceTo, $ext);
            } else {
                //开始替换
                $pathinfo = pathinfo($tmpPath);
                $extArr = explode(",", $ext);
                if (isset($pathinfo['extension']) && in_array($pathinfo['extension'], $extArr)) {
                    $tmpData = file_get_contents($tmpPath);
                    $tmpData = preg_replace($reg, $replaceTo, $tmpData);
                    file_put_contents($tmpPath, $tmpData);
                }
            }
        }
    }
    return true;
}


function getDirName($sourcePath){
    if (!is_dir($sourcePath)) {
        return false;
    }

    $handle = dir($sourcePath);
    $arr = array();
    while ($entry = $handle->read()) {
        if (($entry != ".") && ($entry != "..")) {
            $tmpPath = $sourcePath . "/" . $entry;
            if (is_dir($tmpPath)) $arr[]=$entry;
        }
    }
    return $arr;
}

#目录下文件夹，文件名正则批量替换
function batchDirNameReplace($sourcePath, $reg, $replaceTo)
{
    if (!is_dir($sourcePath)) {
        return false;
    }
    $handle = dir($sourcePath);
    while ($entry = $handle->read()) {
        if (($entry != ".") && ($entry != "..")) {
            $tmpPath = $sourcePath . "/" . $entry;
            $newEntry = preg_replace($reg, $replaceTo, $entry);
            $newPath = $sourcePath . "/" . $newEntry;
            if ($newPath != $tmpPath) rename($tmpPath, $newPath);
            if (is_dir($newPath)) {
                batchDirNameReplace($newPath, $reg, $replaceTo);
            }
        }
    }
    return true;
}

//变量处理
function escape($value)
{
    if (is_array($value)) {
        return array_map('escape', $value);
    } else {
        if (is_null($value)) return 'null';
        if (is_bool($value)) return $value ? 1 : 0;
        if (is_int($value)) return (int)$value;
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        return $value;
    }
}


function getBackData($str = null)
{
    if (!$str) {
        return zl_session::flash_get("data");
    }
    $data = zl_session::flash_get("data");
    return isset($data[$str]) ? $data[$str] : null;
}


function parse_number($number, $dec_point = ".")
{
    if (empty($dec_point)) {
        $locale = localeconv();
        $dec_point = $locale['decimal_point'];
    }

    return number_format(floatval(str_replace($dec_point, '.', preg_replace('/[^\d' . preg_quote($dec_point) . ']/', '', $number))), 2, '.', '');
}

function check_email($email)
{
    $regular = '/^[a-z0-9]([a-z0-9\\.]*[-_]{0,4}?[a-z0-9-_\\.]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+([\\.][\\w_-]+){1,5}$/i';   //update by xuewen 2014-11-28
    if(strpos($email,'@') AND preg_match($regular,$email)){
        return true;
    }else{
        return false;
    }
}

function deldir($dir) {
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
    if(rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

function viewContent($content){
    return zl_markdown::markdown()->parse(site_user_service::service()->addUserLink($content));
}

function viewXss($content){
    return removeXss($content);
}

