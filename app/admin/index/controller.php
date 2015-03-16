<?php

class admin_index_controller extends zl_controller
{
    public $layout="admin";
    function index()
    {
        $this->title = "欢迎";
        $this->display("admin/index/index");
    }

    function notice(){

        $where = array();
        $orderBy = " ctime DESC";
        $p = (int) $this->getParam("p");

        list($list,$markup) = zl::dao("notice")->pager($where,$orderBy,"",$p);

        $this->list = $list;
        $this->markup = $markup;

        $this->title = "公告管理";
        $this->display();
    }

    function notice_add(){

        if(isPost()){
            $title = trim($this->getParam("title"));
            $content = trim($this->getParam("content"));
            $zlType = (int) $this->getParam("zl_type");
            $show_time = $this->getParam("show_time");

            if(!$title) $this->showMsg("标题为空!");
            if(!$content) $this->showMsg("内容为空!");

            $data=array();
            $data['title'] = $title;
            $data['content'] = $content;
            $data['zl_type'] = $zlType;
            $data['show_time'] = $show_time;

            zl::dao("notice")->insert($data);
            $this->redirect("/admin/notice");
        }

        $this->title = "添加公告";
        $this->display();
    }


    function notice_edit($id=0){
        $info = zl::dao("notice")->get(array("id"=>$id));
        if(!$info) $this->showMsg("页面不存在!");
        $this->info = $info;
        $this->title = "编辑公告";
        if(isPost()){
            $id = (int) $this->getParam("id");
            $title = trim($this->getParam("title"));
            $content = trim($this->getParam("content"));
            $zlType = (int) $this->getParam("zl_type");
            $show_time = $this->getParam("show_time");

            if(!$title) $this->showMsg("标题为空!");
            if(!$content) $this->showMsg("内容为空!");

            $data=array();
            $data['title'] = $title;
            $data['content'] = $content;
            $data['zl_type'] = $zlType;
            $data['show_time'] = $show_time;
            zl::dao("notice")->update($data,array("id"=>$id));
            $this->redirect("/admin/notice");
        }
        $this->display();
    }

    function notice_delete($id=0){
        $info = zl::dao("notice")->get(array("id"=>$id));
        if(!$info) $this->showMsg("页面不存在!");
        zl::dao("notice")->delete(array("id"=>$id));
        $this->redirect("/admin/notice");
    }


}