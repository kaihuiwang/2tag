<form id="form-login" class="form-signin" action="<?php echo url('/qqregister'); ?>" method="post">
    <h2 class="form-signin-heading">
        注册
    </h2>
    <?php if($userInfo['figureurl_2']):?>
    <div class="margin-top-10">
        <label>头像</label>
        <div class="gform-box margin-bottom-10">
            <img src="<?php echo $userInfo['figureurl_2'];?>" width="64" height="64">
        </div>
        <?php endif;?>
    <input type="text" class="input-block-level" data-rule="邮箱: required;email;remote[<?php echo url('/check_unique_email'); ?>]" name="email" placeholder="邮箱">
    <input type="text" class="input-block-level" data-rule="花名: required;nickname;remote[<?php echo url('/check_unique_nickname'); ?>]" name="nickname" placeholder="花名">
    <input type="password" class="input-block-level" data-rule="密码: required;pwd" name="pwd" placeholder="密码">
    <input type="password" class="input-block-level"  data-rule="确认密码: required;match(pwd);"name="repwd" placeholder="确认密码">

    <div style="margin-top:10px;">
        <button class="btn btn-large btn-primary input-block-level" type="submit">提交</button>
    </div>
</form>