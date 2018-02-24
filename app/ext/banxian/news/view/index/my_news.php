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
                <li class="active">掘金</li>
            </ol>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body">
            <ul class="ul-row-list">
                <?php
                if($list):
                    foreach($list as $v):
                        ?>
                        <li>
                            <a href="<?php echo url('/news/v-1-'.$v['id']); ?>" >
                                <?php echo viewXss($v['title']); ?>
                            </a>
                            <span class="gray"><?php echo qtime($v['ctime']); ?></span>
                            <span >
                                <a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("/delete_news/".$v['id']); ?>'});">删除</a>
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