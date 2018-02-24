<?php
class ext_banxian_member_site_controller extends zl_ext_controller
{
    function index(){
        $this->setLayout("main");
        $member = zl::dao("user")->gets(array()," arc_number DESC",0,40);
        $this->member =$member;


        $sitedataTmp = zl::dao("data")->gets();
        $sitedata = array();
        if($sitedataTmp){
            foreach($sitedataTmp as $v){
                $sitedata[$v['zl_key']] = $v['zl_value'];
            }
        }
        $this->sitedata =$sitedata;

        $this->title='会员';
        $this->display();
    }
}