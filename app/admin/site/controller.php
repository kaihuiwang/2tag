<?php
class admin_site_controller extends zl_controller
{
    public $layout = "admin";


    function menu(){
        $orderBy = "zl_type desc";
        $where = array();
        list($list,$markup) = zl::dao("menu")->pager($where,$orderBy);
        $this->list = $list;
        $this->markup = $markup;
        $this->title = "导航管理";
        $this->display();
    }

    function menu_add(){
        if(isPost()){
            zl_form::csrfValidate();
            $title = trim($this->getParam("title"));
            $url = trim($this->getParam("zl_url"));
            $type = (int) $this->getParam("zl_type");
            $zl_sort = (int) $this->getParam("zl_sort");

            if(!$title) $this->showMsg("标题不能为空!");
            if(!$url) $this->showMsg("url不能为空!");
            if(!$type) $this->showMsg("类型不能为空!");
            $data=array();
            $data['title'] = $title;
            $data['zl_url'] = $url;
            $data['zl_type'] = $type;
            $data['zl_sort'] = $zl_sort;

            zl::dao("menu")->insert($data);
            $this->redirect("/admin/menu");
        }
        $this->title = "导航添加";
        $this->display();
    }

    function menu_edit($id=0){
        $info = zl::dao("menu")->get(array("id"=>$id));
        if(!$info) $this->showMsg("页面不存在!");

        $this->info = $info;

        if(isPost()){
            zl_form::csrfValidate();
            $id = (int) $this->getParam("id");
            $title = trim($this->getParam("title"));
            $url = trim($this->getParam("zl_url"));
            $type = (int) $this->getParam("zl_type");
            $zl_sort = (int) $this->getParam("zl_sort");

            $data=array();
            $data['title'] = $title;
            $data['zl_url'] = $url;
            $data['zl_type'] = $type;
            $data['zl_sort'] = $zl_sort;

            zl::dao("menu")->update($data,array("id"=>$id));
            $this->redirect("/admin/menu");
        }

        $this->title = "导航编辑";
        $this->display();
    }

    function menu_delete($id=0){
        $info = zl::dao("menu")->get(array("id"=>$id));
        if(!$info) $this->showMsg("页面不存在!");
        zl::dao("menu")->delete(array("id"=>$id));
        $this->redirect("/admin/menu");
    }

    function arc(){
        $orderBy = "id desc";
        $where = admin_index_service::service()->parseSearchWhere();

        if(isset($where['tag']) && $where['tag']){
            $ids = zl::dao("tag")->getField("id",array("name"=>array($where['tag'][0],$where['tag'][1])),true);
            if($ids){
                foreach($ids as $v){
                    $where['tags'] = array("like","%,".$v.",%","OR");
                }
            }else{
                $where['tags'] = "";
            }
         unset($where['tag']);
        }
        list($list,$markup) = zl::dao("arc")->pager($where,$orderBy);
        if($list){
            foreach($list as $k=>$v){
                $tagids = explode(",",trim($v['tags'],","));
                $tagname = zl::dao("tag")->getField("name",array("id"=>array("in",$tagids)),true);
                $list[$k]['tag_name'] = $tagname;
            }
        }
        $this->list = $list;
        $this->markup = $markup;
        $this->title = "主题管理";

        $search = array();
        $search['fields'][] = array("title","标题","text");
        $search['fields'][] = array("tag","标签","text");
        $search['fields'][] = array("is_publish","是否屏蔽","select",array("0"=>"是","1"=>"否"));
        $search['fields'][] = array("ctime","加入时间","date");
        $search['url'] = url("/admin/arc");

        $this->search = array("search"=>$search);

        $this->display();
    }

    /**
     * 屏蔽
     * @param int $id
     */
    function arc_delete($id=0){
        $info = zl::dao("arc")->get(array("id"=>$id));
        if(!$info) $this->showMsg("页面不存在!");
        zl::dao("arc")->update(array("is_publish"=>0),array("id"=>$id));
        $this->redirect("/admin/arc");
    }

    function user(){
        $orderBy = "id desc";
        $where = admin_index_service::service()->parseSearchWhere();

        list($list,$markup) = zl::dao("user")->pager($where,$orderBy);

        $this->list = $list;
        $this->markup = $markup;
        $this->title = "会员管理";

        $search = array();
        $search['fields'][] = array("email","邮箱","text");
        $search['fields'][] = array("nickname","花名","text");
        $search['fields'][] = array("email_validate","是否验证邮箱","select",array("1"=>"是","0"=>"否"));
        $search['fields'][] = array("ctime","加入时间","date");
        $search['url'] = url("/admin/user");

        $this->search = array("search"=>$search);

        $this->display();
    }

    function setLevel($uid){
        if(!$uid) $this->showJsonp(0,"参数错误!");
        $user = zl::dao("user")->get(array("id"=>$uid));
        $level = $user['level']==99 ? 0:99;
        zl::dao("user")->update(array("level"=>$level),array("id"=>$uid));
        $this->showJsonp();
    }
}