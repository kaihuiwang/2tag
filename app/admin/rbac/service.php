<?php
class admin_rbac_service extends zl_service
{

    /**
     *
    color: "#000000",
    backColor: "#FFFFFF",
    href: "#node-1",
     * @param int $pid
     * @return array
     */
    function getAllDepts($selectedDept=-1){
        $result = array("id"=>0,"text"=>"所有","color"=>"#333","backColor"=>"#fff","href"=>"#fff","nodes"=>array());
        $nodes = $this->getAllDeptsNodes(0,$selectedDept);
        $result['nodes'] = $nodes;
        if($selectedDept==0){
            $result['color'] = "#fff";
            $result['backColor'] = "#333";
        }
        return array($result);
    }


    function getAllDeptsNodes($pid=0,$selectedDept=0){
        $depts = zl::dao("dept")->gets(array("pid"=>$pid));
        if(!$depts){
            return false;
        }
        $nodes = array();
        foreach($depts as $k=>$v){
            $nodes[$k]['id'] = $v['id'];
            $nodes[$k]['text'] = $v['name'];

            //showBorder
//            echo $selectedDept."|".$v['id']."<br>";
            if($selectedDept == $v['id']){
                $nodes[$k]['backColor'] = "#333";
                $nodes[$k]['color'] = "#fff";
                //selectable
            }else{
                $nodes[$k]['backColor'] = "#fff";
                $nodes[$k]['color'] = "#333";
            }
            $nodes[$k]['href'] = "#fff";
            $rs = $this->getAllDeptsNodes($v['id'],$selectedDept);
            if($rs){
                $nodes[$k]['nodes'] = $rs;
            }
        }
        return $nodes;
    }

    function getAllChildDepts($id,&$result){
        $depts = zl::dao("dept")->gets(array("pid"=>$id));
        if(!$depts){
            return false;
        }
        foreach($depts as $v){
            $result[] = $v['id'];
            $this->getAllChildDepts($v['id'],$result);
        }
        return $result;
    }


    function viewSex($sex){
        switch($sex){
            case 0: return "保密";
            case 1: return "男";
            case 2:return "女";
            default: return "保密";
        }
    }

    function viewRole($roleId){
        return zl::dao("role")->getField("name",array("id"=>$roleId));
    }

    function viewDept($deptId){
        return zl::dao("dept")->getField("name",array("id"=>$deptId));
    }

    function getNodes(){
        $routes = zl::config()->get("route");
        $result = array();
        if(!$routes) return $result;
        foreach($routes as $k=>$v){
            if(!isset($v['name']) || !isset($v['group_name'])||!$v['name'] || !$v['group_name']) continue;
                $result[$v['group_name']][] = array($v['route'],$v['name'],$k);
        }
        return $result;
    }


    function getNodesByRole($roleId){
        $node = zl::dao("access")->getField("node",array("role_id"=>$roleId),true);
        $routes = zl::config()->get("route");
        $result = array();
        if(!$routes) return $result;
        foreach($node as $v){
            $arr = $routes[$v];
            $result[$arr['group_name']][] = array($arr['route'],$arr['name'],$v);
        }
        return $result;
    }

    function getNodeKey(){
        $routes = zl::config()->get("route");
        $result = array();
        if(!$routes) return $result;
        foreach($routes as $k=>$v){
            if(!isset($v['name']) || !isset($v['group_name'])|| !$v['name'] || !$v['group_name']) continue;
            $result[] = $k;
        }
        return $result;
    }

    function check(){
        $admin = admin_user_service::service()->getLogin();
        $site = site_user_service::service()->getLogin();
        $access = array();
        if(isset($admin['access'])){
            $access = $admin['access'];
        }
        if(isset($site['access'])){
            $access = array_merge($access,$site['access']);
        }
        $nodeKey = $this->getNodeKey();
        $current_route_name = zl::get('current_route_name');
        if(!in_array($current_route_name,$nodeKey)) return true;
        if(in_array($current_route_name,$access)) return true;
        zl::showMsg("你没有访问这个页面的权限!","",0,2);
        exit;
    }

    function checkRight($route_name){
        $admin = admin_user_service::service()->getLogin();
        $site = site_user_service::service()->getLogin();
        $access = array();
        if(isset($admin['access'])){
            $access = $admin['access'];
        }
        if(isset($site['access'])){
            $access = array_merge($access,$site['access']);
        }
        $nodeKey = $this->getNodeKey();
        if(!in_array($route_name,$nodeKey)) return true;
        if(in_array($route_name,$access)) return true;
        return false;
    }


}