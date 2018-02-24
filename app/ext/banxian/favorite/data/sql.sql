DROP TABLE IF EXISTS `2tag_ext_favorite`;
CREATE TABLE `2tag_ext_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arc_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ctime` timestamp NOT NULL,
  `mtime` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;