<?php
zl_hook::create("u_view",function($params){
echo ext_banxian_isonline_service::service()->showOnline($params['user_id']);
});