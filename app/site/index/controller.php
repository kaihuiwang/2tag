<?php
class site_index_controller extends zl_controller
{

    function index($p=1,$tag='')
    {
        $this->setLayout("main");
        $this->title="首页";
        $this->hotTag = zl::dao("tag")->gets(array("is_publish"=>1)," zl_count DESC ",0,20);
        $where = array();
        $orderBy = "zl_score DESC,id DESC";
        if($tag){
            $tagId = zl::dao("tag")->getField("id",array("name"=>$tag));
            if($tagId) $where['tags'] = array("like","%,".$tagId.",%");
        }
        $tagStr = $tag?"-".$tag:"";
        $p = (int) $p;
        $where['is_publish'] = 1;
        list($list,$markup) = zl::dao("arc")->pager($where,$orderBy,"",$p,"/@p".$tagStr);
//        print_r(zl::dao("arc")->getSql());
        if($list){
            foreach($list as $k=>$v){
                $user = zl::dao("user")->get(array("id"=>$v['uid']));
                $list[$k]['user'] = $user;
                $last_reply_user = array();
                if($v['last_reply_uid']){
                    $last_reply_user = zl::dao("user")->get(array("id"=>$v['last_reply_uid']));
                }
                $list[$k]['last_reply_user'] = $last_reply_user;
                $tags = zl::dao("tag")->gets(array("id"=>array("in",explode(",",trim($v['tags'],',')))));
                $list[$k]['tags'] = $tags;
            }
        }
        $this->list = $list;
        $this->markup = $markup;
        $this->tag = $tag;
        $this->user = site_user_service::service()->getLogin();

        //最多标签
        $this->most = zl::dao("tag")->gets(array("is_publish"=>1),"zl_score DESC",0,40);

        //今天按时间最多主题
        $this->todayarc = zl::dao("arc")->gets(array("ctime"=>array(">=",date('Y-m-d 00:00:00'))),"ctime DESC",0,15);
        //公告
        $this->sitenotice = zl::dao("notice")->gets(array("show_time"=>array("<",date('Y-m-d H:i:s')),"zl_type"=>1),"ctime DESC",0,3);
        //社区运行状态
        $sitedataTmp = zl::dao("data")->gets();
        $sitedata = array();
        if($sitedataTmp){
            foreach($sitedataTmp as $v){
                $sitedata[$v['zl_key']] = $v['zl_value'];
            }
        }
        $this->sitedata =$sitedata;
//        site_index_service::service()->updateArcScore(18);

        $this->display("site/index/index");
    }

    function stag(){
        $tag = trim($this->getParam("tag"));
        if(!$tag) $this->redirect("/");
        $info = zl::dao("tag")->get(array("name"=>$tag));
        if(!$info) $this->showMsg("标签不存在!");
        $this->redirect("/t-1-".$tag);
    }

    function captcha($salt='')
    {
        $this->disableLayout();
        if(!$salt){
            $salt = zl::get('current_route_name');
        }
        ob_end_clean();
        header('Pragma: no-cache');
        header("content-type: image/JPEG");
        zl_verify::$useNoise = false;
        zl_verify::$useCurve = false;
        zl_verify::$imageH=30;
        zl_verify::$imageL=130;
        zl_verify::$fontSize=14;
        zl_verify::entry();
        zl_session::set($salt."phrase", zl_verify::$code);
    }

    function checkcaptcha($salt)
    {
        $this->disableLayout();
        $captcha = $this->getParam("captcha");
        if (!site_index_service::service()->checkCaptcha($captcha, $salt)) {
            exit('验证码错误!');
        }
       return true;
    }


