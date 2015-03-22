<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">职位查看</span>
    </div>
    <div class="col-lg-10" style="margin-top: 10px;">
                <?php
                echo zl_widget::create("search",$search);
                ?>
                <div class="btn-group" style="margin-bottom: 10px;">
                    <a href="<?php echo url("/admin/access/add");?>" class="btn btn-info btn-xs">&nbsp;&nbsp;添加职位</a>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>职位名称</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($list):
                        foreach($list as $v):
                        ?>
                    <tr>
                        <td><?php echo $v['id']; ?></td>
                        <td><?php echo $v['name']; ?></td>
                        <td>
                            <a href="javascript:showAccess(<?php echo $v['id']; ?>);">权限</a>
                            <a href="<?php echo url("admin/access/edit/".$v['id']); ?>" class="btn btn-info btn-xs"><li class="glyphicon glyphicon-pencil"></li></a>
                            <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("admin/access/delete/".$v['id']); ?>'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">权限</h4>
            </div>
            <div class="modal-body" id="mymodelContent">

            </div>
        </div>
    </div>
</div>
<script>
    function showAccess(id){
        ajax_get("<?php echo url("/admin/access/get"); ?>/"+id,function(a){
            $("#mymodelContent").html(a);
            $('#myModal').modal({
                keyboard: true
            })
        },'text');

    }

</script>
