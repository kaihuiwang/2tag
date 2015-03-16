<?php
class ext_banxian_backup_site_controller extends zl_ext_controller
{
    public $layout='admin';
    function index($p=1){
        $p = (int) $p;
        $where = array();
        $orderBy="ctime desc";
        list($list,$markup) = zl::dao("ext_backup")->pager($where,$orderBy,"",$p,"/admin/backup/index-@p");
        $this->list = $list;
        $this->markup = $markup;
        $this->display();
    }

    function add(){
        $path = ROOT_PATH . "/data/backup/";
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        $title = date('YmdHis');
        $sql = "SHOW TABLES";
        $tales = zl::dao()->selectAll($sql);

        if($tales){
            foreach($tales as $v){
               $tableNameArr = array_values($v);
                $tableName = $tableNameArr[0];
                $sql = zl::dao()->backup($tableName);
                file_put_contents($path.$title.".sql",$sql,FILE_APPEND);
            }
            $data=array();
            $data['title'] = $title;
            $data['path'] = "/data/backup/".$title.".sql";
            zl::dao("ext_backup")->insert($data);
        }
        $this->redirect("/admin/backup/index");
    }

    function delete($id){
        $info = zl::dao("ext_backup")->get(array("id"=>$id));
        if(!$info){
            $this->redirect("/admin/backup/index");
        }
        zl::dao("ext_backup")->delete(array("id"=>$id));
        $path = ROOT_PATH .$info['path'];
        if(is_file($path)){
            unlink($path);
        }
        $this->redirect("/admin/backup/index");
    }

    function import($id){
        $info = zl::dao("ext_backup")->get(array("id"=>$id));
        if(!$info){
            $this->redirect("/admin/backup/index");
        }
        $path = ROOT_PATH .$info['path'];
        if(is_file($path)){
            zl::dao()->import($path);
        }
        $this->redirect("/admin/backup/index");
    }
}