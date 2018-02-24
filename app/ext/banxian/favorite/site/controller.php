<?php
class ext_banxian_favorite_site_controller extends zl_ext_controller
{
    function todo($id=0){
        if(!$id) $this->showMsg("操作失败!");
        $uid = $this->getUid();
        $info = zl::dao("ext_favorite")->get(array("arc_id"=>$id,"uid"=>$uid));
        if($info){
            zl::dao("ext_favorite")->delete(array("arc_id"=>$id,"uid"=>$uid));
        }else{
            zl::dao("ext_favorite")->insert(array("arc_id"=>$id,"uid"=>$uid));
        }
        $this->redirect("/v-1-".$id);
    }

    function my($p=0){
        $this->setLayout("main");
        $uid = $this->getUid();

        $p = (int) $p;
        $p = $p?$p:1;
        $pageSize = zl::$configApp['page_size'];
        $sql = "SELECT SQL_CALC_FOUND_ROWS b.* FROM ".zl::$configApp['db']['prefix']."ext_favorite a INNER JOIN
                ".zl::$configApp['db']['prefix']."arc b ON a.arc_id=b.id WHERE a.uid=".$uid." ORDER BY ctime DESC
                 ";
        list($arc,$markup) = zl::dao("ext_favorite")->pagerSql($sql,$p,"/favorite/my-@p");
        $this->arc = $arc;
        $this->markup = $markup;

        $this->title = "我的收藏";
        $this->display();
    }

}