    function add(){
        $this->setLayout("main");
        if(isPost()){
            zl_form::csrfValidate();
            $title = trim($this->getParam("title"));
            $content = trim($this->getParam("content"));
            $tags = strip_tags(trim($this->getParam("tags")));
            if(!$title) $this->showMsg("标题不能为空");
            if(mb_strlen($title,'utf-8')>120) $this->showMsg("标题不能大于120字");
            if(!$content) $this->showMsg("正文不能为空");
            if(mb_strlen($content,'utf-8')>20000) $this->showMsg("正文不能大于20000字");
            if(!$tags) $this->showMsg("标签不能为空");
            $tagsArr = explode(",",$tags);
            if(count($tagsArr)>5) $this->showMsg("最多5个标签");
            if($tagsArr){
                foreach($tagsArr as $tagc){
                    if(mb_strlen($tagc,'utf-8')>20) $this->showMsg("标签长度每个不能大于20字");
                }
            }
            try{
                $userInfo = site_user_service::service()->isLogin();
                $uid = $userInfo['id'];
                $arcData=array();
                $arcData['title'] = $title;
                $arcData['content'] = $content;
                $arcData['view_count'] = 1;
                $arcData['zl_type'] = 1;
                $arcData['uid'] = $uid;
                $arcData['is_publish']=1;
                $arc_id = zl::dao("arc")->insert($arcData);
                $check = zl::dao("data")->get(array("zl_key"=>"arc_count"));
                if($check){
                    zl::dao("data")->inCrease("zl_value",array("zl_key"=>"arc_count"));
                }else{
                    zl::dao("data")->insert(array("zl_key"=>"arc_count","zl_value"=>1));
                }
                site_index_service::service()->updateArcScore($arc_id);
                $tags=",";
                foreach($tagsArr as $v){
                    $check = zl::dao("tag")->get(array("name"=>$v));
                    if($check){
                        zl::dao("tag")->inCrease("zl_count",array("id"=>$check['id']));
                        $tag_id = $check['id'];
                    }else{
                        $data=array();
                        $data['name'] = $v;
                        $data['pinyin'] = current(zl_pinyin::getPinyin($v));
                        $data['zl_count'] = 1;
                        $data['uid'] = $this->getUid();
                        $tag_id = zl::dao("tag")->insert($data);
                        $check = zl::dao("data")->get(array("zl_key"=>"tag_count"));
                        if($check){
                            zl::dao("data")->inCrease("zl_value",array("zl_key"=>"tag_count"));
                        }else{
                            zl::dao("data")->insert(array("zl_key"=>"tag_count","zl_value"=>1));
                        }
                    }
                    $tags .=$tag_id.",";
                    site_index_service::service()->updateTagScore($tag_id);
                }
                zl::dao("arc")->update(array("tags"=>$tags),array("id"=>$arc_id));
                //tag_ext
                site_index_service::service()->addArcTagExt($title,$content,$arc_id,8);
            }catch (Exception $e){
                $this->showMsg($e->getMessage());
            }
            zl_hook::run("arc_add",array("id"=>$arc_id));
            $this->redirect("/v-1-".$arc_id);

        }
        $this->title="添加主题";
        $this->display("site/index/add");
    }

    function parsemarkdown(){
        $content = $this->getParam("content");
        if(!$content) $this->showJsonp(0,"正文内容为空!");
        $html = zl_markdown::markdown()->text($content);
        $this->showJsonp(1,$html);
    }

    function indextagsearch(){
        $term = $this->getParam("term");
        $term = strtolower(urldecode($term));
        if(!$term){ echo $this->json(array());exit;};
        $where = array();
        if(preg_match('/[a-zA-Z]/',$term)){
            $where['pinyin'] = array("like",$term."%","or");
        }else{
            $where['name'] = array("like","%".$term."%","or");
        }
        $list = zl::dao("tag")->gets($where," zl_count DESC",0,8);

        if(!$list){ echo $this->json(array());exit;};
        $rs = array();
        foreach ($list as $v){
            $tmp = array("label"=>$v['name'],"value"=>$v['name']);
            $rs[] =$tmp ;
        }
        echo $this->json($rs);exit;
    }

    function tagsearch(){
        $term = $this->getParam("term");
        $term = strtolower(urldecode($term));
        if(!$term){ echo $this->json(array());exit;};
        $where = array();
        if(preg_match('/[a-zA-Z]/',$term)){
            $where['pinyin'] = array("like",$term."%","or");
        }else{
            $where['name'] = array("like","%".$term."%","or");
        }
        $list = zl::dao("tag")->gets($where," zl_count DESC",0,8);

        if(!$list){ echo $this->json(array());exit;};
        $rs = array();
        foreach ($list as $v){
            $rs[] = $v['name'];
        }
        echo $this->json($rs);exit;
    }

