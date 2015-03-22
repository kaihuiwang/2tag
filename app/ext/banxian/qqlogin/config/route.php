<?php
return array(
    "ext.banxian.qqlogin.site.qqlogin" => array("route" => "get|post /qqlogin", "use" => "ext_banxian_qqlogin_site_controller@qqlogin","ext_name"=>"banxian/qqlogin"),
    "ext.banxian.qqlogin.site.qqregister"=> array("route" => "/qqregister", "use" => "ext_banxian_qqlogin_site_controller@qqregister", "ext_name"=>"banxian/qqlogin"),
    "ext.banxian.qqlogin.site.qqlogin_bind"=> array("route" => "/qqlogin_bind", "use" => "ext_banxian_qqlogin_site_controller@qqlogin_bind", "before" => "site_user_service@logined","ext_name"=>"banxian/qqlogin"),
    "ext.banxian.qqlogin.site.qqlogin_unbind"=> array("route" => "/qqlogin_unbind", "use" => "ext_banxian_qqlogin_site_controller@qqlogin_unbind", "before" => "site_user_service@logined","ext_name"=>"banxian/qqlogin"),
    "ext.banxian.qqlogin.site.bind"=> array("route" => "/bind", "use" => "ext_banxian_qqlogin_site_controller@bind", "before" => "site_user_service@logined","ext_name"=>"banxian/qqlogin"),
);
