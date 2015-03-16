<?php
return array(
    "register_email"=>array("body"=>'
<h2>欢迎注册#site_name#</h2><p>请记住本站网址#site_url#</p><p>点击下面链接激活您的帐号:</p>
<p><a href="#validate_url#" target="_blank">#validate_url#</a></p>
',
        "title"=>"欢迎注册#site_name#,请激活您的邮箱!",
    ),
    "find_email"=>array("body"=>'
<h2>欢迎访问#site_name#</h2><p>请记住本站网址#site_url#</p><p>点击下面链接重新设置您的密码:</p>
<p><a href="#validate_url#" target="_blank">#validate_url#</a></p>
',
        "title"=>"#site_name#,密码找回!",
    ),
);