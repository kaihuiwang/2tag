<?php

class site_user_controller extends zl_controller
{

    public $layout="main";
    function login()
    {
        $url = $this->getParam("url");
        if (isPost()) {
            $email = $this->getParam("email");
            $pwd = $this->getParam("pwd");
            $captcha = $this->getParam("captcha");
            $rememberme = (int) $this->getParam("rememberme");

            if (!$email) $this->showMsg("邮箱为空!");
            if (!$pwd) $this->showMsg("密码为空!");
            if (!$captcha) $this->showMsg("验证码为空!");

            if (!site_index_service::service()->checkCaptcha($captcha, "login")) $this->showMsg("验证码错误!");

            zl_form::form()->csrfValidate();
			
            $login = site_user_service::service()->checklogin($email, $pwd);
            if ($login) {
                site_user_service::service()->setLogin($login);
                if($rememberme){
                    $pwdStr = $email.chr(0).$pwd;
                    $key = zl::$configApp['salt'];
                    zl_cookie::cookie()->set(site_user_service::ZL_REMEMBER_ME,authcode($pwdStr,$key,''));
                }
                if($url){
                    $url = urldecode($url);
                    zl::redirect($url, 200);
                }else{
                    $this->redirect("/");
                }
            } else {
                $this->showMsg('用户名或密码错误!');
            }
        }
        $this->setLayout("user_nologin");
        $this->title="登录页";
        $this->url=$url;
        $this->display("site/user/login");
    }


    function register(){

        if (isPost()) {
            zl_form::form()->csrfValidate();
            $email = $this->getParam("email");
            $pwd = $this->getParam("pwd");
            $repwd = $this->getParam("repwd");
            $captcha = $this->getParam("captcha");
            $nickname = $this->getParam("nickname");

            if (!$email) $this->showMsg("邮箱不能为空!");
            if (!$pwd) $this->showMsg("密码不能为空!");
            if (!$captcha) $this->showMsg("验证码不能为空!");
            if (!$nickname) $this->showMsg("花名不能为空!");
            if(!check_email($email)) $this->showMsg("邮箱格式不匹配!");
            if($pwd!=$repwd){
                $this->showMsg("重复密码不匹配!");
            }

            if (!site_index_service::service()->checkCaptcha($captcha, "register")) $this->showMsg("验证码错误!");

            if(zl::dao("admin_user")->get(array("email"=>$email))) $this->showMsg("邮箱已被注册!",null,1);
            if(zl::dao("admin_user")->get(array("nickname"=>$nickname))) $this->showMsg("花名已被注册!",null,1);

            $login = site_user_service::service()->register($email, $pwd,$nickname);
            if ($login) {
                site_user_service::service()->sendRegisterMail($email);
                site_user_service::service()->setLogin($login);
                $this->redirect("/");
            } else {
                $this->showMsg('注册失败,请重试!');
            }
        }
        $this->setLayout("user_nologin");
        $this->title="注册页";
        $this->display("site/user/register");
    }

    function check_unique_email(){
        $this->disableLayout();
        $email = $this->getParam("email");
        if(!$email) exit("邮箱不能为空!");
        if(!check_email($email)) exit("邮箱格式不匹配!");
        $check = zl::dao("user")->get(array("email"=>$email));
        if($check){
            exit("邮箱已被注册!");
        }else{
            return true;
        }
    }

    function check_unique_nickname(){
        $this->disableLayout();
        $nickname = $this->getParam("nickname");
        if(!$nickname) exit("花名不能为空!");
        if(!site_user_service::service()->checkNickname($nickname)) exit(getError());
        $check = zl::dao("user")->get(array("nickname"=>$nickname));
        if($check){
            exit("花名已被注册!");
        }else{
            return true;
        }
    }

    function logout(){
        site_user_service::service()->logout();
        $this->redirect("/login");
    }


    function emailvalidate($token=''){
        $check = zl::dao("email_token")->get(array("token"=>$token,"zl_status"=>1,"zl_type"=>1),"ctime desc");
        if($check){
            zl::dao("user")->update(array("email_validate"=>1),array("email"=>$check['email']));
            zl::dao("email_token")->update(array("zl_status"=>0),array("email"=>$check['email']));
            $this->showMsg("激活成功!",1,0,"/");
        }else{
            $this->showMsg("无效请求");
        }
    }

    function findpwd(){
        if(isPost()){
            zl_form::form()->csrfValidate();
            $email = $this->getParam("email");
            $captcha = $this->getParam("captcha");
            if (!$email) $this->showMsg("邮箱不能为空!");
            if (!$captcha) $this->showMsg("验证码不能为空!");
            if(!check_email($email)) $this->showMsg("邮箱格式不匹配!");

            if (!site_index_service::service()->checkCaptcha($captcha, "findpwd")) $this->showMsg("验证码错误!");

            $check = zl::dao("user")->get(array("email"=>$email));
            if($check){
                site_user_service::service()->sendFindpwdMail($email);
                $this->showMsg("操作成功",1);
            }else{
                $this->showMsg("邮箱不存在!");
            }

        }
        $this->setLayout("user_nologin");
        $this->title="找回密码页";
        $this->display("site/user/findpwd");
    }

