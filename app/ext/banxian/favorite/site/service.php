<?php
class ext_banxian_favorite_site_service extends zl_ext_service{

    function view($id){
        $user = site_user_service::service()->getLogin();
        if($user){
            $uid = $user['id'];
            $info = zl::dao("ext_favorite")->get(array("arc_id"=>$id,"uid"=>$uid));
            if($info){
                $str = "<a href=\"".url("/favorite/todo-".$id)."\">取消收藏</a>";
            }else{
                $str = "<a href=\"".url("/favorite/todo-".$id)."\">收藏</a>";
            }
        }else{
            $str = "<a href=\"".url("/favorite/todo-".$id)."\">收藏</a>";
        }
        return  $str;
    }
}