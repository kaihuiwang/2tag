<?php
zl_hook::create("top_nav",function(){
    echo "<li><a href=\"".url("/news")."\">掘金</a></li>";
});
zl_hook::create("job",function(){
    ext_banxian_news_index_service::service()->scoreJob();
});
zl_hook::create("setting_nav",function($params){
    $str = settingMatch("ext.banxian.news.index.my_news");
    echo "<li ".$str."><a href=\"".url("my_news")."\"><i class=\"glyphicon glyphicon-tower\"></i>&nbsp;&nbsp;&nbsp;&nbsp;掘金</a></li>";
});