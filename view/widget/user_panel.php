<?php
$user = site_user_service::service()->getLogin();
if($user):?>
<div class="panel">
<div class="panel-body">
    <img src="<?php echo showImg($user['id'],48); ?>" class="tip img-rounded" class="img-rounded tip" data-toggle="tooltip" data-placement="top" title="<?php echo $user['nickname'] ?>" >
    <span class="margin-right-10 margin-left-10">主题数:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user['arc_number'] ?></span>
    <span class="margin-right-10">回复数:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user['reply_number'] ?></span>
</div>
</div>
<?php else:?>
<?php endif;?>
