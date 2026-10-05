-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2026 at 07:51 AM
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
(1, 1, NULL, NULL, '2026-09-26', 223, 'Truck, Driver name: Y, Mobile: 789456123', 'pending', 'chittagong_port', '2026-09-26 17:15:02', '2026-09-26 11:15:02'),
(2, 3, NULL, NULL, '2026-09-26', 200, 'Truck, Driver name: Y, Mobile: 789456123', 'pending', 'chittagong_port', '2026-09-26 17:16:17', '2026-09-26 11:16:17'),
(3, 3, NULL, NULL, '2026-09-26', 259, 'Truck, Driver name: Y, Mobile: 789456123', 'pending', 'chittagong_port', '2026-09-26 17:17:19', '2026-09-26 11:17:19'),
(4, 4, NULL, NULL, '2026-09-26', 183, 'Truck, Driver name: Y, Mobile: 789456123', 'pending', 'chittagong_port', '2026-09-26 17:17:32', '2026-09-26 11:17:32'),
(5, 5, NULL, NULL, '2026-09-26', 560, 'Truck, Driver name: Y, Mobile: 789456123', 'pending', 'chittagong_port', '2026-09-26 17:17:46', '2026-09-26 11:17:46'),
(6, 6, NULL, NULL, '2026-09-26', 240, 'Truck, Driver name: Y, Mobile: 789456123', 'complete', 'chittagong_port', '2026-09-26 17:17:57', '2026-09-26 12:27:47'),
(7, 4, NULL, NULL, '2026-09-26', 100, 'Truck', 'pending', 'chittagong_port', '2026-09-26 18:12:47', '2026-09-26 12:12:47'),
(8, NULL, NULL, 1, '2026-09-26', 240, 'truck', 'pending', 'sfcl', '2026-09-26 18:30:47', '2026-09-26 12:30:47'),
(9, 4, NULL, NULL, '2026-09-27', 4117, 'Truck, Driver Name: Z, Mobile No: 123456789', 'pending', 'chittagong_port', '2026-09-27 10:15:53', '2026-09-27 04:15:53');

-- --------------------------------------------------------

--
-- Table structure for table `comparison_tbl`
--

CREATE TABLE `comparison_tbl` (
  `id` int(11) NOT NULL,
  `yearly_demand` float NOT NULL,
  `addition` float NOT NULL,
  `fiscal_year` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comparison_tbl`
--

INSERT INTO `comparison_tbl` (`id`, `yearly_demand`, `addition`, `fiscal_year`) VALUES
(1, 2620000, 2900, '2026-2027');

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
(1, 'bcic-pur-001', 'rajshahi_buffer', 4400, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-26 17:05:11', '2026-09-26 12:10:20'),
(2, 'bcic-pur-001', 'pabna_buffer', 4400, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-26 17:05:42', '2026-09-26 12:10:37'),
(3, 'bcic-pur-001', 'kaliganj_buffer', 4400, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-26 17:05:55', '2026-09-26 12:10:29'),
(4, 'bcic-pur-001', 'barisal_buffer', 4400, 'Lighter', 'bcic_pur', '2026-09-26 17:06:09', '2026-09-26 12:10:46'),
(5, 'bcic-pur-001', 'cufl', 4400, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-26 17:06:47', '2026-09-26 12:11:04'),
(6, 'bcic-pur-001', 'sfcl', 4400, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-26 17:07:01', '2026-09-26 12:11:17'),
(7, 'bcic-pur-001', 'jfcl', 4400, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-26 17:07:12', '2026-09-26 12:11:10'),
(8, 'bcic-pur-001', 'gpfplc', 4500, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-26 17:07:29', '2026-09-26 12:11:23'),
(9, 'bcic-pur-001', 'kalurghat_transit', 4500, 'Lighter', 'bcic_pur', '2026-09-26 17:07:57', '2026-09-26 12:11:37');

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
  `bl_quantity` float NOT NULL,
  `pur_order_no` text NOT NULL,
  `port_name` varchar(200) NOT NULL,
  `status` enum('pending','partial','complete','') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `import_urea`
--

INSERT INTO `import_urea` (`id`, `ref_no`, `shiping_date`, `country`, `ship_name`, `contractor_name`, `port_arraival_date`, `despatch_date`, `quantity`, `bl_quantity`, `pur_order_no`, `port_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'bcic-pur-001', '2026-09-22', 'dubai', 'MD. Euro Band', 'Summit', '2026-09-25', '0000-00-00', 40000, 38000, '', 'chittagong_port', 'pending', '2026-09-26 17:03:31', '2026-09-26 11:03:31');

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
  `date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_transaction`
--

