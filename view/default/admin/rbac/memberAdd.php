<?php echo style("/treeview/css/bootstrap-treeview.css"); ?>
<?php echo script("/treeview/js/bootstrap-treeview.js"); ?>
<style>
.form-group{
    width:400px;
}
</style>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">用户添加</span>
    </div>
</div>
<div class="row" style="margin: 10px;">
    <div class="col-lg-5">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo url("/admin/member/add"); ?>" method="post" role="form">
                    <?php echo zl_form::getCsrf(); ?>
                    <div class="form-group">
                        <label for="inputemail">邮箱</label>
                        <input type="email" class="form-control" id="inputemail" name="email" data-rule="邮箱: required;email;remote[<?php echo url('/check_unique_email'); ?>]">
                    </div>
                    <div class="form-group">
                        <label for="intRealname">真实姓名</label>
                        <input type="text" class="form-control" id="intRealname" name="realname" data-rule="真实姓名: required;realname">
                    </div>
                    <div class="form-group">
                        <label for="intNickname">花名</label>
                        <input type="text" class="form-control" id="intNickname" name="nickname" data-rule="花名: required;nickname;remote[<?php echo url('/check_unique_nickname'); ?>]">
                    </div>
                    <div class="form-group">
                        <label for="intPwd">密码</label>
                        <input type="password" class="form-control" id="intPwd" name="pwd"  data-rule="密码: required;pwd">
                    </div>
                    <div class="form-group">
                        <label for="intsex">性别</label>
                        <p>
                            <select name="sex" id="intsex">
                                <option value="0">保密</option>
                                <option value="1">男</option>
                                <option value="2">女</option>
                            </select>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="intMobile">手机号码</label>
                        <input type="text" class="form-control" id="intMobile" name="mobile">
                    </div>
                    <div class="form-group">
                        <label for="tel">分机/电话号码</label>
                        <input type="text" class="form-control" id="tel" name="tel">
                    </div>
                    <div class="form-group">
                        <label for="qq">qq</label>
                        <input type="text" class="form-control" id="qq" name="qq">
                    </div>
                    <div class="form-group">
                        <label for="skype">skype</label>
                        <input type="text" class="form-control" id="skype" name="skype">
                    </div>
                    <div class="form-group">
                        <label for="gtalk">gtalk</label>
                        <input type="text" class="form-control" id="gtalk" name="gtalk">
                    </div>
                    <div class="form-group">
                        <label for="intsex">职位</label>
                        <p>
                            <select name="role" id="intsex">
                                <?php foreach($selectRole as $k=>$v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endforeach;?>
                            </select>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="intsex">部门&nbsp;&nbsp;<span id="deptName" style="font-weight:100;text-decoration: underline"></span></label>
                        <p>
                        <input type="hidden" name="dept" id="dept" value="">
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
    $('#tree').treeview({data: getTree(),showBorder:false,borderColor:"#fff",borderColor:"#ddd",enableLinks:false,levels:2,selectedBackColor:"#333",showTags:true});
    $('#tree').on('nodeSelected', function(event, node) {
        $("#dept").val(node.id);
        $("#deptName").text(node.text);
    });
</script>