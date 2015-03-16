<?php
zl_hook::create("route_match",function(){
    admin_rbac_service::service()->check();
});
zl_hook::create("job",function(){
   site_index_service::service()->scoreJob();
});
zl_hook::create("route_match",function(){
    site_user_service::service()->setOnlineUser();
});
zl_hook::create("route_match",function(){
    site_user_service::service()->autologin();
});
zl_hook::create("arc_view",function($param){
echo site_index_service::service()->setTop($param['id']);
});
zl_hook::create("top_nav",function(){
    echo "<li><a href=\"".url('add')."\" style=\"color:#5cb85c\">+新主题</a></li>";
});
zl_hook::create("footer",function(){
    echo ' <script>
                    var _hmt = _hmt || [];
                    (function() {
                        var hm = document.createElement("script");
                        hm.src = "//hm.baidu.com/hm.js?88a98bba5f12bfc39c2e3ddc157076d1";
                        var s = document.getElementsByTagName("script")[0];
                        s.parentNode.insertBefore(hm, s);
                    })();
                </script>';
});