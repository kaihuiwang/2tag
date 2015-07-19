<?php
zl_hook::create("setting_nav",function($params){
    $str = settingMatch("ext.banxian.qqlogin.site.bind");
    echo "<li ".$str."><a href=\"".url("/bind")."\"><i class=\"glyphicon glyphicon-link\"></i>&nbsp;&nbsp;&nbsp;&nbsp;绑定QQ</a></li>";
});
zl_hook::create("top_nav_after",function(){
    echo " <li><a href=\"".url("qqlogin")."\" class=\"openqq\" title=\"用QQ帐号登录\"> <img width=\"20\" height=\"20\" src=\"".img("/public/images/bg/qq.jpg")."\"></a></li>";
});
zl_hook::create("top_nav_after_mobile",function(){
    echo " <li><a href=\"".url("qqlogin")."\">QQ登陆</a></li>";
});