DROP TABLE `jbfw`.`realty_type`;
CREATE  TABLE `jbfw`.`realty_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;;

ALTER TABLE `jbfw`.`realty_type` ADD COLUMN `flags` INT NULL  AFTER `name` ;

DROP TABLE `jbfw`.`appartment_type`;
CREATE  TABLE `jbfw`.`appartment_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;;

ALTER TABLE `jbfw`.`appartment_type` ADD COLUMN `flags` INT NULL  AFTER `name` ;