INSERT INTO `master_transaction` (`id`, `buffer_transaction_id`, `dealer_id`, `transaction_source`, `amount`, `remarks`, `date`, `created_at`, `updated_at`) VALUES
(1, 6, 0, 'port_in', 240, 'to sfcl', NULL, '2026-09-26 18:27:47', '2026-09-26 12:27:47'),
(2, 8, 0, 'factory_out', 240, 'from sfcl', NULL, '2026-09-26 18:30:47', '2026-09-26 12:30:47'),
(3, NULL, 2, 'buffer_out', 100, 'delivery to dealer #2 (Tareq Emran) on 2026-09-27', NULL, '2026-09-27 10:45:57', '2026-09-27 04:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_demand`
--

CREATE TABLE `monthly_demand` (
  `id` int(11) NOT NULL,
  `office_tbl_id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `d_amount` float NOT NULL,
  `addition` float NOT NULL,
  `substration` float NOT NULL,
  `actual_d_amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_demand`
--

INSERT INTO `monthly_demand` (`id`, `office_tbl_id`, `ref_no`, `date`, `d_amount`, `addition`, `substration`, `actual_d_amount`, `created_at`, `updated_at`) VALUES
(2, 3, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(3, 5, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(4, 9, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(5, 13, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(6, 2, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(7, 7, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(8, 12, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(9, 1, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(10, 10, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(11, 6, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(12, 4, 'buffer and factory-01', '2026-09-22', 10, 0, 0, 0, '2026-09-22 11:23:29', '2026-09-22 05:23:29'),
(13, 3, 'buffer-factory-001', '2026-08-20', 30, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(14, 5, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(15, 9, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(16, 13, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(17, 2, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(18, 7, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(19, 12, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(20, 1, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(21, 10, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(22, 6, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25'),
(23, 4, 'buffer-factory-001', '2026-08-20', 20, 0, 0, 0, '2026-09-22 11:37:43', '2026-09-22 05:40:25');

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
  `yearly_target` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_tbl`
--

INSERT INTO `office_tbl` (`id`, `office_name`, `buffer_name`, `zone`, `office_type`, `address`, `opening_bal`, `capacity`, `yearly_target`, `created_at`, `updated_at`) VALUES
(1, 'Kaliganj Buffer', 'kaliganj_buffer', 'South Zone', 'buffer_godown', 'Kaliganj', 6571, 8000, 0, '2026-09-12 12:10:02', '2026-09-26 10:39:29'),
(2, 'Jessore Buffer', 'jessore_buffer', 'South Zone', 'buffer_godown', 'Jessore', 0, 0, 0, '2026-09-12 12:10:02', '2026-09-13 10:21:10'),
(3, 'Barisal Buffer', 'barisal_buffer', 'South Zone', 'buffer_godown', 'Barisal', 3742, 3000, 0, '2026-09-13 13:11:47', '2026-09-26 10:38:54'),
(4, 'SFCL', 'sfcl', 'Factory Zone', 'factory_office', 'Sylhet', 32785, 18000, 0, '2026-09-13 15:31:40', '2026-09-26 10:55:03'),
(5, 'Chittagong Port', 'chittagong_port', 'Chittagong', 'port_office', 'Chittagong dd', 0, 0, 0, '2026-09-13 15:32:29', '2026-09-13 10:18:06'),
(6, 'Rajshahi Buffer', 'rajshahi_buffer', 'North Zone', 'buffer_godown', 'Rajshahi', 10551, 8000, 0, '2026-09-13 16:20:08', '2026-09-26 10:40:15'),
(7, 'JFCL', 'jfcl', 'Factory Zone', 'factory_office', 'Jamalpur', 6447, 15000, 0, '2026-09-14 09:43:41', '2026-09-26 10:54:43'),
(9, 'CUFL', 'cufl', 'Factory Zone', 'factory_office', 'Chittagong', 5744, 20000, 0, '2026-09-14 10:30:59', '2026-09-26 10:55:24'),
(10, 'Kalurghat', 'kalurghat_transit', 'Transit Godown Zone', 'buffer_godown', 'Kalurghat, Chittagong', 12869, 50000, 0, '2026-09-14 10:33:53', '2026-09-26 10:43:02'),
(12, 'KAFCO', 'kafco', 'KAFCO Zone', 'factory_office', 'Chittagong', 0, 0, 0, '2026-09-14 11:10:16', '2026-09-26 10:37:12'),
(13, 'GPFPLC', 'gpfplc', 'Factory Zone', 'factory_office', 'GPFPLC, Narsingdi', 87075, 22000, 0, '2026-09-16 10:10:46', '2026-09-26 10:54:06'),
(14, 'Pabna Buffer', 'pabna_buffer', 'North Zone', 'buffer_godown', 'Pabna', 19565, 10000, 0, '2026-09-26 16:41:27', '2026-09-26 10:42:08');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_tbl`
--

INSERT INTO `production_tbl` (`id`, `factory_name`, `product_produce`, `date`, `daily_amount`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `created_at`, `updated_at`) VALUES
(1, 'sfcl', 'Urea', '2026-09-25', 1331, 80, '', '580000', '580000', 0, '2026-09-26 16:59:53', '2026-09-26 12:22:02'),
(2, 'sfcl', 'Urea', '2026-09-26', 1000, 80, '', '580000', '580000', 0, '2026-09-26 18:22:10', '2026-09-26 12:22:10');

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
(1, 'sfcl-01', 'sfcl', 'barisal_buffer', 240, 'truck', 'complete', 240, 'sfcl', '2026-09-26 18:30:44', '2026-09-26 12:30:47');

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
(24, 0, 'user', '$2y$10$SoWpKOQPaJsCDN9MSSj4Q.Qrb/eVhXpm8x7aSAPpibTNYHMZ3AaFe', 'user', 'bcic_hq', '', 'BCIC', '', 'BCIC', '', '', '2026-09-15 14:51:31', '2026-09-15 08:54:55'),
(25, 14, 'pabna_buffer', '$2y$10$0oOIdB5GDz5xYc6TMqgI0O1ssGNLKliVDzX67VUV9VoM/GG44q8rq', 'user', 'buffer_godown', '', 'Pabna Buffer', '', '', '', '', '2026-09-26 16:41:27', '2026-09-26 10:41:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buffer_transaction`
--
ALTER TABLE `buffer_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comparison_tbl`
--
ALTER TABLE `comparison_tbl`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comparison_tbl`
--
ALTER TABLE `comparison_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dealer_tbl`
--
ALTER TABLE `dealer_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `monthly_demand`
--
ALTER TABLE `monthly_demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `office_tbl`
--
ALTER TABLE `office_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `production_tbl`
--
ALTER TABLE `production_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `urea_allotment`
--
ALTER TABLE `urea_allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
