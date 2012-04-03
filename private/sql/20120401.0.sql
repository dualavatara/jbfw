CREATE  TABLE `jbfw`.`appartment` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  `description` TEXT NOT NULL ,
  `features` TINYTEXT NOT NULL ,
  `type` INT NOT NULL ,
  `rooms` INT NOT NULL ,
  `bedrooms` INT NOT NULL ,
  `floor` INT NOT NULL ,
  `ord` INT NOT NULL ,
  `flags` INT NOT NULL ,
  `realty_id` INT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
ALTER TABLE `jbfw`.`price` ADD COLUMN `type` INT NOT NULL  AFTER `object_id` ;
ALTER TABLE `jbfw`.`price` ADD COLUMN `month_disc` DOUBLE NOT NULL  AFTER `type` , ADD COLUMN `week_disc` DOUBLE NOT NULL  AFTER `month_disc` ;
ALTER TABLE `jbfw`.`realty` ADD COLUMN `gmap` TEXT NOT NULL  AFTER `stars` ;

ALTER TABLE `jbfw`.`realty` ADD COLUMN `area` INT NOT NULL  AFTER `gmap` ,
ADD COLUMN `plotarea` INT NOT NULL  AFTER `area` ,
ADD COLUMN `condstate` TINYTEXT NOT NULL  AFTER `plotarea` ,
ADD COLUMN `miscflags` INT NOT NULL  AFTER `condstate` ,
ADD COLUMN `age` TINYTEXT NOT NULL  AFTER `miscflags` ,
CHANGE COLUMN `floor` `floor` TINYTEXT NOT NULL  ;
