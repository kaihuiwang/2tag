<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">主题查看</span>
    </div>
    <div class="col-lg-10" style="margin-top: 10px;">
        <?php
        echo zl_widget::create("search",$search);
        ?>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>标题</th>
                <th>访问/回复</th>
                <th>是否屏蔽</th>
                <th>标签</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if($list):
                foreach($list as $v):
                    ?>
                    <tr>
                        <td><?php echo $v['id']; ?></td>
                        <td><a href="<?php echo url("v-1-".$v['id']);?>" target="_blank"><?php echo viewXss($v['title']); ?></a></td>
                        <td><?php echo $v['view_count']; ?>/<?php echo $v['reply_count']; ?></td>
                        <td><?php echo $v['is_publish']?"否":"是"; ?></td>
                        <th>
                        <?php if($v['tag_name']):?>
                            <?php foreach($v['tag_name'] as $vk):?>
                            <a href="<?php echo url("/t-1-".$vk) ?>"  target="_blank"><span class="badge"><?php echo $vk; ?></span></a>
                            <?php endforeach;?>
                        <?php endif;?>
                        </th>
                        <td>
                            <a href="javascript:fconfirm('确认要屏蔽吗?',function(){location.href='<?php echo url("admin/arc_delete/".$v['id']); ?>'});" class="btn btn-danger btn-xs"><li class="glyphicon glyphicon-remove"></li></a>
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
