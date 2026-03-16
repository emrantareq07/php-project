-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 12:47 PM
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
  `training_title` text NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authority_tbl`
--

INSERT INTO `authority_tbl` (`id`, `batch`, `training_title`, `organized_by`, `start_date`, `end_date`, `name1`, `designation1`, `office1`, `ministry1`, `signature1`, `name2`, `designation2`, `office2`, `ministry2`, `signature2`, `created_at`, `updated_at`) VALUES
(1, '1st', 'PPR1', '', '2025-08-26', '2025-08-31', 'test1', 'PD1', 'MOI', 'MOI', 'uploads/68b678f221253_signature 300_80.jpg', 'test2', 'PD2', 'MOI', 'MOI', 'uploads/68b678f22131b_signature 300_80.jpg', '2025-09-02 04:56:18', '2025-09-02'),
(2, '2nd', 'test3', '', '2025-09-01', '2025-09-02', 'test1', 'PD1', 'MOI', 'MOI', 'uploads/68b682abec2eb_signature 300_80.jpg', 'test2', 'PD2', 'MOI', 'MOI', 'uploads/68b682abec3d5_signature 300_80.jpg', '2025-09-02 05:37:47', '2025-09-02');

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
  `password` varchar(255) NOT NULL DEFAULT '1234',
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
(1, '6594-6', 'txt', 'Sub Assistant Engr.', 'test', 'test', 'BCIC H.O.', 'ICT', '01718834655', 'test3@yahoo.com', '1234', 'user', 'active', '1st', '2025-09-01 09:23:53', '2025-09-02 04:24:29'),
(2, 'admin', 'test', 'Sub Assistant Engr.', 'test', 'sdf', 'BCIC H.O.', 'ICT', '01718834655', 'test@yahoo.com', '1234', 'sadmin', 'active', '', '2025-09-01 09:29:36', '2025-09-02 03:18:16'),
(3, 'admin1', 'PD1', 'PD1', 'test', 'sdf', 'BCIC H.O.', '', '01718834655', 'pd1@yahoo.com', '1234', 'admin', 'active', '', '2025-09-02 03:36:55', '2025-09-02 03:37:18'),
(4, 'admin2', 'PD2', 'PD2', 'test', 'A', 'BCIC H.O.', '', '01718834655', 'pd2@yahoo.com', '1234', 'user', 'active', '1st', '2025-09-02 03:37:41', '2025-09-02 04:25:15'),
(5, '11111', '11111', '', '', '', '', '', '11111111', 'test4@yahoo.com', '1234', 'user', 'active', '2nd', '2025-09-02 08:22:03', '2025-09-02 09:51:04');

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`),
  ADD UNIQUE KEY `email_id` (`email_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authority_tbl`
--
ALTER TABLE `authority_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
