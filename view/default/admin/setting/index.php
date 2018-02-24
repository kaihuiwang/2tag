<?php
echo script("jquerytags/jquery.tagsinput.min.js");
echo style("jquerytags/jquery.tagsinput.css");
echo script("jquerytags/jquery-ui-1.10.3.custom.min.js");
echo style("jquerytags/ui-lightness/jquery-ui-1.10.3.custom.min.css");
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">配置管理</span>
    </div>
    <div class="col-lg-12" style="margin-top: 10px;">
        <ul class="nav nav-pills" role="tablist">
            <?php if(isset($configs['file']) && $configs['file']):?>
                <?php foreach($configs['file'] as $k=>$v):?>
            <li role="presentation" <?php  echo $f==$k?'class="disabled"':"";?>><a href="<?php echo url("/admin/setting/index",array("f"=>$k));?>"><?php echo $v; ?></a></li>
            <?php endforeach;?>
            <?php endif;?>
        </ul>
        <div class="panel-body" style="width: 800px;">
            <form action="<?php echo url("/admin/setting/index"); ?>" method="post" role="form">
                <?php echo zl_form::getCsrf(); ?>
                <?php
                    if(isset($configs['struct'][$f]) && $configs['struct'][$f]):
                        foreach($configs['struct'][$f] as $k=>$v):
                            @list($descr,$type,$data)= $v;
                ?>
                <div class="form-group">
                    <label for="intName"><?php echo $descr; ?></label>
                    <?php
                    $name = $f.".".$k;
                    $value = stripslashes(zl::config()->get($name));
                    $name = str_replace(".","-",$name);
                    if($type =='text'){
                        echo "<input type=\"text\" name=\"".$name."\"  value='".$value."' class=\"form-control\" >";
                    }elseif($type =='select'){
                        echo "<select name=\"".$name."\"   class=\"select\" class=\"form-control\">";
                        foreach($data as $sk=>$s){
                            $select = $value==$sk ? "selected":"";
                            echo "<option value=\"".$sk."\"  ".$select." >".$s."</option>";
                        }
                        echo '</select>';
                    }elseif($type =='date'){
                        echo "<input type=\"text\"  name=\"".$name."\"  data-date-format=\"yyyy-mm-dd\" class=\"datepicker form-control\"  value='".$value."'>";
                    }elseif($type =='textarea'){
                        echo "<textarea name=\"".$name."\" cols=\"2\" class=\"form-control\"  rows=\"3\">".$value."</textarea>";
                    }elseif($type=='password'){
                        echo "<input type=\"password\" name=\"".$name."\"  value='".$value."' class=\"form-control\">";
                    }
                    ?>
                </div>
                <?php
                endforeach;
                endif; ?>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" name="f" value="<?php echo $f; ?>">
                        <button type="submit" class="btn btn-default">提交</button>
                    </div>
                </div>
            </form>
       </div>
    </div>
</div>
<script>
$(function(){
    $('#tags').tagsInput({
        'height':'100px', //设置高度
        'width':'500px',  //设置宽度
        'defaultText':'添加组员',
        'interactive':true,
        'removeWithBackspace':true,
        'minChars':1,
        'maxChars':20,
        'maxCount':100,
        'placeholderColor':'#f4f4f4',
        'autocomplete_url':'<?php echo url("/admin/user/tagssearch");?>',
        'upperCase':true
    });
});
</script>