<?php
class ext_banxian_wikipedia_site_service extends zl_ext_service{
    function getWiki($tagName){
        if(!$tagName) return "";
        $key = md5("tag_wiki".$tagName);
        $cache = zl_cache::get($key);
        if($cache){
            return $cache;
        }
        $wurl="http://zh.wikipedia.org/w/api.php?action=query&list=search&srwhat=text&format=json&srlimit=1&srprop=redirecttitle&srsearch=".$tagName;
        $data = file_get_contents($wurl);
        $arr = json_decode($data,true);
        if(!$arr){
            zl_cache::set($key,"",24*60*60);
            return "";
        }
        $kword = "";
        if(isset($arr['query']['search']) && $arr['query']['search']){
            $tmp = current($arr['query']['search']);
            $kword =  (isset($tmp['title']) && $tmp['title'])?$tmp['title']:"";
        }
        if($kword==''){
            zl_cache::set($key,"",24*60*60);
            return "";
        }
        $url ="http://zh.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exintro=&titles=".$kword;
        $data = file_get_contents($url);
        $arr = json_decode($data,true);
        if(!$arr){
            zl_cache::set($key,"",24*60*60);
            return "";
        }
        if(isset($arr['query']['pages']) && $arr['query']['pages']){
            $tmp = current($arr['query']['pages']);
            $html= (isset($tmp['extract']) && $tmp['extract'])?$tmp['extract']:"";
            $html= str_replace("\n","",$html);
            $html= str_replace("\r","",$html);
            $html = zl_tool_zhconversion::zhconversion_cn($html);
            zl_cache::set($key,$html,24*60*60);
            return $html;
        }
        return "";
    }
}