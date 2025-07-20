-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 11:24 AM
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
-- Database: `estate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `13buf_tbl`
--

CREATE TABLE `13buf_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `13buf_tbl`
--

INSERT INTO `13buf_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '0000-00-00', '', '', '', '', 0, 0, 0, 0, 0, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '0000-00-00', '', '', '', '0000-00-00', '', 0, 0, 0, 0, 0, '', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.', '2024-11-28 04:36:20', '2024-11-28 10:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `34buf_tbl`
--

CREATE TABLE `34buf_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `34buf_tbl`
--

INSERT INTO `34buf_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '0000-00-00', '', '', '', '', 0, 0, 0, 0, 0, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '0000-00-00', '', '', '', '0000-00-00', '', 0, 0, 0, 0, 0, '', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.', '2024-11-28 04:36:20', '2024-11-28 10:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `abbankho_tbl`
--

CREATE TABLE `abbankho_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `abbankho_tbl`
--

INSERT INTO `abbankho_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '2024-07-12', 'July', '2024', '1969-1970', 'AB Bank H.O.', 100, 100, 100, 100, 100, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 50, 50, 50, 50, 50, '5.00', '', '0000-00-00', '', '15.00', '', '0000-00-00', '', 150, 150, 150, 450, 450, '', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.', '2024-11-28 05:22:21', '2024-11-28 11:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `abbankpb_tbl`
--

CREATE TABLE `abbankpb_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `abbankpb_tbl`
--

INSERT INTO `abbankpb_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '2024-07-19', 'July', '2024', '1969-1970', 'AB Bank Principle Branch', 300, 200, 50, 1000, 100, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '15.00', '', '0000-00-00', '', '45.00', '', '0000-00-00', '', 300, 200, 50, 1000, 100, '', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.', '2024-11-28 05:23:15', '2024-11-28 11:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `arjufood_tbl`
--

CREATE TABLE `arjufood_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Nababpur Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bcicsamiti_tbl`
--

CREATE TABLE `bcicsamiti_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bdg_tbl`
--

CREATE TABLE `bdg_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beximco_tbl`
--

CREATE TABLE `beximco_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carbon_tbl`
--

CREATE TABLE `carbon_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cccl_tbl`
--

CREATE TABLE `cccl_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cccl_tbl`
--

INSERT INTO `cccl_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '0000-00-00', '', '', '', '', 0, 0, 0, 0, 0, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '0000-00-00', '', '', '', '0000-00-00', '', 0, 0, 0, 0, 0, '', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.', '2024-11-28 04:36:20', '2024-11-28 10:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `chainntrad_tbl`
--

