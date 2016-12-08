<?php
namespace Home\Controller;
use Think\Controller;
class ForumController extends Controller {
	public function index(){
		echo '主页';
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
	public function showf($forum,$page=1){
		$fm = D('Forum');
		$forum = $fm->getForum($forum);
		if (!$forum) $this->error('该板块不存在！');
		$groups = $fm->allMin();
		$threads = D('Thread')->showf($forum['id'],$page);
		echo '<pre>';
		print_r($forum);
		print_r($groups);
		print_r($threads);
		echo '</pre>';
	}
	public function thread($id,$page=1){
		$threads = D('Thread')->thread($id,$page);
		print_r($threads);
	}
	public function feed($page=1){
		$um = D('Usercookie');
		if (!$um->verify()) $this->error('UUID无法获取！');
		list($id,$uuid) = explode('----', $_COOKIE['userhash']);
		$feed = D('Feed')->feed($uuid,$page);
		print_r($feed);
	}
}