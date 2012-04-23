CREATE  TABLE `jbfw`.`realty_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  PRIMARY KEY (`id`) );

ALTER TABLE `jbfw`.`realty_type` ADD COLUMN `flags` INT NULL  AFTER `name` ;


CREATE  TABLE `jbfw`.`appartment_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  PRIMARY KEY (`id`) );

ALTER TABLE `jbfw`.`appartment_type` ADD COLUMN `flags` INT NULL  AFTER `name` ;

