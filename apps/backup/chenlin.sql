/*后台管理员表*/
 CREATE TABLE `bingli_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `encryption` varchar(8) NOT NULL,
  `loginip` varchar(31) DEFAULT NULL,
  `logintime` varchar(21) DEFAULT NULL,
  `sort` smallint(5) NOT NULL DEFAULT '10',
  `email` varchar(25) DEFAULT NULL,
  `phone` char(11) DEFAULT NULL,
  status tinyint not null default 1,
  addtime int not null default 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
insert into bingli_user values(1,'admin','1d43bac16aa518af710affb8f6c7cd7c','15837','','',10,'admin@qq.com','13800138000',1,1400000000);


/*会员员表*/
CREATE TABLE `bingli_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `password` char(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `name` varchar(120) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `encrypt` varchar(8) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL COMMENT '加密文件',
  `age` int(3) DEFAULT NULL COMMENT '年龄',
  `sex` int(1) DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(70) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `created_at` datetime DEFAULT NULL,
  `active` char(1) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `permis` int(6) DEFAULT NULL COMMENT '分组',
  `birth_date` int(11) DEFAULT NULL,
  `create_time` varchar(12) DEFAULT NULL,
  `update_time` varchar(12) DEFAULT NULL,
  `login_times` int(8) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_cb0fsvip6qow952a07et2k9xv` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*角色表*/
CREATE TABLE `bingli_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*节点表*/
CREATE TABLE `bingli_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(20) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '1',
  `is_main` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示在主菜单',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

/**
 * 角色与管理员关系表
 */
create table bingli_user_role(
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  user_id int not null default 0,
	  role_id int not null default 0,
 	 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

/**
 * 角色与节点关系表
 */
create table bingli_role_node(
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  node_id int not null default 0,
	  role_id int not null default 0,
 	 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

create table bingli_channel(
	id int primary key auto_increment,
	attr tinyint not null default 0,
	title varchar(15) ,
	target varchar(15) ,
	index_rule varchar(50),
	list_rule varchar(50),
	content_rule varchar(50),
	seo_title varchar(255),
	seo_keyword varchar(255),
	seo_description varchar(255),
	is_show tinyint not null default 1,
	sort tinyint not null default 10
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


create table bingli_category(
	id int primary key auto_increment,
	channel_id int not null default 0,
	parent_id int not null default 0,
	image varchar(255),
	title varchar(15) ,
	list_rule varchar(50),
	content_rule varchar(50),
	seo_title varchar(255),
	seo_keyword varchar(255),
	seo_description varchar(255),
	sort tinyint not null default 10
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bingli_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('deleted','draft','published','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `tag` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visibility` enum('public','private','password') COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'en',
  `channel_id` int(10) DEFAULT '0' comment "频道id",
   `category_id` int(10) DEFAULT 0 comment "栏目id",
  `sort_order` int(10) DEFAULT 0 comment "排序",
  `addtime` int(10) NOT NULL comment "创建时间",
  `user_id` int(10) DEFAULT NULL comment "发布者id",
  `updated_time` int(10) DEFAULT NULL comment "最近修改时间",
  `picture` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL comment "文章图片",
  `click` int not null default 0 comment "阅读次数",
   `comment` int(10) DEFAULT 1 comment "是否允许评论",
    `audit` int(10) DEFAULT 1 comment "是否审核通过",
  `source_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `content` longtext COLLATE utf8_unicode_ci NOT NULL,
   `keyword` varchar(255) COLLATE utf8_unicode_ci default null comment "seo关键字",
    `introduction` varchar(255) COLLATE utf8_unicode_ci default null comment "内容简介",
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE `bingli_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE `bingli_config_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(100) DEFAULT NULL COMMENT '组名（英文）',
  `title` varchar(255) DEFAULT NULL COMMENT '组标题（中文）',
  `sort_order` mediumint(6) DEFAULT '100' COMMENT '组顺序',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '显示',
  `system` tinyint(1) DEFAULT '0' COMMENT '系统组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置组';



CREATE TABLE `bingli_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(9) DEFAULT NULL COMMENT '配置组ID',
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '配置名称',
  `value` text NOT NULL COMMENT '配置值',
  `title` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=Innodb DEFAULT CHARSET=utf8 COMMENT='系统配置';