<?php
class admin_rbac_controller extends zl_controller{

    public $layout="admin";
    function member(){
        $where=array();
        $where = admin_index_service::service()->parseSearchWhere();

        $deptId = 0;
        if(isset($where['dept']) && $where['dept']){
            $deptId = $where['dept'];
            $deptIds = array();
            $deptIds[] = $where['dept'];
            admin_rbac_service::service()->getAllChildDepts($where['dept'],$deptIds);
            $where['dept'] = array("in",$deptIds);
        }

        $where['email'] = array("!=",zl::config()->get("app.super_admin"));
        $orderBy = "";
        list($list,$markup) = zl::dao("admin_user")->pager($where,$orderBy);
//        echo zl::dao("admin_user")->getSql();
        $this->list = $list;
        $this->markup = $markup;

        //所有部门
        $depts = admin_rbac_service::service()->getAllDepts($deptId);
        $this->depts = json_encode($depts,JSON_UNESCAPED_UNICODE);

        $role = zl::dao("role")->gets();
        $selectRole = array();
        if($role){
            foreach($role as $v){
                $selectRole[$v['id']] = $v['name'];
            }
        }

        $search = array();
        $search['fields'][] = array("email","邮箱","text");
        $search['fields'][] = array("nickname","花名","text");
        $search['fields'][] = array("real_name","真实姓名","text");
        $search['fields'][] = array("sex","性别","select",array("0"=>"保密","1"=>"男","2"=>"女"));
        $search['fields'][] = array("role","职位","select",$selectRole);
        $search['fields'][] = array("join_date","加入时间","date");
        $search['url'] = url("/admin/member");
        $search['hidden'] = array("dept");

        $this->search = array("search"=>$search);

        $this->display();
    }

    function memberAdd(){
        //所有部门
        $depts = admin_rbac_service::service()->getAllDepts();
        $this->depts = json_encode($depts,JSON_UNESCAPED_UNICODE);


        $role = zl::dao("role")->gets();
        $selectRole = array();
        if($role){
            foreach($role as $v){
                $selectRole[$v['id']] = $v['name'];
            }
        }

        if(isPost()){
            zl_form::csrfValidate();
            $email = $this->getParam("email");
            $nickname = $this->getParam("nickname");
            $realname = $this->getParam("realname");
            $pwd = $this->getParam("pwd");
            $sex = $this->getParam("sex");
            $mobile = $this->getParam("mobile");
            $tel = $this->getParam("tel");
            $qq = $this->getParam("qq");
            $skype = $this->getParam("skype");
            $gtalk = $this->getParam("gtalk");
            $role = $this->getParam("role");
            $dept = $this->getParam("dept");
            if(!$email) $this->showMsg("邮箱不能为空");
            if(!$realname) $this->showMsg("真实姓名不能为空");
            if(!$nickname) $this->showMsg("花名不能为空");
            if(!$pwd) $this->showMsg("密码不能为空");
            if(!check_email($email)) $this->showMsg("邮箱格式不匹配!");

            if(zl::dao("admin_user")->get(array("email"=>$email))) $this->showMsg("邮箱已被注册!",null,1);
            if(zl::dao("admin_user")->get(array("nickname"=>$nickname))) $this->showMsg("花名已被注册!",null,1);

            $data=array();
            $data['email'] = $email;
            $data['real_name'] = $realname;
            $data['pinyin'] = current(zl_pinyin::getPinyin($realname));
            $data['nickname'] = $nickname;
            $data['pwd'] = zl_md5($pwd);
            $data['sex'] = $sex;
            $data['role'] = $role;
            $data['dept'] = $dept;
            $data['mobile'] = $mobile;
            $data['tel'] = $tel;
            $data['qq'] = $qq;
            $data['skype'] = $skype;
            $data['gtalk'] = $gtalk;
            $data['join_date'] = date('Y-m-d');
            $rs = zl::dao("admin_user")->insert($data);
            if($rs){
                site_user_service::service()->sendRegisterMail($email);
                $this->redirect("admin/member");
            }else{
                echo zl::dao("admin_user")->getSql();exit;
                $this->showMsg("添加失败!");
            }
        }

        $this->selectRole = $selectRole;
        $this->display();
    }


