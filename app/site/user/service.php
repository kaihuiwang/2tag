<?php

class site_user_service extends zl_service
{
    const ZOLA_LOGIN_SESSION_KEY = "ZOLA_LOGIN_SESSION_KEY";
    const ZOLA_ONLINE_USER = "ZOLA_ONLINE_USER";
    const ZL_REMEMBER_ME = "ZL_REMEMBER_ME";

    function checklogin($userName, $password)
    {
        $check = zl::dao("user")->get(array("email" => $userName, "pwd" => zl_md5($password)));
        return $check;
    }

    function autologin(){
        $str = zl_cookie::cookie()->get(site_user_service::ZL_REMEMBER_ME);
        if(!$str) return ;
        $key = zl::$configApp['salt'];
        $authcode = authcode($str,$key);
        if(!$authcode) return ;
        list($email,$pwd) = explode(chr(0),$authcode);

        $userInfo = $this->checklogin($email,$pwd);
        if($userInfo){
            $this->setLogin($userInfo);
        }
    }


    function register($userName,$password,$nickname){
        $data=array();
        $data['email'] = $userName;
        $data['pwd'] = zl_md5($password);
        $data['nickname'] = $nickname;
        $data['pinyin'] = current(zl_pinyin::getPinyin($nickname));
        $data['join_date'] = date('Y-m-d');
        $id = zl::dao("user")->insert($data);
        if(!$id) return false;
        $userInfo = zl::dao("user")->get(array("id"=>$id));
        $check = zl::dao("data")->get(array("zl_key"=>"member_count"));
        if($check){
            zl::dao("data")->inCrease("zl_value",array("zl_key"=>"member_count"));
        }else{
            zl::dao("data")->insert(array("zl_key"=>"member_count","zl_value"=>1));
        }
        return $userInfo;
    }

    function setLogin($userInfo)
    {

        zl_session::set(self::ZOLA_LOGIN_SESSION_KEY, $userInfo);
        zl::dao("user")->update(array("last_login_time"=>date('Y-m-d H:i:s')),array("id"=>$userInfo['id']));
        return true;
    }

    function isLogin()
    {
        return zl_session::get(self::ZOLA_LOGIN_SESSION_KEY);
    }

    function getLogin()
    {
        $userInfo = $this->isLogin();
        if(!$userInfo) return $userInfo;
        $user = zl::dao("user")->get(array("id"=>$userInfo['id']));
        return $user;
    }

    function logout()
    {
        zl_session::del(self::ZOLA_LOGIN_SESSION_KEY);
        zl_cookie::cookie()->delete(site_user_service::ZL_REMEMBER_ME);
    }

    function logined()
    {
        $adminloginInfo = $this->isLogin();
        if (!$adminloginInfo) {
            if(isAjax()){
                echo "<script>location.assign('".url("login")."')</script>";exit;
            }else{
                if(!isAjax()){
                    $loginUrl = url("/login")."?url=".urlencode(getCurrentUrl());
                }else{
                    $loginUrl = url("/login");
                }
                zl::redirect($loginUrl, 200);
            }
        }
        return true;
    }

    function sendRegisterMail($email){
        $siteName = zl::config()->get("app.siteName");
        $siteUrl = zl::config()->get("app.siteUrl");
        $rand = guid();

        $emailValidate=array();
        $emailValidate['email']=$email;
        $emailValidate['token']=$rand;
        zl::dao("email_token")->insert($emailValidate);

        $validate_url = $siteUrl.url("/emailvalidate")."/".$rand;
        $title = zl::config()->get("lang.register_email.title");
        $body = zl::config()->get("lang.register_email.body");
        $title = str_replace("#site_name#",$siteName,$title);
        $body = str_replace("#site_name#",$siteName,$body);
        $body = str_replace("#site_url#",$siteUrl,$body);
        $body = str_replace("#validate_url#",$validate_url,$body);
        return zl_mail::mail()->send($email,$title,$body);
    }


