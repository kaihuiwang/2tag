<?php
function adminActive($activeName){
    $routeName = zl::get("current_route_name");
    if(strstr($routeName,$activeName)){
        return " active ";
    }
    return "";
}
?>
<div class="list-group menup">
    <dl>
        <dd>
            <a href="javascript:;" class="list-group-item header">
                <li class="glyphicon glyphicon-th-large"></li>
                &nbsp;&nbsp;网站管理
                <li class="badge"><span class="glyphicon glyphicon-chevron-up"></span></li>
            </a>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.index.notice")):
                ?>
                <a href="<?php echo url("/admin/notice");?>" class="list-group-item  child <?php echo adminActive('admin.index.notice'); ?>">公告管理</a>
            <?php endif;?>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.site.menu")):
                ?>
                <a href="<?php echo url("/admin/menu");?>" class="list-group-item  child <?php echo adminActive('admin.site.menu'); ?>">导航管理</a>
            <?php endif;?>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.site.arc")):
                ?>
                <a href="<?php echo url("/admin/arc");?>" class="list-group-item  child <?php echo adminActive('admin.site.arc'); ?>">主题管理</a>
            <?php endif;?>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.site.user")):
                ?>
                <a href="<?php echo url("/admin/user");?>" class="list-group-item  child <?php echo adminActive('admin.site.user'); ?>">会员管理</a>
            <?php endif;?>
        </dd>
        <dd>
            <a href="javascript:;" class="list-group-item header">
                <li class="glyphicon glyphicon-user"></li>
                &nbsp;&nbsp;组织管理
                <li class="badge"><span class="glyphicon glyphicon-chevron-up"></span></li>
            </a>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.rbac.member")):
            ?>
            <a href="<?php echo url("/admin/member");?>" class="list-group-item  child <?php echo adminActive('admin.rbac.member'); ?>">用户管理</a>
            <?php endif;?>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.rbac.node")):
            ?>
            <a href="<?php echo url("/admin/node");?>" class="list-group-item  child <?php echo adminActive('admin.rbac.node'); ?>">节点管理</a>
            <?php endif;?>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.rbac.dept")):
            ?>
            <a href="<?php echo url("/admin/dept");?>" class="list-group-item  child <?php echo adminActive('admin.rbac.dept'); ?>">部门管理</a>
            <?php endif;?>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.rbac.access")):
            ?>
            <a href="<?php echo url("/admin/access");?>" class="list-group-item  child <?php echo adminActive('admin.rbac.access'); ?>">权限/职位管理</a>
            <?php endif;?>
        </dd>
        <dd>
            <a href="javascript:;" class="list-group-item header">
                <li class="glyphicon glyphicon-cog"></li>
                &nbsp;&nbsp;设置
                <li class="badge"><span class="glyphicon glyphicon-chevron-up"></span></li>
            </a>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.setting.index")):
                ?>
                <a href="<?php echo url("/admin/setting/index");?>" class="list-group-item  child <?php echo adminActive('admin.setting.index'); ?>">配置管理</a>
            <?php endif;?>
            <?php
            if(admin_rbac_service::service()->checkRight("admin.setting.cache")):
                ?>
                <a href="<?php echo url("/admin/setting/cache");?>" class="list-group-item  child <?php echo adminActive('admin.setting.cache'); ?>">缓存管理</a>
            <?php endif;?>
        </dd>
        <dd>
            <a href="javascript:;" class="list-group-item header">
                <li class="glyphicon glyphicon-link"></li>
                &nbsp;&nbsp;扩展
                <li class="badge"><span class="glyphicon glyphicon-chevron-up"></span></li>
            </a>
            <?php echo zl_hook::run("admin_nav"); ?>
        </dd>

    </dl>
</div>