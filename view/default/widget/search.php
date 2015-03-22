<?php
$fieldsVl = zl::getParam("fields");

//$fields = zl::getParam("fields");
//d(zl::getParam());
$selectVl = zl::getParam("select");
$whereVl = (array) zl::getParam("where");
array_unshift($whereVl,"");
$vl = zl::getParam("vl");

array_unshift($search['fields'],array("null","","text"));
$row = array();
if($fieldsVl) {
    foreach ($fieldsVl as $k => $v) {
        $row[$k]['field'] = $v;
        $row[$k]['select'] = $selectVl[$k];
        $row[$k]['where'] = $whereVl[$k];
        $row[$k]['vl'] = $vl[$v][$k];
        foreach ($search['fields'] as $fv) {
            if ($fv[0] == $v) {
                $row[$k]['type'] = $fv[2];
                break;
            }
        }
    }
}
//d($row);
?>

<form action="<?php echo $search['url']; ?>" class="form-inline" role="form" method="post" id="search_form"
      xmlns="http://www.w3.org/1999/html">
            <table class="table  table-condensed">
                <thead>
                <?php
                if(!$row):
                ?>
                <tr>
                    <td align="left" width="150">
                        <a href="javascript:;" id="search_plus"><li class="glyphicon glyphicon-plus"></li></a>
                    </td>
                    <td width="150" align="left">
                        <select style="width: 150px" class="select" name="fields[]">
                            <?php
                            foreach($search['fields'] as $v):
                                ?>
                                <option value="<?php echo $v[0];?>"><?php echo $v[1];?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </td>
                    <td  width="100" align="left">
                        <select  style="width: 100px" class="select" name="select[]">
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value=">">&gt;</option>
                            <option value=">=">&gt;=</option>
                            <option value="<">&lt;</option>
                            <option value="<=">&lt;=</option>
                            <option value="like" >包含</option>
<!--                            <option value="between">介于</option>-->
                            <option value="not like">不包含</option>
                        </select>
                    </td>
                    <td style="width: 200px" align="left">
                        <?php
                        foreach($search['fields'] as $kv=>$v):
                            $style = "style=\"display:none\"";
                            if($kv ==0){
                                $style = "";
                            }
                            if($v[2] =='text'){
                                echo "<input type=\"text\" name=\"vl[".$v[0]."][]\" ".$style.">";
                            }elseif($v[2] =='select'){
                                echo "<select name=\"vl[".$v[0]."][]\"  ".$style."  class=\"select\">";
                                echo '<option value="null"></option>';
                                foreach($v[3] as $sk=>$s){
                                    echo "<option value=\"".$sk."\">".$s."</option>";
                                }
                                echo '</select>';
                            }elseif($v[2] =='date'){
                                echo "<input type=\"text\" value=\"\"  name=\"vl[".$v[0]."][]\"  data-date-format=\"yyyy-mm-dd hh:ii:ss\" class=\"datepicker\" ".$style.">";
                            }
                        endforeach;
                        ?>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($row as $rv):?>
                    <tr>
                        <td align="left" width="150">
                            <?php if($rv['where'] == ""): ?>
                            <a href="javascript:;" id="search_plus"><li class="glyphicon glyphicon-plus"></li></a>
                                <?php else: ?>
                                <select  style="width: 100px" class="select" name="where[]">
                                    <option value="and" <?php if($rv['where']=="and") echo "selected" ;?>>并且</option>
                                    <option value="or" <?php if($rv['where']=="or") echo "selected" ;?>>或者</option>
                                </select>
                            <?php endif; ?>
                        </td>
                        <td width="150" align="left">
                            <select style="width: 150px" class="select" name="fields[]">
                                <?php
                                foreach($search['fields'] as $v):
                                    ?>
                                    <option value="<?php echo $v[0];?>" <?php if($rv['field'] == $v[0]) echo "selected"; ?>><?php echo $v[1];?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </td>
                        <td  width="100" align="left">
                            <select  style="width: 100px" class="select" name="select[]">
                                <option value="="  <?php if($rv['select'] == "=") echo "selected"; ?>>=</option>
                                <option value="!="  <?php if($rv['select'] == "!=") echo "selected"; ?>>!=</option>
                                <option value=">"  <?php if($rv['select'] == ">") echo "selected"; ?>>&gt;</option>
                                <option value=">="  <?php if($rv['select'] == ">=") echo "selected"; ?>>&gt;=</option>
                                <option value="<"  <?php if($rv['select'] == "<") echo "selected"; ?>>&lt;</option>
                                <option value="<="  <?php if($rv['select'] == "<=") echo "selected"; ?>>&lt;=</option>
                                <option value="like"   <?php if($rv['select'] == "like") echo "selected"; ?>>包含</option>
