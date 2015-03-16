<?php
zl_hook::create("admin_nav",function($params){
    if(admin_rbac_service::service()->checkRight("ext.banxian.backup.site")){
        echo "<a href=\"".url("/admin/backup/index")."\" class=\"list-group-item  child ".adminActive('ext.banxian.backup.site')."\">备份管理</a>";
    }
});