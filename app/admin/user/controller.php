<?php

class admin_user_controller extends zl_controller
{
    public $layout="admin_user_nologin";
    function login()
    {
        if (isPost()) {
            zl_form::form()->csrfValidate();
            $captcha = $this->getParam('captcha');
            $username = $this->getParam('email');
            $pwd = $this->getParam('pwd');

            if (!$username) {
                $this->showMsg("邮箱不能为空");
            }

            if (!$pwd) {
                $this->showMsg("密码不能为空");
            }

            if (!$captcha) {
                $this->showMsg("验证码不能为空");
            }
            //检查验证码
            if (!site_index_service::service()->checkCaptcha($captcha, "admin_login")) {
                $this->showMsg("验证码不匹配");
            }

            $check = admin_user_service::service()->checklogin($username, $pwd);
            if (!$check) {
                $this->showMsg("邮箱或密码错误!");
            } else {
                admin_user_service::service()->setLogin($check);
                $this->redirect("/admin");
            }
        }
        $this->title=" 登陆后台页";
        $this->display("admin/user/login");
    }

    function logout()
    {
        $this->disableLayout();
        admin_user_service::service()->logout();
        $this->redirect("/admin/login");
    }


    function tagssearch(){
        $term = $this->getParam("term");
        $term = strtoupper(urldecode($term));
        if(!$term){ echo $this->json(array());exit;};
        if(preg_match('/[a-zA-Z]/',$term)){
            $where['pinyin'] = array("like",$term."%","or");
        }else{
            $where['real_name'] = array("like","%".$term."%","or");
            $where['nickname'] = array("like","%".$term."%","or");
        }
        $list = zl::dao("admin_user")->gets($where," pinyin DESC",0,8);
        if(!$list){ echo $this->json(array());exit;};
        $rs = array();
        foreach ($list as $v){
            $rs[] = $v['real_name']."[".$v['nickname']."]";
        }
        echo $this->json($rs);exit;
    }
}