<?php
class ext_banxian_news_index_service extends zl_ext_service{
    function updateScore($id){
        if(!$id) return ;
        $check = zl::dao("ext_news_list")->get(array("id"=>$id));
        if(!$check) return ;
        $version = date('YmdH');
        $num = $check['reply_count']+$check['good_number'];
        $score = site_index_service::service()->getScore($num,date('Y-m-d H:i:s'),$check['ctime'],0);
        zl::dao("ext_news_list")->update(array("zl_score"=>$score,"score_version"=>$version),array("id"=>$id));
        return true;
    }

    function getContent($id){
        $arc = zl::dao("ext_news_list")->get(array("id"=>$id));
        if($arc['content']){
            return $arc['content'];
        }
        $html = curlGetContents($arc['url']);
        $domainUrl = $this->getUrldomain($arc['url']);
        list($title,$content) = zl_readability::parse($html,"http://".$domainUrl);
        zl::dao("ext_news_list")->update(array("content"=>$content),array("id"=>$id));
        return $content;
    }

    function getUrldomain($url){
        $arr = parse_url($url);
        return isset($arr['host'])?$arr['host']:"";
    }


    function scoreJob(){
        $version = date('YmdH');
        $arcIds = zl::dao("ext_news_list")->getField("id",array("score_version"=>array("<",$version),"is_publish"=>1),true,"score_version ASC",0,10);
        if($arcIds) {
            foreach ($arcIds as $v) {
                $this->updateScore($v);
            }
        }
    }
}