<?php
//http://image.intervention.io/getting_started/installation
/**
 * GD
 * Image::configure(array('driver' => 'imagick'));
 * // and you are ready to go ...
 * $image = Image::make('public/foo.jpg')->resize(300, 200);
 */
use Intervention\Image\ImageManagerStatic as Image;

class zl_image extends Image
{
    protected static $_instance = null;

    /**
     * @return Image
     */
    public static function image()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }
}