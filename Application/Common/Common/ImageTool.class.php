<?php

/***
水印、缩略图、验证码类
***/
namespace Common\Common;
class ImageTool {
    // 分析图片信息
    // return array()
    public static function imageInfo($image) {
        if(!file_exists($image)) {
            return false;
        }
        $info = getimagesize($image);
        if($info == false) {
            return false;
        }
        $img['width'] = $info[0];
        $img['height'] = $info[1];
        $img['ext'] = substr($info['mime'],strpos($info['mime'],'/')+1);
        $img['mime'] = $info['mime'];
        return $img;
    }

    /*
        图片加水印
        parm String $dst 待操作图片
        parm String $water 水印小图
        parm String $save,不填则默认替换原始图
    */
    public static function water($dst,$water,$save=NULL,$pos=2) {
        // 保证图片存在
        if(!file_exists($dst) || !file_exists($water)) {
            return false;
        }
        
        // 水印尺寸不能大于待操作图片
        $dinfo = self::imageInfo($dst);
        $winfo = self::imageInfo($water);

        //gif不加水印
        if ($dinfo['ext']=='gif') return false;

        if($winfo['height'] > $dinfo['height'] || $winfo['width'] > $dinfo['width']) {
            return false;
        }

        // 动态加载函数创建画布
        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $wfunc = 'imagecreatefrom' . $winfo['ext'];

        if(!function_exists($dfunc) || !function_exists($wfunc)) {
            return false;
        }

        $dim = $dfunc($dst);  // 创建待操作画布
        $wim = $wfunc($water);  // 创建水印画布


        // 计算粘贴的坐标
        switch($pos) {
            case 0: // 左上角
            $posx = 0;
            $posy = 0;
            break;

            case 1: // 右上角
            $posx = $dinfo['width'] - $winfo['width'];
            $posy = 0;
            break;

            case 3: // 左下角
            $posx = 0;
            $posy = $dinfo['height'] - $winfo['height'];
            break;
        
            default:// 右下角
            $posx = $dinfo['width'] - $winfo['width'];
            $posy = $dinfo['height'] - $winfo['height'];
        }


        // 加水印
        imagecopy($dim,$wim, $posx , $posy , 0 , 0 , $winfo['width'] , $winfo['height']);

        // 保存
        if(!$save) {
            $save = $dst;
            unlink($dst); // 删除原图
        }

        $createfunc = 'image' . $dinfo['ext'];
        $createfunc($dim,$save);

        imagedestroy($dim);
        imagedestroy($wim);

        return true;
    }

    /**
        thumb 生成缩略图
        等比例缩放,两边留白
    **/
    public static function thumb($dst,$save=NULL,$width=200,$height=200) {
        // 首先判断待处理的图片存不存在
        $dinfo = self::imageInfo($dst);
        if($dinfo == false) {
            return false;
        }

        // 计算缩放比例
        $calc = min($width/$dinfo['width'], $height/$dinfo['height']);

        // 创建原始图的画布
        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $dim = $dfunc($dst);

        // 创建缩略画布
        $tim = imagecreatetruecolor($width,$height);

        // 创建白色填充缩略画布
        $white = imagecolorallocate($tim,255,255,255);

        // 填充缩略画布
        imagefill($tim,0,0,$white);

        // 复制并缩略
        $dwidth = (int)$dinfo['width']*$calc;
        $dheight = (int)$dinfo['height']*$calc;

        $paddingx = (int)($width - $dwidth) / 2;
        $paddingy = (int)($height - $dheight) / 2;


        imagecopyresampled($tim,$dim,$paddingx,$paddingy,0,0,$dwidth,$dheight,$dinfo['width'],$dinfo['height']);

        // 保存图片
        if(!$save) {
            $save = $dst;
            unlink($dst);
        }

        $createfunc = 'image' . $dinfo['ext'];
        $createfunc($tim,$save);

        imagedestroy($dim);
        imagedestroy($tim);

        return true;

    }

    //验证码
    public static function captcha($width=70,$height=30) {
            //创建画布
            $image = imagecreatetruecolor($width,$height) ;
           
            //创建背影色
            $gray = imagecolorallocate($image, 200, 200, 200);
           
            //填充背景
            imagefill($image, 0, 0, $gray);
           
            //创建随机字体颜色
            $color = imagecolorallocate($image, mt_rand(0, 125), mt_rand(0, 125), mt_rand(0, 125)) ;
            //创建随机线条颜色
            $color1 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
            $color2 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
            $color3 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
           
            //在画布上画线
            imageline($image, mt_rand(0, 70), mt_rand(0, 30), mt_rand(0, 70), mt_rand(0, 30), $color1) ;
            imageline($image, mt_rand(0, 70), mt_rand(0, 30), mt_rand(0, 70), mt_rand(0, 30), $color2) ;
            imageline($image, mt_rand(0, 70), mt_rand(0, 30), mt_rand(0, 70), mt_rand(0, 30), $color3) ;
           
            //在画布上写字
            $text = substr(str_shuffle('ABCDEFGHJKMNPRTUVWXYZabcdefghijkmnprstuvwxyz23456789'), 0,4) ;
            imagestring($image, 5, 15, 7, $text, $color) ;
            $_SESSION['captcha'] = strtolower($text);
            //显示、销毁
            header('content-type: image/jpeg');
            imagejpeg($image);
            imagedestroy($image);
    }


}