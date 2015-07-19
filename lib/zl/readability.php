<?php
include_once ROOT_PATH . "/lib/thirdpart/readability/Readability.php";
class zl_readability
{
    protected static $_instance = null;

    /**
     * @return Parsedown
     */
    public static function readability()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

  static function parse($html,$url=null){
        if(!$html) return false;
        if (function_exists('tidy_parse_string')) {
            $tidy = tidy_parse_string($html, array(), 'UTF8');
            $tidy->cleanRepair();
            $html = $tidy->value;
        }
        $readability = new Readability($html, $url);
        $readability->debug = false;
        $readability->convertLinksToFootnotes = false;
        $result = $readability->init();
        if (!$result) return array("","");
        $title = $readability->getTitle()->textContent;
        $content = $readability->getContent()->innerHTML;
        if (function_exists('tidy_parse_string')) {
            $tidy = tidy_parse_string($content, array('indent'=>true, 'show-body-only' => true), 'UTF8');
            $tidy->cleanRepair();
            $content = $tidy->value;
        }
        return array($title,$content);
    }
}