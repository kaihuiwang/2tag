<?php

class zl_ext_config extends zl_config
{

    function get($str)
    {
        if (!zl::get('ext_name')) throwException("当前应用不是扩展应用,不能获取扩展配置数据!");
        return $this->get($str, "app/ext/" . zl::get('ext_name') . "/config");
    }

    function set($k, $v)
    {
        if (!zl::get('ext_name')) throwException("当前应用不是扩展应用,不能获取扩展配置数据!");
        return $this->set($k, $v, "app/ext/" . zl::get('ext_name') . "/config");
    }

}