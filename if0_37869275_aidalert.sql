-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql111.byetcluster.com
-- Generation Time: Dec 08, 2024 at 02:23 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37869275_aidalert`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `bank_account` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(100) NOT NULL,
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `first_name`, `last_name`, `bank_account`, `amount`, `email`, `donation_date`) VALUES
(13, 'cass', 'fer', '898341', '18.00', 'cassandrafernandez562@gmail.com', '2024-12-07 19:00:24'),
(14, 'cass', 'fer', '213123', '39.00', 'cassandrafernandez562@gmail.com', '2024-12-07 19:33:30'),
(15, 'cass', 'fer', '898341', '21.00', 'cassandrafernandez562@gmail.com', '2024-12-08 01:17:10'),
(16, 'Dlala', 'Ajkala', '910287377281', '590.00', 'dlala@gmail.com', '2024-12-08 01:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_number` varchar(50) NOT NULL,
  `reference_no` bigint(20) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `first_name`, `last_name`, `email`, `amount`, `payment_method`, `payment_number`, `reference_no`, `payment_date`) VALUES
(5, 'cass', 'fer', 'cassandrafernandez562@gmail.com', '250.00', 'GCash', '009323312213', 86774410267, '2024-12-07 16:55:54'),
(6, 'cass', 'fer', 'cassandrafernandez562@gmail.com', '1000.00', 'PayPal', '2132134', 88104130555, '2024-12-07 18:06:48'),
(7, 'Cass', 'Jdjsja', 'cass@gmail.com', '1000.00', 'Bank Account', '7181726282', 84512299293, '2024-12-07 19:47:28'),
(8, 'cass', 'fer', 'cassandrafernandez562@gmail.com', '1000.00', 'GCash', '3213123214', 64526778246, '2024-12-08 01:10:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `birthdate`, `first_name`, `last_name`) VALUES
(21, 'fernandezmaryannemonica041605@gmail.com', '243252c98a9a50fd9396c7ba68285802', '2024-12-17', 'mary', 'joy'),
(22, 'cass@gmail.com', '3cab68956fa7c8129671ef96bc1adf7c', '2024-12-19', 'cass', 'fer'),
(23, 'cass1@gmail.com', 'c5011a527dbdac65ed64b9c4061abf51', '2024-12-25', 'cassdadad', 'fersadad'),
(25, 'fer@gmail.com', '3cab68956fa7c8129671ef96bc1adf7c', '2024-12-20', 'fern', 'cass');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
