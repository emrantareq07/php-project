-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 10:23 AM
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
(7, 'test', '01913428714', '45634534535', '', 'Govt. Service', 'Dhaka', 'zxc', 'A+', 'uploads/profile_68e2157ae90ff7.52896517.jpg', '2025-10-05 06:51:38', 'approved'),
(8, 'sssrrrrr', '01913428714', '01718834655', 'emran@yahoo.com', 'Govt. Service', 'Dhaka', 'sdf', 'A-', 'uploads/profile_68e219b97e5900.84122994.jpeg', '2025-10-05 07:09:45', 'pending'),
(9, 'MD. ABUL HOSSAIN', '0137896654', '90124823', 'hasan@yahoo.com', 'sfs', 'Dhaka', 'dd', 'A-', 'uploads/profile_68e23140ec3a84.28364189.jpeg', '2025-10-05 08:50:08', 'approved'),
(10, 'x', '0137896654', '90124823', 'hasan@yahoo.com', 'sf', 'Dhaka', 'dsf', 'B-', 'uploads/profile_68e23179292610.37746645.jpeg', '2025-10-05 08:51:05', 'pending'),
(11, 'y', '0137896654', '90124823', 'hasan44@yahoo.com', 'Govt. Service', 'sdf', 'sdfd', 'A+', 'uploads/profile_68e231bf179ca4.02065774.jpg', '2025-10-05 08:52:15', 'approved'),
(12, 'jjj', '0137896654', '90124823', 'hasan44@yahoo.com', 'sdf', 'sdf', 'sd', 'O+', 'uploads/profile_68e231f7c80ad0.81082950.jpg', '2025-10-05 08:53:11', 'pending');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
