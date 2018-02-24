<?php
class ext_banxian_wikipedia_site_controller extends zl_ext_controller
{

    function get($name=""){
        if(!$name){
            $this->showJsonp(0,"无相关数据");
        }
        $html= ext_banxian_wikipedia_site_service::service()->getWiki($name);
        if(!$html){
            $this->showJsonp(0,$html);
        }
        $this->showJsonp(1,$html);
    }
}