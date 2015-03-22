<?php
echo script("jquerytags/jquery.tagsinput.min.js");
echo style("jquerytags/jquery.tagsinput.css");
echo style("bootstrap-markdown/css/bootstrap-markdown.min.css");
echo script("bootstrap-markdown/js/bootstrap-markdown.js");
echo script("bootstrap-markdown/locale/bootstrap-markdown.zh.js");
?>
<div class="col-md-8">
    <div class="panel">
        <div class="panel-heading">
    <ol class="breadcrumb ">
        <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
        <li class="active">添加新主题</li>
    </ol></div>
            <div class="panel-body">
    <form class="form-horizontal" action="<?php echo url("add"); ?>" method="post">
        <?php echo zl_form::getCsrf(); ?>
            <div class="control-group">
                <label class="control-label">标题</label>
                <label class="control-label" style="float:right;font-weight: normal;color: #aaa;">120字以内</label>
                <div class="controls">
<input type="text" name="title" placeholder="请输入主题标题"
       class="input-xlarge" style="width: 99%;height: 30px;line-height: 30px;"
       data-rule="标题:required;title;;length[~120]"
    >
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">正文</label>
                <label class="control-label" style="float:right;font-weight: normal;color: #aaa">20000字以内</label>
                <div class="controls">
                    <div class="textarea">
    <textarea name="content" id="content"
              data-provide="markdown"
              data-hidden-buttons="cmdPreview"
              data-language="zh"
              data-resize="both"
              data-rule="正文:required;content;;length[~20000]"  rows="20"  style="width: 99%;padding:10px;background-color: #fff"></textarea>
                    </div>
                </div>
            </div>

            <div class="control-group" style="height: 39px;margin-bottom: 20px;">
                <label class="control-label">标签</label>
                <label class="control-label" style="float:right;font-weight: normal;color: #aaa">最多5个标签,回车键添加</label>
                <div class="controls">
                    <div class="textarea">
                        <textarea name="tags"   id="tagsadd"  class="tags" data-target="#msg"  data-rule="标签:required;tags;"  style="width: 99%;"></textarea>
                    </div>
                    <span class="msg-box m-top" id="msg"></span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"></label>
                <div class="controls">
                    <button class="btn btn-default" type="button" id="preview">预览</button>
                    <button class="btn btn-success" type="submit">提交</button>
                </div>
            </div>
    </form>
            <div id="previewdiv"></div>
    </div>
</div></div>
<div class="col-md-4">
    <div class="panel">
        <div class="panel-body">
            <ul class="ul-row-list" >
                <li><span class="f13" style="font-weight: bold">标题</span><div class="sep10"></div>
                    请在标题中描述内容要点。如果一件事情在标题的长度内就已经可以说清楚，那就没有必要写正文了。
                    <div class="sep10"></div>
                </li>
                <li>
                    <span class="f13" style="font-weight: bold">正文</span><div class="sep10"></div>
                    可以在正文中为你要发布的主题添加更多细节。本站 支持 <span style="font-family: Consolas, 'Panic Sans', mono">
                        <a href="http://wowubuntu.com/markdown/" target="_blank">Markdown</a></span> 文本标记语法。
                    <div class="sep10"></div>
                    在正式提交之前，你可以点击本页面左下角的“预览”来查看 Markdown 正文的实际渲染效果。
                    <div class="sep10"></div>
                </li>
                <li><span class="f13" style="font-weight: bold">标签</span><div class="sep10"></div>
                    在最后，请为你的主题选择5个标签。恰当的归类会让你发布的信息更加有用。
                    <div class="sep10"></div>
                    你可以在主题发布后 <?php echo zl::$configApp['arc_timeout']; ?>秒内，对标题或者正文进行编辑。同时，在 300 秒内，你可以重新为主题更新新标签。
                </li>
            </ul>
    </div>    </div>
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
        $('#tagsadd').tagsInput({
            'height':'30px', //设置高度
            'width':'100%',  //设置宽度
            'defaultText':'点击这里',
            'interactive':true,
            'removeWithBackspace':true,
            'minChars':1,
            'maxChars':50,
            'maxCount':5,
            'placeholderColor':'#999',
            'autocomplete_url':'<?php echo url("tagsearch");?>',
            'upperCase':false
        });
    });
</script>