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
