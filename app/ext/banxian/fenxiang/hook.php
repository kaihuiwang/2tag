<?php
zl_hook::create("site.index.v",function($params){
echo zl_widget::widget()->create("fenxiang",null,"banxian/fenxiang");
});