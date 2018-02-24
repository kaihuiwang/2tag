<div class="col-md-2 meleft">
    <div class="panel">
        <div class="panel-body">
            <?php
            echo zl_widget::widget()->create('setting_nav');
            ?>
        </div></div>
</div>
<div class="col-md-10">
    <div class="panel">
        <div class="panel-body">
            <ol class="breadcrumb">
                <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
                <li class="active">我的专题</li>
            </ol>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body">
            <ul class="ul-row-list">
                <?php
                if($zhuanti):
                    foreach($zhuanti as $v):
                        ?>
                        <li>
                            <a href="<?php echo url("/zhuanti-1-".$v['name']); ?>" >
                                <?php echo viewXss($v['name']); ?>
                            </a>
                            <span class="gray"><?php echo qtime($v['ctime']); ?></span>
                            <span >
<a href="javascript:showModel('<?php echo url("edit_zhuanti/".$v['id']); ?>','编辑专题');" class=" label label-tag showmodel tip" style="font-size: 14px;color: #999; border: dashed 1px #ddd;padding: 0px 5px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="点击编辑专题">编辑</a>
                            </span>
                            <span >
                                <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("/delete_zhuanti/".$v['id']); ?>'});">删除</a>
                            </span>
                        </li>
                    <?php
                    endforeach;
                    ?>
                <?php else:?>
                    <li>没有相关数据</li>
                <?php endif;?>
            </ul>
            <?php echo $markup ?>
        </div>
    </div>
</div>