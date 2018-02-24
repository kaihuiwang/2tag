<div class="col-md-12">
    <div class="panel">
        <div class="panel-heading">全部标签(总数<?php echo isset($sitedata['tag_count'])?$sitedata['tag_count']:0; ?>)</div>
<div class="panel-body">
        <?php if($list): ?>
        <ul class="ul-row">
            <?php foreach($list as $v):?>
                <li class="margin-right-10 margin-top-10" style="background-color: #f4f4f4;border:#ddd 1px solid"> <a href="<?php echo url("/t-1-".$v['name']) ?>"
                         class=" label label-tag label-default  "
                         style="background-color: #f4f4f4;"
                        ><?php echo $v['name'];?></a><span class="badge"><?php echo $v['zl_count'];?></span></li>
            <?php endforeach;?>
            </ul>
    <?php  endif;?>
    <div class="clear">
        <?php echo $markup; ?>
    </div>
</div>
</div>
</div>