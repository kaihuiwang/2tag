<?php
zl_hook::create("system_start",function(){
   ext_banxian_install_site_service::service()->checkInstall();
});