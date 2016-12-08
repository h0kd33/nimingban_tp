<?php
namespace Common\Model;
class FeedModel{
	public function feed($uuid,$page,$num=10){
		$uuid = strtoupper(md5($uuid));
		$modal = M('Feed');
		$where['id'] = array('LIKE',$uuid.'-%');
		$start = ($page-1)*$num;
		$feed = $modal->where($where)->limit($start,$num)->getField('id',true);
		if (!$feed) return array();
		foreach ($feed as $k => $v) {
			$exp = explode('-',$v,2);
			if (isset($exp[1])) {
				$feed[$k] = $exp[1];
			} else {
				unset($feed[$k]);
			}
		}
		$feed = array_values($feed);
		$fields = 'id,fid,title,name,email,FROM_UNIXTIME(now)now,replytime,userid,admin,content,img,ext,sage';
		$where['id'] = array('in',$feed);
		$threads = $modal->table('__THREAD__')->where($where)->order('replytime desc')->field($fields)->select();
		foreach ($threads as $k => $v) {
			$threads[$k]['status'] = 'n';
			$threads[$k]['category'] = '';
		}
		return $threads;
	}
	public function addFeed($uuid,$tid){
		$model = M('Thread');
		$map = array('resto'=>0,'id'=>$tid);
		$exists = (int)$model->where($map)->getField('count(*)');
		if (!$exists) return false;
		$data = strtoupper(md5($uuid)).'-'.$tid;
		$sql = 'insert into __FEED__ (id) values (\''.$data.'\')';
		return !!$model->execute($sql);
	}
	public function delFeed($uuid,$tid){
		$model = M('Feed');
		$data = strtoupper(md5($uuid)).'-'.$tid;
		return !!$model->delete($data);
	}
}