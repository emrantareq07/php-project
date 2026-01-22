-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2026 at 06:10 AM
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
(2, '1', 'Departmental Litigation, Disciplinary & Appeal Rules', 'conducted by Personnel Division, BCIC.', '2026-01-10', '2026-01-16', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/68c7939cbdccf_signature.png', 'Mr. Md. Fazlur Rahman', 'Chairman', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/68c7939cbdde4_signature.png', 'active', 'active', NULL, NULL, NULL, NULL, NULL, '2025-09-15 04:18:36', '2026-01-21'),
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
(44, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', '', '', '', '', '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 05:18:16', '2026-01-21 05:18:16'),
(45, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', NULL, NULL, '', NULL, '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '2', '2026-01-21 06:59:45', '2026-01-21 06:59:45'),
(46, '6898-1', 'sssrrrrr', 'Sub Assistant Engr.', NULL, NULL, 'SFCL', NULL, '3333', 'anwarurrashid4619-3@bcic.gov.bd', '1234', 'user', 'active', '1', '2026-01-21 07:00:38', '2026-01-22 04:49:15'),
(47, '', 'test', 'Sub Assistant Engr.', NULL, NULL, 'jj', NULL, '01716791307', 'test@yahoo.com', '1234', 'user', 'active', '1', '2026-01-21 07:04:05', '2026-01-21 07:04:05'),
(48, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', '', '', '', '', '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 05:18:16', '2026-01-21 05:18:16'),
(49, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', NULL, NULL, '', NULL, '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 06:59:45', '2026-01-22 04:49:11'),
(50, '6898-1', 'sssrrrrr', 'Sub Assistant Engr.', NULL, NULL, ';', NULL, '3333', 'anwarurrashid4619-3@bcic.gov.bd', '1234', 'user', 'active', '1', '2026-01-21 07:00:38', '2026-01-22 04:49:08'),
(51, '', 'test', 'Sub Assistant Engr.', NULL, NULL, 'jj', NULL, '01716791307', 'test@yahoo.com', '1234', 'user', 'active', '1', '2026-01-21 07:04:05', '2026-01-21 07:04:05'),
(52, '', 'test', 'Sub Assistant Engr.', NULL, NULL, 'jj', NULL, '01716791307', 'test@yahoo.com', '1234', 'user', 'active', '1', '2026-01-21 07:04:05', '2026-01-21 07:04:05'),
(53, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', '', '', '', '', '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 05:18:16', '2026-01-21 05:18:16'),
(54, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', NULL, NULL, '', NULL, '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 06:59:45', '2026-01-22 04:49:06'),
(55, '6898-1', 'sssrrrrr', 'Sub Assistant Engr.', NULL, NULL, ';', NULL, '3333', 'anwarurrashid4619-3@bcic.gov.bd', '1234', 'user', 'active', '1', '2026-01-21 07:00:38', '2026-01-22 04:49:04'),
(56, '', 'test', 'Sub Assistant Engr.', NULL, NULL, 'jj', NULL, '01716791307', 'test@yahoo.com', '1234', 'user', 'active', '1', '2026-01-21 07:04:05', '2026-01-21 07:04:05'),
(57, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', '', '', '', '', '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 05:18:16', '2026-01-21 05:18:16'),
(58, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', NULL, NULL, '', NULL, '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 06:59:45', '2026-01-22 04:49:02'),
(59, '6898-1', 'sssrrrrr', 'Sub Assistant Engr.', NULL, NULL, ';', NULL, '3333', 'anwarurrashid4619-3@bcic.gov.bd', '1234', 'user', 'active', '1', '2026-01-21 07:00:38', '2026-01-22 04:48:58'),
(60, '', 'test', 'Sub Assistant Engr.', NULL, NULL, 'jj', NULL, '01716791307', 'test@yahoo.com', '1234', 'user', 'active', '1', '2026-01-21 07:04:05', '2026-01-21 07:04:05'),
(61, '', 'test', 'Sub Assistant Engr.', NULL, NULL, 'jj', NULL, '01716791307', 'test@yahoo.com', '1234', 'user', 'active', '1', '2026-01-21 07:04:05', '2026-01-21 07:04:05'),
(62, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', '', '', '', '', '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 05:18:16', '2026-01-21 05:18:16'),
(63, '6594-6', 'MD. ABUL HOSSAIN', 'Assistant Manager', NULL, NULL, '', NULL, '1716956408', 'shane@gmail.com', '1234', 'user', 'active', '1', '2026-01-21 06:59:45', '2026-01-22 04:48:55'),
(64, '6898-1', 'sssrrrrr', 'Sub Assistant Engr.', NULL, NULL, ';', NULL, '3333', 'anwarurrashid4619-3@bcic.gov.bd', '1234', 'user', 'active', '1', '2026-01-21 07:00:38', '2026-01-22 04:48:52'),
(65, '', 'test', 'Sub Assistant Engr.', NULL, NULL, 'jj', NULL, '01716791307', 'test@yahoo.com', '1234', 'user', 'active', '1', '2026-01-21 07:04:05', '2026-01-21 07:04:05');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
