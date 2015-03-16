<!DOCTYPE html>
<html>
<head>
    <title><?php echo zl::config()->get("app.siteTitle"); ?>-<?php echo $title; ?></title>
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
</head>
<body>
<div class="container">
    <?php echo $layout_content; ?>
</div>
<?php
echo zl_widget::widget()->create("gfstatic");
?>
</body>
</html>