    function sendFindpwdMail($email){
        $siteName = zl::config()->get("app.siteName");
        $siteUrl = zl::config()->get("app.siteUrl");
        $rand = guid();

        $emailValidate=array();
        $emailValidate['email']=$email;
        $emailValidate['zl_type']=2;
        $emailValidate['token']=$rand;
        zl::dao("email_token")->insert($emailValidate);

        $validate_url = $siteUrl.url("/setpwd")."/".$rand;
        $title = zl::config()->get("lang.find_email.title");
        $body = zl::config()->get("lang.find_email.body");
        $title = str_replace("#site_name#",$siteName,$title);
        $body = str_replace("#site_name#",$siteName,$body);
        $body = str_replace("#site_url#",$siteUrl,$body);
        $body = str_replace("#validate_url#",$validate_url,$body);
        return zl_mail::mail()->send($email,$title,$body);
    }



    function checkNickname($nickname){
        if(mb_strlen($nickname, "UTF-8") > 12 || mb_strlen($nickname, "UTF-8") < 2) return errorReturn("花名长度只能在2-12个字符之间");
        if(!preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",$nickname)) return errorReturn("花名只能由中文，数字，字母，下划线组成");
        return true;
    }

    function getAtUid($content){
        if(!$content) return array();
        preg_match_all("/@(.*?)&nbsp;/", $content, $matches);
        $names= isset($matches[1]) ?$matches[1]:array();
        if(!$names){
            preg_match_all("/@(.*?)\s/", $content, $matches);
            $names= isset($matches[1]) ?$matches[1]:array();
        }
        $uids = array();
        if($names){
            foreach ($names as $v){
                $userInfo = zl::dao("user")->get(array("nickname"=>$v));
                if($userInfo) $uids[] = $userInfo['id'];
            }
        }
        return $uids;
    }

    function addUserLink($content){
        if(!$content) return array();
        preg_match_all("/@(.*?)&nbsp;/", $content, $matches);
        $names= isset($matches[1]) ?$matches[1]:array();
        if(!$names){
            preg_match_all("/@(.*?)\s/", $content, $matches);
            $names= isset($matches[1]) ?$matches[1]:array();
        }
        $uids = array();
        if($names){
            foreach ($names as $v){
                $userInfo = zl::dao("user")->get(array("nickname"=>$v));
                if($userInfo) {
                    $url = url("/u-".$userInfo['id']);
                    $str = "[@".$v." ](".$url.")";
                    $content = preg_replace("/@".$v."&nbsp;/", $str, $content);
                    $content = preg_replace("/@".$v."\s/", $str, $content);
                }
            }
        }
        return $content;
    }

    #发送通知
    function sendNotice($uid,$content,$url,$uids,$zl_type=1){
        if(!$uid||!$uids||!$content) return false;
        $feedTitle = array("user"=>$uid,"content"=>$content);
        $data['feed_content'] = json_encode($feedTitle);
        $data['url'] = $url;
        $data['zl_type'] =$zl_type;
        $id = zl::dao("msg_notice")->insert($data);
        if($uids){
            foreach($uids as $uid){
                $data = array();
                $data['notice_id'] = $id;
                $data['uid'] = $uid;
                zl::dao("msg_notice_read_log")->insert($data);
            }
        }

        return true;
    }

    function getNotice($uid){

        $notice_ids = zl::dao("msg_notice_read_log")->getField("notice_id",array("uid"=>$uid,"is_read"=>0),true," ctime asc ",0,5);
        if($notice_ids){
            $msgs = zl::dao("msg_notice")->gets(array("id"=>array("in",$notice_ids)));
            if($msgs){
                foreach($msgs as $k=>$v){
                    $feed = json_decode($v['feed_content'],true);
                    $user = zl::dao("user")->get(array("id"=>$feed['user']));
                    $msgs[$k]['user'] = $user;
                    $msgs[$k]['feed'] = $feed;
                }
                return $msgs;
            }
        }
        return array();
    }


    function setOnlineUser(){
        $userInfo = $this->getLogin();
        if(!$userInfo) return true;
        $uid =  $userInfo['id'];
        $key = self::ZOLA_ONLINE_USER;
        $users= (array) zl_cache::cache()->get($key);
        $users[$uid] = time();
        zl_cache::cache()->set($key,$users,60*60*24*30);
    }

    function getOnlineUser(){
        $key = self::ZOLA_ONLINE_USER;
        $users= (array) zl_cache::cache()->get($key);
        if($users){
            foreach($users as &$v){
                if(time()-$v*3600) unset($v);
            }
        }
        return $users;
    }

    function getOnlineUserNumber(){
        $user = $this->getOnlineUser();
        return count($user);
    }


}