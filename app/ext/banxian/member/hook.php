<?php
zl_hook::create("arc_add",function($params){
   return ext_banxian_member_site_service::service()->addArcNumber($params['id']);
});

zl_hook::create("arc_delete",function($params){
    return ext_banxian_member_site_service::service()->cutArcNumber($params['id']);
});

zl_hook::create("arc_reply",function($params){
    return ext_banxian_member_site_service::service()->addReplyNumber($params['id']);
});

zl_hook::create("arc_reply_delete",function($params){
    return ext_banxian_member_site_service::service()->cutReplyNumber($params['id']);
});

zl_hook::create("top_nav",function(){
    echo "<li><a href=\"".url("/member")."\">会员</a></li>";
});