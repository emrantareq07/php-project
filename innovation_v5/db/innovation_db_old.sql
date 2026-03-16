-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2022 at 10:39 AM
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
-- Database: `innovation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `innovation`
--

CREATE TABLE `innovation` (
  `id` int(11) NOT NULL,
  `fiscal_year` varchar(30) NOT NULL,
  `title_of_invention` text NOT NULL,
  `inventors_name` varchar(100) NOT NULL,
  `inventors_designation` varchar(30) NOT NULL,
  `inventors_emp_id` varchar(25) NOT NULL,
  `proposed_workplace` enum('বিসিআইসি প্র: কা:','জেএফসিএল',' জিপিইউএফসিএল','এসএফসিএল','এএফসিসিএল',' ডিএপিএফসিএল','সিইউএফএল','সিসিসিএল',' কেপিএমএল',' বিআইএসএফএল') NOT NULL,
  `des_of_invention` text NOT NULL,
  `imple_status` enum('বাস্তবায়িত','চলমান') NOT NULL,
  `replicate_eligibility` enum('বিশেষায়িত','যোগ্য','যোগ্য  না') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `innovation`
--

INSERT INTO `innovation` (`id`, `fiscal_year`, `title_of_invention`, `inventors_name`, `inventors_designation`, `inventors_emp_id`, `proposed_workplace`, `des_of_invention`, `imple_status`, `replicate_eligibility`, `created_at`) VALUES
(1, '২০১৮-১৯', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'জনাব মোঃ হাইয়ুল কাইয়ুম', 'চেয়ারম্যান, বিসিআইসিi', '5620-4', 'বিসিআইসি প্র: কা:', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'বাস্তবায়িত', 'বিশেষায়িত', '2022-07-26 11:24:46'),
(2, '২০১৮-১৯', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'জনাব মোঃ মোহাদ্দেস হোসেন', 'উপ প্রধান রসায়নবিদ', '5620-5', 'জেএফসিএল', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'বাস্তবায়িত', 'যোগ্য', '2022-07-26 11:24:46'),
(3, '২০১৮-১৯', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', 'জনাব চৌধুরী মোহাম্মদ হারুন', 'মহাব্যবস্থাপক (অপারেশন)', '5620-1', '', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', 'বাস্তবায়িত', 'বিশেষায়িত', '2022-07-26 11:24:46'),
(4, '২০১৮-১৯', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '5620-2', 'বিসিআইসি প্র: কা:', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'চলমান', 'বিশেষায়িত', '2022-07-26 11:24:46'),
(5, '২০১৯-২০', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '5620-3', 'বিসিআইসি প্র: কা:', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'বাস্তবায়িত', 'যোগ্য', '2022-07-26 14:47:10'),
(7, '২০১৯-২০', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা', 'জনাব শাহীন মাহমুদ', 'উপ-প্রধান প্রকৌশলী (রসায়ন)', '5620-6', '', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা', 'বাস্তবায়িত', 'যোগ্য', '2022-07-30 17:29:54'),
(8, '২০১৯-২০', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'মোহাম্মদ সোহরাব হোসেন', 'উপ-প্রধান রসায়নবিদ', '5620-7', '', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'বাস্তবায়িত', 'বিশেষায়িত', '2022-07-30 17:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `innovation_tbl`
--

CREATE TABLE `innovation_tbl` (
  `id` int(11) NOT NULL,
  `fiscal_year` varchar(30) NOT NULL,
  `title_of_invention` text NOT NULL,
  `inventors_name` varchar(100) NOT NULL,
  `inventors_designation` varchar(30) NOT NULL,
  `inventors_emp_id` varchar(25) NOT NULL,
  `proposed_workplace` enum('ইউজিএসএফএল','টিএসপিসিএল','বিসিআইসি প্র: কা:','জেএফসিএল',' জিপিইউএফসিএল','এসএফসিএল','এএফসিসিএল',' ডিএপিএফসিএল','সিইউএফএল','সিসিসিএল',' কেপিএমএল',' বিআইএসএফএল') NOT NULL,
  `des_of_invention` text NOT NULL,
  `imple_status` enum('বাস্তবায়িত','চলমান') NOT NULL,
  `replicate_eligibility` enum('বিশেষায়িত','যোগ্য','যোগ্য  না') NOT NULL,
  `feedback` varchar(50) NOT NULL,
  `service_link` varchar(100) NOT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `innovation_tbl`
--

INSERT INTO `innovation_tbl` (`id`, `fiscal_year`, `title_of_invention`, `inventors_name`, `inventors_designation`, `inventors_emp_id`, `proposed_workplace`, `des_of_invention`, `imple_status`, `replicate_eligibility`, `feedback`, `service_link`, `remarks`, `created_at`) VALUES
(1, '২০১৮-১৯', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'জনাব মোঃ হাইয়ুল কাইয়ুম', 'চেয়ারম্যান, বিসিআইসি', '', 'বিসিআইসি প্র: কা:', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'বাস্তবায়িত', 'বিশেষায়িত', 'প্রত্যাশিত', '', '', '2022-07-26 11:24:46'),
(2, '২০১৮-১৯', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'জনাব মোঃ মোহাদ্দেস হোসেন', 'উপ প্রধান রসায়নবিদ', '', 'জেএফসিএল', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-26 11:24:46'),
(3, '২০১৮-১৯', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', 'জনাব চৌধুরী মোহাম্মদ হারুন', 'মহাব্যবস্থাপক (অপারেশন)', '', 'টিএসপিসিএল', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', '2022-07-26 11:24:46'),
(4, '২০১৮-১৯', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '', 'বিসিআইসি প্র: কা:', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'চলমান', 'বিশেষায়িত', '', '', '', '2022-07-26 11:24:46'),
(5, '২০১৯-২০', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '', 'বিসিআইসি প্র: কা:', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-26 14:47:10'),
(7, '২০১৯-২০', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা', 'জনাব শাহীন মাহমুদ', 'উপ-প্রধান প্রকৌশলী (রসায়ন)', '', 'টিএসপিসিএল', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-30 17:29:54'),
(8, '২০১৯-২০', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'মোহাম্মদ সোহরাব হোসেন', 'উপ-প্রধান রসায়নবিদ', '', 'ইউজিএসএফএল', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', '2022-07-30 17:36:17'),
(9, '২০১৯-২০', 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', 'ড. এম এম এ কাদের', 'ব্যবস্থাপনা পরিচালক', '৫৬২০-১', '', 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', 'চলমান', 'যোগ্য', 'প্রত্যাশিত', '', '', '2022-11-15 15:37:43');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `Username`, `UserEmail`, `Password`, `RegDate`) VALUES
(2, 'abdul', 'admin', 'bootstrapfriendly@gmail.com', '202cb962ac59075b964b07152d234b70', '2020-10-23 16:03:33'),
(6, 'ramesh', 'ramesh', 'ramesh@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL),
(7, 'ramesh', 'ramesh2', 'ramesh@gmail2.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `innovation_tbl`
--
ALTER TABLE `innovation_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `innovation_tbl`
--
ALTER TABLE `innovation_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
