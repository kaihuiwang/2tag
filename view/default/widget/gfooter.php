<?php
$footmenu = zl::dao("menu")->gets(array("zl_type"=>2),"zl_sort asc");
?>
<footer  style="background: #fff; ">
    <div class="container">
        <div class="center" >
            <div class="sep10"></div>
            <strong>
            <?php if($footmenu): ?>
                <?php foreach($footmenu as $v):
                       $url = stristr($v['zl_url'],'http')?$v['zl_url']:url($v['zl_url']);
                    ?>
                <a href="<?php echo $url;?>" class="dark" target="_self"><?php echo $v['title']; ?></a> &nbsp; <span class="snow">·</span> &nbsp;
              <?php
              endforeach;
              endif;?>
                <?php
                zl_hook::run("footer_nav");
                ?>
                <?php echo site_user_service::service()->getOnlineUserNumber(); ?> 人在线
            </strong>
        </div>
            <p class="center sep10">© 2015 <a href="http://2tag.cn">2tag.cn</a> 版权所有&nbsp;&nbsp;<?php echo zl::$configApp['beian']; ?></p>
    </div>
</footer>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body" id="mymodelContent">

            </div>
        </div>
    </div>
</div>
<?php
zl_hook::run("footer");
$routeName= zl::get("current_route_name");
zl_hook::run($routeName);
?>