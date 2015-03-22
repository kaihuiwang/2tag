<html>
<head>
    <title><?php echo isset($title)?$title:""; ?>-<?php echo zl::config()->get("app.siteTitle"); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo zl_widget::widget()->create('gstatic');
    ?>
    <?php echo style('css/zl.css'); ?>
    <?php echo style('font-awesome-4.1.0/css/font-awesome.min.css'); ?>
</head>
<body>
<?php $ctag = zl::get("ctag"); ?>
    <nav class="navbar navbar-inverse navbar-fixed-top" id="navigation" style="background: #fff;">
        <div class="container">
                    <a class="navbar-brand" href="<?php echo url("/");?>" style="color: #333"><?php echo zl::config()->get("app.siteTitle"); ?></a>
                <form class="navbar-search" action="<?php echo url("stag"); ?>" method="get">
                    <input type="text" class="input-search" name="tag" value='<?php echo $ctag; ?>' id="tags" placeholder="请输入标签">
                </form>
                <?php echo zl_widget::widget()->create("topnav"); ?>
            </div>
    </nav>
        <div class="container" >
                    <div class="row">
                    <?php echo $layout_content; ?>
                     </div>
            </div>
<?php
echo zl_widget::widget()->create('gfooter');
echo zl_widget::widget()->create("gfstatic");
?>
</body>
</html>