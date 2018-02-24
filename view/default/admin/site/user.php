<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">会员查看</span>
    </div>
    <div class="col-lg-10" style="margin-top: 10px;">
        <?php
        echo zl_widget::create("search",$search);
        ?>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>邮箱</th>
                <th>花名</th>
                <th>是否验证邮箱</th>
                <th>上次登录</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if($list):
                foreach($list as $v):
                    ?>
                    <tr>
                        <td><?php echo $v['id']; ?></td>
                        <td><a href="<?php echo url("u-".$v['id']);?>" target="_blank"><?php echo viewXss($v['email']); ?></a></td>
                        <td><?php echo $v['nickname']; ?></td>
                        <td><?php echo !$v['email_validate']?"否":"是"; ?></td>
                        <th>
                            <?php echo $v['last_login_time']; ?>
                        </th>
                        <td>
<!--                            <a href="javascript:fconfirm('确认要屏蔽吗?',function(){location.href='--><?php //echo url("admin/arc_delete/".$v['id']); ?><!--'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>-->
                            <?php
                                 $title = "设为网站管理员";
                                if($v['level']==99){
                                    $title = "取消网站管理员";
                                }
                            ?>
                            <a href="javascript:setadmin('<?php echo $v['id'];?>');"><?php echo $title; ?></a>
                        </td>
                    </tr>
                <?php
                endforeach;
            endif;?>
            <tr>
                <td colspan="100%">
                    <?php echo $markup;?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
function setadmin(uid){
    ajax_get("<?php echo url('/admin/user/setLevel'); ?>/"+uid,function(a){
        if(a.status){
            location.assign(location.href);
        }else{
            falert(a.msg);
        }
    })
}
</script>
