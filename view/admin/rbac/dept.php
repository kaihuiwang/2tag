<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">部门查看</span>
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="tree" style="border: none;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <?php
        echo zl_widget::create("search",$search);
        ?>
        <div class="btn-group" style="margin-bottom: 10px;">
            <a href="<?php echo url("/admin/dept/add/".$pid);?>" class="btn btn-info btn-xs">&nbsp;&nbsp;添加部门</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>部门名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($list as $v):
                ?>
                <tr>
                    <td><?php echo $v['id'];?></td>
                    <td><?php echo $v['name'];?></td>
                    <td>
                        <a href="<?php echo url("admin/dept/edit/".$v['id']); ?>" class="btn btn-info btn-xs"><li class="glyphicon glyphicon-pencil"></li></a>
                        <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("admin/dept/delete/".$v['id']); ?>'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>
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
        $("#pid").val(node.id);
        $("#search_form").submit();
    });
</script>