    function setpwd($token=''){
        if(isPost()) {
            zl_form::form()->csrfValidate();
            $token = $this->getParam("token");
            if (!$token)  $this->showMsg("无效请求",2,0);
            $pwd = $this->getParam("pwd");
            if (!$pwd) $this->showMsg("密码不能为空!");
            $captcha = $this->getParam("captcha");
            if (!$captcha) $this->showMsg("验证码不能为空!");
            if (!site_index_service::service()->checkCaptcha($captcha, "setpwd")) $this->showMsg("验证码错误!");

            $check = zl::dao("email_token")->get(array("token"=>$token,"zl_status"=>1,"zl_type"=>2),"ctime desc");
            if($check){
                zl::dao("email_token")->update(array("zl_status"=>0),array("email"=>$check['email']));
//                d(zl::dao("email_token")->getSql());
                zl::dao("user")->update(array("pwd"=>zl_md5($pwd)),array("email"=>$check['email']));
                $this->showMsg("操作成功",1,0,"/login");
            }else{
                $this->showMsg("无效请求",2,0);
            }
        }
        $check = zl::dao("email_token")->get(array("token"=>$token,"zl_status"=>1,"zl_type"=>2));
        if(!$check){
            $this->showMsg("无效请求",2,0);
        }
        $this->token = $token;
        $this->setLayout("user_nologin");
        $this->title="设置密码页";
        $this->display("site/user/setpwd");
    }

    function setting(){
        $this->setLayout("main");
        $this->title = "设置";
        $this->user = $this->getUser();
        if(isPost()){
            if($_FILES['upload_file']['tmp_name']){
                //上传图片
                $savePath = ROOT_PATH . "/public/images/face" . DS . date("Y") . DS . date('m') . DS . date('d'). DS;
                $imgPaths = site_index_service::service()->upImg($savePath);
                $imgPath =  "/public/images/face/" .date("Y") . "/" . date('m') . "/" . date('d'). "/" . $imgPaths[0]['savename'];
                //截图
                $oldPath = $savePath . $imgPaths[0]['savename'];
                site_index_service::service()->cutImg($oldPath, $oldPath, array("160x160","48x48","32x32", "24x24","64x64"));
                #保存到数据库
                $data = array();
                $data['face'] = $imgPath;
                zl::dao("user")->update($data,array("id"=>$this->getUid()));

                $this->redirect("setting");
            }
        }
        $this->display();
    }

    function u($id=0){
        $this->setLayout("main");
        $uid = $this->getUid();
        if($id==0 && !$uid) $this->showMsg("无效页面!");
        //个人用户中心
        $isMe = 0;
        if($id==0 || $uid==$id){
            $isMe=1;
        }else{
            $uid = $id;
        }
        $user = zl::dao("user")->get(array("id"=>$uid));
        $arc = zl::dao("arc")->gets(array('uid'=>$uid),"ctime DESC",0,10);
        $reply = zl::dao("reply")->gets(array('uid'=>$uid),"ctime DESC",0,10);
        if($reply){
            foreach($reply as $k=>$v){
                $arcTmp = zl::dao("arc")->get(array("id"=>$v['arc_id']));
                $reply[$k]['arc'] = $arcTmp;
            }
        }

        $this->isMe = $isMe;
        $this->user = $user;
        $this->arc = $arc;
        $this->reply = $reply;
        $this->title = "个人信息";
        $this->display();
    }



    function me($p=1){
        $this->setLayout("main");
        $uid = $this->getUid();
        $user = zl::dao("user")->get(array("id"=>$uid));

        $p = (int) $p;
        list($arc,$markup) = zl::dao("arc")->pager(array('uid'=>$uid,"is_publish"=>1),"ctime DESC","",$p,"/me-@p");

        $reply = zl::dao("reply")->gets(array('uid'=>$uid),"ctime DESC",0,10);
        if($reply){
            foreach($reply as $k=>$v){
                $arcTmp = zl::dao("arc")->get(array("id"=>$v['arc_id']));
                $reply[$k]['arc'] = $arcTmp;
            }
        }

        $this->user = $user;
        $this->arc = $arc;
        $this->markup = $markup;
        $this->reply = $reply;

        $this->title = "个人中心";
        $this->display();
    }

    function editpwd(){
        $this->setLayout("main");
        if(isPost()){
            zl_form::form()->csrfValidate();
            $oldpassword = $this->getParam("oldpassword");
            if (!$oldpassword) $this->showMsg("原密码不能为空!");
            $pwd = $this->getParam("pwd");
            if (!$pwd) $this->showMsg("密码不能为空!");
            $captcha = $this->getParam("captcha");
            if (!$captcha) $this->showMsg("验证码不能为空!");

            $user = zl::dao("user")->get(array("id"=>$this->getUid()));
            if(zl_md5($oldpassword) != $user['pwd'])  $this->showMsg("原密码有误!");

            if (!site_index_service::service()->checkCaptcha($captcha, "editpwd")) $this->showMsg("验证码错误!");

            zl::dao("user")->update(array("pwd"=>zl_md5($pwd)),array("id"=>$user['id']));
            $this->showMsg("操作成功!",0,0,"/editpwd");
        }
        $this->title='修改密码';
        $this->display();
    }





}