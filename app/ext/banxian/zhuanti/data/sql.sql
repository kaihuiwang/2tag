DROP TABLE IF EXISTS `2tag_ext_zhuanti`;

CREATE TABLE `2tag_ext_zhuanti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `uid` int(11) NOT NULL,
  `mtime` timestamp NOT NULL,
  `ctime` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `2tag_ext_zhuanti_ext`;

CREATE TABLE `2tag_ext_zhuanti_ext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zhuanti_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ctime` timestamp NOT NULL,
  `mtime` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8