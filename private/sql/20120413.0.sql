CREATE TABLE `navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `link` tinytext NOT NULL,
  `flags` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`) VALUES (1,0,'Основное меню','',1);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`) VALUES (2,0,'Левый список подвал','',1);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`) VALUES (3,0,'Правый список подвал','',1);
