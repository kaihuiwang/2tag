<!DOCTYPE html>
<html>
<head>
    <title><?php echo zl::config()->get("app.siteTitle"); ?>-消息提醒</title>
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
            max-width: 400px;
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
    <?php if ($type == 0): ?>
        <div class="alert alert-info msg-signin" role="alert">
            <li class="glyphicon glyphicon-info-sign"></li>
            <?php echo $msg; ?>
        </div>
    <?php elseif ($type == 1): ?>
        <div class="alert alert-success msg-signin" role="alert">
            <li class="glyphicon glyphicon-ok-sign"></li>
            <?php echo $msg; ?>
        </div>
    <?php
    elseif ($type == 2): ?>
        <div class="alert alert-danger msg-signin" role="alert">
            <li class="glyphicon glyphicon-remove-sign"></li>
            <?php echo $msg; ?>
        </div>
    <?php
    elseif ($type == 3): ?>
        <div class="alert alert-warning msg-signin" role="alert">
            <li class="glyphicon glyphicon-minus-sign"></li>
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

</div>
<?php
echo zl_widget::widget()->create("gfstatic");
?>
<script type="text/javascript">
    <?php if($redirect||$isJs):?>
    function redirect() {
        <?php if($isJs):?>
        <?php echo "history.back();";?>
        <?php else:?>
        location.assign('<?php echo url($redirect);?>');
        <?php endif;?>
    }
    setTimeout('redirect()', 2000);
    <?php endif;?>
</script>
</body>
</html>