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
    <ol class="breadcrumb ">
        <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
        <li class="active">通知</li>
    </ol>
</div></div>
    <div class="panel">
        <div class="panel-body">
    <ul class="ul-row-list">
        <?php
        if($list):
            foreach($list as $v):
                ?>
                <li class="message-preview2">
                    <a href="<?php echo url($v['url']); ?>" onclick="isRead(<?php echo $v['id'];?>)">
                        <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object img-rounded" src="<?php echo showImg($v['user']['id']); ?>" >
                                    </span>
                            <div class="media-body <?php echo $v['is_read']?"hasread":"noread"; ?>">
                                <h5 class="media-heading"><strong><?php echo $v['user']['nickname']; ?></strong></h5>
                                <p class="small text-muted"><i class="fa fa-clock-o"></i> <?php echo qtime($v['ctime']); ?></p>
                                <?php echo htmlCut(strip_tags(viewContent($v['feed']['content'])),300);?>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach;?>
        <?php else:?>
            <li class="message-preview2">
                无数据
            </li>
        <?php endif;?>
    </ul>
    <?php echo $markup;?>
    </div></div>
</div>
<script>
    function isRead(noticeId){
        ajax_get("<?php echo url("/noticeRead-") ?>"+noticeId);
    }
    $(function(){

    });
</script>