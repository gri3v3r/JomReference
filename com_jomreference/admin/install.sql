DROP TABLE IF EXISTS `#__jomreference_recommendations`;
CREATE TABLE `#__jomreference_recommendations` (
       `recid` INT NOT NULL AUTO_INCREMENT,
	   `sourceid` INT NOT NULL,
	   `targetid` INT NOT NULL,
       `show` INT NOT NULL DEFAULT 0,	
	   `rating` INT NOT NULL DEFAULT 0,
	   `rectext` TEXT NOT NULL DEFAULT '',
	   `replytext` TEXT NOT NULL DEFAULT '',
	   `yrfrom` INT NOT NULL DEFAULT 0,
	   `yrto` INT NOT NULL DEFAULT 0,
	   `interaction` TEXT NOT NULL DEFAULT '',
	   `recdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	   PRIMARY KEY(`recid`)
	 )ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
	   
DROP TABLE IF EXISTS `#__jomreference_requests`;
CREATE TABLE `#__jomreference_requests` (
       `reqid` INT NOT NULL AUTO_INCREMENT,
	   `sourceid` INT NOT NULL DEFAULT 0,
	   `targetid` INT NOT NULL DEFAULT 0,   
	   `reqtext` TEXT NOT NULL DEFAULT '',
	   `reqdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	   PRIMARY KEY(`reqid`)
	 )ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
	 
DROP TABLE IF EXISTS `#__jomreference_permissions`;
CREATE TABLE `#__jomreference_permissions` (
       `permid` INT NOT NULL AUTO_INCREMENT,
	   `sourceid` INT NOT NULL DEFAULT 0,
	   `targetid` INT NOT NULL DEFAULT 0,
	   `accept` INT NOT NULL DEFAULT 0,	 
       `permdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	   
	   PRIMARY KEY(`permid`)
	 )ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; 
	 
DROP TABLE IF EXISTS `#__jomreference_businessinfo`;
CREATE TABLE `#__jomreference_businessinfo` (
       `infoid` INT NOT NULL AUTO_INCREMENT,
       `userid` INT NOT NULL DEFAULT 0,
	   `yrfrom` INT NOT NULL DEFAULT 0,
	   `yrto` INT NOT NULL DEFAULT 0,
	   `profession` VARCHAR(500) NOT NULL DEFAULT '',
	   `workplace` VARCHAR(500) NOT NULL DEFAULT '',
	   PRIMARY KEY(`infoid`)
	 )ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
	 
DROP TABLE IF EXISTS `#__jomreference_settings`;
CREATE TABLE `#__jomreference_settings` (
      `comp_id` INT NOT NULL AUTO_INCREMENT ,
      `rec_perm` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	  `busi_mod` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	  `rec_del` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	  `rate` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	  `user_man` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	  `rec_req` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	  PRIMARY KEY(`comp_id`)
	 )ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
	 
INSERT INTO `#__jomreference_settings` () VALUES();
