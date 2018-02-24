<?php
$user = admin_user_service::service()->getLogin();
$current_route_name = zl::get("current_route_name");
?>
<input type="hidden" id="currouter" value="<?php echo $current_route_name; ?>">
<nav id="navbar" class="navbar navbar-inverse navbar-fixed-left navbar-collapsed" role="navigation">
    <div class="navbar-header">
        <div class="dropdown" style="height: 30px;padding-left: 10px;margin-top: 10px;">
            <a data-toggle="dropdown" style="color: #fff; text-decoration: none;" href="javascript:;">
                <i class="icon-smile"></i> 欢迎&nbsp;&nbsp;
                <span class="label label-badge label-info"><?php echo $user['nickname']; ?></span>
                <span class="icon-cog"></span>
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li>
                    <a href="#"><span class="icon-pencil"></span>&nbsp;&nbsp;修改密码</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo url('admin/logout'); ?>"><span class="icon-off"></span>&nbsp;&nbsp;退出</a>
                </li>
            </ul>
        </div>

    </div>
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav collapsed" data-tab="member">
            <li class="nav-heading"><i class="icon-user"></i>&nbsp;&nbsp;用户管理</li>
            <li id="admin_member_mlist"><a href="#">会员管理</a></li>
            <li><a href="#">角色管理</a></li>
            <li><a href="#">权限管理</a></li>
            <li><a href="#">修改密码</a></li>
        </ul>
        <ul class="nav navbar-nav collapsed" data-tab="basic">
            <li class="nav-heading"><i class="icon-info"></i>&nbsp;&nbsp;其他</li>
            <li><a href="http://zui.sexy/" target='_blank'>zui帮助文档</a></li>
        </ul>
    </div>
</nav>