<?php
return array(
    "ext.banxian.farther.site.add" => array("route" => "/farther/add-@id", "use" => "ext_banxian_farther_site_controller@add@id","before" => "site_user_service@logined","ext_name"=>"banxian/farther"),
    "ext.banxian.farther.site.get" => array("route" => "/farther/get-@id", "use" => "ext_banxian_farther_site_controller@get@p","before" => "site_user_service@logined","ext_name"=>"banxian/farther"),
);