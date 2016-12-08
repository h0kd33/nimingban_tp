/*板块分组信息表*/
CREATE TABLE nmb_fgroup (
  id int unsigned NOT NULL AUTO_INCREMENT,
  sort int NOT NULL default -1,
  name varchar(10) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*板块信息表*/
CREATE TABLE nmb_forum (
  id int unsigned NOT NULL AUTO_INCREMENT,
  fgroup int unsigned NOT NULL DEFAULT 0,
  sort int NOT NULL DEFAULT -1,
  name varchar(10) NOT NULL,
  showname varchar(30) NOT NULL DEFAULT '',
  msg text NOT NULL,
  cd tinyint unsigned NOT NULL DEFAULT 15,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*串、回复信息表*/
CREATE TABLE nmb_thread (
  id int unsigned NOT NULL AUTO_INCREMENT,
  fid int unsigned NOT NULL DEFAULT 0,
  img varchar(100) NOT NULL DEFAULT '',
  ext varchar(10) NOT NULL DEFAULT '',
  now int unsigned NOT NULL default 0,
  userid varchar(15) NOT NULL DEFAULT '',
  name varchar(15) NOT NULL DEFAULT '无名氏',
  email varchar(30) NOT NULL DEFAULT '',
  title varchar(40) NOT NULL DEFAULT '无标题',
  content text NOT NULL,
  sage tinyint NOT NULL DEFAULT 0,
  admin int unsigned NOT NULL DEFAULT 0,
  resto int unsigned NOT NULL DEFAULT 0,
  replytime int unsigned NOT NULL DEFAULT 0,
  replyip varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*订阅信息表*/
CREATE TABLE nmb_feed (
  id varchar(50) NOT NULL,/*格式：订阅ID-订阅串ID，不可重复*/
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*管理员表*/
CREATE TABLE nmb_admin (
  id int unsigned NOT NULL AUTO_INCREMENT,
  level tinyint NOT NULL DEFAULT 0,
  name varchar(15) NOT NULL DEFAULT '',
  showname varchar(15) NOT NULL DEFAULT '',
  password varchar(15) NOT NULL DEFAULT '',
  logintime varchar(200) NOT NULL DEFAULT '',/*保存最多10次历史登陆时间，以“|”分割*/
  loginip varchar(200) NOT NULL DEFAULT '',/*保存最多10次历史登陆ip，以“|”分割*/
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*禁止发言ID表*/
CREATE TABLE nmb_banid (
  id varchar(15) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*禁止发言IP表*/
CREATE TABLE nmb_banip (
  id varchar(15) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;