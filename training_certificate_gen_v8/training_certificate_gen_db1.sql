-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2026 at 05:44 AM
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
-- Database: `training_certificate_gen_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `authority_tbl`
--

CREATE TABLE `authority_tbl` (
  `id` int(11) NOT NULL,
  `batch` varchar(50) NOT NULL,
  `training_title` longtext NOT NULL,
  `organized_by` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `name1` varchar(255) NOT NULL,
  `designation1` varchar(100) NOT NULL,
  `office1` varchar(255) NOT NULL,
  `ministry1` varchar(100) NOT NULL,
  `signature1` varchar(255) NOT NULL,
  `name2` varchar(255) NOT NULL,
  `designation2` varchar(100) NOT NULL,
  `office2` varchar(100) NOT NULL,
  `ministry2` varchar(100) NOT NULL,
  `signature2` varchar(255) DEFAULT NULL,
  `active_status` varchar(10) NOT NULL,
  `tr_link_status` varchar(50) NOT NULL,
  `name3` varchar(255) DEFAULT NULL,
  `designation3` varchar(100) DEFAULT NULL,
  `office3` varchar(100) DEFAULT NULL,
  `ministry3` varchar(100) DEFAULT NULL,
  `signature3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `exam_date` date NOT NULL,
  `start_time` time(6) NOT NULL,
  `end_time` time(6) NOT NULL,
  `active_exam` varchar(100) NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authority_tbl`
--

INSERT INTO `authority_tbl` (`id`, `batch`, `training_title`, `organized_by`, `start_date`, `end_date`, `name1`, `designation1`, `office1`, `ministry1`, `signature1`, `name2`, `designation2`, `office2`, `ministry2`, `signature2`, `active_status`, `tr_link_status`, `name3`, `designation3`, `office3`, `ministry3`, `signature3`, `created_at`, `updated_at`, `exam_date`, `start_time`, `end_time`, `active_exam`) VALUES
(4, '1', 'Web Portal Training', 'ICT Division, BCIC', '2026-01-26', '2026-01-27', 'S1', 'GM (ICT)', 'BCIC', 'BCIC', 'uploads/697701c9da281_images.jpg', 'S2', 'Dir (Tech)', 'BCIC', 'BCIC', 'uploads/697701c9da6e0_images.jpg', 'Inactive', 'Inactive', NULL, NULL, NULL, NULL, NULL, '2026-01-25 23:55:21', '2026-03-08 18:00:00', '0000-00-00', '00:00:00.000000', '00:00:00.000000', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `question_set`
--

CREATE TABLE `question_set` (
  `id` int(11) NOT NULL,
  `batch` int(11) NOT NULL,
  `question_name` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_set`
--

INSERT INTO `question_set` (`id`, `batch`, `question_name`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'Country of Ours??', 'BD', 'IND', 'PAK', 'SL', 'A'),
(2, 1, 'ICC Full Meaning??', 'ACC', 'BCC', 'CCC', 'ICC', 'D'),
(3, 2, 'Country of Ours??', 'BD', 'IND', 'PAK', 'SL', 'A'),
(4, 2, 'ICC Full Meaning??', 'ACC', 'BCC', 'CCC', 'ICC', 'D');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `place_of_posting` varchar(100) DEFAULT NULL,
  `office` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `batch` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `serial_no` varchar(255) NOT NULL,
  `question_all` text NOT NULL,
  `answer_all` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `place_of_posting`, `office`, `mobile_no`, `email_id`, `password`, `role`, `status`, `batch`, `created_at`, `updated_at`, `serial_no`, `question_all`, `answer_all`) VALUES