CREATE TABLE `chainntrad_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `tenants_name` varchar(255) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `table_name`, `tenants_name`, `address`) VALUES
(1, 'abbankho_tbl', 'AB Bank H.O.', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000'),
(2, 'abbankpb_tbl', 'AB Bank Principle Branch', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(3, 'poton_tbl', 'Poton Traders', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(4, 'mollik_tbl', 'Mollik Traders', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(5, 'erres_tbl', 'E. R. Resourses', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(6, 'mrtrading_tbl', 'M. R. Trading', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(7, 'motalibasso_tbl', 'Motalib & Associates', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(8, 'romanaent_tbl', 'Romana Enterprise', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(9, 'rafirachi_tbl', 'Rafi & Rachi', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(10, 'oyasistec_tbl', 'Oyasis Technologies', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(11, 'mehedient_tbl', 'Mehedi Enterprise', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(12, 'multiwaysmkt_tbl', 'Multi-Ways Marketing', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),
(13, 'demano_tbl', 'Demano Ltd.', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1012'),
(14, 'beximco_tbl', 'BEXIMCO', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1013'),
(15, 'bdg_tbl', 'Bangladesh Development Group', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1014'),
(16, 'creativep_tbl', 'Creative Papers', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1015'),
(17, 'pp_tbl', 'Petroleum Products', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1016'),
(18, 'sadg_tbl', 'South Asia Dev. Group', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1017'),
(19, 'gp_tbl', 'Grameen Phone', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1018'),
(20, 'carbon_tbl', 'Carbon Holdings', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1019'),
(21, 'bcicsamiti_tbl', 'BCIC Samiti', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1020'),
(22, 'deshb_tbl', 'Desh Builders', '74-Dilkusha(Medical Center), Dhaka-1000'),
(23, 'hirajheelh_tbl', 'Hirajheel Hotel', '22-Motijheel, Dhaka-1000'),
(24, 'cccl_tbl', 'Chatak Cement Pro', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(25, '13buf_tbl', '13 Buffer Project', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(26, '34buf_tbl', '34 Buffer Project', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(27, 'daycare_tbl', 'Day Care', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(28, 'pdb_tbl', 'BPDB', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(29, 'fahiment_tbl', 'Fahim Enterprise', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(30, 'shamin_tbl', 'Sharmin Akter', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(32, 'nment_tbl', 'N M Enterprise', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1031'),
(33, 'rajobali_tbl', 'Rajob Ali', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1032'),
(34, 'arjufood_tbl', 'Arju Food & Restaurant', 'Nababpur Road, Chalkbazar, Dhaka.'),
(35, 'rajuk_tbl', 'Rajuk', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1034'),
(36, 'rajuk148_tbl', 'Rajuk 148/Ka', 'BCIC Bhaban-2, 148/ka-Motijheel, Dhaka-1000'),
(37, 'dami1', 'Dami', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1037'),
(38, 'royelton_tbl', 'Royelton Lacquer Coating', 'Chemical Godown Project, Shampur, Kadamtoli.'),
(40, 'pusti_tbl', 'Super Oil Rifinary (Pusti)', 'Shimrail, Narayangonj, Dhaka.'),
(41, 'kpml_tbl', 'KPML', 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000.'),
(42, 'nbpm_tbl', 'NBPM', 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000');

-- --------------------------------------------------------

--
-- Table structure for table `creativep_tbl`
--

CREATE TABLE `creativep_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dami1`
--

CREATE TABLE `dami1` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1037.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dami2`
--

CREATE TABLE `dami2` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dami3`
--

CREATE TABLE `dami3` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dami4`
--

CREATE TABLE `dami4` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daycare_tbl`
--

CREATE TABLE `daycare_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daycare_tbl`
--

INSERT INTO `daycare_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '0000-00-00', '', '', '', '', 0, 0, 0, 0, 0, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '0000-00-00', '', '', '', '0000-00-00', '', 0, 0, 0, 0, 0, '', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.', '2024-11-28 04:36:20', '2024-11-28 10:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `demano_tbl`
--

CREATE TABLE `demano_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deshb_tbl`
--

CREATE TABLE `deshb_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT '74-Dilkusha(Medical Center), Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erres_tbl`
--

CREATE TABLE `erres_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fahiment_tbl`
--

CREATE TABLE `fahiment_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Nawabpur Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fahiment_tbl`
--

INSERT INTO `fahiment_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '0000-00-00', '', '', '', '', 0, 0, 0, 0, 0, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '0000-00-00', '', '', '', '0000-00-00', '', 0, 0, 0, 0, 0, '', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.', '2024-11-28 04:36:20', '2024-11-28 10:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `gp_tbl`
--

CREATE TABLE `gp_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hirajheelh_tbl`
--

CREATE TABLE `hirajheelh_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT '22-Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kpml_tbl`
--

CREATE TABLE `kpml_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaker_tbl`
--

CREATE TABLE `leaker_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_table`
--

CREATE TABLE `log_table` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `Ip` varchar(100) NOT NULL,
  `login_date_time` datetime NOT NULL,
  `code` int(11) NOT NULL,
  `logout_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_table`
--

