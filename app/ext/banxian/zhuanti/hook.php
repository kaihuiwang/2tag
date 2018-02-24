<?php
zl_hook::create("tag_view",function($params){
echo ext_banxian_zhuanti_site_index_service::service()->tuijian($params);
});
zl_hook::create("top_nav",function(){
    echo "<li><a href=\"".url("/zhuanti")."\">专题</a></li>";
});
zl_hook::create("setting_nav",function($params){
    $str = settingMatch("ext.banxian.zhuanti.site.my_zhuanti");
    echo "<li ".$str."><a href=\"".url("my_zhuanti")."\"><i class=\"glyphicon glyphicon-magnet\"></i>&nbsp;&nbsp;&nbsp;&nbsp;专题</a></li>";
});