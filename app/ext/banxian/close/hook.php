<?php
zl_hook::create("arc_view",function($param){
    echo ext_banxian_close_site_service::service()->setClose($param['id']);
});