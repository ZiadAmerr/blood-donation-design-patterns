-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 04:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdp`
--
CREATE DATABASE IF NOT EXISTS `sdp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sdp`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_parent_name` (`parent_id`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `name`, `parent_id`) VALUES
(1, 'Home', NULL),
(2, 'Home', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blooddonation`
--

DROP TABLE IF EXISTS `blooddonation`;
CREATE TABLE IF NOT EXISTS `blooddonation` (
  `donation_id` int(11) NOT NULL,
  `number_of_liters` float NOT NULL,
  `blood_type` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
  PRIMARY KEY (`donation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bloodstock`
--

DROP TABLE IF EXISTS `bloodstock`;
CREATE TABLE IF NOT EXISTS `bloodstock` (
  `blood_type` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`blood_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carddetails`
--

DROP TABLE IF EXISTS `carddetails`;
CREATE TABLE IF NOT EXISTS `carddetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method_id` int(11) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `cvv` varchar(4) NOT NULL,
  `expiry_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_method_id` (`payment_method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

DROP TABLE IF EXISTS `donation`;
CREATE TABLE IF NOT EXISTS `donation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `type` enum('Blood','Money') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `donor_id` (`donor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donor`
--

DROP TABLE IF EXISTS `donor`;
CREATE TABLE IF NOT EXISTS `donor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `national_id` (`national_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor`
--

INSERT INTO `donor` (`id`, `name`, `date_of_birth`, `phone_number`, `national_id`, `address_id`) VALUES
(1, '', NULL, '', NULL, NULL),
(2, 'Abdelrahman', '2002-12-12', '01121105774', '30209220101234', 13),
(5, 'Abdelrahman', '2002-12-12', '01121105774', '30209220101235', 13),
(6, 'Abdelrahman', '1212-12-12', '01121105774', '30209220101237', 13);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('Workshop','Fundraiser','Outreach') NOT NULL,
  `title` varchar(255) NOT NULL,
  `address_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `money_donation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `money_donation_id` (`money_donation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moneydonation`
--

DROP TABLE IF EXISTS `moneydonation`;
CREATE TABLE IF NOT EXISTS `moneydonation` (
  `donation_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL,
  `date` date NOT NULL,
  `national_id` varchar(20) NOT NULL,
  PRIMARY KEY (`donation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moneydonation`
--

INSERT INTO `moneydonation` (`donation_id`, `amount`, `date`, `national_id`) VALUES
(2, 12, '2002-12-12', '30209220101235'),
(3, 900, '1212-12-12', '30209220101237');

-- --------------------------------------------------------

--
-- Table structure for table `moneydonationdetails`
--

DROP TABLE IF EXISTS `moneydonationdetails`;
CREATE TABLE IF NOT EXISTS `moneydonationdetails` (
  `donation_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`donation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moneystock`
--

DROP TABLE IF EXISTS `moneystock`;
CREATE TABLE IF NOT EXISTS `moneystock` (
  `totalCash` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moneystock`
--

INSERT INTO `moneystock` (`totalCash`) VALUES
(2037);

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethod`
--

DROP TABLE IF EXISTS `paymentmethod`;
CREATE TABLE IF NOT EXISTS `paymentmethod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('Credit','Debit','PayPal','BnsPay') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `national_id` varchar(20) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `national_id` (`national_id`),
  UNIQUE KEY `username` (`username`),
  KEY `address_id` (`address_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

DROP TABLE IF EXISTS `volunteer`;
CREATE TABLE IF NOT EXISTS `volunteer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteerskills`
--

DROP TABLE IF EXISTS `volunteerskills`;
CREATE TABLE IF NOT EXISTS `volunteerskills` (
  `volunteer_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  PRIMARY KEY (`volunteer_id`,`skill_id`),
  KEY `skill_id` (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `address` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blooddonation`
--
ALTER TABLE `blooddonation`
  ADD CONSTRAINT `blooddonation_ibfk_1` FOREIGN KEY (`donation_id`) REFERENCES `donation` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carddetails`
--
ALTER TABLE `carddetails`
  ADD CONSTRAINT `carddetails_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `paymentmethod` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donation`
--
ALTER TABLE `donation`
  ADD CONSTRAINT `donation_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donor` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`money_donation_id`) REFERENCES `moneydonationdetails` (`donation_id`) ON DELETE CASCADE;

--
-- Constraints for table `moneydonationdetails`
--
ALTER TABLE `moneydonationdetails`
  ADD CONSTRAINT `moneydonationdetails_ibfk_1` FOREIGN KEY (`donation_id`) REFERENCES `donation` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD CONSTRAINT `volunteer_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `volunteerskills`
--
ALTER TABLE `volunteerskills`
  ADD CONSTRAINT `volunteerskills_ibfk_1` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `volunteerskills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

