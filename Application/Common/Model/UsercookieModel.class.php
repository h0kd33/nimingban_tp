<?php
namespace Common\Model;
class UsercookieModel{
	protected $key = 'I#7OJ@%iTLR1yG7uZR@C';

	public function getRandStr($length = 7, $special_chars = false) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		if ($special_chars) {
			$chars .= '!@#$%^&*()';
		}
		$randStr = '';
		for ($i = 0; $i < $length; $i++) {
			$randStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $randStr;
	}

	public function mkHash($id){
		return strtoupper(md5($id.$this->key));
	}

	public function mkCookie($expire=0){
		if ($expire===0) $expire = time()+3600*365;
		$randStr = $this->getRandStr();
		$cookie = $randStr.'----'.$this->mkHash($randStr);
		setcookie('userhash',$cookie,$expire,'/');
		return $randStr;
	}

	public function verify($cookie=''){
		if ($cookie==='') {
			if (empty($_COOKIE['userhash'])) return false;
			$cookie = $_COOKIE['userhash'];
		}
		$exp = explode('----', $cookie, 2);
		if (count($exp)!==2) return false;
		list($id,$hash) = $exp;
		if (strtoupper($hash)===$this->mkHash($id)) {
			return $id;
		}
		return false;
	}

	public function lock($islock=true){
		if ($islock) {
			file_put_contents(CONF_PATH.'cookie.lock.config', '');
		} else {
			if ($this->isLock()) unlink(CONF_PATH.'cookie.lock.config');
		}
	}

	public function isLock(){
		return is_file(CONF_PATH.'cookie.lock.config');
	}
}