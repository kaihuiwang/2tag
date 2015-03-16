<form id="form-login" class="form-signin" action="<?php echo url('/setpwd'); ?>" method="post">
    <?php echo zl_form::getCsrf(); ?>
    <h2 class="form-signin-heading">
        设置密码
    </h2>
    <input type="password" class="input-block-level" data-rule="密码: required;pwd" name="pwd" placeholder="密码">
    <input class="input-block-level" type="text" name="captcha" id="captcha"
           placeholder="验证码"
           data-rule="required;captcha;remote[<?php echo url('/checkcaptcha/setpwd'); ?>]"
        >
    <img src="<?php echo url("/captcha/setpwd"); ?>" id="captcha_img" class="captcha" title="点击刷新"
         style="display: inline-table">
    <a href="javascript:void();" class="captcha small"><i class="glyphicon glyphicon-refresh"></i></a>

    <div style="margin-top:10px;">
        <input type="hidden" name="token" id="token" value="<?php echo $token;?>">
        <button class="btn btn-large btn-primary input-block-level" type="submit">提交</button>
    </div>
</form>
<script>
    $(function () {
        $(".captcha").each(function () {
            $(this).click(function () {
                $("#captcha_img").attr("src", "<?php echo url("/captcha/setpwd"); ?>" + "?" + parseInt(100000 * Math.random()))
            });
        })
    });
</script>