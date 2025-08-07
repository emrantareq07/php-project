-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2024 at 11:37 AM
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
-- Database: `dfms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `afccl`
--

CREATE TABLE `afccl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `afccl`
--

INSERT INTO `afccl` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'afccl', 'Urea', 202407, '2024-07-01', 100, 10, 10, 80, '', '580000', '580000', 0, 0, 0),
(2, 'afccl', 'Urea', 202407, '2024-07-02', 200, 10, 10, 80, '', '580000', '580000', 0, 0, 0),
(3, 'afccl', 'Urea', 202407, '2024-07-25', 50, 10, 10, 80, '', '580000', '580000', 0, 0, 0),
(4, 'afccl', 'Urea', 202408, '2024-08-01', 100, 11, 10, 80, '', '580000', '580000', 0, 0, 0),
(5, 'afccl', 'Urea', 202408, '2024-08-03', 50, 11, 10, 22, '', '580000', '580000', 0, 0, 0),
(6, 'afccl', 'Urea', 202409, '2024-09-02', 100, 12, 10, 80, '', '580000', '580000', 0, 0, 0),
(7, 'afccl', 'Urea', 202410, '2024-10-03', 100, 13, 10, 80, '', '580000', '580000', 0, 0, 0),
(8, 'afccl', 'Urea', 202507, '2025-07-01', 100, 27, 11, 80, '', '580000', '580000', 0, 0, 0),
(9, 'afccl', 'Urea', 202412, '2024-12-29', 100, 14, 10, 22, '', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bisf`
--

CREATE TABLE `bisf` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bisf`
--

INSERT INTO `bisf` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'bisf', 'sanitary', 202407, '2024-07-01', 100, 15, 1, 80, '', '580000', '580000', 0, 0, 0),
(2, 'bisf', 'insulator', 202407, '2024-07-01', 100, 16, 2, 80, '', '580000', '580000', 0, 0, 0),
(3, 'bisf', 'refractories', 202407, '2024-07-01', 200, 17, 3, 80, '', '580000', '580000', 0, 0, 0),
(4, 'bisf', 'sanitary', 202407, '2024-07-02', 50, 15, 1, 80, '', '580000', '580000', 0, 0, 0),
(5, 'bisf', 'insulator', 202407, '2024-07-02', 50, 16, 2, 200, '', '580000', '580000', 0, 0, 0),
(6, 'bisf', 'refractories', 202407, '2024-07-02', 50, 17, 3, 22, '', '580000', '580000', 0, 0, 0),
(7, 'bisf', 'sanitary', 202407, '2024-07-25', 10, 15, 1, 80, '', '580000', '580000', 0, 0, 0),
(8, 'bisf', 'insulator', 202407, '2024-07-25', 50, 16, 2, 22, '', '580000', '580000', 0, 0, 0),
(9, 'bisf', 'refractories', 202407, '2024-07-25', 100, 17, 3, 80, '', '580000', '580000', 0, 0, 0),
(10, 'bisf', 'sanitary', 202408, '2024-08-01', 100, 18, 1, 80, '', '580000', '580000', 0, 0, 0),
(11, 'bisf', 'insulator', 202408, '2024-08-01', 100, 19, 2, 80, '', '580000', '580000', 0, 0, 0),
(12, 'bisf', 'refractories', 202408, '2024-08-01', 100, 20, 3, 80, '', '580000', '580000', 0, 0, 0),
(13, 'bisf', 'sanitary', 202408, '2024-08-03', 10, 18, 1, 10, '', '580000', '580000', 0, 0, 0),
(14, 'bisf', 'insulator', 202408, '2024-08-03', 10, 19, 2, 10, '', '580000', '580000', 0, 0, 0),
(15, 'bisf', 'refractories', 202408, '2024-08-03', 10, 20, 3, 10, '', '580000', '580000', 0, 0, 0),
(16, 'bisf', 'sanitary', 202409, '2024-09-02', 20, 21, 1, 10, '', '580000', '580000', 0, 0, 0),
(17, 'bisf', 'insulator', 202409, '2024-09-02', 20, 22, 2, 20, '', '580000', '580000', 0, 0, 0),
(18, 'bisf', 'refractories', 202409, '2024-09-02', 20, 23, 3, 20, '', '580000', '580000', 0, 0, 0),
(19, 'bisf', 'sanitary', 202410, '2024-10-03', 30, 24, 1, 10, '', '580000', '580000', 0, 0, 0),
(20, 'bisf', 'insulator', 202410, '2024-10-03', 30, 25, 2, 22, '', '580000', '580000', 0, 0, 0),
(21, 'bisf', 'refractories', 202410, '2024-10-03', 30, 26, 3, 22, '', '580000', '580000', 0, 0, 0),
(22, 'bisf', 'sanitary', 202507, '2025-07-01', 100, 28, 12, 80, '', '580000', '580000', 0, 0, 0),
(23, 'bisf', 'insulator', 202507, '2025-07-01', 100, 29, 13, 80, '', '580000', '580000', 0, 0, 0),
(24, 'bisf', 'refractories', 202507, '2025-07-01', 100, 30, 14, 80, '', '580000', '580000', 0, 0, 0),
(25, 'bisf', 'sanitary', 202412, '2024-12-29', 100, 1, 1, 80, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0),
(26, 'bisf', 'insulator', 202412, '2024-12-29', 0, 2, 2, 0, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0),
(27, 'bisf', 'refractories', 202412, '2024-12-29', 10, 3, 3, 0, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cccl`
--

CREATE TABLE `cccl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cccl`
--

INSERT INTO `cccl` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'cccl', 'Cement', 202412, '2024-12-26', 30, 9, 9, 100, 'fffffffffffffffffffffffffffffffff', '580000', '580000', 0, 0, 0),
(2, 'cccl', 'Cement', 202412, '2024-12-29', 100, 9, 9, 0, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cufl`
--

CREATE TABLE `cufl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cufl`
--

INSERT INTO `cufl` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'cufl', 'Urea', 202412, '2024-12-26', 200, 5, 5, 22, '', '580000', '580000', 0, 0, 0),
(2, 'cufl', 'Urea', 202412, '2024-12-29', 200, 5, 5, 80, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dapfcl`
--

CREATE TABLE `dapfcl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dapfcl`
--

INSERT INTO `dapfcl` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'dapfcl', 'DAP', 202412, '2024-12-26', 40, 8, 8, 100, '', '580000', '580000', 0, 0, 0),
(2, 'dapfcl', 'DAP', 202412, '2024-12-29', 0, 8, 8, 0, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gpfplc`
--

CREATE TABLE `gpfplc` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpfplc`
--

INSERT INTO `gpfplc` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'gpfplc', 'Urea', 202412, '2024-12-29', 0, 31, 15, 0, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jfcl`
--

CREATE TABLE `jfcl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jfcl`
--

INSERT INTO `jfcl` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'jfcl', 'Urea', 202412, '2024-12-26', 190, 7, 7, 80, '', '580000', '580000', 0, 0, 0),
(2, 'jfcl', 'Urea', 202412, '2024-12-29', 0, 7, 7, 0, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kpml`
--

CREATE TABLE `kpml` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kpml`
--

INSERT INTO `kpml` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'kpml', 'Paper', 202412, '2024-12-29', 100, 32, 16, 0, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_target`
--

CREATE TABLE `monthly_target` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(50) NOT NULL,
  `product_produce` varchar(255) NOT NULL,
  `target` int(100) NOT NULL,
  `target_date` date NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_target`
--

INSERT INTO `monthly_target` (`id`, `factory_name`, `product_produce`, `target`, `target_date`, `fiscalstart`, `fiscalend`) VALUES
(1, 'bisf', 'sanitary', 20, '2024-12-26', '2024-07-01', '2025-06-30'),
(2, 'bisf', 'insulator', 111, '2024-12-26', '2024-07-01', '2025-06-30'),
(3, 'bisf', 'refractories', 2220, '2024-12-26', '2024-07-01', '2025-06-30'),
(4, 'sfcl', 'Urea', 30000, '2024-12-26', '2024-07-01', '2025-06-30'),
(5, 'cufl', 'Urea', 0, '2024-12-26', '2024-07-01', '2025-06-30'),
(6, 'tspcl', 'TSP', 0, '2024-12-26', '2024-07-01', '2025-06-30'),
(7, 'jfcl', 'Urea', 0, '2024-12-26', '2024-07-01', '2025-06-30'),
(8, 'dapfcl', 'DAP', 2000, '2024-12-26', '2024-07-01', '2025-06-30'),
(9, 'cccl', 'Cement', 20000, '2024-12-26', '2024-07-01', '2025-06-30'),
(10, 'afccl', 'Urea', 500, '2024-07-01', '2024-07-01', '2025-06-30'),
(11, 'afccl', 'Urea', 400000, '2024-08-01', '2024-07-01', '2025-06-30'),
(12, 'afccl', 'Urea', 0, '2024-09-02', '2024-07-01', '2025-06-30'),
(13, 'afccl', 'Urea', 0, '2024-10-03', '2024-07-01', '2025-06-30'),
(14, 'afccl', 'Urea', 500000, '2024-12-30', '2024-07-01', '2025-06-30'),
(15, 'bisf', 'sanitary', 0, '2024-07-01', '2024-07-01', '2025-06-30'),
(16, 'bisf', 'insulator', 0, '2024-07-01', '2024-07-01', '2025-06-30'),
(17, 'bisf', 'refractories', 0, '2024-07-01', '2024-07-01', '2025-06-30'),
(18, 'bisf', 'sanitary', 0, '2024-08-01', '2024-07-01', '2025-06-30'),
(19, 'bisf', 'insulator', 0, '2024-08-01', '2024-07-01', '2025-06-30'),
(20, 'bisf', 'refractories', 0, '2024-08-01', '2024-07-01', '2025-06-30'),
(21, 'bisf', 'sanitary', 0, '2024-09-02', '2024-07-01', '2025-06-30'),
(22, 'bisf', 'insulator', 0, '2024-09-02', '2024-07-01', '2025-06-30'),
(23, 'bisf', 'refractories', 0, '2024-09-02', '2024-07-01', '2025-06-30'),
(24, 'bisf', 'sanitary', 0, '2024-10-03', '2024-07-01', '2025-06-30'),
(25, 'bisf', 'insulator', 0, '2024-10-03', '2024-07-01', '2025-06-30'),
(26, 'bisf', 'refractories', 0, '2024-10-03', '2024-07-01', '2025-06-30'),
(27, 'afccl', 'Urea', 2000, '2025-07-01', '2025-07-01', '2026-06-30'),
(28, 'bisf', 'sanitary', 0, '2025-07-01', '2025-07-01', '2026-06-30'),
(29, 'bisf', 'insulator', 0, '2025-07-01', '2025-07-01', '2026-06-30'),
(30, 'bisf', 'refractories', 0, '2025-07-01', '2025-07-01', '2026-06-30'),
(31, 'gpfplc', 'Urea', 0, '2024-12-30', '2024-07-01', '2025-06-30'),
(32, 'kpml', 'Paper', 0, '2024-12-29', '2024-07-01', '2025-06-30'),
(33, 'ugsf', 'Sheet Glass', 0, '2024-12-29', '2024-07-01', '2025-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `report_urea`
--

CREATE TABLE `report_urea` (
  `id` int(5) NOT NULL,
  `factory_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `daily` varchar(10) NOT NULL,
  `monthly` varchar(10) NOT NULL,
  `yearly` varchar(10) NOT NULL,
  `production_target` varchar(30) NOT NULL,
  `due` varchar(30) NOT NULL,
  `plant_load` varchar(5) NOT NULL,
  `stock_on_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `report_urea`
--

INSERT INTO `report_urea` (`id`, `factory_name`, `date`, `daily`, `monthly`, `yearly`, `production_target`, `due`, `plant_load`, `stock_on_date`) VALUES
(18, 'SFCL', '2022-03-03', '10', '100', '1000', '', '', '', ''),
(45, 'SFCL', '2022-03-30', '4', '62', '94', '', '', '', ''),
(46, 'SFCL', '2022-03-31', '2', '64', '96', '', '', '', ''),
(47, 'SFCL', '2022-04-01', '10', '10', '106', '', '', '', ''),
(48, 'SFCL', '2022-07-01', '10', '10', '10', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sfcl`
--

CREATE TABLE `sfcl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sfcl`
--

INSERT INTO `sfcl` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'sfcl', 'Urea', 202412, '2024-12-26', 100, 4, 4, 80, '', '580000', '580000', 0, 0, 0),
(2, 'sfcl', 'Urea', 202412, '2024-12-29', 40, 4, 4, 100, 'Dap1:this is the most intelisent and important purpose of our country of our countrty of dhakaDap1:this is the most intelisent and important purpose of our country of our countrty of dhakaDap1:this is the most intelisent and important purpose of our country of our countrty of dhakaDap1:this is the most intelisent and important purpose of our country of our countrty of dhaka', '580000', '580000', 0, 0, 0),
(3, 'sfcl', 'Urea', 202412, '2024-12-31', 100, 4, 4, 80, '', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shutdown_cause`
--

CREATE TABLE `shutdown_cause` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(25) NOT NULL,
  `cause` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `target_table`
--

CREATE TABLE `target_table` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(50) NOT NULL,
  `product_produce` varchar(255) NOT NULL,
  `target` int(100) NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `target_table`
--

INSERT INTO `target_table` (`id`, `factory_name`, `product_produce`, `target`, `fiscalstart`, `fiscalend`) VALUES
(1, 'bisf', 'sanitary', 60, '2024-07-01', '2025-06-30'),
(2, 'bisf', 'insulator', 555, '2024-07-01', '2025-06-30'),
(3, 'bisf', 'refractories', 1000, '2024-07-01', '2025-06-30'),
(4, 'sfcl', 'Urea', 240000, '2024-07-01', '2025-06-30'),
(5, 'cufl', 'Urea', 0, '2024-07-01', '2025-06-30'),
(6, 'tspcl', 'TSP', 0, '2024-07-01', '2025-06-30'),
(7, 'jfcl', 'Urea', 0, '2024-07-01', '2025-06-30'),
(8, 'dapfcl', 'DAP', 3000, '2024-07-01', '2025-06-30'),
(9, 'cccl', 'Cement', 2555, '2024-07-01', '2025-06-30'),
(10, 'afccl', 'Urea', 500000, '2024-07-01', '2025-06-30'),
(11, 'afccl', 'Urea', 1000, '2025-07-01', '2026-06-30'),
(12, 'bisf', 'sanitary', 0, '2025-07-01', '2026-06-30'),
(13, 'bisf', 'insulator', 0, '2025-07-01', '2026-06-30'),
(14, 'bisf', 'refractories', 0, '2025-07-01', '2026-06-30'),
(15, 'gpfplc', 'Urea', 0, '2024-07-01', '2025-06-30'),
(16, 'kpml', 'Paper', 0, '2024-07-01', '2025-06-30'),
(17, 'ugsf', 'Sheet Glass', 0, '2024-07-01', '2025-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `tspcl`
--

CREATE TABLE `tspcl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tspcl`
--

INSERT INTO `tspcl` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'tspcl', 'TSP', 202412, '2024-12-26', 200, 6, 6, 22, '', '580000', '580000', 0, 0, 0),
(2, 'tspcl', 'TSP', 202412, '2024-12-29', 50, 6, 6, 100, 'dddddddddddddddddddddddddddddddddddddddddddd', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ugsf`
--

CREATE TABLE `ugsf` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ugsf`
--

INSERT INTO `ugsf` (`id`, `factory_name`, `product_produce`, `month_id`, `date`, `daily`, `month_code`, `year_code`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(1, 'ugsf', 'Sheet Glass', 202412, '2024-12-29', 100, 33, 17, 80, 'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n', '580000', '580000', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `product_type`, `email`, `created_at`) VALUES
(1, 'sfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'urea', '', '2024-12-01 08:59:48'),
(2, 'jfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'urea', '', '2024-12-02 08:59:54'),
(3, 'afccl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'urea', '', '2024-12-29 04:57:15'),
(4, 'sadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', '', '', '2024-05-28 08:21:58'),
(12, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', '', 'user@yahoo.com', '2024-05-28 08:22:05'),
(13, 'cufl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'urea', 'cufl@yahoo.com', '2024-12-02 10:15:26'),
(17, 'tspcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', 'tspcl.md@bcic.gov.bd', '2024-12-01 08:57:43'),
(19, 'dapfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', 'dap@yahoo.com', '2024-12-29 04:57:19'),
(20, 'gpfplc', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'urea', 'gpfplc@yahoo.com', '2024-12-29 04:57:28'),
(21, 'bisf', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', '', '2024-12-22 10:46:15'),
(22, 'cccl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', '', '2024-12-22 10:46:15'),
(23, 'ugsf', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', '', '2024-12-22 10:46:15'),
(24, 'kpml', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', '', '2024-12-22 10:46:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `afccl`
--
ALTER TABLE `afccl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bisf`
--
ALTER TABLE `bisf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cccl`
--
ALTER TABLE `cccl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cufl`
--
ALTER TABLE `cufl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dapfcl`
--
ALTER TABLE `dapfcl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gpfplc`
--
ALTER TABLE `gpfplc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jfcl`
--
ALTER TABLE `jfcl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpml`
--
ALTER TABLE `kpml`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_target`
--
ALTER TABLE `monthly_target`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_urea`
--
ALTER TABLE `report_urea`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sfcl`
--
ALTER TABLE `sfcl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shutdown_cause`
--
ALTER TABLE `shutdown_cause`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `target_table`
--
ALTER TABLE `target_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tspcl`
--
ALTER TABLE `tspcl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ugsf`
--
ALTER TABLE `ugsf`
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
-- AUTO_INCREMENT for table `afccl`
--
ALTER TABLE `afccl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bisf`
--
ALTER TABLE `bisf`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `cccl`
--
ALTER TABLE `cccl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cufl`
--
ALTER TABLE `cufl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dapfcl`
--
ALTER TABLE `dapfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gpfplc`
--
ALTER TABLE `gpfplc`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jfcl`
--
ALTER TABLE `jfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kpml`
--
ALTER TABLE `kpml`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `monthly_target`
--
ALTER TABLE `monthly_target`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `report_urea`
--
ALTER TABLE `report_urea`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sfcl`
--
ALTER TABLE `sfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shutdown_cause`
--
ALTER TABLE `shutdown_cause`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target_table`
--
ALTER TABLE `target_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tspcl`
--
ALTER TABLE `tspcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ugsf`
--
ALTER TABLE `ugsf`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
