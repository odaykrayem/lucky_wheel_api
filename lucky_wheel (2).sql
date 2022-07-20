-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306:3308
-- Generation Time: Jan 16, 2022 at 07:42 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lucky_wheel`
--

-- --------------------------------------------------------

--
-- Table structure for table `lottery_contests`
--

DROP TABLE IF EXISTS `lottery_contests`;
CREATE TABLE IF NOT EXISTS `lottery_contests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize` int(30) NOT NULL,
  `draw_date` date NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'مسابقة',
  `draw_time` varchar(100) NOT NULL DEFAULT '18:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lottery_contests`
--

INSERT INTO `lottery_contests` (`id`, `prize`, `draw_date`, `name`, `draw_time`) VALUES
(32, 100, '2021-12-28', 'مسابقة', '18:00:00'),
(33, 800, '2021-12-31', 'ت', '19:35');

-- --------------------------------------------------------

--
-- Table structure for table `minimum_balance`
--

DROP TABLE IF EXISTS `minimum_balance`;
CREATE TABLE IF NOT EXISTS `minimum_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `minimum_balance` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `minimum_balance`
--

INSERT INTO `minimum_balance` (`id`, `minimum_balance`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

DROP TABLE IF EXISTS `participants`;
CREATE TABLE IF NOT EXISTS `participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `is_winner` int(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `contest_id` (`contest_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`id`, `user_id`, `contest_id`, `is_winner`) VALUES
(70, 57, 32, 0),
(71, 57, 33, 0);

-- --------------------------------------------------------

--
-- Table structure for table `points_price`
--

DROP TABLE IF EXISTS `points_price`;
CREATE TABLE IF NOT EXISTS `points_price` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `points` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `points_price`
--

INSERT INTO `points_price` (`id`, `points`, `price`) VALUES
(1, 1000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) NOT NULL,
  `middle_name` varchar(125) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `points` int(10) NOT NULL DEFAULT '0',
  `balance` decimal(6,2) NOT NULL DEFAULT '0.00',
  `referral_code` varchar(250) NOT NULL,
  `ref_times` int(11) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  UNIQUE KEY `constraint_name` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `points`, `balance`, `referral_code`, `ref_times`) VALUES
(56, 'oday', 'oday', 'test', 'o@q', '202cb962ac59075b964b07152d234b70', 0, '0.00', '98C5CUGMB9SP5Z2B5CHI', 10),
(57, 'oday', 'test', 'test', 'oday@gmail', '827ccb0eea8a706c4c34a16891f84e7b', 443, '1709.00', '2HQQKDZAFYKFAR25VEY7', 10);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_requests`
--

DROP TABLE IF EXISTS `withdrawal_requests`;
CREATE TABLE IF NOT EXISTS `withdrawal_requests` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `amount` int(11) NOT NULL,
  `bank_code` varchar(200) NOT NULL,
  `date` timestamp NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `withdrawal_requests`
--

INSERT INTO `withdrawal_requests` (`id`, `user_id`, `amount`, `bank_code`, `date`, `status`) VALUES
(23, 57, 10, 'fdggdffdh', '2021-12-22 10:10:00', 1),
(26, 57, 10, 'fdggdffdh', '2021-12-22 10:10:00', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
