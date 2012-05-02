ALTER TABLE `jbfw`.`car` ADD COLUMN `rent_include_flags` INT NOT NULL  AFTER `resort_id` ;
ALTER TABLE `jbfw`.`car` ADD COLUMN `min_exp` INT NOT NULL  AFTER `rent_include_flags` ;

CREATE  TABLE `jbfw`.`place` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `resort_id` INT NOT NULL ,
  `name` TINYTEXT NOT NULL ,
  `gps` TINYTEXT NOT NULL ,
  PRIMARY KEY (`id`) )
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `jbfw`.`car` ADD COLUMN `place_id` INT NOT NULL  AFTER `min_exp` ;

