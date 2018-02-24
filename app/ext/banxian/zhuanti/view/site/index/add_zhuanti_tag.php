<form id="form-editpwd"  class="form-horizontal" action="<?php echo url('/add_zhuanti_tag/'.$id); ?>" method="post">
    <?php echo zl_form::getCsrf(); ?>
    <div class="control-group">
        <label class="control-label">选择专题</label>
        <div class="controls">
            <?php if($zhuanti): ?>
            <select name="zhuanti_id">
                <?php foreach($zhuanti as $v): ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                <?php endforeach;?>
            </select>
            <?php else:?>
            你还没有添加专题，去<a href="<?php echo url('zhuanti'); ?>">添加</a>
            <?php endif;?>
        </div>
    </div>
    <div style="margin-top:10px;">
        <button class="btn btn-large btn-primary input-block-level" type="submit">提交</button>
    </div>
</form>