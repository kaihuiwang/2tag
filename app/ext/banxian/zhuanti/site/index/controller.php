<?php

class ext_banxian_zhuanti_site_index_controller extends zl_ext_controller
{

    public $layout = "main";

    function index($p=1,$z="")
    {
        $uid = $this->getUid();
        $list = array();
        $markup = "";
        $tags = array();
        $zhuanti = array();
        $isAll=1;
        if($uid){
            $zhuantiArr =  zl::dao("ext_zhuanti")->gets(array("uid"=>$uid));
            if($zhuantiArr){
                foreach($zhuantiArr as $v){
                    $zhuanti_tags = zl::dao("ext_zhuanti_ext")->gets(array("zhuanti_id"=>$v['id']),true);
                    $zhuanti[$v['name']] = $zhuanti_tags;
                }
            }

            $p = (int) $p;
            $orderBy = "ORDER BY zl_score DESC,id DESC";
            $zArr = isset($zhuanti[$z])?$zhuanti[$z]:"";

            $whereStr = "";
            if($zArr!==""){
                $isAll=0;
                foreach($zArr as $v){
                    $tagId = $v['tag_id'];
                    if($tagId){
                        $whereStr .= " OR tags LIKE '%,".$tagId.",%'";
                        $tagInfo = zl::dao("tag")->get(array("id"=>$tagId));
                        $tagInfo['zhuanti_tag_id'] = $v['id'];
                        $tags[$tagId] = $tagInfo;
                    }
                }
                $whereStr = trim($whereStr);
                $whereStr = trim($whereStr,"OR");
                $whereStr = $whereStr ? " AND (".$whereStr.")" : "";

                if($whereStr){
                    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ".zl::$configApp['db']['prefix']."arc WHERE is_publish =1 ".$whereStr." ".$orderBy;

                    list($list,$markup) = zl::dao("arc")->pagerSql($sql,$p,"/zhuanti-@p-".$z);
                }else{
                    $list=array();
                    $markup="";
                }
            }else{
                foreach($zhuanti as $zArr){
                    foreach($zArr as $v){
                        $tagId = $v['tag_id'];
                        if($tagId){
                            $whereStr .= " OR tags LIKE '%,".$tagId.",%'";
                            $tagInfo = zl::dao("tag")->get(array("id"=>$tagId));
                            $tagInfo['zhuanti_tag_id'] = $v['id'];
                            $tags[$tagId] = $tagInfo;
                        }
                    }
                }

                $whereStr = trim($whereStr);
                $whereStr = trim($whereStr,"OR");
                $whereStr = $whereStr ? " AND (".$whereStr.")" : "";
                if($whereStr){
                    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ".zl::$configApp['db']['prefix']."arc WHERE is_publish =1 ".$whereStr."".$orderBy;

                    list($list,$markup) = zl::dao("arc")->pagerSql($sql,$p,"/zhuanti-@p");
                }else{
                    $list=array();
                    $markup="";
                }
            }
    //        pp(zl::dao("arc")->getSql());
            if($list){
                foreach($list as $k=>$v){
                    $user = zl::dao("user")->get(array("id"=>$v['uid']));
                    $list[$k]['user'] = $user;
                    $last_reply_user = array();
                    if($v['last_reply_uid']){
                        $last_reply_user = zl::dao("user")->get(array("id"=>$v['last_reply_uid']));
                    }
                    $list[$k]['last_reply_user'] = $last_reply_user;
                    $tagsTmp = zl::dao("tag")->gets(array("id"=>array("in",explode(",",trim($v['tags'],',')))));
                    $list[$k]['tags'] = $tagsTmp;
                }
            }
        }

        $this->zhuanti = $zhuanti;
        $this->list = $list;
        $this->markup = $markup;
        $this->isAll = $isAll;
        $this->z = $z;
        $this->tags = $tags;

        $this->title="专题";
        $this->display();
    }

    function add_zhuanti(){
        $this->disableLayout();
        if(isPost()){
            zl_form::csrfValidate();
            $name = trim($this->getParam("name"));
            if(!$name) $this->showMsg("专题名称不能为空!");
            $check = zl::dao("ext_zhuanti")->get(array("uid"=>$this->getUid(),"name"=>$name));
            if($check){
                $this->showMsg("专题名称已存在!");
            }else{
               $data=array();
                $data['uid'] = $this->getUid();
                $data['name'] = $name;
                zl::dao("ext_zhuanti")->insert($data);
                $this->redirect("/zhuanti");
            }
        }
        $this->display();
    }

    function checkzhuanti(){
        $this->disableLayout();
        $name = trim($this->getParam("name"));
        if(!$name) exit("专题名称不能为空!");
        $check = zl::dao("ext_zhuanti")->get(array("uid"=>$this->getUid(),"name"=>$name));
        if($check){
            exit("专题名称已存在!");
        }else{
            return true;
        }
    }

    function add_zhuanti_tag($id=0){
        $this->disableLayout();
        $zhuantiArr =  zl::dao("ext_zhuanti")->gets(array("uid"=>$this->getUid()));
        $this->zhuanti = $zhuantiArr;
        $this->id = $id;
        if(isPost()){
            zl_form::csrfValidate();
            $zhuanti_id = (int) $this->getParam("zhuanti_id");
            $check = zl::dao("ext_zhuanti_ext")->get(array("zhuanti_id"=>$zhuanti_id,"tag_id"=>$id));
            if(!$check){
                $data=array();
                $data['zhuanti_id'] = $zhuanti_id;
                $data['uid'] = $this->getUid();
                $data['tag_id'] = $id;
                zl::dao("ext_zhuanti_ext")->insert($data);
            }
            $info = zl::dao("ext_zhuanti")->get(array("id"=>$zhuanti_id));
            $this->redirect("/zhuanti-1-".$info['name']);
        }
        $this->display();
    }

    function tagdelete($str=0){
        $strArr = explode('-',$str);
        $id= (int) $strArr[1];
        $isAll = (int) $strArr[0];
        $check = zl::dao("ext_zhuanti_ext")->get(array("id"=>$id));
        if(!$check) $this->showMsg("参数错误");
        if(!$isAll){
            zl::dao("ext_zhuanti_ext")->delete(array("id"=>$id));
        }else{
            zl::dao("ext_zhuanti_ext")->delete(array("tag_id"=>$check['tag_id'],"uid"=>$this->getUid()));
        }
        $this->redirect("/zhuanti");
    }

}