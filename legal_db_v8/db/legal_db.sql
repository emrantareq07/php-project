-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2024 at 05:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `legal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `case_type`
--

CREATE TABLE `case_type` (
  `id` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `auto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_type`
--

INSERT INTO `case_type` (`id`, `type`, `auto_id`) VALUES
(2, 'রিট পিটিশন', 1),
(3, 'কনটেম্পট পিটিশন', 1),
(4, 'এডমিলারিটি স্যুট', 1),
(5, 'ফার্স্ট আপীল', 1),
(6, 'আরবিট্রেশন এপ্লিকেশন', 1),
(7, 'সিপিএলএ', 2),
(8, 'সিএমপি', 2),
(9, 'সিভিল রিভিউ পিটিশন', 2),
(10, 'ক্রিমিনাল রিভিউ পিটিশন', 2),
(11, 'কনটেম্পট পিটিশন', 2),
(12, 'সিভিল আপীল', 2),
(13, 'মানি স্যুট', 3),
(14, 'মানি এক্সিকিউশন মামলা', 3),
(15, 'অর্থঋণ মামলা', 3),
(16, 'টাইটেল স্যুট', 3),
(17, 'মিসকেস', 3),
(18, 'আরবিট্রেশন এপ্লিকেশন', 3),
(19, 'ট্রান্সফার মিসকেস', 3),
(20, 'মিস আপীল', 3),
(21, 'জিআর', 4),
(22, 'সিআর', 4),
(23, 'দেওয়ানী', 4),
(24, 'বিএলএল', 5),
(25, 'আপীল মামলা', 6);

-- --------------------------------------------------------

--
-- Table structure for table `court_division`
--

CREATE TABLE `court_division` (
  `id` int(11) NOT NULL,
  `court_division_name` varchar(100) NOT NULL,
  `auto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `court_division`
--

INSERT INTO `court_division` (`id`, `court_division_name`, `auto_id`) VALUES
(1, 'হাইকোর্ট বিভাগ', 1),
(2, 'আপিল বিভাগ', 1),
(3, 'জজ কোর্ট', 2),
(4, 'সিএমএম কোর্ট', 2),
(5, 'শ্রম আদালত', 2),
(6, 'শ্রম আপীল আদালত', 2);

-- --------------------------------------------------------

--
-- Table structure for table `court_type`
--

CREATE TABLE `court_type` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `court_type`
--

INSERT INTO `court_type` (`id`, `type`) VALUES
(1, 'উচ্চ আদালত'),
(2, 'নিম্ন আদালত');

-- --------------------------------------------------------

--
-- Table structure for table `legal_tbl`
--

CREATE TABLE `legal_tbl` (
  `id` int(11) NOT NULL,
  `court_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `court_division` varchar(100) NOT NULL,
  `case_type` varchar(200) NOT NULL,
  `case_no` varchar(100) NOT NULL,
  `case_date` varchar(1000) NOT NULL,
  `case_duration` date NOT NULL,
  `reason_of_case` text NOT NULL,
  `plaintiff_name` varchar(250) NOT NULL,
  `defendant_name` varchar(250) NOT NULL,
  `lower_name_address` text NOT NULL,
  `case_filing_date` date NOT NULL,
  `hearing_date` date NOT NULL,
  `hearing_result` varchar(100) NOT NULL,
  `next_hearing_result_date` date NOT NULL,
  `case_filing_accept_steps` text NOT NULL,
  `panel_lower_info` text NOT NULL,
  `panel_low_adv_info` text NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `legal_tbl`
--

INSERT INTO `legal_tbl` (`id`, `court_type`, `court_division`, `case_type`, `case_no`, `case_date`, `case_duration`, `reason_of_case`, `plaintiff_name`, `defendant_name`, `lower_name_address`, `case_filing_date`, `hearing_date`, `hearing_result`, `next_hearing_result_date`, `case_filing_accept_steps`, `panel_lower_info`, `panel_low_adv_info`, `remarks`) VALUES
(31, 'নিম্ন আদালত', 'জজ কোর্ট', 'মানি এক্সিকিউশন মামলা', '444', '2024-02-05', '0000-00-00', 'd', 'uu', 'uu', 'uu', '2024-02-13', '2024-02-12', 'পক্ষে', '0000-00-00', 'yyyy', 'iiii', 'jhjhj', ''),
(36, 'উচ্চ আদালত', 'হাইকোর্ট বিভাগ', 'কনটেম্পট পিটিশন', '11111', '2007', '2024-03-13', '', 'aa', 'aa', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '', '', '', ''),
(37, 'উচ্চ আদালত', 'হাইকোর্ট বিভাগ', 'রিট পিটিশন', 'aa', '2007', '0000-00-00', 'aaaaaaaaaaaa', 'aa', 'aa', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `Username` varchar(120) DEFAULT NULL,
  `UserEmail` varchar(200) DEFAULT NULL,
  `Password` varchar(250) DEFAULT NULL,
  `RegDate` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `Username`, `UserEmail`, `Password`, `RegDate`) VALUES
(2, 'abdul', 'admin', 'bootstrapfriendly@gmail.com', '202cb962ac59075b964b07152d234b70', '2020-10-23 16:03:33'),
(6, 'ramesh', 'ramesh', 'ramesh@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL),
(7, 'ramesh', 'ramesh2', 'ramesh@gmail2.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL),
(0, 'user', 'user', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `case_type`
--
ALTER TABLE `case_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `court_division`
--
ALTER TABLE `court_division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `court_type`
--
ALTER TABLE `court_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `legal_tbl`
--
ALTER TABLE `legal_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `case_type`
--
ALTER TABLE `case_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `court_division`
--
ALTER TABLE `court_division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `court_type`
--
ALTER TABLE `court_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `legal_tbl`
--
ALTER TABLE `legal_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
