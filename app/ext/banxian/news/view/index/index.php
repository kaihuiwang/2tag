<div class="col-md-8">
    <div class="panel">
    <div class="panel-heading">
        <ul class="nav nav-pills">
            <li role="presentation"><a   href="<?php echo url('/news'); ?>">最热</a></li>
            <li role="presentation"><a   href="<?php echo url('/news/1-1'); ?>">最新</a></li>
            <li role="presentation"> <a href="javascript:showModel('<?php echo url('/news/add'); ?>','提交');" style="color:#5cb85c">+提交</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <?php
        if($list):
        ?>
        <ul class="media-list" id="topic_list">
            <?php foreach($list as $k=>$v): ?>
            <li class="media topic-list">
                <div class="pull-right">
                    <span class="badge badge-info topic-comment"><a href="<?php echo url('/news/v-1-'.$v['id']); ?>"><?php echo $v['reply_count']; ?></a></span>
                </div>
                <a class="media-left" href="<?php echo url("/u-".$v['uid']);?>">
                    <img class="img-rounded medium" src="<?php echo showImg($v['uid']); ?>">
                </a>
                <div class="media-body">
                    <div class="media-heading topic-list-heading">
                        <?php
                            $color = "font-size:14px;color:#666";
                            $class = "class=\"glyphicon glyphicon-arrow-up\"";
                            if($v['num']==1 && $type==0){
                                $class = "class=\"glyphicon glyphicon-tower\"";
                                $color="font-size:18px;color:RGB(218,178,115);font";
                            } elseif($v['num']==2 && $type==0){
                                $class = "class=\"glyphicon glyphicon-tower\"";
                            $color="font-size:18px;color:RGB(220,220,220)";
                            }
                            elseif($v['num']==3 && $type==0){
                                $class = "class=\"glyphicon glyphicon-tower\"";
                            $color="font-size:18px;color:RGB(191,173,111)";
                        }
                        ?>
                        <a href="<?php echo url("/news/dogood-".$v['id']); ?>" <?php echo $class; ?>  style="<?php echo $color; ?>"></a>
                        <a href="<?php echo $v['url']; ?>" target="_blank" class="font-14" style="color: #333;font-weight:blod"><?php echo viewXss($v['title']) ?></a>
                    </div>
                    <p class="text-muted">
                        <?php  echo strip_tags(viewXss(substrtxt($v['content'],"150"))) ?>
                    </p>
                    <p class="text-muted">
                        &nbsp;
                        <span><a href="<?php echo url("/u-".$v['uid']);?>"><?php echo $v['user']['nickname']; ?></a></span>&nbsp;
                        <span><?php echo qtime($v['ctime']); ?>提交</span>&nbsp;
                        <?php if($v['last_reply_user']):?>
                            •&nbsp;
                            <span>最后回复来自 <a href="<?php echo url("/u-".$v['last_reply_uid']);?>"><?php echo $v['last_reply_user']['nickname']; ?></a></span>
                        <?php endif;?>
                        •&nbsp;
                        <span>来自<?php echo $v['domain'];?></span>
                        •&nbsp;
                        <span>收到<?php echo $v['good_number']; ?>个赞</span>
                        •&nbsp;
                        <span>
                            <a href="<?php echo url('/news/v-1-'.$v['id']); ?>" class="glyphicon glyphicon-search" title="预览"></a>
                        </span>
                    </p>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
            <?php echo $markup;?>
        <?php
        endif;
        ?>
    </div>
    </div>
</div>