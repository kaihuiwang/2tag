<?php
return array(
    "ext.banxian.news.index.index" => array("route" => "/news", "use" => "ext_banxian_news_index_controller@index","ext_name"=>"banxian/news"),
    "ext.banxian.news.index.index_ptype" => array("route" => "get /news/@p:[0-9]{1,}(-@type)", "use" => "ext_banxian_news_index_controller@index@p@type","ext_name"=>"banxian/news"),
    "ext.banxian.news.index.add" => array("route" => "get /news/add", "use" => "ext_banxian_news_index_controller@add","before" => "site_user_service@logined","ext_name"=>"banxian/news"),
    "ext.banxian.news.index.doadd" => array("route" => "get|post /news/doadd", "use" => "ext_banxian_news_index_controller@doadd","before" => "site_user_service@logined","ext_name"=>"banxian/news"),
    "ext.banxian.news.index.v" => array("route" => "get /news/v-@p:[0-9]{1,}(-@id)", "use" => "ext_banxian_news_index_controller@v@p@id","ext_name"=>"banxian/news"),
    "ext.banxian.news.index.dogood" => array("route" => "/news/dogood-@arcId", "use" => "ext_banxian_news_index_controller@dogood@arcId","before" => "site_user_service@logined","ext_name"=>"banxian/news"),
    "ext.banxian.news.index.my_news" => array("route" => "/my_news(-@p)", "use" => "ext_banxian_news_index_controller@my_news@p","before" => "site_user_service@logined","ext_name"=>"banxian/news"),
    "ext.banxian.news.index.delete_news" => array("route" => "/delete_news/@id", "use" => "ext_banxian_news_index_controller@delete_news@id","before" => "site_user_service@logined","ext_name"=>"banxian/news"),
);