-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 08:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `friendsforeve03`
--

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `alt_mobile` varchar(50) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `occupation` varchar(191) DEFAULT NULL,
  `jobplace` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `name`, `mobile`, `alt_mobile`, `email`, `occupation`, `jobplace`, `address`, `created_at`, `status`) VALUES
(2, 'MD. ABUL HOSSAIN', '01913428714', '01718834655', 'hasan@yahoo.com', 'Govt. Service', 'Dhaka', '', '2025-09-14 08:41:52', 'approved'),
(5, 'test33', '01913428714', '01718834655', 'hasan@yahoo.com', 'Govt. Service', 'Dhaka', '', '2025-09-16 10:41:02', 'approved'),
(6, 'test', '01913428714', '01718834655', 'emran445@yahoo.com', 'Govt. Service', 'Dhaka', '', '2025-09-17 03:50:39', 'approved'),
(9, 'emran', '01913428714', '01718834655', 'jamal@yahoo.com', 'Govt. Service', 'Dhaka', '', '2025-09-17 04:09:02', 'approved'),
(15, 'hhh', '01913428714', '01718834655', 'kamal@yahoo.com', 'Govt. Service', 'Dhaka', '', '2025-09-18 06:27:23', 'pending'),
(16, 'ttt', '01913428714', '01718834655', 'emran445@yahoo.com', 'Govt. Service', 'Dhaka', 'dd', '2025-09-18 06:29:45', 'pending'),
(17, 'rrrr', '01913428714', '01718834655', 'hasan@yahoo.com', 'Govt. Service', 'Dhaka', 'f', '2025-09-18 06:32:03', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `alt_mobile` varchar(50) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `occupation` varchar(191) DEFAULT NULL,
  `jobplace` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `name`, `mobile`, `alt_mobile`, `email`, `occupation`, `jobplace`, `address`, `status`, `created_at`) VALUES
(1, 'MD. ABUL HOSSAIN', '01913428714', '01718834655', 'hasan@yahoo.com', 'Govt. Service', 'Dhaka', '', 'Approved', '2025-09-14 08:06:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'emran', '1234', '2025-09-15'),
(2, 'user', '$2y$10$GaubiISEpwd/0MeHd5EbV.AcbDigstWRJxkiprNcQzie.hE6PC/nW', '2025-09-15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
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
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
