-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 11:22 AM
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
  `blood_group` varchar(15) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `name`, `mobile`, `alt_mobile`, `email`, `occupation`, `jobplace`, `address`, `blood_group`, `image`, `created_at`, `status`) VALUES
(1, 'test', '01913428714', '01718834655', 'hasan@yahoo.com', 'Govt. Service', '', 'ert', 'A+', 'uploads/profile_68db97a1ea9c97.04866374.jpeg', '2025-09-30 08:41:05', 'approved'),
(2, 'sssrrrrr', '01913428714', '01718834655', 'hasan@yahoo.com', 'sfs', '', 'wer', 'A-', 'uploads/profile_68db998ceff0c9.79544067.jpeg', '2025-09-30 08:49:16', 'approved'),
(3, 'test33', '01913428714', '90124823', 'hasan@yahoo.com', 'sfs', 'Dhaka', 'sfd', 'O+', 'uploads/profile_68db9a7967cb07.08456217.png', '2025-09-30 08:53:13', 'approved'),
(4, 'x', '01913428714', '01718834655', 'emran445@yahoo.com', 'Govt. Service', 'Dhaka', 'frg', 'O-', 'uploads/profile_68db9b9b8cec04.81182468.jpg', '2025-09-30 08:58:03', 'pending'),
(5, 'MD. ABUL HOSSAIN', '0137896654', '90124823', 'jamal@yahoo.com', 'sdff', 'Dhaka', 'sdf', 'O+', 'uploads/profile_68db9c6059ed57.90292719.jpg', '2025-09-30 09:01:20', 'pending'),
(6, 'yyy', '01913428714', '01718834655', 'kamal@yahoo.com', 'Govt. Service', 'Dhaka', 'sdf', 'O-', 'uploads/profile_68db9cba658892.57658186.png', '2025-09-30 09:02:50', 'approved'),
(7, 'tttt', '01913428714', '01718834655', 'hasan@yahoo.com', 'Govt. Service', 'Dhaka', 'dfgsd', 'B-', 'uploads/profile_68db9f5fe47994.61856551.jpeg', '2025-09-30 09:14:07', 'approved');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
