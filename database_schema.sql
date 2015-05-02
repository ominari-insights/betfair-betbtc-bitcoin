-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 30-Abr-2015 às 19:28
-- Versão do servidor: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `api`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `markets`
--

CREATE TABLE IF NOT EXISTS `markets` (
`id` int(11) unsigned NOT NULL,
  `featured` int(11) NOT NULL,
  `sport` int(11) DEFAULT NULL,
  `betbtc` varchar(255) DEFAULT '',
  `betfair` varchar(255) DEFAULT NULL,
  `home_betbtc` varchar(255) DEFAULT NULL,
  `home_betfair` varchar(255) DEFAULT NULL,
  `away_betbtc` varchar(255) DEFAULT NULL,
  `away_betfair` varchar(255) DEFAULT NULL,
  `league_betbtc` varchar(255) DEFAULT NULL,
  `league_betfair` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  `event_date` datetime DEFAULT NULL,
  `event_date_betfair` datetime DEFAULT NULL,
  `back_price_home` decimal(10,3) DEFAULT NULL,
  `lay_price_home` decimal(10,3) DEFAULT NULL,
  `back_volume_home` decimal(10,3) DEFAULT NULL,
  `lay_volume_home` decimal(10,3) DEFAULT NULL,
  `back_price_away` decimal(10,3) DEFAULT NULL,
  `lay_price_away` decimal(10,3) DEFAULT NULL,
  `back_volume_away` decimal(10,3) DEFAULT NULL,
  `lay_volume_away` decimal(10,3) DEFAULT NULL,
  `back_price_draw` decimal(10,3) DEFAULT NULL,
  `lay_price_draw` decimal(10,3) DEFAULT NULL,
  `back_volume_draw` decimal(10,3) DEFAULT NULL,
  `lay_volume_draw` decimal(10,3) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `home_id` varchar(255) DEFAULT NULL,
  `away_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `markets`
--
ALTER TABLE `markets`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `betbtc` (`betbtc`), ADD UNIQUE KEY `betfair` (`betfair`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `markets`
--
ALTER TABLE `markets`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
