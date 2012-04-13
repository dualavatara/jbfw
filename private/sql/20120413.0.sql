CREATE TABLE `navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `link` tinytext NOT NULL,
  `flags` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `jbfw`.`navigation` ADD COLUMN `ord` INT NULL  AFTER `flags` ;


INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (1,0,'Основное меню','',1,0);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (2,0,'Левый список подвал','',1,0);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (3,0,'Правый список подвал','',1,0);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (4,1,'Аренда авто','/car?type=rent',1,100);;
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (5,1,'Аренда жилья','#',1,90);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (6,1,'Продажа авто','/car?type=sell',1,80);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (7,1,'Недвижимость','/realty?',1,70);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (8,1,'Услуги','#',1,60);
INSERT INTO `navigation` (`id`,`parent_id`,`name`,`link`,`flags`,`ord`) VALUES (9,1,'Советы','#',1,50);
