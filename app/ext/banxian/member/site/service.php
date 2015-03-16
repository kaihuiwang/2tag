<?php
class ext_banxian_member_site_service extends zl_ext_service{

    function addArcNumber($id){
        $uid = zl::dao("arc")->getField("uid",array("id"=>$id));
        zl::dao("user")->inCrease("arc_number",array("id"=>$uid));
        return true;
    }

    function cutArcNumber($id){
        $uid = zl::dao("arc")->getField("uid",array("id"=>$id));
        zl::dao("user")->deCrement("arc_number",array("id"=>$uid));
        return true;
    }

    function addReplyNumber($id){
        $uid = zl::dao("reply")->getField("uid",array("id"=>$id));
        zl::dao("user")->inCrease("reply_number",array("id"=>$uid));
        return true;
    }

    function cutReplyNumber($id){
        $uid = zl::dao("reply")->getField("uid",array("id"=>$id));
        zl::dao("user")->deCrement("reply_number",array("id"=>$uid));
        return true;
    }

}