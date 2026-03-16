-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 09:39 AM
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
-- Database: `project_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_url` varchar(500) NOT NULL,
  `category` varchar(100) DEFAULT 'Other',
  `status` enum('Active','Maintenance','Development') DEFAULT 'Active',
  `icon_color` varchar(20) DEFAULT '#3498db',
  `description` text DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `project_url`, `category`, `status`, `icon_color`, `description`, `screenshot`, `sort_order`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'Daily Meeting Schedule Management (for All Factory)', 'https://180.211.161.44/daily_meeting_schedule_factory/', 'System', 'Active', '#3498db', '', '', 6, '2026-03-05 07:11:26', '2026-03-09 04:44:01', 1),
(2, 'বিসিআইসি এমপ্লয়ী ট্রেনিং ডাটাবেজ', 'http://103.7.112.186:8080/rnt_training_db/', 'Website', 'Active', '#3498db', '', '', 4, '2026-03-05 07:11:52', '2026-03-09 04:38:58', 1),
(3, 'Employee ID Generator', 'http://180.211.161.44/emp_id_creator_v2/', 'System', 'Active', '#3498db', '', '', 2, '2026-03-05 07:25:56', '2026-03-09 04:34:52', 1),
(4, 'বিসিআইসি মামলার ডাটাবেস সিস্টেম', 'http://103.7.112.186:8080/legal_db/', 'Website', 'Active', '#3498db', '', '', 0, '2026-03-05 07:25:56', '2026-03-09 04:27:14', 1),
(5, 'Daily Meeting Schedule Management (Only LAN user)', 'http://192.168.1.51:8080/daily_meeting_schedule/', 'System', 'Active', '#3498db', '', '', 5, '2026-03-05 07:26:19', '2026-03-09 04:43:06', 1),
(6, 'বিসিআইসি ভূমি ডাটাবেজ', 'http://103.7.112.186:8080/land_db/', 'System', 'Active', '#3498db', '', '', 3, '2026-03-08 04:22:11', '2026-03-09 04:36:10', 1),
(7, 'ইএমডি ভাড়া ব্যবস্থাপনা', 'http://103.7.112.186:8080/emd_rent_db/', 'System', 'Active', '#3498db', '', '', 1, '2026-03-08 05:26:56', '2026-03-09 04:34:04', 1),
(8, 'বিসিআইসি পত্র প্রাপ্তি রেজিস্টার', 'http://103.7.112.186:8080/blrr/', 'Website', 'Active', '#3498db', '', '', 0, '2026-03-08 05:35:22', '2026-03-09 04:30:23', 1),
(9, 'দৈনিক উৎপাদন ও প্ল্যান্ট স্ট্যাটাস রিপোর্ট', 'http://180.211.161.44/dfms/', 'System', 'Active', '#3498db', '', '', 0, '2026-03-08 05:54:05', '2026-03-09 04:34:19', 1),
(10, 'আইসিটি রক্ষণাবেক্ষন ডাটাবেজ (শুধু মাত্র ল্যান ব্যবহারকারীদের জন্য)', 'http://192.168.1.51:8080/ict_main_records_db/', 'System', 'Active', '#3498db', '', '', 0, '2026-03-09 04:44:39', '2026-03-09 04:44:39', 1),
(11, 'উদ্ভাবনী ধারনা, সহজিকৃত ও ডিজিটালাইজকৃত সেবার ডাটাবেজ', 'http://180.211.161.44/innovation_db/', 'Website', 'Active', '#3498db', '', '', 0, '2026-03-09 04:46:06', '2026-03-09 04:46:06', 1),
(12, 'বিসিআইসি ট্রেনিং ও সার্টিফিকেট ম্যানেজমেন্ট সিস্টেম', 'http://180.211.161.44/training_certificate_gen/', 'System', 'Active', '#3498db', '', '', 0, '2026-03-09 04:48:05', '2026-03-09 04:48:05', 1),
(13, 'বিসিআইসি ই-লাইব্রেরি ম্যানেজমেন্ট সিস্টেম', 'http://180.211.161.44/bcic_e-library/', 'System', 'Active', '#3498db', '', '', 0, '2026-03-09 04:48:30', '2026-03-09 04:48:30', 1),
(14, 'বিসিআইসি কলেজ ই-লাইব্রেরি ম্যানেজমেন্ট সিস্টেম', 'http://180.211.161.44/bcic-college_e-library/', 'System', 'Active', '#3498db', '', '', 0, '2026-03-09 04:49:02', '2026-03-09 04:49:02', 1),
(15, 'বিসিআইসি ম্যানপাওয়ার ম্যানেজমেন্ট সিস্টেম', 'http://180.211.161.44/bcic_man_power_db/', 'System', 'Active', '#3498db', '', '', 0, '2026-03-09 04:49:17', '2026-03-09 04:49:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects1`
--

CREATE TABLE `projects1` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_url` varchar(500) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` enum('Active','Maintenance','Development') DEFAULT 'Active',
  `icon_color` varchar(20) DEFAULT '#3498db',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects1`
--

INSERT INTO `projects1` (`id`, `project_name`, `project_url`, `category`, `status`, `icon_color`, `description`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'E-Commerce Platform', 'https://shop.example.com', 'E-commerce', 'Active', '#3498db', 'Online shopping platform with payment integration', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(2, 'Content Management', 'https://cms.example.com', 'CMS', 'Active', '#27ae60', 'Blog and content management system', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(3, 'Analytics Dashboard', 'https://analytics.example.com', 'Dashboard', 'Active', '#f39c12', 'Real-time analytics and reporting', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(4, 'CRM System', 'https://crm.example.com', 'CRM', 'Maintenance', '#e74c3c', 'Customer relationship management', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(5, 'Project Management', 'https://pm.example.com', 'Dashboard', 'Active', '#9b59b6', 'Team task and project tracking', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(6, 'Payment Gateway', 'https://payment.example.com', 'API', 'Active', '#1abc9c', 'Payment processing system', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(7, 'Email Marketing', 'https://email.example.com', 'CRM', 'Active', '#34495e', 'Email campaign management', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(8, 'Invoice System', 'https://invoice.example.com', 'Dashboard', 'Maintenance', '#e84393', 'Invoicing and billing system', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(9, 'Booking System', 'https://booking.example.com', 'E-commerce', 'Active', '#4834d4', 'Appointment and reservation system', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1),
(10, 'Database Manager', 'https://db.example.com', 'API', 'Active', '#00cec9', 'Database administration tool', '2026-02-26 05:47:05', '2026-02-26 05:47:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects3`
--

CREATE TABLE `projects3` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_url` varchar(500) NOT NULL,
  `category` varchar(100) DEFAULT 'Other',
  `status` enum('Active','Maintenance','Development') DEFAULT 'Active',
  `icon_color` varchar(20) DEFAULT '#3498db',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects4`
--

CREATE TABLE `projects4` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_url` varchar(500) NOT NULL,
  `category` varchar(100) DEFAULT 'Other',
  `status` enum('Active','Maintenance','Development') DEFAULT 'Active',
  `icon_color` varchar(20) DEFAULT '#3498db',
  `description` text DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects4`
--

INSERT INTO `projects4` (`id`, `project_name`, `project_url`, `category`, `status`, `icon_color`, `description`, `screenshot`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'test bb', 'http://test.com', 'CMS', 'Active', '#3498db', '', NULL, '2026-03-05 05:58:12', '2026-03-05 06:32:54', 1),
(2, 'test', 'http://test.com', 'CMS', 'Active', '#3498db', '', NULL, '2026-03-05 06:14:26', '2026-03-05 06:49:15', 1),
(3, 'test 4', 'http://test4.com', 'Website', 'Active', '#3498db', '', NULL, '2026-03-05 06:56:36', '2026-03-05 06:56:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects1`
--
ALTER TABLE `projects1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects3`
--
ALTER TABLE `projects3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects4`
--
ALTER TABLE `projects4`
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
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `projects1`
--
ALTER TABLE `projects1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `projects3`
--
ALTER TABLE `projects3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects4`
--
ALTER TABLE `projects4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
