-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2022 at 10:28 AM
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
-- Database: `bcic_tel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `designation_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `designation_type`) VALUES
(1, 'Assitant Engineer'),
(2, 'Executive Engineer'),
(3, 'Deputy Chief Engineer'),
(4, 'Additional Chief Engineer'),
(5, 'General Manager'),
(6, 'Departmental Head(MTS/EIP)'),
(7, 'Departmental Head(MTS/Mech)'),
(8, 'Departmental Head(Accounts)'),
(9, 'Departmental Head(Administration)'),
(10, 'Departmental Head(Commercial)'),
(11, 'Sr. System Analyst'),
(12, 'Deputy Manager'),
(13, 'Manager'),
(14, 'Deputy General Manager'),
(15, 'Addl. Chief Accountant'),
(16, 'Assistant Programmer'),
(17, 'Programmer'),
(18, 'Chairman (Grade-1)'),
(19, 'Director(Com.)'),
(20, 'Director(Fin.)'),
(21, 'Director(T&E)'),
(22, 'Director(P&I)'),
(23, 'Director(Prod.)'),
(24, 'Sr. GM(Admin)'),
(25, 'Accounts Officer'),
(26, 'GM(MTS)/Chief Engineer(MTS)'),
(27, 'Assistant Accounts Officer'),
(28, 'Assistant Admin Officer'),
(29, 'Assistant Com.Officer'),
(30, 'Assistant Manager (Admin) '),
(31, 'Assistant Manager (Com.) '),
(32, 'Assistant Technical Officer'),
(33, 'Assistant Operation Officer'),
(34, 'Operation Officer'),
(35, 'Technical Officer'),
(36, 'System Analyst');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `division`
--

INSERT INTO `division` (`id`, `name`) VALUES
(1, 'Administration'),
(2, 'Accounts'),
(3, 'Commercial'),
(4, 'Technical'),
(5, 'MTS'),
(6, 'Chairman Secretariat'),
(7, 'Operation'),
(8, 'PRD'),
(9, 'Personal Division'),
(10, 'RPD'),
(11, 'Marketing Division'),
(12, 'Audit Division'),
(13, 'Purchase Division'),
(14, 'Finance Division'),
(15, 'MIS'),
(16, 'Director (Com.)'),
(17, 'Director (Fin.)'),
(18, 'Director (P&I)'),
(19, 'Director (T&E)'),
(20, 'Director (Prod.)'),
(21, 'ICT Division'),
(22, 'Board of Director'),
(23, 'BCIC'),
(24, 'BCIC H.O.'),
(25, 'Director (T&E)'),
(26, 'Director (P&I)'),
(27, 'Director (Prod.)');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `division_id`, `name`) VALUES
(1, 6, 'Chairman Secretariat'),
(2, 9, 'COP'),
(3, 9, 'LSA'),
(4, 9, 'RNT'),
(5, 8, 'PRD'),
(6, 12, 'Audit'),
(7, 13, 'Local Purchase'),
(8, 13, 'Foreign Purchase'),
(9, 11, 'Marketing'),
(10, 11, 'Marketing Store'),
(11, 2, 'Salary'),
(12, 2, 'PF'),
(13, 15, 'MIS'),
(14, 14, 'Finance '),
(15, 21, 'ICT'),
(16, 16, 'Director (Com.)'),
(17, 17, 'Director (Fin.)'),
(18, 18, 'Director (P&I)'),
(19, 19, 'Director (T&E)'),
(20, 20, 'Director (Prod.)'),
(21, 22, 'Board of Director'),
(22, 22, 'BCIC'),
(23, 22, 'BCIC H.O.');

-- --------------------------------------------------------

--
-- Table structure for table `tel_tbl`
--

