-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 05:01 AM
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
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authority_tbl`
--

INSERT INTO `authority_tbl` (`id`, `batch`, `training_title`, `organized_by`, `start_date`, `end_date`, `name1`, `designation1`, `office1`, `ministry1`, `signature1`, `name2`, `designation2`, `office2`, `ministry2`, `signature2`, `active_status`, `tr_link_status`, `name3`, `designation3`, `office3`, `ministry3`, `signature3`, `created_at`, `updated_at`) VALUES
(2, '1', 'Departmental Litigation, Disciplinary & Appeal Rules', 'conducted by Personnel Division, BCIC.', '2025-09-16', '2026-03-28', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/68c7939cbdccf_signature.png', 'Mr. Md. Fazlur Rahman', 'Chairman', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/68c7939cbdde4_signature.png', 'active', 'active', NULL, NULL, NULL, NULL, NULL, '2025-09-15 04:18:36', '2026-01-19'),
(3, '2', 'web portal', 'bcic ict division', '2026-01-26', '2026-01-27', 'Mr. Md. Solaiman', 'PD1', 'MOI', 'MOI', 'uploads/696da2fef1a87_signature.png', 'Fazlur Rahman', 'PD2', 'MOI', 'MOI', 'uploads/696da2fef1dd9_signature.png', 'active', 'active', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:20:30', '2026-01-19');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `place_of_posting`, `office`, `mobile_no`, `email_id`, `password`, `role`, `status`, `batch`, `created_at`, `updated_at`) VALUES
(1, '5620-0', 'emran', 'Programmer', 'ICT', 'ICT', 'H.O', NULL, '01718834655', 'test@yahoo.com', '1234', 'sadmin', 'active', '', '2025-09-15 03:39:12', '2025-09-15 03:39:43'),
(2, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 04:40:24', '2026-01-19 06:37:00'),
(3, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:04:08', '2026-01-19 06:37:00'),
(8, '4619-3', 'Mr. Mohammad Anwarur Rashid', 'Deputy General Manager (Administration)', '', '', 'BCIC H.O.', '', '1716956408', 'anwarurrashid4619-3@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:06:37', '2026-01-18 09:37:04'),
(9, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:08:18', '2026-01-19 06:37:00'),
(10, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:09:43', '2026-01-19 06:37:00'),
(11, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:10:41', '2026-01-19 06:37:00'),
(12, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:12:26', '2026-01-19 06:37:00'),
(13, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:13:47', '2026-01-19 06:37:00'),
(14, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:15:29', '2026-01-19 06:37:00'),
(15, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:17:53', '2026-01-19 06:37:00'),
(16, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:20:09', '2026-01-19 06:37:00'),
(17, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:21:45', '2026-01-19 06:37:00'),
(18, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:23:08', '2026-01-19 06:37:00'),
(19, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:24:19', '2026-01-19 06:37:00'),
(20, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:26:00', '2026-01-19 06:37:00'),
(21, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:27:20', '2026-01-19 06:37:00'),
(22, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:28:48', '2026-01-19 06:37:00'),
(23, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:30:12', '2026-01-19 06:37:00'),
(24, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:32:39', '2026-01-19 06:37:00'),
(25, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:34:02', '2026-01-19 06:37:00'),
(26, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:35:14', '2026-01-19 06:37:00'),
(27, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:37:20', '2026-01-19 06:37:00'),
(28, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:38:18', '2026-01-19 06:37:00'),
(29, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:40:22', '2026-01-19 06:37:00'),
(30, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:41:30', '2026-01-19 06:37:00'),
(31, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:42:44', '2026-01-19 06:37:00'),
(32, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:43:28', '2026-01-19 06:37:00'),
(33, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:45:52', '2026-01-19 06:37:00'),
(34, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:47:06', '2026-01-19 06:37:00'),
(35, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '1', '2025-09-15 05:54:14', '2026-01-19 06:37:00'),
(36, '6898-1', 'raji', 'Deputy Chief Accountant', 'Administration', 'Tecnical', 'BCIC H.O.', 'SFCL', '01716791307', 'pd2@yahoo.com', '$2y$10$hpUdrtBuniIcyLp1OYhjgOqonmdzxJHFmNfSaborq6cn1N2up2mTe', 'user', 'active', '2', '2026-01-19 03:22:07', '2026-01-19 10:39:42'),
(37, '', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', '', '', 'BCIC H.O.', '', '01718956354', 'cop@bcic.gov.bd', '1234', 'user', 'active', '2', '2026-01-19 06:35:15', '2026-01-19 06:37:00'),
(38, '', 'Shaneworn Bhadra ', 'Assistant Manager', '', '', 'BCIC H.O.', '', '01878072812', 'shane@gmail.com', '1234', 'user', 'active', '2', '2026-01-19 06:52:40', '2026-01-19 06:52:40'),
(39, '', 'Shaneworn Bhadra ', 'Assistant Programmer', '', '', 'BCIC H.O.', '', '01718956354op@b', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-19 06:56:46', '2026-01-19 06:56:46'),
(40, '6594-6', 'test', 'Sub Assistant Engr.', '', '', 'BCIC H.O.', '', '131234124124312', 'ram@gmail.com', '1234', 'user', 'active', '1', '2026-01-19 06:58:06', '2026-01-19 06:58:06'),
(41, '', 'test', 'Sub Assistant Engr.', '', '', 'BCIC H.O.', '', '01718834655', 'ram@gmail.com', '1234', 'user', 'active', '2', '2026-01-19 07:01:38', '2026-01-19 07:01:38'),
(42, '6594-6', 'sssrrrrr', 'Sub Assistant Engr.', '', '', 'BCIC H.O.', '', '131234124124312', 'anwarurrashid4619-3@bcic.gov.bd', '1234', 'user', 'active', '2', '2026-01-19 07:02:52', '2026-01-19 07:02:52'),
(43, '6790-0', 'MD. ABUL HOSSAIN', 'Assistant Manager', '', '', '', '', '01716791307', 'pd2@yahoo.com', '1234', 'user', 'active', '1', '2026-01-19 10:47:43', '2026-01-19 10:47:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authority_tbl`
--
ALTER TABLE `authority_tbl`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
