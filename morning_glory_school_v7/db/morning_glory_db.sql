-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2025 at 11:21 AM
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
-- Database: `morning_glory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(2, '21 February', '2025-06-03 11:38:48'),
(3, 'Victory Day 1', '2025-06-04 04:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `latest_news`
--

CREATE TABLE `latest_news` (
  `id` int(11) NOT NULL,
  `news` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `messenger_name` varchar(50) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice_board`
--

CREATE TABLE `notice_board` (
  `id` int(11) NOT NULL,
  `notice` longtext NOT NULL,
  `filename` varchar(255) NOT NULL,
  `dated` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice_board`
--

INSERT INTO `notice_board` (`id`, `notice`, `filename`, `dated`, `created_at`) VALUES
(1, 'Pens taggedbootstrap-navbar. Include forks. No Pens for the tag bootstrap-navbar. ', '', '2022-11-26', '2022-11-25 18:20:21'),
(2, 'Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip', '', '2022-11-27', '2022-11-26 18:33:19'),
(3, 'For those which are facing the UnCaught Reference error, they need to include the bootstrap.min.js file before executing the custom jQuery added. I mean, add this line', '', '2022-11-27', '2022-11-26 18:46:27'),
(4, 'At the top of index.php, we are including', 'Scan-295.pdf', '0000-00-00', '2022-12-13 18:13:12'),
(5, 'test rr', 'BCIC PMIS.pdf', '2023-12-12', '2025-05-26 11:38:12'),
(6, 'test121', 'tender notice egp_0001_ea1fWf.pdf', '2025-05-28', '2025-05-28 10:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `photo_gallary`
--

CREATE TABLE `photo_gallary` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category` varchar(200) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `sub_category` varchar(100) NOT NULL,
  `publish_date` date NOT NULL,
  `publish_month` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photo_gallary`
--

INSERT INTO `photo_gallary` (`id`, `title`, `photo`, `category_id`, `category`, `subcategory_id`, `sub_category`, `publish_date`, `publish_month`, `created_at`) VALUES
(1, '', 'dir pi.jpg', 0, 'Pohela Boishakh', 0, '', '2023-01-24', 'January', '2023-01-25 07:00:28'),
(2, 'Test', '2022-12-29-04-15-5eb68367a89371773eb8f3dd98b7e3d7.jpeg', 0, 'Victory Day', 0, '', '2023-01-18', 'March', '2023-01-25 07:05:54'),
(3, 'test', 'fsa.jpg', 0, 'Victory Day', 0, '16th December\'2022', '2025-05-28', 'March', '2025-05-28 10:40:01'),
(4, 'test 4', 'images.jpg', 0, 'Victory Day', 0, '21 February\' 2020', '2025-06-02', 'January', '2025-06-03 11:53:02'),
(5, 'yyyy', 'fcbcbc00-f778-4fcd-be4f-e14f721e6201_removalai_preview.png', 3, '', 5, '', '2025-06-03', 'October', '2025-06-04 05:21:19'),
(6, 'tttttt', 'WhatsApp Image 2025-04-10 at 12.16.54 PM.jpeg', 0, '', 0, '', '2025-06-01', 'August', '2025-06-04 05:26:05'),
(7, 'eeeeeee', 'maksuda.jpg', 3, 'Victory Day 1', 5, '', '2025-06-11', 'October', '2025-06-04 05:29:05'),
(8, 'uuuu', 'Addl Sec Nuruzzaman.jpg', 3, 'Victory Day 1', 5, '', '2025-06-02', 'October', '2025-06-04 05:41:20'),
(9, 'rrrrrr', '2024-11-25-03-20-6970cb849639f1f42bf7c89f6b73c130.jpeg', 3, 'Victory Day 1', 5, 'Victory Day\' 2021', '2025-06-02', 'December', '2025-06-04 05:53:17'),
(10, 'mmmmmmm', '13.jpg', 3, 'Victory Day 1', 5, '', '2025-06-03', 'March', '2025-06-04 05:55:55'),
(11, 'bbbbb', 'WhatsApp Image 2025-05-27 at 12.40.27 PM.jpeg', 2, '21 February', 4, '', '2021-06-01', 'September', '2025-06-04 08:49:41'),
(12, 'cccccc', 'WhatsApp Image 2025-05-19 at 10.06.23 AM.jpeg', 2, '21 February', 4, '21 February\' 2020', '2020-06-01', 'January', '2025-06-04 08:59:55'),
(13, 'wwwwwwwwwww', 'WhatsApp Image 2025-05-27 at 12.40.27 PM.jpeg', 2, '21 February', 4, '21 February\' 2020', '2025-06-02', 'September', '2025-06-04 09:00:28');

-- --------------------------------------------------------

--
-- Table structure for table `scrolling`
--

CREATE TABLE `scrolling` (
  `id` int(11) NOT NULL,
  `scrolling_news` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `sub_category_name`, `created_at`) VALUES
(4, 2, '21 February\' 2020', '2025-06-04 04:06:16'),
(5, 3, 'Victory Day\' 2021', '2025-06-04 04:06:36');

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
(7, 'ramesh', 'ramesh2', 'ramesh@gmail2.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_info`
--

CREATE TABLE `teacher_info` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `joining_date` date NOT NULL,
  `last_edu_info` varchar(25) NOT NULL,
  `university` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `maritial_status` enum('Married','Unmarried','Divorce','Widow') NOT NULL,
  `religon` enum('Islam','Hindu','Chiristan','Others') NOT NULL,
  `blood_group` enum('B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `address` text NOT NULL,
  `nid` varchar(15) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `latest_news`
--
ALTER TABLE `latest_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_board`
--
ALTER TABLE `notice_board`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photo_gallary`
--
ALTER TABLE `photo_gallary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrolling`
--
ALTER TABLE `scrolling`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `latest_news`
--
ALTER TABLE `latest_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notice_board`
--
ALTER TABLE `notice_board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `photo_gallary`
--
ALTER TABLE `photo_gallary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `scrolling`
--
ALTER TABLE `scrolling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
