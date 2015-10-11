--
-- Table structure for table `grantcoinuser`
--

DROP TABLE IF EXISTS `grantcoinuser`;
CREATE TABLE `grantcoinuser` (
  `userid` int(11) NOT NULL COMMENT 'The user''s id',
  `address` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The grantcoin address',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores the user''s grantcoin address';