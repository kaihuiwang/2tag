DROP TABLE IF EXISTS `2tag_ext_backup`;
CREATE TABLE `2tag_ext_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `path` varchar(100) NOT NULL,
  `ctime` timestamp NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;