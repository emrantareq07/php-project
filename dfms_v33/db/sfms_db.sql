-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2026 at 02:05 PM
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
(9, 4, NULL, NULL, '2026-09-27', 4117, 'Truck, Driver Name: Z, Mobile No: 123456789', 'pending', 'chittagong_port', '2026-09-27 10:15:53', '2026-09-27 04:15:53'),
(10, NULL, NULL, 3, '2026-09-28', 100, 'truck', 'pending', 'cufl', '2026-09-28 14:05:33', '2026-09-28 08:05:33');

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
(9, 'bcic-pur-001', 'kalurghat_transit', 4500, 'Lighter', 'bcic_pur', '2026-09-26 17:07:57', '2026-09-26 12:11:37'),
(10, 'bcic-pur-001', 'jfcl', 200, 'Truck, Driver Name: X, Mobile No: 123456789', 'bcic_pur', '2026-09-27 14:52:11', '2026-09-27 08:52:11'),
(11, 'Transit-9090', 'cufl', 111, 'Lighter', 'bcic_pur', '2026-09-27 15:30:13', '2026-09-27 09:30:13'),
(12, 'transite-693', 'sfcl', 200, 'Truck', 'bcic_pur', '2026-09-28 13:29:18', '2026-09-28 07:29:18'),
(13, 'Transit-345', 'barisal_buffer', 100, 'fsfsdfsf\r\nrfgdfgf\r\ndsfsdf', 'bcic_pur', '2026-09-28 13:31:09', '2026-09-28 07:31:09');

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
  `status` enum('pending','overseas','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `import_urea`
--

INSERT INTO `import_urea` (`id`, `ref_no`, `shiping_date`, `country`, `ship_name`, `contractor_name`, `port_arraival_date`, `despatch_date`, `quantity`, `bl_quantity`, `pur_order_no`, `port_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'bcic-pur-001', '2026-09-22', 'dubai', 'MD. Euro Band', 'Summit', '2026-09-25', '0000-00-00', 40000, 38000, '', 'chittagong_port', 'completed', '2026-09-26 17:03:31', '2026-09-27 08:54:53'),
(3, 'Transit-345', '2026-09-11', 'SABIC', 'MD. Euro Band 2', 'c4', '2026-09-28', '0000-00-00', 11000, 10000, '', 'mongla_port', 'pending', '2026-09-27 14:38:31', '2026-09-27 08:39:19'),
(4, '21', '2026-09-05', 'Saudi', 'ship 1', 'c3', '2026-09-27', '0000-00-00', 100, 100, '', 'chittagong_port', 'overseas', '2026-09-27 14:42:20', '2026-09-28 10:10:27'),
(5, 'Transit-9090', '2026-09-11', 'Russia', 'Rtu', 'c6', '2026-09-28', '0000-00-00', 111, 100, '', 'chittagong_port', 'completed', '2026-09-27 14:49:06', '2026-09-27 09:30:13'),
(6, 'transite-693', '2026-09-12', 'Saudi', 'ship 2', 'c6', '2026-09-30', '0000-00-00', 200, 100, '', 'chittagong_port', 'completed', '2026-09-28 13:28:25', '2026-09-28 07:29:18');

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
(3, NULL, 2, 'buffer_out', 100, 'delivery to dealer #2 (Tareq Emran) on 2026-09-27', NULL, '2026-09-27 10:45:57', '2026-09-27 04:45:57'),
(4, 10, 0, 'factory_out', 100, 'from cufl', NULL, '2026-09-28 14:05:33', '2026-09-28 08:05:33');

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
(1, 'Bogura', 'bogura_buffer', 'NORTH ZONE', 'buffer_godown', 'Tin Matha, Railgate, Puran Bogra, Bogra.', 5118, 9000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(2, 'Shantaher, Bogura', 'shantaher_buffer', 'NORTH ZONE', 'buffer_godown', 'Sandia, Santahar, Bogra.', 11027, 16000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(3, 'Gaibandha', 'gaibandha_buffer', 'NORTH ZONE', 'buffer_godown', 'D.B. Road, Gaibandha.', 8972, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(4, 'Rangpur', 'rangpur_buffer', 'NORTH ZONE', 'buffer_godown', 'Alamnagar, Rangpur.', 4149, 5000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(5, 'Kurigram', 'kruigram_buffer', 'NORTH ZONE', 'buffer_godown', 'Upazila Parishad Campus, Khalilpur, Kurigram.', 1325, 2000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(6, 'M.Nagar, Lalmonirhat', 'lalmoirhat_buffer', 'NORTH ZONE', 'buffer_godown', 'Mahendranagar, Lalmonirhat.', 8554, 9000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(7, 'Dinajpur', 'dinajpur_buffer', 'NORTH ZONE', 'buffer_godown', 'Pulhat, Dinajpur.', 8583, 6000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(8, 'Parbotipur, Dinajpur', 'parbotipur_buffer', 'NORTH ZONE', 'buffer_godown', 'Parbatipur, Dinajpur.', 8055, 9500, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(9, 'Charkai,Dinajpur', 'charkai_buffer', 'NORTH ZONE', 'buffer_godown', 'Birampur, Dinajpur.', 6762, 6000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(10, 'Joypurhat', 'joypurhat_buffer', 'NORTH ZONE', 'buffer_godown', 'C.O. Colony, Joypurhat.', 2848, 2000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(11, 'Rajshahi', 'rajshahi_buffer', 'NORTH ZONE', 'buffer_godown', 'Shiroil, Boalia, Rajshahi.', 10608, 8000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(12, 'Natore', 'natore_buffer', 'NORTH ZONE', 'buffer_godown', 'Railgate, Natore.', 6689, 8000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(13, 'Thakurgaon', 'thakurgaon_buffer', 'NORTH ZONE', 'buffer_godown', 'Shibganj, Thakurgaon.', 7690, 7500, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(14, 'Baghabari', 'baghabari_buffer', 'NORTH ZONE', 'buffer_godown', 'Shahjadpur, Sirajganj.', 2581, 4000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(15, 'Chap.N.Gonj', 'chapai_buffer', 'NORTH ZONE', 'buffer_godown', 'Nayagolahat, Sadar, Chapainawabganj.', 14471, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(16, 'Sherpur', 'sherpur_buffer', 'NORTH ZONE', 'buffer_godown', 'Jhenaigati, Sherpur.', 8158, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(17, 'Nilphamari', 'nilphamari_buffer', 'NORTH ZONE', 'buffer_godown', 'Nilphamari Sadar, Nilphamari.', 8769, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(18, 'Panchagor', 'panchagor_buffer', 'NORTH ZONE', 'buffer_godown', 'Arajigaighata (Dhanipara), Maidandighi, Boda, Panchagarh.', 9590, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(19, 'Kishorgonj', 'kishorgonj_buffer', 'NORTH ZONE', 'buffer_godown', 'Kishoreganj Sadar, Kishoreganj.', 6313, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(20, 'Netrokona', 'netrokona_buffer', 'NORTH ZONE', 'buffer_godown', 'Netrokona Sadar, Netrokona.', 7509, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(21, 'Pabna', 'pabna_buffer', 'NORTH ZONE', 'buffer_godown', 'Raghunathpur, Bera, Pabna.', 17046, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(22, 'Kaligonj, Jhinaidha', 'kaligonj_buffer', 'SOUTH ZONE', 'buffer_godown', 'Kaliganj, Jhenaidah.', 3096, 8000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(23, 'Jessore', 'jessore_buffer', 'SOUTH ZONE', 'buffer_godown', '78/79, Kholadanga, Jessore.', 8464, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(24, 'Shiromoni, Khulna', 'shiromoni_buffer', 'SOUTH ZONE', 'buffer_godown', 'Sonali Jute Mills, Shiromoni, Khulna.', 12636, 5500, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(25, 'Barisal', 'barisal_buffer', 'SOUTH ZONE', 'buffer_godown', 'Band Road, Barisal.', 3161, 3000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(26, 'Bhola', 'bhola_buffer', 'SOUTH ZONE', 'buffer_godown', 'Kheyaghat Road, Bhola Sadar, Bhola.', 2026, 8000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(27, 'Takerhat, Gopalgonj', 'tekerhat_buffer', 'SOUTH ZONE', 'buffer_godown', 'Muksudpur, Gopalganj.', 2788, 2000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(28, 'Tapakhola, Faridpur', 'tapakhola_buffer', 'SOUTH ZONE', 'buffer_godown', 'Tepakhola, Faridpur.', 2034, 2000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(29, 'MML, Kushtia', 'kushtia_buffer', 'SOUTH ZONE', 'buffer_godown', 'Millpara, Sadar, Kushtia.', 2527, 1000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(30, 'Rajbari', 'rajbari_buffer', 'SOUTH ZONE', 'buffer_godown', 'South Daulatdia, Goalanda, Rajbari.', 4284, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(31, 'Gopalganj', 'gopalganj_buffer', 'SOUTH ZONE', 'buffer_godown', 'Ghonapara Bus Stand, Gobra, Gopalganj Sadar, Gopalganj.', 1497, 10000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(32, 'CUFL', 'cufl', 'FACTORY ZONE', 'factory_office', 'Rangadia, Chittagong.', 7637, 20000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(33, 'AFCCL', 'afccl', 'FACTORY ZONE', 'factory_office', 'Ashuganj, Brahmanbaria.', 20704, 23000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(34, 'SFCL', 'sfcl', 'FACTORY ZONE', 'factory_office', 'Fenchuganj, Sylhet.', 27036, 18000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(35, 'JFCL', 'jfcl', 'FACTORY ZONE', 'factory_office', 'Tarakandi, Sarishabari, Jamalpur.', 7410, 15000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(36, 'GPFPLC/GPUFP', 'gpfplc', 'FACTORY ZONE', 'factory_office', 'Polash, Narsingdi.', 77528, 22000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43'),
(37, 'Kalurghat, Chittagong', 'kalurghat_transit', 'TRANSIT GODOWN', 'buffer_godown', 'Kalurghat, Chittagong.', 3386, 50000, 0, '2026-09-28 16:51:43', '2026-09-28 10:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `office_tbl_bak`
--

CREATE TABLE `office_tbl_bak` (
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
-- Dumping data for table `office_tbl_bak`
--

INSERT INTO `office_tbl_bak` (`id`, `office_name`, `buffer_name`, `zone`, `office_type`, `address`, `opening_bal`, `capacity`, `yearly_target`, `created_at`, `updated_at`) VALUES
(1, 'Kaliganj Buffer', 'kaliganj_buffer', 'South Zone', 'buffer_godown', 'Kaliganj, Jhenaidah', 6571, 8000, 0, '2026-09-12 12:10:02', '2026-09-28 06:22:26'),
(2, 'Barisal Buffer', 'barisal_buffer', 'South Zone', 'buffer_godown', 'Barisal', 3742, 3000, 0, '2026-09-13 13:11:47', '2026-09-28 06:50:12'),
(3, 'SFCL', 'sfcl', 'Factory Zone', 'factory_office', 'Fenchuganj, Sylhet', 32785, 18000, 0, '2026-09-13 15:31:40', '2026-09-28 06:50:15'),
(4, 'Chittagong Port', 'chittagong_port', 'Chittagong', 'port_office', 'Chittagong Port', 0, 0, 0, '2026-09-13 15:32:29', '2026-09-28 06:50:16'),
(5, 'Rajshahi Buffer', 'rajshahi_buffer', 'North Zone', 'buffer_godown', 'Rajshahi', 10551, 8000, 0, '2026-09-13 16:20:08', '2026-09-28 06:50:25'),
(6, 'JFCL', 'jfcl', 'Factory Zone', 'factory_office', 'Jamalpur', 6447, 15000, 0, '2026-09-14 09:43:41', '2026-09-28 06:50:31'),
(7, 'CUFL', 'cufl', 'Factory Zone', 'factory_office', 'Chittagong', 5744, 20000, 0, '2026-09-14 10:30:59', '2026-09-28 06:50:34'),
(8, 'Kalurghat', 'kalurghat_transit', 'Transit Godown Zone', 'buffer_godown', 'Kalurghat, Chittagong', 12869, 50000, 0, '2026-09-14 10:33:53', '2026-09-28 06:50:38'),
(9, 'KAFCO', 'kafco', 'KAFCO Zone', 'factory_office', 'Chittagong', 0, 0, 0, '2026-09-14 11:10:16', '2026-09-28 06:50:39'),
(10, 'GPFPLC', 'gpfplc', 'Factory Zone', 'factory_office', 'GPFPLC, Narsingdi', 87075, 22000, 0, '2026-09-16 10:10:46', '2026-09-28 06:50:42'),
(11, 'Pabna Buffer', 'pabna_buffer', 'North Zone', 'buffer_godown', 'Pabna', 19565, 10000, 0, '2026-09-26 16:41:27', '2026-09-28 06:50:45'),
(12, 'Mongla Port', 'mongla_port', 'Khulna', 'port_office', 'Mongla, Khulna', 0, 0, 0, '2026-09-28 12:56:32', '2026-09-28 06:56:32'),
(13, 'Bogura', 'bogura_buffer', 'NORTH ZONE', 'buffer_godown', 'Tin Matha, Railgate, Puran Bogra, Bogra.', 5118, 9000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(14, 'Shantaher, Bogura', 'shantaher_buffer', 'NORTH ZONE', 'buffer_godown', 'Sandia, Santahar, Bogra.', 11027, 16000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(15, 'Gaibandha', 'gaibandha_buffer', 'NORTH ZONE', 'buffer_godown', 'D.B. Road, Gaibandha.', 8972, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(16, 'Rangpur', 'rangpur_buffer', 'NORTH ZONE', 'buffer_godown', 'Alamnagar, Rangpur.', 4149, 5000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(17, 'Kurigram', 'kruigram_buffer', 'NORTH ZONE', 'buffer_godown', 'Upazila Parishad Campus, Khalilpur, Kurigram.', 1325, 2000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(18, 'M.Nagar, Lalmonirhat', 'lalmoirhat_buffer', 'NORTH ZONE', 'buffer_godown', 'Mahendranagar, Lalmonirhat.', 8554, 9000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(19, 'Dinajpur', 'dinajpur_buffer', 'NORTH ZONE', 'buffer_godown', 'Pulhat, Dinajpur.', 8583, 6000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(20, 'Parbotipur, Dinajpur', 'parbotipur_buffer', 'NORTH ZONE', 'buffer_godown', 'Parbatipur, Dinajpur.', 8055, 9500, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(21, 'Charkai,Dinajpur', 'charkai_buffer', 'NORTH ZONE', 'buffer_godown', 'Birampur, Dinajpur.', 6762, 6000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(22, 'Joypurhat', 'joypurhat_buffer', 'NORTH ZONE', 'buffer_godown', 'C.O. Colony, Joypurhat.', 2848, 2000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(23, 'Rajshahi', 'rajshahi_buffer', 'NORTH ZONE', 'buffer_godown', 'Shiroil, Boalia, Rajshahi.', 10608, 8000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(24, 'Natore', 'natore_buffer', 'NORTH ZONE', 'buffer_godown', 'Railgate, Natore.', 6689, 8000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(25, 'Thakurgaon', 'thakurgaon_buffer', 'NORTH ZONE', 'buffer_godown', 'Shibganj, Thakurgaon.', 7690, 7500, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(26, 'Baghabari', 'baghabari_buffer', 'NORTH ZONE', 'buffer_godown', 'Shahjadpur, Sirajganj.', 2581, 4000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(27, 'Chap.N.Gonj', 'chapai_buffer', 'NORTH ZONE', 'buffer_godown', 'Nayagolahat, Sadar, Chapainawabganj.', 14471, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(28, 'Sherpur', 'sherpur_buffer', 'NORTH ZONE', 'buffer_godown', 'Jhenaigati, Sherpur.', 8158, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(29, 'Nilphamari', 'nilphamari_buffer', 'NORTH ZONE', 'buffer_godown', 'Nilphamari Sadar, Nilphamari.', 8769, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(30, 'Panchagor', 'panchagor_buffer', 'NORTH ZONE', 'buffer_godown', 'Arajigaighata (Dhanipara), Maidandighi, Boda, Panchagarh.', 9590, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(31, 'Kishorgonj', 'kishorgonj_buffer', 'NORTH ZONE', 'buffer_godown', 'Kishoreganj Sadar, Kishoreganj.', 6313, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(32, 'Netrokona', 'netrokona_buffer', 'NORTH ZONE', 'buffer_godown', 'Netrokona Sadar, Netrokona.', 7509, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(33, 'Pabna', 'pabna_buffer', 'NORTH ZONE', 'buffer_godown', 'Raghunathpur, Bera, Pabna.', 17046, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(34, 'Kaligonj, Jhinaidha', 'kaligonj_buffer', 'SOUTH ZONE', 'buffer_godown', 'Kaliganj, Jhenaidah.', 3096, 8000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(35, 'Jessore', 'jessore_buffer', 'SOUTH ZONE', 'buffer_godown', '78/79, Kholadanga, Jessore.', 8464, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(36, 'Shiromoni, Khulna', 'shiromoni_buffer', 'SOUTH ZONE', 'buffer_godown', 'Sonali Jute Mills, Shiromoni, Khulna.', 12636, 5500, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(37, 'Barisal', 'barisal_buffer', 'SOUTH ZONE', 'buffer_godown', 'Band Road, Barisal.', 3161, 3000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(38, 'Bhola', 'bhola_buffer', 'SOUTH ZONE', 'buffer_godown', 'Kheyaghat Road, Bhola Sadar, Bhola.', 2026, 8000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(39, 'Takerhat, Gopalgonj', 'tekerhat_buffer', 'SOUTH ZONE', 'buffer_godown', 'Muksudpur, Gopalganj.', 2788, 2000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(40, 'Tapakhola, Faridpur', 'tapakhola_buffer', 'SOUTH ZONE', 'buffer_godown', 'Tepakhola, Faridpur.', 2034, 2000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(41, 'MML, Kushtia', 'kushtia_buffer', 'SOUTH ZONE', 'buffer_godown', 'Millpara, Sadar, Kushtia.', 2527, 1000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(42, 'Rajbari', 'rajbari_buffer', 'SOUTH ZONE', 'buffer_godown', 'South Daulatdia, Goalanda, Rajbari.', 4284, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(43, 'Gopalganj', 'gopalganj_buffer', 'SOUTH ZONE', 'buffer_godown', 'Ghonapara Bus Stand, Gobra, Gopalganj Sadar, Gopalganj.', 1497, 10000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(44, 'CUFL', 'cufl', 'FACTORY ZONE', 'factory_office', 'Rangadia, Chittagong.', 7637, 20000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(45, 'AFCCL', 'afccl', 'FACTORY ZONE', 'factory_office', 'Ashuganj, Brahmanbaria.', 20704, 23000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(46, 'SFCL', 'sfcl', 'FACTORY ZONE', 'factory_office', 'Fenchuganj, Sylhet.', 27036, 18000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(47, 'JFCL', 'jfcl', 'FACTORY ZONE', 'factory_office', 'Tarakandi, Sarishabari, Jamalpur.', 7410, 15000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(48, 'GPFPLC/GPUFP', 'gpfplc', 'FACTORY ZONE', 'factory_office', 'Polash, Narsingdi.', 77528, 22000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21'),
(49, 'Kalurghat, Chittagong', 'kalurghat_transit', 'TRANSIT GODOWN', 'buffer_godown', 'Kalurghat, Chittagong.', 3386, 50000, 0, '2026-09-28 16:48:21', '2026-09-28 10:48:21');

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
(1, 'sfcl-01', 'sfcl', 'barisal_buffer', 240, 'truck', 'complete', 240, 'sfcl', '2026-09-26 18:30:44', '2026-09-26 12:30:47'),
(2, 'mkt-049', 'cufl', 'jfcl', 100, 'truck', 'pending', 100, 'bcic_mkt', '2026-09-28 13:54:46', '2026-09-28 08:29:07'),
(3, 'cufl-898', 'cufl', 'jfcl', 100, 'truck', 'complete', 100, 'cufl', '2026-09-28 14:05:08', '2026-09-28 08:05:33');

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
(1, 0, 'sadmin', '$2y$10$WzbJLzxN3Bkq3Ta5JvMQ2ummeBsSoqrzckoSrQrP8bnmH77ooeOGq', 'sadmin', 'bcic_hq', '', '', '', '', '', '', '2024-05-25 13:21:42', '2026-09-28 11:12:11'),
(2, 0, 'bcic_mkt', '$2y$10$molBVE0XiGEqRlOFpXyfF.gvxDF9k2jP9tj9SiwLa3R/dw.UZggIa', 'user', 'bcic_hq', '', '', 'user@yahoo.com', '', '', '', '2024-06-01 12:48:45', '2026-09-28 11:12:17'),
(3, 0, 'admin', '$2y$10$WglKrBO2qZJbeG4ve.XzeeMrLGAIkMYmiOuu4qZzeEvfnrz4JIyMa', 'admin', 'bcic_hq', '', '', 'admin@yahoo.com', '', '', '', '2024-05-25 20:48:37', '2026-09-28 11:12:20'),
(4, 0, 'bcic_pur', '$2y$10$jzNzmObomiLt2gDSEu0YY.uiO5vym6RQytoIVNNcS0rAlumjQS.zO', 'user', 'bcic_hq', '', '', 'pur@yahoo.com', '', '', '', '2026-09-10 15:35:15', '2026-09-28 11:12:23'),
(5, 0, 'user', '$2y$10$SoWpKOQPaJsCDN9MSSj4Q.Qrb/eVhXpm8x7aSAPpibTNYHMZ3AaFe', 'user', 'bcic_hq', '', 'BCIC', '', 'BCIC', '', '', '2026-09-15 14:51:31', '2026-09-28 11:12:26'),
(6, 1, 'bogura_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Bogura', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(7, 2, 'shantaher_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Shantaher, Bogura', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(8, 3, 'gaibandha_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Gaibandha', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(9, 4, 'rangpur_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Rangpur', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(10, 5, 'kruigram_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Kurigram', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(11, 6, 'lalmoirhat_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'M.Nagar, Lalmonirhat', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(12, 7, 'dinajpur_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Dinajpur', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(13, 8, 'parbotipur_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Parbotipur, Dinajpur', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(14, 9, 'charkai_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Charkai,Dinajpur', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(15, 10, 'joypurhat_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Joypurhat', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(16, 11, 'rajshahi_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Rajshahi', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(17, 12, 'natore_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Natore', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(18, 13, 'thakurgaon_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Thakurgaon', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(19, 14, 'baghabari_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Baghabari', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(20, 15, 'chapai_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Chap.N.Gonj', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(21, 16, 'sherpur_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Sherpur', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(22, 17, 'nilphamari_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Nilphamari', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(23, 18, 'panchagor_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Panchagor', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(24, 19, 'kishorgonj_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Kishorgonj', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(25, 20, 'netrokona_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Netrokona', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(26, 21, 'pabna_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Pabna', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(27, 22, 'kaligonj_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Kaligonj, Jhinaidha', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(28, 23, 'jessore_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Jessore', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(29, 24, 'shiromoni_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Shiromoni, Khulna', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(30, 25, 'barisal_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Barisal', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(31, 26, 'bhola_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Bhola', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(32, 27, 'tekerhat_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Takerhat, Gopalgonj', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(33, 28, 'tapakhola_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Tapakhola, Faridpur', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(34, 29, 'kushtia_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'MML, Kushtia', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(35, 30, 'rajbari_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Rajbari', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(36, 31, 'gopalganj_buffer', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Gopalganj', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(37, 32, 'cufl', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'factory_office', '', 'CUFL', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(38, 33, 'afccl', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'factory_office', '', 'AFCCL', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(39, 34, 'sfcl', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'factory_office', '', 'SFCL', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(40, 35, 'jfcl', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'factory_office', '', 'JFCL', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(41, 36, 'gpfplc', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'factory_office', '', 'GPFPLC/GPUFP', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35'),
(42, 37, 'kalurghat_transit', '$2y$10$fkYNdjQfBUeaDKLNlLbd7.aSwoR04o0I9JWk/O6f6VTmBMQC6KwTe', 'user', 'buffer_godown', '', 'Kalurghat, Chittagong', '', '', '', '', '2026-09-28 17:12:35', '2026-09-28 11:12:35');

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
-- Indexes for table `office_tbl_bak`
--
ALTER TABLE `office_tbl_bak`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `import_urea`
--
ALTER TABLE `import_urea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `master_transaction`
--
ALTER TABLE `master_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `monthly_demand`
--
ALTER TABLE `monthly_demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `office_tbl`
--
ALTER TABLE `office_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `office_tbl_bak`
--
ALTER TABLE `office_tbl_bak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `production_tbl`
--
ALTER TABLE `production_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `urea_allotment`
--
ALTER TABLE `urea_allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
