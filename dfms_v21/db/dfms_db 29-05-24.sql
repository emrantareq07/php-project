-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2024 at 12:04 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

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
  `id` int(5) NOT NULL,
  `factory_name` varchar(100) NOT NULL DEFAULT 'afccl',
  `month_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `daily` varchar(10) NOT NULL,
  `monthly` varchar(10) NOT NULL,
  `yearly` varchar(10) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `afccl`
--

INSERT INTO `afccl` (`id`, `factory_name`, `month_id`, `date`, `daily`, `monthly`, `yearly`, `plant_load`, `remarks`) VALUES
(1, 'afccl', 202312, '2023-06-01', '10', '20', '20', 0, ''),
(3, 'afccl', 20231, '2023-07-01', '10', '10', '10', 0, ''),
(5, 'afccl', 202411, '2024-05-05', '30', '30', '40', 0, ''),
(6, 'afccl', 202411, '2024-05-20', '', '30', '40', 0, 'Due to NG ');

-- --------------------------------------------------------

--
-- Table structure for table `cufl`
--

CREATE TABLE `cufl` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dapfcl`
--

CREATE TABLE `dapfcl` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gpfplc`
--

CREATE TABLE `gpfplc` (
  `id` int(10) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(25) NOT NULL,
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gpfplc`
--

INSERT INTO `gpfplc` (`id`, `username`, `password`, `fullname`) VALUES
(1, 'emran', '123', 'Md. Tareq Emran'),
(2, 'tareq', '123', 'Md. Tareq Emran'),
(3, 'rajon', '$2y$10$UCz7x/aaQrQdv1XFab', '');

-- --------------------------------------------------------

--
-- Table structure for table `jfcl`
--

CREATE TABLE `jfcl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'jfcl',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(10) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jfcl`
--

INSERT INTO `jfcl` (`id`, `factory_name`, `month_id`, `date`, `daily`, `monthly`, `yearly`, `plant_load`, `remarks`) VALUES
(1, 'jfcl', 20231, '2023-07-04', 60, 60, 60, 80, ''),
(2, 'jfcl', 202411, '2024-05-20', 10, 10, 70, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `kafco`
--

CREATE TABLE `kafco` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `factory_name` varchar(50) NOT NULL DEFAULT 'sfcl',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(10) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sfcl`
--

INSERT INTO `sfcl` (`id`, `factory_name`, `month_id`, `date`, `daily`, `monthly`, `yearly`, `plant_load`, `remarks`) VALUES
(45, 'sfcl', 202411, '2024-05-19', 30, 30, 30, 60, ''),
(46, 'sfcl', 202411, '2024-05-28', 0, 30, 30, 0, ''),
(47, 'sfcl', 202411, '2024-05-29', 30, 60, 60, 60, '');

-- --------------------------------------------------------

--
-- Table structure for table `shutdown_cause`
--

CREATE TABLE `shutdown_cause` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(25) NOT NULL,
  `cause` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `target_table`
--

CREATE TABLE `target_table` (
  `id` int(11) NOT NULL,
  `factory_name` varchar(50) NOT NULL,
  `target` int(100) NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `target_table`
--

INSERT INTO `target_table` (`id`, `factory_name`, `target`, `fiscalstart`, `fiscalend`) VALUES
(5, 'sfcl', 400000, '2023-07-01', '2024-06-30'),
(6, 'jfcl', 400000, '2023-07-01', '2024-06-30'),
(7, 'jfcl', 0, '2024-07-01', '2025-06-30'),
(8, 'afccl', 400000, '2023-07-01', '2024-06-30'),
(9, 'tici', 0, '2023-07-01', '2024-06-30'),
(12, 'sfcl', 0, '2024-07-01', '2025-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `tici`
--

CREATE TABLE `tici` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tici',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(10) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tspcl`
--

CREATE TABLE `tspcl` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `email`, `created_at`) VALUES
(1, 'sfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', '2022-12-30 16:12:35'),
(2, 'jfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', '2023-02-21 16:20:41'),
(3, 'afccl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', '2022-12-30 16:54:22'),
(4, 'sadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', '', '2024-05-28 08:21:58'),
(12, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', 'user@yahoo.com', '2024-05-28 08:22:05'),
(13, 'cufl', '6116afedcb0bc31083935c1c262ff4c9', 'user', 'cufl@yahoo.com', '2024-05-23 09:39:24'),
(14, 'dapfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'dap@yahoo.com', '2024-05-23 09:39:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `afccl`
--
ALTER TABLE `afccl`
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
-- Indexes for table `kafco`
--
ALTER TABLE `kafco`
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
-- Indexes for table `tici`
--
ALTER TABLE `tici`
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
-- AUTO_INCREMENT for table `afccl`
--
ALTER TABLE `afccl`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cufl`
--
ALTER TABLE `cufl`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dapfcl`
--
ALTER TABLE `dapfcl`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gpfplc`
--
ALTER TABLE `gpfplc`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jfcl`
--
ALTER TABLE `jfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kafco`
--
ALTER TABLE `kafco`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_urea`
--
ALTER TABLE `report_urea`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sfcl`
--
ALTER TABLE `sfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `shutdown_cause`
--
ALTER TABLE `shutdown_cause`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target_table`
--
ALTER TABLE `target_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tici`
--
ALTER TABLE `tici`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tspcl`
--
ALTER TABLE `tspcl`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
