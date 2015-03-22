DROP TABLE IF EXISTS `2tag_ext_qqlogin`;
CREATE TABLE `2tag_ext_qqlogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `obj_id` varchar(200) DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