CREATE TABLE `tel_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` enum('Assistant Engineer','Executive Engineer','Deputy Chief Engineer','Additional Chief Engineer','General Manager','Sr. System Analyst','Deputy Manager','Manager','Deputy General Manager','Addl. Chief Accountant','Assistant Programmer','Programmer','Chairman (Grade-1)','Director(Commerce)','Director(Finance)','Director(T&E)','Director(P&I)','Director(Production)','Sr. GM(Admin)','Accounts Officer','GM(MTS)/Chief Engineer(MTS)','Assistant Accounts Officer','Assistant Admin Officer','Assistant Com.Officer','Assistant Manager (Admin)','Assistant Manager (Com.)','Assistant Technical Officer','Assistant Operation Officer','Operation Officer','Technical Officer','System Analyst') NOT NULL,
  `division_name` enum('Administration','Accounts','MTS','Commercial','Technical','Operation','PRD','PRD','Personal Division') NOT NULL,
  `section_name` varchar(25) NOT NULL,
  `phone_office` varchar(25) NOT NULL,
  `phone_home` varchar(25) NOT NULL,
  `intercom` varchar(25) NOT NULL,
  `mobile` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `location` varchar(25) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tel_tbl`
--

INSERT INTO `tel_tbl` (`id`, `emp_id`, `name`, `designation`, `division_name`, `section_name`, `phone_office`, `phone_home`, `intercom`, `mobile`, `email`, `location`, `image`, `created_at`) VALUES
(1, '5620-0', 'emran', 'Programmer', '', 'RNT', '799', '889', '998', '3809348', 'emran@yahoo.com', 'Dhaka', '', '2022-10-20 03:12:17'),
(2, '', 'tareq', '', '', 'LSA', '989', '89', '898', '7978', 'tareq@yahoo.com', 'Dhaka', '', '2022-09-12 08:29:11'),
(3, '5620-1', 'Gazi shahinul', '', 'Commercial', '1', '12345', '2344', '34', '23456', 'gazi@yahoo.com', 'Dhaka', '1603179140.jpg', '2022-10-24 16:25:08'),
(4, '5620-2', 'ABM Ferdous', 'General Manager', '', '15', '986', '76q75', '7665q', '757', 'fer@yahoo.com', 'Dhaka', '', '2022-10-20 03:31:08'),
(5, '', 'Tareq Emran', 'Programmer', '', '15', '345', '345', '345', '344', 't@yahoo.com', 'Dhaka', '', '2022-09-16 16:08:49'),
(6, '', 'jamal', 'Deputy Chief Engineer', 'Technical', '13', '12345', '2344', '34', '23456', 'fer@yahoo.com', 'Dhaka', '', '2022-09-16 17:36:48'),
(7, '', 'kamal', 'Additional Chief Engineer', '', '9', '12345', '2344', '34', '23456', 'kamal@yahoo.com', 'Dhaka', '', '2022-09-17 09:10:43'),
(11, '', 'জনাব শাহ্‌ মোঃ ইমদাদুল হক', '', '', 'বিসিআইসি', '০২-২২৩৩৮৪১৫৩  ', '০২-২২৩৩৮৪১৫৩  ', '০২-২২৩৩৮৪১৫৩  ', '০২-২২৩৩৮৪১৫৩  ', 'chairman.bcic@bcic.gov.bd ', 'Dhaka', '', '2022-09-18 15:48:26'),
(12, '', 'জনাব কাজী মোহাম্মদ সাইফুল ইসলাম ', '', '', 'বিসিআইসি', '০২-২২৩৩৮২০৯০  ', '০২-২২৩৩৮২০৯০  ', '০২-২২৩৩৮২০৯০  ', '০২-২২৩৩৮২০৯০  ', 'dir.com@bcic.gov.bd ', 'Dhaka', '', '2022-09-18 15:48:31'),
(13, '', 'জনাব মোঃ ওয়াহিদুজজামান', '', '', 'বিসিআইসি', '০২-২২৩৩৮৪১৩৫', '০২-২২৩৩৮৪১৩৫', '০২-২২৩৩৮৪১৩৫', '০২-২২৩৩৮৪১৩৫', 'dir.fin@bcic.gov.bd   ', 'Dhaka', '', '2022-09-18 15:48:34'),
(14, '', 'জনাব মোঃ শাহীন কামাল ', 'Director(Production)', 'Operation', 'বিসিআইসি', '০২-২২৩৩৮৪১২৯   ', '০২-২২৩৩৮৪১২৯   ', '০২-২২৩৩৮৪১২৯   ', '০২-২২৩৩৮৪১২৯   ', 'dir.pr@bcic.gov.bd', 'Dhaka', '362620511.jpg', '2022-10-25 07:57:45'),
(15, '', 'জনাব মোঃ মনিরুল ইসলাম', 'Director(T&E)', 'Administration', 'বিসিআইসি', '০২-২২৩৩৮৫৬৯১', '০২-২২৩৩৮৫৬৯১', '০২-২২৩৩৮৫৬৯১', '০২-২২৩৩৮৫৬৯১', 'dir.te@bcic.gov.bd', 'Dhaka', '2038696506.jpg', '2022-10-25 05:40:10'),
(16, '', 'foisal v', 'Deputy Chief Engineer', 'Administration', 'MIS', '12345678', '4567', '345', '2345678', 'f@yahho.com', 'Dhaka', '2091077952.jpg', '2022-10-25 06:56:32'),
(17, '', 'Basir uddin', 'Deputy Chief Engineer', 'MTS', 'MIS', '22-123456789', '22-1234567', '1234', '12345-123456', 'ha@yahoo.com', 'Dhaka', 'bdlogo2.png', '2022-10-24 14:59:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tel_tbl`
--
ALTER TABLE `tel_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tel_tbl`
--
ALTER TABLE `tel_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
