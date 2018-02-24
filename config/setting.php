<?php
return array(
    "file"=>array("app"=>"基本配置","cache"=>"缓存配置","lang"=>"语言模板配置"),
    "struct"=>array(
        "app"=>array(
            "debug" => array("是否开启调试模式","select",array("1"=>"是","0"=>"否")),
            'siteHalt' => array("是否开启网站维护模式","select",array("1"=>"是","0"=>"否")),//网站维护
            'haltMsg' => array('网站维护输出字段',"textarea"),
            "isRewrite" => array("是否屏蔽index.php","select",array("1"=>"是","0"=>"否")),
            "beian"=>array("备案号","text"),
            "static_version" => array("静态文件版本","text"),
            "page_size"=> array("全站分页每页条数","text"),
            "rand_job"=> array("自动脚本运行几率","text"),
            "super_admin"=> array("超级管理员email","text"),
            "db.host"=>array("数据库主机地址","text"),
            "db.user"=>array("数据库用户名","text"),
            "db.password"=>array("数据库密码","password"),
            "db.db_name"=>array("数据库名","text"),
            "db.prefix"=>array("数据库前缀","text"),
            "siteTitle" => array("网站标题","text"),
            "siteName"=>array("网站名称","text"),
            "siteUrl"=>array("网站地址","text"),
            "mail.enable"=>array("是否开启发送邮件","select",array("1"=>"是","0"=>"否")),
            "mail.smtp.host"=>array("邮件SMTP服务器地址","text"),
            "mail.smtp.username"=>array("邮件SMTP服务器用户名","text"),
            "mail.smtp.password"=>array("邮件SMTP服务器密码","password"),
            "mail.smtp.secure"=>array("邮件SMTP服务器安全协议(比如:ssl)","text"),
            "mail.smtp.port"=>array("邮件SMTP服务器端口","text"),
            "mail.smtp.timeout"=>array("邮件SMTP服务器超时时间","text"),
            "memcached"=>array("memcache服务器信息，多个用英文逗号','隔开","text"),
            "tpl"=>array("网站模版","text"),
            "arc_timeout"=>array("文章多长时间不能编辑，单位s（秒）","text"),
        ),
        "cache"=>array(
            "storage"=>array("缓存保存方式","select",array("auto"=>"自动", "apc"=>"apc" ,"xcache"=>"xcache","wincache"=>"wincache" ,"memcache"=>"memcache" ,"memcached"=>"memcached" ,"files"=>"文件")),
        ),
        "ext"=>array(),
        "lang"=>array(
            "register_email.title"=>array("注册邮件标题","textarea"),
            "register_email.body"=>array("注册邮件内容","textarea"),
            "find_email.title"=>array("找回密码邮件标题","textarea"),
            "find_email.body"=>array("找回密码内容","textarea"),
        ),
    )
);