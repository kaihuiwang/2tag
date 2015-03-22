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
    <ol class="breadcrumb ">
        <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
        <li><a href="<?php echo url('msg');?>">站内留言</a></li>
        <li class="active">你和"<?php echo $ouser['nickname'];?>"的对话</li>
    </ol>
            </div></div>
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal" action="<?php echo url("msgv"); ?>" method="post">
                <?php echo zl_form::getCsrf(); ?>
                <div class="control-group">
                    <label class="control-label">正文</label>
                    <label class="control-label" style="font-weight: normal;color: #aaa">1000字以内</label>
                    <div class="controls">
                        <textarea   name="content" id="content" data-rule="正文:required;content;;length[~1000]"  rows="5"  style="width: 100%;"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                    </label>
                    <div class="controls">
                        <input type="hidden" name="receiver" value="<?php echo $ouser['nickname'];?>">
                        <input type="hidden" name="id" value="<?php echo $id;?>">
                        <button class="btn btn-default" type="button" id="preview">预览</button>
                        <button class="btn btn-success" type="submit">提交</button>
                    </div>
                </div>
            </form>
            <div class="row" id="previewdiv"></div>
    </div></div>
    <div class="panel">
        <div class="panel-body">
<ul class="media-list" id="topic_list">
    <?php if($list):?>
        <?php foreach ($list as $v):?>
                <li id="li_<?php echo $v['id'];?>"  class="media topic-list" style="margin-bottom: 10px;">
                    <div class="media-body margin-left-10">
                        <div class="media-heading topic-list-heading" >
                        <?php if($v['is_send']):  ?>
                            <div class="row padding-left-10" >
                            <span style="color: #aaaaaa;font-size: 12px">你发给</span>
                            <a href="<?php echo url("u-".$ouser['id']);?>" target="_blank" style="color: #333;font-weight: bold">
                                <?php echo $ouser['nickname'];?>:
                            </a>
                            </div>
                            <div style="color: #aaaaaa;font-size: 12px;padding: 10px; "><?php echo viewContent($v['content']);?></div>
                        <?php else: ?>
                            <div class="row padding-left-10">
                            <a href="<?php echo url("u-".$ouser['id']);?>" target="_blank" style="color: #333;font-weight: bold">
                                <?php echo $ouser['nickname'];?>:
                            </a>
                           <span style="color: #aaaaaa;font-size: 12px">发给你</span>
                            </div>
                               <div style="color: #aaaaaa;font-size: 12px;  padding: 10px;"><?php echo viewContent($v['content']);?></div>
                        <?php endif;?>
                        </div>
                        <div class="text-muted ">
                            <span><?php echo qtime($v['ctime']);?></span>
                            <span> <a href="javascript: void 0; "  style="font-size: 12px"  onclick="delReply(<?php echo $v['id'];?>)">删除</a></span>
                        </div>
                    </div>
                </li>
        <?php endforeach;?>
    <?php endif;?>
</ul>
<?php echo $markup;?>
        </div></div>
</div>
<script>
    $(function(){
        $("#preview").click(function(){
            var content = $("#content").val();
            if(!content) return true;
            ajax_post("<?php echo url("parsemarkdown"); ?>",{'content':content},function(json){
                if(json.status==1){
                    $("#previewdiv").html(json.data);
                }
            })
        });
    });

</script>