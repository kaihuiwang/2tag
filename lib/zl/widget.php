<?php

class zl_widget
{
    protected static $_instance = null;

    /**
     * @return zl_widget
     */
    static function widget()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    static function create($widgetName, $params = null,$extName='')
    {
        $widgetName = "widget." . $widgetName;
        $widgetTmp = str_replace('.', '/', $widgetName);
        $name = 'widget_' . str_replace('.', '_', $widgetName);
        $extNameTmp = $extName?$extName:zl::get("ext_name");
        if ($extNameTmp) {
            $tpl = zl::config()->getExt("app.tpl");
            $tpl = $tpl?$tpl."/":"";
            $path = ROOT_PATH . "/app/ext/" . $extNameTmp . "/view/".$tpl.$widgetTmp.".php";
            if(!is_file($path)){
                zl::render($widgetTmp, is_object($params) ? null : $params, $name);
            }else{
                zl::render($widgetTmp, $params, null, ROOT_PATH . "/app/ext/" . $extNameTmp . "/view/".$tpl);
            }

        } else {
            zl::render($widgetTmp, is_object($params) ? null : $params, $name);
        }
        return zl::view()->get($name);
    }

}