<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">用户查看</span>
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="col-lg-2">
        <div class="panel panel-default">
            <div class="panel-heading">部门结构</div>
            <div class="panel-body">
                <div id="tree" style="border: none;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-10">
<?php
echo zl_widget::create("search",$search);
?>
        <div class="btn-group" style="margin-bottom: 10px;">
            <a href="<?php echo url("/admin/member/add");?>" class="btn btn-info btn-xs">&nbsp;&nbsp;添加用户</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>姓名</th>
                <th>花名</th>
                <th>邮箱</th>
                <th>职位</th>
                <th>部门</th>
                <th>QQ</th>
                <th>性别</th>
                <th>手机号码</th>
                <th>分机号码</th>
                <th>加入日期</th>
                <th>最后登录日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($list as $v):
            ?>
            <tr>
                <td><?php echo $v['id'];?></td>
                <td><?php echo $v['real_name'];?></td>
                <td><?php echo $v['nickname'];?></td>
                <td><?php echo $v['email'];?></td>
                <td><?php echo admin_rbac_service::service()->viewRole($v['role']);?></td>
                <td><?php echo admin_rbac_service::service()->viewDept($v['dept']);?></td>
                <td><?php echo $v['qq'];?></td>
                <td><?php
                    echo admin_rbac_service::service()->viewSex($v['sex']);
                    ?></td>
                <td><?php echo $v['mobile'];?></td>
                <td><?php echo $v['tel'];?></td>
                <td><?php echo $v['join_date'];?></td>
                <td><?php echo $v['last_login_time'];?></td>
                <td>
                    <a href="<?php echo url("admin/member/edit/".$v['id']); ?>" class="btn btn-info btn-xs"><li class="glyphicon glyphicon-pencil"></li></a>
                    <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("admin/member/delete/".$v['id']); ?>'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>
                </td>
            </tr>
            <?php endforeach;?>
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
    function getTree() {
        return <?php echo $depts;?>;
    }
    $('#tree').treeview({data: getTree(),showBorder:false,borderColor:"#fff",borderColor:"#ddd",enableLinks:false,levels:10,selectedBackColor:"#333",showTags:true});
    $('#tree').on('nodeSelected', function(event, node) {
        $("#dept").val(node.id);
        $("#search_form").submit();
    });
</script>