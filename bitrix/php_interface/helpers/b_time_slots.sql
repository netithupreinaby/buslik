-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2017 at 12:35 AM
-- Server version: 5.5.47-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buslik`
--

-- --------------------------------------------------------

--
-- Table structure for table `b_time_slots`
--

CREATE TABLE IF NOT EXISTS `b_time_slots` (
  `id` int(14) unsigned NOT NULL,
  `outerId` varchar(40) NOT NULL,
  `start` int(13) unsigned NOT NULL,
  `end` int(13) unsigned NOT NULL,
  `type` tinyint(2) unsigned NOT NULL COMMENT '1 - big slot / 2 - small slot',
  `zoneId` int(13) unsigned NOT NULL,
  `reserved` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `b_time_slots`
--

INSERT INTO `b_time_slots` (`id`, `outerId`, `start`, `end`, `type`, `zoneId`, `reserved`) VALUES
(1, '3232', 1493241033, 1493249033, 1, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `b_time_slots`
--
ALTER TABLE `b_time_slots`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `b_time_slots`
--
ALTER TABLE `b_time_slots`
  MODIFY `id` int(14) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
