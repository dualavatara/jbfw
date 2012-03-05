
--
-- Table structure for table `car_rent_office`
--

CREATE TABLE IF NOT EXISTS `car_rent_office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text NOT NULL,
  `percent` double NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `phone_msk` tinytext NOT NULL,
  `phone_local` tinytext NOT NULL,
  `address` text NOT NULL,
  `country` tinytext NOT NULL,
  `email` tinytext NOT NULL,
  `skype` tinytext NOT NULL,
  `icq` tinytext NOT NULL,
  `admin_note` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