    function memberEdit($id){
        $id = $id ? (int)$id : (int) $this->getParam("id");
        $userInfo = zl::dao("admin_user")->get(array("id"=>$id));
        $userInfo['dept'] =  zl::dao("dept")->get(array("id"=>$userInfo['dept']));
        $this->userInfo = $userInfo;
        $depts = admin_rbac_service::service()->getAllDepts();
        $this->depts = json_encode($depts,JSON_UNESCAPED_UNICODE);


        $role = zl::dao("role")->gets();
        $selectRole = array();
        if($role){
            foreach($role as $v){
                $selectRole[$v['id']] = $v['name'];
            }
        }
        $this->selectRole = $selectRole;


        if(isPost()){
            zl_form::csrfValidate();
            $email = $this->getParam("email");
            $nickname = $this->getParam("nickname");
            $realname = $this->getParam("realname");
            $pwd = $this->getParam("pwd");
            $sex = $this->getParam("sex");
            $mobile = $this->getParam("mobile");
            $tel = $this->getParam("tel");
            $qq = $this->getParam("qq");
            $skype = $this->getParam("skype");
            $gtalk = $this->getParam("gtalk");
            $role = $this->getParam("role");
            $dept = $this->getParam("dept");
            if(!$email) $this->showMsg("邮箱不能为空");
            if(!$realname) $this->showMsg("真实姓名不能为空");
            if(!$nickname) $this->showMsg("花名不能为空");
            if(!check_email($email)) $this->showMsg("邮箱格式不匹配!");

            if($email !=$userInfo['email']){
                if(zl::dao("admin_user")->get(array("email"=>$email))) $this->showMsg("邮箱已被注册!",null,1);
            }
            if($nickname !=$userInfo['nickname']) {
                if (zl::dao("admin_user")->get(array("nickname" => $nickname))) $this->showMsg("花名已被注册!", null, 1);
            }

            $data=array();
            $data['email'] = $email;
            $data['real_name'] = $realname;
            $data['nickname'] = $nickname;
            $data['pinyin'] = current(zl_pinyin::getPinyin($realname));
            if($pwd) $data['pwd'] = zl_md5($pwd);
            $data['sex'] = $sex;
            $data['role'] = $role;
            if($dept) $data['dept'] = $dept;
            $data['mobile'] = $mobile;
            $data['tel'] = $tel;
            $data['qq'] = $qq;
            $data['skype'] = $skype;
            $data['gtalk'] = $gtalk;
            $rs = zl::dao("admin_user")->update($data,array("id"=>$id));
            if($rs){
                $this->redirect("admin/member");
            }else{
                $this->showMsg("编辑失败!");
            }
        }

        $this->display();
    }

    function memberDelete($id){
        $id = $id ? (int)$id : (int) $this->getParam("id");
        $userInfo = zl::dao("admin_user")->get(array("id"=>$id));
        if(!$userInfo) $this->showMsg("用户不存在!");
        zl::dao("admin_user")->delete(array("id"=>$id));
        $this->redirect("admin/member");
    }

    function node(){
        $this->nodes = admin_rbac_service::service()->getNodes();

        $this->display();
    }

    function dept($p=1){
        $where = admin_index_service::service()->parseSearchWhere();

        $deptId = 0;
        if(isset($where['pid']) && $where['pid']){
            $deptId = $where['pid'];
            $deptIds = array();
            $deptIds[] = $where['pid'];
            $where['pid'] = array("in",$deptIds);
        }

        $orderBy = "id asc";
        list($list,$markup) = zl::dao("dept")->pager($where,$orderBy,"",$p);
//        echo zl::dao("admin_user")->getSql();
        $this->list = $list;
        $this->markup = $markup;

        //所有部门
        $depts = admin_rbac_service::service()->getAllDepts($deptId);
        $this->depts = json_encode($depts,JSON_UNESCAPED_UNICODE);


        $search = array();
        $search['fields'][] = array("name","部门名称","text");
        $search['url'] = url("/admin/dept");
        $search['hidden'] = array("pid");

        $this->pid = $deptId;
        $this->search = array("search"=>$search);

        $this->display();
    }

    function deptAdd($pid=0){
       $pid = $pid?$pid:$this->getParam("pid");
        $pid = (int) $pid;
        $this->levelDept = zl::dao("dept")->get(array("id"=>$pid));

        $depts = admin_rbac_service::service()->getAllDepts(-1);
        $this->depts = json_encode($depts,JSON_UNESCAPED_UNICODE);

        if(isPost()){
            zl_form::csrfValidate();
            $name = $this->getParam("name");
            if(!$name) $this->showMsg("部门名称不能为空!");
            if(zl::dao("dept")->get(array("name"=>$name))) $this->showMsg("部门名称已经被占用!");
            $data=array();
            $data['name'] = $name;
            $data['pid'] = $pid;
            $rs = zl::dao("dept")->insert($data);
            if($rs){
                $this->redirect("/admin/dept");
            }else{
                $this->showMsg("添加失败!");
            }
        }

        $this->display();
    }


