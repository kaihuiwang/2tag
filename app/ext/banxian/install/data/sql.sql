/*
SQLyog Ultimate v11.25 (64 bit)
MySQL - 5.6.19 : Database - 2tag
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`2tag` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `2tag`;

/*Table structure for table `2tag_access` */

DROP TABLE IF EXISTS `2tag_access`;

CREATE TABLE `2tag_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `node` varchar(50) DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_access` */

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
  `ctime` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `nickname` (`nickname`),
  KEY `email` (`email`),
  KEY `pinyin` (`pinyin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

/*Data for the table `2tag_admin_user` */

insert  into `2tag_admin_user`(`id`,`email`,`pwd`,`real_name`,`pinyin`,`nickname`,`sign`,`face`,`coin`,`sex`,`email_validate`,`role`,`dept`,`mobile`,`tel`,`qq`,`skype`,`gtalk`,`join_date`,`is_admin`,`last_login_time`,`mtime`,`ctime`) values (1,'admin@admin.com','d5ac9f8f0e69efe73cd3d9a8ea70a228','管理员','guanliyuan','管理员','','0',0,0,1,0,0,'','','','','','2015-03-10',1,'2015-03-11 21:19:43','2015-03-11 21:19:43','2015-03-10 22:58:24');

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
  `last_reply_time` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后回复时间',
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*Data for the table `2tag_arc` */

/*Table structure for table `2tag_data` */

DROP TABLE IF EXISTS `2tag_data`;

CREATE TABLE `2tag_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zl_key` varchar(20) DEFAULT NULL,
  `zl_value` varchar(20) DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_data` */

/*Table structure for table `2tag_dept` */

DROP TABLE IF EXISTS `2tag_dept`;

CREATE TABLE `2tag_dept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `pid` int(11) DEFAULT '0',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_dept` */

/*Table structure for table `2tag_email_token` */

DROP TABLE IF EXISTS `2tag_email_token`;

CREATE TABLE `2tag_email_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(30) DEFAULT NULL,
  `token` varchar(60) DEFAULT NULL,
  `zl_status` tinyint(1) DEFAULT '1',
  `zl_type` tinyint(1) DEFAULT '1' COMMENT '1-邮箱验证，2-找回密码',
  `ctime` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_email_token` */

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

/*Data for the table `2tag_linked_user` */

/*Table structure for table `2tag_menu` */

DROP TABLE IF EXISTS `2tag_menu`;

CREATE TABLE `2tag_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zl_url` varchar(300) NOT NULL,
  `title` varchar(200) NOT NULL,
  `zl_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-底部导航  2-顶部导航',
  `zl_sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

/*Data for the table `2tag_menu` */

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
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `receive_uid` (`receive_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_msg_data` */

/*Table structure for table `2tag_msg_notice` */

DROP TABLE IF EXISTS `2tag_msg_notice`;

CREATE TABLE `2tag_msg_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_content` text NOT NULL,
  `url` varchar(400) NOT NULL DEFAULT '0',
  `zl_type` tinyint(1) DEFAULT '1' COMMENT '1-notice 2-msg',
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_msg_notice` */

/*Table structure for table `2tag_msg_notice_read_log` */

DROP TABLE IF EXISTS `2tag_msg_notice_read_log`;

CREATE TABLE `2tag_msg_notice_read_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_id` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_msg_notice_read_log` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_notice` */

/*Table structure for table `2tag_reply` */

DROP TABLE IF EXISTS `2tag_reply`;

CREATE TABLE `2tag_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `arc_id` int(11) NOT NULL DEFAULT '0',
  `is_publish` int(11) NOT NULL DEFAULT '1',
  `content` text,
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_reply` */

/*Table structure for table `2tag_role` */

DROP TABLE IF EXISTS `2tag_role`;

CREATE TABLE `2tag_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_role` */

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
  `ctime` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `zl_score` (`zl_score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_tag` */

/*Table structure for table `2tag_tag_ext` */

DROP TABLE IF EXISTS `2tag_tag_ext`;

CREATE TABLE `2tag_tag_ext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `arc_id` int(11) NOT NULL DEFAULT '0',
  `is_publish` tinyint(1) DEFAULT '1',
  `ctime` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `arc_id` (`arc_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_tag_ext` */

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
  `ctime` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `arc_number` int(11) DEFAULT '0' COMMENT '文章数量',
  `reply_number` int(11) DEFAULT '0' COMMENT '回复数量',
  PRIMARY KEY (`id`),
  KEY `nickname` (`nickname`),
  KEY `email` (`email`),
  KEY `pinyin` (`pinyin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息表';

DROP TABLE IF EXISTS `2tag_arc_digg`;
CREATE TABLE `2tag_arc_digg` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `zl_type` TINYINT(1) DEFAULT '1',
  `uid` INT(11) DEFAULT NULL,
  `arc_id` INT(11) DEFAULT NULL,
  `ctime` TIMESTAMP NULL DEFAULT NULL,
  `mtime` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uid_arc_id` (`uid`,`arc_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

/*Data for the table `2tag_user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
