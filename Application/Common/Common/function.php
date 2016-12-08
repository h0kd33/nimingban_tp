<?php
function isPC(){
	if (!isset($_SERVER['HTTP_USER_AGENT'])) return true;
	$clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
	if (preg_match('/('.implode('|', $clientkeywords).')/i',strtolower($_SERVER['HTTP_USER_AGENT']))){
		return false;
	}
	return true;
}