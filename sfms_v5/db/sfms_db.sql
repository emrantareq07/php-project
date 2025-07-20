-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 12:02 PM
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
  `month_id` int(11) NOT NULL,
  `concat_receive` varchar(255) NOT NULL,
  `concat_delivery` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kaliganj_buffer`
--

INSERT INTO `kaliganj_buffer` (`id`, `buffer_name`, `date`, `receive_import`, `receive_factory`, `delivery`, `total_stock`, `pipeline`, `month_id`, `concat_receive`, `concat_delivery`) VALUES
(23, 'kaliganj_buffer', '2024-05-29', 0, 0, 0, 100, 100, 202411, '', ''),
(24, 'kaliganj_buffer', '2024-05-30', 20, 5, 25, 100, 75, 202411, '20+5+', '25+');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `monthly_demand`
--

INSERT INTO `monthly_demand` (`id`, `date`, `office_name`, `demand_amount`, `month_id`) VALUES
(3, '2024-06-01', 'kaliganj_buffer', 0, 202412),
(4, '2024-05-30', 'shiromoni_buffer', 4000, 202411),
(5, '2024-05-30', 'kaliganj_buffer', 1000, 202411);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pipeline`
--

INSERT INTO `pipeline` (`id`, `date`, `from_office`, `to_office`, `amount`, `status`) VALUES
(1, '2024-05-26', 'mongla_port', 'kaliganj_buffer', 50, 'accept'),
(2, '2024-05-26', 'mongla_port', 'kaliganj_buffer', 20, 'accept'),
(3, '2024-05-26', 'sfcl', 'kaliganj_buffer', 200, 'pending'),
(7, '2024-05-30', 'mongla_port', 'kaliganj_buffer', 10, 'accept'),
(8, '2024-05-30', 'mongla_port', 'kaliganj_buffer', 100, 'accept'),
(9, '2024-05-30', 'chittagong_port', 'kaliganj_buffer', 100, 'pending'),
(10, '2024-05-30', 'monglaport', 'kaliganj_buffer', 100, 'accept'),
(11, '2024-05-30', 'chittagonj_port', 'kaliganj_buffer', 50, 'pending'),
(12, '2024-05-30', 'monglaport', 'shiromoni_buffer', 100, 'accept');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shiromoni_buffer`
--

INSERT INTO `shiromoni_buffer` (`id`, `buffer_name`, `date`, `receive_import`, `receive_factory`, `delivery`, `total_stock`, `pipeline`, `month_id`, `concat_receive`, `concat_delivery`) VALUES
(23, 'shiromoni_buffer', '2024-05-29', 0, 0, 20, 200, 100, 202411, '', ''),
(24, 'shiromoni_buffer', '2024-05-30', 10, 10, 30, 190, 80, 202411, '10+10+', '30+');

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
  `office_type` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `office_type`, `email`, `created_at`) VALUES
(1, 'shiromoni_buffer', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'buffer', '', '2024-05-30 08:49:12'),
(2, 'jfcl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'factory_office', '', '2024-05-27 08:39:41'),
(3, 'afccl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '', '', '2022-12-30 16:54:22'),
(4, 'sadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', 'bcic_hq', '', '2024-05-25 07:21:42'),
(12, 'user', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', '', 'user@yahoo.com', '2024-05-20 09:23:36'),
(13, 'kaliganj_buffer', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'buffer', 'kaliganj_buffer@yahoo.com', '2024-05-25 07:16:37'),
(14, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', 'bcic_hq', 'admin@yahoo.com', '2024-05-25 14:48:37'),
(15, 'monglaport', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', 'monglaport@yahoo.com', '2024-05-27 08:29:30'),
(16, 'chittagonj_port', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', 'chittagonj_port@yahoo.com', '2024-05-30 07:12:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kaliganj_buffer`
--
ALTER TABLE `kaliganj_buffer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_demand`
--
ALTER TABLE `monthly_demand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pipeline`
--
ALTER TABLE `pipeline`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `monthly_demand`
--
ALTER TABLE `monthly_demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pipeline`
--
ALTER TABLE `pipeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `shiromoni_buffer`
--
ALTER TABLE `shiromoni_buffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `stock_mgtm`
--
ALTER TABLE `stock_mgtm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
