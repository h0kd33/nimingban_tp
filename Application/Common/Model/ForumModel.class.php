<?php
namespace Common\Model;
class ForumModel{
	public function addGroup($name,$sort=''){
		$fgroup = M('Fgroup');
		$data = array();
		$data['name'] = $name;
		if ($sort) $data['sort'] = (int)$sort;
		return $fgroup->add($data);
	}
	public function addForum($name,$fgroup,$msg,$sort='',$cd='',$showname=''){
		$forum = M('Forum');
		$data = array();
		$data['name'] = $name;
		$data['fgroup'] = $fgroup;
		$data['msg'] = $msg;
		if ($sort) $data['sort'] = (int)$sort;
		if ($cd) $data['cd'] = $cd;
		if ($showname) $data['showname'] = $showname;
		return $forum->add($data);
	}
	public function all(){
		$fm = M('Fgroup');
		$groups = $fm->order('sort')->select();
		foreach ($groups as $k => $v) {
			$groups[$k]['status'] = 'n';
			$forums = $fm->table('__FORUM__')->where('fgroup='.$v['id'])->order('sort')->select();
			foreach ($forums as $key => $value) {
				$forums[$key]['status'] = 'n';
				$forums[$key]['interval'] = $forums[$key]['cd'];
				unset($forums[$key]['cd']);
			}
			$groups[$k]['forums'] = $forums;
		}
		return $groups;
	}
	public function getForum($key){
		$model = M('Forum');
		$where = is_numeric($key)?array('id'=>$key):array('name'=>$key);
		$forum = $model->where($where)->find();
		return $forum;
	}
	public function allMin(){
		$model = M('Fgroup');
		$groups = $model->order('sort')->getField('id,sort,name');
		$fields = 'id,fgroup,sort,name,showname';
		$forums = $model->table('__FORUM__')->field($fields)->select();
		foreach ($forums as $k => $v) {
			if (isset($groups[$v['fgroup']])) {
				$groups[$v['fgroup']]['forums'][] = $v;
			}
		}
		return $groups;
	}
}