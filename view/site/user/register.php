<form id="form-login" class="form-signin" action="<?php echo url('/register'); ?>" method="post">
        <?php echo zl_form::getCsrf(); ?>
        <h2 class="form-signin-heading">
            注册
        </h2>
        <input type="text" class="input-block-level" data-rule="邮箱: required;email;remote[<?php echo url('/check_unique_email'); ?>]" name="email" placeholder="邮箱">
        <input type="text" class="input-block-level" data-rule="花名: required;nickname;remote[<?php echo url('/check_unique_nickname'); ?>]" name="nickname" placeholder="花名">
        <input type="password" class="input-block-level" data-rule="密码: required;pwd" name="pwd" placeholder="密码">
        <input type="password" class="input-block-level"  data-rule="确认密码: required;match(pwd);"name="repwd" placeholder="确认密码">
        <input class="input-block-level" type="text" name="captcha" id="captcha"
               placeholder="验证码"
               data-rule="required;captcha;remote[<?php echo url('/checkcaptcha/register'); ?>]"
            >
        <img src="<?php echo url("/captcha/login"); ?>" id="captcha_img" class="captcha" title="点击刷新"
             style="display: inline-table">
        <a href="javascript:;" class="captcha small"><i class="glyphicon glyphicon-refresh"></i></a>

        <div style="margin-top:10px;">
            <button class="btn btn-large btn-primary input-block-level" type="submit">提交</button>
            <div style="float: right">
                <a href="<?php echo url("login");?>">
                    登录
                </a>
            </div>
        </div>
    </form>
<script>
    $(function () {
        $(".captcha").each(function () {
            $(this).click(function () {
                $("#captcha_img").attr("src", "<?php echo url("/captcha/register"); ?>" + "?" + parseInt(100000 * Math.random()))
            });
        })
    });
</script>