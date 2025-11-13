-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2024 at 12:45 PM
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
-- Database: `todolist_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chairman`
--

CREATE TABLE `chairman` (
  `id` int(10) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chairman`
--

INSERT INTO `chairman` (`id`, `date`, `time`, `subject`, `place`, `status`, `month`) VALUES
(2, '2022-02-08', 'সকাল ১১:০০ ঘটিকা', 'জেএফসিএল বোর্ড মিটিং', 'বোর্ড অফিস কনফারেন্স রুম', 'incomplete', ''),
(4, '2022-02-09', '55', 'dfsf', 'fdsf', 'incomplete', ''),
(5, '2022-02-11', '4', 'tsp', 'c', 'complete', ''),
(6, '2022-02-08', '5', 'dsfsdf', 'fdg', 'incomplete', ''),
(9, '2024-08-12', '10:00', 'JFCL vv', 'Conference Room	', 'incomplete', 'August'),
(11, '2024-08-11', '12:10', 'Test vffd', 'Board Room', 'incomplete', 'August'),
(12, '2024-08-12', '17:00', 'klsjfklj', 'Conference Room	', 'incomplete', 'August'),
(13, '2024-08-12', '20:08', 'Test vffd', 'Conference Room	', 'incomplete', 'August'),
(14, '2024-08-13', '12:00', 'Board meeteing', 'Conference Room	', 'incomplete', 'August'),
(15, '2024-08-14', '10:10', 'AFCCL Board ', 'Conference Room	', 'incomplete', 'August');

-- --------------------------------------------------------

--
-- Table structure for table `dir_com`
--

CREATE TABLE `dir_com` (
  `id` int(10) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dir_com`
--

INSERT INTO `dir_com` (`id`, `date`, `time`, `subject`, `place`, `status`, `month`) VALUES
(1, '2024-08-13', '16:00', 'Board meeteing tf bb3', 'Conference Room', 'incomplete', 'August'),
(2, '2024-08-14', '10:00', 'Board meeteing sfb 55vv', 'Board Room', 'incomplete', 'August');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `division` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `division`
--

INSERT INTO `division` (`id`, `division`) VALUES
(1, 'Personnel Division'),
(2, 'Accounts Division'),
(3, 'Commercial Division'),
(4, 'Technical Division'),
(5, 'MTS Division'),
(6, 'Chairman Secretariat'),
(7, 'Operation Division'),
(8, 'PRD'),
(9, 'PID'),
(10, 'RPD'),
(11, 'Marketing Division'),
(12, 'Audit Division'),
(13, 'Purchase Division'),
(14, 'Finance Division'),
(15, 'MIS Division'),
(16, 'Director (Com.)'),
(17, 'Director (Fin.)'),
(18, 'Director (P&I)'),
(19, 'Director (T&E)'),
(20, 'Director (Prod.)'),
(21, 'ICT Division'),
(25, 'Director (T&E)'),
(26, 'Director (P&I)'),
(47, 'AFCCL'),
(48, 'SFCL'),
(49, 'JFCL'),
(50, 'BISFL'),
(51, 'CUFL'),
(52, 'GPUFP'),
(53, 'GPFPLC'),
(54, 'DAPFCL'),
(55, 'TSPCL'),
(56, 'KPML'),
(57, 'UGSFL'),
(58, 'CCCL'),
(59, 'CCC'),
(60, '34 Buffer Project'),
(61, '13 Buffer Project'),
(62, 'UF-85 Project'),
(63, 'Chemical Godown, Shampur'),
(64, 'KNM & KHBM'),
(65, 'EMD'),
(66, 'Administration Division'),
(67, 'Senior General Manager (Admin)'),
(68, 'Planning Division'),
(69, 'Production Division'),
(72, 'HSET Division'),
(73, 'MTS (MECH)'),
(74, 'CSD'),
(75, 'BCIC College '),
(76, 'Legal Affairs Department'),
(77, 'Board & Co-ordination Department'),
(78, 'Company Affairs'),
(79, 'PDD'),
(80, 'ISHD'),
(81, 'Construction Division'),
(82, 'Forest Division');

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `id` int(11) NOT NULL,
  `office` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`id`, `office`) VALUES
(1, 'Chairman Secretariat'),
(2, 'Director (Commercial)'),
(3, 'Director (Finance)'),
(4, 'Director (P&R)'),
(5, 'Director (T&E)'),
(6, 'Director (P&I)');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `office_type`, `office`, `email`, `created_at`) VALUES
(1, 'chairman', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', 'buffer', 'Chairman Secretariat', '', '2024-08-12 10:37:20'),
(2, 'dir_com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'factory_office', 'Director (Commercial)', '', '2024-08-13 08:36:11'),
(3, 'afccl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'factory_office', '', '', '2024-06-01 17:19:24'),
(4, 'sadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sadmin', 'bcic_hq', 'ICT Division', '', '2024-08-14 04:01:26'),
(12, 'bcic_mkt', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'bcic_hq', '', 'user@yahoo.com', '2024-06-01 06:48:45'),
(13, 'kaliganj_buffer', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'buffer', '', 'kaliganj_buffer@yahoo.com', '2024-05-25 07:16:37'),
(14, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', 'bcic_hq', '', 'admin@yahoo.com', '2024-05-25 14:48:37'),
(15, 'mongla_port', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', '', 'monglaport@yahoo.com', '2024-05-31 18:39:31'),
(16, 'chittagonj_port', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', 'port_office', '', 'chittagonj_port@yahoo.com', '2024-05-30 07:12:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chairman`
--
ALTER TABLE `chairman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dir_com`
--
ALTER TABLE `dir_com`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office`
--
ALTER TABLE `office`
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
-- AUTO_INCREMENT for table `chairman`
--
ALTER TABLE `chairman`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `dir_com`
--
ALTER TABLE `dir_com`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `office`
--
ALTER TABLE `office`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
