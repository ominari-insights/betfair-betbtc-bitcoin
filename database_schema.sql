# ************************************************************
# Sequel Pro SQL dump
# Version 4703
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.0.17-MariaDB)
# Database: api_beta
# Generation Time: 2016-03-25 11:51:39 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table Markets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Markets`;

CREATE TABLE `Markets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sport` int(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mkt_name` varchar(255) DEFAULT NULL,
  `betbtc` varchar(255) DEFAULT '',
  `betfair` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `almost` tinyint(4) DEFAULT '0',
  `live` tinyint(4) DEFAULT '0',
  `event_date` datetime DEFAULT NULL,
  `selections` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `betbtc` (`betbtc`),
  UNIQUE KEY `betfair` (`betfair`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
