<style>
    .form-group{
        width:400px;
    }
</style>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-12 mytab">
        <span class="label label-default">添加导航</span>
    </div>
</div>
<div class="row" style="margin: 10px;">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo url("/admin/menu_add"); ?>" method="post" role="form">
                    <?php echo zl_form::getCsrf(); ?>
                    <div class="form-group">
                        <label for="inputemail">标题</label>
                        <input type="text" class="form-control" id="inputtitle" name="title" data-rule="标题: required;title;">
                    </div>
                    <div class="form-group">
                        <label for="intRealname">url</label>
                        <input type="text" class="form-control" id="inputurl" name="zl_url" data-rule="url: required;zl_url;">                    </div>
                    <div class="form-group">
                        <label for="intsex">类型</label>
                        <p>
                            <select name="zl_type" id="zl_type">
                                <option value="1">顶部导航</option>
                                <option value="2">底部导航</option>
                            </select>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="inputemail">排序</label>
                        <input type="text" class="form-control" id="inputsort" name="zl_sort" data-rule="sort: required;zl_sort;" value="0">
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