<?php
class ext_banxian_close_site_controller extends zl_ext_controller
{
    function close($id=0){
        $loginUser = $this->getUser();
        if($loginUser['level'] !=99) $this->showMsg('你没有权限进行此操作!');

        $arc = zl::dao("arc")->get(array("id"=>$id));
        if(!$arc){
            $this->redirect("v-1-".$id);
        }
        $is_publish = (int) !$arc['is_publish'];
        zl::dao("arc")->update(array("is_publish"=>$is_publish),array("id"=>$id));
        $this->redirect("v-1-".$id);
    }
}