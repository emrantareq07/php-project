-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 12:52 PM
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
-- Table structure for table `ansar_tbl`
--

CREATE TABLE `ansar_tbl` (
  `id` int(11) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `sanctioned_post` int(11) DEFAULT 0,
  `male` int(11) DEFAULT 0,
  `female` int(11) DEFAULT 0,
  `total` int(11) GENERATED ALWAYS AS (`male` + `female`) STORED,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_basis_tbl`
--

CREATE TABLE `daily_basis_tbl` (
  `id` int(11) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `sanctioned_post` int(11) DEFAULT 0,
  `male` int(11) DEFAULT 0,
  `female` int(11) DEFAULT 0,
  `total` int(11) GENERATED ALWAYS AS (`male` + `female`) STORED,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(15, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-16 09:35:11', NULL, NULL, '2025-10-16 03:35:11', '2025-10-16 03:35:11'),
(16, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-19 09:58:21', NULL, NULL, '2025-10-19 03:58:21', '2025-10-19 03:58:21'),
(17, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-19 09:58:28', NULL, NULL, '2025-10-19 03:58:28', '2025-10-19 03:58:28'),
(18, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-20 09:25:04', NULL, NULL, '2025-10-20 03:25:04', '2025-10-20 03:25:04'),
(19, 'user', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:18:46', NULL, NULL, '2025-10-21 03:18:46', '2025-10-21 03:18:46'),
(20, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:18:53', NULL, NULL, '2025-10-21 03:18:53', '2025-10-21 03:18:53'),
(21, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:19:01', NULL, NULL, '2025-10-21 03:19:01', '2025-10-21 03:19:01'),
(22, 'emran', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:19:05', NULL, NULL, '2025-10-21 03:19:05', '2025-10-21 03:19:05'),
(23, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-21 09:20:08', NULL, NULL, '2025-10-21 03:20:08', '2025-10-21 03:20:08'),
(24, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-22 10:07:27', NULL, NULL, '2025-10-22 04:07:27', '2025-10-22 04:07:27'),
(25, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-22 10:07:34', NULL, NULL, '2025-10-22 04:07:35', '2025-10-22 04:07:35');

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
(1, 'sfcl', '2025-10-19', '', '1,1,0,0,100,0,0,0,0,0,0,0,0,0', '1,1,0,0,100,0,0,0,0,0,0,0,0,0', '', '1,1,0,11,0,0,0,0,0,1,0,0,0,0', '1,1,0,0,0,0,0,0,0,1,0,0,0,0', '', '1,0,1,11,0,0,0,0,0,1,0,0,0,0', '1,0,1,0,0,0,0,0,0,1,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-19 07:05:32', '2025-10-20 09:54:51');

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
-- Table structure for table `staffs_tbl`
--

CREATE TABLE `staffs_tbl` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `designation` text DEFAULT NULL,
  `grade` text DEFAULT NULL,
  `sanctioned_post` text DEFAULT NULL,
  `male` text DEFAULT NULL,
  `female` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staffs_tbl`
--

INSERT INTO `staffs_tbl` (`id`, `factory_name`, `date`, `designation`, `grade`, `sanctioned_post`, `male`, `female`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'sfcl', '2025-09-16', 'RS', 'Grade 11', '10', '5', '1', '6', 'active', '2025-10-22 10:46:04', '2025-10-22 10:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `staff_tbl`
--

CREATE TABLE `staff_tbl` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `department` varchar(255) NOT NULL,
  `g11_m` int(11) DEFAULT 0,
  `g11_f` int(11) DEFAULT 0,
  `g11_sanctioned_post` int(11) DEFAULT 0,
  `g11_vacant_post` int(11) DEFAULT 0,
  `g12_m` int(11) DEFAULT 0,
  `g12_f` int(11) DEFAULT 0,
  `g12_sanctioned_post` int(11) DEFAULT 0,
  `g12_vacant_post` int(11) DEFAULT 0,
  `g13_m` int(11) DEFAULT 0,
  `g13_f` int(11) DEFAULT 0,
  `g13_sanctioned_post` int(11) DEFAULT 0,
  `g13_vacant_post` int(11) DEFAULT 0,
  `g14_m` int(11) DEFAULT 0,
  `g14_f` int(11) DEFAULT 0,
  `g14_sanctioned_post` int(11) DEFAULT 0,
  `g14_vacant_post` int(11) DEFAULT 0,
  `g15_m` int(11) DEFAULT 0,
  `g15_f` int(11) DEFAULT 0,
  `g15_sanctioned_post` int(11) DEFAULT 0,
  `g15_vacant_post` int(11) DEFAULT 0,
  `g16_m` int(11) DEFAULT 0,
  `g16_f` int(11) DEFAULT 0,
  `g16_sanctioned_post` int(11) DEFAULT 0,
  `g16_vacant_post` int(11) DEFAULT 0,
  `g17_m` int(11) DEFAULT 0,
  `g17_f` int(11) DEFAULT 0,
  `g17_sanctioned_post` int(11) DEFAULT 0,
  `g17_vacant_post` int(11) DEFAULT 0,
  `g18_m` int(11) DEFAULT 0,
  `g18_f` int(11) DEFAULT 0,
  `g18_sanctioned_post` int(11) DEFAULT 0,
  `g18_vacant_post` int(11) DEFAULT 0,
  `g19_m` int(11) DEFAULT 0,
  `g19_f` int(11) DEFAULT 0,
  `g19_sanctioned_post` int(11) DEFAULT 0,
  `g19_vacant_post` int(11) DEFAULT 0,
  `g20_m` int(11) DEFAULT 0,
  `g20_f` int(11) DEFAULT 0,
  `g20_sanctioned_post` int(11) DEFAULT 0,
  `g20_vacant_post` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_tbl_new`
--

CREATE TABLE `staff_tbl_new` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `designation` text DEFAULT NULL,
  `grade` text DEFAULT NULL,
  `sanctioned_post` text DEFAULT NULL,
  `male` text DEFAULT NULL,
  `female` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'sfcl', '$2y$10$6J5IdNSWBCJOc.4IjlJD8.HvXz90KJEB0xsTjqLnY3feLreJsATza', 'SFCL..', 'SFCL', NULL, 'sfcl@yahoo.com', '2222', 'user', '2025-10-14 08:00:19', '2025-10-19 07:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `workers_tbl`
--

CREATE TABLE `workers_tbl` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `designation` text DEFAULT NULL,
  `grade` text DEFAULT NULL,
  `sanctioned_post` text DEFAULT NULL,
  `male` text DEFAULT NULL,
  `female` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers_tbl`
--

INSERT INTO `workers_tbl` (`id`, `factory_name`, `date`, `designation`, `grade`, `sanctioned_post`, `male`, `female`, `total`, `status`, `created_at`, `updated_at`) VALUES
(10, 'sfcl', '2025-09-01', 'SST,SST 1', 'Grade 3,Grade 3', '70,07', '07,07', '7,0', '14,7', 'active', '2025-10-22 04:10:27', '2025-10-22 04:52:09'),
(12, 'sfcl', '2025-09-18', 'SST', 'Grade 13', '10', '5', '5', '10', 'active', '2025-10-22 08:34:42', '2025-10-22 09:54:37'),
(13, 'sfcl', '2025-10-20', 'SST,SST 1', 'Grade 13,Grade 14', '10,20', '5,5', '5,5', '10,10', 'active', '2025-10-22 09:54:43', '2025-10-22 09:54:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ansar_tbl`
--
ALTER TABLE `ansar_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_basis_tbl`
--
ALTER TABLE `daily_basis_tbl`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `staffs_tbl`
--
ALTER TABLE `staffs_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_factory_date` (`factory_name`,`date`);

--
-- Indexes for table `staff_tbl`
--
ALTER TABLE `staff_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_tbl_new`
--
ALTER TABLE `staff_tbl_new`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_factory_date` (`factory_name`,`date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `workers_tbl`
--
ALTER TABLE `workers_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_factory_date` (`factory_name`,`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ansar_tbl`
--
ALTER TABLE `ansar_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_basis_tbl`
--
ALTER TABLE `daily_basis_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `officers_tbl`
--
ALTER TABLE `officers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sfcl`
--
ALTER TABLE `sfcl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staffs_tbl`
--
ALTER TABLE `staffs_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff_tbl`
--
ALTER TABLE `staff_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_tbl_new`
--
ALTER TABLE `staff_tbl_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workers_tbl`
--
ALTER TABLE `workers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
