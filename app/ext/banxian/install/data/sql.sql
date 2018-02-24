/*Table structure for table `zl_access` */

DROP TABLE IF EXISTS `2tag_access`;

CREATE TABLE `2tag_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `node` varchar(50) DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_admin_user` */

DROP TABLE IF EXISTS `2tag_admin_user`;

CREATE TABLE `2tag_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '0',
  `pwd` varchar(50) NOT NULL DEFAULT '0',
  `real_name` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `pinyin` varchar(50) DEFAULT NULL COMMENT '拼音',
  `nickname` varchar(50) NOT NULL DEFAULT '0',
  `sign` varchar(100) DEFAULT NULL COMMENT '签名',
  `face` varchar(100) NOT NULL DEFAULT '0',
  `coin` int(11) NOT NULL DEFAULT '0',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:保密,1:男，2：女',
  `email_validate` tinyint(4) NOT NULL DEFAULT '0',
  `role` int(1) NOT NULL DEFAULT '0' COMMENT '职位,999-超级管理员',
  `dept` int(11) DEFAULT NULL COMMENT '部门',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号码',
  `tel` varchar(20) DEFAULT NULL COMMENT '电话',
  `qq` varchar(20) DEFAULT NULL COMMENT 'qq',
  `skype` varchar(20) DEFAULT NULL COMMENT 'skype',
  `gtalk` varchar(20) DEFAULT NULL COMMENT 'gtalk',
  `join_date` date DEFAULT NULL COMMENT '加入日期',
  `is_admin` tinyint(1) DEFAULT '0' COMMENT '1-是管理',
  `last_login_time` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ctime` timestamp NULL ,
  PRIMARY KEY (`id`),
  KEY `nickname` (`nickname`),
  KEY `email` (`email`),
  KEY `pinyin` (`pinyin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

/*Table structure for table `2tag_arc` */

DROP TABLE IF EXISTS `2tag_arc`;

CREATE TABLE `2tag_arc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `content` text,
  `zl_type` tinyint(1) DEFAULT '1' COMMENT '1:普通，2：精华',
  `uid` int(11) NOT NULL DEFAULT '0',
  `reply_count` int(11) NOT NULL DEFAULT '0',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '访问量',
  `good_number` int(11) DEFAULT '0' COMMENT '顶数目',
  `bad_number` int(11) DEFAULT '0' COMMENT '踩数目',
  `is_publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未发布，1已发布',
  `tags` varchar(100) DEFAULT NULL COMMENT '标签列表',
  `zl_score` double DEFAULT NULL COMMENT '当前文章分数',
  `zl_score_base` double DEFAULT '0',
  `score_version` int(11) DEFAULT '0' COMMENT 'score版本',
  `last_reply_uid` int(11) DEFAULT NULL COMMENT '最后回复人',
  `last_reply_time` timestamp NULL DEFAULT NULL COMMENT '最后回复时间',
  `ctime` timestamp NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_arc_digg` */

DROP TABLE IF EXISTS `2tag_arc_digg`;

CREATE TABLE `2tag_arc_digg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zl_type` tinyint(1) DEFAULT '1',
  `uid` int(11) DEFAULT NULL,
  `arc_id` int(11) DEFAULT NULL,
  `ctime` timestamp NULL ,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uid_arc_id` (`uid`,`arc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_data` */

DROP TABLE IF EXISTS `2tag_data`;

CREATE TABLE `2tag_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zl_key` varchar(20) DEFAULT NULL,
  `zl_value` varchar(20) DEFAULT NULL,
  `ctime` timestamp NULL,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_dept` */

DROP TABLE IF EXISTS `2tag_dept`;

CREATE TABLE `2tag_dept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `pid` int(11) DEFAULT '0',
  `ctime` timestamp NULL ,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_email_token` */

DROP TABLE IF EXISTS `2tag_email_token`;

CREATE TABLE `2tag_email_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(30) DEFAULT NULL,
  `token` varchar(60) DEFAULT NULL,
  `zl_status` tinyint(1) DEFAULT '1',
  `zl_type` tinyint(1) DEFAULT '1' COMMENT '1-邮箱验证，2-找回密码',
  `ctime` timestamp NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_ext_backup` */

DROP TABLE IF EXISTS `2tag_ext_backup`;

CREATE TABLE `2tag_ext_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(50) NOT NULL,
  `path` varchar(100) NOT NULL,
  `ctime` timestamp  NULL ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `2tag_ext_favorite` */

DROP TABLE IF EXISTS `2tag_ext_favorite`;

CREATE TABLE `2tag_ext_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arc_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_ext_news_digg` */

DROP TABLE IF EXISTS `2tag_ext_news_digg`;

CREATE TABLE `2tag_ext_news_digg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zl_type` tinyint(1) DEFAULT '1',
  `uid` int(11) DEFAULT NULL,
  `arc_id` int(11) DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uid_arc_id` (`uid`,`arc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_ext_news_list` */

DROP TABLE IF EXISTS `2tag_ext_news_list`;

CREATE TABLE `2tag_ext_news_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) DEFAULT '0' COMMENT '标题',
  `url` varchar(400) DEFAULT '0' COMMENT '网址',
  `uid` int(11) DEFAULT '0' COMMENT '添加人',
  `content` text COMMENT '正文',
  `view_count` int(11) DEFAULT '0' COMMENT '查看人数',
  `good_number` int(11) DEFAULT '0' COMMENT '喜欢数',
  `reply_count` int(11) DEFAULT '0' COMMENT '回复数',
  `is_publish` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `zl_score` double DEFAULT '0',
  `score_version` double DEFAULT '0',
  `last_reply_uid` int(11) DEFAULT NULL COMMENT '最后回复人',
  `last_reply_time` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ctime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_ext_qqlogin` */

DROP TABLE IF EXISTS `2tag_ext_qqlogin`;

CREATE TABLE `2tag_ext_qqlogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `obj_id` varchar(200) DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_ext_zhuanti` */

DROP TABLE IF EXISTS `2tag_ext_zhuanti`;

CREATE TABLE `2tag_ext_zhuanti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `uid` int(11) NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ctime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_ext_zhuanti_ext` */

DROP TABLE IF EXISTS `2tag_ext_zhuanti_ext`;

CREATE TABLE `2tag_ext_zhuanti_ext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zhuanti_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_linked_user` */

DROP TABLE IF EXISTS `2tag_linked_user`;

CREATE TABLE `2tag_linked_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ftype` varchar(10) DEFAULT 'product',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_menu` */

DROP TABLE IF EXISTS `2tag_menu`;

CREATE TABLE `2tag_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zl_url` varchar(300) NOT NULL,
  `title` varchar(200) NOT NULL,
  `zl_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-底部导航  2-顶部导航',
  `zl_sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ctime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf16;

/*Table structure for table `2tag_msg_data` */

DROP TABLE IF EXISTS `2tag_msg_data`;

CREATE TABLE `2tag_msg_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `root_id` int(11) NOT NULL COMMENT '根消息id',
  `sub_count` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `receive_uid` int(11) NOT NULL DEFAULT '0',
  `content` text,
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  `send_status` tinyint(1) NOT NULL DEFAULT '1',
  `receve_status` tinyint(1) NOT NULL DEFAULT '1',
  `msg_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为系统信息，0为普通信息',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `receive_uid` (`receive_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_msg_notice` */

DROP TABLE IF EXISTS `2tag_msg_notice`;

CREATE TABLE `2tag_msg_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_content` text NOT NULL,
  `url` varchar(400) NOT NULL DEFAULT '0',
  `zl_type` tinyint(1) DEFAULT '1' COMMENT '1-notice 2-msg',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_msg_notice_read_log` */

DROP TABLE IF EXISTS `2tag_msg_notice_read_log`;

CREATE TABLE `2tag_msg_notice_read_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_id` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_notice` */

DROP TABLE IF EXISTS `2tag_notice`;

CREATE TABLE `2tag_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `content` text,
  `show_time` timestamp NULL DEFAULT NULL,
  `zl_type` tinyint(1) DEFAULT '1' COMMENT '1-公告，2-说明',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_reply` */

DROP TABLE IF EXISTS `2tag_reply`;

CREATE TABLE `2tag_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `arc_id` int(11) NOT NULL DEFAULT '0',
  `is_publish` int(11) NOT NULL DEFAULT '1',
  `zl_type` tinyint(1) DEFAULT '0' COMMENT '0-文章,1-news',
  `content` text,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_role` */

DROP TABLE IF EXISTS `2tag_role`;

CREATE TABLE `2tag_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_tag` */

DROP TABLE IF EXISTS `2tag_tag`;

CREATE TABLE `2tag_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `pinyin` varchar(200) DEFAULT NULL,
  `zl_count` int(11) DEFAULT '0',
  `follow_count` int(11) NOT NULL DEFAULT '0',
  `is_publish` tinyint(1) DEFAULT '1',
  `score_version` int(11) DEFAULT '0' COMMENT 'score版本',
  `zl_score` float DEFAULT NULL COMMENT '当前标签分数',
  `uid` int(11) DEFAULT NULL COMMENT '第一次创建人',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ctime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zl_score` (`zl_score`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_tag_ext` */

DROP TABLE IF EXISTS `2tag_tag_ext`;

CREATE TABLE `2tag_tag_ext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `arc_id` int(11) NOT NULL DEFAULT '0',
  `is_publish` tinyint(1) DEFAULT '1',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `arc_id` (`arc_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=808 DEFAULT CHARSET=utf8;

/*Table structure for table `2tag_user` */

DROP TABLE IF EXISTS `2tag_user`;

CREATE TABLE `2tag_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '0',
  `pwd` varchar(50) NOT NULL DEFAULT '0',
  `pinyin` varchar(50) DEFAULT NULL COMMENT '拼音',
  `nickname` varchar(50) NOT NULL DEFAULT '0',
  `face` varchar(100) NOT NULL DEFAULT '0',
  `coin` int(11) NOT NULL DEFAULT '0',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:保密,1:男，2：女',
  `email_validate` tinyint(4) NOT NULL DEFAULT '0',
  `join_date` date DEFAULT NULL COMMENT '加入日期',
  `last_login_time` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
  `level` int(11) DEFAULT '0' COMMENT '会员等级',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ctime` timestamp NULL DEFAULT NULL,
  `arc_number` int(11) DEFAULT '0' COMMENT '文章数量',
  `reply_number` int(11) DEFAULT '0' COMMENT '回复数量',
  PRIMARY KEY (`id`),
  KEY `nickname` (`nickname`),
  KEY `email` (`email`),
  KEY `pinyin` (`pinyin`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

