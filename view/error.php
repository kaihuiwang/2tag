<!DOCTYPE html>
<html>
<head>
    <title><?php echo zl::config()->get("app.siteTitle"); ?>-出错啦</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo zl_widget::widget()->create('gstatic');
    ?>
    <style type="text/css">
        body {
            padding-top: 150px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .msg-signin {
            max-width: 800px;
            padding: 40px 20px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            font-size: 22px;
        }

        .center {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="alert alert-danger msg-signin" role="alert">
        <li class="glyphicon glyphicon-remove-sign"></li>
        <?php echo $msg; ?>
        <?php if($isdebug): ?>
        <pre><?php echo $trace; ?></pre>
        <?php endif;?>
    </div>
</div>
<?php
echo zl_widget::widget()->create("gfstatic");
?>
</body>
</html>