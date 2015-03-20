<div class="col-md-8">
    <div class="panel">
        <div class="panel-heading">
                活跃
        </div>
        <div class="panel-body">
            <ul class="ul-row">
                <?php if($hotTag):?>
                    <?php foreach($hotTag as $v):?>
                        <?php if($tag == $v['name']):?>
                            <li > <a href="<?php echo url("/1-".$v['name']);?>" class=" label label-tag label-tag-active" style="color:#fff"><?php echo $v['name']; ?></a></li>
                        <?php else: ?>
                            <li > <a href="<?php echo url("/1-".$v['name']);?>" class=" label label-tag "><?php echo $v['name']; ?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
        <div class="panel">
            <div class="panel-heading">
      主题
            </div>
        <div class="panel-body">
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
                                <h2 class="media-heading topic-list-heading">
                                    <a href="<?php echo url("/v-1-".$v['id']);?>">
                                        <?php if($v['zl_type']==2):?>
                                        <span class="label label-success font-10">精华</span>
                                        <?php endif; ?>
                                        <?php echo viewXss($v['title']) ?>
                                    </a>
                                </h2>
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

        </div>
        <?php if($markup): ?>
            <div class="panel-footer">
                <?php echo $markup;?>
            </div>
        <?php endif;?>
    </div>
</div>
    <div class="col-md-4">
<?php
echo zl_widget::widget()->create("user_panel");
?>
        <div class="panel">
            <div class="panel-heading">公告</div>
            <div class="panel-body">
                <ul class="ul-row-list">
                    <?php if($sitenotice): ?>
                        <?php foreach($sitenotice as $v):?>
                            <li>
                                <span style="color:#AFAFAF;">♀</span>
                                <a href="<?php echo url("/n-".$v['id']) ?>" class="startbbs" title="<?php echo viewXss($v['title']) ?>"><?php echo substrtxt(viewXss($v['title']),"25"); ?></a>
                                <span class="fr gray" style="color:#AFAFAF;margin-left: 10px;"><?php echo qtime($v['show_time']); ?></span>
                            </li>
                        <?php endforeach;?>
                    <?php endif;?>
                </ul>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">今日主题</div>
            <div class="panel-body">
                <?php if($todayarc):?>
                    <?php foreach($todayarc as $v):?>
                        <div class="cell">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tbody>
                                <tr>
                                    <td width="24" valign="middle" align="center">
                                        <a href="<?php echo url("u-".$v['uid']); ?>"><img src="<?php echo showImg($v['uid'])?>" class="avatar img-rounded" border="0" align="default" style="max-width: 24px; max-height: 24px;"></a>
                                    </td>
                                    <td width="10"></td>
                                    <td width="auto" valign="middle">
                <span class="item_hot_topic_title">
                <a href="<?php echo url("v-1-".$v['id']); ?>"><?php echo viewXss($v["title"]);?></a>
                </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php endif;?>
            </div>
            </div>
        <div class="panel">
            <div class="panel-heading ">标签</div>
            <div class="panel-body">
                <ul class="ul-row">
                    <?php if($most): ?>
                        <?php foreach($most as $v):?>
                            <li > <a href="<?php echo url("/t-1-".$v['name']) ?>" class=" label label-tag "><?php echo $v['name'];?></a></li>
                        <?php endforeach;?>
                    <?php endif;?>
                </ul>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">社区运行状况</div>
            <div class="panel-body">
                <table  cellspacing="0" border="0" width="100%" class="table-my">
                    <tbody>
                    <tr>
                        <td width="100" align="left"><span >注册会员</span></td>
                        <td width="auto" align="left"><strong><?php echo isset($sitedata['member_count'])?$sitedata['member_count']:0; ?></strong></td>
                    </tr>
                    <tr>
                        <td width="100" align="left"><span >标签</span></td>
                        <td width="auto" align="left"><strong><?php echo isset($sitedata['tag_count'])?$sitedata['tag_count']:0; ?></strong></td>
                    </tr>
                    <tr>
                        <td width="100" align="left"><span>主题</span></td>
                        <td width="auto" align="left"><strong><?php echo isset($sitedata['arc_count'])?$sitedata['arc_count']:0; ?></strong></td>
                    </tr>
                    <tr>
                        <td width="100" align="left"><span>回复</span></td>
                        <td width="auto" align="left"><strong><?php echo isset($sitedata['reply_count'])?$sitedata['reply_count']:0; ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>