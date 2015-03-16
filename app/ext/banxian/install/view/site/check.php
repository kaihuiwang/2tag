<h1>2tag安装环境测试</h1>
<div class="content">
    <div class="title">系统所需功能支持检测</div>
    <div class="list">
        <div class="name">图像处理(GD库)：</div>
        <div class="value"> <span id="image">
      <?php
      $is_error = 0;
      if(function_exists("imageline")):

          ?>
          <font color=green><b>√</b></font>
      <?php else:
          $is_error++;
          ?>
          <font color=green><b>×</b></font>
      <?php endif;?>
      </span> </div>
    </div>
    <div class="list">
        <div class="name">MYSQL_PDO支持：</div>
        <div class="value"> <span id="image">
      <?php
      $is_error = 0;
      if(extension_loaded('pdo')):

          ?>
          <font color=green><b>√</b></font>
      <?php else:
          $is_error++;
          ?>
          <font color=green><b>×</b></font>
      <?php endif;?>
      </span> </div>
    </div>
    <div class="title">系统所需目录权限</div>

    <?php foreach ($path as $k=>$v):?>
        <div class="list">
            <div class="name"><?php echo $k; ?></div>
            <?php if($v[0] > 11):?>
                <div class="value"><font color=green><b>√</b></font></div>
            <?php else:
                $is_error++;
                ?>
                <div class="value"><font color=red><b>x</b></font>
                    <span style="padding:0 5px;color: #008ef1;margin-left: 10px">(<?php echo $v[1]; ?>)</span>
                </div>

            <?php endif;?>
        </div>
    <?php endforeach;?>
</div>
<?php if(!$is_error):?>
    <div class="menu">
        <a href='<?php echo url("install/config");?>'>准备完毕进入安装</a>
    </div>
<?php else: ?>
    <div class="menu">
        <span style="padding:0 5px;color: red;margin-left: 10px"> 非linux服务器请根据各自系统，设置目录权限</span>
    </div>
<?php endif;?>
