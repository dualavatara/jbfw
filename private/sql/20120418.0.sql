CREATE TABLE `article_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumbnail` tinytext NOT NULL,
  `image` tinytext NOT NULL,
  `article_id` int(11) NOT NULL,
  `flags` int(11) NOT NULL,
  `thumbnail50` tinytext NOT NULL,
  `thumbnail125` tinytext NOT NULL,
  `thumbnail200` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

