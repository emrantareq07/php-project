-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 11:33 AM
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
(1, 'sfcl', '2025-09-16', 'RS', 'Grade 11', '10', '5', '1', '6', 'active', '2025-10-22 10:46:04', '2025-10-22 10:51:24'),
(2, 'sfcl', '2025-09-25', 'SS,GS,yy', 'Grade 11,Grade 14,Grade 18', '11,22,44', '1,2,4', '0,0,0', '1,2,4', 'active', '2025-10-23 03:33:26', '2025-10-23 04:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `committe_tbl`
--

CREATE TABLE `committe_tbl` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `committe_name` varchar(255) NOT NULL,
  `ref_no` varchar(100) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `division` varchar(150) DEFAULT NULL,
  `type` enum('Chairman','Member Secretary','Member') DEFAULT 'Member',
  `status` enum('Active','Inactive','Pending') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'sfcl', '2025-09-16', 'RS', 'Grade 11', '10', '5', '1', '6', 'active', '2025-10-22 10:46:04', '2025-10-22 10:51:24'),
(2, 'sfcl', '2025-09-25', 'SS,GS,yy', 'Grade 11,Grade 14,Grade 18', '11,22,44', '1,2,4', '0,0,0', '1,2,4', 'active', '2025-10-23 03:33:26', '2025-10-23 04:06:43');

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
(119, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:40:44', NULL, NULL, '2025-10-29 06:40:44', '2025-10-29 06:40:44');

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
(2, 'sfcl', '2025-08-12', '', '1,0,1,0,0,0,0,0,0,0,0,0,0,0', '1,0,1,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,1,1,0,0,0,0,0,0,0,0,0,0', '0,0,1,1,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-23 07:46:25', '2025-10-23 16:21:49'),
(3, 'sfcl', '2025-09-10', '', '1,0,1,1,0,0,0,0,0,0,0,0,0,0', '1,0,1,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,1,1,0,0,0,0,0,0,0,0,0,0', '0,0,1,1,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-23 09:35:48', '2025-10-29 11:05:20'),
(4, 'sfcl', '2025-07-08', '', '1,0,1,0,0,0,0,0,0,0,0,0,0,0', '1,0,1,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,1,1,0,0,0,0,0,0,0,0,0,0', '0,0,1,1,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,1,0,0,0,0,0', '1,0,0,0,0,0,0,0,1,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-23 10:22:26', '2025-10-23 16:30:35'),
(5, 'sfcl', '2025-10-23', '', '5,1,0,0,0,0,0,0,0,0,0,0,0,0', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-23 10:30:41', '2025-10-29 12:40:03'),
(6, 'jfcl', '2025-10-26', '', '4,5,0,0,0,0,0,0,0,0,22,0,0,0', '1,1,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '5,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '4,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '1,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0', '', 'active', '2025-10-26 04:36:02', '2025-10-29 12:43:11');

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
(1, 'sfcl', '2025-09-16', 'RS', 'Grade 11', '10', '5', '1', '6', 'active', '2025-10-22 10:46:04', '2025-10-22 10:51:24'),
(2, 'sfcl', '2025-09-25', 'SS,GS,yy', 'Grade 11,Grade 14,Grade 18', '11,22,44', '1,2,4', '0,0,0', '1,2,4', 'active', '2025-10-23 03:33:26', '2025-10-23 04:06:43'),
(3, 'jfcl', '2025-09-02', 'RS,MLSS', 'Grade 11,Grade 13', '11,22', '1,2', '1,2', '2,4', 'active', '2025-10-26 04:37:01', '2025-10-26 07:17:42'),
(4, 'jfcl', '2025-10-26', 'RS,SS', 'Grade 13,Grade 11', '11,22', '1,2', '1,2', '2,4', 'active', '2025-10-26 07:18:13', '2025-10-26 07:18:13');

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
(1, 'sfcl', '$2y$10$6J5IdNSWBCJOc.4IjlJD8.HvXz90KJEB0xsTjqLnY3feLreJsATza', 'SFCL..', 'SFCL', NULL, 'sfcl@yahoo.com', '2222', 'user', '2025-10-14 08:00:19', '2025-10-19 07:09:44'),
(2, 'jfcl', '$2y$10$JJlrLH/y.Lh5elcETMBNf.i/zmkZL718QER/yNfKK3kWUbtH9VNyO', 'JFCL', 'JFCL', NULL, NULL, NULL, 'user', '2025-10-26 03:45:21', '2025-10-26 03:45:21'),
(3, 'admin', '$2y$10$TdA8pCKlQmdP56Jm2CD.fuEVpKskNFAgsjyzFluSoTwR8u5rc3nry', 'admin', 'admin', NULL, NULL, NULL, 'admin', '2025-10-26 04:29:52', '2025-10-26 04:29:52');

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
(13, 'sfcl', '2025-10-20', 'SST,SST 1,SST,hso', 'Grade 13,Grade 14,Grade 15,Grade 10', '10,20,11,22', '5,5,1,21', '5,5,1,0', '10,10,2,21', 'active', '2025-10-22 09:54:43', '2025-10-23 03:28:24');

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
-- Indexes for table `committe_tbl`
--
ALTER TABLE `committe_tbl`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `committe_tbl`
--
ALTER TABLE `committe_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_basis_tbl`
--
ALTER TABLE `daily_basis_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `officers_tbl`
--
ALTER TABLE `officers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sfcl`
--
ALTER TABLE `sfcl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staffs_tbl`
--
ALTER TABLE `staffs_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `workers_tbl`
--
ALTER TABLE `workers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
