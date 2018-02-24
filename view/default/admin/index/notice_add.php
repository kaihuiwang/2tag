<style>
    .form-group{
        width:400px;
    }
</style>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">添加公告</span>
    </div>
</div>
<div class="row" style="margin: 10px;">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo url("/admin/notice/add"); ?>" method="post" role="form">
                    <?php echo zl_form::getCsrf(); ?>
                    <div class="form-group">
                        <label for="inputemail">标题</label>
 <input type="text" class="form-control" id="inputtitle" name="title" data-rule="标题: required;title;">
                    </div>
                    <div class="form-group">
                        <label for="intRealname">内容</label>
 <textarea class="form-control" name="content"  id="content"  data-rule="内容: required;content;" style="width: 600px;height: 400px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="intsex">类型</label>
                        <p>
                            <select name="zl_type" id="zl_type">
                                <option value="1">公告</option>
                                <option value="2">说明</option>
                            </select>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="intRealname">显示时间</label>
                        <input type="text" class="form-control datepicker"  data-date-format="yyyy-mm-dd hh:ii:ss"  id="inputshow_time" name="show_time" data-rule="显示时间: required;show_time;">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-default" type="button" id="preview">预览</button>
                            <button type="submit" class="btn btn-default">提交</button>
                        </div>
                    </div>
                </form>
            </div>
            <p  id="previewdiv" style="display: block;padding: 10px;"></p>
        </div>

    </div>
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