-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 10:48 AM
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
-- Table structure for table `sfcl`
--

CREATE TABLE `sfcl` (
  `id` int(10) NOT NULL,
  `factory_name` varchar(50) NOT NULL DEFAULT 'sfcl',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(100) NOT NULL,
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

INSERT INTO `sfcl` (`id`, `factory_name`, `month_id`, `date`, `daily`, `monthly`, `yearly`, `plant_load`, `remarks`, `installed_capacity`, `attain_capacity`, `monthly_target`, `monthly_due`, `previous_day_prod`) VALUES
(45, 'sfcl', 202411, '2024-05-19', 30, 30, 30, 60, '', '580000', '580000', 0, 0, 0),
(46, 'sfcl', 202411, '2024-05-28', 0, 30, 30, 0, '', '580000', '580000', 0, 0, 0),
(47, 'sfcl', 202411, '2024-05-29', 30, 60, 60, 60, '', '580000', '580000', 0, 0, 0),
(48, 'sfcl', 202412, '2024-06-11', 30, 30, 90, 60, '', '580000', '580000', 0, 0, 0),
(49, 'sfcl', 20246, '2024-12-01', 100, 100, 190, 80, 'Runing', '580000', '580000', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sfcl`
--
ALTER TABLE `sfcl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sfcl`
--
ALTER TABLE `sfcl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
