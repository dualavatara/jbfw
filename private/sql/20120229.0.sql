CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `name` tinytext NOT NULL,
  `content` text NOT NULL,
  `type` int(11) NOT NULL,
  `flags` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `article` ADD  `photo` TINYTEXT NOT NULL AFTER  `name` ,
ADD  `ord` INT NOT NULL AFTER  `photo`;