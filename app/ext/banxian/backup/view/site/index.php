<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">备份查看</span>
    </div>
    <div class="col-lg-10" style="margin-top: 10px;">
        <div class="btn-group" style="margin-bottom: 10px;">
            <a href="<?php echo url("/admin/backup/add");?>" class="btn btn-info btn-xs">&nbsp;&nbsp;添加备份</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>标题</th>
                <th>路径</th>
                <th>时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if($list):
                foreach($list as $v):
                    ?>
                    <tr>
                        <td><?php echo $v['id']; ?></td>
                        <td><?php echo $v['title']; ?></td>
                        <td><?php echo $v['path']; ?></td>
                        <td>
                            <?php echo $v['ctime']; ?>
                        </td>
                        <td>
                            <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("admin/backup/delete/".$v['id']); ?>'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>
                            <a href="javascript:fconfirm('导入将删除当前数据库数据,确认要导入吗?',function(){location.href='<?php echo url("admin/backup/import/".$v['id']); ?>'});" class="btn btn-xs"><li class="glyphicon glyphicon-open"></li></a>
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