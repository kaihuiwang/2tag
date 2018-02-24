<form name="frm" method="post" action="<?php echo url("install/config"); ?>">
<h1>2tag安装配置</h1>
<div class="content">
    <div class="list">
        <div class="name">数据库地址：</div>
        <div class="value"><input type="text" class="input" name="DB_HOST" id="DB_HOST" value="<?php echo $dbHost?$dbHost:"localhost";?>"  /></div>
    </div>
    <div class="list">
        <div class="name">数据库端口：</div>
        <div class="value"><input type="text" class="input" name="DB_PORT" id="DB_PORT" value="<?php echo $dbPort?$dbPort:"3306";?>" /></div>
    </div>
    <div class="list">
        <div class="name">数据库名称：</div>
        <div class="value"><input type="text" class="input" name="DB_NAME" id="DB_NAME" value="<?php echo $dbName?$dbName:"2tag";?>"  /> </div>
    </div>
    <div class="list">
        <div class="name">数据库用户名：</div>
        <div class="value"><input type="text" class="input" name="DB_USER" id="DB_USER" value="<?php echo $dbUser?$dbUser:"root";?>"  /></div>
    </div>
    <div class="list">
        <div class="name">数据库密码：</div>
        <div class="value"><input type="text" class="input" name="DB_PWD" id="DB_PWD" value="<?php echo $dbPwd?$dbPwd:"";?>" /></div>
    </div>
    <div class="list">
        <div class="name">数据库前缀：</div>
        <div class="value"><input type="text" class="input" name="DB_PRE" id="DB_PRE" value="<?php echo $dbPre?$dbPre:"2tag_";?>" /></div>
    </div>
    <div class="list">
        <div class="name">管理员账户：</div>
        <div class="value"><input type="text" class="input" name="admin" value="<?php echo $admin?$admin:"admin@admin.com";?>" />&nbsp;&nbsp;&nbsp;&nbsp;必须为邮箱地址</div>
    </div>
    <div class="list">
        <div class="name">管理员密码：</div>
        <div class="value"><input name="adminPwd"  class="input"  type="text" value="<?php echo $adminPwd?$adminPwd:"111111";?>" /></div>
    </div>

</div>
<div class="menu">
    <?php
    $msg = zl_tool_error::lastError();
    if($msg):?>
        <div style="color:red" id="error"><?php echo $msg?></div>
    <?php endif;?>
    <button type="submit"  id="submit">准备完毕进入安装</button>
</div>
</form>
<script>
    $(function(){
        $("#submit").click(function(){
            $(this).html("正在安装中，请耐心等待");
        });
    })
</script>