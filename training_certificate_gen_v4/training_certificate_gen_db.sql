-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2026 at 04:21 AM
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
(2, '1', 'Departmental Litigation, Disciplinary & Appeal Rules', 'conducted by Personnel Division, BCIC.', '2026-01-10', '2026-01-30', 'Mr. A.N.M. Shariful Alam', 'Chief of Personnel', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/68c7939cbdccf_signature.png', 'Mr. Md. Fazlur Rahman', 'Chairman', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/68c7939cbdde4_signature.png', 'active', 'active', NULL, NULL, NULL, NULL, NULL, '2025-09-15 04:18:36', '2026-01-21'),
(3, '2', 'web portal', 'bcic ict division', '2026-01-26', '2026-01-27', 'Mr. Md. Solaiman', 'PD1', 'MOI', 'MOI', 'uploads/696da2fef1a87_signature.png', 'Fazlur Rahman', 'PD2', 'MOI', 'MOI', 'uploads/696da2fef1dd9_signature.png', 'Inactive', 'active', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:20:30', '2026-01-25');

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
  `serial_no` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `place_of_posting`, `office`, `mobile_no`, `email_id`, `password`, `role`, `status`, `batch`, `created_at`, `updated_at`, `serial_no`) VALUES
(1, '5620-0', 'sadmin', NULL, NULL, NULL, 'BCIC', 'BCIC', '01718834655', 'test@yahoo.com', '1234', 'sadmin', 'active', '', '2026-01-22 08:28:08', '2026-01-22 08:28:08', ''),
(2, '11111444', 'MR. A.N.M. SHARIFUL ALAM1', 'Sub Assistant Engr.', '', '', 'BCIC H.O.', '', '335435343', 'shane@gmail.com', '$2y$10$0/PSksgznRfAEDogufvNDeOHpucTKMmIHSCOvvbcCHictmIgcqo8C', 'user', 'active', '2', '2026-01-22 08:28:33', '2026-01-25 09:53:03', 'BCIC-ICT-DIVISION-B2-2'),
(3, '11111444', 'MR. A.N.M. SHARIFUL ALAM1', 'Chief of Personnel', '', '', 'BCIC H.O', '', '335435343', 'shane@gmail.com', '1234', 'user', 'active', '2', '2026-01-25 03:43:05', '2026-01-25 09:53:03', 'BCIC-ICT-DIVISION-B2-3'),
(4, '1111122', 'test', 'Sub Assistant Engr.', NULL, NULL, 'BCIC H.O.', NULL, '01718834655', 'cop@bcic.gov.bd', '1234', 'user', 'active', '2', '2026-01-25 09:42:22', '2026-01-25 09:42:22', 'BCIC-ICT-DIVISION-B2-4'),
(5, '6594-6', 'test', 'Sub Assistant Engr.', NULL, NULL, 'BCIC H.O.', NULL, '01716791307', 'shane@gmail.com', '1234', 'user', 'active', '2', '2026-01-25 09:51:21', '2026-01-25 09:51:21', 'BCIC-ICT-DIVISION-B2-5'),
(6, '6851-0', 't2', 'test', NULL, NULL, 'tesat', NULL, '23453453242', 'shane4@gmail.com', '1234', 'user', 'active', '2', '2026-01-26 03:20:33', '2026-01-26 03:20:33', 'BCIC-ICT-DIVISION-B2-6');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
