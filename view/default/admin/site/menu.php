<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">导航查看</span>
    </div>
    <div class="col-lg-10" style="margin-top: 10px;">
        <div class="btn-group" style="margin-bottom: 10px;">
            <a href="<?php echo url("/admin/menu_add");?>" class="btn btn-info btn-xs">&nbsp;&nbsp;添加导航</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>标题</th>
                <th>url</th>
                <th>类型</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if($list):
                foreach($list as $v):
                    ?>
                    <tr>
                        <td><?php echo $v['id']; ?></td>
                        <td><?php echo $v['zl_url']; ?></td>
                        <td><?php echo viewXss($v['title']); ?></td>
                        <td><?php echo $v['zl_type']==1?"顶部导航":"底部导航"; ?></td>
                        <th><?php echo viewXss($v['zl_sort']); ?></th>
                        <td>
                            <a href="<?php echo url("admin/menu_edit/".$v['id']); ?>" class="btn btn-info btn-xs"><li class="glyphicon glyphicon-pencil"></li></a>
                            <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("admin/menu_delete/".$v['id']); ?>'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>
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
