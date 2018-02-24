<?php
echo script("jquerytags/jquery.tagsinput.min.js");
echo style("jquerytags/jquery.tagsinput.css");
echo script("jquerytags/jquery-ui-1.10.3.custom.min.js");
echo style("jquerytags/ui-lightness/jquery-ui-1.10.3.custom.min.css");
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">缓存管理</span>
    </div>
    <div class="col-lg-12" style="margin-top: 10px;">
        <div class="panel-body" style="width: 800px;">
            <form action="<?php echo url("/admin/setting/cache"); ?>" method="post" role="form">
                <?php echo zl_form::getCsrf(); ?>
                <div class="form-group">
                        <button type="submit" class="btn btn-primary">清空缓存</button>
                </div>
            </form>
       </div>
    </div>
</div>