    function v($p='',$id=''){
        $this->setLayout("main");
        $id = intval($id);

        $this->loginuser = site_user_service::service()->getLogin();
        $arc = zl::dao("arc")->get(array("id"=>$id));
        if($this->loginuser['level']!=99 && (!$arc['is_publish'])){
            if(!$arc) $this->showMsg("主题文章不存在!",0,0,"/");
        }

        $user = zl::dao("user")->get(array("id"=>$arc['uid']));
        $tags = zl::dao("tag")->gets(array("id"=>array("in",explode(",",trim($arc['tags'],',')))));
        $this->arc = $arc;
        $this->tags = $tags;
        $this->user = $user;
        $this->title = $arc['title'];
        zl::dao("arc")->inCrease("view_count",array("id"=>$id));
        site_index_service::service()->updateArcScore($id);

        //上下一篇
        $this->prearc = zl::dao("arc")->get(array("zl_score"=>array(">",$arc['zl_score']),"id"=>array("!=",$id),"is_publish"=>1),"zl_score asc,id asc");

        $this->nextarc = zl::dao("arc")->get(array("zl_score"=>array("<",$arc['zl_score']),"id"=>array("!=",$id),"is_publish"=>1),"zl_score desc,id desc");

        $tagExsNames = zl::dao("tag_ext")->getField("name",array("arc_id"=>$id,"is_publish"=>1));
        $likeWhere=array();
        if($tagExsNames){
            $arcIds = zl::dao("tag_ext")->getField("arc_id",array("name"=>array("in",$tagExsNames),"arc_id"=>array("!=",$id)));
            if($arcIds) $likeWhere['id']=array("in",$arcIds);
        }
        $orderBy = "zl_score DESC";
        if(!$likeWhere){
            $orderBy = "rand()";
        }
        $likeWhere['is_publish'] = 1;
        $this->likearc = zl::dao("arc")->gets($likeWhere,$orderBy,0,15);
        
        $this->id = $id;


        $p = (int) $p;
        $where=array();
        $where['arc_id'] = $id;
        $where['is_publish'] = 1;
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
        $this->display("site/index/v");
    }

    function reply(){
        if(isPost()){
            $id = (int) $this->getParam("id");
            $arc = zl::dao("arc")->get(array("id"=>$id,"is_publish"=>1));
            if(!$arc) $this->showMsg("回复的主题文章不存在!");
            $content = trim($this->getParam("content"));
            if(!$content)  $this->showMsg("回复不能为空!");
            if(mb_strlen($content,'utf-8')>10000) $this->showMsg("回复不能大于10000字");
            $data=array();
            $user = site_user_service::service()->getLogin();
            $data['uid'] =$user['id'];
            $data['arc_id'] =$id;
            $data['content'] = $content;
            $insertId = zl::dao("reply")->insert($data);
            $check = zl::dao("data")->get(array("zl_key"=>"reply_count"));
            if($check){
                zl::dao("data")->inCrease("zl_value",array("zl_key"=>"reply_count"));
            }else{
                zl::dao("data")->insert(array("zl_key"=>"reply_count","zl_value"=>1));
            }
            zl::dao("arc")->inCrease("reply_count",array("id"=>$id));
            zl::dao("arc")->update(array("last_reply_uid"=>$user['id'],"last_reply_time"=>date('Y-m-d H:i:s')),array("id"=>$id));
            $count = zl::dao("reply")->getCount(array("arc_id"=>$id,"is_publish"=>1));
            $p = ceil($count/zl::$configApp['page_size']);

            $redirectUrl  = "/v-".$p."-".$id."#reply_content_".$insertId;
            //更新统计信息
            $noticeUsers = site_user_service::service()->getAtUid($content);
            //加上主题创建人
            array_push($noticeUsers,$arc['uid']);
            //剔除自己
            $key = array_search($user['id'], $noticeUsers);
            $noticeUsers = (array) array_unique($noticeUsers);
            if($key !== false){
                unset($noticeUsers[$key]);
            }

            site_user_service::service()->sendNotice($user['id'],$content,$redirectUrl,$noticeUsers);
            zl_hook::run("arc_reply",array("id"=>$insertId));
            $this->redirect($redirectUrl);
        }else{
            $this->showMsg("非法访问");
        }
    }

