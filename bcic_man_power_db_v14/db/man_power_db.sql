-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 05:25 AM
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
(1, 'sfcl', '2025-11-09', ',', ',', '10,10', '1,1', '1,1', '2,2', 'active', '2025-11-09 07:21:45', '2025-11-09 07:22:00'),
(2, 'jfcl', '2025-11-09', ',', ',', '10,10', '1,1', '1,1', '2,2', 'active', '2025-11-09 09:06:49', '2025-11-09 09:06:49');

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
(1, 'sfcl', '2025-11-05', ',', ',', '10,10', '1,1', '1,1', '2,2', 'active', '2025-11-09 05:28:54', '2025-11-26 08:48:19'),
(2, 'jfcl', '2025-11-06', 'AO,', ',', '10,10', '1,1', '1,1', '2,2', 'active', '2025-11-09 09:06:29', '2025-11-12 04:52:53');

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
(1, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-05-05 10:16:16', NULL, NULL, '2026-05-05 04:16:16', '2026-05-05 04:16:16'),
(2, 'sfcl', 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'failed', '2026-05-05 10:16:31', NULL, NULL, '2026-05-05 04:16:31', '2026-05-05 04:16:31'),
(3, 'sfcl', 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:16:49', NULL, NULL, '2026-05-05 04:16:49', '2026-05-05 04:16:49'),
(4, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-05-05 10:18:54', NULL, NULL, '2026-05-05 04:18:54', '2026-05-05 04:18:54'),
(5, 'sfcl', 'logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:27:06', NULL, NULL, '2026-05-05 04:27:06', '2026-05-05 04:27:06'),
(6, 'jfcl', 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:32:56', NULL, NULL, '2026-05-05 04:32:56', '2026-05-05 04:32:56'),
(7, 'jfcl', 'logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:33:56', NULL, NULL, '2026-05-05 04:33:56', '2026-05-05 04:33:56'),
(8, 'sfcl', 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'failed', '2026-05-05 10:39:57', NULL, NULL, '2026-05-05 04:39:57', '2026-05-05 04:39:57'),
(9, 'sfcl', 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:40:06', NULL, NULL, '2026-05-05 04:40:06', '2026-05-05 04:40:06'),
(10, 'sfcl', 'logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:43:32', NULL, NULL, '2026-05-05 04:43:32', '2026-05-05 04:43:32'),
(11, 'sfcl', 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:43:36', NULL, NULL, '2026-05-05 04:43:36', '2026-05-05 04:43:36'),
(12, 'sfcl', 'logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'success', '2026-05-05 10:55:08', NULL, NULL, '2026-05-05 04:55:08', '2026-05-05 04:55:08'),
(13, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-05-05 10:55:15', NULL, NULL, '2026-05-05 04:55:15', '2026-05-05 04:55:15'),
(14, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-05-05 10:58:31', NULL, NULL, '2026-05-05 04:58:31', '2026-05-05 04:58:31'),
(15, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-05-05 10:58:35', NULL, NULL, '2026-05-05 04:58:35', '2026-05-05 04:58:35'),
(16, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-05-05 11:13:03', NULL, NULL, '2026-05-05 05:13:03', '2026-05-05 05:13:03'),
(17, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', '2026-05-05 11:13:10', NULL, NULL, '2026-05-05 05:13:10', '2026-05-05 05:13:10'),
(18, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-05-05 11:13:14', NULL, NULL, '2026-05-05 05:13:14', '2026-05-05 05:13:14'),
(19, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', '2026-05-05 15:20:20', NULL, NULL, '2026-05-05 09:20:20', '2026-05-05 09:20:20');

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
(4, 'sfcl', '2025-12-02', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,150,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', 'active', '2025-12-02 06:05:15', '2025-12-09 12:18:49'),
(6, 'sfcl', '2025-11-02', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', 'active', '2025-12-02 06:05:15', '2025-12-09 11:09:54'),
(7, 'jfcl', '2025-11-02', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', 'active', '2025-12-02 06:05:15', '2025-12-09 11:10:08'),
(8, 'cufl', '2025-11-02', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', 'active', '2025-12-02 06:05:15', '2025-12-09 11:10:15'),
(9, 'cufl', '2025-12-09', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', 'active', '2025-12-02 06:05:15', '2025-12-09 11:10:15'),
(10, 'sfcl', '2026-01-07', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,150,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '100,100,100,100,100,100,100,100,100,100,100,100,100,100', '', 'active', '2025-12-10 07:13:29', '2025-12-10 13:13:29'),
(11, 'sfcl', '2026-04-06', '', '10,0,0,0,0,0,0,0,0,0,0,0,0,0', '5,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '2,0,0,0,0,0,0,0,0,0,0,0,0,0', '14,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '10,0,0,0,0,0,0,0,0,0,0,0,0,0', '5,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2026-04-06 07:38:01', '2026-04-07 11:54:58');

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
(1, 'sfcl', '2025-11-09', 'RS,MLSS', 'Grade 11,Grade 12', '10,10', '1,1', '1,1', '2,2', 'active', '2025-11-09 05:00:30', '2025-11-09 05:00:30'),
(2, 'jfcl', '2025-11-09', ',', 'Grade 11,Grade 12', '20,20', '2,2', '2,2', '4,4', 'active', '2025-11-09 09:03:10', '2025-11-09 09:47:52'),
(3, 'sfcl', '2026-03-04', 'RS', 'Grade 11', '10', '2', '5', '7', 'active', '2026-04-06 06:42:52', '2026-04-06 07:36:30');

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
  `status` int(11) NOT NULL DEFAULT 1,
  `login_status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `factory_name1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `factory_name`, `designation`, `email`, `phone`, `role`, `status`, `login_status`, `created_at`, `updated_at`, `factory_name1`) VALUES
(1, 'sfcl', '$2y$10$auvXNIL1E4QG4uDg6TfL8OsbPbJ6W2VvK4ufEf2c75M0vNnXtoBk6', 'SFCL', 'শাহজালাল ফার্টিলাইজার কোম্পানী লিমিটেড (এসএফসিএল)', 'Assistant Manager (Admin)', NULL, NULL, 'user', 1, 0, '2025-10-14 08:00:19', '2026-05-05 04:55:08', 'শাহজালাল ফার্টিলাইজার কোম্পানী লিমিটেড (এসএফসিএল)'),
(2, 'jfcl', '$2y$10$JJlrLH/y.Lh5elcETMBNf.i/zmkZL718QER/yNfKK3kWUbtH9VNyO', 'JFCL', 'যমুনা ফার্টিলাইজার ফ্যাক্টরী লিমিটেড(জেএফসিএল)', '', '', '', 'user', 1, 0, '2025-10-26 03:45:21', '2026-05-05 04:33:56', 'যমুনা ফার্টিলাইজার ফ্যাক্টরী লিমিটেড(জেএফসিএল)'),
(3, 'admin', '$2y$10$TdA8pCKlQmdP56Jm2CD.fuEVpKskNFAgsjyzFluSoTwR8u5rc3nry', 'admin', 'admin', NULL, NULL, NULL, 'admin', 1, 0, '2025-10-26 04:29:52', '2026-05-05 04:16:16', 'admin'),
(4, 'cufl', '$2y$10$dn6pkZzdsCFuZmZkPDYed.HetnHL8vy93uQN49Vtr.ui5D8gsvNqm', 'cufl', 'চিটাগাং ইউরিয়া ফার্টিলাইজার লিমিটেড (সিইউএফএল)', '', '', '', 'user', 1, 0, '2025-11-04 08:14:17', '2026-05-05 03:59:27', 'চিটাগাং ইউরিয়া ফার্টিলাইজার লিমিটেড (সিইউএফএল)'),
(5, 'sadmin', '$2y$10$PyuzEfstKkGaUagnDRhLzOmwIjaRvRc85UPfr3ZJ.BrF7A78djTqG', 'sadmin', NULL, NULL, NULL, NULL, 'sadmin', 1, 0, '2025-11-10 10:22:07', '2026-05-05 09:20:20', ''),
(6, 'bcicho', '$2y$10$opSfyvo3x4hfjA8TQrURTO7nybeED.KHEfCI0rMF3prOTpU/weX/a', 'BCIC H.O', 'বিসিআইসি প্রধান কার্যালয়', '', NULL, NULL, 'user', 1, 0, '2025-12-07 05:23:05', '2026-05-05 04:41:53', 'বিসিআইসি প্রধান কার্যালয়'),
(7, 'bcicclg', '$2y$10$vMLOB3iFvUfFKD5C83INp.nn8Mib9fvc8R6Ef37TPHBebsiA5WzW.', 'BCIC College', 'বিসিআইসি কলেজ', '', '', '', 'user', 1, 0, '2025-12-07 05:32:58', '2026-05-05 03:59:27', 'বিসিআইসি কলেজ'),
(8, 'gpfplc', '$2y$10$PBtQJdTTwpTOyER.YVKDBOHJRXIcy10CE1.wTxQzVGf/W2Np/C2Q6', 'GPFPCL', 'ঘোড়াশাল পলাশ ফার্টিলাইজার পাবলিক লিমিটেড কোম্পানী (জিপিএফপিএলসি)', '', '', '', 'user', 1, 0, '2025-12-07 09:05:04', '2026-05-05 03:59:27', 'ঘোড়াশাল পলাশ ফার্টিলাইজার পাবলিক লিমিটেড কোম্পানী (জিপিএফপিএলসি)'),
(9, 'afccl', '$2y$10$flFnKR2Wo/3ZOlbrGTe0wu.q2wjIy2B2zj1aOwNGYoLpUnVDvJb3C', 'AFCCL', 'আশুগঞ্জ ফার্টিলাইজার এন্ড কেমিক্যাল কোম্পানী লিমিটেড(এএফসিসিএল)', '', '', '', 'user', 1, 0, '2025-12-07 09:23:56', '2026-05-05 03:59:27', 'আশুগঞ্জ ফার্টিলাইজার এন্ড কেমিক্যাল কোম্পানী লিমিটেড(এএফসিসিএল)'),
(10, 'dapfcl', '$2y$10$KNFhL9BTyTMwuRGZgwL4fe9yVpNTPECWq1NHj3JyFuko6A52H5CWS', 'DAPFCL', 'ডিএপি ফার্টিলাইজার কোম্পানী লিমিটেড (ডিএপিএফসিএল)', 'e', NULL, NULL, 'user', 1, 0, '2025-12-07 09:24:24', '2026-05-05 04:22:05', 'ডিএপি ফার্টিলাইজার কোম্পানী লিমিটেড (ডিএপিএফসিএল)'),
(11, 'tspcl', '$2y$10$gMi8/v1fxdZKz.vy7f6mdet.XLxfugvt9UNiltH.8cwZzXGY5P64m', 'TSPCL', 'টিএসপি কমপ্লেক্স লিমিটেড (টিএসপিসিএল)', '', '', '', 'user', 1, 0, '2025-12-07 09:24:57', '2026-05-05 03:59:27', 'টিএসপি কমপ্লেক্স লিমিটেড (টিএসপিসিএল)'),
(12, 'tici', '$2y$10$R518fyZ6uc6pEIxkcJkxOuhS.wI3gKKJeMp6TPjMSJlhE7EKmOTmi', 'TICI', 'ট্রেনিং ইন্সটিটিউট ফর কেমিক্যাল ইন্ডাস্ট্রিজ (টিআইসিআই)', '', '', '', 'user', 1, 0, '2025-12-07 09:25:21', '2026-05-05 03:59:27', 'ট্রেনিং ইন্সটিটিউট ফর কেমিক্যাল ইন্ডাস্ট্রিজ (টিআইসিআই)'),
(13, 'cccl', '$2y$10$OjiIRSntNzGOOxx1IIc1j.ryGqTc4fldRcCD0fx0x3l.BYjYfL/n6', 'CCCL', 'ছাতক সিমেন্ট কোম্পানী লিমিটেড (সিসিসিএল)', '', '', '', 'user', 1, 0, '2025-12-07 09:25:47', '2026-05-05 03:59:27', 'ছাতক সিমেন্ট কোম্পানী লিমিটেড (সিসিসিএল)'),
(14, 'kpml', '$2y$10$39M6tEZQuFhGTD70emI8KO8v6XgUpA2XWRRKjiUBLjUmxSNZ6FheG', 'KPML', 'কর্ণফুলী পেপার মিলস লিমিটেড (কেপিএমএল)', '', '', '', 'user', 1, 0, '2025-12-07 09:26:14', '2026-05-05 03:59:27', 'কর্ণফুলী পেপার মিলস লিমিটেড (কেপিএমএল)'),
(15, 'bisfl', '$2y$10$YINytG2A3xgCIferFLr0JOyoJsVCHf.twuzdDQjMFDKxLvwDStnD.', 'BISFL', 'বাংলাদেশ ইনসুলেটর এন্ড স্যানিটারীওয়্যার ফ্যাক্টরী লিমিটেড (বিআইএসএফ)', '', NULL, NULL, 'user', 1, 0, '2025-12-07 09:26:45', '2026-05-05 03:59:27', 'বাংলাদেশ ইনসুলেটর এন্ড স্যানিটারীওয়্যার ফ্যাক্টরী লিমিটেড (বিআইএসএফ)'),
(16, 'ugsfl', '$2y$10$byoH2K4EFLBskhEhUf4C6.tJgLRCuIq0u1sASHjUbUOh8hgErE4p.', 'UGSFL', 'উসমানিয়া গ্লাস শীট ফ্যাক্টরী লিমিটেড (ইউজিএসএফএল)', '', '', '', 'user', 1, 0, '2025-12-07 09:27:18', '2026-05-05 03:59:27', 'উসমানিয়া গ্লাস শীট ফ্যাক্টরী লিমিটেড (ইউজিএসএফএল)'),
(17, 'dlcl', '$2y$10$VoafqI/N.QNHSGj1wbSY1uCl13uU.IUgh1ydafeK5/73S6ho4WDzO', 'DLCL', 'ঢাকা লেদার কোম্পানি লিমিটেড (ডিএলসিএল)', '', '', '', 'user', 1, 0, '2025-12-07 09:28:25', '2026-05-05 03:59:27', 'ঢাকা লেদার কোম্পানি লিমিটেড (ডিএলসিএল)'),
(18, 'ccc', '$2y$10$6rdOyr/hphWWswLYnbOgkeOYl1N8AqxzXl/493GfwmoKxNqZKUN4O', 'CCC', 'চিটাগাং কেমিক্যাল কমপ্লেক্স (সিসিসি)', '', '', '', 'user', 1, 0, '2025-12-07 09:28:53', '2026-05-05 03:59:27', 'চিটাগাং কেমিক্যাল কমপ্লেক্স (সিসিসি)'),
(19, 'khbm', '$2y$10$q0UpyK0hOtbInPDRQSTAkul5tCJLuJaPnnHanF2hvCuK9NTNgRkSO', 'KHBM', 'খুলনা হার্ড বোর্ড মিলস লিমিটেড (কেএইচবিএমএল)', '', '', '', 'user', 1, 0, '2025-12-07 09:29:34', '2026-05-05 03:59:27', 'খুলনা হার্ড বোর্ড মিলস লিমিটেড (কেএইচবিএমএল)'),
(20, 'knm', '$2y$10$IsRzcYyjHLIghzEgsoUyL.JPU0bNKHdiUMOyw28OKTdVmSbmkqv1W', 'KNM', 'খুলনা নিউজপ্রিন্ট মিলস (কেএনএম)', '', '', '', 'user', 1, 0, '2025-12-07 09:29:51', '2026-05-05 03:59:27', 'খুলনা নিউজপ্রিন্ট মিলস (কেএনএম)'),
(21, 'krc', '$2y$10$YsusbNG1R3z4vaouScV5We9qjz4i9bA40ZE2/NJuwLqhSfJazNGT.', 'KRC', 'KRC', '', '', '', 'user', 1, 0, '2025-12-07 09:30:35', '2026-05-05 03:59:27', 'KRC'),
(22, 'nbpm', '$2y$10$nAA7Hg9adAI8Q9vA1Ce1ruaX22V8qH8KnigcYviP71RiHte.admhq', 'NBPM', 'নর্থ বেঙ্গল পেপার মিলস (এনবিপিএম)', '', '', '', 'user', 1, 0, '2025-12-07 09:31:04', '2026-05-05 03:59:27', 'নর্থ বেঙ্গল পেপার মিলস (এনবিপিএম)'),
(23, 'gpufp', '$2y$10$IpdUdEdrP6GZTS4oy0KBR.OzEmYUbXB.NG8D7tJG24/A3h84cOAu2', 'GPUFP', 'ঘোড়াশাল পলাশ ইউরিয়া ফার্টিলাইজার প্রকল্প (জিপিইউএফপি)', '', '', '', 'user', 1, 0, '2025-12-07 09:31:48', '2026-05-05 03:59:27', 'ঘোড়াশাল পলাশ ইউরিয়া ফার্টিলাইজার প্রকল্প (জিপিইউএফপি)'),
(24, '34buffer', '$2y$10$tTwjhYlPiT2cVPQpRyq4wecYrJcQs8H7gF1/m7MfXWhS6c0qV99H2', '34 Buffer', '৩৪ বাফার গুদাম নির্মাণ প্রকল্প ', '', '', '', 'user', 1, 0, '2025-12-07 09:32:13', '2026-05-05 03:59:27', '৩৪ বাফার গুদাম নির্মাণ প্রকল্প '),
(25, '13buffer', '$2y$10$5ok8MIO/Ly5nAfpM0EQwZOCZA3x7LgrLJIPT4wQTnlsDoLqjPb4XK', '13 Buffer', '১৩ বাফার গুদাম নির্মাণ প্রকল্প ', '', '', '', 'user', 1, 0, '2025-12-07 09:32:34', '2026-05-05 03:59:27', '১৩ বাফার গুদাম নির্মাণ প্রকল্প '),
(26, 'broffice', '$2y$10$7uSqFyIZdhitPidmFyettes8w1tAfbIj0o4IuXjdwyN8VWEpD7DD6', 'Branch Office', 'বিসিআইসি শাখা অফিস, চট্টগ্রাম', '', '', '', 'user', 1, 0, '2025-12-07 09:33:19', '2026-05-05 03:59:27', 'বিসিআইসি শাখা অফিস, চট্টগ্রাম');

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
(1, 'sfcl', '2026-02-04', '10,20,10,20,10,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,10', '6,5,8,10,5,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2', '4,15,1,5,2,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5', '0,0,1,5,3,6,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3', '2026-04-05 04:56:42', '2026-04-15 08:41:28'),
(2, 'sfcl', '2026-03-03', '10,20,10,20,10,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,10', '4,5,8,10,5,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2', '2,15,1,5,2,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5', '4,0,1,5,3,6,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3', '2026-04-05 06:27:45', '2026-04-05 07:33:05'),
(3, 'sfcl', '2026-04-05', '10,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,10', '5,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5', '1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1', '4,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4', '2026-04-05 08:02:23', '2026-04-06 09:20:59'),
(4, 'jfcl', '2026-03-04', '10,20,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '5,10,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,5,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '4,5,4,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '2026-04-05 08:57:13', '2026-04-06 04:48:29'),
(5, 'jfcl', '2026-04-06', '10,20,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '2,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '5,15,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '2026-04-06 04:35:36', '2026-04-06 04:35:36'),
(6, 'afccl', '2026-04-06', '10,20,20,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '2,5,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '5,15,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '2,0,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '2026-04-06 04:39:30', '2026-04-06 04:45:21');

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
(1, 'sfcl', '2025-11-09', 'SST,HSO', 'Grade 1,Grade 1', '10,10', '1,1', '1,1', '2,2', 'active', '2025-11-09 05:20:25', '2025-11-09 05:20:25'),
(2, 'jfcl', '2025-11-09', 'SST,', 'Grade 1,Grade 4', '10,10', '1,2', '1,2', '2,4', 'active', '2025-11-09 09:05:57', '2025-11-09 09:49:16');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `daily_basis_tbl`
--
ALTER TABLE `daily_basis_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `officers_tbl`
--
ALTER TABLE `officers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `staffs_tbl`
--
ALTER TABLE `staffs_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `vacant_statistics_tbl`
--
ALTER TABLE `vacant_statistics_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `workers_tbl`
--
ALTER TABLE `workers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
