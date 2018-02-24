<?php
return array(
    "ext.banxian.favorite.site.todo" => array("route" => "/favorite/todo-@id", "use" => "ext_banxian_favorite_site_controller@todo@id","before" => "site_user_service@logined","ext_name"=>"banxian/favorite"),
    "ext.banxian.favorite.site.my" => array("route" => "/favorite/my(-@p)", "use" => "ext_banxian_favorite_site_controller@my@p","before" => "site_user_service@logined","ext_name"=>"banxian/favorite"),
 );