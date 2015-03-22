<div class="col-md-2 meleft">
    <div class="panel">
        <div class="panel-body">
    <?php
    echo zl_widget::widget()->create('setting_nav');
    ?>
            </div></div>
</div>
<div class="col-md-10" >
    <div class="panel">
        <div class="panel-body">
    <ol class="breadcrumb ">
        <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
        <li class="active">站内留言</li>
        <li><a class="btn btn-link btn-sm" href="<?php echo url("addmsg");?>">+写信</a></li>
    </ol>
            </div></div>
    <div class="panel">
        <div class="panel-body">
    <ul class="media-list" id="topic_list">
        <?php if($list):?>
            <?php foreach ($list as $v):?>
                <?php if(isset($v['info']['user_receive'])):?>
                    <li id="li_<?php echo $v['id'];?>"  class="media topic-list" style="margin-bottom: 10px;">
                        <div class="pull-right">
                            <span class=" topic-comment">
                                 <?php if(!$v['msg_type']):?>
                                     <a href="<?php echo url("/msgv")."-".$v['id']."-1";?>"  style="color: #aaaaaa;font-size: 12px">共<?php echo $v['sub_count'];?>封留言</a>
                                 <?php endif;?>
                            </span>
                        </div>
                        <a class="media-left"  href="<?php echo url("u-".$user['id']);?>" target="_blank" title="<?php echo $user['nickname'];?>">
                            <img src="<?php echo showImg($user['id'],32);?>"
                                  class='img-rounded' alt="<?php echo $user['nickname'];?>">
                        </a>
                        <div class="media-body">
                            <div class="media-heading topic-list-heading" >
                                <span style="color: #aaaaaa;font-size: 12px">你发给</span>
                                <a href="<?php echo url("u-".$v['info']['user_receive']['id']);?>" target="_blank" style="color: #333;font-weight: bold">
                                    <?php echo $v['info']['user_receive']['nickname'];?>:
                                </a><span style="color: #aaaaaa;font-size: 12px"><?php echo viewContent($v['info']['content']);?></span>
                             </div>
                            <div class="text-muted">
                                <span><?php echo qtime($v['info']['ctime']);?></span>
                                <span> <a href="javascript: fconfirm('确认要删除吗?',function(){location.href='<?php echo url("/msgdelete-".$v['id']); ?>'}); "  style="font-size: 12px"  >删除</a></span>
                    <?php if(!$v['msg_type']):?>
                        <span>|</span>
                                <a href="<?php echo url("/msgv")."-".$v['id']."-1";?>"   style="font-size: 12px">回复</a>
                    <?php endif;?>
                            </div>
                        </div>

                    </li>
                <?php else:?>
                    <li id="li_<?php echo $v['id'];?>"  class="media topic-list"  style="margin-bottom: 10px;">
                        <div class="pull-right">
                            <span class=" topic-comment">
                                 <?php if(!$v['msg_type']):?>
                                     <a href="<?php echo url("/msgv")."-".$v['id']."-1";?>"  style="color: #aaaaaa;font-size: 12px">共<?php echo $v['sub_count'];?>封留言</a>
                                 <?php endif;?>
                            </span>
                        </div>
                        <a  class="media-left"  href="<?php echo url("u-".$v['info']['user_send']['id']);?>" target="_blank" title="<?php echo $v['info']['user_send']['nickname'];?>">
                            <img src="<?php echo showImg($v['info']['user_send']['id'],32);?>"
                                 class='img-rounded' alt="<?php echo $v['info']['user_send']['nickname'];?>">
                        </a>
                        <div class="media-body">
                            <div class="media-heading topic-list-heading">
                                <a href="<?php echo url("u-".$v['info']['user_send']['id']);?>" target="_blank" style="color: #333;font-weight: bold"><?php echo $v['info']['user_send']['nickname'];?></a>
                                <span style="color: #aaaaaa;font-size: 12px">发给你：</span>
                                <span style="color: #aaaaaa;font-size: 12px"><?php echo viewContent($v['info']['content']);?></span>
                            </div>
                            <div class="text-muted">
                                <span><?php echo qtime($v['info']['ctime']);?></span>
                                <span> <a href="javascript: fconfirm('确认要删除吗?',function(){location.href='<?php echo url("/msgdelete-".$v['id']); ?>'}); " >删除</a></span>
                            <span>
                                <?php if(!$v['msg_type']):?>
                                    <span class="gsplit">|</span>
                                    <a href="<?php echo url("/msgv")."-".$v['id']."-1";?>">回复</a>

                                <?php endif;?>
                            </span>
                                <?php
                                if(!$v['info']['is_read']):
                                    ?>
                                <span style="color: #FF0000">  new!</span>
                                <?php
                                endif;
                                ?>
                            </div>
                        </div>
                    </li>
                <?php endif;?>
            <?php endforeach;?>
        <?php else:?>
            <li class="media topic-list">
                无数据
            </li>
        <?php endif;?>
    </ul>
<?php echo $markup;?>
    </div>    </div>
</div>