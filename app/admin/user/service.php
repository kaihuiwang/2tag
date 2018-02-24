<?php
class admin_user_service extends zl_service
{
    const ADMIN_LOGIN_SESSION_KEY = "ADMIN_LOGIN_SESSION_KEY";

    function checklogin($userName, $password)
    {
        $check = zl::dao("admin_user")->get(array("email" => $userName, "pwd" => zl_md5($password), "is_admin" => 1));
        return $check;
    }

    function setLogin($userInfo)
    {
        $superAdmin = zl::config()->get("app.super_admin");
        if($userInfo['email']==$superAdmin){
            $access = array_keys(zl::config()->get("route"));
        }else{
            $access = zl::dao("access")->getField("node",array("role_id"=>$userInfo['role']),true);
        }
        $userInfo['access'] = $access;
        zl_session::set(self::ADMIN_LOGIN_SESSION_KEY, $userInfo);
        zl::dao("admin_user")->update(array("last_login_time"=>date('Y-m-d H:i:s')),array("id"=>$userInfo['id']));
    }

    function isLogin()
    {
        return zl_session::get(self::ADMIN_LOGIN_SESSION_KEY);
    }

    function getLogin()
    {
        return $this->isLogin();
    }

    function logout()
    {
        zl_session::del(self::ADMIN_LOGIN_SESSION_KEY);
    }

}