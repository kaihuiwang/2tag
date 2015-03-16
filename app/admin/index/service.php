<?php

class admin_index_service extends zl_service
{
    function checklogin()
    {
        $adminloginInfo = admin_user_service::service()->isLogin();
        if (!$adminloginInfo) {
            $this->redirect("/admin/login");
        }
        return true;
    }

    function parseSearchWhere(){
        $fieldsVl = zl::getParam("fields");
        $selectVl = zl::getParam("select");
        $whereVl = (array) zl::getParam("where");
        $hidden = zl::getParam("hidden");
        array_unshift($whereVl,"and");
        $vl = zl::getParam("vl");
        $row = array();
        if($fieldsVl) {
            foreach ($fieldsVl as $k => $v) {
                if ($v == '' || $vl[$v][$k] == '') {
                    continue;
                }
                $value = trim($vl[$v][$k]);
                if ($selectVl[$k] == 'like' || $selectVl[$k] == 'not like') {
                    $value = "%" . $value . "%";
                }
                $row[$v] = array($selectVl[$k], $value, $whereVl[$k]);
            }
        }

        if($hidden){
            foreach($hidden as $k=>$v){
                if(!empty($v)) $row[$k] = $v;
            }
        }

        return $row;
    }

}