(1, '5620-0', 'sadmin', NULL, NULL, NULL, 'BCIC', 'BCIC', '01718834655', 'test@yahoo.com', '1234', 'sadmin', 'active', '', '2026-01-22 08:28:08', '2026-01-22 08:28:08', '', '', ''),
(2, '5692-9', 'Mohammad Azim Uddin', 'Deputy Manager (Administration)', 'Administration', 'LSA', 'DAPFCL', 'DAPFCL', '01515680819', 'azimlawcu@gmail.com', '$2y$10$HAPfVPlN/psx.adZslVmD.SubZZKdfUTS3tcNwberU1tEQcPF9RCC', 'user', 'active', '1', '2026-01-26 05:58:01', '2026-02-05 09:59:41', 'BCIC-ICT-DIVISION-B1-2', '', ''),
(3, '5619-2', 'Mohammad Saiful Islam', 'Programmer', NULL, NULL, 'TSP Complex Limited', NULL, '01885406868', 'saiful.on@gmail.com', '$2y$10$lswDmd6wsQFFcrVmM3G.E.LhcRwpjMXbnkjotfL17NL4LjkWSheZa', 'user', 'active', '1', '2026-01-26 05:59:15', '2026-02-05 09:59:55', 'BCIC-ICT-DIVISION-B1-3', '', ''),
(4, '5053-4', 'Naba Krishna Sardar', 'Additional Chief Chemist', NULL, NULL, 'DAPFCL', NULL, '01717658284', 'nabokrishna@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:20', '2026-02-05 10:00:02', 'BCIC-ICT-DIVISION-B1-4', '', ''),
(5, '', 'Kowshik Chowdhury', 'Assistant Programmer', 'CIT', '', 'TICI', '', '01994928756', 'kowshikichowdury.bcic@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:28', '2026-03-11 04:19:25', 'BCIC-ICT-DIVISION-B1-5', '', ''),
(6, '6769-4', 'Kazi Mahtab-ul-Islam', 'Sub Assistant Chemist', NULL, NULL, 'Chhatak Cement Company Limited', NULL, '01676339522', 'mahtabshawon@gmail.com', '$2y$10$2RZ6Bujh6dtUQocHJtbMd.aTVTSPGzriXuStGp/xlvGtzfBZSMdHq', 'user', 'active', '1', '2026-01-26 06:01:20', '2026-02-05 10:00:13', 'BCIC-ICT-DIVISION-B1-6', '', ''),
(7, '5726-5', 'Samiran Marma', 'Deputy Manager (Admin)', 'Administration', 'MD Office', 'CUFL', 'Chittagong Urea Fertilizer Limited', '01784607581', 'marmasamiran@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:01:43', '2026-02-05 10:00:18', 'BCIC-ICT-DIVISION-B1-7', '', ''),
(8, '5753-9', 'RAJIB KUMAR PAUL', 'Deputy Manager(Administration)', 'Administration', '', 'Chhatak Cement Company Limited', '', '01842710472', 'paulrajib15du@gmail.com', '$2y$10$ZMMDGKmiZu6NCd7eDErK0OzcHZ3m1NSimibRihWsDH2APSnus4E3e', 'user', 'active', '1', '2026-01-26 06:01:45', '2026-02-05 10:00:23', 'BCIC-ICT-DIVISION-B1-8', '', ''),
(9, '1412', 'Md. Shakilur Rahman', 'UDA', NULL, NULL, 'TSPCL', NULL, '01834059361', 'shakilurrahman.361@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:02:38', '2026-02-05 10:00:29', 'BCIC-ICT-DIVISION-B1-9', '', ''),
(10, '5671-3', 'Seikh Shoaibur Rahman', 'Assistant Chief Accountant', 'Accounts', 'COST & Budget and ICT', 'AFCCL', 'AFCCL', '01719385232', 'seikhshoaibur@gmail.com', '$2y$10$GzOHALUfJxzYLAcYXwZDLODVozyyjA5WwSCLfMUyShWj0xCHegK3G', 'user', 'active', '1', '2026-01-26 06:02:46', '2026-02-05 10:00:34', 'BCIC-ICT-DIVISION-B1-10', '', ''),
(11, '', 'MD. Anamul Hoque Monir', 'Assistant Commercial Officer', NULL, NULL, 'BISFL', NULL, '01737487236', 'mamonir74@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:06', '2026-02-05 10:00:38', 'BCIC-ICT-DIVISION-B1-11', '', ''),
(12, '', 'Efdtekhar Uddin Mohammad Aman', 'Additional Chief Chemist', NULL, NULL, 'Training Institute for Chemical Industries', NULL, '01611063963', 'eumaman@live.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:11', '2026-02-05 10:00:43', 'BCIC-ICT-DIVISION-B1-12', '', ''),
(13, '5074-0', 'Md. Ziaul Hasan', 'System Analyst', NULL, NULL, 'Jamuna Fertilizer Company Ltd.', NULL, '01717883311', 'ziajfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:36', '2026-02-05 10:00:48', 'BCIC-ICT-DIVISION-B1-13', '', ''),
(14, '4497-4', 'Mohammad Mahbubur Rahman', 'Additional Chief Engr. (Elect.)', NULL, NULL, 'GPFPLC', NULL, '01684818300', 'm.mahbub.2030@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:01', '2026-02-05 10:00:56', 'BCIC-ICT-DIVISION-B1-14', '', ''),
(15, '5244-9', 'Rupom Barua', 'Deputy Chief Chemist', 'Technical', 'R&D and QC', 'Karnaphuli Paper Mills Limited', '', '01815062404', 'rupombarua@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:47', '2026-02-05 10:01:02', 'BCIC-ICT-DIVISION-B1-15', '', ''),
(16, '6681-1', 'MD. ASHFAQUL ISLAM', 'Assistant Programmer', NULL, NULL, 'Ghorashal Polash Fertilizer PLC', NULL, '01774915052', 'fahimkuet09@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:00', '2026-02-05 10:01:07', 'BCIC-ICT-DIVISION-B1-16', '', ''),
(17, '4603-7', 'Shameem Ahmed', 'Deputy Chief Chemist', NULL, NULL, 'KPML', NULL, '01874463865', 'shameemlyon@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:10', '2026-02-05 10:01:15', 'BCIC-ICT-DIVISION-B1-17', '', ''),
(18, '3244-1', 'Shambhu Lal Das', 'GM(Operation)', NULL, NULL, 'SFCL/BCIC', NULL, '01735593606', 'shambhubcic@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:35', '2026-02-05 10:01:20', 'BCIC-ICT-DIVISION-B1-18', '', ''),
(19, '6489-9', 'sharif uddin sikder', 'Programmer', NULL, NULL, ' CUFL', NULL, '01711038836', 'sharifsikderbd24@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:10:36', '2026-02-05 10:01:24', 'BCIC-ICT-DIVISION-B1-19', '', ''),
(20, '5197-9', 'Md Saiful Islam', 'ACA', NULL, NULL, 'JFCL', NULL, '01721-100107', 'saiful1978jfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:11:33', '2026-02-05 10:01:28', 'BCIC-ICT-DIVISION-B1-20', '', ''),
(21, '', 'Sadman Sakib', 'Sub-Assistant Engineer (Mech.)', NULL, NULL, 'BISFL', NULL, '01830624838', 'saakiib@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:16:35', '2026-02-05 10:01:32', 'BCIC-ICT-DIVISION-B1-21', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl_old`
--

CREATE TABLE `users_tbl_old` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `place_of_posting` varchar(100) DEFAULT NULL,
  `office` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `batch` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `serial_no` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl_old`
--

INSERT INTO `users_tbl_old` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `place_of_posting`, `office`, `mobile_no`, `email_id`, `password`, `role`, `status`, `batch`, `created_at`, `updated_at`, `serial_no`) VALUES
(1, '5620-0', 'sadmin', NULL, NULL, NULL, 'BCIC', 'BCIC', '01718834655', 'test@yahoo.com', '1234', 'sadmin', 'active', '', '2026-01-22 08:28:08', '2026-01-22 08:28:08', ''),
(4, '1111122', 'test', 'Sub Assistant Engr.', 'Administration', 'Tecnical', 'BCIC H.O.', '', '01718834655', 'cop@bcic.gov.bd', '1234', 'user', 'active', '2', '2026-01-25 09:42:22', '2026-02-05 05:51:50', 'BCIC-ICT-DIVISION-B2-4'),
(7, '5692-9', 'Mohammad Azim Uddin', 'Deputy Manager (Administration)', 'Administration', 'LSA', 'DAPFCL', 'DAPFCL', '01515680819', 'azimlawcu@gmail.com', '$2y$10$HAPfVPlN/psx.adZslVmD.SubZZKdfUTS3tcNwberU1tEQcPF9RCC', 'user', 'active', '1', '2026-01-26 05:58:01', '2026-01-26 06:05:11', 'BCIC-ICT-DIVISION-B1-5'),
(8, '4956-9', 'Md Mamudul Hasan Chowdhury', 'Deputy Chief Engineer', NULL, NULL, 'SFCL', NULL, '01716006200', 'hasan49569@sfcl.gov.bd', '1234', 'user', 'active', '1', '2026-01-26 05:58:28', '2026-01-26 05:58:28', 'BCIC-ICT-DIVISION-B1-8'),
(9, '5619-2', 'Mohammad Saiful Islam', 'Programmer', NULL, NULL, 'TSP Complex Limited', NULL, '01885406868', 'saiful.on@gmail.com', '$2y$10$lswDmd6wsQFFcrVmM3G.E.LhcRwpjMXbnkjotfL17NL4LjkWSheZa', 'user', 'active', '1', '2026-01-26 05:59:15', '2026-01-26 06:01:30', 'BCIC-ICT-DIVISION-B1-9'),
(10, '5053-4', 'Naba Krishna Sardar', 'Additional Chief Chemist', NULL, NULL, 'DAPFCL', NULL, '01717658284', 'nabokrishna@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:20', '2026-01-26 06:00:20', 'BCIC-ICT-DIVISION-B1-10'),
(11, '', 'Kowshik Chowdhury', 'Assistant Programmer', 'CIT', '', 'TICI', '', '01994928756', 'kowshikichowdury.bcic@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:28', '2026-01-26 06:01:27', 'BCIC-ICT-DIVISION-B1-11'),
(13, '6769-4', 'Kazi Mahtab-ul-Islam', 'Sub Assistant Chemist', NULL, NULL, 'Chhatak Cement Company Limited', NULL, '01676339522', 'mahtabshawon@gmail.com', '$2y$10$2RZ6Bujh6dtUQocHJtbMd.aTVTSPGzriXuStGp/xlvGtzfBZSMdHq', 'user', 'active', '1', '2026-01-26 06:01:20', '2026-01-26 06:02:15', 'BCIC-ICT-DIVISION-B1-13'),
(14, '5726-5', 'Samiran Marma', 'Deputy Manager (Admin)', 'Administration', 'MD Office', 'CUFL', 'Chittagong Urea Fertilizer Limited', '01784607581', 'marmasamiran@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:01:43', '2026-01-26 06:05:17', 'BCIC-ICT-DIVISION-B1-14'),
(15, '5753-9', 'RAJIB KUMAR PAUL', 'Deputy Manager(Administration)', 'Administration', '', 'Chhatak Cement Company Limited', '', '01842710472', 'paulrajib15du@gmail.com', '$2y$10$ZMMDGKmiZu6NCd7eDErK0OzcHZ3m1NSimibRihWsDH2APSnus4E3e', 'user', 'active', '1', '2026-01-26 06:01:45', '2026-01-27 04:05:22', 'BCIC-ICT-DIVISION-B1-15'),
(16, '1412', 'Md. Shakilur Rahman', 'UDA', NULL, NULL, 'TSPCL', NULL, '01834059361', 'shakilurrahman.361@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:02:38', '2026-01-26 06:02:38', 'BCIC-ICT-DIVISION-B1-16'),
(17, '5671-3', 'Seikh Shoaibur Rahman', 'Assistant Chief Accountant', 'Accounts', 'COST & Budget and ICT', 'AFCCL', 'AFCCL', '01719385232', 'seikhshoaibur@gmail.com', '$2y$10$GzOHALUfJxzYLAcYXwZDLODVozyyjA5WwSCLfMUyShWj0xCHegK3G', 'user', 'active', '1', '2026-01-26 06:02:46', '2026-01-26 06:05:36', 'BCIC-ICT-DIVISION-B1-17'),
(18, '', 'MD. Anamul Hoque Monir', 'Assistant Commercial Officer', NULL, NULL, 'BISFL', NULL, '01737487236', 'mamonir74@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:06', '2026-01-26 06:04:06', 'BCIC-ICT-DIVISION-B1-18'),
(19, '', 'Efdtekhar Uddin Mohammad Aman', 'Additional Chief Chemist', NULL, NULL, 'Training Institute for Chemical Industries', NULL, '01611063963', 'eumaman@live.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:11', '2026-01-26 06:04:11', 'BCIC-ICT-DIVISION-B1-19'),
(20, '5074-0', 'Md. Ziaul Hasan', 'System Analyst', NULL, NULL, 'Jamuna Fertilizer Company Ltd.', NULL, '01717883311', 'ziajfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:36', '2026-01-26 06:04:36', 'BCIC-ICT-DIVISION-B1-20'),
(21, '4497-4', 'Mohammad Mahbubur Rahman', 'Additional Chief Engr. (Elect.)', NULL, NULL, 'GPFPLC', NULL, '01684818300', 'm.mahbub.2030@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:01', '2026-01-26 06:07:01', 'BCIC-ICT-DIVISION-B1-21'),
(22, '5244-9', 'Rupom Barua', 'Deputy Chief Chemist', 'Technical', 'R&D and QC', 'Karnaphuli Paper Mills Limited', '', '01815062404', 'rupombarua@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:47', '2026-01-26 06:10:48', 'BCIC-ICT-DIVISION-B1-22'),
(23, '6681-1', 'MD. ASHFAQUL ISLAM', 'Assistant Programmer', NULL, NULL, 'Ghorashal Polash Fertilizer PLC', NULL, '01774915052', 'fahimkuet09@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:00', '2026-01-26 06:08:00', 'BCIC-ICT-DIVISION-B1-23'),
(24, '4603-7', 'Shameem Ahmed', 'Deputy Chief Chemist', NULL, NULL, 'KPML', NULL, '01874463865', 'shameemlyon@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:10', '2026-01-26 06:08:10', 'BCIC-ICT-DIVISION-B1-24'),
(25, '3244-1', 'Shambhu Lal Das', 'GM(Operation)', NULL, NULL, 'SFCL/BCIC', NULL, '01735593606', 'shambhubcic@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:35', '2026-01-26 06:08:35', 'BCIC-ICT-DIVISION-B1-25'),
(26, '6489-9', 'sharif uddin sikder', 'Programmer', NULL, NULL, ' CUFL', NULL, '01711038836', 'sharifsikderbd24@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:10:36', '2026-01-26 06:10:36', 'BCIC-ICT-DIVISION-B1-26'),
(27, '5197-9', 'Md Saiful Islam', 'ACA', NULL, NULL, 'JFCL', NULL, '01721-100107', 'saiful1978jfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:11:33', '2026-01-26 06:11:33', 'BCIC-ICT-DIVISION-B1-27'),
(28, '', 'Sadman Sakib', 'Sub-Assistant Engineer (Mech.)', NULL, NULL, 'BISFL', NULL, '01830624838', 'saakiib@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:16:35', '2026-01-26 06:16:35', 'BCIC-ICT-DIVISION-B1-28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authority_tbl`
--
ALTER TABLE `authority_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_set`
--
ALTER TABLE `question_set`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_tbl_old`
--
ALTER TABLE `users_tbl_old`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `question_set`
--
ALTER TABLE `question_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users_tbl_old`
--
ALTER TABLE `users_tbl_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
