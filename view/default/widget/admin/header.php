<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($descr) ? $descr : "" ?>">
    <title>
        <?php echo isset($title) ? $title : "" ?>
    </title>
    <?php
    echo style("/css/admin.css");
    echo script("/js/jquery.js");
    echo script("/js/html5shiv.js");
    echo script("/js/respond.js");
    ?>
</head>
<body style="background-color: #ffffff">

