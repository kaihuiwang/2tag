<div class="col-md-12">
    <div class="panel">
        <div class="panel-body">
            <ul class="breadcrumb">
                <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
                <li><a href="<?php echo url('/news');?>">掘金</a></li>
                <li><a href="javascript:history.back();" class="glyphicon glyphicon-arrow-left" title="返回"></a></li>
            </ul>
            <div class="header">
                <div class="fr">
                    <a href="<?php echo url("/u-".$user['id']); ?>">
                        <img src="<?php echo showImg($user['id'],48); ?>" class="avatar img-rounded tip"  data-toggle="tooltip" data-placement="top" title="<?php echo $user['nickname'] ?>">
                    </a>
                </div>
                <h1 style="font-size: 16px;padding-left: 10px;"><?php echo viewXss($arc['title']);?></h1>
                <div class="height-30 padding-left-10">
                    <small class="gray">
                        <a href="<?php echo url("/u-".$user['id']); ?>"><?php echo $user['nickname'] ?></a> · <?php echo qtime($arc['ctime']);?> · <?php echo $arc['view_count'];?> 次点击 &nbsp;
                    </small>
                </div>
            </div>
            <div>
                <div class="topic_content">
                    <?php
                    echo viewContent($arc['content']);
                    ?>
                    <div class="gray font-10 margin-top-10 margin-bottom-10">
                        原文链接：<a href="<?php echo $arc['url']; ?>" target="_blank"><?php echo $arc['url']; ?></a>
                    </div>
                    <div class="center">
                        <a href="<?php echo url("/news/dogood-".$arc['id']); ?>" class="btn btn-default btn-sm  btn-c" >顶&nbsp;&nbsp;<?php echo $arc['good_number']?$arc['good_number']:""; ?></a>
                    </div>

                </div>
            </div>
            <div  id="reply">
                <div class="height-30 border-bottom font-12  padding-left-10">
            <span class="gray">
                    <?php echo $arc['reply_count'];?> 回复 &nbsp;
                <?php if($arc['last_reply_uid']):?>
                    <strong class="snow">|</strong> &nbsp;直到 <?php echo qtime($arc['last_reply_time']);?>
                <?php endif;?>
                <?php if($prearc): ?><a href="<?php echo url("/news/v-1-".$prearc['id']); ?>">上一篇</a>&nbsp;·&nbsp;<?php endif; ?><?php if($nextarc): ?><a href="<?php echo url("/news/v-1-".$nextarc['id']); ?>">下一篇</a><?php endif; ?>
         </span>
                </div>
                <?php if($list): ?>
                    <?php foreach($list as $v): ?>
                        <div  class="cell" id="reply_content_<?php echo $v['id'];?>">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tbody><tr>
                                    <td width="48" valign="top" align="center">
                                        <img src="<?php echo showImg($v['uid']); ?>" class="avatar img-rounded" border="0" align="default">
                                    </td>
                                    <td width="10" valign="top"></td>
                                    <td width="auto" valign="top" align="left">
                                        <div class="fr">
                                            <div class="thank_area">
                                            </div> &nbsp;
                                            <a href="javascript:" onclick="replyOne('<?php echo $v['user']['nickname']; ?>');" title="回复">
                                                <li class="glyphicon glyphicon-share-alt"></li>
                                            </a>
                                            <?php
                                            if($loginuser['id'] == $v['user']['id']):
                                                ?>
                                                <a href="javascript:fconfirm('确认删除吗?',function(){location.href='<?php echo url("delReply/".$v['id']."/".$arc['id']); ?>'});" title="删除">
                                                    <li class="glyphicon glyphicon-remove color-red"></li>
                                                </a>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                        <div class="sep3"></div>
                                        <strong><a href="<?php echo url("/u-".$v['uid']); ?>" class="dark font-12"><?php echo $v['user']['nickname']; ?></a>
                                        </strong>&nbsp; &nbsp;<span class="gray small"><?php echo qtime($v['ctime']); ?></span>
                                        <div class="sep5"></div>
                                        <div class="reply_content">
                                            <?php echo viewContent($v['content']); ?>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>

                <?php echo $markup;?>
                <?php if($loginuser): ?>
                    <div class="cell"><div class="fr"><a href="#"><strong>↑</strong> 回到顶部</a></div>
                        添加一条新回复
                    </div>
                    <div class="cell">
                        <form method="post" action="<?php echo url("/reply");?>" id="reply_form">
                            <?php
                            echo zl_form::getCsrf();
                            ?>
                            <textarea name="content" maxlength="10000" class="mll" id="reply_content"
                                      style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 112px;width:100%;padding: 10px;"
                                      data-rule="回复:required;content;;length[~10000]"  data-target="#msg"
                                ></textarea>
                            <div class="sep10"></div>
                            <div class="fr"><div class="sep5"></div><span class="gray">请尽量让自己的回复能够对别人有帮助,回复支持markdown语法</span></div>
                            <input type="hidden" name="id" value="<?php echo $id;?>">
                            <input type="hidden" name="type" value="1">
                            <input type="submit" value="回复" class="super normal button">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="msg-box m-top" id="msg"></span>
                        </form>
                    </div>
                <?php else: ?>
                    <div style="margin: 10px;">
                        回复需要
                        <a href="<?php echo url("login");?>">
                            登录
                        </a>
                        |
                        <a href="<?php echo url("register");?>">
                            注册
                        </a>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<script>
    function replyOne(nickname){
        var txt = $("#reply_content").val();
        if(txt) txt += "\r\n";
        txt += "@"+nickname+" \r\n";
        $("#reply_content").val(txt);
        $("#reply_content").focus();
    }
</script>