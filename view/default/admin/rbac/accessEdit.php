<style>
.form-group{
    width:400px;
}
#access ul{
        list-style: none;
        float: left;;
    }
#access li{
    float: left;
    width: 150px;
}
</style>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">职位编辑</span>
    </div>
</div>
<div class="row" style="margin: 10px;">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo url("/admin/access/edit"); ?>" method="post" role="form">
                    <?php echo zl_form::getCsrf(); ?>
                    <div class="form-group">
                        <label for="intName">职位名称</label>
                        <input type="text" class="form-control" id="intName" name="name" data-rule="职位名称: required;name" value="<?php echo $roleInfo['name']; ?>">
                    </div>
                    <div  id="access">
                        <label>权限&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label style="font-weight: 100"><input type="checkbox" value="1" id="checkAll">全选</label></label>
                        <div class="panel panel-default">
                            <div class="panel-body" id="Accesslist">
                            <?php
                            if($nodes):
                                foreach($nodes as $k=>$v):
                                    ?>
                                    <h5 style="clear: both"><span ><?php echo $k; ?></span></h5>
                                    <ul>
                                        <?php
                                        foreach($v as $v1):
                                            list($tag_name,$name,$url_name) = $v1;
                                            ?>
                                            <li>
                                            <input type="checkbox" name="access[]" value="<?php echo $url_name ?>" <?php echo in_array($url_name,$access)?"checked='checked'":""; ?>>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <span class="label label-default tip" data-toggle="tooltip" data-placement="top" title="<?php echo $tag_name ?>"><?php echo $name ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php
                                endforeach;
                            endif;?>
                            </div> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <button type="submit" class="btn btn-default">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script>
    $(function(){
        $("#checkAll").click(function(){
            if(this.checked){
                $("#Accesslist :checkbox").each(function(){
                    $(this).prop("checked", true);
                });
            }else{
                $("#Accesslist :checkbox").each(function(){
                    $(this).prop("checked", false);
                });
            }
        });
    });
</script>