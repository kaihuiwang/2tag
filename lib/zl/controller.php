<?php

class zl_controller
{
    public $layout = null;

    function request()
    {
        return zl::request();
    }

    function getParam($str = null,$default=null)
    {
        return zl::getParam($str,$default);
    }

    function json($str)
    {
        return zl::json($str);
    }

    function jsonp($str, $callback = 'jsonpcallback')
    {
        return zl::jsonp($str, $callback);
    }

    function showJson($status=1,$data=array()){
        echo $this->json(array("status"=>$status,"data"=>$data));
        exit;
    }

    function showJsonp($status=1,$data=array()){
        echo $this->jsonp(array("status"=>$status,"data"=>$data));
        exit;
    }

    function redirect($url, $code = 200)
    {
        return zl::redirect(urlnodir($url), $code);
    }

    function redirectAll($url, $code = 200)
    {
        return zl::redirect($url, $code);
    }

    function redirectWithError($errorMsg, $url = '')
    {
        zl_session::flash_set("msg", $errorMsg);
        $query = $this->getParam();
        zl_session::flash_set("data", $query);
        if (!$url) $url = getCurrentUrl();
        return $this->redirectAll($url);
    }

    function display($viewPath='', $params = null)
    {
        zl_hook::run("display_start",array("viewPath"=>$viewPath,"params"=>$params));
        if(!$viewPath){
            $className = get_called_class();
            $pathArr  = explode("_",$className);
            $route = zl::config()->get("route");
            $route_name = zl::get("current_route_name");
            $croute = $route[$route_name];
            $use = $croute['use'];
            $methodArr = explode("@",$use);
            array_shift($methodArr);
            $method =  array_shift($methodArr);
            $viewPath = array_shift($pathArr)."/".array_shift($pathArr)."/".$method;
        }

        if (isset($this->layout) && $this->layout) {
            $path = str_replace(".", '/', $this->layout);
            $path = "layout/" . $path . ".php";
            zl::render($viewPath, $params, "layout_content");
            zl::render($path, $params);
        } else {
            zl::render($viewPath, $params);
        }
        zl_hook::run("display_end",array("viewPath"=>$viewPath,"params"=>$params));
    }

    function disableLayout()
    {
        $this->layout = null;
    }

    function setLayout($layout)
    {
        $this->layout = $layout;
    }

    function assign($k, $v)
    {
        zl::view()->set($k, $v);
    }

    function __set($k, $v)
    {
        zl::view()->set($k, $v);
    }

    function __get($k)
    {
        return zl::view()->get($k);
    }

    function showMsg($msg,$type = 0,$isJs = 1,$redirect = "")
    {
        if(isAjax()){
            if($type == 1){
                $this->showJson(1,$msg);
            }else{
                $this->showJson(0,$msg);
            }
        }else{
            zl::showMsg($msg, $redirect, $isJs, $type);
        }
    }

    function showMsgBack($msg){
        $this->showMsg($msg,0,1);
    }

    function requestCookie($param, $default=""){
        if(!$param) return false;
        $pre = zl::get('current_route_name');
        $result = zl_cookie::cookie()->get($pre . $param);
        $resultTmp = $this->getParam($param);
        if($resultTmp && $resultTmp>0){
            zl_cookie::cookie()->set($pre . $param, $resultTmp, 60*30);//保存半小时
            return ($resultTmp != "") ? $resultTmp:$default;
        }elseif($resultTmp === 0||$resultTmp === '0'){
            zl_cookie::cookie()->set($pre . $param, 0, 60*30);//保存半小时
            return 0;
        }elseif($resultTmp<0){
            zl_cookie::cookie()->set($pre . $param, $resultTmp, 60*30);//保存半小时
            return $resultTmp;
        }else{
            return $result;
        }
    }

    function getUser(){
        $user = site_user_service::service()->getLogin();
        return $user;
    }

    function getUid(){
        $user = site_user_service::service()->getLogin();
        return $user['id'];
    }

    function getAdminUser(){
        $user = admin_user_service::service()->getLogin();
        return $user;
    }

    function getAdminUid(){
        $user = admin_user_service::service()->getLogin();
        return $user['id'];
    }


}