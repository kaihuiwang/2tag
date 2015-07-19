<?php
class ext_banxian_news_index_controller extends zl_ext_controller
{
    function index($p=1,$type=0)
    {
        $this->setLayout("main");
        $where = array();
        if(!$type){
            $orderBy = "zl_score DESC,id DESC";
        }else{
            $orderBy = "id DESC";
        }
        $p = (int) $p;
        $p = $p<0?1:$p;
        $where['is_publish'] = 1;
        $str = $type=='1'?"-".$type:"";
        list($list,$markup) = zl::dao("ext_news_list")->pager($where,$orderBy,"",$p,"/news/@p".$str);
        if($list){
            foreach($list as $k=>$v){
                $num = zl::$configApp['page_size']*($p-1)+$k;
                $list[$k]['num'] = $num+1;
                $list[$k]['domain'] = ext_banxian_news_index_service::service()->getUrldomain($v['url']);
                $user = zl::dao("user")->get(array("id"=>$v['uid']));
                $list[$k]['user'] = $user;
                $last_reply_user = array();
                if($v['last_reply_uid']){
                    $last_reply_user = zl::dao("user")->get(array("id"=>$v['last_reply_uid']));
                }
                $list[$k]['last_reply_user'] = $last_reply_user;
            }
        }
        $this->list = $list;
        $this->markup = $markup;
        $this->title='掘金';
        $this->type = $type;
        $this->display();
    }

    function add(){
        $this->title='提交';
        $this->display();
    }

    function doadd(){
            $url = trim($this->getParam("url"));
            if(!$url) $this->showMsg("网址不能为空");
            if(mb_strlen($url,'utf-8')>350) $this->showMsg("网址不能大于350字");
            $ref = getRef(url("/news"));
            try{
                $html = curlGetContents($url,20);
                if(!$html) $this->showMsg("网址不正确，无法访问");
                $domainUrl = ext_banxian_news_index_service::service()->getUrldomain($url);
                list($title,$content) = zl_readability::parse($html,"http://".$domainUrl);
                if(!$title||!$content) $this->showMsg("网址不正确，无法访问或者网页内容无法分析");
                $check = zl::dao("ext_news_list")->get(array("url"=>$url));
                if($check) $this->showMsg("网址已存在!");
                $uid  = $this->getUid();
                $arcData=array();
                $arcData['title'] = $title;
                $arcData['url'] = $url;
                $arcData['uid'] = $uid;
                $arcData['content'] = $content;
                $arc_id = zl::dao("ext_news_list")->insert($arcData);
                $check = zl::dao("data")->get(array("zl_key"=>"news_count"));
                if($check){
                    zl::dao("data")->inCrease("zl_value",array("zl_key"=>"news_count"));
                }else{
                    zl::dao("data")->insert(array("zl_key"=>"news_count","zl_value"=>1));
                }
                ext_banxian_news_index_service::service()->updateScore($arc_id);
                $this->redirectAll($ref);
            }catch (Exception $e){
                $this->showMsg($e->getMessage());
            }
    }

    function dogood($arcId=0){
        $this->disableLayout();
        $ref = getRef(url("/news"));
        $arcId = intval($arcId);
        if(!$arcId) $this->showMsg("参数错误");
        $check = zl::dao("ext_news_digg")->get(array("uid"=>$this->getUid(),"arc_id"=>$arcId));
        if($check)  $this->redirectAll($ref);
        zl::dao("ext_news_list")->inCrease("good_number",array("id"=>$arcId));
        $data=array();
        $data['zl_type'] = 1;
        $data['uid'] = $this->getUid();
        $data['arc_id'] = $arcId;
        zl::dao("ext_news_digg")->insert($data);
        ext_banxian_news_index_service::service()->updateScore($arcId);
        $this->redirectAll($ref);
    }

    function v($p=1,$id=0){
        $this->setLayout("main");
        $id = intval($id);

        $this->loginuser = $this->getUser();
        $arc = zl::dao("ext_news_list")->get(array("id"=>$id));
        if($this->loginuser['level']!=99 && (!$arc['is_publish'])){
            if(!$arc) $this->showMsg("News不存在!",0,0,"/news");
        }

        $user = zl::dao("user")->get(array("id"=>$arc['uid']));

        $content = ext_banxian_news_index_service::service()->getContent($id);
        $arc['content'] = $content;
        $this->arc = $arc;
        $this->user = $user;
        $this->title = $arc['title'];
        zl::dao("ext_news_list")->inCrease("view_count",array("id"=>$id));
        ext_banxian_news_index_service::service()->updateScore($id);
        //上下一篇
        $this->prearc = zl::dao("ext_news_list")->get(array("zl_score"=>array(">",$arc['zl_score']),"id"=>array("!=",$id),"is_publish"=>1),"zl_score asc,id asc");

        $this->nextarc = zl::dao("ext_news_list")->get(array("zl_score"=>array("<",$arc['zl_score']),"id"=>array("!=",$id),"is_publish"=>1),"zl_score desc,id desc");

        $this->id = $id;

        $p = (int) $p;
        $where=array();
        $where['arc_id'] = $id;
        $where['is_publish'] = 1;
        $where['zl_type'] =1;
        $orderBy = "ctime ASC";
        list($list,$markup) = zl::dao("reply")->pager($where,$orderBy,"",$p,"/v-@p-".$id);

        if($list){
            foreach($list as $k=>$v){
                $user = zl::dao("user")->get(array("id"=>$v['uid']));
                $list[$k]['user'] = $user;
            }
        }
        $this->list = $list;
        $this->markup = $markup;
        $this->display();
    }

    function my_news($p=1){
        $this->setLayout("main");
        $where = array();
        $orderBy = "id DESC";
        $p = (int) $p;
        $p = $p<0?1:$p;
        list($list,$markup) = zl::dao("ext_news_list")->pager($where,$orderBy,"",$p,"/my_news-@p");
        $this->list = $list;
        $this->markup = $markup;
        $this->title='掘金';
        $this->display();
    }

    function delete_news($id=0){
        if(!$id) $this->showMsgBack("参数错误");
        $id = (int) $id;
        zl::dao("ext_news_list")->delete(array("id"=>$id));
        $this->redirectBack();
    }

}