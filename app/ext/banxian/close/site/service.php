<?php
class ext_banxian_close_site_service extends zl_ext_service{
    function setClose($id){
        $user = site_user_service::service()->getLogin();
        $arc = zl::dao("arc")->get(array("id"=>$id));
        if($user['level'] == 99){
            $title = !$arc['is_publish']?"取消屏蔽":"屏蔽";
            return "&nbsp;&nbsp;&nbsp;&nbsp;<a href='".url('/close/todo-'.$id)."'>".$title."</a>";
        }else{
            return ;
        }
    }
}