<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">公告查看</span>
    </div>
    <div class="col-lg-10" style="margin-top: 10px;">
                <div class="btn-group" style="margin-bottom: 10px;">
                    <a href="<?php echo url("/admin/notice/add");?>" class="btn btn-info btn-xs">&nbsp;&nbsp;添加公告</a>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>标题</th>
                        <th>显示时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($list):
                        foreach($list as $v):
                        ?>
                    <tr>
                        <td><?php echo $v['id']; ?></td>
                        <td><?php echo viewXss($v['title']); ?></td>
                        <td><?php echo $v['show_time']; ?></td>
                        <td>
        <a href="<?php echo url("admin/notice/edit/".$v['id']); ?>" class="btn btn-info btn-xs"><li class="glyphicon glyphicon-pencil"></li></a>
        <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("admin/notice/delete/".$v['id']); ?>'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>
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
