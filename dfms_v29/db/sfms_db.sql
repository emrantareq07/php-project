-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2026 at 02:13 PM
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
-- Table structure for table `barisal_buffer`
--

CREATE TABLE `barisal_buffer` (
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
(2, 9, NULL, NULL, '2026-09-12', 300, 'Truck', 'complete', 'chittagonj_port', '2026-09-12 13:14:58', '2026-09-12 09:11:16'),
(3, 7, NULL, NULL, '2026-09-12', 200, 'Truck', 'pending', 'chittagonj_port', '2026-09-12 13:24:34', '2026-09-12 07:24:34'),
(5, NULL, 1, NULL, '2026-09-13', 1000, 'truck', 'complete', 'bcic_mkt', '2026-09-13 16:58:29', '2026-09-13 12:05:18'),
(6, NULL, NULL, 2, '2026-09-13', 100, 'truck', 'complete', 'bcic_mkt', '2026-09-13 17:16:33', '2026-09-13 12:06:24');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dealer_tbl`
--

INSERT INTO `dealer_tbl` (`id`, `name`, `nid`, `mobile_no`, `address`, `office_tbl_id`, `dealer_code`, `created_at`, `updated_at`) VALUES
(1, 'x', 2147483647, 1234567891, 'Kaliganj, Jhenaidah', 1, 101, '2026-09-13 12:46:38', '2026-09-13 06:46:46'),
(2, 'Tareq Emran', 2147483647, 1234567891, 'Kaliganj, Jhenaidah', 3, 100, '2026-09-13 13:18:56', '2026-09-13 07:18:56');

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
(1, 'kamp_office-21', 'barisal_buffer', 3300, 'Lighter', 'kopa', '2026-09-12 11:28:57', '2026-09-12 05:30:33'),
(4, 'kamp_office-21', 'gopalganj_buffer', 2200, 'Lighter', 'kopa', '2026-09-12 11:31:06', '2026-09-12 05:31:06'),
(5, 'kamp_office-21', 'jessore_buffer', 7700, 'Lighter', 'kopa', '2026-09-12 11:31:57', '2026-09-12 05:31:57'),
(6, 'kamp_office-21', 'rajshahi_buffer', 3300, 'Lighter', 'kopa', '2026-09-12 11:32:14', '2026-09-12 05:32:14'),
(7, 'kamp_office-21', 'teherhat_buffer', 2200, 'Lighter', 'kopa', '2026-09-12 11:32:29', '2026-09-12 05:32:29'),
(9, 'kamp_office-21', 'kaliganj_buffer', 25300, 'Lighter', 'kopa', '2026-09-12 11:40:09', '2026-09-12 05:40:09');

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
(1, 'kamp_office-21', '2026-09-11', 'dubai', 'ship 1', 'c1', '2026-09-12', '0000-00-00', 44000, 'kamp_office-21', 'chittagonj_port', 'pending', '2026-09-12 11:07:43', '2026-09-12 05:07:43');

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
  `transaction_source` enum('port_in','factory_in','factory_out','buffer_in','buffer_out','opening_in') NOT NULL,
  `amount` float NOT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_transaction`
--

INSERT INTO `master_transaction` (`id`, `buffer_transaction_id`, `transaction_source`, `amount`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, '', 300, '', '2026-09-12 15:11:16', '2026-09-12 09:11:16'),
(2, NULL, 'factory_in', 2000, '0', '2026-09-13 00:00:00', '2026-09-13 00:09:23'),
(3, 5, 'factory_out', 1000, 'from sfcl', '2026-09-13 18:05:18', '2026-09-13 12:05:18'),
(4, 5, 'buffer_in', 1000, 'to rajshahi_buffer', '2026-09-13 18:05:18', '2026-09-13 12:05:18'),
(5, 6, 'buffer_out', 100, 'from kaliganj_buffer', '2026-09-13 18:06:24', '2026-09-13 12:06:24'),
(6, 6, 'buffer_in', 100, 'to rajshahi_buffer', '2026-09-13 18:06:24', '2026-09-13 12:06:24');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_tbl`
--

INSERT INTO `office_tbl` (`id`, `office_name`, `buffer_name`, `zone`, `office_type`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Kaliganj Buffer', 'kaliganj_buffer', 'South Zone', 'buffer_godown', 'Kaliganj', '2026-09-12 12:10:02', '2026-09-13 10:21:15'),
(2, 'Jessore Buffer', 'jessore_buffer', 'South Zone', 'buffer_godown', 'Jessore', '2026-09-12 12:10:02', '2026-09-13 10:21:10'),
(3, 'Barisal Buffer', 'barisal_buffer', 'South Zone', 'buffer_godown', 'Barisal', '2026-09-13 13:11:47', '2026-09-13 10:20:59'),
(4, 'SFCL', 'sfcl', 'Factory Zone', 'factory_office', 'Sylhet', '2026-09-13 15:31:40', '2026-09-13 10:20:45'),
(5, 'Chittagong Port', 'chittagong_port', 'Chittagong', 'port_office', 'Chittagong dd', '2026-09-13 15:32:29', '2026-09-13 10:18:06'),
(6, 'Rajshahi Buffer', 'rajshahi_buffer', 'North Zone', 'buffer_godown', 'Rajshahi', '2026-09-13 16:20:08', '2026-09-13 10:20:08');

-- --------------------------------------------------------

--
-- Table structure for table `pipeline`
--

CREATE TABLE `pipeline` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `from_office` varchar(50) NOT NULL,
  `to_office` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pipeline`
--

INSERT INTO `pipeline` (`id`, `date`, `from_office`, `to_office`, `amount`, `status`) VALUES
(1, '2026-09-10', 'chittagong_port', 'kaliganj_buffer', 100, 'pending'),
(2, '2026-09-10', 'chittagong_port', 'kaliganj_buffer', 100, 'accept'),
(3, '2026-09-10', 'chittagonj_port', 'kaliganj_buffer', 20, 'accept'),
(4, '2026-09-10', 'chittagonj_port', 'kaliganj_buffer', 100, 'pending');

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
(205, 'gpfplc', 'Urea', '2026-09-12', 2000, 80, '', '580000', '580000', 0, 0, 0, '2026-09-13 10:36:45', '2026-09-13 04:42:13'),
(206, 'gpfplc', 'Urea', '0000-00-00', 0, 0, '', '580000', '580000', 0, 0, 10000, '2026-09-13 10:37:13', '2026-09-13 04:37:13'),
(207, 'gpfplc', 'Urea', '2026-09-13', 2200, 80, '', '580000', '580000', 0, 0, 0, '2026-09-13 10:42:26', '2026-09-13 04:59:23'),
(208, 'sfcl', 'Urea', '2026-09-13', 1100, 60, '', '580000', '580000', 0, 0, 0, '2026-09-13 11:01:34', '2026-09-13 05:01:34'),
(209, 'sfcl', 'Urea', '0000-00-00', 0, 0, '', '580000', '580000', 0, 0, 5000, '2026-09-13 11:01:51', '2026-09-13 05:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `shiromoni_buffer`
--

CREATE TABLE `shiromoni_buffer` (
  `id` int(11) NOT NULL,
  `buffer_name` varchar(100) NOT NULL DEFAULT 'shiromoni_buffer',
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

-- --------------------------------------------------------

--
-- Table structure for table `stock_mgtm`
--

CREATE TABLE `stock_mgtm` (
  `id` int(11) NOT NULL,
  `buffer_name` varchar(100) NOT NULL,
  `stock_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `urea_allotment`
--

INSERT INTO `urea_allotment` (`id`, `ref_no`, `sender`, `receiver`, `amount`, `medium`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '597', 'sfcl', 'rajshahi_buffer', 1000, 'truck', 'complete', 'bcic_mkt', '2026-09-13 16:23:56', '2026-09-13 11:15:39'),
(2, '001', 'kaliganj_buffer', 'rajshahi_buffer', 100, 'truck', 'complete', 'bcic_mkt', '2026-09-13 17:16:20', '2026-09-13 11:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `office_tbl_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL,
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
(2, 0, 'sfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'factory_office', '', '', '', '', '', '', '2024-05-27 14:39:41', '2026-09-13 05:00:25'),
(3, 0, 'gpfplc', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'factory_office', '', '', '', '', '', '', '2024-06-01 23:19:24', '2026-09-13 03:56:37'),
(4, 0, 'sadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', 'bcic_hq', '', '', '', '', '', '', '2024-05-25 13:21:42', '2026-09-10 06:15:18'),
(12, 0, 'bcic_mkt', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'bcic_hq', '', '', 'user@yahoo.com', '', '', '', '2024-06-01 12:48:45', '2026-09-10 06:15:18'),
(13, 0, 'kaliganj_buffer', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'buffer_godown', '', '', 'kaliganj_buffer@yahoo.com', '', '', '', '2024-05-25 13:16:37', '2026-09-12 07:35:54'),
(14, 0, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', 'bcic_hq', '', '', 'admin@yahoo.com', '', '', '', '2024-05-25 20:48:37', '2026-09-10 06:15:18'),
(15, 0, 'mongla_port', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', '', '', 'monglaport@yahoo.com', '', '', '', '2024-06-01 00:39:31', '2026-09-10 06:15:18'),
(16, 0, 'chittagong_port', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', '', '', 'chittagonj_port@yahoo.com', '', '', '', '2024-05-30 13:12:50', '2026-09-13 09:40:37'),
(17, 0, 'bcic_pur', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'bcic_hq', '', '', 'pur@yahoo.com', '', '', '', '2026-09-10 15:35:15', '2026-09-10 09:38:21'),
(18, 6, 'rajshahi_buffer', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'buffer_godown', 'Rajshahi', 'Rajshahi Buffer', '', '', '', '', '2026-09-13 17:19:39', '2026-09-13 11:19:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barisal_buffer`
--
ALTER TABLE `barisal_buffer`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `pipeline`
--
ALTER TABLE `pipeline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_tbl`
--
ALTER TABLE `production_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shiromoni_buffer`
--
ALTER TABLE `shiromoni_buffer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_mgtm`
--
ALTER TABLE `stock_mgtm`
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
-- AUTO_INCREMENT for table `barisal_buffer`
--
ALTER TABLE `barisal_buffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buffer_transaction`
--
ALTER TABLE `buffer_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dealer_tbl`
--
ALTER TABLE `dealer_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `import_allotment`
--
ALTER TABLE `import_allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `import_urea`
--
ALTER TABLE `import_urea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kaliganj_buffer`
--
ALTER TABLE `kaliganj_buffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master_transaction`
--
ALTER TABLE `master_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `monthly_demand`
--
ALTER TABLE `monthly_demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `office_tbl`
--
ALTER TABLE `office_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pipeline`
--
ALTER TABLE `pipeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `production_tbl`
--
ALTER TABLE `production_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `shiromoni_buffer`
--
ALTER TABLE `shiromoni_buffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_mgtm`
--
ALTER TABLE `stock_mgtm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urea_allotment`
--
ALTER TABLE `urea_allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
