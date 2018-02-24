<form id="form-editpwd"  class="form-horizontal" action="<?php echo url('/news/doadd'); ?>" method="post">
    <div class="control-group">
        <label class="control-label">网址</label>
        <div class="controls">
            <input type="text" class="input-block-level" data-rule="网址: required;url;;length[~350]" name="url" placeholder="" style="width:100%">
        </div>
    </div>
    <div style="margin-top:10px;">
        <button class="btn btn-large btn-primary input-block-level" type="submit">提交</button>
    </div>
</form>