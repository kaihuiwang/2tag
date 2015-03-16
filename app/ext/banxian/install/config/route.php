<?php
return array(
    "ext.banxian.install.site.index" => array("route" => "/install", "use" => "ext_banxian_install_site_controller@index","ext_name"=>"banxian/install"),
    "ext.banxian.install.site.config" => array("route" => "/install/config", "use" => "ext_banxian_install_site_controller@config","ext_name"=>"banxian/install"),
    "ext.banxian.install.site.check" => array("route" => "/install/check", "use" => "ext_banxian_install_site_controller@check","ext_name"=>"banxian/install"),
    "ext.banxian.install.site.success" => array("route" => "/install/success", "use" => "ext_banxian_install_site_controller@success","ext_name"=>"banxian/install"),
);