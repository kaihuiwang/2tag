<?php
return array(
    "ext.banxian.backup.site.index" => array("route" => "/admin/backup/index(-@p)", "use" => "ext_banxian_backup_site_controller@index@p","before" => "admin_index_service@checklogin","ext_name"=>"banxian/backup"),
    "ext.banxian.backup.site.add" => array("route" => "/admin/backup/add", "use" => "ext_banxian_backup_site_controller@add","before" => "admin_index_service@checklogin","ext_name"=>"banxian/backup"),
    "ext.banxian.backup.site.delete" => array("route" => "/admin/backup/delete/@id", "use" => "ext_banxian_backup_site_controller@delete@id","before" => "admin_index_service@checklogin","ext_name"=>"banxian/backup"),
    "ext.banxian.backup.site.import" => array("route" => "/admin/backup/import/@id", "use" => "ext_banxian_backup_site_controller@import@id","before" => "admin_index_service@checklogin","ext_name"=>"banxian/backup"),
);