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


--
-- Table structure for table `grantcoinusertransaction`
--

DROP TABLE IF EXISTS `grantcoinusertransaction`;
CREATE TABLE `grantcoinusertransaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id of the transaction',
  `userid` int(11) NOT NULL COMMENT 'id of the user making the transaction',
  `type` int(11) NOT NULL COMMENT 'the type of transaction',
  `amountgrt` double NOT NULL COMMENT 'how much grt is being transfered in the transaction',
  `isapproved` tinyint(1) NOT NULL COMMENT 'whether or not the transaction has been approved/committed',
  `approvaldate` datetime DEFAULT NULL COMMENT 'the date the transaction was approved',
  `membershipexpiration` datetime DEFAULT NULL COMMENT 'the datetime of when the membership expires',
  `withdrawaddress` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The grantcoin address to withdraw coins to',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Storage for GrantCoinUser Transactions' AUTO_INCREMENT=21 ;