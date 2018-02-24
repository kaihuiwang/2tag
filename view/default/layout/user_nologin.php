<!DOCTYPE html>
<html>
<head>
<head>
    <title><?php echo zl::config()->get("app.siteTitle"); ?>-<?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo zl_widget::widget()->create('gstatic');
    ?>
    <style type="text/css">
        .form-signin {
            max-width: 300px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .form-signin .form-signin-heading {
            text-align: center;
        }

        .form-signin input[type="text"],
        .form-signin input[type="password"] {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
        }
        .center {
            text-align: center;
        }
    </style>
    <?php echo style('css/zl.css'); ?>
    <?php echo style('font-awesome-4.1.0/css/font-awesome.min.css'); ?>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background: #fff;">
        <div class="container">
                <a class="navbar-brand" href="<?php echo url("/"); ?>" style="color: #333"><?php echo zl::config()->get("app.siteTitle"); ?></a>
            <?php echo zl_widget::widget()->create("topnav"); ?>
        </div>
    </nav>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel"  style="background-color: #eee;border: none">
                <div class="panel-body">
                    <?php echo $layout_content; ?>
                </div>
                </div>
        </div>
    </div>
</div>
<?php
echo zl_widget::widget()->create("gfstatic");
?>

</body>
</html>