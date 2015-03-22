<?php
return array(
    "ext.banxian.zhuanti.site.index" => array("route" => "/zhuanti", "use" => "ext_banxian_zhuanti_site_index_controller@index","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.index.z" => array("route" => "/zhuanti-@p:[0-9]{1,}(-@z)", "use" => "ext_banxian_zhuanti_site_index_controller@index@p@z","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.add_zhuanti"=> array("route" => "get|post /add_zhuanti", "use" => "ext_banxian_zhuanti_site_index_controller@add_zhuanti", "before" => "site_user_service@logined","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.checkzhuanti"=> array("route" => "/checkzhuanti(/@id)", "use" => "ext_banxian_zhuanti_site_index_controller@checkzhuanti@id", "before" => "site_user_service@logined","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.add_zhuanti_tag"=> array("route" => "get|post /add_zhuanti_tag/@id", "use" => "ext_banxian_zhuanti_site_index_controller@add_zhuanti_tag@id", "before" => "site_user_service@logined","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.tagdelete"=> array("route" => "get|post /zhuanti_tagdelete/@id", "use" => "ext_banxian_zhuanti_site_index_controller@tagdelete@id", "before" => "site_user_service@logined","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.delete_zhuanti"=> array("route" => "get|post /delete_zhuanti/@id", "use" => "ext_banxian_zhuanti_site_index_controller@delete_zhuanti@id", "before" => "site_user_service@logined","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.my_zhuanti" => array("route" => "/my_zhuanti(/@p)", "use" => "ext_banxian_zhuanti_site_index_controller@my_zhuanti@p","before" => "site_user_service@logined","ext_name"=>"banxian/zhuanti"),
    "ext.banxian.zhuanti.site.edit_zhuanti" => array("route" => "get|post /edit_zhuanti/@id", "use" => "ext_banxian_zhuanti_site_index_controller@edit_zhuanti@id","before" => "site_user_service@logined","ext_name"=>"banxian/zhuanti"),

);