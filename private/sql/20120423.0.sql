ALTER TABLE `jbfw`.`appartment` ADD COLUMN `adults` INT NOT NULL  AFTER `realty_id` , ADD COLUMN `kids` INT NOT NULL  AFTER `adults` ;
ALTER TABLE `jbfw`.`realty` ADD COLUMN `adults` INT NOT NULL  AFTER `age` , ADD COLUMN `kids` INT NOT NULL  AFTER `adults` ;