    function delReply($id=0,$arcId=0){
        if(!$id){
            $this->redirect("/v-1-".$arcId);
            return ;
        }
        $info =   zl::dao('reply')->get(array('id'=>$id));
        if($info['uid']!=$this->getUid()){
            $this->showMsg("你没有此操作的权限");
        }

        zl::dao("arc")->deCrement("reply_count",array("id"=>$arcId));
        zl::dao("data")->deCrement("zl_value",array("zl_key"=>"reply_count"));
        zl_hook::run("arc_reply_delete",array("id"=>$id,"arc"=>$arcId));
        zl::dao('reply')->delete(array('id'=>$id));
        $this->redirect("/v-1-".$arcId);
    }

    function n($id=0){
        $this->setLayout("main");
        $id = (int) $id;
        $info = zl::dao("notice")->get(array("id"=>$id));
        if(!$info) $this->showMsg("公告不存在！");
        $this->info = $info;
        $this->title = $info['title'];
        $this->display();
    }

    function noticeRead($id=0){
        $id = (int) $id;
        if(!$id) return false;
        $user = site_user_service::service()->getLogin();
        zl::dao("msg_notice_read_log")->update(array("is_read"=>1),array("notice_id"=>$id,"uid"=>$user['id']));
    }

    function msg($p=1){
        $this->setLayout("main");
        $user = site_user_service::service()->getLogin();
        $uid = $user['id'];
        $p = (int) $p;
        $p = $p?$p:1;
        $pageSize = zl::$configApp['page_size'];
        $limit = ($p-1)*$pageSize;
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ".zl::$configApp['db']['prefix']."msg_data WHERE root_id=id AND ((uid='".$uid."' AND send_status = 1 AND msg_type=0) OR
		       (receive_uid = '".$uid."' AND receve_status=1)) ORDER BY ctime DESC  LIMIT ".$limit.", ".$pageSize."";
        $list = zl::dao("msg_data")->selectAll($sql,array(),1);
        $total = zl::dao("msg_data")->getTotal();

        if($list){
            foreach($list as $k=>$v){
                $info = zl::dao("msg_data")->selectRow("SELECT * FROM ".zl::$configApp['db']['prefix']."msg_data WHERE id = '".$v['id']."' ORDER BY ctime DESC LIMIT 1");
                if($info['uid'] == $uid){
                    $userinfo = zl::dao("user")->get(array("id"=>$info['receive_uid']));
                    $info['user_receive'] = $userinfo;
                }else{
                    $userinfo = zl::dao("user")->get(array("id"=>$info['uid']));
                    $info['user_send'] = $userinfo;
                }
                $list[$k]['info'] = $info;
            }
        }

        $markup = zl_paginator::paginator()->parse($pageSize,$p,$total,"msg-@p");

        $this->markup = $markup;
        $this->list = $list;
        $this->user = $user;

        $this->title = "站内留言";
        $this->display();
    }

    function setTop($id=0){
        $loginUser = $this->getUser();
        if($loginUser['level'] !=99) $this->showMsg('你没有权限进行此操作!');
        $arc = zl::dao("arc")->get(array("id"=>$id));
        $set = $arc['zl_type']==1?2:1;
        $data=array();
        $data['zl_type'] = $set;
        if($set==2){
            $topScore = zl::dao("arc")->getField("zl_score",array("is_publish"=>1),false,"zl_score DESC");
            $data['zl_score_base'] = $topScore+1-$arc['zl_score'];
        }
        zl::dao("arc")->update($data,array("id"=>$id));
//        dd(zl::dao("arc")->getSql());
        $this->redirect("v-1-".$id);
    }

    function msgdelete($id=0){
        if(!$id) $this->showMsg("非法操作!");
        $info = zl::dao("msg_data")->get(array("id"=>$id));
        if(!$info) $this->showMsg("非法操作!");
        //收信箱删除
        $uid = $this->getUid();
        if($info['receive_uid'] == $uid){
            zl::dao("msg_data")->update(array("receve_status"=>0),array("id"=>$id));
        }
        //发信箱删除
        if($info['uid'] == $uid){
            zl::dao("msg_data")->update(array("send_status"=>0),array("id"=>$id));
        }
        $this->redirect("/msg");
    }

