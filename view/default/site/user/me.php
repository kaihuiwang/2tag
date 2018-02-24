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
                <li>个人中心</li><li class="active">创建的主题</li>
            </ol>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <ul class="nav nav-pills">
                <li role="presentation" class="disabled"><a href="<?php echo url('/me'); ?>">创建的主题</a></li>
                <li role="presentation"><a href="<?php echo url('/myreply'); ?>">最近回复</a></li>
            </ul>
            </div>
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
                                <?php
                                $difftime = time()-strtotime($v['ctime']);
                                if($difftime<zl::$configApp['arc_timeout']):
                                    ?>
                                    <span ><a href="<?php echo url("/editv-".$v['id']); ?>">编辑</a></span>
                                    <span ><a href="javascript:fconfirm('确认要删除吗?',function(){location.href='<?php echo url("/deletev-".$v['id']); ?>'});">删除</a></span>
                                <?php endif;?>
                            </li>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
                <?php echo $markup ?>
        </div>        </div>

</div>