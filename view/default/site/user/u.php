<div class="col-md-12">
    <div class="panel">
        <div class="panel-body">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody><tr>
                <td width="90" valign="top" align="center" style="padding: 10px;">
                    <img src="<?php echo showImg($user['id'],'64');?>" class="avatar img-rounded"  border="0" align="default">

                </td>
                <td width="10"></td>
                <td width="auto" valign="top" align="left">
                    <div class="fr">
                    </div>
                    <h1 style="margin-bottom: 5px;"><?php echo $user['nickname'] ;?></h1>
                    <div class="sep10"></div>
                    <span class="gray"><li class="fa fa-time"></li> &nbsp; 本站 第 <?php echo $user['id'] ;?> 号会员，
                        加入于 <?php echo $user['ctime'] ;?></span>
                    <?php echo zl_hook::run("u_view",array("user_id"=>$user['id'])); ?>
                </td>
            </tr>
            </tbody>
        </table>
</div></div>
    <div class="panel">
            <div class="panel-heading">最近创建的主题</div>
        <div class="panel-body">
                <ul class="ul-row-list">
                    <?php
                    if($arc):
                        foreach($arc as $v):
                    ?>
                    <li>
                        <a href="<?php echo url("/v-1-".$v['id']); ?>" >
                            <?php echo viewXss($v['title']); ?>
                        </a>
                        <span class="gray"><?php echo qtime($v['ctime']); ?></span>
                    </li>
                    <?php
                    endforeach;
                    endif;
                    ?>
                </ul>
 </div></div>
    <div class="panel">
        <div class="panel-heading">最近回复</div>
        <div class="panel-body">
            <ul class="ul-row-list">
                <?php
                if($reply):
                    foreach($reply as $v):
                        ?>
                        <li>
                            <span class="gray">回复主题</span>
                            <a href="<?php echo url("/v-1-".$v['arc']['id']); ?>">
                                <?php echo viewXss($v['arc']['title']); ?>
                            </a>
                            <span  class="gray"><?php echo viewContent($v['content']); ?></span>
                            <span class="gray"><?php echo qtime($v['ctime']); ?></span>
                        </li>
                    <?php
                    endforeach;
                endif;
                ?>
            </ul>
    </div>    </div>
</div>
