<div class="col-md-12">
    <div class="panel">
        <div class="panel-heading">活跃会员(社区会员<?php echo isset($sitedata['member_count'])?$sitedata['member_count']:0; ?>)</div>
        <div class="panel-body">
            <?php if($member): ?>
                <?php foreach($member as $v): ?>
                <div class="col-md-1 remove-padding-right">
                    <p>
                        <a href="<?php echo url("/u-".$v['id']); ?>"><img src="<?php echo showImg($v['id'],48) ?>" class="img-rounded tip" data-toggle="tooltip" data-placement="top"
                                                                          title="<?php echo $v['nickname'] ?>(主题数<?php echo $v['arc_number'] ?>)" ></a>
                    </p>
                </div>
                <?php  endforeach;?>
            <?php  endif;?>
        </div>
    </div>
</div>