    function msgv($id=0,$p=1){
        if(isPost()){
//            zl_form::csrfValidate();
            $receiver = $this->getParam("receiver");
            $id = (int) $this->getParam("id");
            $content = trim($this->getParam("content"));
            if(!$receiver) $this->showMsg("收件人不能为空!");
            if(!$content) $this->showMsg("正文不能为空!");
            $user = site_user_service::service()->getLogin();

            $uid = $user['id'];
            $ruids = zl::dao("user")->getField("id",array("nickname"=>array("in",explode(",",$receiver))),true);

            foreach($ruids as $v){
                if($v == $uid) continue;
                zl::dao("msg_data")->inCrease("sub_count",array("id"=>$id));
                $data=array();
                $data['root_id'] = $id;
                $data['sub_count'] = 1;
                $data['uid'] = $uid;
                $data['receive_uid'] = $v;
                $data['content'] = $content;
                $data['is_read'] = 0;
                $data['send_status'] = 1;
                $data['receve_status'] = 1;
                $data['msg_type'] = 0;
                $msgId = zl::dao("msg_data")->insert($data);
                $redirectUrl  = "msgv-".$msgId."-1";
                site_user_service::service()->sendNotice($uid,$content,$redirectUrl,array($v),2);
            }
            $this->redirect("msgv-".$id."-".$p);
        }

        $id = (int) $id;
        $info = zl::dao("msg_data")->get(array("id"=>$id));
        if(!$info) $this->showMsg("页面不存在!");
        if($info['root_id']!=$id){
            $id = $info['root_id'];
            $info = zl::dao("msg_data")->get(array("id"=>$id));
        }
        $this->id = $id;
        $user = site_user_service::service()->getLogin();
        $uid = $user['id'];

        if($uid==$info['uid']){
            $ouser = zl::dao("user")->get(array("id"=>$info['receive_uid']));
        }else{
            $ouser = zl::dao("user")->get(array("id"=>$info['uid']));
        }

        $this->info =$info;
        $this->ouser =$ouser;
        $this->user =$user;

        zl::dao("msg_data")->update(array("is_read"=>1),array("root_id"=>$id,"receive_uid"=>$uid));

        $p = (int) $p;
        $p = $p?$p:1;
        $pageSize = zl::$configApp['page_size'];
        $limit = ($p-1)*$pageSize;
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ".zl::$configApp['db']['prefix']."msg_data WHERE root_id='".$id."' AND ((uid='".$uid."' AND send_status = 1 AND msg_type=0) OR
		       (receive_uid = '".$uid."' AND receve_status=1)) ORDER BY ctime DESC  LIMIT ".$limit.", ".$pageSize."";
        $list = zl::dao("msg_data")->selectAll($sql,array(),1);
        $total = zl::dao("msg_data")->getTotal();

        $markup = zl_paginator::paginator()->parse($pageSize,$p,$total,"/msgv-".$id."-@p");
        if($list){
            foreach($list as $k=>$v){
                if($v['uid'] == $uid){
                    $list[$k]['is_send'] = 1;
                }else{
                    $list[$k]['is_send'] = 0;
                }
            }
        }

        $this->list =$list;
        $this->markup =$markup;

        $this->setLayout("main");
        $this->title = "查看留言";
        $this->display();
    }

    function addmsg($id=0){
        $id = (int) $id;
        $this->id = $id;
        $this->setLayout("main");
        if(isPost()){
            zl_form::csrfValidate();
            $receiver = $this->getParam("receiver");
            $id = (int) $this->getParam("id");
            $content = trim($this->getParam("content"));
            if(!$receiver) $this->showMsg("收件人不能为空!");
            if(!$content) $this->showMsg("正文不能为空!");
            $user = site_user_service::service()->getLogin();

            $uid = $user['id'];
            $ruids = zl::dao("user")->getField("id",array("nickname"=>array("in",explode(",",$receiver))),true);

            foreach($ruids as $v){
                if($v == $uid) continue;
                $data=array();
                $data['root_id'] = 0;
                $data['sub_count'] = 1;
                $data['uid'] = $uid;
                $data['receive_uid'] = $v;
                $data['content'] = $content;
                $data['is_read'] = 0;
                $data['send_status'] = 1;
                $data['receve_status'] = 1;
                $data['msg_type'] = 0;
                $msgId = zl::dao("msg_data")->insert($data);
                zl::dao("msg_data")->update(array("root_id"=>$msgId),array('id'=>$msgId));
                $redirectUrl  = "msgv-".$msgId."-1";
                site_user_service::service()->sendNotice($uid,$content,$redirectUrl,array($v),2);
            }
            $this->showMsg('发送成功');
        }
        $this->title = "写信";
        $this->display();
    }


