CREATE  TABLE `jbfw`.`car_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NULL ,
  PRIMARY KEY (`id`) )
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE `car_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumbnail` tinytext NOT NULL,
  `image` tinytext NOT NULL,
  `car_id` int(11) NOT NULL,
  `flags` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

CREATE  TABLE `jbfw`.`car` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` TINYTEXT NOT NULL ,
  `description` TEXT NOT NULL ,
  `age` TINYTEXT NOT NULL ,
  `min_rent` INT NOT NULL ,
  `ord` INT NOT NULL ,
  `flags` INT NOT NULL ,
  `type_id` INT NOT NULL ,
  `fuel` INT NOT NULL ,
  `seats` INT NOT NULL ,
  `baggage` INT NOT NULL ,
  `doors` INT NOT NULL ,
  `min_age` INT NOT NULL ,
  `office_id` INT NOT NULL ,
  `customer_id` INT NOT NULL ,
  `volume` DOUBLE NOT NULL ,
  `price_addseat` DOUBLE NOT NULL ,
  `price_insure` DOUBLE NOT NULL ,
  `price_franchise` DOUBLE NOT NULL ,
  `price_seat1` DOUBLE NOT NULL ,
  `price_seat2` DOUBLE NOT NULL ,
  `price_seat3` DOUBLE NOT NULL ,
  `price_chains` DOUBLE NOT NULL ,
  `price_navigator` DOUBLE NOT NULL ,
  `price_zalog` DOUBLE NOT NULL ,
  `discount1` DOUBLE NOT NULL ,
  `discount2` DOUBLE NOT NULL ,
  `discount3` DOUBLE NOT NULL ,
  `discount4` DOUBLE NOT NULL ,
  `discount5` DOUBLE NOT NULL ,
  `trans_airport` DOUBLE NOT NULL ,
  `trans_hotel` DOUBLE NOT NULL ,
  `trans_driver` DOUBLE NOT NULL ,
  `trans_dirty` DOUBLE NOT NULL ,
  PRIMARY KEY (`id`) )ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
