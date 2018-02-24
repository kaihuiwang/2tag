<?php
//生成图像缩略图和生成验证码
class Image
{//类定义开始

    /**
     +----------------------------------------------------------
     * 取得图像信息
     *
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image 图像文件名
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    static function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if( $imageInfo!== false)
		 {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            $imageSize = filesize($img);
            $info = array(
                "width"=>$imageInfo[0],
                "height"=>$imageInfo[1],
                "type"=>$imageType,
                "size"=>$imageSize,
                "mime"=>$imageInfo['mime']
            );
            return $info;
        }else {
            return false;
        }
    }



    /**
     +----------------------------------------------------------
     * 生成缩略图
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image  原图
     * @param string $type 图像格式
     * @param string $thumbname 缩略图文件名
     * @param string $maxWidth  宽度
     * @param string $maxHeight  高度
     * @param string $position 缩略图保存目录
     * @param boolean $interlace 启用隔行扫描
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function thumb($image,$thumbname,$type='',$maxWidth=200,$maxHeight=50,$interlace=true)
    {
        // 获取原图信息
        $info  = Image::getImageInfo($image);
         if($info !== false)
		  {
            $srcWidth  = $info['width'];
            $srcHeight = $info['height'];
            $type = empty($type)?$info['type']:$type;
			$type = strtolower($type);
            $interlace  =  $interlace? 1:0;
            unset($info);
            $scale = min($maxWidth/$srcWidth, $maxHeight/$srcHeight); // 计算缩放比例
            if($scale>=1) 
			{
                // 超过原图大小不再缩略
                $width   =  $srcWidth;
                $height  =  $srcHeight;
            }
			else
			{
                // 缩略图尺寸
                $width  = (int)($srcWidth*$scale);
                $height = (int)($srcHeight*$scale);
            }

            // 载入原图
            $createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
            $srcImg     = $createFun($image);

            //创建缩略图
            if($type!='gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);

            // 复制图片
            if(function_exists("ImageCopyResampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth,$srcHeight);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height,  $srcWidth,$srcHeight);
				
            if('gif'==$type || 'png'==$type) 
			{
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一个绿色
				imagecolortransparent($thumbImg,$background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
            }

            // 对jpeg图形设置隔行扫描
            if('jpg'==$type || 'jpeg'==$type) 	
			     imageinterlace($thumbImg,$interlace);

            // 生成图片
            $imageFun = 'image'.($type=='jpg'?'jpeg':$type);
            $imageFun($thumbImg,$thumbname);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $thumbname;
         }
         return false;
    }


    /**
     +----------------------------------------------------------
     * 生成图像验证码
     +----------------------------------------------------------
     * @static
     * @access public
     * @param string $width  宽度
     * @param string $height  高度
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static function buildImageVerify($width=48,$height=22,$randval=NULL,$verifyName='verify')
    {
		
		$randval =empty($randval)? ("".rand(1000,9999)):$randval;
		
        $_SESSION[$verifyName]= $randval;
		$length=4;
        $width = ($length*10+10)>$width?$length*10+10:$width;

        $im = imagecreate($width,$height);
  
        $r = array(225,255,255,223);
        $g = array(225,236,237,255);
        $b = array(225,236,166,125);
        $key = mt_rand(0,3);

        $backColor = imagecolorallocate($im, $r[$key],$g[$key],$b[$key]);    //背景色（随机）
		$borderColor = imagecolorallocate($im, 100, 100, 100);                    //边框色
        $pointColor = imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));                 //点颜色

        @imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        @imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
        $stringColor = imagecolorallocate($im,mt_rand(0,200),mt_rand(0,120),mt_rand(0,120));
		// 干扰
		for($i=0;$i<10;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
		}
		for($i=0;$i<25;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pointColor);
		}
		for($i=0;$i<$length;$i++) {
			imagestring($im,5,$i*10+5,mt_rand(1,8),$randval{$i}, $stringColor);
		}
        Image::output($im,'png');
    }

    static function output($im,$type='png',$filename='')
    {
        header("Content-type: image/".$type);
        $ImageFun='image'.$type;
		if(empty($filename)) {
	        $ImageFun($im);
		}else{
	        $ImageFun($im,$filename);
		}
        imagedestroy($im);
		exit;
    }
	
     /**
     * +----------------------------------------------------------
     * 图片水印
     * +----------------------------------------------------------
     * @$image  原图
     * @$water 水印图片
     * @$$waterPos 水印位置(0-9) 0为随机，其他代表上中下9个部分位置
     * +----------------------------------------------------------
     */

    static function water($image, $water, $waterPos =9)
    {
	    //检查图片是否存在
        if (!file_exists($image) || !file_exists($water))
            return false;
	   //读取原图像文件
        $imageInfo = self::getImageInfo($image);
        $image_w = $imageInfo['width']; //取得水印图片的宽
        $image_h = $imageInfo['height']; //取得水印图片的高
        $imageFun = "imagecreatefrom" . $imageInfo['type'];
        $image_im = $imageFun($image);
        
        //读取水印文件
        $waterInfo = self::getImageInfo($water);
        $w = $water_w = $waterInfo['width']; //取得水印图片的宽
        $h = $water_h = $waterInfo['height']; //取得水印图片的高
        $waterFun = "imagecreatefrom" . $waterInfo['type'];
        $water_im = $waterFun($water);

        switch ($waterPos) {
            case 0: //随机
                $posX = rand(0, ($image_w - $w));
                $posY = rand(0, ($image_h - $h));
                break;
            case 1: //1为顶端居左
                $posX = 0;
                $posY = 0;
                break;
            case 2: //2为顶端居中
                $posX = ($image_w - $w) / 2;
                $posY = 0;
                break;
            case 3: //3为顶端居右
                $posX = $image_w - $w;
                $posY = 0;
                break;
            case 4: //4为中部居左
                $posX = 0;
                $posY = ($image_h - $h) / 2;
                break;
            case 5: //5为中部居中
                $posX = ($image_w - $w) / 2;
                $posY = ($image_h - $h) / 2;
                break;
            case 6: //6为中部居右
                $posX = $image_w - $w;
                $posY = ($image_h - $h) / 2;
                break;
            case 7: //7为底端居左
                $posX = 0;
                $posY = $image_h - $h;
                break;
            case 8: //8为底端居中
                $posX = ($image_w - $w) / 2;
                $posY = $image_h - $h;
                break;
            case 9: //9为底端居右
                $posX = $image_w - $w;
                $posY = $image_h - $h;
                break;
            default: //随机
                $posX = rand(0, ($image_w - $w));
                $posY = rand(0, ($image_h - $h));
                break;
        }

        //设定图像的混色模式
        
        imagealphablending($image_im, true);

        imagecopy($image_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h); //拷贝水印到目标文件

        //生成水印后的图片
        $bulitImg = "image" . $imageInfo['type'];
        $bulitImg($image_im, $image);
        //释放内存
        $waterInfo = $imageInfo = null;
        imagedestroy($image_im);
    }

}//类定义结束
?>