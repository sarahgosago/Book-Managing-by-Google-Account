-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 14, 2014 at 01:54 PM
-- Server version: 5.5.38
-- PHP Version: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE IF NOT EXISTS `staffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groups_id` int(11) DEFAULT NULL,
  `positions_id` int(11) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `regularized_at` date NOT NULL,
  `employee_id` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '2',
  `status` int(1) NOT NULL COMMENT '0=probationary, 1=regular, 2=project-based, 3=fixed-term, 4=klab staff',
  `firstname` varchar(60) DEFAULT NULL,
  `middlename` varchar(60) DEFAULT NULL,
  `lastname` varchar(60) DEFAULT NULL,
  `nickname` varchar(60) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `socket_id` text,
  `mobile_number` varchar(22) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_staffs_1` (`groups_id`),
  KEY `fk_staffs_2` (`positions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
