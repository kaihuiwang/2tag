<?php
function settingMatch($route_name){
    $current_route_name = zl::get("current_route_name");
    return strstr($current_route_name,$route_name) ? " class=\"menu-selected\"":"";
}
?>
<div class="sidebar-menu">
    <ul  class="nav nav-list  menu">
        <li <?php echo settingMatch("site.user.me"); ?>><a href="<?php echo url("me");?>"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;个人中心</a></li>
        <li <?php echo settingMatch("site.user.setting"); ?>><a href="<?php echo url("setting");?>"><i class="glyphicon glyphicon-cog"></i>&nbsp;&nbsp;&nbsp;&nbsp;设置</a></li>
        <li  <?php echo settingMatch("site.index.msg"); ?>><a href="<?php echo url("msg");?>"><i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;&nbsp;&nbsp;站内留言</a></li>
        <li <?php echo settingMatch("site.index.notice"); ?>><a href="<?php echo url("notice");?>"><i class="glyphicon glyphicon-bell"></i>&nbsp;&nbsp;&nbsp;&nbsp;通知</a></li>
        <?php echo zl_hook::run("setting_nav"); ?>
    </ul>
</div>