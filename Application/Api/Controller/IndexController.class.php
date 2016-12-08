<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller{
	public function getCookie(){
		$um = D('Usercookie');
		if ($um->isLock()||$um->verify()) {
			$this->ajaxReturn('error');
		} else {
			$um->mkCookie();
			$this->ajaxReturn('ok');
		}
	}
	public function getForumList(){
		$fm = D('Forum');
		$this->ajaxReturn($fm->all());
	}
	public function showf($id=1,$page=1){
		$thread = D('Thread');
		$this->ajaxReturn($thread->showf($id,$page));
	}
	public function doPostThread(){
		$thread = D('Thread');
		if ($thread->create()) {
			if (!$thread->add()) $this->error('发言失败');
			$this->success('发言成功！');
		} else {
			$this->error($thread->getError());
		}
	}
	public function doReplyThread(){
		$this->doPostThread();
	}
	public function thread($id,$page=0){
		$thread = D('Thread');
		$this->ajaxReturn($thread->thread($id,$page));
	}
	public function feed($uuid,$page=1){
		$feed = D('Feed');
		$this->ajaxReturn($feed->feed($uuid,$page));
	}
	public function addFeed($uuid,$tid){
		$feed = D('Feed');
		if ($feed->addFeed($uuid,$tid)){
			$this->ajaxReturn('订阅大成功→_→');
		} else {
			$this->ajaxReturn('该主题不存在');
		}
	}
	public function delFeed($uuid,$tid){
		$feed = D('Feed');
		if ($feed->delFeed($uuid,$tid)) {
			$this->ajaxReturn('取消订阅成功!');
		} else {
			$this->ajaxReturn('取消订阅失败!');
		}
	}
}