-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2014 at 08:54 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dailywho_whois`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `address_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `address_line1` varchar(100) NOT NULL,
  `address_line2` varchar(100) NOT NULL,
  `address_line3` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `country` varchar(20) NOT NULL,
  `address_updated_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `domain`
--

CREATE TABLE IF NOT EXISTS `domain` (
  `domain_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(50) NOT NULL,
  `domain_sponsor` varchar(100) DEFAULT NULL,
  `domain_status1` varchar(50) DEFAULT NULL,
  `domain_status2` varchar(50) DEFAULT NULL,
  `domain_status3` varchar(50) DEFAULT NULL,
  `domain_status4` varchar(50) DEFAULT NULL,
  `domain_nserver1` varchar(60) DEFAULT NULL,
  `domain_nserver2` varchar(60) DEFAULT NULL,
  `domain_nserver3` varchar(60) DEFAULT NULL,
  `domain_nserver4` varchar(60) DEFAULT NULL,
  `domain_referer` varchar(40) DEFAULT NULL,
  `domain_created` date DEFAULT NULL,
  `domain_changed` date DEFAULT NULL,
  `domain_expires` date DEFAULT NULL,
  `owner_name` varchar(50) DEFAULT NULL,
  `owner_organization` varchar(50) DEFAULT NULL,
  `owner_address_street` varchar(100) DEFAULT NULL,
  `owner_address_city` varchar(50) DEFAULT NULL,
  `owner_address_state` varchar(50) DEFAULT NULL,
  `owner_address_pcode` varchar(50) DEFAULT NULL,
  `owner_address_country` varchar(50) DEFAULT NULL,
  `owner_phone` varchar(50) DEFAULT NULL,
  `owner_email` varchar(50) DEFAULT NULL,
  `admin_name` varchar(50) DEFAULT NULL,
  `admin_organization` varchar(50) DEFAULT NULL,
  `admin_address_street` varchar(100) DEFAULT NULL,
  `admin_address_city` varchar(50) DEFAULT NULL,
  `admin_address_state` varchar(50) DEFAULT NULL,
  `admin_address_pcode` varchar(50) DEFAULT NULL,
  `admin_address_country` varchar(50) DEFAULT NULL,
  `admin_phone` varchar(50) DEFAULT NULL,
  `admin_email` varchar(50) DEFAULT NULL,
  `tech_name` varchar(50) DEFAULT NULL,
  `tech_organization` varchar(50) DEFAULT NULL,
  `tech_address_street` varchar(100) DEFAULT NULL,
  `tech_address_city` varchar(50) DEFAULT NULL,
  `tech_address_state` varchar(50) DEFAULT NULL,
  `tech_address_pcode` varchar(50) DEFAULT NULL,
  `tech_address_country` varchar(50) DEFAULT NULL,
  `tech_phone` varchar(50) DEFAULT NULL,
  `tech_email` varchar(50) DEFAULT NULL,
  `updated_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`domain_id`),
  UNIQUE KEY `domain_name` (`domain_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_domains`
--

CREATE TABLE IF NOT EXISTS `temp_domains` (
  `dom_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dom_name` varchar(60) NOT NULL,
  `server_id` int(11) DEFAULT NULL,
  `dom_status` varchar(22) DEFAULT NULL,
  `dom_update_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dom_id`),
  UNIQUE KEY `dom_name` (`dom_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `whoisserver`
--

CREATE TABLE IF NOT EXISTS `whoisserver` (
  `server_id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(45) DEFAULT NULL,
  `server_update_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`server_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
