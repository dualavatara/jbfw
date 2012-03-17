
--
-- Table structure for table `realty`
--

CREATE TABLE IF NOT EXISTS `realty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text NOT NULL,
  `features` tinytext NOT NULL,
  `type` int(11) NOT NULL,
  `rooms` int(11) NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `total_floors` int(11) NOT NULL,
  `ord` int(11) NOT NULL,
  `flags` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `realty_image`
--

CREATE TABLE IF NOT EXISTS `realty_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumbnail` tinytext NOT NULL,
  `image` tinytext NOT NULL,
  `realty_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

