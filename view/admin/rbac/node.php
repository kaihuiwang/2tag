<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">节点查看</span>
    </div>
</div>
<div class="row" style="margin: 10px;">
    <div class="panel panel-default">
        <div class="panel-body">
    <?php
        if($nodes):
            foreach($nodes as $k=>$v):
    ?>
                <h5><span ><?php echo $k; ?></span></h5>
                <div>
                    <?php
                    foreach($v as $v1):
                        list($tag_name,$name) = $v1;
                    ?>
                        <span class="label label-default tip" data-toggle="tooltip" data-placement="top" title="<?php echo $tag_name ?>"><?php echo $name ?></span>
                    <?php endforeach; ?>
                </div>
    <?php
    endforeach;
    endif;?>
</div>
    </div>
</div>