<?php
zl_hook::create("top_nav",function(){
    echo "<li><a href=\"".url("/tags")."\">标签</a></li>";
});