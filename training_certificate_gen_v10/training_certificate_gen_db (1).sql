-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 05:53 AM
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
-- Database: `training_certificate_gen_db`
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
  `updated_at` date DEFAULT NULL,
  `exam_date` date NOT NULL,
  `start_time` time(6) NOT NULL,
  `end_time` time(6) NOT NULL,
  `active_exam` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authority_tbl`
--

INSERT INTO `authority_tbl` (`id`, `batch`, `training_title`, `organized_by`, `start_date`, `end_date`, `name1`, `designation1`, `office1`, `ministry1`, `signature1`, `name2`, `designation2`, `office2`, `ministry2`, `signature2`, `active_status`, `tr_link_status`, `name3`, `designation3`, `office3`, `ministry3`, `signature3`, `created_at`, `updated_at`, `exam_date`, `start_time`, `end_time`, `active_exam`) VALUES
(1, '1', 'Webportal Training Session', 'bcic ict division', '2026-02-25', '2026-03-31', 'Mr. Md. Solaiman', 'Project Director (PD)1', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/699eaba692b0c_68b678ba583e0_signature 300_80.jpg', 'Fazlur Rahman', 'Chairman', 'Bangladesh Chemical Industries Corporation', 'Bangladesh Chemical Industries Corporation', 'uploads/699eaba692d76_68b678f22131b_signature 300_80.jpg', 'Inactive', 'active', NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:58:30', '2026-03-08', '2026-03-08', '11:36:00.000000', '15:00:00.000000', 'active'),
(2, '2', 'ggWebportal Training Session34567', 'bcic ict division', '2026-02-25', '2026-03-04', 'Mr. Md. Solaiman', 'Project Director (PD)1', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/699eaba692b0c_68b678ba583e0_signature 300_80.jpg', 'Fazlur Rahman', 'Chairman', 'Bangladesh Chemical Industries Corporation', 'Bangladesh Chemical Industries Corporation', 'uploads/699eaba692d76_68b678f22131b_signature 300_80.jpg', 'Inactive', 'active', NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:58:30', '2026-03-08', '2026-03-08', '11:36:00.000000', '14:53:00.000000', 'active');

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
  `correct_option` enum('A','B','C','D') NOT NULL,
  `exam_date` date NOT NULL,
  `start_time` time(6) NOT NULL,
  `end_time` time(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_set`
--

INSERT INTO `question_set` (`id`, `batch`, `question_name`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `exam_date`, `start_time`, `end_time`) VALUES
(1, 1, 'Country of Ours??', 'BD', 'IND', 'PAK', 'SL', 'A', '0000-00-00', '00:00:00.000000', '00:00:00.000000'),
(2, 1, 'ICC Full Meaning??', 'ACC', 'BCC', 'CCC', 'ICC', 'D', '0000-00-00', '00:00:00.000000', '00:00:00.000000'),
(3, 2, 'Country of Ours??', 'BD', 'IND', 'PAK', 'SL', 'A', '0000-00-00', '00:00:00.000000', '00:00:00.000000'),
(4, 2, 'ICC Full Meaning??', 'ACC', 'BCC', 'CCC', 'ICC', 'D', '0000-00-00', '00:00:00.000000', '00:00:00.000000');

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
(10, '6594-7', 'SHANEWORN ', 'Assistant Programmer', '', '', 'BCIC HO', '', '00000000000', 'shaneworn@gmail.com', '1234', 'user', 'active', '1', '2026-02-25 08:00:04', '2026-03-05 04:27:01', 'BCIC-ICT-DIVISION-B1-2', '\'1\',\'2\'', '\'D\',\'C\''),
(11, '6595-3', 'Abul ', 'Assistant Programmer', NULL, NULL, 'BCIC HO', NULL, '7896352413', 'kk@gmail.com', '$2y$10$.kLFene7e1zFovVn5GerQuSdFm3hp1iviDoiDlYr.cAtct.okQSq.', 'user', 'active', '1', '2026-02-25 08:00:34', '2026-03-08 08:25:31', 'BCIC-ICT-DIVISION-B1-11', '\'1\',\'2\'', '\'A\',\'D\''),
(12, '6595-3', 'Abul ', 'Assistant Programmer', NULL, NULL, 'BCIC HO', NULL, '7896352413', 'kk@gmail.com', '$2y$10$.kLFene7e1zFovVn5GerQuSdFm3hp1iviDoiDlYr.cAtct.okQSq.', 'user', 'active', '2', '2026-02-25 08:00:34', '2026-03-08 07:05:19', 'BCIC-ICT-DIVISION-B1-11', '\'1\',\'2\'', '\'A\',\'C\''),
(13, '6594-7', 'SHANEWORN ', 'Assistant Programmer', '', '', 'BCIC HO', '', '00000000000', 'shaneworn@gmail.com', '$2y$10$SOz.7MJjIR58C.Mh3Vi84.rfS80ROSG1UvkEzBU3E/gCDMqAsjZyG', 'user', 'active', '2', '2026-02-25 08:00:04', '2026-03-08 08:40:41', 'BCIC-ICT-DIVISION-B1-2', '', ''),
(14, '5620-1', 'emran', NULL, NULL, NULL, 'BCIC', NULL, '01718834655', 'test1@yahoo.com', '1234', 'user', 'active', '1', '2026-03-08 08:46:23', '2026-03-08 08:50:25', 'BCIC-ICT-DIVISION-B1-14', '\'1\',\'2\'', '\'A\',\'D\'');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authority_tbl`
--
ALTER TABLE `authority_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `question_set`
--
ALTER TABLE `question_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
