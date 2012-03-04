
--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES(1, 'Телефон 1', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(2, 'Телефон 2', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(3, 'Факс', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(4, 'Skype', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(5, 'ICQ', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(6, 'Facebook', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(7, 'Адрес офиса', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(8, 'Координаты GPS офиса', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(9, 'Email', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(10, 'Описание проекта', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(11, 'Название проекта', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(12, 'Количество предложений по объектам на 1 страницу', '');
INSERT INTO `settings` (`id`, `name`, `value`) VALUES(13, 'Текст для CEO', '');

--
-- Table structure for table `resort`
--

CREATE TABLE IF NOT EXISTS `resort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `link` tinytext NOT NULL,
  `gmaplink` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
