<?php
class ext_banxian_qqlogin_site_controller extends zl_ext_controller
{
    const QQ_USER_INFO_KEY="QQ_USER_INFO_KEY";
    public $layout="main";
    function qqlogin(){
        $code = $this->getParam("code");
        if(!$code){
            ext_banxian_qqlogin_site_service::service()->qqAccess();
        }
        $result= ext_banxian_qqlogin_site_service::service()->getQQAccessToken($code);
        if(!isset($result['access_token']))  ext_banxian_qqlogin_site_service::service()->qqAccess();

        $accessToken  = $result['access_token'];
        $userInfo = ext_banxian_qqlogin_site_service::service()->getQQInfo($accessToken);
        if(!$userInfo)  $this->showMsg("登录失败!");
        $openid = $userInfo['openid'];
        $findwhere['obj_id'] = $openid;
        $openinfo = zl::dao("ext_qqlogin")->get($findwhere);
        if($openinfo){
            $user = zl::dao("user")->get(array("id"=>$openinfo['uid']));
            if(!$user) $this->showMsg("登录失败!");
            site_user_service::service()->setLogin($user);
            $this->redirect("/");
        }
        //判断昵称是否存在
        zl_session::set(self::QQ_USER_INFO_KEY,$userInfo);
        $this->userInfo = $userInfo;
        $this->setLayout("user_nologin");
        $this->title='QQ联合登录';
        $this->display();
    }

    function qqregister(){
        if (isPost()) {
            $qqinfo = zl_session::get(self::QQ_USER_INFO_KEY);
            if(!$qqinfo) ext_banxian_qqlogin_site_service::service()->qqAccess();

            $email = $this->getParam("email");
            $pwd = $this->getParam("pwd");
            $repwd = $this->getParam("repwd");
            $nickname = $this->getParam("nickname");

            if (!$email) $this->showMsg("邮箱不能为空!");
            if (!$pwd) $this->showMsg("密码不能为空!");
            if (!$nickname) $this->showMsg("花名不能为空!");
            if(!check_email($email)) $this->showMsg("邮箱格式不匹配!");
            if($pwd!=$repwd){
                $this->showMsg("重复密码不匹配!");
            }

            if(zl::dao("admin_user")->get(array("email"=>$email))) $this->showMsg("邮箱已被注册!",null,1);
            if(zl::dao("admin_user")->get(array("nickname"=>$nickname))) $this->showMsg("花名已被注册!",null,1);

            $login = site_user_service::service()->register($email, $pwd,$nickname);
            if ($login) {
                #更新头像
                $imgfile = @file_get_contents($qqinfo['figureurl_2']);
                $savePath = ROOT_PATH . DS . "public/images" . DS . "face" . DS . date("Y") . DS . date('m') . DS . date('d'). DS;
                if(!is_dir($savePath)) mkdir($savePath, 0777, true);
                $imgPath = $savePath.$qqinfo['openid'].".jpg";
                @file_put_contents($imgPath, $imgfile);
                //截图
                site_index_service::service()->cutImg($imgPath, $imgPath, array("160x160","48x48","32x32", "24x24","64x64"));
                #保存到数据库
                $qqdata=array();
                $data['face'] = $imgPath;
                zl::dao("user")->update($qqdata,array("id"=>$login['id']));

                $qqdata=array();
                $qqdata['obj_id'] =$qqinfo['openid'];
                $qqdata['uid'] = $login['id'];
                zl::dao("ext_qqlogin")->insert($qqdata);

                site_user_service::service()->sendRegisterMail($email);
                site_user_service::service()->setLogin($login);
                $this->redirect("/");
            } else {
                $this->showMsg('注册失败,请重试!');
            }
        }
    }

    function qqlogin_bind(){
        $code = $this->getParam("code");
        $callbackUrl = "qqlogin_bind";
        if(!$code){
            ext_banxian_qqlogin_site_service::service()->qqAccess($callbackUrl);
        }
        $result= ext_banxian_qqlogin_site_service::service()->getQQAccessToken($code,$callbackUrl);
        if(!isset($result['access_token']))  ext_banxian_qqlogin_site_service::service()->qqAccess($callbackUrl);

        $accessToken  = $result['access_token'];
        $userInfo = ext_banxian_qqlogin_site_service::service()->getQQInfo($accessToken,$callbackUrl);
        if(!$userInfo) $this->showMsg("绑定失败!");
        $openid = $userInfo['openid'];
        $findwhere=array();
        $findwhere['obj_id'] = $openid;
        $openinfo = zl::dao("ext_qqlogin")->get($findwhere);
        if($openinfo) $this->showMsg("该账户已经绑定了其他账户,请将先解绑，然后重试~",0,0,"/bind");
        $uid =$this->getUid();
        $qqdata=array();
        $qqdata['obj_id'] = $openid;
        $qqdata['uid'] = $uid;

        zl::dao("ext_qqlogin")->insert($qqdata);

        $this->redirect("/bind");
    }

    function qqlogin_unbind(){
        $uid = $this->getUid();
        zl::dao("ext_qqlogin")->delete(array("uid"=>$uid));
        $this->redirect("/bind");
    }

    function bind(){
        $this->title='QQ绑定';
        $uid = $this->getUid();
        $this->isBind = zl::dao("ext_qqlogin")->get(array("uid"=>$uid));
        $this->display();
    }
}