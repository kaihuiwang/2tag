<?php
if($pageCount && $pageCount > 1):
    $p = $current;

    $end = $p+5;
    $start = $p-5;
    $end = $end > $pageCount ? $pageCount+1 : $end;
    $start = $start < 1 ? 1 : $start;
    ?>
    <ul class="gpages clearfix pagination">
        <?php if($page >1):?>
            <li><a href="<?php echo pagiUrl(1, $pageCount,$currentUrl); ?>">首页</a></li>
            <li><a href="<?php echo pagiUrl($previous, $pageCount,$currentUrl); ?>">上一页</a></li>
        <?php endif;?>
        <?php
        for($start; $start <$end ; $start++):
            ?>
            <?php if($page == $start):?>
            <li class="number active">
            <span><?php echo $start; ?></span>
            </li>
        <?php else:?>
            <li class="number">
            <a href="<?php echo pagiUrl($start, $pageCount,$currentUrl); ?>"><?php echo $start; ?></a>
            </li>
        <?php endif;?>
        <?php endfor;?>
        <?php
        if(($pageCount- $page)>4):
        ?>
        <li class="disabled"><span>...</span></li>
        <?php endif;?>
        <?php if($next > $page):?>
            <li class="copy next"><a href="<?php echo pagiUrl($next, $pageCount,$currentUrl); ?>">下一页</a></li>
            <li class="copy next"><a href="<?php echo pagiUrl($pageCount, $pageCount,$currentUrl); ?>">末页</a></li>
        <?php endif;?>
    </ul>
<?php
endif;
?>