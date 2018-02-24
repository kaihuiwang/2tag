<form id="form-editpwd"  class="form-horizontal" action="<?php echo url('/add_zhuanti'); ?>" method="post">
    <?php echo zl_form::getCsrf(); ?>
    <div class="control-group">
        <label class="control-label">专题名称</label>
        <div class="controls">
            <input type="text" class="input-block-level" data-rule="专题名称: required;name;remote[<?php echo url('/checkzhuanti'); ?>]" name="name" placeholder="">
        </div>
    </div>
    <div style="margin-top:10px;">
        <button class="btn btn-large btn-primary input-block-level" type="submit">提交</button>
    </div>
</form>