<!--                                <option value="between"  --><?php //if($rv['select'] == "between") echo "selected"; ?><!-->介于</option>-->
                                <option value="not like"  <?php if($rv['select'] == "not like") echo "selected"; ?>>不包含</option>
                            </select>
                        </td>
                        <td style="width: 200px" align="left">
                            <?php
                            foreach($search['fields'] as $kv=>$v):
                                if($rv['type'] == $v[2] && $rv['field']==$v[0]){
                                    $style = "";
                                }else{
                                        $style = "style=\"display:none;\"";
                                }
                                $value = $rv['field']==$v[0] && $rv['type'] == $v[2] ? $rv['vl']:"";

                                if($v[2] =='text'){
                                    echo "<input type=\"text\" name=\"vl[".$v[0]."][]\" ".$style." value='".$value."'>";
                                }elseif($v[2] =='select'){
                                    echo "<select name=\"vl[".$v[0]."][]\"  ".$style."  class=\"select\">";
                                    echo '<option value="null"></option>';
                                    foreach($v[3] as $sk=>$s){
                                        $select = $value==$sk ? "selected":"";
                                        echo "<option value=\"".$sk."\"  ".$select." >".$s."</option>";
                                    }
                                    echo '</select>';
                                }elseif($v[2] =='date'){
                                    echo "<input type=\"text\"  name=\"vl[".$v[0]."][]\"  data-date-format=\"yyyy-mm-dd\" class=\"datepicker\" ".$style."  value='".$value."'>";
                                }
                            endforeach;
                            ?>
                        </td>
                    </tr>
                <?php endforeach;?>
                <?php endif;?>
                <tr>
                    <td colspan="100%">
                        <?php
                            if(isset($search['hidden'])):
                            foreach($search['hidden'] as $v):
                        ?>
                                <input type="hidden" name="hidden[<?php echo $v; ?>]"  id="<?php echo $v; ?>" value="<?php $hidden= zl::getParam("hidden");echo $hidden[$v]; ?>">
                        <?php
                            endforeach;
                            endif;
                        ?>
                        <button type="submit" id="search_submit" class="btn btn-submit btn-primary  btn-xs">搜索</button>
                    </td>
                </tr>
                </thead>
            </table>
        </form>

<script>
$(function(){
    function liveChange(){
        $("select[name='fields[]']").each(function(){
//            console.log($(this).parent().parent().children("td").last().html());
            $(this).change(function(){
                $(this).parent().parent().children("td").last().children().each(function(){
                    $(this).hide();
                });
                var name=$("option:selected", this).val();
                //console.log($(this).parent().parent().children("td").last());
                //console.log($(this).parent().parent().children("td").last().html());
//                console.log($("[name='vl["+name+"][]']",$(this).parent().parent().children("td").last()).attr("name"));
                $("[name='vl["+name+"][]']",$(this).parent().parent().children("td").last()).show();
            });
        });

    }

    liveChange();

    $("#search_plus").click(function(){
        var clone = $(this).parent().parent().clone();
        var postion  = $(this).parent().parent().parent().children("tr").eq(-2);
//        console.log(.html());
        var str = "<select  style=\"width: 100px\" class=\"select\" name=\"where[]\"><option value=\"and\">并且</option> <option value=\"or\">或者</option></select>";
        postion.after("<tr>"+clone.children("td").first().html(str).parent().html()+"</tr>");
        liveChange();
        $(".datepicker",$(this).parent().parent().parent().last()).each(function(){
            $(this).datepicker({
                language: 'zh-CN'
            });
        });
    });
});
</script>