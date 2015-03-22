<div class="col-md-12">
    <div class="panel">
        <div class="panel-body">
    <ol class="breadcrumb">
        <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
        <li>
<span class=" label label-tag label-tag-active">公告</span>
        </li>
    </ol>
    <div class="header">
        <h1 style="font-size: 16px;padding-left: 10px;"><?php echo viewXss($info['title']);?></h1>
        &nbsp; <small class="gray"><?php echo qtime($info['show_time']);?>&nbsp; </small>
    </div>
        <div class="topic_content">
            <?php
            echo viewContent($info['content']);
            ?>
        </div>
</div>
</div>
</div>