    function notice($p=1){
        $this->setLayout("main");
        $user = site_user_service::service()->getLogin();
        $p = (int) $p;
        $where = array();
        $where['uid'] = $this->getUid();
        $orderBy = "ctime desc, is_read desc";
        list($list,$markup) = zl::dao("msg_notice_read_log")->pager($where,$orderBy,"",$p,"/notice-@p");
        $newList = array();
        if($list){
            foreach($list as $k=>$v){
                $info = zl::dao("msg_notice")->get(array("id"=>$v['notice_id']));
                $feed = json_decode($info['feed_content'],true);

                $newList[$k] = $info;
                $user = zl::dao("user")->get(array("id"=>$feed['user']));
                $newList[$k]['feed'] = $feed;
                $newList[$k]['user'] = $user;
                $newList[$k]['feed'] = $feed;
                $newList[$k]['is_read'] = $v['is_read'];
            }
        }

        $this->list =$newList;
        $this->markup =$markup;
        $this->title = "通知";
        $this->display();
    }

    function usersearch(){
        $term = $this->getParam("term");
        $term = strtolower(urldecode($term));
        if(!$term){ echo $this->json(array());exit;};
        $where = array();
        if(preg_match('/[a-zA-Z]/',$term)){
            $where['pinyin'] = array("like",$term."%","or");
        }else{
            $where['nickname'] = array("like","%".$term."%","or");
        }
        $list = zl::dao("user")->gets($where," pinyin asc",0,8);

        if(!$list){ echo $this->json(array());exit;};
        $rs = array();
        foreach ($list as $v){
            $rs[] = $v['nickname'];
        }
        echo $this->json($rs);exit;
    }

    function t($p=1,$tag=''){
        $this->setLayout("main");
        if(!$tag) $this->showMsg("标签为空!");
        zl::set("ctag",$tag);
        $this->p = $p;
        $this->tag = $tag;
        $taginfo = zl::dao("tag")->get(array("name"=>$tag));
        $this->taginfo = $taginfo;
        $this->user = zl::dao("user")->get(array("id"=>$taginfo['uid']));

        $p = (int) $p;
        $where = array();
        $where['tags'] = array("like","%,".$taginfo['id'].",%");
        $orderBy = "zl_score desc,ctime desc";
        list($list,$markup) = zl::dao("arc")->pager($where,$orderBy,"",$p,"/t-@p-".$tag);

        if($list){
            foreach($list as $k=>$v){
                $user = zl::dao("user")->get(array("id"=>$v['uid']));
                $list[$k]['user'] = $user;
                $last_reply_user = array();
                if($v['last_reply_uid']){
                    $last_reply_user = zl::dao("user")->get(array("id"=>$v['last_reply_uid']));
                }
                $list[$k]['last_reply_user'] = $last_reply_user;
                $tags = zl::dao("tag")->gets(array("id"=>array("in",explode(",",trim($v['tags'],',')))));
                $list[$k]['tags'] = $tags;
            }
        }
        $this->list = $list;
        $this->markup = $markup;

        $this->alltags = zl::dao("tag")->gets(array(),"zl_score",0,100);

        $this->title = "标签-".$taginfo['name'];
        $this->display();
    }

    function deletev($id=0){
        if(!$id) $this->showMsg("页面不存在!");
        $info = zl::dao("arc")->get(array("id"=>$id,"is_publish"=>1));
        if(!$info) $this->showMsg("页面不存在!",0,0,"/me");
        if($info['uid']!=$this->getUid()) $this->showMsg("非法操作!",0,0,"/me");
        $difftime = time()-strtotime($info['ctime']);
        if($difftime>300){
            $this->showMsg("主题删除必须在".zl::$configApp['arc_timeout']."秒内",0,0,"/me");
        }

        zl_hook::run("arc_delete",array("id"=>$id));

        zl::dao("arc")->update(array("is_publish"=>0),array("id"=>$id));

        $check = zl::dao("data")->get(array("zl_key"=>"arc_count"));
        if($check){
            zl::dao("data")->deCrement("zl_value",array("zl_key"=>"arc_count"));
        }
        $this->redirect("/me");
    }

