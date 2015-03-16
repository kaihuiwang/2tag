<?php
class ext_banxian_isonline_service extends zl_ext_service{
    function isOnline($uid){
        if(!$uid) return false;
        $user = site_user_service::service()->getOnlineUser();
        if(isset($user[$uid]) && $user[$uid]) return true;
        return false;
    }

    function showOnline($uid){
        $isOnline = $this->isOnline($uid);
        if($isOnline){
            return "<li class='glyphicon glyphicon-user tip' style='color: #00a300' data-toggle=\"tooltip\" data-placement=\"top\" title=\"当前在线\"></li>";
        }else{
            return "<span><li class='glyphicon glyphicon-user tip'  style='color: #aaa' data-toggle=\"tooltip\" data-placement=\"top\" title=\"当前不在线\"></li></span>";
        }
    }
}