    function deptEdit($id=0){
        $id = $id?$id:$this->getParam("id");
        $id = (int) $id;
        $this->deptInfo = zl::dao("dept")->get(array("id"=>$id));
        if(!$this->deptInfo) $this->showMsg("部门不存在!");

        $this->levelDept = zl::dao("dept")->get(array("id"=>$this->deptInfo['pid']));

        $depts = admin_rbac_service::service()->getAllDepts(-1);
        $this->depts = json_encode($depts,JSON_UNESCAPED_UNICODE);

        if(isPost()){
            zl_form::csrfValidate();
            $name = $this->getParam("name");
            $pid = (int) $this->getParam("pid");
            if(!$name) $this->showMsg("部门名称不能为空!");
            if($name !=$this->deptInfo['name']){
                if(zl::dao("dept")->get(array("name"=>$name))) $this->showMsg("部门名称已经被占用!");
            }
            if($pid == $id)  $this->showMsg("错误!自己不能是自己部门的上级部门!");
            $data=array();
            $data['name'] = $name;
            $data['pid'] = $pid;
            $rs = zl::dao("dept")->update($data,array("id"=>$id));
            if($rs){
                $this->redirect("/admin/dept");
            }else{
                $this->showMsg("编辑失败!");
            }
        }

        $this->display();
    }


    function deptDelete($id){
        $id = $id ? (int)$id : (int) $this->getParam("id");
        $deptInfo = zl::dao("dept")->get(array("id"=>$id));
        if(!$deptInfo) $this->showMsg("部门不存在!");
        zl::dao("dept")->delete(array("id"=>$id));
        $this->redirect("/admin/dept");
    }

    function access(){
        $orderBy = "id asc";
        $where = admin_index_service::service()->parseSearchWhere();
        list($list,$markup) = zl::dao("role")->pager($where,$orderBy);
//        echo zl::dao("admin_user")->getSql();
        $this->list = $list;
        $this->markup = $markup;

        $search = array();
        $search['fields'][] = array("name","职位名称","text");
        $search['url'] = url("/admin/access");

        $this->search = array("search"=>$search);

        $this->display();
    }

    function accessGet($id){
        $this->disableLayout();
        $id= $id?$id:$this->getParam("id");
        $id = (int) $id;
        $this->nodes = admin_rbac_service::service()->getNodesByRole($id);
        $this->display();
    }

    function accessAdd(){
        $this->nodes = admin_rbac_service::service()->getNodes();
        if(isPost()){
            zl_form::csrfValidate();
            $name = trim($this->getParam("name"));
            $access = $this->getParam("access");

            if(!$name) $this->showMsg("职位名称不能为空!");
            if(!$access)  $this->showMsg("权限不能为空!");

            $data=array();
            $data['name'] = $name;
            $roleId = zl::dao("role")->insert($data);
            if(!$roleId) $this->showMsg("添加失败!请重试");
            foreach($access as $v){
                $data=array();
                $data['role_id'] = $roleId;
                $data['node'] = $v;
                zl::dao("access")->insert($data);
            }
            $this->redirect("/admin/access");
        }
        $this->display();
    }

    function accessEdit($id=0){
        $id= $id?$id:$this->getParam("id");
        $id = (int) $id;
        $this->id = $id;
        $roleInfo = zl::dao("role")->get(array("id"=>$id));
        if(!$roleInfo)  $this->showMsg("职位不存在!");
        $this->nodes = admin_rbac_service::service()->getNodes();
        $this->roleInfo = $roleInfo;
        $this->access = zl::dao("access")->getField("node",array("role_id"=>$id),true);

        if(isPost()) {
            zl_form::csrfValidate();
            $name = trim($this->getParam("name"));
            $access = $this->getParam("access");

            if(!$name) $this->showMsg("职位名称不能为空!");
            if(!$access)  $this->showMsg("权限不能为空!");

            if($name != $roleInfo['name']){
                if(zl::dao("role")->get(array("name"=>$name))) $this->showMsg("职位名称已被占用!");
            }

            $data=array();
            $data['name'] = $name;
            zl::dao("role")->update($data,array("id"=>$id));
            zl::dao("access")->delete(array("role_id"=>$id));
            foreach($access as $v){
                $data=array();
                $data['role_id'] = $id;
                $data['node'] = $v;
                zl::dao("access")->insert($data);
            }
            $this->redirect("/admin/access");
        }

        $this->display();
    }

    function accessDelete($id){
        $id = $id ? (int)$id : (int) $this->getParam("id");
        $deptInfo = zl::dao("role")->get(array("id"=>$id));
        if(!$deptInfo) $this->showMsg("职位不存在!");
        zl::dao("role")->delete(array("id"=>$id));
        $this->redirect("/admin/access");
    }

}