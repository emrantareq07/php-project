-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2026 at 09:53 AM
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
-- Table structure for table `all_employee`
--

CREATE TABLE `all_employee` (
  `code` varchar(255) NOT NULL,
  `designation_bn` varchar(255) NOT NULL,
  `designation_en` varchar(255) NOT NULL,
  `post` varchar(255) NOT NULL,
  `responsibilities` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `all_employee`
--

INSERT INTO `all_employee` (`code`, `designation_bn`, `designation_en`, `post`, `responsibilities`) VALUES
('1001', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1002', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1003', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1004', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1005', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1006', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1007', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1008', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('1009', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('10010', 'সহকারী প্রোগ্রামার', 'Assistant Programmer', 'Assistant Programmer', ''),
('10011', 'প্রোগ্রামার', 'প্রোগ্রামার', 'প্রোগ্রামার', ''),
('10012', 'প্রোগ্রামার', 'প্রোগ্রামার', 'প্রোগ্রামার', ''),
('2001', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2002', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2003', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2004', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2005', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2006', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2007', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2008', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('2009', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('20010', 'সহকারী ব্যবস্থাপক (বাণিজ্যিক)', 'Assistant Manager (Commercial)', 'Assistant manager', ''),
('10013', 'System Analyst', 'System Analyst', 'System Analyst', ''),
('10014', 'System Analyst', 'System Analyst', 'System Analyst', ''),
('10015', 'System Analyst', 'System Analyst', 'System Analyst', ''),
('20011', 'DGM', 'DGM', 'DGM', ''),
('20012', 'DGM', 'DGM', 'DGM', ''),
('20013', 'DGM', 'DGM', 'DGM', ''),
('20014', 'DGM', 'DGM', 'DGM', ''),
('20015', 'DGM', 'DGM', 'DGM', ''),
('20016', 'GM', 'GM', 'GM', ''),
('20017', 'GM', 'GM', 'GM', ''),
('20018', 'SGM', 'SGM', 'SGM', ''),
('20019', 'SGM', 'SGM', 'SGM', ''),
('20020', 'SGM', 'SGM', 'SGM', '');

-- --------------------------------------------------------

--
-- Table structure for table `ansar_tbl`
--

CREATE TABLE `ansar_tbl` (
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
-- Dumping data for table `ansar_tbl`
--

INSERT INTO `ansar_tbl` (`id`, `factory_name`, `date`, `designation`, `grade`, `sanctioned_post`, `male`, `female`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'afccl', '2026-04-20', ',', ',', '10,0', '5,0', '2,0', '7,0', 'active', '2026-04-20 04:54:53', '2026-04-21 04:21:51');

-- --------------------------------------------------------

--
-- Table structure for table `daily_basis_tbl`
--

CREATE TABLE `daily_basis_tbl` (
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
-- Dumping data for table `daily_basis_tbl`
--

INSERT INTO `daily_basis_tbl` (`id`, `factory_name`, `date`, `designation`, `grade`, `sanctioned_post`, `male`, `female`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'afccl', '2026-04-20', 'MT,MO', ',', '10,20', '2,20', '2,0', '4,20', 'active', '2026-04-20 04:53:55', '2026-04-21 04:29:00');

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
(25, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-22 10:07:34', NULL, NULL, '2025-10-22 04:07:35', '2025-10-22 04:07:35'),
(26, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-23 09:15:15', NULL, NULL, '2025-10-23 03:15:15', '2025-10-23 03:15:15'),
(27, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:42:32', NULL, NULL, '2025-10-26 03:42:32', '2025-10-26 03:42:32'),
(28, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:43:00', NULL, NULL, '2025-10-26 03:43:00', '2025-10-26 03:43:00'),
(29, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:43:05', NULL, NULL, '2025-10-26 03:43:05', '2025-10-26 03:43:05'),
(30, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:45:26', NULL, NULL, '2025-10-26 03:45:26', '2025-10-26 03:45:26'),
(31, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:45:32', NULL, NULL, '2025-10-26 03:45:32', '2025-10-26 03:45:32'),
(32, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:10:13', NULL, NULL, '2025-10-26 04:10:13', '2025-10-26 04:10:13'),
(33, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:10:18', NULL, NULL, '2025-10-26 04:10:19', '2025-10-26 04:10:19'),
(34, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:08', NULL, NULL, '2025-10-26 04:12:08', '2025-10-26 04:12:08'),
(35, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:11', NULL, NULL, '2025-10-26 04:12:11', '2025-10-26 04:12:11'),
(36, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:24', NULL, NULL, '2025-10-26 04:12:24', '2025-10-26 04:12:24'),
(37, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:28', NULL, NULL, '2025-10-26 04:12:28', '2025-10-26 04:12:28'),
(38, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:14:15', NULL, NULL, '2025-10-26 04:14:15', '2025-10-26 04:14:15'),
(39, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:14:19', NULL, NULL, '2025-10-26 04:14:19', '2025-10-26 04:14:19'),
(40, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:19', NULL, NULL, '2025-10-26 04:15:19', '2025-10-26 04:15:19'),
(41, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:24', NULL, NULL, '2025-10-26 04:15:24', '2025-10-26 04:15:24'),
(42, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:50', NULL, NULL, '2025-10-26 04:15:50', '2025-10-26 04:15:50'),
(43, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:53', NULL, NULL, '2025-10-26 04:15:53', '2025-10-26 04:15:53'),
(44, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:17:04', NULL, NULL, '2025-10-26 04:17:04', '2025-10-26 04:17:04'),
(45, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:17:09', NULL, NULL, '2025-10-26 04:17:09', '2025-10-26 04:17:09'),
(46, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:34:56', NULL, NULL, '2025-10-26 04:34:56', '2025-10-26 04:34:56'),
(47, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:35:01', NULL, NULL, '2025-10-26 04:35:01', '2025-10-26 04:35:01'),
(48, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:35:43', NULL, NULL, '2025-10-26 04:35:43', '2025-10-26 04:35:43'),
(49, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:35:49', NULL, NULL, '2025-10-26 04:35:49', '2025-10-26 04:35:49'),
(50, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:43:09', NULL, NULL, '2025-10-26 04:43:09', '2025-10-26 04:43:09'),
(51, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:43:14', NULL, NULL, '2025-10-26 04:43:14', '2025-10-26 04:43:14'),
(52, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:47:36', NULL, NULL, '2025-10-26 04:47:36', '2025-10-26 04:47:36'),
(53, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:47:47', NULL, NULL, '2025-10-26 04:47:47', '2025-10-26 04:47:47'),
(54, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:49:21', NULL, NULL, '2025-10-26 04:49:21', '2025-10-26 04:49:21'),
(55, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:49:25', NULL, NULL, '2025-10-26 04:49:25', '2025-10-26 04:49:25'),
(56, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 13:43:43', NULL, NULL, '2025-10-26 07:43:43', '2025-10-26 07:43:43'),
(57, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 13:43:47', NULL, NULL, '2025-10-26 07:43:47', '2025-10-26 07:43:47'),
(58, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 14:51:15', NULL, NULL, '2025-10-26 08:51:15', '2025-10-26 08:51:15'),
(59, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 14:51:19', NULL, NULL, '2025-10-26 08:51:19', '2025-10-26 08:51:19'),
(60, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:02:43', NULL, NULL, '2025-10-26 10:02:43', '2025-10-26 10:02:43'),
(61, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:02:49', NULL, NULL, '2025-10-26 10:02:49', '2025-10-26 10:02:49'),
(62, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:04:43', NULL, NULL, '2025-10-26 10:04:43', '2025-10-26 10:04:43'),
(63, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:04:47', NULL, NULL, '2025-10-26 10:04:47', '2025-10-26 10:04:47'),
(64, 'user', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-27 14:18:37', NULL, NULL, '2025-10-27 08:18:37', '2025-10-27 08:18:37'),
(65, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-27 14:19:09', NULL, NULL, '2025-10-27 08:19:09', '2025-10-27 08:19:09'),
(66, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:42:39', NULL, NULL, '2025-10-29 03:42:39', '2025-10-29 03:42:39'),
(67, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:52:35', NULL, NULL, '2025-10-29 03:52:35', '2025-10-29 03:52:35'),
(68, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:52:39', NULL, NULL, '2025-10-29 03:52:39', '2025-10-29 03:52:39'),
(69, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:55:10', NULL, NULL, '2025-10-29 03:55:10', '2025-10-29 03:55:10'),
(70, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:55:18', NULL, NULL, '2025-10-29 03:55:18', '2025-10-29 03:55:18'),
(71, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:56:19', NULL, NULL, '2025-10-29 03:56:19', '2025-10-29 03:56:19'),
(72, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:56:23', NULL, NULL, '2025-10-29 03:56:23', '2025-10-29 03:56:23'),
(73, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:11', NULL, NULL, '2025-10-29 03:57:11', '2025-10-29 03:57:11'),
(74, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:17', NULL, NULL, '2025-10-29 03:57:17', '2025-10-29 03:57:17'),
(75, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:20', NULL, NULL, '2025-10-29 03:57:20', '2025-10-29 03:57:20'),
(76, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:24', NULL, NULL, '2025-10-29 03:57:24', '2025-10-29 03:57:24'),
(77, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:21', NULL, NULL, '2025-10-29 03:59:21', '2025-10-29 03:59:21'),
(78, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:26', NULL, NULL, '2025-10-29 03:59:26', '2025-10-29 03:59:26'),
(79, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:39', NULL, NULL, '2025-10-29 03:59:39', '2025-10-29 03:59:39'),
(80, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:47', NULL, NULL, '2025-10-29 03:59:47', '2025-10-29 03:59:47'),
(81, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:00:20', NULL, NULL, '2025-10-29 04:00:20', '2025-10-29 04:00:20'),
(82, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:00:29', NULL, NULL, '2025-10-29 04:00:29', '2025-10-29 04:00:29'),
(83, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:19:17', NULL, NULL, '2025-10-29 04:19:17', '2025-10-29 04:19:17'),
(84, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:19:21', NULL, NULL, '2025-10-29 04:19:21', '2025-10-29 04:19:21'),
(85, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:23', NULL, NULL, '2025-10-29 04:26:23', '2025-10-29 04:26:23'),
(86, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:27', NULL, NULL, '2025-10-29 04:26:27', '2025-10-29 04:26:27'),
(87, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:37', NULL, NULL, '2025-10-29 04:26:37', '2025-10-29 04:26:37'),
(88, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:41', NULL, NULL, '2025-10-29 04:26:41', '2025-10-29 04:26:41'),
(89, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:57:16', NULL, NULL, '2025-10-29 04:57:16', '2025-10-29 04:57:16'),
(90, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:57:20', NULL, NULL, '2025-10-29 04:57:20', '2025-10-29 04:57:20'),
(91, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:58:17', NULL, NULL, '2025-10-29 04:58:17', '2025-10-29 04:58:17'),
(92, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:58:20', NULL, NULL, '2025-10-29 04:58:20', '2025-10-29 04:58:20'),
(93, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:04:36', NULL, NULL, '2025-10-29 05:04:36', '2025-10-29 05:04:36'),
(94, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:04:40', NULL, NULL, '2025-10-29 05:04:40', '2025-10-29 05:04:40'),
(95, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:07:21', NULL, NULL, '2025-10-29 05:07:21', '2025-10-29 05:07:21'),
(96, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:07:25', NULL, NULL, '2025-10-29 05:07:25', '2025-10-29 05:07:25'),
(97, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:10:09', NULL, NULL, '2025-10-29 05:10:09', '2025-10-29 05:10:09'),
(98, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:10:12', NULL, NULL, '2025-10-29 05:10:12', '2025-10-29 05:10:12'),
(99, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:16:27', NULL, NULL, '2025-10-29 05:16:27', '2025-10-29 05:16:27'),
(100, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:16:32', NULL, NULL, '2025-10-29 05:16:32', '2025-10-29 05:16:32'),
(101, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:17:46', NULL, NULL, '2025-10-29 05:17:46', '2025-10-29 05:17:46'),
(102, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:17:50', NULL, NULL, '2025-10-29 05:17:50', '2025-10-29 05:17:50'),
(103, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:17:56', NULL, NULL, '2025-10-29 05:17:56', '2025-10-29 05:17:56'),
(104, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:18:06', NULL, NULL, '2025-10-29 05:18:06', '2025-10-29 05:18:06'),
(105, '', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:27:37', NULL, NULL, '2025-10-29 06:27:37', '2025-10-29 06:27:37'),
(106, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:27:44', NULL, NULL, '2025-10-29 06:27:44', '2025-10-29 06:27:44'),
(107, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:29:25', NULL, NULL, '2025-10-29 06:29:25', '2025-10-29 06:29:25'),
(108, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:29:36', NULL, NULL, '2025-10-29 06:29:36', '2025-10-29 06:29:36'),
(109, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:29:43', NULL, NULL, '2025-10-29 06:29:43', '2025-10-29 06:29:43'),
(110, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:30:37', NULL, NULL, '2025-10-29 06:30:37', '2025-10-29 06:30:37'),
(111, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:31:37', NULL, NULL, '2025-10-29 06:31:37', '2025-10-29 06:31:37'),
(112, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:31:44', NULL, NULL, '2025-10-29 06:31:44', '2025-10-29 06:31:44'),
(113, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:38:24', NULL, NULL, '2025-10-29 06:38:24', '2025-10-29 06:38:24'),
(114, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:38:29', NULL, NULL, '2025-10-29 06:38:29', '2025-10-29 06:38:29'),
(115, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:39:28', NULL, NULL, '2025-10-29 06:39:28', '2025-10-29 06:39:28'),
(116, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-29 12:39:42', NULL, NULL, '2025-10-29 06:39:42', '2025-10-29 06:39:42'),
(117, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:39:48', NULL, NULL, '2025-10-29 06:39:49', '2025-10-29 06:39:49'),
(118, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:40:40', NULL, NULL, '2025-10-29 06:40:40', '2025-10-29 06:40:40'),
(119, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:40:44', NULL, NULL, '2025-10-29 06:40:44', '2025-10-29 06:40:44'),
(120, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:20:50', NULL, NULL, '2025-11-02 10:20:50', '2025-11-02 10:20:50'),
(121, '', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:40', NULL, NULL, '2025-11-02 10:22:40', '2025-11-02 10:22:40'),
(122, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:46', NULL, NULL, '2025-11-02 10:22:46', '2025-11-02 10:22:46'),
(123, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:49', NULL, NULL, '2025-11-02 10:22:49', '2025-11-02 10:22:49'),
(124, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:53', NULL, NULL, '2025-11-02 10:22:53', '2025-11-02 10:22:53'),
(125, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:31:53', NULL, NULL, '2025-11-02 10:31:53', '2025-11-02 10:31:53'),
(126, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:31:57', NULL, NULL, '2025-11-02 10:31:57', '2025-11-02 10:31:57'),
(127, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:32:47', NULL, NULL, '2025-11-02 10:32:47', '2025-11-02 10:32:47'),
(128, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:32:51', NULL, NULL, '2025-11-02 10:32:51', '2025-11-02 10:32:51'),
(129, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:42:33', NULL, NULL, '2025-11-03 09:42:33', '2025-11-03 09:42:33'),
(130, '', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:44:01', NULL, NULL, '2025-11-03 09:44:01', '2025-11-03 09:44:01'),
(131, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:44:07', NULL, NULL, '2025-11-03 09:44:07', '2025-11-03 09:44:07'),
(132, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:52:05', NULL, NULL, '2025-11-03 09:52:05', '2025-11-03 09:52:05'),
(133, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:52:09', NULL, NULL, '2025-11-03 09:52:09', '2025-11-03 09:52:09'),
(134, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:54:37', NULL, NULL, '2025-11-03 09:54:37', '2025-11-03 09:54:37'),
(135, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:54:41', NULL, NULL, '2025-11-03 09:54:41', '2025-11-03 09:54:41'),
(136, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:59:23', NULL, NULL, '2025-11-03 09:59:23', '2025-11-03 09:59:23'),
(137, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:59:28', NULL, NULL, '2025-11-03 09:59:28', '2025-11-03 09:59:28'),
(138, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 13:25:39', NULL, NULL, '2025-11-04 07:25:39', '2025-11-04 07:25:39'),
(139, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:05:18', NULL, NULL, '2025-11-04 08:05:18', '2025-11-04 08:05:18'),
(140, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:05:22', NULL, NULL, '2025-11-04 08:05:22', '2025-11-04 08:05:22'),
(141, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:14:32', NULL, NULL, '2025-11-04 08:14:32', '2025-11-04 08:14:32'),
(142, 'cufl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:14:38', NULL, NULL, '2025-11-04 08:14:38', '2025-11-04 08:14:38'),
(143, 'cufl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:16:09', NULL, NULL, '2025-11-04 08:16:09', '2025-11-04 08:16:09'),
(144, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:16:13', NULL, NULL, '2025-11-04 08:16:13', '2025-11-04 08:16:13'),
(145, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:19:29', NULL, NULL, '2025-11-04 09:19:29', '2025-11-04 09:19:29'),
(146, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:20:04', NULL, NULL, '2025-11-04 09:20:04', '2025-11-04 09:20:04'),
(147, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:29:03', NULL, NULL, '2025-11-04 09:29:03', '2025-11-04 09:29:03'),
(148, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:29:06', NULL, NULL, '2025-11-04 09:29:06', '2025-11-04 09:29:06'),
(149, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 16:37:34', NULL, NULL, '2025-11-04 10:37:34', '2025-11-04 10:37:34'),
(150, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 16:37:42', NULL, NULL, '2025-11-04 10:37:42', '2025-11-04 10:37:42'),
(151, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-05 12:13:29', NULL, NULL, '2025-11-05 06:13:29', '2025-11-05 06:13:29'),
(152, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-05 13:37:10', NULL, NULL, '2025-11-05 07:37:10', '2025-11-05 07:37:10'),
(153, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-05 14:09:18', NULL, NULL, '2025-11-05 08:09:18', '2025-11-05 08:09:18'),
(154, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-06 13:03:24', NULL, NULL, '2025-11-06 07:03:24', '2025-11-06 07:03:24'),
(155, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 10:52:53', NULL, NULL, '2025-11-09 04:52:53', '2025-11-09 04:52:53'),
(156, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 10:57:02', NULL, NULL, '2025-11-09 04:57:02', '2025-11-09 04:57:02'),
(157, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 10:57:09', NULL, NULL, '2025-11-09 04:57:09', '2025-11-09 04:57:09'),
(158, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:09:07', NULL, NULL, '2025-11-09 05:09:07', '2025-11-09 05:09:07'),
(159, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:09:10', NULL, NULL, '2025-11-09 05:09:10', '2025-11-09 05:09:10'),
(160, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:11:44', NULL, NULL, '2025-11-09 05:11:44', '2025-11-09 05:11:44'),
(161, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:11:49', NULL, NULL, '2025-11-09 05:11:49', '2025-11-09 05:11:49'),
(162, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:12', NULL, NULL, '2025-11-09 05:18:12', '2025-11-09 05:18:12'),
(163, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:16', NULL, NULL, '2025-11-09 05:18:16', '2025-11-09 05:18:16'),
(164, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:41', NULL, NULL, '2025-11-09 05:18:41', '2025-11-09 05:18:41'),
(165, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:45', NULL, NULL, '2025-11-09 05:18:45', '2025-11-09 05:18:45'),
(166, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:40:07', NULL, NULL, '2025-11-09 05:40:07', '2025-11-09 05:40:07'),
(167, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:40:14', NULL, NULL, '2025-11-09 05:40:15', '2025-11-09 05:40:15'),
(168, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:27', NULL, NULL, '2025-11-09 08:59:27', '2025-11-09 08:59:27'),
(169, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:31', NULL, NULL, '2025-11-09 08:59:31', '2025-11-09 08:59:31'),
(170, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:40', NULL, NULL, '2025-11-09 08:59:40', '2025-11-09 08:59:40'),
(171, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:49', NULL, NULL, '2025-11-09 08:59:49', '2025-11-09 08:59:49'),
(172, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:07:02', NULL, NULL, '2025-11-09 09:07:02', '2025-11-09 09:07:02'),
(173, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:07:06', NULL, NULL, '2025-11-09 09:07:06', '2025-11-09 09:07:06'),
(174, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:35:49', NULL, NULL, '2025-11-09 09:35:49', '2025-11-09 09:35:49'),
(175, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:35:55', NULL, NULL, '2025-11-09 09:35:55', '2025-11-09 09:35:55'),
(176, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:37:16', NULL, NULL, '2025-11-09 09:37:16', '2025-11-09 09:37:16'),
(177, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:37:20', NULL, NULL, '2025-11-09 09:37:20', '2025-11-09 09:37:20'),
(178, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:39:22', NULL, NULL, '2025-11-09 09:39:22', '2025-11-09 09:39:22'),
(179, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:39:27', NULL, NULL, '2025-11-09 09:39:27', '2025-11-09 09:39:27'),
(180, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:53:32', NULL, NULL, '2025-11-09 09:53:32', '2025-11-09 09:53:32'),
(181, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:53:40', NULL, NULL, '2025-11-09 09:53:40', '2025-11-09 09:53:40'),
(182, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:55:36', NULL, NULL, '2025-11-09 09:55:36', '2025-11-09 09:55:36'),
(183, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:55:40', NULL, NULL, '2025-11-09 09:55:40', '2025-11-09 09:55:40'),
(184, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:05', NULL, NULL, '2025-11-09 09:56:05', '2025-11-09 09:56:05'),
(185, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:08', NULL, NULL, '2025-11-09 09:56:08', '2025-11-09 09:56:08'),
(186, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:23', NULL, NULL, '2025-11-09 09:56:23', '2025-11-09 09:56:23'),
(187, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:26', NULL, NULL, '2025-11-09 09:56:26', '2025-11-09 09:56:26'),
(188, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:36:13', NULL, NULL, '2025-11-09 10:36:13', '2025-11-09 10:36:13'),
(189, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:36:19', NULL, NULL, '2025-11-09 10:36:19', '2025-11-09 10:36:19'),
(190, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:42:59', NULL, NULL, '2025-11-09 10:42:59', '2025-11-09 10:42:59'),
(191, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:43:03', NULL, NULL, '2025-11-09 10:43:03', '2025-11-09 10:43:03'),
(192, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:46:39', NULL, NULL, '2025-11-09 10:46:39', '2025-11-09 10:46:39'),
(193, 'cufl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:46:59', NULL, NULL, '2025-11-09 10:46:59', '2025-11-09 10:46:59'),
(194, 'cufl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:47:24', NULL, NULL, '2025-11-09 10:47:24', '2025-11-09 10:47:24'),
(195, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:47:27', NULL, NULL, '2025-11-09 10:47:27', '2025-11-09 10:47:27'),
(196, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:39:11', NULL, NULL, '2025-11-10 03:39:11', '2025-11-10 03:39:11'),
(197, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:39:53', NULL, NULL, '2025-11-10 03:39:53', '2025-11-10 03:39:53'),
(198, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:39:57', NULL, NULL, '2025-11-10 03:39:57', '2025-11-10 03:39:57'),
(199, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:50:32', NULL, NULL, '2025-11-10 03:50:32', '2025-11-10 03:50:32'),
(200, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:50:38', NULL, NULL, '2025-11-10 03:50:38', '2025-11-10 03:50:38'),
(201, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 10:07:23', NULL, NULL, '2025-11-10 04:07:23', '2025-11-10 04:07:23'),
(202, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 10:07:27', NULL, NULL, '2025-11-10 04:07:27', '2025-11-10 04:07:27'),
(203, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 10:59:02', NULL, NULL, '2025-11-10 04:59:02', '2025-11-10 04:59:02'),
(204, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:02:52', NULL, NULL, '2025-11-10 05:02:52', '2025-11-10 05:02:52'),
(205, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:03:02', NULL, NULL, '2025-11-10 05:03:02', '2025-11-10 05:03:02'),
(206, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:03:06', NULL, NULL, '2025-11-10 05:03:06', '2025-11-10 05:03:06'),
(207, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:09:33', NULL, NULL, '2025-11-10 05:09:33', '2025-11-10 05:09:33'),
(208, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:09:43', NULL, NULL, '2025-11-10 05:09:43', '2025-11-10 05:09:43'),
(209, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:47:18', NULL, NULL, '2025-11-10 05:47:18', '2025-11-10 05:47:18'),
(210, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:47:24', NULL, NULL, '2025-11-10 05:47:24', '2025-11-10 05:47:24');
INSERT INTO `log_table` (`id`, `username`, `event_type`, `ip_address`, `user_agent`, `status`, `login_time`, `logout_time`, `remarks`, `created_at`, `updated_at`) VALUES
(211, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:00:08', NULL, NULL, '2025-11-10 10:00:08', '2025-11-10 10:00:08'),
(212, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-11-10 16:00:33', NULL, NULL, '2025-11-10 10:00:33', '2025-11-10 10:00:33'),
(213, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:22:21', NULL, NULL, '2025-11-10 10:22:21', '2025-11-10 10:22:21'),
(214, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:22:27', NULL, NULL, '2025-11-10 10:22:27', '2025-11-10 10:22:27'),
(215, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:22:42', NULL, NULL, '2025-11-10 10:22:42', '2025-11-10 10:22:42'),
(216, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:26:43', NULL, NULL, '2025-11-10 10:26:43', '2025-11-10 10:26:43'),
(217, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:26:47', NULL, NULL, '2025-11-10 10:26:47', '2025-11-10 10:26:47'),
(218, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:22:01', NULL, NULL, '2025-11-11 09:22:01', '2025-11-11 09:22:01'),
(219, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:27:27', NULL, NULL, '2025-11-11 09:27:27', '2025-11-11 09:27:27'),
(220, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:27:31', NULL, NULL, '2025-11-11 09:27:31', '2025-11-11 09:27:31'),
(221, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:30:54', NULL, NULL, '2025-11-11 09:30:54', '2025-11-11 09:30:54'),
(222, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:30:57', NULL, NULL, '2025-11-11 09:30:57', '2025-11-11 09:30:57'),
(223, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:50:24', NULL, NULL, '2025-11-11 09:50:24', '2025-11-11 09:50:24'),
(224, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:50:28', NULL, NULL, '2025-11-11 09:50:28', '2025-11-11 09:50:28'),
(225, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-12 10:45:55', NULL, NULL, '2025-11-12 04:45:55', '2025-11-12 04:45:55'),
(226, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-12 10:49:43', NULL, NULL, '2025-11-12 04:49:43', '2025-11-12 04:49:43'),
(227, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-12 10:49:48', NULL, NULL, '2025-11-12 04:49:48', '2025-11-12 04:49:48'),
(228, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-13 16:28:37', NULL, NULL, '2025-11-13 10:28:37', '2025-11-13 10:28:37'),
(229, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-25 11:22:11', NULL, NULL, '2025-11-25 05:22:12', '2025-11-25 05:22:12'),
(230, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-25 11:22:17', NULL, NULL, '2025-11-25 05:22:17', '2025-11-25 05:22:17'),
(231, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:34:37', NULL, NULL, '2025-11-26 08:34:37', '2025-11-26 08:34:37'),
(232, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:48:31', NULL, NULL, '2025-11-26 08:48:31', '2025-11-26 08:48:31'),
(233, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:48:35', NULL, NULL, '2025-11-26 08:48:35', '2025-11-26 08:48:35'),
(234, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:49:12', NULL, NULL, '2025-11-26 08:49:12', '2025-11-26 08:49:12'),
(235, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:49:17', NULL, NULL, '2025-11-26 08:49:17', '2025-11-26 08:49:17'),
(236, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:53:13', NULL, NULL, '2025-11-26 08:53:13', '2025-11-26 08:53:13'),
(237, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:53:18', NULL, NULL, '2025-11-26 08:53:18', '2025-11-26 08:53:18'),
(238, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:44:35', NULL, NULL, '2025-11-26 09:44:35', '2025-11-26 09:44:35'),
(239, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:44:39', NULL, NULL, '2025-11-26 09:44:39', '2025-11-26 09:44:39'),
(240, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:45:18', NULL, NULL, '2025-11-26 09:45:18', '2025-11-26 09:45:18'),
(241, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:45:22', NULL, NULL, '2025-11-26 09:45:22', '2025-11-26 09:45:22'),
(242, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:46:12', NULL, NULL, '2025-11-26 09:46:12', '2025-11-26 09:46:12'),
(243, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:46:15', NULL, NULL, '2025-11-26 09:46:15', '2025-11-26 09:46:15'),
(244, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:46:58', NULL, NULL, '2025-11-26 09:46:58', '2025-11-26 09:46:58'),
(245, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:47:03', NULL, NULL, '2025-11-26 09:47:03', '2025-11-26 09:47:03'),
(246, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:20:19', NULL, NULL, '2025-11-26 10:20:19', '2025-11-26 10:20:19'),
(247, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:20:25', NULL, NULL, '2025-11-26 10:20:25', '2025-11-26 10:20:25'),
(248, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:35:07', NULL, NULL, '2025-11-26 10:35:07', '2025-11-26 10:35:07'),
(249, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:35:12', NULL, NULL, '2025-11-26 10:35:12', '2025-11-26 10:35:12'),
(250, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:36:33', NULL, NULL, '2025-11-26 10:36:33', '2025-11-26 10:36:33'),
(251, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:36:37', NULL, NULL, '2025-11-26 10:36:38', '2025-11-26 10:36:38'),
(252, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:36:47', NULL, NULL, '2025-11-26 10:36:47', '2025-11-26 10:36:47'),
(253, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:37:17', NULL, NULL, '2025-11-26 10:37:17', '2025-11-26 10:37:17'),
(254, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:37:55', NULL, NULL, '2025-11-26 10:37:55', '2025-11-26 10:37:55'),
(255, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:38:21', NULL, NULL, '2025-11-26 10:38:21', '2025-11-26 10:38:21'),
(256, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:38:54', NULL, NULL, '2025-11-26 10:38:54', '2025-11-26 10:38:54'),
(257, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:38:58', NULL, NULL, '2025-11-26 10:38:58', '2025-11-26 10:38:58'),
(258, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:45:59', NULL, NULL, '2025-11-27 08:45:59', '2025-11-27 08:45:59'),
(259, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:46:47', NULL, NULL, '2025-11-27 08:46:47', '2025-11-27 08:46:47'),
(260, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:46:51', NULL, NULL, '2025-11-27 08:46:51', '2025-11-27 08:46:51'),
(261, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:53:22', NULL, NULL, '2025-11-27 08:53:22', '2025-11-27 08:53:22'),
(262, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:53:25', NULL, NULL, '2025-11-27 08:53:25', '2025-11-27 08:53:25'),
(263, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:01:45', NULL, NULL, '2025-11-27 09:01:45', '2025-11-27 09:01:45'),
(264, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-11-27 15:01:48', NULL, NULL, '2025-11-27 09:01:48', '2025-11-27 09:01:48'),
(265, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:01:55', NULL, NULL, '2025-11-27 09:01:55', '2025-11-27 09:01:55'),
(266, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:01:57', NULL, NULL, '2025-11-27 09:01:57', '2025-11-27 09:01:57'),
(267, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:00', NULL, NULL, '2025-11-27 09:02:01', '2025-11-27 09:02:01'),
(268, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:08', NULL, NULL, '2025-11-27 09:02:08', '2025-11-27 09:02:08'),
(269, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:12', NULL, NULL, '2025-11-27 09:02:12', '2025-11-27 09:02:12'),
(270, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:13', NULL, NULL, '2025-11-27 09:02:13', '2025-11-27 09:02:13'),
(271, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:17', NULL, NULL, '2025-11-27 09:02:17', '2025-11-27 09:02:17'),
(272, 'admin', 'login', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:24:00', NULL, NULL, '2025-12-01 09:24:00', '2025-12-01 09:24:00'),
(273, 'admin', 'logout', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:29:22', NULL, NULL, '2025-12-01 09:29:22', '2025-12-01 09:29:22'),
(274, 'sfcl', 'login', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:29:35', NULL, NULL, '2025-12-01 09:29:35', '2025-12-01 09:29:35'),
(275, 'sfcl', 'logout', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:32:18', NULL, NULL, '2025-12-01 09:32:18', '2025-12-01 09:32:18'),
(276, 'admin', 'login', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:32:27', NULL, NULL, '2025-12-01 09:32:27', '2025-12-01 09:32:27'),
(277, 'admin', 'logout', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:37:47', NULL, NULL, '2025-12-01 09:37:47', '2025-12-01 09:37:47'),
(278, 'admin', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:27:20', NULL, NULL, '2025-12-02 05:27:20', '2025-12-02 05:27:20'),
(279, 'admin', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:30:32', NULL, NULL, '2025-12-02 05:30:32', '2025-12-02 05:30:32'),
(280, 'sfcl', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:30:39', NULL, NULL, '2025-12-02 05:30:39', '2025-12-02 05:30:39'),
(281, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:36:59', NULL, NULL, '2025-12-02 05:36:59', '2025-12-02 05:36:59'),
(282, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:03:59', NULL, NULL, '2025-12-02 06:03:59', '2025-12-02 06:03:59'),
(283, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:04:03', NULL, NULL, '2025-12-02 06:04:04', '2025-12-02 06:04:04'),
(284, 'sfcl', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:04:05', NULL, NULL, '2025-12-02 06:04:05', '2025-12-02 06:04:05'),
(285, 'admin', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:04:11', NULL, NULL, '2025-12-02 06:04:11', '2025-12-02 06:04:11'),
(286, 'admin', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:30', NULL, NULL, '2025-12-02 06:05:30', '2025-12-02 06:05:30'),
(287, 'sfcl', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:43', NULL, NULL, '2025-12-02 06:05:43', '2025-12-02 06:05:43'),
(288, 'sfcl', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:49', NULL, NULL, '2025-12-02 06:05:49', '2025-12-02 06:05:49'),
(289, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:56', NULL, NULL, '2025-12-02 06:05:56', '2025-12-02 06:05:56'),
(290, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:06:00', NULL, NULL, '2025-12-02 06:06:00', '2025-12-02 06:06:00'),
(291, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:55:06', NULL, NULL, '2025-12-02 06:55:06', '2025-12-02 06:55:06'),
(292, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:55:16', NULL, NULL, '2025-12-02 06:55:16', '2025-12-02 06:55:16'),
(293, 'admin', 'login', '192.168.3.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 09:25:52', NULL, NULL, '2025-12-03 03:25:52', '2025-12-03 03:25:52'),
(294, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:04:06', NULL, NULL, '2025-12-03 04:04:06', '2025-12-03 04:04:06'),
(295, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:09:12', NULL, NULL, '2025-12-03 04:09:12', '2025-12-03 04:09:12'),
(296, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:09:17', NULL, NULL, '2025-12-03 04:09:17', '2025-12-03 04:09:17'),
(297, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:14', NULL, NULL, '2025-12-03 04:10:14', '2025-12-03 04:10:14'),
(298, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:26', NULL, NULL, '2025-12-03 04:10:26', '2025-12-03 04:10:26'),
(299, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:40', NULL, NULL, '2025-12-03 04:10:40', '2025-12-03 04:10:40'),
(300, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:59', NULL, NULL, '2025-12-03 04:10:59', '2025-12-03 04:10:59'),
(301, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:53:13', NULL, NULL, '2025-12-03 04:53:13', '2025-12-03 04:53:13'),
(302, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:53:18', NULL, NULL, '2025-12-03 04:53:18', '2025-12-03 04:53:18'),
(303, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:55:40', NULL, NULL, '2025-12-03 04:55:40', '2025-12-03 04:55:40'),
(304, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:55:48', NULL, NULL, '2025-12-03 04:55:48', '2025-12-03 04:55:48'),
(305, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:33:23', NULL, NULL, '2025-12-03 05:33:23', '2025-12-03 05:33:23'),
(306, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:33:30', NULL, NULL, '2025-12-03 05:33:31', '2025-12-03 05:33:31'),
(307, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:38:37', NULL, NULL, '2025-12-03 05:38:37', '2025-12-03 05:38:37'),
(308, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:38:42', NULL, NULL, '2025-12-03 05:38:42', '2025-12-03 05:38:42'),
(309, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:41:36', NULL, NULL, '2025-12-03 05:41:36', '2025-12-03 05:41:36'),
(310, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:41:56', NULL, NULL, '2025-12-03 05:41:56', '2025-12-03 05:41:56'),
(311, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:21:05', NULL, NULL, '2025-12-03 06:21:05', '2025-12-03 06:21:05'),
(312, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:21:11', NULL, NULL, '2025-12-03 06:21:11', '2025-12-03 06:21:11'),
(313, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:54:28', NULL, NULL, '2025-12-03 06:54:28', '2025-12-03 06:54:28'),
(314, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:54:32', NULL, NULL, '2025-12-03 06:54:32', '2025-12-03 06:54:32'),
(315, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 13:10:44', NULL, NULL, '2025-12-03 07:10:44', '2025-12-03 07:10:44'),
(316, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 13:10:51', NULL, NULL, '2025-12-03 07:10:51', '2025-12-03 07:10:51'),
(317, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 10:11:10', NULL, NULL, '2025-12-04 04:11:11', '2025-12-04 04:11:11'),
(318, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 12:24:32', NULL, NULL, '2025-12-04 06:24:32', '2025-12-04 06:24:32'),
(319, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 12:24:38', NULL, NULL, '2025-12-04 06:24:38', '2025-12-04 06:24:38'),
(320, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 15:12:03', NULL, NULL, '2025-12-04 09:12:03', '2025-12-04 09:12:03'),
(321, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 15:34:36', NULL, NULL, '2025-12-04 09:34:36', '2025-12-04 09:34:36'),
(322, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 15:34:38', NULL, NULL, '2025-12-04 09:34:38', '2025-12-04 09:34:38'),
(323, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 15:40:50', NULL, NULL, '2025-12-04 09:40:50', '2025-12-04 09:40:50'),
(324, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 15:40:53', NULL, NULL, '2025-12-04 09:40:53', '2025-12-04 09:40:53'),
(325, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 15:40:57', NULL, NULL, '2025-12-04 09:40:57', '2025-12-04 09:40:57'),
(326, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 15:41:01', NULL, NULL, '2025-12-04 09:41:01', '2025-12-04 09:41:01'),
(327, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 16:38:58', NULL, NULL, '2025-12-04 10:38:58', '2025-12-04 10:38:58'),
(328, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 16:41:36', NULL, NULL, '2025-12-04 10:41:36', '2025-12-04 10:41:36'),
(329, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 16:41:42', NULL, NULL, '2025-12-04 10:41:42', '2025-12-04 10:41:42'),
(330, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 16:43:16', NULL, NULL, '2025-12-04 10:43:16', '2025-12-04 10:43:16'),
(331, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 09:10:48', NULL, NULL, '2025-12-07 03:10:49', '2025-12-07 03:10:49'),
(332, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 09:45:42', NULL, NULL, '2025-12-07 03:45:42', '2025-12-07 03:45:42'),
(333, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 09:47:15', NULL, NULL, '2025-12-07 03:47:15', '2025-12-07 03:47:15'),
(334, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 10:51:07', NULL, NULL, '2025-12-07 04:51:07', '2025-12-07 04:51:07'),
(335, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 10:51:11', NULL, NULL, '2025-12-07 04:51:12', '2025-12-07 04:51:12'),
(336, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 11:16:13', NULL, NULL, '2025-12-07 05:16:13', '2025-12-07 05:16:13'),
(337, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 11:16:17', NULL, NULL, '2025-12-07 05:16:17', '2025-12-07 05:16:17'),
(338, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 11:21:03', NULL, NULL, '2025-12-07 05:21:03', '2025-12-07 05:21:03'),
(339, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-12-07 11:21:08', NULL, NULL, '2025-12-07 05:21:08', '2025-12-07 05:21:08'),
(340, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-12-07 11:21:14', NULL, NULL, '2025-12-07 05:21:14', '2025-12-07 05:21:14'),
(341, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 11:21:38', NULL, NULL, '2025-12-07 05:21:38', '2025-12-07 05:21:38'),
(342, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:09:16', NULL, NULL, '2025-12-07 09:09:16', '2025-12-07 09:09:16'),
(343, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:09:21', NULL, NULL, '2025-12-07 09:09:21', '2025-12-07 09:09:21'),
(344, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:09:34', NULL, NULL, '2025-12-07 09:09:34', '2025-12-07 09:09:34'),
(345, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:09:56', NULL, NULL, '2025-12-07 09:09:56', '2025-12-07 09:09:56'),
(346, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:18:07', NULL, NULL, '2025-12-07 09:18:07', '2025-12-07 09:18:07'),
(347, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:18:11', NULL, NULL, '2025-12-07 09:18:11', '2025-12-07 09:18:11'),
(348, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:21:43', NULL, NULL, '2025-12-07 09:21:43', '2025-12-07 09:21:43'),
(349, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:21:47', NULL, NULL, '2025-12-07 09:21:47', '2025-12-07 09:21:47'),
(350, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:35:56', NULL, NULL, '2025-12-07 09:35:56', '2025-12-07 09:35:56'),
(351, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-12-07 15:36:01', NULL, NULL, '2025-12-07 09:36:01', '2025-12-07 09:36:01'),
(352, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:36:07', NULL, NULL, '2025-12-07 09:36:07', '2025-12-07 09:36:07'),
(353, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:38:45', NULL, NULL, '2025-12-07 09:38:45', '2025-12-07 09:38:45'),
(354, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-07 15:38:49', NULL, NULL, '2025-12-07 09:38:49', '2025-12-07 09:38:49'),
(355, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-08 12:03:23', NULL, NULL, '2025-12-08 06:03:23', '2025-12-08 06:03:23'),
(356, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-08 12:07:38', NULL, NULL, '2025-12-08 06:07:38', '2025-12-08 06:07:38'),
(357, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-08 12:07:44', NULL, NULL, '2025-12-08 06:07:44', '2025-12-08 06:07:44'),
(358, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-08 12:52:14', NULL, NULL, '2025-12-08 06:52:14', '2025-12-08 06:52:14'),
(359, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-08 12:52:20', NULL, NULL, '2025-12-08 06:52:20', '2025-12-08 06:52:20'),
(360, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 11:02:18', NULL, NULL, '2025-12-09 05:02:18', '2025-12-09 05:02:18'),
(361, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 12:17:34', NULL, NULL, '2025-12-09 06:17:34', '2025-12-09 06:17:34'),
(362, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 12:17:40', NULL, NULL, '2025-12-09 06:17:40', '2025-12-09 06:17:40'),
(363, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 12:24:09', NULL, NULL, '2025-12-09 06:24:09', '2025-12-09 06:24:09'),
(364, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 12:24:13', NULL, NULL, '2025-12-09 06:24:13', '2025-12-09 06:24:13'),
(365, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 13:07:05', NULL, NULL, '2025-12-09 07:07:05', '2025-12-09 07:07:05'),
(366, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 15:02:10', NULL, NULL, '2025-12-09 09:02:10', '2025-12-09 09:02:10'),
(367, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 15:02:15', NULL, NULL, '2025-12-09 09:02:15', '2025-12-09 09:02:15'),
(368, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:04:01', NULL, NULL, '2025-12-09 10:04:01', '2025-12-09 10:04:01'),
(369, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:04:09', NULL, NULL, '2025-12-09 10:04:09', '2025-12-09 10:04:09'),
(370, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:04:18', NULL, NULL, '2025-12-09 10:04:18', '2025-12-09 10:04:18'),
(371, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:04:23', NULL, NULL, '2025-12-09 10:04:23', '2025-12-09 10:04:23'),
(372, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:45:51', NULL, NULL, '2025-12-09 10:45:51', '2025-12-09 10:45:51'),
(373, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-12-09 16:45:56', NULL, NULL, '2025-12-09 10:45:56', '2025-12-09 10:45:56'),
(374, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-12-09 16:46:03', NULL, NULL, '2025-12-09 10:46:03', '2025-12-09 10:46:03'),
(375, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:46:08', NULL, NULL, '2025-12-09 10:46:08', '2025-12-09 10:46:08'),
(376, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:46:43', NULL, NULL, '2025-12-09 10:46:43', '2025-12-09 10:46:43'),
(377, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:46:48', NULL, NULL, '2025-12-09 10:46:48', '2025-12-09 10:46:48'),
(378, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-09 16:46:58', NULL, NULL, '2025-12-09 10:46:58', '2025-12-09 10:46:58'),
(379, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-12-10 09:38:38', NULL, NULL, '2025-12-10 03:38:38', '2025-12-10 03:38:38'),
(380, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 09:38:50', NULL, NULL, '2025-12-10 03:38:50', '2025-12-10 03:38:50'),
(381, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 12:51:29', NULL, NULL, '2025-12-10 06:51:29', '2025-12-10 06:51:29'),
(382, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 12:51:34', NULL, NULL, '2025-12-10 06:51:34', '2025-12-10 06:51:34'),
(383, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 13:04:31', NULL, NULL, '2025-12-10 07:04:31', '2025-12-10 07:04:31'),
(384, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 13:04:36', NULL, NULL, '2025-12-10 07:04:36', '2025-12-10 07:04:36'),
(385, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 13:08:17', NULL, NULL, '2025-12-10 07:08:17', '2025-12-10 07:08:17'),
(386, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 13:08:23', NULL, NULL, '2025-12-10 07:08:23', '2025-12-10 07:08:23'),
(387, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 13:16:41', NULL, NULL, '2025-12-10 07:16:41', '2025-12-10 07:16:41'),
(388, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-10 13:16:52', NULL, NULL, '2025-12-10 07:16:52', '2025-12-10 07:16:52'),
(389, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-14 10:03:32', NULL, NULL, '2025-12-14 04:03:32', '2025-12-14 04:03:32'),
(390, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-14 10:04:33', NULL, NULL, '2025-12-14 04:04:33', '2025-12-14 04:04:33'),
(391, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-14 10:04:49', NULL, NULL, '2025-12-14 04:04:49', '2025-12-14 04:04:49'),
(392, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', '2026-01-01 12:31:38', NULL, NULL, '2026-01-01 06:31:39', '2026-01-01 06:31:39'),
(393, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', '2026-01-04 14:37:27', NULL, NULL, '2026-01-04 08:37:27', '2026-01-04 08:37:27'),
(394, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', '2026-01-11 09:41:14', NULL, NULL, '2026-01-11 03:41:14', '2026-01-11 03:41:14'),
(395, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', '2026-01-11 09:41:20', NULL, NULL, '2026-01-11 03:41:20', '2026-01-11 03:41:20'),
(396, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', '2026-01-15 11:53:16', NULL, NULL, '2026-01-15 05:53:16', '2026-01-15 05:53:16'),
(397, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', '2026-01-15 11:56:54', NULL, NULL, '2026-01-15 05:56:54', '2026-01-15 05:56:54'),
(398, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', '2026-01-15 11:57:02', NULL, NULL, '2026-01-15 05:57:02', '2026-01-15 05:57:02'),
(399, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-02 15:54:04', NULL, NULL, '2026-04-02 09:54:04', '2026-04-02 09:54:04'),
(400, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 09:55:50', NULL, NULL, '2026-04-05 03:55:50', '2026-04-05 03:55:50'),
(401, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:11:55', NULL, NULL, '2026-04-05 08:11:55', '2026-04-05 08:11:55'),
(402, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:11:59', NULL, NULL, '2026-04-05 08:11:59', '2026-04-05 08:11:59'),
(403, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:12:05', NULL, NULL, '2026-04-05 08:12:05', '2026-04-05 08:12:05'),
(404, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:12:10', NULL, NULL, '2026-04-05 08:12:10', '2026-04-05 08:12:10'),
(405, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:44:19', NULL, NULL, '2026-04-05 08:44:19', '2026-04-05 08:44:19'),
(406, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:44:22', NULL, NULL, '2026-04-05 08:44:22', '2026-04-05 08:44:22'),
(407, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:53:29', NULL, NULL, '2026-04-05 08:53:29', '2026-04-05 08:53:29'),
(408, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:53:33', NULL, NULL, '2026-04-05 08:53:33', '2026-04-05 08:53:33'),
(409, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:56:39', NULL, NULL, '2026-04-05 08:56:39', '2026-04-05 08:56:39'),
(410, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:56:44', NULL, NULL, '2026-04-05 08:56:44', '2026-04-05 08:56:44'),
(411, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:57:22', NULL, NULL, '2026-04-05 08:57:22', '2026-04-05 08:57:22'),
(412, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-05 14:57:27', NULL, NULL, '2026-04-05 08:57:27', '2026-04-05 08:57:27'),
(413, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 09:19:20', NULL, NULL, '2026-04-06 03:19:20', '2026-04-06 03:19:20'),
(414, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:34:38', NULL, NULL, '2026-04-06 04:34:38', '2026-04-06 04:34:38'),
(415, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:34:45', NULL, NULL, '2026-04-06 04:34:45', '2026-04-06 04:34:45'),
(416, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:35:59', NULL, NULL, '2026-04-06 04:35:59', '2026-04-06 04:35:59'),
(417, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:36:15', NULL, NULL, '2026-04-06 04:36:15', '2026-04-06 04:36:15'),
(418, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:40:35', NULL, NULL, '2026-04-06 04:40:35', '2026-04-06 04:40:35'),
(419, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:40:45', NULL, NULL, '2026-04-06 04:40:45', '2026-04-06 04:40:45');
INSERT INTO `log_table` (`id`, `username`, `event_type`, `ip_address`, `user_agent`, `status`, `login_time`, `logout_time`, `remarks`, `created_at`, `updated_at`) VALUES
(420, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:41:08', NULL, NULL, '2026-04-06 04:41:08', '2026-04-06 04:41:08'),
(421, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:41:12', NULL, NULL, '2026-04-06 04:41:12', '2026-04-06 04:41:12'),
(422, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:48:39', NULL, NULL, '2026-04-06 04:48:39', '2026-04-06 04:48:39'),
(423, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 10:48:43', NULL, NULL, '2026-04-06 04:48:43', '2026-04-06 04:48:43'),
(424, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:04:34', NULL, NULL, '2026-04-06 05:04:34', '2026-04-06 05:04:34'),
(425, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:04:38', NULL, NULL, '2026-04-06 05:04:38', '2026-04-06 05:04:38'),
(426, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:06:22', NULL, NULL, '2026-04-06 05:06:22', '2026-04-06 05:06:22'),
(427, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:06:26', NULL, NULL, '2026-04-06 05:06:26', '2026-04-06 05:06:26'),
(428, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:06:34', NULL, NULL, '2026-04-06 05:06:34', '2026-04-06 05:06:34'),
(429, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:06:38', NULL, NULL, '2026-04-06 05:06:38', '2026-04-06 05:06:38'),
(430, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:14:43', NULL, NULL, '2026-04-06 05:14:43', '2026-04-06 05:14:43'),
(431, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 11:14:47', NULL, NULL, '2026-04-06 05:14:47', '2026-04-06 05:14:47'),
(432, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 15:21:08', NULL, NULL, '2026-04-06 09:21:08', '2026-04-06 09:21:08'),
(433, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-06 15:21:12', NULL, NULL, '2026-04-06 09:21:12', '2026-04-06 09:21:12'),
(434, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-07 11:25:18', NULL, NULL, '2026-04-07 05:25:18', '2026-04-07 05:25:18'),
(435, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-07 11:38:07', NULL, NULL, '2026-04-07 05:38:07', '2026-04-07 05:38:07'),
(436, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-07 11:38:32', NULL, NULL, '2026-04-07 05:38:32', '2026-04-07 05:38:32'),
(437, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-07 12:01:51', NULL, NULL, '2026-04-07 06:01:51', '2026-04-07 06:01:51'),
(438, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-07 12:01:54', NULL, NULL, '2026-04-07 06:01:54', '2026-04-07 06:01:54'),
(439, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', '2026-04-08 12:29:29', NULL, NULL, '2026-04-08 06:29:29', '2026-04-08 06:29:29'),
(440, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-12 13:53:28', NULL, NULL, '2026-04-12 07:53:28', '2026-04-12 07:53:28'),
(441, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-13 10:32:01', NULL, NULL, '2026-04-13 04:32:01', '2026-04-13 04:32:01'),
(442, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-13 10:32:19', NULL, NULL, '2026-04-13 04:32:19', '2026-04-13 04:32:19'),
(443, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-13 10:32:26', NULL, NULL, '2026-04-13 04:32:26', '2026-04-13 04:32:26'),
(444, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-15 12:04:42', NULL, NULL, '2026-04-15 06:04:42', '2026-04-15 06:04:42'),
(445, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-04-15 12:16:34', NULL, NULL, '2026-04-15 06:16:34', '2026-04-15 06:16:34'),
(446, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-15 12:17:14', NULL, NULL, '2026-04-15 06:17:14', '2026-04-15 06:17:14'),
(447, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 09:37:31', NULL, NULL, '2026-04-16 03:37:31', '2026-04-16 03:37:31'),
(448, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:29:36', NULL, NULL, '2026-04-16 09:29:36', '2026-04-16 09:29:36'),
(449, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:29:42', NULL, NULL, '2026-04-16 09:29:42', '2026-04-16 09:29:42'),
(450, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:30:12', NULL, NULL, '2026-04-16 09:30:12', '2026-04-16 09:30:12'),
(451, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:30:16', NULL, NULL, '2026-04-16 09:30:16', '2026-04-16 09:30:16'),
(452, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:43:15', NULL, NULL, '2026-04-16 09:43:15', '2026-04-16 09:43:15'),
(453, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:43:24', NULL, NULL, '2026-04-16 09:43:24', '2026-04-16 09:43:24'),
(454, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:43:46', NULL, NULL, '2026-04-16 09:43:46', '2026-04-16 09:43:46'),
(455, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:43:49', NULL, NULL, '2026-04-16 09:43:49', '2026-04-16 09:43:49'),
(456, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:44:14', NULL, NULL, '2026-04-16 09:44:14', '2026-04-16 09:44:14'),
(457, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:44:18', NULL, NULL, '2026-04-16 09:44:18', '2026-04-16 09:44:18'),
(458, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:44:36', NULL, NULL, '2026-04-16 09:44:36', '2026-04-16 09:44:36'),
(459, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:44:40', NULL, NULL, '2026-04-16 09:44:40', '2026-04-16 09:44:40'),
(460, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:44:48', NULL, NULL, '2026-04-16 09:44:48', '2026-04-16 09:44:48'),
(461, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:44:56', NULL, NULL, '2026-04-16 09:44:56', '2026-04-16 09:44:56'),
(462, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:45:08', NULL, NULL, '2026-04-16 09:45:08', '2026-04-16 09:45:08'),
(463, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:45:14', NULL, NULL, '2026-04-16 09:45:14', '2026-04-16 09:45:14'),
(464, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:46:10', NULL, NULL, '2026-04-16 09:46:10', '2026-04-16 09:46:10'),
(465, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-16 15:46:15', NULL, NULL, '2026-04-16 09:46:15', '2026-04-16 09:46:15'),
(466, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 09:25:18', NULL, NULL, '2026-04-19 03:25:19', '2026-04-19 03:25:19'),
(467, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:14:51', NULL, NULL, '2026-04-19 05:14:51', '2026-04-19 05:14:51'),
(468, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:15:00', NULL, NULL, '2026-04-19 05:15:00', '2026-04-19 05:15:00'),
(469, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:16:06', NULL, NULL, '2026-04-19 05:16:06', '2026-04-19 05:16:06'),
(470, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:16:11', NULL, NULL, '2026-04-19 05:16:11', '2026-04-19 05:16:11'),
(471, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:16:52', NULL, NULL, '2026-04-19 05:16:52', '2026-04-19 05:16:52'),
(472, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:16:56', NULL, NULL, '2026-04-19 05:16:56', '2026-04-19 05:16:56'),
(473, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:18:38', NULL, NULL, '2026-04-19 05:18:38', '2026-04-19 05:18:38'),
(474, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-04-19 11:18:45', NULL, NULL, '2026-04-19 05:18:45', '2026-04-19 05:18:45'),
(475, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:18:52', NULL, NULL, '2026-04-19 05:18:52', '2026-04-19 05:18:52'),
(476, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:20:39', NULL, NULL, '2026-04-19 05:20:39', '2026-04-19 05:20:39'),
(477, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:20:43', NULL, NULL, '2026-04-19 05:20:44', '2026-04-19 05:20:44'),
(478, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:22:04', NULL, NULL, '2026-04-19 05:22:04', '2026-04-19 05:22:04'),
(479, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:22:11', NULL, NULL, '2026-04-19 05:22:11', '2026-04-19 05:22:11'),
(480, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:35:24', NULL, NULL, '2026-04-19 05:35:24', '2026-04-19 05:35:24'),
(481, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:35:28', NULL, NULL, '2026-04-19 05:35:28', '2026-04-19 05:35:28'),
(482, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:35:29', NULL, NULL, '2026-04-19 05:35:29', '2026-04-19 05:35:29'),
(483, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:35:33', NULL, NULL, '2026-04-19 05:35:33', '2026-04-19 05:35:33'),
(484, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:37:42', NULL, NULL, '2026-04-19 05:37:42', '2026-04-19 05:37:42'),
(485, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:37:46', NULL, NULL, '2026-04-19 05:37:46', '2026-04-19 05:37:46'),
(486, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:38:00', NULL, NULL, '2026-04-19 05:38:00', '2026-04-19 05:38:00'),
(487, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 11:38:04', NULL, NULL, '2026-04-19 05:38:04', '2026-04-19 05:38:04'),
(488, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 12:51:36', NULL, NULL, '2026-04-19 06:51:36', '2026-04-19 06:51:36'),
(489, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 12:51:41', NULL, NULL, '2026-04-19 06:51:41', '2026-04-19 06:51:41'),
(490, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 13:06:44', NULL, NULL, '2026-04-19 07:06:44', '2026-04-19 07:06:44'),
(491, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 13:06:50', NULL, NULL, '2026-04-19 07:06:50', '2026-04-19 07:06:50'),
(492, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 13:18:28', NULL, NULL, '2026-04-19 07:18:28', '2026-04-19 07:18:28'),
(493, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-19 13:18:34', NULL, NULL, '2026-04-19 07:18:34', '2026-04-19 07:18:34'),
(494, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 09:42:56', NULL, NULL, '2026-04-20 03:42:56', '2026-04-20 03:42:56'),
(495, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 09:46:28', NULL, NULL, '2026-04-20 03:46:28', '2026-04-20 03:46:28'),
(496, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 09:54:24', NULL, NULL, '2026-04-20 03:54:24', '2026-04-20 03:54:24'),
(497, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 09:54:27', NULL, NULL, '2026-04-20 03:54:27', '2026-04-20 03:54:27'),
(498, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 09:54:34', NULL, NULL, '2026-04-20 03:54:34', '2026-04-20 03:54:34'),
(499, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:02:22', NULL, NULL, '2026-04-20 04:02:22', '2026-04-20 04:02:22'),
(500, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:02:26', NULL, NULL, '2026-04-20 04:02:26', '2026-04-20 04:02:26'),
(501, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:03:23', NULL, NULL, '2026-04-20 04:03:23', '2026-04-20 04:03:23'),
(502, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:03:27', NULL, NULL, '2026-04-20 04:03:27', '2026-04-20 04:03:27'),
(503, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:03:36', NULL, NULL, '2026-04-20 04:03:36', '2026-04-20 04:03:36'),
(504, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:03:39', NULL, NULL, '2026-04-20 04:03:39', '2026-04-20 04:03:39'),
(505, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:06:50', NULL, NULL, '2026-04-20 04:06:50', '2026-04-20 04:06:50'),
(506, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:06:53', NULL, NULL, '2026-04-20 04:06:53', '2026-04-20 04:06:53'),
(507, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:09:07', NULL, NULL, '2026-04-20 04:09:07', '2026-04-20 04:09:07'),
(508, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:09:11', NULL, NULL, '2026-04-20 04:09:11', '2026-04-20 04:09:11'),
(509, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:25:29', NULL, NULL, '2026-04-20 04:25:29', '2026-04-20 04:25:29'),
(510, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:25:35', NULL, NULL, '2026-04-20 04:25:36', '2026-04-20 04:25:36'),
(511, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:27:22', NULL, NULL, '2026-04-20 04:27:22', '2026-04-20 04:27:22'),
(512, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:27:26', NULL, NULL, '2026-04-20 04:27:26', '2026-04-20 04:27:26'),
(513, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:28:35', NULL, NULL, '2026-04-20 04:28:35', '2026-04-20 04:28:35'),
(514, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:28:39', NULL, NULL, '2026-04-20 04:28:39', '2026-04-20 04:28:39'),
(515, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:29:06', NULL, NULL, '2026-04-20 04:29:06', '2026-04-20 04:29:06'),
(516, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:29:10', NULL, NULL, '2026-04-20 04:29:10', '2026-04-20 04:29:10'),
(517, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:44:24', NULL, NULL, '2026-04-20 04:44:24', '2026-04-20 04:44:24'),
(518, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:44:28', NULL, NULL, '2026-04-20 04:44:28', '2026-04-20 04:44:28'),
(519, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:44:48', NULL, NULL, '2026-04-20 04:44:48', '2026-04-20 04:44:48'),
(520, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:44:52', NULL, NULL, '2026-04-20 04:44:52', '2026-04-20 04:44:52'),
(521, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:53:30', NULL, NULL, '2026-04-20 04:53:30', '2026-04-20 04:53:30'),
(522, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:53:33', NULL, NULL, '2026-04-20 04:53:33', '2026-04-20 04:53:33'),
(523, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:54:02', NULL, NULL, '2026-04-20 04:54:02', '2026-04-20 04:54:02'),
(524, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:54:05', NULL, NULL, '2026-04-20 04:54:05', '2026-04-20 04:54:05'),
(525, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:54:31', NULL, NULL, '2026-04-20 04:54:31', '2026-04-20 04:54:31'),
(526, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:54:35', NULL, NULL, '2026-04-20 04:54:35', '2026-04-20 04:54:35'),
(527, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:55:12', NULL, NULL, '2026-04-20 04:55:12', '2026-04-20 04:55:12'),
(528, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 10:55:15', NULL, NULL, '2026-04-20 04:55:15', '2026-04-20 04:55:15'),
(529, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:02:19', NULL, NULL, '2026-04-20 05:02:19', '2026-04-20 05:02:19'),
(530, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:02:25', NULL, NULL, '2026-04-20 05:02:25', '2026-04-20 05:02:25'),
(531, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:03:33', NULL, NULL, '2026-04-20 05:03:33', '2026-04-20 05:03:33'),
(532, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:03:37', NULL, NULL, '2026-04-20 05:03:37', '2026-04-20 05:03:37'),
(533, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:04:39', NULL, NULL, '2026-04-20 05:04:39', '2026-04-20 05:04:39'),
(534, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:04:43', NULL, NULL, '2026-04-20 05:04:43', '2026-04-20 05:04:43'),
(535, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:05:10', NULL, NULL, '2026-04-20 05:05:10', '2026-04-20 05:05:10'),
(536, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:05:13', NULL, NULL, '2026-04-20 05:05:13', '2026-04-20 05:05:13'),
(537, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:12:01', NULL, NULL, '2026-04-20 05:12:01', '2026-04-20 05:12:01'),
(538, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:12:04', NULL, NULL, '2026-04-20 05:12:04', '2026-04-20 05:12:04'),
(539, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:12:21', NULL, NULL, '2026-04-20 05:12:21', '2026-04-20 05:12:21'),
(540, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:12:24', NULL, NULL, '2026-04-20 05:12:24', '2026-04-20 05:12:24'),
(541, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:19:25', NULL, NULL, '2026-04-20 05:19:25', '2026-04-20 05:19:25'),
(542, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:19:28', NULL, NULL, '2026-04-20 05:19:28', '2026-04-20 05:19:28'),
(543, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:20:02', NULL, NULL, '2026-04-20 05:20:02', '2026-04-20 05:20:02'),
(544, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:20:06', NULL, NULL, '2026-04-20 05:20:06', '2026-04-20 05:20:06'),
(545, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:22:18', NULL, NULL, '2026-04-20 05:22:18', '2026-04-20 05:22:18'),
(546, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:22:22', NULL, NULL, '2026-04-20 05:22:22', '2026-04-20 05:22:22'),
(547, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:22:54', NULL, NULL, '2026-04-20 05:22:54', '2026-04-20 05:22:54'),
(548, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:22:57', NULL, NULL, '2026-04-20 05:22:58', '2026-04-20 05:22:58'),
(549, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:31:15', NULL, NULL, '2026-04-20 05:31:15', '2026-04-20 05:31:15'),
(550, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:33:11', NULL, NULL, '2026-04-20 05:33:11', '2026-04-20 05:33:11'),
(551, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:33:57', NULL, NULL, '2026-04-20 05:33:57', '2026-04-20 05:33:57'),
(552, 'afccl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:34:01', NULL, NULL, '2026-04-20 05:34:01', '2026-04-20 05:34:01'),
(553, 'afccl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:34:42', NULL, NULL, '2026-04-20 05:34:42', '2026-04-20 05:34:42'),
(554, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:34:46', NULL, NULL, '2026-04-20 05:34:46', '2026-04-20 05:34:46'),
(555, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:52:58', NULL, NULL, '2026-04-20 05:52:58', '2026-04-20 05:52:58'),
(556, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-04-20 11:53:06', NULL, NULL, '2026-04-20 05:53:06', '2026-04-20 05:53:06'),
(557, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-04-20 11:53:32', NULL, NULL, '2026-04-20 05:53:32', '2026-04-20 05:53:32'),
(558, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:53:44', NULL, NULL, '2026-04-20 05:53:44', '2026-04-20 05:53:44'),
(559, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:56:43', NULL, NULL, '2026-04-20 05:56:43', '2026-04-20 05:56:43'),
(560, 'bcicho', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-20 11:56:47', NULL, NULL, '2026-04-20 05:56:47', '2026-04-20 05:56:47'),
(561, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-21 09:20:19', NULL, NULL, '2026-04-21 03:20:19', '2026-04-21 03:20:19'),
(562, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-21 11:52:40', NULL, NULL, '2026-04-21 05:52:40', '2026-04-21 05:52:40'),
(563, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-21 12:48:24', NULL, NULL, '2026-04-21 06:48:24', '2026-04-21 06:48:24'),
(564, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-21 13:19:23', NULL, NULL, '2026-04-21 07:19:23', '2026-04-21 07:19:23'),
(565, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-04-21 13:19:27', NULL, NULL, '2026-04-21 07:19:27', '2026-04-21 07:19:27'),
(566, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-21 13:19:32', NULL, NULL, '2026-04-21 07:19:32', '2026-04-21 07:19:32'),
(567, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-04-21 13:49:35', NULL, NULL, '2026-04-21 07:49:35', '2026-04-21 07:49:35'),
(568, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-04-21 13:49:40', NULL, NULL, '2026-04-21 07:49:40', '2026-04-21 07:49:40');

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
(1, 'afccl', '2026-03-11', '', '10,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2026-04-20 05:34:21', '2026-04-20 11:34:21'),
(2, 'afccl', '2026-04-20', '', '10,0,0,0,0,0,0,0,0,0,0,0,0,0', '12,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '2,0,0,0,0,0,0,0,0,0,0,0,0,0', '2,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2026-04-20 05:34:37', '2026-04-20 11:34:37');

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
(1, 'afccl', '2026-03-12', '', 'Grade 15', '10', '1', '2', '3', 'active', '2026-04-20 05:22:35', '2026-04-20 05:22:35'),
(2, 'afccl', '2026-04-20', ',', 'Grade 17,Grade 19', '50,10', '1,2', '22,5', '23,7', 'active', '2026-04-20 05:22:50', '2026-04-20 05:22:50');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `factory_name1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `factory_name`, `designation`, `email`, `phone`, `role`, `created_at`, `updated_at`, `factory_name1`) VALUES
(1, 'sfcl', '$2y$10$qfxm.dHbgqvVpFyANyLjgui0IX/47onIOW6icpv.ob2b5cQDg6rzK', 'SFCL..', 'শাহজালাল ফার্টিলাইজার কোম্পানী লিমিটেড (এসএফসিএল)', 'Assistant Manager (Admin)', 'sfcl@yahoo.com', '2222', 'user', '2025-10-14 08:00:19', '2025-12-10 07:05:46', 'শাহজালাল ফার্টিলাইজার কোম্পানী লিমিটেড (এসএফসিএল)'),
(2, 'jfcl', '$2y$10$JJlrLH/y.Lh5elcETMBNf.i/zmkZL718QER/yNfKK3kWUbtH9VNyO', 'JFCL', 'যমুনা ফার্টিলাইজার ফ্যাক্টরী লিমিটেড(জেএফসিএল)', '', '', '', 'user', '2025-10-26 03:45:21', '2025-12-10 06:27:29', 'যমুনা ফার্টিলাইজার ফ্যাক্টরী লিমিটেড(জেএফসিএল)'),
(3, 'admin', '$2y$10$TdA8pCKlQmdP56Jm2CD.fuEVpKskNFAgsjyzFluSoTwR8u5rc3nry', 'admin', 'admin', NULL, NULL, NULL, 'admin', '2025-10-26 04:29:52', '2025-12-10 06:27:29', 'admin'),
(4, 'cufl', '$2y$10$dn6pkZzdsCFuZmZkPDYed.HetnHL8vy93uQN49Vtr.ui5D8gsvNqm', 'cufl', 'চিটাগাং ইউরিয়া ফার্টিলাইজার লিমিটেড (সিইউএফএল)', '', '', '', 'user', '2025-11-04 08:14:17', '2025-12-10 06:27:29', 'চিটাগাং ইউরিয়া ফার্টিলাইজার লিমিটেড (সিইউএফএল)'),
(5, 'sadmin', '$2y$10$PyuzEfstKkGaUagnDRhLzOmwIjaRvRc85UPfr3ZJ.BrF7A78djTqG', 'sadmin', NULL, NULL, NULL, NULL, 'sadmin', '2025-11-10 10:22:07', '2025-12-07 09:35:54', ''),
(6, 'bcicho', '$2y$10$opSfyvo3x4hfjA8TQrURTO7nybeED.KHEfCI0rMF3prOTpU/weX/a', 'BCIC Head Office', 'Mohammand Sohel', 'Deputy Manager (Admin)', '', '', 'admin', '2025-12-07 05:23:05', '2026-04-20 05:56:24', 'বিসিআইসি প্রধান কার্যালয়'),
(7, 'bcicclg', '$2y$10$vMLOB3iFvUfFKD5C83INp.nn8Mib9fvc8R6Ef37TPHBebsiA5WzW.', 'BCIC College', 'বিসিআইসি কলেজ', '', '', '', 'user', '2025-12-07 05:32:58', '2025-12-10 06:27:29', 'বিসিআইসি কলেজ'),
(8, 'gpfplc', '$2y$10$PBtQJdTTwpTOyER.YVKDBOHJRXIcy10CE1.wTxQzVGf/W2Np/C2Q6', 'GPFPCL', 'ঘোড়াশাল পলাশ ফার্টিলাইজার পাবলিক লিমিটেড কোম্পানী (জিপিএফপিএলসি)', '', '', '', 'user', '2025-12-07 09:05:04', '2025-12-10 06:27:29', 'ঘোড়াশাল পলাশ ফার্টিলাইজার পাবলিক লিমিটেড কোম্পানী (জিপিএফপিএলসি)'),
(9, 'afccl', '$2y$10$flFnKR2Wo/3ZOlbrGTe0wu.q2wjIy2B2zj1aOwNGYoLpUnVDvJb3C', 'AFCCL', 'আশুগঞ্জ ফার্টিলাইজার এন্ড কেমিক্যাল কোম্পানী লিমিটেড(এএফসিসিএল)', '', '', '', 'user', '2025-12-07 09:23:56', '2025-12-10 06:27:29', 'আশুগঞ্জ ফার্টিলাইজার এন্ড কেমিক্যাল কোম্পানী লিমিটেড(এএফসিসিএল)'),
(10, 'dapfcl', '$2y$10$KNFhL9BTyTMwuRGZgwL4fe9yVpNTPECWq1NHj3JyFuko6A52H5CWS', 'DAPFCL', 'ডিএপি ফার্টিলাইজার কোম্পানী লিমিটেড (ডিএপিএফসিএল)', '', '', '', 'user', '2025-12-07 09:24:24', '2025-12-10 06:27:29', 'ডিএপি ফার্টিলাইজার কোম্পানী লিমিটেড (ডিএপিএফসিএল)'),
(11, 'tspcl', '$2y$10$gMi8/v1fxdZKz.vy7f6mdet.XLxfugvt9UNiltH.8cwZzXGY5P64m', 'TSPCL', 'টিএসপি কমপ্লেক্স লিমিটেড (টিএসপিসিএল)', '', '', '', 'user', '2025-12-07 09:24:57', '2025-12-10 06:27:29', 'টিএসপি কমপ্লেক্স লিমিটেড (টিএসপিসিএল)'),
(12, 'tici', '$2y$10$R518fyZ6uc6pEIxkcJkxOuhS.wI3gKKJeMp6TPjMSJlhE7EKmOTmi', 'TICI', 'ট্রেনিং ইন্সটিটিউট ফর কেমিক্যাল ইন্ডাস্ট্রিজ (টিআইসিআই)', '', '', '', 'user', '2025-12-07 09:25:21', '2025-12-10 06:27:29', 'ট্রেনিং ইন্সটিটিউট ফর কেমিক্যাল ইন্ডাস্ট্রিজ (টিআইসিআই)'),
(13, 'cccl', '$2y$10$OjiIRSntNzGOOxx1IIc1j.ryGqTc4fldRcCD0fx0x3l.BYjYfL/n6', 'CCCL', 'ছাতক সিমেন্ট কোম্পানী লিমিটেড (সিসিসিএল)', '', '', '', 'user', '2025-12-07 09:25:47', '2025-12-10 06:27:29', 'ছাতক সিমেন্ট কোম্পানী লিমিটেড (সিসিসিএল)'),
(14, 'kpml', '$2y$10$39M6tEZQuFhGTD70emI8KO8v6XgUpA2XWRRKjiUBLjUmxSNZ6FheG', 'KPML', 'কর্ণফুলী পেপার মিলস লিমিটেড (কেপিএমএল)', '', '', '', 'user', '2025-12-07 09:26:14', '2025-12-10 06:27:29', 'কর্ণফুলী পেপার মিলস লিমিটেড (কেপিএমএল)'),
(15, 'bisf', '$2y$10$YINytG2A3xgCIferFLr0JOyoJsVCHf.twuzdDQjMFDKxLvwDStnD.', 'BISFL', 'বাংলাদেশ ইনসুলেটর এন্ড স্যানিটারীওয়্যার ফ্যাক্টরী লিমিটেড (বিআইএসএফ)', '', '', '', 'user', '2025-12-07 09:26:45', '2025-12-10 06:27:29', 'বাংলাদেশ ইনসুলেটর এন্ড স্যানিটারীওয়্যার ফ্যাক্টরী লিমিটেড (বিআইএসএফ)'),
(16, 'ugsfl', '$2y$10$byoH2K4EFLBskhEhUf4C6.tJgLRCuIq0u1sASHjUbUOh8hgErE4p.', 'UGSFL', 'উসমানিয়া গ্লাস শীট ফ্যাক্টরী লিমিটেড (ইউজিএসএফএল)', '', '', '', 'user', '2025-12-07 09:27:18', '2025-12-10 06:27:29', 'উসমানিয়া গ্লাস শীট ফ্যাক্টরী লিমিটেড (ইউজিএসএফএল)'),
(17, 'dlcl', '$2y$10$VoafqI/N.QNHSGj1wbSY1uCl13uU.IUgh1ydafeK5/73S6ho4WDzO', 'DLCL', 'ঢাকা লেদার কোম্পানি লিমিটেড (ডিএলসিএল)', '', '', '', 'user', '2025-12-07 09:28:25', '2025-12-10 06:27:29', 'ঢাকা লেদার কোম্পানি লিমিটেড (ডিএলসিএল)'),
(18, 'ccc', '$2y$10$6rdOyr/hphWWswLYnbOgkeOYl1N8AqxzXl/493GfwmoKxNqZKUN4O', 'CCC', 'চিটাগাং কেমিক্যাল কমপ্লেক্স (সিসিসি)', '', '', '', 'user', '2025-12-07 09:28:53', '2025-12-10 06:27:29', 'চিটাগাং কেমিক্যাল কমপ্লেক্স (সিসিসি)'),
(19, 'khbm', '$2y$10$q0UpyK0hOtbInPDRQSTAkul5tCJLuJaPnnHanF2hvCuK9NTNgRkSO', 'KHBM', 'খুলনা হার্ড বোর্ড মিলস লিমিটেড (কেএইচবিএমএল)', '', '', '', 'user', '2025-12-07 09:29:34', '2025-12-10 06:27:29', 'খুলনা হার্ড বোর্ড মিলস লিমিটেড (কেএইচবিএমএল)'),
(20, 'knm', '$2y$10$IsRzcYyjHLIghzEgsoUyL.JPU0bNKHdiUMOyw28OKTdVmSbmkqv1W', 'KNM', 'খুলনা নিউজপ্রিন্ট মিলস (কেএনএম)', '', '', '', 'user', '2025-12-07 09:29:51', '2025-12-10 06:27:29', 'খুলনা নিউজপ্রিন্ট মিলস (কেএনএম)'),
(21, 'krc', '$2y$10$YsusbNG1R3z4vaouScV5We9qjz4i9bA40ZE2/NJuwLqhSfJazNGT.', 'KRC', 'KRC', '', '', '', 'user', '2025-12-07 09:30:35', '2025-12-10 06:27:29', 'KRC'),
(22, 'nbpm', '$2y$10$nAA7Hg9adAI8Q9vA1Ce1ruaX22V8qH8KnigcYviP71RiHte.admhq', 'NBPM', 'নর্থ বেঙ্গল পেপার মিলস (এনবিপিএম)', '', '', '', 'user', '2025-12-07 09:31:04', '2025-12-10 06:27:29', 'নর্থ বেঙ্গল পেপার মিলস (এনবিপিএম)'),
(23, 'gpufp', '$2y$10$IpdUdEdrP6GZTS4oy0KBR.OzEmYUbXB.NG8D7tJG24/A3h84cOAu2', 'GPUFP', 'ঘোড়াশাল পলাশ ইউরিয়া ফার্টিলাইজার প্রকল্প (জিপিইউএফপি)', '', '', '', 'user', '2025-12-07 09:31:48', '2025-12-10 06:27:29', 'ঘোড়াশাল পলাশ ইউরিয়া ফার্টিলাইজার প্রকল্প (জিপিইউএফপি)'),
(24, '34buffer', '$2y$10$tTwjhYlPiT2cVPQpRyq4wecYrJcQs8H7gF1/m7MfXWhS6c0qV99H2', '34 Buffer', '৩৪ বাফার গুদাম নির্মাণ প্রকল্প ', '', '', '', 'user', '2025-12-07 09:32:13', '2025-12-10 06:27:29', '৩৪ বাফার গুদাম নির্মাণ প্রকল্প '),
(25, '13buffer', '$2y$10$5ok8MIO/Ly5nAfpM0EQwZOCZA3x7LgrLJIPT4wQTnlsDoLqjPb4XK', '13 Buffer', '১৩ বাফার গুদাম নির্মাণ প্রকল্প ', '', '', '', 'user', '2025-12-07 09:32:34', '2025-12-10 06:27:29', '১৩ বাফার গুদাম নির্মাণ প্রকল্প '),
(26, 'broffice', '$2y$10$7uSqFyIZdhitPidmFyettes8w1tAfbIj0o4IuXjdwyN8VWEpD7DD6', 'Branch Office', 'বিসিআইসি শাখা অফিস, চট্টগ্রাম', '', '', '', 'user', '2025-12-07 09:33:19', '2025-12-10 06:27:29', 'বিসিআইসি শাখা অফিস, চট্টগ্রাম');

-- --------------------------------------------------------

--
-- Table structure for table `vacant_statistics_tbl`
--

CREATE TABLE `vacant_statistics_tbl` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(255) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `granted_post` text DEFAULT NULL,
  `in_service` text DEFAULT NULL,
  `eligible_promotion` text DEFAULT NULL,
  `direct_recruit` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vacant_statistics_tbl`
--

INSERT INTO `vacant_statistics_tbl` (`id`, `factory_name`, `entry_date`, `granted_post`, `in_service`, `eligible_promotion`, `direct_recruit`, `created_at`, `updated_at`) VALUES
(1, 'afccl', '2026-04-19', '10,20,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '5,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '4,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '2026-04-19 05:49:09', '2026-04-19 05:49:57');

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
(1, 'afccl', '2026-03-12', '', 'Grade 12', '10', '5', '5', '10', 'active', '2026-04-20 05:19:41', '2026-04-20 05:19:41'),
(2, 'afccl', '2026-04-20', ',,', 'Grade 9,Grade 15,Grade 12', '40,10,15', '40,2,8', '0,5,3', '40,7,11', 'active', '2026-04-20 05:19:59', '2026-04-20 07:44:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ansar_tbl`
--
ALTER TABLE `ansar_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_factory_date` (`factory_name`,`date`);

--
-- Indexes for table `daily_basis_tbl`
--
ALTER TABLE `daily_basis_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_factory_date` (`factory_name`,`date`);

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
-- Indexes for table `staffs_tbl`
--
ALTER TABLE `staffs_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_factory_date` (`factory_name`,`date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vacant_statistics_tbl`
--
ALTER TABLE `vacant_statistics_tbl`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daily_basis_tbl`
--
ALTER TABLE `daily_basis_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=569;

--
-- AUTO_INCREMENT for table `officers_tbl`
--
ALTER TABLE `officers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staffs_tbl`
--
ALTER TABLE `staffs_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `vacant_statistics_tbl`
--
ALTER TABLE `vacant_statistics_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workers_tbl`
--
ALTER TABLE `workers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
