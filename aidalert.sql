-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 07, 2024 at 06:06 PM
-- Server version: 5.0.27
-- PHP Version: 5.2.1
-- 
-- Database: `aidalert`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `donations`
-- 

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL auto_increment,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `bank_account` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(100) NOT NULL,
  `donation_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`donation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Dumping data for table `donations`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `payments`
-- 

CREATE TABLE `payments` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_number` varchar(50) NOT NULL,
  `reference_no` bigint(20) NOT NULL,
  `payment_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `payments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `first_name` varchar(255) default NULL,
  `last_name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` (`id`, `email`, `password`, `birthdate`, `first_name`, `last_name`) VALUES 
(19, 'cass@gmail.com', 'd53afbfa61a3884bafbb84e814309739', '2024-12-09', 'casssd', 'fer');
