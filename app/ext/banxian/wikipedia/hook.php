<?php
zl_hook::create("footer",function(){
    echo '<script>
    $(function(){
        $(".my_tag").each(function(){
                $(this).hover(function() {
                $(".my_tag").each(function(){
                        $(this).popover("destroy");
                  });
                    var e=$(this);
                    var name = e.html();
                    ajax_get("'.url("/wikipedia/get").'/"+name,function(d) {
                        if(d.status){
                        e.popover({"content": d.data,"html":true,"title":"来自维基百科的数据","container":"body"}).popover("show");
                        }else{
                        e.popover({"content": "<p>没有相关数据</p>","html":true,"container":"body"}).popover("show");
                        }
                        });
                        },function() {
                            $(".my_tag").each(function(){
                                    $(this).popover("destroy");
                              });
                        });
                        });
            });
            </script>';
});