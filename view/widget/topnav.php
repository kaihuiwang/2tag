<ul class="nav navbar-left top-nav topmenu">
    <?php echo zl_hook::run("top_nav"); ?>
    <?php
    $topmenu = zl::dao("menu")->gets(array("zl_type"=>1),"zl_sort asc");
    if($topmenu):
        ?>
        <?php foreach($topmenu as $v):
        $url = stristr($v['zl_url'],'http')?$v['zl_url']:url($v['zl_url']);
        ?>
        <li>
            <a href="<?php echo $url;?>"><?php echo $v['title']; ?></a>
        </li>
    <?php
    endforeach;
    endif;
    ?>
</ul>
<?php
$user = site_user_service::service()->getLogin();
?>
    <?php if($user):
        $notices = site_user_service::service()->getNotice($user['id']);
        ?>
        <ul class="nav navbar-right top-nav topmenu">
    <li class="dropdown">
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b> <?php if($notices): ?><b style="color: RGB(255, 113, 112);font-size: 20px;">●</b><?php endif; ?></a>
        <ul class="dropdown-menu message-dropdown">
            <?php
            if($notices):
                foreach($notices as $v):
            ?>
            <li class="message-preview">
                <a href="<?php echo url($v['url']); ?>" onclick="isRead(<?php echo $v['id'];?>)">
                    <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object img-rounded" src="<?php echo showImg($v['user']['id']); ?>" >
                                    </span>

                        <div class="media-body">
                            <h5 class="media-heading"><strong><?php echo $v['user']['nickname']; ?></strong>
                            </h5>
                            <p class="small text-muted"><i class="fa fa-clock-o"></i> <?php echo qtime($v['ctime']); ?></p>
                            <?php echo htmlCut(strip_tags(viewContent($v['feed']['content'])),300);?>
                        </div>
                    </div>
                </a>
            </li>
                    <?php endforeach;?>
            <?php endif;?>
            <li class="message-footer">
                <a href="<?php echo url("/notice");?>">所有通知</a>
            </li>
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $user['nickname'];?> <b
                class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="<?php  echo url('me');?>"><i class="fa fa-fw fa-user"></i>&nbsp;&nbsp;个人中心</a></li>
            <li><a href="<?php  echo url('msg');?>"><i class="fa fa-fw fa-envelope"></i>&nbsp;&nbsp;站内留言</a></li>
            <li><a href="<?php echo url('logout'); ?>"><i class="fa fa-fw fa-power-off"></i>&nbsp;&nbsp;退出</a></li>
<!--            <li class="divider"></li>-->
<!--            <li><a href="#">BUG反馈及建议</a></li>-->
        </ul>
    </li>
</ul>
        <?php else: ?>
    <ul class="mynav navbar-right my-top-nav topmenu">
    <li>
        <a href="<?php echo url("login");?>">
            登录
        </a>
    </li>
        <li>
        <a href="<?php echo url("register");?>">
            注册
        </a>
    </li>
        <li><a href="<?php echo url("qqlogin");?>" class="openqq" title="用QQ帐号登录"> <img width="20" height="20" src="<?php echo img("/public/images/bg/qq.jpg") ?>"></a></li>
    </ul>
    <?php endif;?>
<ul class="nav navbar-right top-nav mobilemenu">
    <?php if($user):?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-align-justify"></i></a>
        <ul class="dropdown-menu">
            <?php echo zl_hook::run("top_nav"); ?>
            <?php
            $topmenu = zl::dao("menu")->gets(array("zl_type"=>1),"zl_sort asc");
            if($topmenu):
                ?>
                <?php foreach($topmenu as $v):
                $url = stristr($v['zl_url'],'http')?$v['zl_url']:url($v['zl_url']);
                ?>
                <li>
                    <a href="<?php echo $url;?>"><?php echo $v['title']; ?></a>
                </li>
            <?php
            endforeach;
            endif;
            ?>
            <li><a href="<?php  echo url('me');?>">个人中心</a></li>
            <li><a href="<?php echo url("/notice");?>">通知</a></li>
            <li><a href="<?php echo url('logout'); ?>">退出</a></li>
        </ul>
    </li>
    <?php else: ?>
    <li  class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-align-justify"></i></a>
        <ul class="dropdown-menu">
            <?php echo zl_hook::run("top_nav"); ?>
            <?php
            $topmenu = zl::dao("menu")->gets(array("zl_type"=>1),"zl_sort asc");
            if($topmenu):
                ?>
                <?php foreach($topmenu as $v):
                $url = stristr($v['zl_url'],'http')?$v['zl_url']:url($v['zl_url']);
                ?>
                <li>
                    <a href="<?php echo $url;?>"><?php echo $v['title']; ?></a>
                </li>
            <?php
            endforeach;
            endif;
            ?>
            <li><a href="<?php echo url("login");?>">登录</a></li>
            <li><a href="<?php echo url("register");?>">注册</a></li>
            <li><a href="<?php echo url("qqlogin");?>">QQ联合登陆</a></li>
        </ul>
    </li>
    <?php endif;?>
</ul>
<script>
    function isRead(noticeId){
        ajax_get("<?php echo url("/noticeRead-") ?>"+noticeId);
    }
$(function(){

    $("#tags").autocomplete({
        source: function(request, response) {
            var s = $.trim($("#tags").val());
            $.ajax({
                url: "<?php echo url("indextagsearch"); ?>/?term="+s,
                dataType: "json",
                data: {
                    featureClass: "P",
                    style: "full",
                    maxRows: 8,
                    name_startsWith: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return item;
                    }));
                }
            });
        },
        minLength: 1
    });
});
</script>
