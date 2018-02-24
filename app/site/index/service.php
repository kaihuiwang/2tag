<?php

class site_index_service extends zl_service
{


    function checkCaptcha($captcha, $salt='')
    {
        if(!$salt){
            $salt = zl::get('current_route_name');
        }
        $phrase = zl_session::get($salt."phrase");
        if (!$phrase) return false;
        return strtolower($phrase) == strtolower($captcha);
    }

    function getScore($count,$mtime,$ctime,$base=0){
        $timer = 3600*3;
        $mtime = is_numeric($mtime)?$mtime:strtotime($mtime);
        $ctime = is_numeric($ctime)?$ctime:strtotime($ctime);
        $mnumber = ceil((time()-$mtime)/$timer);
        $cnumber = ceil((time()-$ctime)/$timer);
        $number = ceil(($cnumber+$mnumber)/2);
        $result = abs(getVoteScore($count,$number))+$base;
        return $result;
    }

    function updateTagScore($id){
        if(!$id) return ;
        $check = zl::dao("tag")->get(array("id"=>$id));
        if(!$check) return ;
        $version = date('YmdH');
        $score = $this->getScore($check['zl_count'],date('Y-m-d H:i:s'),$check['ctime']);
        zl::dao("tag")->update(array("zl_score"=>$score,"score_version"=>$version),array("id"=>$check['id']));
        return true;
    }

    function updateArcScore($id){
        if(!$id) return ;
        $check = zl::dao("arc")->get(array("id"=>$id));
        if(!$check) return ;
        $version = date('YmdH');
        $num = $check['reply_count']+$check['good_number']-$check['bad_number'];
        $score = $this->getScore($num,date('Y-m-d H:i:s'),$check['ctime'],$check['zl_score_base']);
//        exit($score);
        zl::dao("arc")->update(array("zl_score"=>$score,"score_version"=>$version),array("id"=>$id));
        return true;
    }

    function addArcTagExt($title,$content,$arc_id,$num = 3){
        $tagExt = getTags($title,$content,$num);
        if($tagExt){
            foreach($tagExt as $v){
                $check = zl::dao("tag_ext")->get(array("name"=>$v));
                if(!$check){
                    $data=array();
                    $data['name'] = $v;
                    $data['arc_id'] = $arc_id;
                    zl::dao("tag_ext")->insert($data);
                }
            }
        }
    }

    function scoreJob(){
            $version = date('YmdH');
            $arcIds = zl::dao("arc")->getField("id",array("score_version"=>array("<",$version),"is_publish"=>1),true,"score_version ASC",0,10);
            if($arcIds) {
                foreach ($arcIds as $v) {
                    $this->updateArcScore($v);
                }
            }
            $tagIds = zl::dao("tag")->getField("id",array("score_version"=>array("<",$version),"is_publish"=>1),true,"score_version ASC",0,10);
            if($tagIds) {
                foreach ($tagIds as $v) {
                    $this->updateTagScore($v);
                }
            }
        }

    function upImg($savePath,$isked=0){
        $upload = zl_fupload::fupload();
        //设置上传文件大小
        $upload->maxSize=1024*1024*1;//最大1M
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,png,jpeg,bmp');
        //设置附件上传目录
        $upload->savePath = $savePath;
        if(!is_dir($upload->savePath)) mkdir($upload->savePath, 0777, true);
        $upload->saveRule = "uniqid";
        if(!$upload->upload())
        {
                //捕获上传异常
                if($isked){
                    $this->showJson(0,'上传失败，只支持jpg,png,jpeg,bmp格式，大小不大于1M的图片，请重新上传!');
                }else{
                    zl::showMsg("上传失败，只支持jpg,png,jpeg,bmp格式，不大于1M的图片，请重新上传!");
                }
        }
        else
        {
            //取得成功上传的文件信息
            $info = $upload->getUploadFileInfo();
            return $info;
        }
    }

    #裁剪图片
    function cutImg($oldPath, $newPath, $sizes=array("160x160","48x48", "24x24")){
        $pathinfo = pathinfo($oldPath);
        $ext = $pathinfo['extension'];
        foreach ($sizes as $v){
            $newPathTmp = $newPath."_".$v.".".$ext;
            list($w, $h) = explode('x', $v);
            $img = zl_image::make($oldPath);
            $img->resize($w, $h);
            $img->save($newPathTmp);
        }
        $savepath = str_replace(ROOT_PATH, "", $newPath);
        $savepath = str_replace('\\', '/', $savepath);
        return $savepath;
    }

    function setTop($id){
        $user = site_user_service::service()->getLogin();
        $arc = zl::dao("arc")->get(array("id"=>$id));
        $set = (int) !$arc['zl_type'];
        if($user['level'] == 99){
            $title = $arc['zl_type']==2?"取消精华":"置为精华";
            return "&nbsp;&nbsp;&nbsp;&nbsp;<a href='".url('/setTop/'.$id)."'>".$title."</a>";
        }else{
            return ;
        }
    }

}