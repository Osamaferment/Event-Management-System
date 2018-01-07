-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 07, 2018 at 10:26 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `building_name_number` varchar(45) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `building_name_number`, `post_code`) VALUES
(6, 'Emirates Stadium', 'N7 8FH'),
(8, 'Wembley Stadium', 'NW6 6HA'),
(9, 'O2 Arena', 'SE5 9LH'),
(10, 'O2 Arena', 'SE1 2JS'),
(11, 'O2 Arena', 'SE3 1HS'),
(12, 'Wembley Stadium', 'N17 8HS'),
(13, 'Wembley Stadium', 'N5 6HS');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(100) NOT NULL,
  `event_date_time` datetime NOT NULL,
  `event_category_id` int(11) NOT NULL,
  `ticket_allocation` int(11) NOT NULL,
  `tickets_remaining` int(11) NOT NULL,
  `last_purchase_date` datetime NOT NULL,
  `created_by_user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `name`, `description`, `event_date_time`, `event_category_id`, `ticket_allocation`, `tickets_remaining`, `last_purchase_date`, `created_by_user_id`, `address_id`) VALUES
(7, 'Arsenal Vs Everton', 'Premier League', '2018-02-01 19:45:00', 1, 60000, 0, '2018-01-31 20:00:00', 1, 6),
(9, 'Liverpool vs Everton', 'FA Cup Semi-Final', '2018-01-31 20:00:00', 1, 90000, 89999, '2018-01-01 21:00:00', 1, 8),
(10, 'Federer Vs Nadal', 'ATP World Tour Finals ', '2018-01-26 10:40:00', 3, 5000, 4993, '2018-01-23 20:00:00', 15, 11),
(12, 'Arsenal Vs Chelsea', 'FA Cup Final 2018', '2018-01-10 10:00:00', 1, 90000, 90000, '2018-01-01 10:00:00', 20, 13);

-- --------------------------------------------------------

--
-- Table structure for table `event_bookings`
--

DROP TABLE IF EXISTS `event_bookings`;
CREATE TABLE IF NOT EXISTS `event_bookings` (
  `event_booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_date_time` datetime NOT NULL,
  `no_tickets_booked` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`event_booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_bookings`
--

INSERT INTO `event_bookings` (`event_booking_id`, `booking_date_time`, `no_tickets_booked`, `event_id`, `user_id`) VALUES
(15, '2018-01-07 03:48:29', 2, 7, 1),
(16, '2018-01-07 07:55:00', 1, 7, 15),
(17, '2018-01-07 08:06:06', 1, 7, 15),
(18, '2018-01-07 08:18:30', 3, 7, 16),
(19, '2018-01-07 08:20:14', 2, 7, 16),
(20, '2018-01-07 08:21:44', 7, 10, 16),
(21, '2018-01-07 08:23:15', 4, 7, 16),
(22, '2018-01-07 09:34:47', 2, 7, 20),
(23, '2018-01-07 09:43:33', 1, 9, 20);

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

DROP TABLE IF EXISTS `event_categories`;
CREATE TABLE IF NOT EXISTS `event_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(20) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `idx_event_categories_category_name` (`category_name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_categories`
--

INSERT INTO `event_categories` (`category_id`, `category_name`) VALUES
(1, 'Football'),
(2, 'Cricket'),
(3, 'Tennis'),
(4, 'Rugby Union'),
(5, 'Rugby League'),
(6, 'Golf'),
(7, 'Formula 1'),
(8, 'Athletics');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `password` varchar(15) NOT NULL,
  `security_question` varchar(45) NOT NULL,
  `security_answer` varchar(45) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `security_question`, `security_answer`) VALUES
(1, 'asdasd@gmail.com', 'abc12345', 'hey', 'hi'),
(14, 'osamazaman@gmail.com', 'abc12345', 'What is your favourite film?', 'The Dark Knight'),
(15, 'osamazaman4@gmail.com', 'ferment634', 'Whats your favourite city?', 'London'),
(16, 'osama@gmail.com', 'abc12345', 'What is your favourite city?', 'NYC'),
(17, 'osamazam@hotmail.com', 'platoon56', 'What is your fav food?', 'fish'),
(18, 'osama44@hotmail.com', 'ferment634', 'what is your favourite city?', 'london'),
(19, 'asdasdasd@gmail.com', 'abc12345', 'What is my favourite color?', 'Blue'),
(20, 'osama45@hotmail.com', 'goat666', 'what is your favourite city?', 'london');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
