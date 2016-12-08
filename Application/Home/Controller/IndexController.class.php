<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function index(){
		if (isPC()) {
			header('Location: '.__ROOT__.'/Forum');
		} else {
			header('Location: '.__ROOT__.'/Mobile/switchType');
		}
	}
	public function image($img){
		$path = dirname(THINK_PATH).'/Public/image/';
		$img = $path.$img.'.'.__EXT__;
		if (!file_exists($img)) exit('404');
		if (!$imgInfo=getimagesize($img)) exit('404');
		if (!$fp=fopen($img,'rb')) exit('404');
		header("Content-type: {$imgInfo['mime']}");
		fpassthru($fp);
	}
	public function thumb($img){
		$public = dirname(THINK_PATH).'/Public/';
		$image = $public.'image/'.$img.'.'.__EXT__;
		$thumb = $public.'thumb/'.$img.'.'.__EXT__;
		if (!file_exists($thumb)) {
			if (!file_exists($image)) exit('404');
			$imgInfo = \Common\Common\ImageTool::imageInfo($image);
			if (!$imgInfo) exit('404');
			$thumb_dir = dirname($thumb);
			if (!is_dir($thumb_dir)) mkdir($thumb_dir);
			if ($imgInfo['width']>250||$imgInfo['height']>250) {
				if ($imgInfo['width']>$imgInfo['height']) {
					$width = 250;
					$height = 250/$imgInfo['width']*$imgInfo['height'];
				} else {
					$height = 250;
					$width = 250/$imgInfo['height']*$imgInfo['width'];
				}
				if (!\Common\Common\ImageTool::thumb($image,$thumb,$width,$height)) {
					exit('404');
				}
			} else {
				copy($image,$thumb);
			}
		}
		if (!isset($imgInfo)) $imgInfo=getimagesize($thumb);
		if (!$fp=fopen($thumb,'rb')) exit('404');
		header("Content-type: {$imgInfo['mime']}");
		fpassthru($fp);
	}
}