INSERT INTO `log_table` (`id`, `username`, `password`, `user_type`, `Ip`, `login_date_time`, `code`, `logout_date_time`) VALUES
(1, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-26 12:30:49', 90304, '0000-00-00 00:00:00'),
(2, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 10:31:13', 24732, '0000-00-00 00:00:00'),
(3, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 13:38:14', 36784, '0000-00-00 00:00:00'),
(4, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 13:59:15', 99884, '0000-00-00 00:00:00'),
(5, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 13:59:45', 81963, '0000-00-00 00:00:00'),
(6, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 13:59:55', 30162, '0000-00-00 00:00:00'),
(7, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:00:26', 22160, '0000-00-00 00:00:00'),
(8, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:00:36', 29197, '0000-00-00 00:00:00'),
(9, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:01:41', 61449, '0000-00-00 00:00:00'),
(10, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:02:07', 87924, '0000-00-00 00:00:00'),
(11, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:02:40', 58980, '0000-00-00 00:00:00'),
(12, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:03:13', 79919, '0000-00-00 00:00:00'),
(13, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:03:48', 37636, '0000-00-00 00:00:00'),
(14, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:04:51', 60181, '0000-00-00 00:00:00'),
(15, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:05:06', 37569, '0000-00-00 00:00:00'),
(16, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '127.0.0.1', '2024-11-28 14:16:56', 40383, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mehedient_tbl`
--

CREATE TABLE `mehedient_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mollik_tbl`
--

CREATE TABLE `mollik_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `motalibasso_tbl`
--

CREATE TABLE `motalibasso_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mrtrading_tbl`
--

CREATE TABLE `mrtrading_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `multiwaysmkt_tbl`
--

CREATE TABLE `multiwaysmkt_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nbpm_tbl`
--

CREATE TABLE `nbpm_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nment_tbl`
--

CREATE TABLE `nment_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1031.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oyasistec_tbl`
--

CREATE TABLE `oyasistec_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pdb_tbl`
--

CREATE TABLE `pdb_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pdb_tbl`
--

INSERT INTO `pdb_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '0000-00-00', '', '', '', '', 0, 0, 0, 0, 0, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '0000-00-00', '', '', '', '0000-00-00', '', 0, 0, 0, 0, 0, '', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.', '2024-11-28 04:36:20', '2024-11-28 10:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `poton_tbl`
--

CREATE TABLE `poton_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pp_tbl`
--

CREATE TABLE `pp_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pusti_tbl`
--

CREATE TABLE `pusti_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Shimrail, Narayangonj, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rafirachi_tbl`
--

CREATE TABLE `rafirachi_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rajobali_tbl`
--

CREATE TABLE `rajobali_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1032.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rajuk148_tbl`
--

CREATE TABLE `rajuk148_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/ka-Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rajuk148_tbl`
--

INSERT INTO `rajuk148_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '2024-08-15', 'August', '2024', '1969-1970', 'RAJUK (Zone-7)', 300, 60, 50, 1000, 100, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '15.00', '', '0000-00-00', '', '45.00', '', '0000-00-00', '', 300, 60, 50, 1000, 100, '', 'BCIC Bhaban-2, 148/ka-Motijheel, Dhaka-1000.', '2024-11-28 05:51:16', '2024-11-28 11:51:16');

-- --------------------------------------------------------

--
-- Table structure for table `rajuk_tbl`
--

CREATE TABLE `rajuk_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1034.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rajuk_tbl`
--

INSERT INTO `rajuk_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '2024-08-22', 'August', '2024', '1969-1970', 'RAJUK (Zone-6)', 100, 60, 100, 1000, 100, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 20, 0, 10, 0, 0, '5.00', '', '0000-00-00', '', '15.00', '', '0000-00-00', '', 80, 60, 90, 1000, 100, '', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1034.', '2024-11-28 05:53:18', '2024-11-28 11:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `rasheda_tbl`
--

CREATE TABLE `rasheda_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Imamgonj, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `romanaent_tbl`
--

CREATE TABLE `romanaent_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `royelton_tbl`
--

CREATE TABLE `royelton_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Chemical Godown Project, Shampur, Kadamtoli.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sadg_tbl`
--

CREATE TABLE `sadg_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shamin_tbl`
--

CREATE TABLE `shamin_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Nawabpur Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shamin_tbl`
--

INSERT INTO `shamin_tbl` (`id`, `date`, `month`, `year`, `fiscal_year`, `tenants_name`, `actual_hr`, `actual_eb`, `actual_wb`, `actual_gb`, `actual_cb`, `payorder_no_hr`, `payorder_date_hr`, `payorder_no_eb`, `payorder_date_eb`, `payorder_no_wb`, `payorder_date_wb`, `payorder_no_gb`, `payorder_date_gb`, `payorder_no_cb`, `payorder_date_cb`, `payorder_no_comb`, `payorder_date_comb`, `hr_in`, `eb_in`, `wasa_in`, `gb_in`, `cb_in`, `tax5`, `challan_no_tax`, `date_tax`, `month_tax`, `vat15`, `challa_no_vat`, `date_vat`, `month_vat`, `hr_outs`, `eb_outs`, `wasa_outs`, `gb_outs`, `cb_outs`, `all_value`, `address`, `created_at`, `updated_at`) VALUES
(1, '0000-00-00', '', '', '', '', 0, 0, 0, 0, 0, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '0000-00-00', '', '', '', '0000-00-00', '', 0, 0, 0, 0, 0, '', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.', '2024-11-28 04:36:20', '2024-11-28 10:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `signeture_tbl`
--

CREATE TABLE `signeture_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Chemical Godown Project, Shampur, Kadamtoli.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table`
--

CREATE TABLE `table` (
  `id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `tenants_name` varchar(255) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table`
--

INSERT INTO `table` (`id`, `table_name`, `tenants_name`, `address`) VALUES
(1, 'abbankho_tbl', 'AB Bank H.O.', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000'),
(2, 'abbankpb_tbl', 'AB Bank Principle Branch', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1001'),
(3, 'poton_tbl', 'Poton Traders', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1002'),
(4, 'mollik_tbl', 'Mollik Traders', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1003'),
(5, 'erres_tbl', 'E. R. Resourses', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1004'),
(6, 'mrtrading_tbl', 'M. R. Trading', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1005'),
(7, 'motalibasso_tbl', 'Motalib & Associates', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1006'),
(8, 'romanaent_tbl', 'Romana Enterprise', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1007'),
(9, 'rafirachi_tbl', 'Rafi & Rachi', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1008'),
(10, 'oyasistec_tbl', 'Oyasis Technologies', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1009'),
(11, 'mehedient_tbl', 'Mehedi Enterprise', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1010'),
(12, 'multiwaysmkt_tbl', 'Multi-Ways Marketing', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1011'),
(13, 'demano_tbl', 'Demano Ltd.', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1012'),
(14, 'beximco_tbl', 'BEXIMCO', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1013'),
(15, 'bdg_tbl', 'Bangladesh Development Group', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1014'),
(16, 'creativep_tbl', 'Creative Papers', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1015'),
(17, 'pp_tbl', 'Petroleum Products', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1016'),
(18, 'sadg_tbl', 'South Asia Dev. Group', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1017'),
(19, 'gp_tbl', 'Grameen Phone', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1018'),
(20, 'carbon_tbl', 'Carbon Holdings', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1019'),
(21, 'bcicsamiti_tbl', 'BCIC Samiti', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1020'),
(22, 'deshb_tbl', 'Desh Builders', '74-Dilkusha(Medical Center), Dhaka-1000'),
(23, 'hirajheelh_tbl', 'Hirajheel Hotel', '22-Motijheel, Dhaka-1000'),
(24, 'cccl_tbl', 'Chatak Cement Pro', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(25, '13buf_tbl', '13 Buffer Project', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(26, '34buf_tbl', '34 Buffer Project', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(27, 'daycare_tbl', 'Day Care', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(28, 'pdb_tbl', 'BPDB', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(29, 'fahiment_tbl', 'Fahim Enterprise', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(30, 'shamin_tbl', 'Sharmin Akter', 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),
(31, 'taniaent_tbl', 'Tania Enterprise', 'Zel Road, Chalkbazar, Dhaka.'),
(32, 'nment_tbl', 'N M Enterprise', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1031'),
(33, 'rajobali_tbl', 'Rajob Ali', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1032'),
(34, 'arjufood_tbl', 'Arju Food & Restaurant', 'Nababpur Road, Chalkbazar, Dhaka.'),
(35, 'rajuk_tbl', 'Rajuk', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1034'),
(36, 'rajuk148_tbl', 'Rajuk 148/Ka', 'BCIC Bhaban-2, 148/ka-Motijheel, Dhaka-1000'),
(37, 'dami1', 'Dami', 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1037'),
(38, 'royelton_tbl', 'Royelton Lacquer Coating', 'Chemical Godown Project, Shampur, Kadamtoli.'),
(39, 'signeture_tbl', 'Signeture Coating Industries', 'Chemical Godown Project, Shampur, Kadamtoli.'),
(40, 'pusti_tbl', 'Super Oil Rifinary (Pusti)', 'Shimrail, Narayangonj, Dhaka.'),
(41, 'kpml_tbl', 'KPML', 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000.'),
(42, 'nbpm_tbl', 'NBPM', 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000');

-- --------------------------------------------------------

--
-- Table structure for table `taniaent_tbl`
--

CREATE TABLE `taniaent_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Zel Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `office_type` varchar(100) NOT NULL,
  `office` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `office_type`, `office`, `email`, `created_at`) VALUES
(1, 'chairman', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'buffer', 'Chairman Secretariat', '', '2024-09-26 00:07:13'),
(2, 'dir_com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'factory_office', 'Director (Commercial)', '', '2024-08-19 02:12:04'),
(3, 'afccl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'factory_office', '', '', '2024-06-01 11:19:24'),
(4, 'sadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', 'bcic_hq', 'ICT Division', '', '2024-08-13 22:01:26'),
(12, 'bcic_mkt', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'bcic_hq', '', 'user@yahoo.com', '2024-06-01 00:48:45'),
(14, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', 'bcic_hq', '', 'admin@yahoo.com', '2024-05-25 08:48:37'),
(15, 'mongla_port', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', '', 'monglaport@yahoo.com', '2024-05-31 12:39:31'),
(17, 'csd', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', 'CSD', '', '2024-08-15 05:05:13'),
(20, 'ict', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', 'Chairman Secretariat', 'emrantareq09@gmail.com', '2024-08-18 02:56:30'),
(21, 'admin2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', '', 'CSD', 'emrantareq09@gmail.com', '2024-08-18 04:25:51'),
(22, 'dir_fin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', 'Director (Finance)', '', '2024-09-18 04:43:49'),
(23, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', 'EMD', '', '2024-10-15 04:06:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `13buf_tbl`
--
ALTER TABLE `13buf_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `34buf_tbl`
--
ALTER TABLE `34buf_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `abbankho_tbl`
--
ALTER TABLE `abbankho_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `abbankpb_tbl`
--
ALTER TABLE `abbankpb_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arjufood_tbl`
--
ALTER TABLE `arjufood_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcicsamiti_tbl`
--
ALTER TABLE `bcicsamiti_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bdg_tbl`
--
ALTER TABLE `bdg_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beximco_tbl`
--
ALTER TABLE `beximco_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carbon_tbl`
--
ALTER TABLE `carbon_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cccl_tbl`
--
ALTER TABLE `cccl_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chainntrad_tbl`
--
ALTER TABLE `chainntrad_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `creativep_tbl`
--
ALTER TABLE `creativep_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dami1`
--
ALTER TABLE `dami1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dami2`
--
ALTER TABLE `dami2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dami3`
--
ALTER TABLE `dami3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dami4`
--
ALTER TABLE `dami4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daycare_tbl`
--
ALTER TABLE `daycare_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `demano_tbl`
--
ALTER TABLE `demano_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deshb_tbl`
--
ALTER TABLE `deshb_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `erres_tbl`
--
ALTER TABLE `erres_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fahiment_tbl`
--
ALTER TABLE `fahiment_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gp_tbl`
--
ALTER TABLE `gp_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hirajheelh_tbl`
--
ALTER TABLE `hirajheelh_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpml_tbl`
--
ALTER TABLE `kpml_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaker_tbl`
--
ALTER TABLE `leaker_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_table`
--
ALTER TABLE `log_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mehedient_tbl`
--
ALTER TABLE `mehedient_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mollik_tbl`
--
ALTER TABLE `mollik_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `motalibasso_tbl`
--
ALTER TABLE `motalibasso_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mrtrading_tbl`
--
ALTER TABLE `mrtrading_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `multiwaysmkt_tbl`
--
ALTER TABLE `multiwaysmkt_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nbpm_tbl`
--
ALTER TABLE `nbpm_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nment_tbl`
--
ALTER TABLE `nment_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oyasistec_tbl`
--
ALTER TABLE `oyasistec_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pdb_tbl`
--
ALTER TABLE `pdb_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poton_tbl`
--
ALTER TABLE `poton_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pp_tbl`
--
ALTER TABLE `pp_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pusti_tbl`
--
ALTER TABLE `pusti_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rafirachi_tbl`
--
ALTER TABLE `rafirachi_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rajobali_tbl`
--
ALTER TABLE `rajobali_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rajuk148_tbl`
--
ALTER TABLE `rajuk148_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rajuk_tbl`
--
ALTER TABLE `rajuk_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `romanaent_tbl`
--
ALTER TABLE `romanaent_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `royelton_tbl`
--
ALTER TABLE `royelton_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadg_tbl`
--
ALTER TABLE `sadg_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shamin_tbl`
--
ALTER TABLE `shamin_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table`
--
ALTER TABLE `table`
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
-- AUTO_INCREMENT for table `13buf_tbl`
--
ALTER TABLE `13buf_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `34buf_tbl`
--
ALTER TABLE `34buf_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `abbankho_tbl`
--
ALTER TABLE `abbankho_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `abbankpb_tbl`
--
ALTER TABLE `abbankpb_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `arjufood_tbl`
--
ALTER TABLE `arjufood_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bcicsamiti_tbl`
--
ALTER TABLE `bcicsamiti_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bdg_tbl`
--
ALTER TABLE `bdg_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beximco_tbl`
--
ALTER TABLE `beximco_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carbon_tbl`
--
ALTER TABLE `carbon_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cccl_tbl`
--
ALTER TABLE `cccl_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chainntrad_tbl`
--
ALTER TABLE `chainntrad_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `creativep_tbl`
--
ALTER TABLE `creativep_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dami1`
--
ALTER TABLE `dami1`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dami2`
--
ALTER TABLE `dami2`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dami3`
--
ALTER TABLE `dami3`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dami4`
--
ALTER TABLE `dami4`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daycare_tbl`
--
ALTER TABLE `daycare_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `demano_tbl`
--
ALTER TABLE `demano_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deshb_tbl`
--
ALTER TABLE `deshb_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erres_tbl`
--
ALTER TABLE `erres_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fahiment_tbl`
--
ALTER TABLE `fahiment_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gp_tbl`
--
ALTER TABLE `gp_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hirajheelh_tbl`
--
ALTER TABLE `hirajheelh_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kpml_tbl`
--
ALTER TABLE `kpml_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaker_tbl`
--
ALTER TABLE `leaker_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mehedient_tbl`
--
ALTER TABLE `mehedient_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mollik_tbl`
--
ALTER TABLE `mollik_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `motalibasso_tbl`
--
ALTER TABLE `motalibasso_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mrtrading_tbl`
--
ALTER TABLE `mrtrading_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `multiwaysmkt_tbl`
--
ALTER TABLE `multiwaysmkt_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nbpm_tbl`
--
ALTER TABLE `nbpm_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nment_tbl`
--
ALTER TABLE `nment_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oyasistec_tbl`
--
ALTER TABLE `oyasistec_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pdb_tbl`
--
ALTER TABLE `pdb_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `poton_tbl`
--
ALTER TABLE `poton_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pp_tbl`
--
ALTER TABLE `pp_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pusti_tbl`
--
ALTER TABLE `pusti_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rafirachi_tbl`
--
ALTER TABLE `rafirachi_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rajobali_tbl`
--
ALTER TABLE `rajobali_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rajuk148_tbl`
--
ALTER TABLE `rajuk148_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rajuk_tbl`
--
ALTER TABLE `rajuk_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `romanaent_tbl`
--
ALTER TABLE `romanaent_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `royelton_tbl`
--
ALTER TABLE `royelton_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sadg_tbl`
--
ALTER TABLE `sadg_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shamin_tbl`
--
ALTER TABLE `shamin_tbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `table`
--
ALTER TABLE `table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
