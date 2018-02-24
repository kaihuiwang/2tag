<?php
class admin_setting_controller extends zl_controller{
    public $layout="admin";
    function index(){
        $this->f = $this->getParam("f","app");
        $this->configs = zl::config()->get("setting");
        if(isPost()){
            zl_form::csrfValidate();
            $post = $this->getParam();
            $current = zl::config()->get("setting.struct.".$this->f);
            foreach($current as $k=>$v){
                $kTmp = $this->f."-".str_replace(".","-",$k);
                if(isset($post[$kTmp])){
                    zl::config()->set($this->f.".".$k,addslashes($post[$kTmp]));
                }
            }
            $this->showMsg("编辑成功",1);
        }
        $this->display();
    }

    function cache(){
        if(isPost()){
            zl_form::csrfValidate();
            $cache = ROOT_PATH."/data/cache";
            deldir($cache);
            $this->showMsg("操作成功",1);
        }
        $this->display();
    }
}