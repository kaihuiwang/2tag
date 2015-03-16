<?php

class ext_banxian_zhuanti_site_index_service extends zl_ext_service{

    function tuijian($params){
        $tagsId= $params['tag_id'];
        $url = url('/add_zhuanti_tag/'.$tagsId);
        $str = "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:showModel('".$url."','添加到专题');\">添加到专题</a>";
        return $str;
    }

}