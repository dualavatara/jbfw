ALTER TABLE `jbfw`.`resort` ADD COLUMN `flags` INT NOT NULL  AFTER `gmaplink` ;
ALTER TABLE `jbfw`.`car_rent_office` ADD COLUMN `rent_rules_link` TINYTEXT NOT NULL  AFTER `rating` ;
