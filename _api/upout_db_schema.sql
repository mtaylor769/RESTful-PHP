DROP TABLE IF EXISTS `upout_user`;
CREATE TABLE IF NOT EXISTS `upout_user` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` varchar(20) NOT NULL,
  `username` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `first_name` varchar(150) DEFAULT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `email_address` varchar(150) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `upout_user` (`id`, `timestamp`, `username`, `first_name`, `last_name`, `password`, `email_address`) VALUES 
(1, '1380932134', 'mtaylor', 'Mike', 'Taylor', MD5('pa55w0rd'), 'mike@whatsmycut.com'),
(2, '1380932134', 'cchanning', 'Carol', 'Channing', MD5('password'), 'carol.channing@gmail.com'),
(3, '1380932234', 'spenn', 'Sean', 'Penn', MD5('pennword'), 'sean_penn@gmail.com');
