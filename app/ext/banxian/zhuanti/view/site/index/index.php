<div class="col-md-8">
    <div class="panel">
        <div class="panel-heading fl">
            <ul class="ul-row">
                <?php if($zhuanti):?>
                    <?php foreach($zhuanti as $k=>$v):?>
                        <?php if($z == $k):?>
                            <li > <a href="<?php echo url("/zhuanti-1-".$k);?>" class=" label label-tag label-tag-active " style="color:#fff"><?php echo $k; ?></a></li>
                        <?php else: ?>
                            <li > <a href="<?php echo url("/zhuanti-1-".$k);?>" class=" label label-tag "><?php echo $k; ?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif; ?>
                <li><a  href="javascript:showModel('<?php echo url('add_zhuanti'); ?>','添加专题');" class=" label label-tag showmodel tip" style="font-size: 14px;color: #999; border: dashed 1px #ddd;padding: 0px 5px;"
                        data-toggle="tooltip" data-placement="top" title="点击添加专题"
                        >+</a></li>
            </ul>
        </div>
        <div class="panel-body  clear">
            <ul class="media-list" id="topic_list">
                <?php if($list):?>
                    <?php foreach($list as $v):?>
                        <li class="media topic-list">
                            <div class="pull-right">
                                <span class="badge badge-info topic-comment"><a href="<?php echo url("/v-1-".$v['id']);?>#reply"><?php echo $v['reply_count']; ?></a></span>
                            </div>
                            <a class="media-left" href="<?php echo url("/u-".$v['uid']);?>">
                                <img class="img-rounded medium" src="<?php echo showImg($v['uid']); ?>">
                            </a>
                            <div class="media-body">
                                <h2 class="media-heading topic-list-heading"><a href="<?php echo url("/v-1-".$v['id']);?>"><?php echo viewXss($v['title']) ?></a></h2>
                                <p class="text-muted">
                                    &nbsp;
                                    <span><a href="<?php echo url("/u-".$v['uid']);?>"><?php echo $v['user']['nickname'] ?></a></span>&nbsp;
                                    <span><?php echo qtime($v['ctime']); ?></span>&nbsp;
                                    <?php if($v['last_reply_uid']):?>
                                        •&nbsp;
                                        <span>最后回复来自 <a href="<?php echo url("/u-".$v['last_reply_uid']);?>"><?php echo $v['last_reply_user']['nickname'] ?></a></span>
                                    <?php endif;?>
                                    <?php
                                    foreach($v['tags'] as $tv):
                                        ?>
                                        •&nbsp;<span><a class=" label label-tag label-default tag-gray" href="<?php echo url('/t-1-'.$tv['name']);?>"><?php echo $tv['name']; ?></a></span>
                                    <?php endforeach;?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach;?>
                    <?php else:?>
                <li class="media topic-list">
                    无数据
                </li>
                <?php endif; ?>
            </ul>
            <?php echo $markup;?>
        </div>
        </div>
</div>
<div class="col-md-4">
    <?php
    echo zl_widget::widget()->create("user_panel");
    ?>
    <div class="panel">
        <div class="panel-heading">相关标签</div>
        <div class="panel-body">
        <ul class="ul-row">
            <?php if($tags): ?>
                <?php foreach($tags as $v):?>
                    <li class="ext_tag" data-id="<?php echo $v['zhuanti_tag_id']; ?>">
                        <a  href="<?php echo url("/t-1-".$v['name']) ?>" class=" label label-tag "><?php echo $v['name'];?>
                            <span class="badge"><?php echo $v['zl_count'];?></span>
                        </a>
                        <lable id="lable_<?php echo $v['zhuanti_tag_id']; ?>" class="glyphicon glyphicon-remove font-10 hidden" style="color:#FF0000;cursor: pointer;margin-left: -10px;padding: 0px;"
                               onclick="fconfirm('确认要删除吗?',function(){location.href='<?php echo url("/zhuanti_tagdelete/".$isAll."-".$v['zhuanti_tag_id']); ?>'});"
                            ></lable>
                    </li>
                <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>    </div>
</div>
<script>
    $(function(){
        $(".ext_tag").each(function(){
            $(this).hover(
                function(){
                    var id = $(this).attr("data-id");
                    $("#lable_"+id).removeClass("hidden");
                    $("#lable_"+id).addClass("show_inline");
                },function(){
                    var id = $(this).attr("data-id");
                    $("#lable_"+id).removeClass("show_inline");
                    $("#lable_"+id).addClass("hidden");
                }
            );
        });
    });
</script>