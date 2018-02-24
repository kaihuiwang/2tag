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
                <li>个人中心</li><li class="active">最近回复</li>
            </ol>
        </div>        </div>

    <div class="panel">
        <div class="panel-heading">
        <ul class="nav nav-pills">
            <li role="presentation"><a href="<?php echo url('/me'); ?>">创建的主题</a></li>
            <li role="presentation" class="disabled"><a href="<?php echo url('/myreply'); ?>">最近回复</a></li>
        </ul>
            </div>
        <div class="panel-body">
                <ul class="ul-row-list">
                    <?php
                    if($reply):
                        foreach($reply as $v):
                            ?>
                            <li>
                                <span class="gray">回复主题›</span>
                                <a href="<?php echo url("/v-1-".$v['arc']['id']); ?>">
                                    <?php echo viewXss($v['arc']['title']); ?>
                                </a>
                                <span  class="gray"><?php echo viewContent($v['content']); ?></span>
                                <span class="gray"><?php echo qtime($v['ctime']); ?></span>
                            </li>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            <?php echo $markup ?>
        </div>
        </div>

</div>