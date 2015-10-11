--
-- Table structure for table `grantcoinuser`
--

DROP TABLE IF EXISTS `grantcoinuser`;
CREATE TABLE `grantcoinuser` (
  `userid` int(11) NOT NULL COMMENT 'The user''s id',
  `address` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The grantcoin address',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores the user''s grantcoin address';


--
-- Table structure for table `marketdata`
--

DROP TABLE IF EXISTS `marketdata`;
CREATE TABLE `marketdata` (
  `id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Market ID',
  `bid` double DEFAULT NULL COMMENT 'current bid price',
  `ask` double DEFAULT NULL COMMENT 'current ask price',
  `high` double DEFAULT NULL COMMENT 'Highest price for the day',
  `low` double DEFAULT NULL COMMENT 'lowest price for the day',
  `last` double DEFAULT NULL COMMENT 'last price paid',
  `volume` double DEFAULT NULL COMMENT 'number of trades',
  `lastupdated` datetime NOT NULL COMMENT 'date and time of when the record was last updated',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Storage for market data to cache the data to reduce requests';