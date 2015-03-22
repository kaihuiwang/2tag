<?php
echo script("jquerytags/jquery.tagsinput.min.js");
echo style("jquerytags/jquery.tagsinput.css");
?>
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
        <li class="active">发送留言</li>
    </ol>
            </div></div>
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal" action="<?php echo url("addmsg"); ?>" method="post">
                <?php echo zl_form::getCsrf(); ?>
                <div class="control-group">
                    <label class="control-label">收件人</label>
                    <label class="control-label" style="font-weight: normal;color: #aaa">一次能加20人</label>
                    <label class="control-label" id="msg1"></label>
                    <div class="controls" style="height: 35px;">
                        <input type="text" name="receiver"
                               class="input-xlarge"
                               data-rule="收件人:required;"  class="tags" id="receiver" data-target="#msg1"
                            >
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">正文</label>
                    <label class="control-label" style="font-weight: normal;color: #aaa">1000字以内</label>
                    <div class="controls">
                        <textarea   name="content" id="content" data-rule="正文:required;content;;length[~1000]"  rows="26"  style="width: 100%;"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                    </label>
                    <div class="controls">
                        <input type="hidden" name="id" value="<?php echo $id;?>">
                        <button class="btn btn-default" type="button" id="preview">预览</button>
                        <button class="btn btn-success" type="submit">提交</button>
                    </div>
                </div>
            </form>
            <div class="row" id="previewdiv"></div>
    </div>  </div>
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
        $('#receiver').tagsInput({
            'height':'30px', //设置高度
            'width':'100%',  //设置宽度
            'defaultText':'+收件人',
            'interactive':true,
            'removeWithBackspace':true,
            'minChars':1,
            'maxChars':50,
            'maxCount':20,
            'placeholderColor':'#999',
            'autocomplete_url':'<?php echo url("usersearch");?>',
            'upperCase':false
        });
    });

</script>