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
    <ol class=" breadcrumb">
        <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
        <li class="active">修改密码</li>
    </ol>
            </div></div>
    <div class="panel">
            <div class="panel-heading">
        <ul class="nav nav-pills">
            <li role="presentation" ><a href="<?php echo url('/setting'); ?>">设置头像</a></li>
            <li role="presentation" class="disabled"><a href="javascript:;">修改密码</a></li>
        </ul>
                </div>
        <div class="panel-body">
        <form id="form-editpwd"  class="form-horizontal" action="<?php echo url('/editpwd'); ?>" method="post">
            <?php echo zl_form::getCsrf(); ?>
            <div class="control-group">
                <label class="control-label">原密码</label>
                <div class="controls">
                    <input type="password" class="input-block-level" data-rule="原密码: required;oldpassword" name="oldpassword" placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">新密码</label>
                <div class="controls">
                    <input type="password" class="input-block-level" data-rule="密码: required;pwd" name="pwd" placeholder="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">验证码</label>
                <div class="controls">
                    <input class="input-block-level" type="text" name="captcha" id="captcha"
                           placeholder="验证码"
                           data-rule="required;captcha;remote[<?php echo url('/checkcaptcha/editpwd'); ?>]"
                        >
                    <img src="<?php echo url("/captcha/editpwd"); ?>" id="captcha_img" class="captcha" title="点击刷新"
                         style="display: inline-table">
                    <a href="javascript:void();" class="captcha small"><i class="glyphicon glyphicon-refresh"></i></a>
                </div>
            </div>

            <div style="margin-top:10px;">
                <button class="btn btn-large btn-primary input-block-level" type="submit">提交</button>
            </div>
        </form>
        <script>
            $(function () {
                $(".captcha").each(function () {
                    $(this).click(function () {
                        $("#captcha_img").attr("src", "<?php echo url("/captcha/editpwd"); ?>" + "?" + parseInt(100000 * Math.random()))
                    });
                })
            });
        </script>
    </div>    </div>
</div>