-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2026 at 09:08 AM
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
-- Database: `sfms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `buffer_transaction`
--

CREATE TABLE `buffer_transaction` (
  `id` int(11) NOT NULL,
  `import_allotment_id` int(11) DEFAULT NULL,
  `prod_allotment_id` int(11) DEFAULT NULL,
  `buffer_allotment_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `amount` float NOT NULL,
  `medium` text NOT NULL,
  `status` enum('pending','complete','','') NOT NULL DEFAULT 'pending',
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buffer_transaction`
--

INSERT INTO `buffer_transaction` (`id`, `import_allotment_id`, `prod_allotment_id`, `buffer_allotment_id`, `date`, `amount`, `medium`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 1, '2026-09-16', 100, 'truck', 'complete', 'gpfplc', '2026-09-16 14:27:40', '2026-09-16 08:28:43'),
(2, NULL, NULL, 2, '2026-09-16', 100, 'truck', 'complete', 'gpfplc', '2026-09-16 14:34:52', '2026-09-16 08:35:22'),
(3, NULL, NULL, 3, '2026-09-16', 100, 'truck', 'complete', 'gpfplc', '2026-09-16 15:01:19', '2026-09-16 09:02:04'),
(4, NULL, 4, NULL, '2026-09-16', 300, 'truck', 'complete', 'barisal_buffer', '2026-09-16 15:10:14', '2026-09-16 09:10:31'),
(5, 4, NULL, NULL, '2026-09-16', 10000, 'Truck', 'complete', 'chittagong_port', '2026-09-16 15:47:27', '2026-09-16 09:52:41'),
(6, 3, NULL, NULL, '2026-09-16', 10000, 'Lighter', 'complete', 'chittagong_port', '2026-09-16 15:47:42', '2026-09-16 09:48:27'),
(7, NULL, 5, NULL, '2026-09-16', 1000, 'truck', 'complete', 'barisal_buffer', '2026-09-16 15:50:54', '2026-09-16 09:52:39'),
(8, 2, NULL, NULL, '2026-09-16', 5000, 'Truck', 'pending', 'chittagong_port', '2026-09-16 15:58:28', '2026-09-16 09:58:28'),
(9, 8, NULL, NULL, '2026-09-17', 100, 'Truck', 'pending', 'chittagong_port', '2026-09-17 10:54:36', '2026-09-17 04:54:36'),
(10, 8, NULL, NULL, '2026-09-17', 400, 'Truck', 'pending', 'chittagong_port', '2026-09-17 10:55:00', '2026-09-17 04:55:00'),
(11, 7, NULL, NULL, '2026-09-17', 500, 'Truck', 'pending', 'chittagong_port', '2026-09-17 10:55:25', '2026-09-17 04:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `dealer_tbl`
--

CREATE TABLE `dealer_tbl` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nid` int(11) DEFAULT NULL,
  `mobile_no` int(11) NOT NULL,
  `address` text NOT NULL,
  `office_tbl_id` int(11) NOT NULL,
  `dealer_code` int(11) DEFAULT NULL,
  `status` enum('active','inactive','','') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dealer_tbl`
--

INSERT INTO `dealer_tbl` (`id`, `name`, `nid`, `mobile_no`, `address`, `office_tbl_id`, `dealer_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'x', 2147483647, 1234567891, 'Kaliganj, Jhenaidah', 1, 101, 'active', '2026-09-13 12:46:38', '2026-09-13 06:46:46'),
(2, 'Tareq Emran', 2147483647, 1234567891, 'Barisal', 3, 100, 'active', '2026-09-13 13:18:56', '2026-09-14 05:02:15'),
(3, 'Abul Hossain and co', 2147483647, 1234567891, 'Chapai', 6, 33, 'active', '2026-09-14 11:00:18', '2026-09-14 05:00:18'),
(4, 'M/S Akter Mia', 2147483647, 1234567891, 'Jamalpur', 7, 104, 'active', '2026-09-15 12:21:11', '2026-09-15 06:21:11'),
(5, 'M/S Karim', 2147483647, 1234567891, 'Narsingdi', 13, 1065, 'active', '2026-09-16 10:16:58', '2026-09-16 04:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `import_allotment`
--

CREATE TABLE `import_allotment` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `buffer_name` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `mdium` varchar(100) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `import_allotment`
--

INSERT INTO `import_allotment` (`id`, `ref_no`, `buffer_name`, `amount`, `mdium`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '21', 'barisal_buffer', 20000, 'Lighter', 'kopab', '2026-09-16 15:24:42', '2026-09-16 09:24:42'),
(2, '21', 'gpfplc', 20000, 'Truck', 'kopa', '2026-09-16 15:25:15', '2026-09-16 09:25:15'),
(3, 'kamp_office-22', 'barisal_buffer', 10000, 'Truck', 'kopab', '2026-09-16 15:28:41', '2026-09-16 09:28:41'),
(4, 'kamp_office-22', 'gpfplc', 10000, 'Truck', 'kopab', '2026-09-16 15:28:58', '2026-09-16 09:28:58'),
(5, 'kamp_office-26', 'Barisal Buffer', 200, 'Lighter', 'bcic_pur', '2026-09-17 10:15:03', '2026-09-17 04:15:24'),
(6, 'kamp_office-26', 'CUFL', 800, 'Truck', 'bcic_pur', '2026-09-17 10:24:33', '2026-09-17 04:24:33'),
(7, 'kamp_office-27', 'GPFPLC', 500, 'Truck', 'bcic_pur', '2026-09-17 10:36:47', '2026-09-17 04:36:47'),
(8, 'kamp_office-27', 'GPFPLC', 500, 'Truck', 'bcic_pur', '2026-09-17 10:39:25', '2026-09-17 04:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `import_urea`
--

CREATE TABLE `import_urea` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `shiping_date` date NOT NULL,
  `country` varchar(100) NOT NULL,
  `ship_name` varchar(255) NOT NULL,
  `contractor_name` varchar(255) NOT NULL,
  `port_arraival_date` date NOT NULL,
  `despatch_date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `pur_order_no` text NOT NULL,
  `port_name` varchar(200) NOT NULL,
  `status` enum('pending','partial','complete','') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `import_urea`
--

INSERT INTO `import_urea` (`id`, `ref_no`, `shiping_date`, `country`, `ship_name`, `contractor_name`, `port_arraival_date`, `despatch_date`, `quantity`, `pur_order_no`, `port_name`, `status`, `created_at`, `updated_at`) VALUES
(1, '21', '2026-09-16', 'dubai', 'ship 1', 'c1', '2026-09-16', '2026-09-16', 40000, '21', 'chittagong_port', '', '2026-09-16 15:23:10', '2026-09-17 04:42:30'),
(2, 'kamp_office-22', '2026-09-16', 'Saudi', 'ship 2', 'c2', '2026-09-16', '2026-09-16', 20000, 'kamp_office-22', 'chittagong_port', '', '2026-09-16 15:27:32', '2026-09-17 04:42:27'),
(3, 'kamp_office-26', '2026-09-17', 'Saudi', 'ship 3', 'c3', '2026-09-17', '0000-00-00', 1000, '', 'mongla_port', '', '2026-09-17 09:52:16', '2026-09-17 04:25:53'),
(4, 'kamp_office-27', '2026-09-17', 'russia', 'ship 4', 'c4', '2026-09-17', '0000-00-00', 1000, '', 'chittagong_port', 'pending', '2026-09-17 10:34:06', '2026-09-17 04:34:06');

-- --------------------------------------------------------

--
-- Table structure for table `kaliganj_buffer`
--

CREATE TABLE `kaliganj_buffer` (
  `id` int(11) NOT NULL,
  `buffer_name` varchar(100) NOT NULL DEFAULT 'kaliganj_buffer',
  `date` date NOT NULL,
  `receive_import` int(11) NOT NULL,
  `receive_factory` int(11) NOT NULL,
  `delivery` int(11) NOT NULL,
  `total_stock` int(11) NOT NULL,
  `pipeline` int(11) NOT NULL,
  `month_id` int(11) NOT NULL,
  `concat_receive` varchar(255) NOT NULL,
  `concat_delivery` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kaliganj_buffer`
--

INSERT INTO `kaliganj_buffer` (`id`, `buffer_name`, `date`, `receive_import`, `receive_factory`, `delivery`, `total_stock`, `pipeline`, `month_id`, `concat_receive`, `concat_delivery`) VALUES
(1, 'kaliganj_buffer', '2026-09-10', 120, 0, 41, 79, 0, 20263, '20+0+100+0+', '20+21+');

-- --------------------------------------------------------

--
-- Table structure for table `master_transaction`
--

CREATE TABLE `master_transaction` (
  `id` int(11) NOT NULL,
  `buffer_transaction_id` int(11) DEFAULT NULL,
  `dealer_id` int(11) NOT NULL,
  `transaction_source` enum('port_in','factory_in','factory_out','buffer_in','buffer_out') NOT NULL,
  `amount` float NOT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_transaction`
--

INSERT INTO `master_transaction` (`id`, `buffer_transaction_id`, `dealer_id`, `transaction_source`, `amount`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'factory_out', 100, 'from gpfplc', '2026-09-16 14:28:43', '2026-09-16 08:28:43'),
(2, 1, 0, 'buffer_in', 100, 'to barisal_buffer', '2026-09-16 14:28:43', '2026-09-16 08:28:43'),
(3, 2, 0, 'factory_out', 100, 'from gpfplc', '2026-09-16 14:35:22', '2026-09-16 08:35:22'),
(4, 2, 0, 'buffer_in', 100, 'to barisal_buffer', '2026-09-16 14:35:22', '2026-09-16 08:35:22'),
(5, 3, 0, 'factory_out', 100, 'from gpfplc', '2026-09-16 15:02:04', '2026-09-16 09:02:04'),
(6, 3, 0, 'buffer_in', 100, 'to barisal_buffer', '2026-09-16 15:02:04', '2026-09-16 09:02:04'),
(7, 4, 0, 'buffer_out', 300, 'from barisal_buffer', '2026-09-16 15:10:31', '2026-09-16 09:10:31'),
(8, 4, 0, 'factory_in', 300, 'to gpfplc', '2026-09-16 15:10:31', '2026-09-16 09:10:31'),
(9, 6, 0, 'port_in', 10000, '', '2026-09-16 15:48:27', '2026-09-16 09:48:27'),
(10, NULL, 2, 'buffer_out', 2000, 'delivery to dealer #2 (Tareq Emran) on 2026-09-16', '2026-09-16 15:49:47', '2026-09-16 09:49:47'),
(11, 7, 0, 'buffer_out', 1000, 'from barisal_buffer', '2026-09-16 15:52:39', '2026-09-16 09:52:39'),
(12, 7, 0, 'factory_in', 1000, 'to gpfplc', '2026-09-16 15:52:39', '2026-09-16 09:52:39'),
(13, 5, 0, 'port_in', 10000, '', '2026-09-16 15:52:41', '2026-09-16 09:52:41');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_demand`
--

CREATE TABLE `monthly_demand` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `office_name` varchar(100) NOT NULL,
  `demand_amount` int(11) NOT NULL,
  `month_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_demand`
--

INSERT INTO `monthly_demand` (`id`, `date`, `office_name`, `demand_amount`, `month_id`) VALUES
(11, '2024-06-01', 'kaliganj_buffer', 2000, 202412),
(12, '2024-06-01', 'shiromoni_buffer', 2000, 202412),
(13, '2026-09-10', 'kaliganj_buffer', 10000, 20263);

-- --------------------------------------------------------

--
-- Table structure for table `office_tbl`
--

CREATE TABLE `office_tbl` (
  `id` int(11) NOT NULL,
  `office_name` varchar(255) NOT NULL,
  `buffer_name` varchar(100) NOT NULL,
  `zone` varchar(200) NOT NULL,
  `office_type` enum('buffer_godown','factory_office','port_office','transit_godown','bcic_hq') NOT NULL,
  `address` text NOT NULL,
  `opening_bal` float NOT NULL,
  `capacity` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_tbl`
--

INSERT INTO `office_tbl` (`id`, `office_name`, `buffer_name`, `zone`, `office_type`, `address`, `opening_bal`, `capacity`, `created_at`, `updated_at`) VALUES
(1, 'Kaliganj Buffer', 'kaliganj_buffer', 'South Zone', 'buffer_godown', 'Kaliganj', 0, 0, '2026-09-12 12:10:02', '2026-09-13 10:21:15'),
(2, 'Jessore Buffer', 'jessore_buffer', 'South Zone', 'buffer_godown', 'Jessore', 0, 0, '2026-09-12 12:10:02', '2026-09-13 10:21:10'),
(3, 'Barisal Buffer', 'barisal_buffer', 'South Zone', 'buffer_godown', 'Barisal', 2000, 4000, '2026-09-13 13:11:47', '2026-09-16 07:38:38'),
(4, 'SFCL', 'sfcl', 'Factory Zone', 'factory_office', 'Sylhet', 3000, 2000, '2026-09-13 15:31:40', '2026-09-16 07:38:48'),
(5, 'Chittagong Port', 'chittagong_port', 'Chittagong', 'port_office', 'Chittagong dd', 0, 0, '2026-09-13 15:32:29', '2026-09-13 10:18:06'),
(6, 'Rajshahi Buffer', 'rajshahi_buffer', 'North Zone', 'buffer_godown', 'Rajshahi', 0, 0, '2026-09-13 16:20:08', '2026-09-13 10:20:08'),
(7, 'JFCL', 'jfcl', 'Factory Zone', 'factory_office', 'Jamalpur', 5000, 2000, '2026-09-14 09:43:41', '2026-09-16 07:37:56'),
(9, 'CUFL', 'cufl', 'Factory Zone', 'factory_office', 'Chittagong', 0, 0, '2026-09-14 10:30:59', '2026-09-14 04:30:59'),
(10, 'Kalurghat', 'kalurghat_transit', 'Transit Godown Zone', 'buffer_godown', 'Kalurghat, Chittagong', 0, 0, '2026-09-14 10:33:53', '2026-09-14 04:39:07'),
(12, 'KAFCO', 'kafco', 'KAFCO Zone', 'factory_office', 'Chittagong', 0, 0, '2026-09-14 11:10:16', '2026-09-14 05:10:16'),
(13, 'GPFPLC', 'gpfplc', 'Factory Zone', 'factory_office', 'GPFPLC, Narsingdi', 1000, 5000, '2026-09-16 10:10:46', '2026-09-16 07:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `production_tbl`
--

CREATE TABLE `production_tbl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(255) NOT NULL DEFAULT 'sfcl',
  `product_produce` varchar(50) NOT NULL DEFAULT 'Urea',
  `date` date NOT NULL,
  `daily_amount` float NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `yearly_target` float NOT NULL,
  `opening_bal` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_tbl`
--

INSERT INTO `production_tbl` (`id`, `factory_name`, `product_produce`, `date`, `daily_amount`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `yearly_target`, `opening_bal`, `created_at`, `updated_at`) VALUES
(1, 'gpfplc', 'Urea', '2026-09-16', 1000, 80, '', '580000', '580000', 0, 0, 0, '2026-09-16 13:40:03', '2026-09-17 06:41:04'),
(2, 'sfcl', 'Urea', '2026-09-16', 2000, 80, '', '580000', '580000', 0, 0, 0, '2026-09-16 13:40:03', '2026-09-16 07:40:28');

-- --------------------------------------------------------

--
-- Table structure for table `urea_allotment`
--

CREATE TABLE `urea_allotment` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `medium` enum('truck','wagon','','') NOT NULL,
  `status` enum('pending','complete','','') NOT NULL,
  `total_allot_amount` float NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `urea_allotment`
--

INSERT INTO `urea_allotment` (`id`, `ref_no`, `sender`, `receiver`, `amount`, `medium`, `status`, `total_allot_amount`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'gpfplc-01', 'gpfplc', 'barisal_buffer', 100, 'truck', 'complete', 500, 'gpfplc', '2026-09-16 13:41:36', '2026-09-16 08:27:40'),
(2, 'gpfplc-01', 'gpfplc', 'barisal_buffer', 100, 'truck', 'complete', 500, 'gpfplc', '2026-09-16 14:32:10', '2026-09-16 08:34:52'),
(3, 'gpfplc-01', 'gpfplc', 'barisal_buffer', 100, 'truck', 'complete', 500, 'gpfplc', '2026-09-16 15:01:07', '2026-09-16 09:01:19'),
(4, 'barisal-001', 'barisal_buffer', 'gpfplc', 300, 'truck', 'complete', 300, 'barisal_buffer', '2026-09-16 15:10:10', '2026-09-16 09:10:14'),
(5, 'barisal-002', 'barisal_buffer', 'gpfplc', 1000, 'truck', 'complete', 1000, 'barisal_buffer', '2026-09-16 15:50:50', '2026-09-16 09:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `office_tbl_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('user','admin','sadmin','') NOT NULL DEFAULT 'user',
  `office_type` varchar(100) NOT NULL,
  `division` varchar(255) NOT NULL,
  `office_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `office_tbl_id`, `username`, `password`, `user_type`, `office_type`, `division`, `office_name`, `email`, `full_name`, `designation`, `mobile_no`, `created_at`, `updated_at`) VALUES
(1, 0, 'shiromoni_buffer', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'buffer', '', '', '', '', '', '', '2024-05-30 14:49:12', '2026-09-10 06:15:18'),
(2, 0, 'sfcl', '$2y$10$piHdQUJZGHlgf7lsitiXVOWx9skGoc8WF759GmZvq3nWhlsO8uyK6', 'user', 'factory_office', '', '', '', '', '', '', '2024-05-27 14:39:41', '2026-09-14 09:19:44'),
(3, 0, 'gpfplc', '$2y$10$D06lpIN7.KvTvxqk/V0heOhgCi8tJ1qR468Is65SUGOiPxJRBWdqe', 'user', 'factory_office', '', '', '', '', '', '', '2024-06-01 23:19:24', '2026-09-14 04:25:32'),
(4, 0, 'sadmin', '$2y$10$WzbJLzxN3Bkq3Ta5JvMQ2ummeBsSoqrzckoSrQrP8bnmH77ooeOGq', 'sadmin', 'bcic_hq', '', '', '', '', '', '', '2024-05-25 13:21:42', '2026-09-14 04:11:43'),
(12, 0, 'bcic_mkt', '$2y$10$molBVE0XiGEqRlOFpXyfF.gvxDF9k2jP9tj9SiwLa3R/dw.UZggIa', 'user', 'bcic_hq', '', '', 'user@yahoo.com', '', '', '', '2024-06-01 12:48:45', '2026-09-14 04:24:59'),
(13, 1, 'kaliganj_buffer', '$2y$10$YEL1NIhTmFVd0CUZoLcBWeyVDaGE5FKFJ0QzU15NsYZSM4siwC7cK', 'user', 'buffer_godown', '', 'Kaliganj Buffer', 'kaliganj_buffer@yahoo.com', 'Owhid', 'Deputy Manager (Commercial)', '01234567891', '2024-05-25 13:16:37', '2026-09-14 04:24:21'),
(14, 0, 'admin', '$2y$10$WglKrBO2qZJbeG4ve.XzeeMrLGAIkMYmiOuu4qZzeEvfnrz4JIyMa', 'admin', 'bcic_hq', '', '', 'admin@yahoo.com', '', '', '', '2024-05-25 20:48:37', '2026-09-15 08:47:52'),
(15, 0, 'mongla_port', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', '', '', 'monglaport@yahoo.com', '', '', '', '2024-06-01 00:39:31', '2026-09-10 06:15:18'),
(16, 0, 'chittagong_port', '$2y$10$6hewAJ656k0zoPziMVmRBego2AwrfN/BOeaxXTjSOdzNj.ZNo6v46', 'user', 'port_office', '', '', 'chittagonj_port@yahoo.com', '', '', '', '2024-05-30 13:12:50', '2026-09-14 04:25:15'),
(17, 0, 'bcic_pur', '$2y$10$jzNzmObomiLt2gDSEu0YY.uiO5vym6RQytoIVNNcS0rAlumjQS.zO', 'user', 'bcic_hq', '', '', 'pur@yahoo.com', '', '', '', '2026-09-10 15:35:15', '2026-09-14 04:26:07'),
(18, 6, 'rajshahi_buffer', '$2y$10$vmCmz7ptFwZyzzPQRezkxOWjXQPECPIaWVJ9Q64Hv9hjguZRHxrMO', 'user', 'buffer_godown', 'Rajshahi', 'Rajshahi Buffer', '', '', '', '', '2026-09-13 17:19:39', '2026-09-14 04:26:22'),
(19, 7, 'jfcl', '$2y$10$eanOJ/lYrLsGr5E12puGIegFjeUxsLmPWLfbZArh6GKf77R5lsPLu', 'user', 'factory_office', '', 'JFCL', 'jfcl@yahoo.com', 'x', '', '', '2026-09-14 09:43:41', '2026-09-14 04:31:12'),
(20, 9, 'cufl', '$2y$10$87H0cHl02t89zsiEtrIpTu5Ff2sBzuYClZ.UWY0cmM8DBFurZvGIW', 'user', 'factory_office', '', 'CUFL', '', 'CUFL', '', '', '2026-09-14 10:30:59', '2026-09-14 04:30:59'),
(21, 10, 'kalurghat_transit', '$2y$10$qrXmrvWiTh6TNgQTHj88t.FPneX9fd8OOULeWxD/DDFwk.4UNMwjS', 'user', 'buffer_godown', '', 'Kalurghat', 'kalurghat_transit@yahoo.com', 'X', 'Assistant Manager', '1234567891', '2026-09-14 10:33:53', '2026-09-14 04:40:12'),
(22, 12, 'kafco', '$2y$10$tnx/YtL.fojZKdVuPwifuOpfPqD9CXdepnG7ofmtrGzZgSOpZBu8y', 'user', 'factory_office', '', 'KAFCO', '', '', '', '', '2026-09-14 11:10:16', '2026-09-14 05:10:16'),
(23, 3, 'barisal_buffer', '$2y$10$/GStGPWJE4YBFsijAEDRAu4A2kFtQCWEwpmaNsroaHSDlJIyp4gQu', 'user', 'buffer_godown', '', 'Barisal Buffer', '', '', '', '', '2026-09-14 11:14:43', '2026-09-14 05:15:10'),
(24, 0, 'user', '$2y$10$SoWpKOQPaJsCDN9MSSj4Q.Qrb/eVhXpm8x7aSAPpibTNYHMZ3AaFe', 'user', 'bcic_hq', '', 'BCIC', '', 'BCIC', '', '', '2026-09-15 14:51:31', '2026-09-15 08:54:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buffer_transaction`
--
ALTER TABLE `buffer_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dealer_tbl`
--
ALTER TABLE `dealer_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `import_allotment`
--
ALTER TABLE `import_allotment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `import_urea`
--
ALTER TABLE `import_urea`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kaliganj_buffer`
--
ALTER TABLE `kaliganj_buffer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_transaction`
--
ALTER TABLE `master_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_demand`
--
ALTER TABLE `monthly_demand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_tbl`
--
ALTER TABLE `office_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_tbl`
--
ALTER TABLE `production_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urea_allotment`
--
ALTER TABLE `urea_allotment`
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
-- AUTO_INCREMENT for table `buffer_transaction`
--
ALTER TABLE `buffer_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dealer_tbl`
--
ALTER TABLE `dealer_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `import_allotment`
--
ALTER TABLE `import_allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `import_urea`
--
ALTER TABLE `import_urea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kaliganj_buffer`
--
ALTER TABLE `kaliganj_buffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master_transaction`
--
ALTER TABLE `master_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `monthly_demand`
--
ALTER TABLE `monthly_demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `office_tbl`
--
ALTER TABLE `office_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `production_tbl`
--
ALTER TABLE `production_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `urea_allotment`
--
ALTER TABLE `urea_allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
