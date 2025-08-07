-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 05:02 AM
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
(1, 'bisf', 'sanitary', 202412, '2024-12-26', 100, 1, 1, 80, '', '580000', '580000', 0, 0, 0),
(2, 'bisf', 'insulator', 202412, '2024-12-26', 200, 2, 2, 80, '', '580000', '580000', 0, 0, 0),
(3, 'bisf', 'refractories', 202412, '2024-12-26', 100, 3, 3, 55, '', '580000', '580000', 0, 0, 0);

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
(1, 'cufl', 'Urea', 202412, '2024-12-26', 200, 5, 5, 22, '', '580000', '580000', 0, 0, 0);

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
(1, 'jfcl', 'Urea', 202412, '2024-12-26', 190, 7, 7, 80, '', '580000', '580000', 0, 0, 0);

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
(3, 'bisf', 'refractories', 0, '2024-12-26', '2024-07-01', '2025-06-30'),
(4, 'sfcl', 'Urea', 0, '2024-12-26', '2024-07-01', '2025-06-30'),
(5, 'cufl', 'Urea', 0, '2024-12-26', '2024-07-01', '2025-06-30'),
(6, 'tspcl', 'TSP', 0, '2024-12-26', '2024-07-01', '2025-06-30'),
(7, 'jfcl', 'Urea', 0, '2024-12-26', '2024-07-01', '2025-06-30');

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
(1, 'sfcl', 'Urea', 202412, '2024-12-26', 100, 4, 4, 80, '', '580000', '580000', 0, 0, 0);

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
(3, 'bisf', 'refractories', 0, '2024-07-01', '2025-06-30'),
(4, 'sfcl', 'Urea', 0, '2024-07-01', '2025-06-30'),
(5, 'cufl', 'Urea', 0, '2024-07-01', '2025-06-30'),
(6, 'tspcl', 'TSP', 0, '2024-07-01', '2025-06-30'),
(7, 'jfcl', 'Urea', 0, '2024-07-01', '2025-06-30');

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
(1, 'tspcl', 'TSP', 202412, '2024-12-26', 200, 6, 6, 22, '', '580000', '580000', 0, 0, 0);

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
(3, 'afccl', '6116afedcb0bc31083935c1c262ff4c9', 'user', 'urea', '', '2024-12-01 08:50:51'),
(4, 'sadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', '', '', '2024-05-28 08:21:58'),
(12, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', '', 'user@yahoo.com', '2024-05-28 08:22:05'),
(13, 'cufl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'urea', 'cufl@yahoo.com', '2024-12-02 10:15:26'),
(17, 'tspcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', 'tspcl.md@bcic.gov.bd', '2024-12-01 08:57:43'),
(19, 'dapfcl', '6116afedcb0bc31083935c1c262ff4c9', 'user', 'non-urea', 'dap@yahoo.com', '2024-12-01 08:50:27'),
(20, 'gpfplc', '4f7a386d9ac4efbd3dbefd74113bc766', 'user', 'urea', 'gpfplc@yahoo.com', '2024-12-01 08:50:15'),
(21, 'bisf', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'non-urea', '', '2024-12-22 10:46:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bisf`
--
ALTER TABLE `bisf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cufl`
--
ALTER TABLE `cufl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jfcl`
--
ALTER TABLE `jfcl`
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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bisf`
--
ALTER TABLE `bisf`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cufl`
--
ALTER TABLE `cufl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jfcl`
--
ALTER TABLE `jfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `monthly_target`
--
ALTER TABLE `monthly_target`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `report_urea`
--
ALTER TABLE `report_urea`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sfcl`
--
ALTER TABLE `sfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shutdown_cause`
--
ALTER TABLE `shutdown_cause`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target_table`
--
ALTER TABLE `target_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tspcl`
--
ALTER TABLE `tspcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
