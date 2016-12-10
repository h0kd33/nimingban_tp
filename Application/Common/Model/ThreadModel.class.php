<?php
namespace Common\Model;
use Think\Model;
class ThreadModel extends Model{
	protected $_validate = array(
		array('userid','userVerify','没有饼干',1,'callback',1),
		array('userid','isBaned','你被禁止发言',1,'callback',1),
		array('fid','number','板块错误',2,'regex',1),
		array('fid','forumExists','板块错误',2,'callback',1),
		array('resto','number','回复串错误',2,'regex',1),
		array('resto','threadExists','回复串错误',2,'callback',1),
		array('act','isActEmpty','参数缺失',1,'callback',1),
		array('img','imgSizeOverflow','图片大小不得超过2M',1,'callback',1),
		array('water','imgHandle','不支持的图片格式',1,'callback',1),
		array('content','isEmpty','没有上传文件的时候，必须填写内容',1,'callback',1),
		array('content','0,10000','正文内容过长',2,'length',1),
		array('email','email','E-mail格式错误',2,'regex',1),
	);
	protected $_auto = array(
		array('userid','getUserid',1,'callback'),
		array('name','htmlspecialchars',1,'function'),
		array('title','htmlspecialchars',1,'function'),
		array('name','',1,'ignore'),
		array('email','',1,'ignore'),
		array('title','',1,'ignore'),
		array('content','filterContent',1,'callback'),
		array('img','getUploadImg',1,'callback'),
		array('ext','getUploadExt',1,'callback'),
		array('now','time',1,'function'),
		array('replytime','now',1,'field'),
		array('replyip','getUserip',1,'callback'),
		array('admin','fillAdmin',1,'callback'),
		array('resto','updateReptime',1,'callback'),
	);
	protected $_uploadImg = array('img'=>'','ext'=>'');
	protected $_userid = '';
	protected function updateReptime($resto){
		if (!$resto) return 0;
		$data['replytime'] = time();
		$this->where(array('id'=>$resto))->save($data);
		return $resto;
	}
	protected function fillAdmin(){
		if (empty($_POST['isManager'])) return 0;
		$isAuth = false;//TODO
		$uid = 0;//TODO
		if (!$isAuth) return 0;
		return $uid;
	}
	protected function isActEmpty(){
		if (empty($_POST['fid'])&&empty($_POST['resto'])) return false;
		return true;
	}
	protected function getUserip(){
		return $_SERVER['REMOTE_ADDR'];
	}
	protected function getUserid(){
		return $this->_userid;
	}
	protected function userVerify(){
		$um = D('Usercookie');
		$userid = $um->verify();
		if (!$userid) {
			if ($um->isLock()) return false;
			$userid = $um->mkCookie();
		}
		$this->_userid = $userid;
		return true;
	}
	protected function isBaned(){
		$model = M('Banid');
		$map = array('id' => $this->_userid);
		if ($model->where($map)->getField('count(*)')) return false;
		$map['id'] = $_SERVER['REMOTE_ADDR'];
		if ($model->table('__BANIP__')->where($map)->getField('count(*)')) return false;
		return true;
	}
	protected function imgSizeOverflow(){
		if ( (!empty($_FILES['image']['error']))&&($_FILES['image']['error']==1) ){
			return false;
		}
		if (empty($_FILES['image']['size'])||$_FILES['image']['size']<1024*1024*2) {
			return true;
		}
		return false;
	}
	protected function getUploadImg(){
		return $this->_uploadImg['img'];
	}
	protected function getUploadExt(){
		return $this->_uploadImg['ext'];
	}
	protected function imgHandle($water){
		if (empty($_FILES['image']['size'])) return true;
		$tmp = $_FILES['image']['tmp_name'];
		$image = new \Think\Image();
		$image->open($tmp);
		$ext = $image->mime();
		$ext = substr($ext,strpos($ext,'/')+1);
		$path = dirname(THINK_PATH).'/Public/image/';
		$waterImg = $path.'water.png';
		if ($water) {
			$image->water($waterImg,9,100)->save($tmp);
		}
		$time = time();
		$dir = date('Y-m-d', $time);
		is_dir($path.$dir)||mkdir($path.$dir);
		$filename = $dir.'/'.md5($time.mt_rand());
		$this->_uploadImg['img'] = $filename;
		$this->_uploadImg['ext'] = '.'.$ext;
		move_uploaded_file($tmp,$path.$filename.'.'.$ext);
		return true;
	}
	protected function isEmpty($content){
		$content = trim($content);
		if (empty($content)&&empty($_FILES['image']['size'])) {
			return false;
		}
		return true;
	}
	protected function filterContent($content){
		$content = trim($content);
		if (!$content) return '分享图片';
		$content = htmlspecialchars($content);
		$content = nl2br($content);
		return $content;
	}
	protected function forumExists($fid){
		$fid = (int)$fid;
		$forum = M('Forum');
		return !!$forum->where('id='.$fid)->getField('count(*)');
	}
	protected function threadExists($tid){
		$tid = (int)$tid;
		$map = array('id'=>$tid,'resto'=>0);
		$fields = $this->where($map)->getField('count(*)');
		if ($fields) return true;
		return false;
	}
	public function showf($id=1,$page=1,$num=20){
		$map = array('resto'=>0,'fid'=>$id);
		$start = ($page-1)*$num;
		$fields = 'id,title,name,email,FROM_UNIXTIME(now)now,userid,admin,content,img,ext,sage';
		$threads = $this->where($map)->order('replytime desc')->limit($start,$num)->getField($fields);
		foreach ($threads as $k => $v) {
			$threads[$k]['replyCount'] = $this->where(array('resto'=>$v['id']))->getField('count(*)');
			$threads[$k]['remainReplys'] = $threads[$k]['replyCount'] - 5;
			$threads[$k]['remainReplys'] = $threads[$k]['remainReplys']<0?0:$threads[$k]['remainReplys'];
			$replys = $this->where(array('resto'=>$v['id']))->order('now desc')->limit(0,5)->getField($fields);
			$threads[$k]['replys'] = $replys?array_values($replys):array();
		}
		return array_values($threads);
	}
	public function thread($id,$page=0,$num=19){
		$fields = 'id,title,name,email,FROM_UNIXTIME(now)now,userid,admin,content,img,ext,sage';
		$map = array('resto'=>0,'id'=>$id);
		$thread = $this->where($map)->field($fields)->find();
		if (!$thread) return '该主题不存在';
		$thread['replyCount'] = $this->where(array('resto'=>$id))->getField('count(*)');
		if ($thread['replyCount']==0) {
			$thread['replys'] = array();
			return $thread;
		}
		$page = $page===0?ceil($thread['replyCount']/19):$page;
		$start = ($page-1)*$num;
		$replys = $this->where(array('resto'=>$id))->order('now desc')->limit($start,$num)->getField($fields);
		$thread['replys'] = $replys?array_values($replys):array();
		return $thread;
	}
}