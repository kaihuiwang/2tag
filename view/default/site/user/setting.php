<div class="col-md-2 meleft">
    <div class="panel">
        <div class="panel-body">
    <?php
    echo zl_widget::widget()->create('setting_nav');
    ?>
            </div></div>
</div>
<div class="col-md-10">
    <div class="panel">
        <div class="panel-body">
    <ol class=" breadcrumb">
        <li><a href="<?php echo url('/');?>"><?php echo zl::config()->get("app.siteTitle"); ?></a></li>
        <li class="active">设置头像</li>
    </ol>
            </div></div>
    <div class="panel">
            <div class="panel-heading">
            <ul class="nav nav-pills">
                <li role="presentation" class="disabled"><a href="javascript:;">设置头像</a></li>
                <li role="presentation"><a href="<?php echo url('/editpwd'); ?>">修改密码</a></li>
            </ul>
                </div>
        <div class="panel-body">
    <div class=" box-top-0">
        <div class="gprefix-2 gmt20">
            <form method="post" enctype="multipart/form-data" id="mfacefrm" action="<?php echo url('setting');?>">
                <div class="form-div">
                    <p>
                        <input type="file" name="upload_file" id="face" accept="image/*" style="display: inline">
                        <input type="submit" id="imgSubmit" value="上传" style="display: inline">
                    </p><p id="uploadMsg" class="head-summary">
                        （支持bmp、jpg、png、gif图片格式，大小不要超过1M）
                    </p>
                </div>
            </form>
            <div class="gmt60 gpack">
                <div class="main-head">
                    <div class="main-head-holder">
                        <div id="preview160">
                            <img src="<?php echo showImg($user['id'],160); ?>" id="previewBig" class='img-rounded'>
                        </div>
                    </div>
                    <p class="head-summary center">
                        大尺寸头像，160×160像素
                    </p>
                </div>
                <div class="other-head">
                    <div>
                        <div id="preview48">
                            <img src="<?php echo showImg($user['id'],48); ?>" id="previewMiddle"  class='img-rounded'>
                        </div>
                        <p class="head-summary">
                            中尺寸头像，48x48像素
                        </p>
                    </div>
                    <div class="gmt20">
                        <div id="preview24">
                            <img src="<?php echo showImg($user['id'],32); ?>" id="previewTiny" class='img-rounded'>
                        </div>
                        <p class="head-summary">
                            小尺寸头像，32x32像素
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
</div>
</div>