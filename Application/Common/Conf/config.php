<?php
return array(
	//'配置项'=>'配置值'
	//数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'nimingban', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'nmb_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	//模块设置
	'MODULE_ALLOW_LIST'=>array('Home','Api'),
	'DEFAULT_MODULE'=>'Home',
	//调试工具
	'SHOW_PAGE_TRACE' =>true,
	//路由
	'URL_ROUTER_ON'   => true,
	'URL_ROUTE_RULES' => array(
		't/:id'=>'Forum/thread',
		'f/:forum'=>'Forum/showf',
		'/^image\/(\S+)$/i'=>'Index/image?img=:1',
		'/^thumb\/(\S+)$/i'=>'Index/thumb?img=:1',
	),
);