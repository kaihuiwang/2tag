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
                <li class="active">我的收藏</li>
            </ol>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body">
            <ul class="ul-row-list">
                <?php
                if($arc):
                    foreach($arc as $v):
                        ?>
                        <li>
                            <a href="<?php echo url("/v-1-".$v['id']); ?>" >
                                <?php echo viewXss($v['title']); ?>
                            </a>
                            <span class="gray"><?php echo qtime($v['ctime']); ?></span>
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