-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2024 at 11:40 AM
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
-- Database: `sfms_db`
--

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
  `month_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kaliganj_buffer`
--

INSERT INTO `kaliganj_buffer` (`id`, `buffer_name`, `date`, `receive_import`, `receive_factory`, `delivery`, `total_stock`, `pipeline`, `month_id`) VALUES
(4, 'kaliganj_buffer', '2024-05-22', 10, 0, 0, 110, 0, 202411),
(5, 'kaliganj_buffer', '2024-05-23', 10, 5, 0, 125, 0, 202411);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_demand`
--

CREATE TABLE `monthly_demand` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `office_name` varchar(100) NOT NULL,
  `demand_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_mgtm`
--

CREATE TABLE `stock_mgtm` (
  `id` int(11) NOT NULL,
  `buffer_name` varchar(100) NOT NULL,
  `stock_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(4, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', '', '2024-05-20 09:23:13'),
(12, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', 'user@yahoo.com', '2024-05-20 09:23:36'),
(13, 'kaliganj_buffer', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'kaliganj_buffer@yahoo.com', '2024-05-23 06:12:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kaliganj_buffer`
--
ALTER TABLE `kaliganj_buffer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_mgtm`
--
ALTER TABLE `stock_mgtm`
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
-- AUTO_INCREMENT for table `kaliganj_buffer`
--
ALTER TABLE `kaliganj_buffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_mgtm`
--
ALTER TABLE `stock_mgtm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
