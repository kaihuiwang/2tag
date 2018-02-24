<?php

class zl_ext_controller extends zl_controller
{
    function display($viewPath='', $params = null)
    {
        zl_hook::run("display_start",array("viewPath"=>$viewPath,"params"=>$params));
        if(!$viewPath){
            $className = get_called_class();
            $extname = zl::get("ext_name");
            $extnameTmp = "ext_".str_replace("/","_",$extname)."_";
            $className = substr($className,strlen($extnameTmp));

            $pathArr  = explode("_",$className);
            $path = ROOT_PATH . "/app/ext/".$extname."/config";
            $route = zl::config()->get("route",$path);
            $route_name = zl::get("current_route_name");
            $croute = $route[$route_name];
            $use = $croute['use'];
            $methodArr = explode("@",$use);
            array_shift($methodArr);
            $method =  array_shift($methodArr);
            array_pop($pathArr);
            $controller = array_pop($pathArr);
            $module = $pathArr ? array_pop($pathArr)."/":"";
            $viewPath = $module.$controller."/".$method;
        }

        if (isset($this->layout) && $this->layout) {
            $path = str_replace(".", '/', $this->layout);
            $path = "layout/" . $path . ".php";
            zl::renderExt($viewPath, $params, "layout_content");
            $newPath = ROOT_PATH . "/app/ext/" . zl::get("ext_name") . "/view/".$path;

            if(is_file($newPath)){
                zl::renderExt($path, $params);
            }else{
                zl::render($path, $params);
            }
        } else {
            zl::renderExt($viewPath, $params);
        }
        zl_hook::run("display_end",array("viewPath"=>$viewPath,"params"=>$params));
    }

}