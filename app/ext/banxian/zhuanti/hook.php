<?php
zl_hook::create("tag_view",function($params){
echo ext_banxian_zhuanti_site_index_service::service()->tuijian($params);
});
zl_hook::create("top_nav",function(){
    echo "<li><a href=\"".url("/zhuanti")."\">专题</a></li>";
});