ALTER TABLE  `article` ADD  `content_short` TEXT NOT NULL ,
ADD  `photo_preview` TINYTEXT NOT NULL;

--
-- Table structure for table `banner`
--

CREATE TABLE IF NOT EXISTS `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` tinytext NOT NULL,
  `type` int(11) NOT NULL,
  `flags` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
