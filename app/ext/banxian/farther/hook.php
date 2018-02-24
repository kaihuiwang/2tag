<?php
zl_hook::create("arc_view",function($params){
    echo ext_banxian_favorite_site_service::service()->view($params['id']);
});