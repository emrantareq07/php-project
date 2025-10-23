-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 12:22 PM
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
-- Database: `man_power_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_table`
--

CREATE TABLE `log_table` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `event_type` enum('login','logout') NOT NULL DEFAULT 'login',
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success',
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_table`
--

INSERT INTO `log_table` (`id`, `username`, `event_type`, `ip_address`, `user_agent`, `status`, `login_time`, `logout_time`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 10:01:15', NULL, NULL, '2025-10-14 08:01:15', '2025-10-14 08:01:15'),
(2, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:02:54', NULL, NULL, '2025-10-14 08:02:54', '2025-10-14 08:02:54'),
(3, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:05:23', NULL, NULL, '2025-10-14 08:05:23', '2025-10-14 08:05:23'),
(4, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:05:47', NULL, NULL, '2025-10-14 08:05:47', '2025-10-14 08:05:47'),
(5, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:05:55', NULL, NULL, '2025-10-14 08:05:55', '2025-10-14 08:05:55'),
(6, 'user', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 14:07:11', NULL, NULL, '2025-10-14 08:07:11', '2025-10-14 08:07:11'),
(7, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 10:08:14', NULL, NULL, '2025-10-14 08:08:14', '2025-10-14 08:08:14'),
(8, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:08:21', NULL, NULL, '2025-10-14 08:08:21', '2025-10-14 08:08:21'),
(9, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 14:08:28', NULL, NULL, '2025-10-14 08:08:28', '2025-10-14 08:08:28'),
(10, 'dsfs', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 10:08:32', NULL, NULL, '2025-10-14 08:08:32', '2025-10-14 08:08:32'),
(11, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 14:12:42', NULL, NULL, '2025-10-14 08:12:42', '2025-10-14 08:12:42'),
(12, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-15 12:42:06', NULL, NULL, '2025-10-15 06:42:06', '2025-10-15 06:42:06'),
(13, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-15 12:57:26', NULL, NULL, '2025-10-15 06:57:26', '2025-10-15 06:57:26'),
(14, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-15 12:57:34', NULL, NULL, '2025-10-15 06:57:34', '2025-10-15 06:57:34'),
(15, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-16 09:35:11', NULL, NULL, '2025-10-16 03:35:11', '2025-10-16 03:35:11');

-- --------------------------------------------------------

--
-- Table structure for table `officers_tbl`
--

CREATE TABLE `officers_tbl` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `department` varchar(100) NOT NULL,
  `g2_m` text NOT NULL,
  `g2_f` text NOT NULL,
  `g2_sanctioned_post` text NOT NULL,
  `g3_m` text NOT NULL,
  `g3_f` text NOT NULL,
  `g3_sanctioned_post` text NOT NULL,
  `g4_m` text NOT NULL,
  `g4_f` text NOT NULL,
  `g4_sanctioned_post` text NOT NULL,
  `g5_m` text NOT NULL,
  `g5_f` text NOT NULL,
  `g5_sanctioned_post` text NOT NULL,
  `g6_m` text NOT NULL,
  `g6_f` text NOT NULL,
  `g6_sanctioned_post` text NOT NULL,
  `g7_m` text NOT NULL,
  `g7_f` text NOT NULL,
  `g7_sanctioned_post` text NOT NULL,
  `g8_m` text NOT NULL,
  `g8_f` text NOT NULL,
  `g8_sanctioned_post` text NOT NULL,
  `g9_m` text NOT NULL,
  `g9_f` text NOT NULL,
  `g9_sanctioned_post` text NOT NULL,
  `g10_m` text NOT NULL,
  `g10_f` text NOT NULL,
  `g10_sanctioned_post` text NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officers_tbl`
--

INSERT INTO `officers_tbl` (`id`, `factory_name`, `date`, `department`, `g2_m`, `g2_f`, `g2_sanctioned_post`, `g3_m`, `g3_f`, `g3_sanctioned_post`, `g4_m`, `g4_f`, `g4_sanctioned_post`, `g5_m`, `g5_f`, `g5_sanctioned_post`, `g6_m`, `g6_f`, `g6_sanctioned_post`, `g7_m`, `g7_f`, `g7_sanctioned_post`, `g8_m`, `g8_f`, `g8_sanctioned_post`, `g9_m`, `g9_f`, `g9_sanctioned_post`, `g10_m`, `g10_f`, `g10_sanctioned_post`, `status`, `created_at`, `updated_at`) VALUES
(2, 'sfcl', '2025-09-30', '', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-16 06:05:18', '2025-10-16 16:20:14'),
(3, 'sfcl', '2025-10-16', '', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-16 06:13:17', '2025-10-16 12:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `sfcl`
--

CREATE TABLE `sfcl` (
  `id` int(11) NOT NULL,
  `reports_month` varchar(100) NOT NULL,
  `factory_name` varchar(255) NOT NULL,
  `employee_type` enum('officer','staff','worker') NOT NULL,
  `division` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `grade_class` varchar(100) DEFAULT NULL,
  `male` int(11) DEFAULT 0,
  `female` int(11) DEFAULT 0,
  `sanctioned_post` int(11) DEFAULT 0,
  `filled_post` int(11) DEFAULT 0,
  `vacant_post` int(11) DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sfcl`
--

INSERT INTO `sfcl` (`id`, `reports_month`, `factory_name`, `employee_type`, `division`, `department`, `designation`, `grade`, `grade_class`, `male`, `female`, `sanctioned_post`, `filled_post`, `vacant_post`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'October', 'sfcl', 'officer', 'Administration', 'Security', 'Assistant Manager', '3', '0', 2, 1, 0, 0, 0, '0', '2025-10-15 16:08:08', '2025-10-15 16:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `factory_name` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` enum('admin','user','sadmin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `factory_name`, `designation`, `email`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'sfcl', '$2y$10$6J5IdNSWBCJOc.4IjlJD8.HvXz90KJEB0xsTjqLnY3feLreJsATza', 'SFCL', 'SFCL', NULL, 'sfcl@yahoo.com', '2222', 'user', '2025-10-14 08:00:19', '2025-10-14 08:00:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_table`
--
ALTER TABLE `log_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officers_tbl`
--
ALTER TABLE `officers_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sfcl`
--
ALTER TABLE `sfcl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `officers_tbl`
--
ALTER TABLE `officers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sfcl`
--
ALTER TABLE `sfcl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
