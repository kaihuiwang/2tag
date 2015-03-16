<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo zl::config()->get("app.siteTitle"); ?>后台-<?php echo isset($title)?$title:""; ?></title>
    <?php
    echo style('bs/css/bootstrap.css');
    echo style('bs/css/bootstrap-theme.css');
    ?>
    <!--[if lt IE 9]>
    <?php
      echo script('js/html5shiv.js');
     ?>
    <![endif]-->
    <?php
    echo script('js/respond.js');
    echo script('js/jquery.js');
    echo script('bs/js/bootstrap.js');
    echo script("bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");
    echo script("bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js");
    echo script('nice_validator/jquery.validator.js');
    echo script('nice_validator/local/zh_CN.js');
   echo style('nice_validator/jquery.validator.css');
    echo style("bootstrap-datetimepicker/bootstrap-datetimepicker.min.css");
    echo style("css/bs2.css");
    echo style("easydialog/easydialog.css");
    echo script("easydialog/easydialog.min.js");
    echo script("js/util.js");
    echo style("css/admin.css");
    ?>
    <style>
        #nav {
            background-color: #333333;
            color: #fff;
            font-size: 12px;;
        }

        .padding-5 {
            padding: 10px;
        }

        .white {
            color: #fff;
        }

        .dropdown a:hover {
            color: #fff;;
        }

        .black {
            background-color: #333333;
        }

        .list-group-item {
            background-color: #000;
            border: none;
            border-top: 1px solid #555;
            color: #fff;
        }

        .list-group a {
            color: #ddd;
            font-weight: bold;
        }

        .list-group a:hover {
            background-color: #000;
            color: #ddd;
            font-weight: bold;
        }

        .list-group a:focus {
            background-color: #000;
            color: #ddd;
            font-weight: bold;
        }

        .badge {
            background-color: #000;
        }

        .gray {
            color: #ddd;
        }

        .child {
            display: none;
        }

        .block {
            display: block;
            background-color: #333;
            border: none;
        }

        .list-group-item.active, .list-group-item.active:hover, .list-group-item.active:focus {
            background: none;
            background-color: #000;
        }

        .mytab {
            border-bottom: 2px solid #32AFD4;
            padding-bottom: 5px;
        }

        /* pagination styling */
        .pagination {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            height: 30px;
            font-size: 0.75em;
            -webkit-font-smoothing: antialiased;
        }

        .pagination ul li {
            float: left;
            display: block;
            width: 30px;
        }

        .pagination ul li a {
            background-repeat: no-repeat;
            background-image: url(data:image/png;base64,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);
            width: 27px;
            height: 27px;
            line-height: 27px;
            text-decoration: none;
            display: block;
            text-align: center;
            color: #717171;
            text-shadow: 1px 1px 0 #fff;
            padding: 5px 10px;
        }


        .pagination ul li.disabled a {
            visibility: hidden;
        }

        .pagination ul li.copy a {
            text-indent: -10000px;
        }

        .pagination ul li.previous a {
            background-position: -28px 0;
        }

        .pagination ul li.previous a:hover {
            background-position: -28px -27px;
        }

        .pagination ul li.number a:hover {
            background-position: 0 -27px;
        }

        .pagination ul li.next a {
            background-position: -112px 0;
        }

        .pagination ul li.next a:hover {
            background-position: -112px -27px;
        }

        .pagination ul li.copy.disabled a {
            cursor: default;
        }

        .pagination ul li.active a {
            background-position: right 0;
            color: #fff;
            cursor: default;
            text-shadow: 0 1px 0 #585858;
        }

        .pagination ul li.active a:hover {
            background-position: right 0;
        }
        .select{
            height: 30px;
        }
    </style>
</head>
<body>
<?php
$user = admin_user_service::service()->getLogin();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2" id="nav">
            <div class="row">
                <div class="col-sm-12 padding-5">
                    <li class="glyphicon glyphicon-heart"></li>
                    &nbsp;&nbsp;欢迎&nbsp;&nbsp;<a href="<?php echo url('/admin'); ?>" class="btn btn-info btn-xs"><?php echo $user['nickname']; ?></a>&nbsp;&nbsp;
                    <span class="dropdown">
                        <a class="dropdown-toggle white" id="dropdownMenu1" data-toggle="dropdown" href="javascript:;">
                            <span class="glyphicon glyphicon-cog"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                            <li role="presentation"><a role="menuitem" href="<?php echo url('admin/logout'); ?>"><span
                                        class="glyphicon glyphicon-off"></span>&nbsp;&nbsp;退出</a></li>
                        </ul>
                    </span>
                </div>
            </div>
            <div class="row">
            <?php echo zl_widget::widget()->create("admin.nav"); ?>
            </div>
        </div>

        <div class="col-md-10">
            <?php echo $layout_content; ?>
        </div>

    </div>
</div>
<script>
    $(function () {
        $(".datepicker").each(function(){
            $(this).datetimepicker({
               language: 'zh-CN',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1
            });
        });

        $(".tip").each(function(){
            $(this).tooltip();
        });

        $("#nav").attr('style',"min-height:"+$(window).height()+"px; height:auto!important;");

        $(window).scroll(function(){
            var scrollTop = $(window).scrollTop();
            if (scrollTop>0) {
                $('#nav').css('min-height', $(window).height()+scrollTop);
            }
        });


        $('#nav .menup .header').hover(function () {
            if ($(this).is(".glyphicon-chevron-down")) {
                $(this).children(".badge").children("span").toggleClass('glyphicon-chevron-up');
            } else {
                $(this).children(".badge").children("span").toggleClass('glyphicon-chevron-down');
            }
        });

        // navbar collapse
        $('#nav .menup .header').click(function (event) {
            $(this).parent().children(".child").each(function () {
                $(this).toggleClass('block');
                if ($(this).is(".block")) {
                    $(this).parent().children(".header").children(".badge").children("span").addClass('glyphicon-chevron-up');
                } else {
                    $(this).parent().children(".header").children(".badge").children("span").addClass('glyphicon-chevron-down');
                }
            });
        });

        $('#nav .menup .child').each(function () {
            $(this).click(function () {
                $('#nav .menup .child').each(function () {
                    $(this).removeClass("active");
                });
                $(this).addClass("active");
            });
        });


        $('#nav .menup  .active').parent().children(".child").each(function () {
            $(this).toggleClass('block');
            if ($(this).is(".block")) {
                $(this).parent().children(".header").children(".badge").children("span").addClass('glyphicon-chevron-up');
            } else {
                $(this).parent().children(".header").children(".badge").children("span").addClass('glyphicon-chevron-down');
            }
        });
    });
</script>
</body>
</html>