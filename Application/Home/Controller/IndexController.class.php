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
		header('Content-type: '.$imgInfo['mime']);
		fpassthru($fp);
	}
	public function thumb($img){
		$public = dirname(THINK_PATH).'/Public/';
		$image = $public.'image/'.$img.'.'.__EXT__;
		$thumb = $public.'thumb/'.$img.'.'.__EXT__;
		if (!file_exists($thumb)) {
			if (!file_exists($image)) exit('404');
			$image_ = new \Think\Image();
			$image_->open($image);
			$imgInfo[0] = $image_->width();
			$imgInfo[1] = $image_->height();
			$imgInfo['mime'] = $image_->mime();
			$thumb_dir = dirname($thumb);
			if (!is_dir($thumb_dir)) mkdir($thumb_dir);
			if ($imgInfo[0]>250||$imgInfo[1]>250) {
				$image_->thumb(250, 250)->save($thumb);
			} else {
				copy($image,$thumb);
			}
		}
		if (!isset($imgInfo)) $imgInfo=getimagesize($thumb);
		if (!$fp=fopen($thumb,'rb')) exit('404');
		header('Content-type: '.$imgInfo['mime']);
		fpassthru($fp);
	}
}