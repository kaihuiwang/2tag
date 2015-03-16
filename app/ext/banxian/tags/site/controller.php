<?php
class ext_banxian_tags_site_controller extends zl_ext_controller
{
    function index($p=1){
        $this->setLayout("main");
        $where = array();
        $orderBy = "zl_count DESC,id DESC";
        $p = (int) $p;
        $where['is_publish'] = 1;
        list($list,$markup) = zl::dao("tag")->pager($where,$orderBy,"",$p,"/tags-@p",200);
        $this->list = $list;
        $this->markup = $markup;
        $this->title="全部标签";

        $sitedataTmp = zl::dao("data")->gets();
        $sitedata = array();
        if($sitedataTmp){
            foreach($sitedataTmp as $v){
                $sitedata[$v['zl_key']] = $v['zl_value'];
            }
        }
        $this->sitedata =$sitedata;

        $this->display();
    }
}