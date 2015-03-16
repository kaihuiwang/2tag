<div class="col-md-8">
    <div class="panel">
        <div class="panel-body">
            <h4><a href="javascript:;"  class="my_tag"><?php echo $taginfo['name'];?></a></h4>
            <p class="gray" style="border-bottom: 1px solid #ddd">
                创建人:<a href="<?php echo url("/u-".$user['id']); ?>"><?php echo $user['nickname'] ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo qtime($taginfo['ctime']) ?>
                <?php echo zl_hook::run("tag_view",array("tag_id"=>$taginfo['id'])); ?>
            </p>
            <ul class="media-list clear" id="topic_list">
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
                <?php endif; ?>
            </ul>
            <?php echo $markup;?>
        </div></div>
</div>
<div class="col-md-4">
    <?php
    echo zl_widget::widget()->create("user_panel");
    ?>
    <div class="panel">
        <div class="panel-heading">标签</div>
        <div class="panel-body">
        <ul class="ul-row">
            <?php if($alltags): ?>
                <?php foreach($alltags as $v):?>
                    <li > <a href="<?php echo url("/t-1-".$v['name']) ?>" class=" label label-tag "><?php echo $v['name'];?></a></li>
                <?php endforeach;?>
            <?php endif;?>
        </ul>
</div></div>
</div>