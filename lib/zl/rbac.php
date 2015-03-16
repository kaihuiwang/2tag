<?php
class zl_rbac{

    protected static $_instance = null;

    /**
     * @return zl_rbac
     */
    public static function rbac()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }


    function addRole($name){
        return zl::dao("role")->insert(array("name"=>$name));
    }


    function addNode($name,$rules,$pnodeName=0){
        if(is_array($rules)){
            foreach($rules as $rule){
                $this->addNode($name,$rule,$pnodeName);
            }
        }else{
            if($pnodeName){
                $get = zl::dao("node")->get(array("name"=>$pnodeName));
                if(!$get) return errorReturn("父节点不存在!");
                return zl::dao("node")->insert(array("name"=>$name,"rule"=>$rules,"pid"=>$get['id']));
            }else{
                return zl::dao("node")->insert(array("name"=>$name,"rule"=>$rules,"pid"=>0));
            }
        }

    }

    function setRole($roleName,$nodeRules){
        $role = zl::dao("role")->get(array("name"=>$roleName));
        if($role){
           $roleId =  $role['id'];
        }else{
            $roleId =  $this->addRole($roleName);
        }
        if(is_array($nodeRules)){
            foreach($nodeRules as $v){
                $this->setRole($roleName,$v);
            }
        }else{
            $data=array();
            $data['role_id'] = $roleId ;
            $node = zl::dao("node")->get(array("rule"=>$nodeRules));
            if($node){
                $nodeId = $node['id'];
            }else{
                $nodeId = $this->addNode($nodeRules,$nodeRules);
            }
            $data['node_id'] = $nodeId;
            zl::dao("access")->insert($data);
        }
        return true;
    }

    function unSetRole($roleId,$nodeId){
        zl::dao("access")->delete(array("role_id"=>$roleId,"node_id"=>$nodeId));
        return true;
    }

    function bindUser($uid,$roleIds){
        if(is_array($roleIds)){
            foreach($roleIds as $v){
                $this->bindUser($uid,$v);
            }
        }else{
            return zl::dao("role_user")->insert(array("role_id"=>$roleIds,"user_id"=>$uid));
        }
    }

    function unbindUser($uid,$roleIds){
        if(is_array($roleIds)){
            foreach($roleIds as $v){
                $this->unbindUser($uid,$v);
            }
        }else{
            return zl::dao("role_user")->delete(array("role_id"=>$roleIds,"user_id"=>$uid));
        }
    }

    function checkRule($rule,$uid){
        $roleIds = zl::dao("role_user")->getField("id",array("user_id"=>$uid),true);
        $nodes = zl::dao("access")->getField("node_id",array("role_id"=>array("in",$roleIds)));
        $check = zl::dao("node")->get(array("id"=>array("in",$nodes),"rule"=>$rule));
        return $check?true:false;
    }

    function check($uid){
        $rule = zl::get("current_route_name");
        return $this->checkRule($rule,$uid);
    }

    function getAllRoute(){
        $route = array();
        $ext = zl::config()->get("ext.names");
        if ($ext) {
            foreach ($ext as $v) {
                $extConfig = include ROOT_PATH . "/app/ext/" . $v . "/config/route.php";
                if (!$extConfig) continue;
                $route = array_merge_recursive($route,$extConfig);
            }
        }
        $routeConfig = zl::config()->get("route");
        $route = array_merge_recursive($route,$routeConfig);
        return $route;
    }


}