    function editv($id=0){
        $id = intval($id);
        $this->setLayout("main");
        if(!$id) $this->showMsg("页面不存在!");
        $info = zl::dao("arc")->get(array("id"=>$id,"is_publish"=>1));
        if(!$info) $this->showMsg("页面不存在!",0,0,"/me");
        if($info['uid']!=$this->getUid()) $this->showMsg("非法操作!",0,0,"/me");
        $difftime = time()-strtotime($info['ctime']);
        if($difftime>zl::$configApp['arc_timeout']){
            $this->showMsg("主题编辑必须在".zl::$configApp['arc_timeout']."秒内",0,0,"/me");
        }

        $tagsoldids = explode(",",trim($info['tags'],","));
        $tagnames = zl::dao("tag")->getField("name",array("id"=>array("in",$tagsoldids)),true);

        $info['tagname'] = $tagnames;

        $this->info = $info;
        $this->title = "编辑主题";


        if(isPost()){
            zl_form::csrfValidate();
            $title = trim($this->getParam("title"));
            $content = trim($this->getParam("content"));
            $tags = strip_tags(trim($this->getParam("tags")));
            if(!$title) $this->showMsg("标题不能为空");
            if(mb_strlen($title,'utf-8')>120) $this->showMsg("标题不能大于120字");
            if(!$content) $this->showMsg("正文不能为空");
            if(mb_strlen($content,'utf-8')>20000) $this->showMsg("正文不能大于20000字");
            if(!$tags) $this->showMsg("标签不能为空");
            $tagsArr = explode(",",$tags);
            if(count($tagsArr)>5) $this->showMsg("最多5个标签");

            if($tagsArr){
                foreach($tagsArr as $tagc){
                    if(mb_strlen($tagc,'utf-8')>20) $this->showMsg("标签长度每个不能大于20字");
                }
            }

            try{
                $userInfo = site_user_service::service()->isLogin();
                $uid = $userInfo['id'];
                $arcData=array();
                $arcData['title'] = $title;
                $arcData['content'] = $content;
                $arcData['view_count'] = 1;
                $arcData['zl_type'] = 1;
                $arcData['uid'] = $uid;
                $arcData['is_publish']=1;
                zl::dao("arc")->update($arcData,array("id"=>$id));
                $arc_id = $id;
                site_index_service::service()->updateArcScore($arc_id);
                $tags=",";
                foreach($tagsArr as $v){
                    $check = zl::dao("tag")->get(array("name"=>$v));
                    if(!$check){
                        $data=array();
                        $data['name'] = $v;
                        $data['pinyin'] = current(zl_pinyin::getPinyin($v));
                        $data['zl_count'] = 1;
                        $data['uid'] = $this->getUid();
                        $tag_id = zl::dao("tag")->insert($data);
                        $check = zl::dao("data")->get(array("zl_key"=>"tag_count"));
                        if($check){
                            zl::dao("data")->inCrease("zl_value",array("zl_key"=>"tag_count"));
                        }else{
                            zl::dao("data")->insert(array("zl_key"=>"tag_count","zl_value"=>1));
                        }
                    }else{
                        $tag_id = $check['id'];
                    }
                    $tags .=$tag_id.",";
                    site_index_service::service()->updateTagScore($tag_id);
                }

                zl::dao("arc")->update(array("tags"=>$tags),array("id"=>$arc_id));
                //tag_ext
                site_index_service::service()->addArcTagExt($title,$content,$arc_id,8);
                if($tagsoldids){
                    foreach($tagsoldids as $oldid){
                        site_index_service::service()->updateTagScore($oldid);
                    }
                }

            }catch (Exception $e){
                $this->showMsg($e->getMessage());
            }
            $this->redirect("/me");

        }
        $this->display();

    }

}