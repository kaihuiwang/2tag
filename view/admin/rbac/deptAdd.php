<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<style>
.form-group{
    width:400px;
}
</style>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">部门添加</span>
    </div>
</div>
<div class="row" style="margin: 10px;">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo url("/admin/dept/add"); ?>" method="post" role="form">
                    <?php echo zl_form::getCsrf(); ?>
                    <div class="form-group">
                        <label for="intName">部门名称</label>
                        <input type="text" class="form-control" id="intName" name="name" data-rule="部门名称: required;name">
                    </div>
                    <div class="form-group">
                        <label for="intsex">上级部门&nbsp;&nbsp;<span id="deptName" style="font-weight:100;text-decoration: underline"><?php echo $levelDept['name'];?></span></label>
                        <p>
                        <input type="hidden" name="pid" id="pid" value="<?php echo $levelDept['id'];?>">
                        <div id="tree" style="border: none;"></div>
                        </p>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script>
    function getTree() {
        return <?php echo $depts;?>;
    }
    $('#tree').treeview({data: getTree(),showBorder:false,borderColor:"#fff",borderColor:"#ddd",enableLinks:false,levels:4,selectedBackColor:"#333",showTags:true});
    $('#tree').on('nodeSelected', function(event, node) {

//        $('#tree').children().children().each(function(){
//            $(this).attr('style',"color:#333;background-color:#fff")
////            $(this).css("color","#333");
////            $(this).css("background-color","#fff");
//            console.log($(this).attr('style'));
//        });
//

        $("#pid").val(node.id);
        $("#deptName").text(node.text);
    });
</script>