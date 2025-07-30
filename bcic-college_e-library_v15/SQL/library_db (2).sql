-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 03:32 PM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `FullName`, `AdminEmail`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'Anuj Kumar', 'phpgurukulofficial@gmail.com', 'admin', 'f925916e2754e5e03f75dd58a5733251', '2017-07-16 18:11:42'),
(2, 'emran', 'emrantaeq@yahoo.com', 'emran', '202cb962ac59075b964b07152d234b70', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `student_notifications`
--

CREATE TABLE `student_notifications` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_notifications`
--

INSERT INTO `student_notifications` (`id`, `student_id`, `message`, `is_read`, `created_at`) VALUES
(1, '0001', 'Your book \'Algorithm\' is overdue (due date: 2025-05-06 14:13:51). Please return it immediately.', 0, '2025-05-08 11:01:09'),
(2, '0001', '<p style=\'color:red;\'> Your book \'Algorithm\' is overdue (due date: 2025-05-07 00:00:00). Please return it immediately.</p>', 0, '2025-05-08 12:06:11'),
(5, '0001', 'Your book \'Algorithm\' is overdue (due date: 2025-05-07 00:00:00). Please return it immediately.', 0, '2025-05-13 15:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `tblauthors`
--

CREATE TABLE `tblauthors` (
  `id` int(11) NOT NULL,
  `AuthorName` varchar(159) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblauthors`
--

INSERT INTO `tblauthors` (`id`, `AuthorName`, `creationDate`, `UpdationDate`) VALUES
(1, 'Anuj kumar test', '2017-07-08 12:49:09', '2025-04-30 03:25:44'),
(2, 'Chetan Bhagatt', '2017-07-08 14:30:23', '2017-07-08 15:15:09'),
(3, 'Anita Desai', '2017-07-08 14:35:08', NULL),
(4, 'HC Verma', '2017-07-08 14:35:21', NULL),
(5, 'R.D. Sharma ', '2017-07-08 14:35:36', NULL),
(9, 'fwdfrwer', '2017-07-08 15:22:03', NULL),
(10, 'Balagurushamy', '2025-04-17 06:53:22', NULL),
(11, 'হুমায়ূন আহমেদ', '2025-04-30 06:03:06', '2025-04-30 06:04:23'),
(12, 'আসমা কবির', '2025-05-07 04:57:41', '2025-05-07 05:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooks`
--

CREATE TABLE `tblbooks` (
  `id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `accession_no` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `edition` varchar(50) NOT NULL,
  `isbn` varchar(100) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_place` varchar(255) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `page_no` int(11) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `call_no` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0,
  `series` varchar(50) NOT NULL,
  `volume` varchar(20) NOT NULL,
  `language` varchar(50) NOT NULL,
  `self_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbooks`
--

INSERT INTO `tblbooks` (`id`, `entry_date`, `accession_no`, `book_name`, `category_id`, `author_id`, `edition`, `isbn`, `publisher`, `publication_place`, `year`, `page_no`, `price`, `source`, `call_no`, `remarks`, `reg_date`, `updation_date`, `status`, `series`, `volume`, `language`, `self_no`) VALUES
(1, '2025-04-30', 1, 'Data Structure', 4, 3, '1', '৬৪৫৬৪৫', '', '', '', 0, '', '', '11', '', '2025-05-07 05:08:17', '2025-05-07 05:24:14', 1, '', '', '', ''),
(2, '2025-05-02', 2, 'Algorithm', 5, 4, '2', '6456456', '', '', '', 0, '', '', '11', '', '2025-05-07 05:10:45', '2025-05-07 05:26:07', 0, '', '', '', ''),
(3, '2025-04-30', 3, 'Numerical Methods', 9, 4, '0', '8784654456', '', '', '', 0, '', '', '', '', '2025-05-17 05:51:55', '2025-05-17 05:51:55', 0, '', '', '', ''),
(4, '2025-04-30', 4, 'Computation Theory', 0, 1, '0', '345345', '', '', '', 0, '', '', '', '', '2025-05-17 07:07:08', '2025-05-17 07:07:08', 0, '', '', '', ''),
(5, '2025-05-02', 5, 'Numerical Methods test', 9, 4, '0', '8784654456', '', '', '', 0, '', '', '', '', '2025-05-17 07:12:32', '2025-05-17 07:12:32', 0, '', '', '', ''),
(6, '2025-05-01', 6, 'ttrerswf', 0, 4, '0', '4455', '', '', '', 0, '', '', '', '', '2025-05-17 07:15:26', '2025-05-17 07:15:26', 0, '', '', '', ''),
(7, '2025-05-08', 7, 'tttttttttttt', 2, 2, '0', 't577645', '', '', '', 0, '', '', '', '', '2025-05-17 07:50:21', '2025-05-17 07:50:21', 0, '', '', '', ''),
(8, '2025-05-02', 8, 'Applied Physics', 0, 5, '0', '12345', '', '', '', 0, '', '', '', '', '2025-05-17 07:52:55', '2025-05-17 07:52:55', 0, '', '', '', ''),
(9, '2025-05-08', 9, 'yyyyyyyyyyyyyyyyy', 4, 2, '0', '3456', 'test', 'test 123', '2012', 33, '222', '111', '3', 'dsfs', '2025-05-17 07:56:59', '2025-05-17 11:14:15', 0, '5', '3', 'BN', '55'),
(10, '2025-05-09', 10, 'Procurement and PPA', 2, 3, '1', '523452', '', '', '', 0, '', '', '', '', '2025-05-17 09:39:06', '2025-05-17 09:39:06', 0, '1', '2', 'BN', '43'),
(11, '2025-05-17', 11, 'yyyyyyyyyyyyyyyyy', 4, 2, '0', '3456', 'test', 'test 123', '2012', 33, '222', '111', '3', 'dsfs', '2025-05-17 11:22:10', '2025-05-17 11:22:10', 0, '5', '3', 'BN', '55'),
(12, '2025-05-17', 12, 'Algorithm', 5, 4, '2', '6456456', '22', '222', '22', 0, '22', '22', '11', '222', '2025-05-17 11:23:13', '2025-05-17 11:23:13', 0, '222', '3', '222', '22');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooks_old`
--

CREATE TABLE `tblbooks_old` (
  `id` int(11) NOT NULL,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int(11) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `ISBNNumber` int(11) DEFAULT NULL,
  `BookPrice` int(11) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbooks_old`
--

INSERT INTO `tblbooks_old` (`id`, `BookName`, `CatId`, `AuthorId`, `ISBNNumber`, `BookPrice`, `RegDate`, `UpdationDate`) VALUES
(1, 'PHP And MySql programming', 5, 1, 222333, 20, '2017-07-08 20:04:55', '2017-07-15 05:54:41'),
(3, 'physics', 6, 4, 1111, 15, '2017-07-08 20:17:31', '2017-07-15 06:13:17'),
(4, 'Data structure', 5, 2, 545435, NULL, '2024-07-31 05:31:20', NULL),
(5, 'Mircroprocessor', 5, 9, 456321, NULL, '2025-03-18 07:41:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `categoryParent` varchar(200) DEFAULT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `categoryParent`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(1, 'Romantic', 'Romantic  ', '1', '2025-05-17 06:21:17', '2025-05-17 06:21:17'),
(2, 'Management', 'Management  ', '1', '2025-05-17 06:21:35', '2025-05-17 06:21:35'),
(3, 'Mathematics', 'Mathematics  abc', '1', '2025-05-17 06:21:55', '2025-05-17 06:21:55'),
(4, 'Mathematics', 'Mathematics  nnnn', '1', '2025-05-17 06:22:14', '2025-05-17 06:22:14'),
(5, 'Science', 'Science  Physics', '1', '2025-05-17 06:23:35', '2025-05-17 06:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory_old`
--

CREATE TABLE `tblcategory_old` (
  `id` int(11) NOT NULL,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcategory_old`
--

INSERT INTO `tblcategory_old` (`id`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(4, 'Romantic', 1, '2017-07-04 18:35:25', '2025-04-30 04:45:34'),
(5, 'Technology', 1, '2017-07-04 18:35:39', '2017-07-08 17:13:03'),
(6, 'Science', 1, '2017-07-04 18:35:55', '0000-00-00 00:00:00'),
(7, 'Management', 1, '2017-07-04 18:36:16', '2025-04-17 08:25:00'),
(8, 'Dictionary', 1, '2025-05-07 04:57:19', '0000-00-00 00:00:00'),
(9, 'Mathematics Differential ', 1, '2025-05-17 05:39:46', '2025-05-17 05:50:09'),
(10, 'Mathematics Calculas', 1, '2025-05-17 05:39:46', '2025-05-17 05:49:55'),
(11, 'Mathematics', 1, '2025-05-17 05:39:46', '2025-05-17 05:49:55'),
(12, 'Mathematics-Discrete', 1, '2025-05-17 06:03:21', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblissuedbookdetails`
--

CREATE TABLE `tblissuedbookdetails` (
  `id` int(11) NOT NULL,
  `accession_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `BookId` int(11) DEFAULT NULL,
  `StudentID` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT current_timestamp(),
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `RetrunStatus` int(1) NOT NULL,
  `fine` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblissuedbookdetails`
--

INSERT INTO `tblissuedbookdetails` (`id`, `accession_no`, `BookId`, `StudentID`, `IssuesDate`, `ReturnDate`, `RetrunStatus`, `fine`) VALUES
(1, '1', NULL, '0001', '2025-04-30 18:00:00', '2025-05-08 05:20:12', 1, 0),
(2, '2', NULL, '0001', '2025-04-30 18:00:00', '2025-05-06 18:00:00', 0, 0),
(3, '1', NULL, '0001', '2025-05-06 18:00:00', '2025-05-11 18:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `id` int(11) NOT NULL,
  `StudentId` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `FullName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `std_class` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `std_section` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `std_group` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `std_session` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `EmailId` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MobileNumber` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Image` varchar(255) DEFAULT NULL,
  `password_changed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`id`, `StudentId`, `FullName`, `std_class`, `std_section`, `std_group`, `std_session`, `EmailId`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdationDate`, `Image`, `password_changed`) VALUES
(1, '0001', 'Jamal', 'Eleven', 'A', 'Science', '2024-2025', '', '0171883465', '$2y$10$I6MZF/1UiMRh/YfNAXXQk.Iu16Ki/iI9HYGT4k4/kUUitQwAM8n6G', 1, '2025-05-07 05:14:14', '2025-05-13 10:34:14', 'student_images/1746594854_maksuda.jpg', 1),
(2, '0002', 'hasan', 'Twelve', 'A', 'O+', '2025-2026', '', '', '$2y$10$nLcLloFsLvQXlc9LY/7.8eliKqAWLoy2zGIm2AjMpcHwq/sQBjPd6', 1, '2025-05-07 05:16:42', '2025-05-08 06:26:57', 'student_images/1746595002_sarower.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_notifications`
--
ALTER TABLE `student_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `tblauthors`
--
ALTER TABLE `tblauthors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbooks`
--
ALTER TABLE `tblbooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbooks_old`
--
ALTER TABLE `tblbooks_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory_old`
--
ALTER TABLE `tblcategory_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblissuedbookdetails`
--
ALTER TABLE `tblissuedbookdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `StudentId` (`StudentId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_notifications`
--
ALTER TABLE `student_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblauthors`
--
ALTER TABLE `tblauthors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblbooks`
--
ALTER TABLE `tblbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblbooks_old`
--
ALTER TABLE `tblbooks_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblcategory_old`
--
ALTER TABLE `tblcategory_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblissuedbookdetails`
--
ALTER TABLE `tblissuedbookdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_notifications`
--
ALTER TABLE `student_notifications`
  ADD CONSTRAINT `student_notifications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudents` (`StudentId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
