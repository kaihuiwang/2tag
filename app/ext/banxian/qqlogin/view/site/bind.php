<div class="col-md-2 meleft">
    <div class="panel">
        <div class="panel-body">
            <?php
            echo zl_widget::widget()->create('setting_nav');
            ?>
        </div></div>
</div>
<div class="col-md-10">
    <div class="panel">
        <div class="panel-body">
            <ol class=" breadcrumb">
                <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
                <li class="active">绑定QQ</li>
            </ol>
        </div></div>
    <div class="panel">
        <div class="panel-body">
            <div class=" box-top-0">
                <div class="gprefix-2 gmt20">
                    <p>
                        <span class="gbind-line"></span>
                        <?php if($isBind):?>
                            <a href="<?php echo ("qqlogin_unbind");?>" style="color: #0066cc;padding-left: 10px;" >取消绑定</a>
                        <?php else:?>
                            <a href="<?php echo ("qqlogin_bind");?>" style="color: #0066cc;padding-left: 10px;"  >绑定帐号</a>
                        <?php endif;?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>