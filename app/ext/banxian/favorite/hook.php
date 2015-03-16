<?php
zl_hook::create("arc_view",function($params){
echo ext_banxian_favorite_site_service::service()->view($params['id']);
});
zl_hook::create("setting_nav",function($params){
    $str = settingMatch("ext.banxian.favorite.site.my");
echo "<li ".$str."><a href=\"".url("favorite/my")."\"><i class=\"glyphicon glyphicon-star\"></i>&nbsp;&nbsp;&nbsp;&nbsp;收藏</a></li>";
});