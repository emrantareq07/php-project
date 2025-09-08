-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 12:48 PM
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
-- Database: `work_request_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `work_request_items`
--

CREATE TABLE `work_request_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `item` varchar(100) NOT NULL,
  `item_desc` text DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_request_items`
--

INSERT INTO `work_request_items` (`id`, `request_id`, `item`, `item_desc`, `remarks`) VALUES
(1, 1, 'CPU', 'dfg', 'ff'),
(2, 1, 'Monitor', 'dfsd', 'dsfsd'),
(3, 2, 'CPU', 'dfg', 'ff'),
(4, 3, 'CPU', 'test', ''),
(5, 3, 'CPU', 'test', '');

-- --------------------------------------------------------

--
-- Table structure for table `work_request_tbl`
--

CREATE TABLE `work_request_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `requested_type` enum('Civil','MTS','ICT') NOT NULL,
  `extra_item` text DEFAULT NULL,
  `status` enum('pending','complete') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_request_tbl`
--

INSERT INTO `work_request_tbl` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `mobile_no`, `email_id`, `requested_type`, `extra_item`, `status`, `created_at`, `updated_at`) VALUES
(1, '6594-6', 'test', 'test', 'test', 'test', 'test', 'test@yahoo.com', 'Civil', '', 'pending', '2025-08-28 10:18:33', '2025-08-28 10:18:33'),
(2, '6898-1', 'test', 'Sub Assistant Engr.', 'test', 'sdf', '', 'test@yahoo.com', 'Civil', 't', 'pending', '2025-08-28 10:28:33', '2025-08-28 10:28:33'),
(3, '6826-2', 'test', 'Sub Assistant Engr.', 'test', 'sdf', '1312341241243124', 'test@yahoo.com', 'ICT', 'test', 'pending', '2025-08-28 10:33:58', '2025-08-28 10:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `work_request_tbl_old`
--

CREATE TABLE `work_request_tbl_old` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `requested_type` varchar(100) NOT NULL,
  `requested_item` varchar(255) NOT NULL,
  `item_desc` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `extra_item` text DEFAULT NULL,
  `status` enum('pending','complete') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_request_tbl_old`
--

INSERT INTO `work_request_tbl_old` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `mobile_no`, `email_id`, `requested_type`, `requested_item`, `item_desc`, `remarks`, `extra_item`, `status`, `created_at`, `updated_at`) VALUES
(1, '6594-6', 'test', 'Sub Assistant Engr.', 'test', 'test', '1312341241243124', 'test@yahoo.com', '', 'test', 'test', 'test', 'test', 'pending', '2025-08-28 08:56:11', '2025-08-28 08:56:11'),
(2, '6594-6', 'test', 'Sub Assistant Engr.', 'test', 'test', '1312341241243124', 'test@yahoo.com', '', 'test', 'test', 'test', 'test', 'pending', '2025-08-28 08:56:55', '2025-08-28 08:56:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `work_request_items`
--
ALTER TABLE `work_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `work_request_tbl`
--
ALTER TABLE `work_request_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_request_tbl_old`
--
ALTER TABLE `work_request_tbl_old`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `work_request_items`
--
ALTER TABLE `work_request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `work_request_tbl`
--
ALTER TABLE `work_request_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `work_request_tbl_old`
--
ALTER TABLE `work_request_tbl_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `work_request_items`
--
ALTER TABLE `work_request_items`
  ADD CONSTRAINT `work_request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `work_request_tbl` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
