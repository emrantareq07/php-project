-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 10:05 AM
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
-- Database: `innovation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `designation_bn` varchar(255) NOT NULL,
  `designation_en` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `designation_bn`, `designation_en`) VALUES
(1, 'সহকারী প্রকৌশলী', 'Assistant Engineer (AE)'),
(2, 'নির্বাহী প্রকৌশলী', 'Executive Engineer (Ex.E)'),
(3, 'উপ-প্রধান প্রকৌশলী', 'Deputy Chief Engineer (DCE)'),
(4, 'অতিরিক্ত প্রধান প্রকৌশলী', 'Additional Chief Engineer (Addl.CE)'),
(5, 'মহাব্যবস্থাপক', 'General Manager (GM)'),
(6, 'বিভাগীয় প্রধান', 'Department Head'),
(7, 'বিভাগীয় প্রধান', 'Divisional Head'),
(8, 'সহকারী রসায়নবিদ', 'Assistant Chemist (AC)'),
(9, 'রসায়নবিদ', 'Chemist'),
(10, 'সিনিয়র সিস্টেম এনালিস্ট', 'Senior System Analyst (SSA)'),
(11, 'উপ-ব্যবস্থাপক', 'Deputy Manager (DM)'),
(12, 'ব্যবস্থাপক', 'Manager (M)'),
(13, 'উপ-মহাব্যবস্থাপক', 'Deputy General Manager (DGM)'),
(14, 'অতিরিক্ত প্রধান হিসাবরক্ষক', 'Additional Chief Accountant (ACA)'),
(15, 'সহকারী প্রোগ্রামার', 'Assistant Programmer (AP)'),
(16, 'প্রোগ্রামার', 'Programmer'),
(17, 'চেয়ারম্যান (গ্রেড-১)', 'Chairman (Grade-1)'),
(18, 'পরিচালক', 'Director'),
(19, 'অতিরিক্ত প্রধান রসায়নবিদ', 'Additional Chief Chemist (ACC)'),
(20, 'অতিরিক্ত প্রধান ব্যবস্থাপক', 'Additional Chief Manager (ACM)'),
(21, 'হিসাব কর্মকর্তা', 'Accounts Officer (AO)'),
(22, 'প্রধান প্রকৌশলী', 'Chief Engineer (CE)'),
(23, 'সহকারী হিসাব কর্মকর্তা', 'Assistant Accounts Officer (AAO)'),
(24, 'সহকারী প্রশাসনিক কর্মকর্তা', 'Assistant Admin Officer'),
(25, 'সহকারী বাণিজ্যিক কর্মকর্তা', 'Assistant Commercial Officer'),
(26, 'সহকারী ব্যবস্থাপক', 'Assistant Manager (AM)'),
(27, 'সহকারী কারিগরি কর্মকর্তা', 'Assistant Technical Officer (ATO)'),
(28, 'সহকারী অপারেশন কর্মকর্তা', 'Assistant Operation Officer (AOO)'),
(29, 'অপারেশন কর্মকর্তা', 'Operation Officer'),
(30, 'কারিগরি কর্মকর্তা', 'Technical Officer (TO)'),
(31, 'সিস্টেম এনালিস্ট', 'System Analyst (SA)'),
(32, 'ব্যবস্থাপনা পরিচালক', 'Managing Director (MD)'),
(33, 'নির্বাহী পরিচালক', 'Executive Director (ED)'),
(34, 'কর্মচারী প্রধান', 'Chief of Personnel (COP)'),
(35, 'হিসাব নিয়ন্ত্রক', 'Controller of Accounts (CA)'),
(36, 'ঊর্ধ্বতন মহাব্যবস্থাপক', 'Senior General Manager (Sr.GM)'),
(37, 'উপ-প্রধান হিসাবরক্ষক', 'Deputy Chief Accountant (DCA)'),
(38, 'চিকিৎসা কর্মকর্তা', 'Medical officer (MO)'),
(39, 'উর্ধ্বতন চিকিৎসা কর্মকর্তা', 'Senior Medical Officer (SMO)'),
(40, 'প্রধান চিকিৎসা কর্মকর্তা', 'Chief Medical Officer (CMO)'),
(41, 'প্রধান অর্থ কর্মকর্তা', 'Chief Finance Officer (CFO)'),
(42, 'চিফ অডিটর', 'Chief Auditor (CA)'),
(43, 'প্রকল্প পরিচালক', 'Project Director (PD)'),
(44, 'অতিরিক্ত প্রধান চিকিৎসা কর্মকর্তা', 'Addl. Chief Medical Officer (ACMO)'),
(45, 'ডেপুটি চিফ অব পার্সোনেল', 'Deputy Chief of Personnel (DCOP)'),
(46, 'অধ্যক্ষ', 'Principle'),
(47, 'উপ-প্রধান চিকিৎসা কর্মকর্তা', 'Deputy Chief Medical Officer (DCMO)'),
(48, 'উপ-প্রধান নিরীক্ষক', 'Deputy Chief Auditors (DCA)'),
(49, 'উপ-প্রধান অর্থ কর্মকর্তা', 'Deputy Chief Finance Officer (DCFO)'),
(50, 'এ্যাসিসটেন্ট চিফ অব পার্সোনেল', 'Assistant Chief of Personnel (ACOP)'),
(51, 'সহকারী অধ্যাপক', 'Assistant Professor'),
(52, 'উর্ধ্বতন লাইব্রেরিয়ান', 'Senior Librarian'),
(53, 'প্রধান শিক্ষক', 'Head Master'),
(54, 'উর্ধ্বতন কারিগরি কর্মকর্তা', 'Senior Technical Officer'),
(55, 'সহকারী প্রধান হিসাব রক্ষক', 'Assistant Chief Accountant (ACA)'),
(56, 'সহকারী প্রধান অর্থ কর্মকর্তা', 'Assistant Chief Finance Officer (ACFO)'),
(57, 'সহকারী প্রধান নিরীক্ষক', 'Assistant Chief Auditor'),
(58, 'কম্পিউটার অপারেটর', 'Computer Operator'),
(59, 'ডাটা এন্ট্রি অপারেটর', 'Data Entry Operator'),
(60, 'উর্ধ্বতন কর্মকর্তা আইসিটি', 'Senior Officer ICT'),
(61, 'রেকর্ড স্টার', 'Record Sorter'),
(62, 'উপ-সহকারী প্রকৌশলী', 'Sub Assistant Engineer (SAE)'),
(63, 'ব্যবস্থাপনা পরিচালক (অতিরিক্ত দায়িত্ব)', 'Managing Director (Addl.C.)'),
(64, 'ব্যবস্থাপনা পরিচালক (চলতি দায়িত্ব)', 'Managing Director (C.C.)'),
(65, 'কর্মকর্তা', 'Officer'),
(66, 'অপারেশন কর্মকর্তা', 'Production Officer'),
(67, 'সহকারী অধ্যক্ষ অফিসার', 'Assistant Principle Officer'),
(68, 'উপ-সহকারী রসায়নবিদ', 'Sub Assistant Chemist'),
(69, 'উর্ধ্বতন কর্মকর্তা', 'Senior Officer'),
(70, 'প্রশিক্ষণার্থী প্রকৌশলী', 'Trainee Engineer'),
(71, 'জেনারেটর অপারেটর', 'Generator Operator'),
(72, 'সহকারী প্রশিক্ষক', 'Assistant Instructor'),
(73, 'জুনিয়র প্রশিক্ষক', 'Junior Instructor'),
(74, 'প্রশিক্ষক', 'Instructor'),
(75, 'জুনিয়র কর্মকর্তা', 'Junior Officer'),
(76, 'প্রোডাকশন শিফট ইনচার্জ', 'Production Shift Incharge'),
(77, 'অপারেশন উর্ধ্বতন কর্মকর্তা', 'Production Senior Officer'),
(78, 'অপারেশন প্রকৌশলী', 'Production Engineer'),
(79, 'তথ্য সুরক্ষা কর্মকর্তা', 'Data Protection Officer'),
(80, 'জুনিয়র প্রকৌশলী', 'Junior Engineer'),
(81, 'প্রকল্প প্রযুক্তি', 'Project Technology'),
(82, 'জুনিয়র সহকারী প্রকৌশলী', 'Junior Assistant Engineer'),
(83, 'উর্ধ্বতন ইলেকট্রিশিয়ান', 'Senior Electrician'),
(84, 'নিরাপত্তা কর্মকর্তা', 'Security Officer'),
(85, 'ট্রেইনি কর্মকর্তা', 'Trainee Officer'),
(86, 'মেশিনারি ফিটার', 'Machinery Fitter'),
(87, 'সহকারী নিরাপত্তা কর্মকর্তা', 'Assistant Security Officer'),
(88, 'বন কর্মকর্তা', 'Forest Officer'),
(89, 'শিক্ষক', 'Teacher'),
(90, 'প্রকৌশলী', 'Engineer'),
(91, 'এলডিএ কাম-টাইপিষ্ট', 'LDA Cum-Typist'),
(92, 'সিনিয়ার ক্লার্ক', 'Senior Clark'),
(93, 'ক্যাশিয়ার', 'Cashier'),
(94, 'সহকারী প্রধান শিক্ষক', 'Assistant Headmaster'),
(95, 'সহকারী স্টোর কিপার', 'Assistant Store Keper'),
(96, 'স্টোর কিপার', 'Store Keper'),
(97, 'সহকারী শিক্ষক', 'Assistant Teacher'),
(98, 'লাইব্রেরীয়ান', 'Librerian'),
(99, 'সহকারী শিক্ষক', 'Assistant Teacher'),
(100, 'সিনিয়ার লেকচারার', 'Senior Lecturer'),
(101, 'দক্ষ অপারেটর (এস.ও-২)', 'Skilled Operator (S.O.)-2'),
(102, 'দক্ষ অপারেটর (এস.ও-১)', 'Skilled Operator (S.O.)-1'),
(103, 'উচ্চ দক্ষ অপারেটর (এইচএসও)', 'High Skilled Operator (HSO)'),
(104, 'মাস্টার অপারেটর (এমও)', 'Master Operator (MO)'),
(105, 'উপ-সহকারী কারিগরি কর্মকর্তা', 'Sub-Assistant Technical Officer'),
(106, 'সহকারী মেডিকেল অফিসার', 'Assistant Medical Officer'),
(107, 'সহকারী পরিবহন কর্মকর্তা', 'Assistant Transport Officer'),
(108, 'সহকারী সমন্বয়ক কর্মকর্তা', 'Assistant Coordination Officer'),
(109, 'সহকারী ব্যক্তিগত কর্মকর্তা', 'Assistant Personnel Officer'),
(110, 'ব্যক্তিগত কর্মকর্তা', 'Personal Officer'),
(111, 'উপ-প্রধান রসায়নবিদ', 'Deputy Chief Chemist (DCC)'),
(112, 'প্রভাষক', 'Lecturer'),
(113, 'প্রশাসনিক কর্মকর্তা', 'Administrative Officer'),
(114, 'সহকারী মার্কেটিং কর্মকর্তা', 'Assistant Marketing Officer'),
(115, 'মাস্টার টেকনিশিয়ান', 'Master Technician'),
(116, 'প্রসেস অপারেটর', 'Process Operator'),
(117, 'উচ্চ দক্ষ টেকনিশিয়ান (এইচএসটি)', 'High Skilled Technician (HST)'),
(118, 'স্টোর কর্মকর্তা', 'Store Officer'),
(119, 'জুনিয়র ক্লার্ক', 'Junior Clark'),
(120, 'মডেলার', 'Modeller'),
(121, 'সহকারী মডেলার', 'Assistant Modeller'),
(122, 'নিরীক্ষা অফিসার', 'Audit Officer'),
(123, 'স্টেনোগ্রাফার', 'Stenographer'),
(124, 'টেলিফোন অপারেটর', 'Telephone Operator'),
(125, 'সেমি স্কিলড অপারেটর (এসএসও)', 'Semi Skilled Operator (SSO)'),
(126, 'সেমি স্কিলড টেকনিশিয়ান (এসএসটি)', 'Semi Skilled Technician (SST)'),
(127, 'দক্ষ টেকনিশিয়ান (এসটি)', 'Skilled Technician (ST)'),
(128, 'সহকারী ক্যাশিয়ার', 'Assistant Cashier'),
(129, 'হিসাব সহকারী', 'Accounts Assistant'),
(130, 'সুপারভাইজার', 'Supervisor'),
(131, 'অতিরিক্ত প্রধান বীমা কর্মকর্তা', 'Addl. Chief Insuarance Officer (ACIO)'),
(132, 'অতিরিক্ত প্রধান নিরীক্ষক', 'Additional Chief Auditor'),
(133, 'জুনিয়র প্রোগ্রামার', 'Junior Programmer'),
(134, 'সিকিউরিটি গার্ড', 'Security Guard'),
(135, 'এমএলএসএস', 'MLSS'),
(136, 'অফিস সহকারী', 'Office Assistant'),
(137, 'এসটিজি.কাম- কম্পিউটার অপারেটর', 'Stg.Cum. Computer Operator'),
(138, 'ড্রাইভার', 'Driver'),
(139, 'বিক্ষোভকারী', 'Demonstrator'),
(140, 'ইমাম', 'IMAM'),
(141, 'ইলেকট্রিসিনা', 'Electricina'),
(142, 'ক্রয় সহকারী', 'Purchase Assistant'),
(143, 'মেকানিক', 'Mechanic'),
(144, 'মার্কেটিং সহকারী', 'Marketing Assistant'),
(145, 'এসটি. কাম-কম্পিউটার অপারেটর', 'St. Cum. Computer Operator'),
(146, 'সিনিয়র শ্রম কল্যাণ কর্মকর্তা', 'Senior labour welfare officer'),
(147, 'উপাধ্যক্ষ', 'Vice principal'),
(148, 'সিনিয়র ইন্স্যুরেন্স অফিসার', 'Senior Insurance officer'),
(149, 'সুপারিনটেনডেন্ট', 'Superintendent'),
(150, 'বীমা কর্মকর্তা', 'Insurance Officer'),
(151, 'পরিসংখ্যানবিদ', 'Statistician'),
(152, 'স্টোর অফিসার', 'Store officer'),
(153, 'ফায়ার অ্যান্ড সেফটি অফিসার', 'Fire & Safety Officer'),
(154, 'আইনি কর্মকর্তা', 'legal Officer'),
(155, 'উপ-প্রধান রসায়নবিদ', 'Deputy Chief Chemist'),
(156, 'ক্রয় শিপিং অফিসার', 'Purchase Shipping Officer'),
(157, 'সহকারী সুপারিনটেনডেন্ট', 'Assistant Superintendent'),
(158, 'বাণিজ্যিক কর্মকর্তা', 'Commercial officer'),
(159, 'এস্টেট অফিসার', 'Estate officer'),
(160, 'চেয়ারম্যান', 'Chairman');

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_year`
--

CREATE TABLE `fiscal_year` (
  `id` int(11) NOT NULL,
  `fiscal_year` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fiscal_year`
--

INSERT INTO `fiscal_year` (`id`, `fiscal_year`) VALUES
(8, '২০১৭-২০১৮'),
(7, '২০১৮-২০১৯'),
(6, '২০১৯-২০২০'),
(5, '২০২০-২০২১'),
(4, '২০২১-২০২২'),
(3, '২০২২-২০২৩'),
(2, '২০২৩-২০২৪'),
(1, '২০২৪-২০২৫'),
(9, '২০২৫-২০২৬');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `innovation_tbl`
--

INSERT INTO `innovation_tbl` (`id`, `fiscal_year`, `title_of_invention`, `inventors_name`, `inventors_designation`, `inventors_emp_id`, `proposed_workplace`, `des_of_invention`, `imple_status`, `replicate_eligibility`, `feedback`, `service_link`, `remarks`, `created_at`) VALUES
(1, '২০১৮-২০১৯', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'জনাব মোঃ হাইয়ুল কাইয়ুম', 'চেয়ারম্যান', '', 'বিসিআইসি প্র: কা:', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'বাস্তবায়িত', 'যোগ্য', 'অপ্রত্যাশিত', '', '', '2022-07-26 11:24:46'),
(2, '২০১৮-২০১৯', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'জনাব মোঃ মোহাদ্দেস হোসেন', 'উপ প্রধান রসায়নবিদ', '', 'জেএফসিএল', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-26 11:24:46'),
(3, '২০১৮-২০১৯', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', 'জনাব চৌধুরী মোহাম্মদ হারুন', 'মহাব্যবস্থাপক', '', 'টিএসপিসিএল', 'সালফিউরিক এসিড প্ল্যান্ট চালু করার পর এর কনভার্টারের বিভিন্ন বেডের তাপমাত্রা ডিজাইন মানে স্থির না হওয়া পর্যন্ত সময়ে অতিমাত্রায় নির্গত SO2, SO3  কে নিয়ন্ত্রণের জন্য বর্তমানে প্রায় সর্বত্রই সালফিউরিক এসিড প্ল্যান্টে স্ক্রাবার ব্যবহৃত হয়ে থাকে। প্ল্যান্ট চালুর শুরুতে অতিমাত্রায় নির্গত SO2, SO3  স্ক্রাবিং প্রক্রিয়ায় কষ্টিক সোডা দ্রবণের সাথে বিক্রিয়া করে সোডিয়াম সালফাইট ও সোডিয়াম সালফেট উৎপন্ন করার ফলে গ্যাস এর নিঃসরন উল্লেখযোগ্য মাত্রায় হ্রাস পেয়েছে। ফলশ্রুতিতে, জনস্বাস্থ্য ও পরিবেশ হুমকিমুক্ত রেখে সালফিউরিক এসিড প্ল্যান্ট চালিয়ে দেশে সার, বিদ্যুৎ, সমরাস্ত্র ও অন্যান্য গুরুত্বপূর্ণ সেক্টরসমূহে সালফিউরিক এসিড সরবরাহ অব্যাহত রাখা সম্ভব হচ্ছে। প্রতিবার প্ল্যান্ট চালুর সময় ৩-৪ ঘন্টা স্ক্রাবার চালু রাখার প্রয়োজন হয়। প্ল্যান্টের সকল প্যারামিটার স্বাভাবিক হলে পরবর্তীতে স্ক্রাবার ছাড়া প্ল্যান্ট চালু রাখা হয়।\r\nTCV:no\r\nFlowchart: আছে। \r\nপুরুস্কার গ্রহনের ছবি নাই', 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', '2022-07-26 11:24:46'),
(4, '২০১৮-২০১৯', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '', 'বিসিআইসি প্র: কা:', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'চলমান', 'বিশেষায়িত', '', '', '', '2022-07-26 11:24:46'),
(5, '২০১৯-২০২০', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '', 'বিসিআইসি প্র: কা:', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-26 14:47:10'),
(6, '২০১৯-২০২০', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা।', 'জনাব শাহীন মাহমুদ', 'উপ-প্রধান প্রকৌশলী', '', 'টিএসপিসিএল', 'কারখানার ফসফরিক এসিড প্ল্যান্টে উৎপাদিত ২৮.৫% P2O5 ফসফরিক এসিডের মধ্যে অপদ্রব্য হিসেবে ফ্লুসিলিসিক (H2SiF6) এসিড থাকে। এই ২৮.৫% P2O5 ফসফরিক এসিড হতে কনসেনট্রেটেড ফসফরিক এসিড (৪৮.৫% P2O5) তৈরীর সময় ফ্লুসিলিসিক এসিড ফিউম আকারে কনসেনট্রেটরে ভ্যাকুয়াম (720mmHg) সৃষ্টিতে ব্যবহৃত ওয়াশ ওয়াটারের সাথে চলে যাওয়ার কারণে মূলতঃ ড্রেন ওয়াটারের PH কমে যায়। কারখানার ফসফরিক এসিড প্ল্যান্টের কনসেনট্রেটর ইউনিট মডিফিকেশন করে ফ্লুসিলিসিক এসিড (H2SiF6) অপসারণ করাসহ জিরো ডিসচার্জ (ওয়াশ ওয়াটার বার বার রিসাইকেল) সিস্টেম চালু করা হয়েছে এতে কারখানা পরিবেশ বান্ধব হয়েছে।\r\nTCV: নাই। কার্যক্রম চলমান।\r\nFlowchart: পাঠাবে।\r\nপুরুস্কার গ্রহনের ছবি নাই।\r\n', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-30 17:29:54'),
(7, '২০১৯-২০২০', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'মোহাম্মদ সোহরাব হোসেন', 'উপ-প্রধান রসায়নবিদ', '', 'ইউজিএসএফএল', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', '2022-07-30 17:36:17'),
(8, '২০১৯-২০২০', 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', 'ড. এম এম এ কাদের', 'ব্যবস্থাপনা পরিচালক', '৫৬২০-১', '', 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', 'চলমান', 'যোগ্য', 'প্রত্যাশিত', '', '', '2022-11-15 15:37:43'),
(10, '২০২০-২০২১', 'শুষ্ক মৌসুমে ওয়াসার পানির সাহায্যে ডেমি পানি উৎপাদন', 'সনাতন চন্দ্র দে', 'উপ-প্রধান রসায়নবিদ', '', 'টিএসপিসিএল', 'শুষ্ক মৌসুমে ওয়াসার পানির সাহায্যে ডেমি পানি উৎপাদন', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2022-12-14 09:57:44'),
(11, '২০২১-২০২২', 'এসএফসিএল এর অ্যামোনিয়া বোতলিং স্টেশনে অ্যামোনিয়া ভেসেলের ইনলেট লাইন মডিফিকেশন', 'জনাব গোপাল চন্দ্র ঘোষ', 'অতিরিক্ত প্রধান রসায়নবিদ', '', 'এসএফসিএল', 'এসএফসিএল এর অ্যামোনিয়া বোতলিং স্টেশনে অ্যামোনিয়া ভেসেলের ইনলেট লাইন মডিফিকেশন', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2022-12-14 10:01:51'),
(19, '২০২৩-২০২৪', 'টিআইসিআই এর এক্সপার্ট সার্ভিস বিল এর হিসাব বিষয়ক প্রোগ্রাম।', 'জনাব মোঃ মহিউদ্দীন', '', '', 'টিএসপিসিএল', 'টিআইসিআই এর এক্সপার্ট সার্ভিস বিল এর হিসাব বিষয়ক প্রোগ্রাম।', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2026-02-22 13:14:55'),
(20, '২০২৩-২০২৪', 'SFCL এর ইউরিয়া প্ল্যান্টের উদ্বাবনী কার্যক্রম হিসেবে PCT এ Purified Process Condensate Cooler হিসেবে পূর্বের Plate type Cooler এর পরিবর্তে Shell & Tube Type স্থাপন।', 'ছরোয়ার হোসেন রিপন', 'উপ-প্রধান প্রকৌশলী', '', 'এসএফসিএল', 'SFCL এর ইউরিয়া প্ল্যান্টের উদ্বাবনী কার্যক্রম হিসেবে PCT এ Purified Process Condensate Cooler হিসেবে পূর্বের Plate type Cooler এর পরিবর্তে Shell & Tube Type স্থাপন।', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2026-02-23 14:36:00'),
(21, '২০২০-২০২১', 'কারখানার Important equipment, instruments এবং pipeline মেরামত সংক্রান্ত Job history সংরক্ষণ করা।', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক', '', 'বিসিআইসি প্র: কা:', 'বিসিআইসি’র সকল কারখানার অনাকাঙ্খিত শাট-ডাউন, মেনটেন্যান্স কস্ট, উৎপাদন খরচ, দূর্ঘটনা বেড়ে গেছে ও Attainable capacity এবং Efficiency কমে গেছে। কারখানার গুরুত্বপূর্ণ সকল ইকুইপমেন্ট, ইন্সট্রুমেন্ট ও পাইপলাইন মেরামত সংক্রান্ত জব হিস্টোরি সংরক্ষন করা হচ্ছে, ফলে নিরবিছিন্ন উৎপাদন সম্ভব হচ্ছে এবং কারখানা আর্থিকভাবে লাভবান হচ্ছে।\r\n\r\nTCV:নাই\r\nFlowchart: নাই\r\nছবি: আছে। \r\n', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2026-02-24 11:49:11'),
(22, '২০২১-২০২২', 'এসএফসিএল এর ইউরিয়া গ্রানুলেশন প্ল্যান্টে Crusher Feed Hopper (T- 662) হতে ০১ (এক) টি Overflow Line স্থাপন।', 'জনাব মোহাম্মদ আজিজুল হক ', 'উপ-প্রধান প্রকৌশলী', '', 'এসএফসিএল', 'Crusher Feed Hopper () হতে ০১ (এক)টি Outlet Line স্থাপনের পর Crusher Maintenance কাজের সময় Granulation প্ল্যান্ট বন্ধ করার প্রয়োজন হচ্ছে না। যার ফলে Downtime কমিয়ে উৎপাদনের ধারা অব্যাহত রাখা সম্ভব হচ্ছে।\r\n\r\nসময়, ভ্রমন ও ব্যয় সাশ্রয়ঃ Crusher Feed Hopper হতে Overflow লাইনের স্থাপনের পূর্বে Crusher overload হয়ে ঘন ঘন V- Belt ছিড়ে যেত। বর্তমানে লাইনটি স্থাপনের ফলে V-Belt ছেড়া কমে এসেছে। এতে করে Maintenance কাজ কমে এসেছে। এছাড়া পূর্বে Maintenance কাজের সময় গ্রানুলেশন প্ল্যান্ট বন্ধ করার প্রয়োজন হতো। বর্তমানে Maintenance কাজের সময় গ্রানুলেশন প্ল্যান্ট বন্ধ করার প্রয়োজন হয় না। যার ফলে ডাউনটাইম কমিয়ে উৎপাদনের ধারা অব্যাহত রাখা সম্ভব হচ্ছে। Overflow লাইন স্থাপনের পূর্বে Maintenance কাজের জন্য প্রতি মাসে গড়ে ২ বার ৮-১০ ঘন্টা গ্রানুলেশন প্ল্যান্ট বন্ধ থাকতো এতে করে আনুমানিক ৫০০ মে: টন সার কম উৎপাদন হতো যার বাজার মূল্য ৭০ লক্ষ টাকা। সুতরাং Crusher Hopper হতে Overflow লাইন করার ফলে কারখানার প্রতি মাসে আনুমানিক ৭০ লক্ষ টাকা সাশ্রয় হয়েছে।\r\nFlowchart: আছে\r\nছবি : আছে।\r\n', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2026-02-24 11:53:32'),
(23, '২০২২-২০২৩', '3D Printing Technology ব্যবহারের মাধ্যমে High Voltage Panel (6.6kv) এ ব্যবহৃত অকেজো 86 Lockout Relay সমূহ পুন:ব্যবহার যোগ্য করে তোলা।', 'জনাব তরিকুল আলম', 'সহকারী প্রকৌশলী', '', 'জেএফসিএল', 'জেএফসিএল এর Switch gear রুমের High Voltage Panel সমূহে ব্যবহৃত 86 Lockout Relay গুলো দীর্ঘ দিন ব্যবহার হওয়ার ফলে ভেতরে থাকা পিনিয়ন ভেঙে যায় এবং এই পিনিয়নের কোন Spare কিংবা Dimensional Drawing না থাকায়  সম্পূর্ণ Relay Set পরিবর্তন করে নতুন Relay স্থাপন করা ছাড়া কোন বিকল্প থাকে না। 3D Modeling Software ব্যবহার করে ভেঙে যাওয়া পিনিয়নের একটি 3D Model তৈরি করে তা আধুনিক 3D Printing Technology ব্যবহারের মাধ্যমে অকেজো হয়ে যাওয়া Relay গুলো পুন:ব্যবহার যোগ্য করে তোলা হয়। প্রতিটি 86 lock out Relay  প্রায় ৫১,০০০/- টাকা দরে ক্রয় করা হয় এবং ক্রয় করতে ২ থেকে ২.৫ বছর সময় লাগে। 86 lock out Relay তে ব্যবহৃত pinion মাত্র ২০০ টাকায় 3D printing device এর মাধ্যমে তৈরি করে ব্যবহার করা হচ্ছে ফলে 86 lock out Relay ক্রয় বাবদ বিপুল পরিমাণ সময় ও অর্থ সাশ্রয় হচ্ছে।\r\nসময় ভ্রমন ও ব্যয় সাশ্রয়:\r\nবর্তমানে জেএফসিএল-এর ৭টি Sub Station এর High Voltage Panel গুলোতে সর্বমোট ৫৬টি (Transformer -১৫টি, High Voltage Motor -২৬টি, Busbar -১৪টি) ৮৬ Lockout Relay ব্যবহারে আছে। পিনিয়ন ভেঙে অকেজো হয়ে যাওয়ায় এবং Relay গুলোর কোন Spare Unit না থাকায় ২০২০-২০২১ অর্থ বছরে প্রতিটি Relay প্রায় ৫১০০০/- (৪৫৫.৫৫ এইচ) মূল্যে IOTM পদ্ধতিতে ক্রয় করা হয়। যা ছিল ব্যয় বহুল এবং সময় সাপেক্ষ। উল্লেখিত পদ্ধতিতে 3D Model তৈরি (শুধুমাত্র ১ম বার) এবং 3D Printing Technology ব্যবহার করে পিনিয়নটি তৈরি করে প্রতিটি অকেজো Relay কার্যক্ষম করতে খরচ হবে ২০০ (দুইশত টাকা)। এতে Relay প্রতি জেএফসিএল-এর সাশ্রয় হবে প্রায় ৫০,৮০০ ( পঞ্চাশ হাজার আটশত ) টাকা (১ এইচ =১১১.৮৭ টাকা) ।', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2026-02-24 13:04:54'),
(24, '২০২২-২০২৩', 'ইউরিয়া ও এ্যামোনিয়া প্ল্যান্ট থেকে সরবরাহকৃত Process Condensate লাইনে ওয়াটার কুলার স্থাপন করা।', 'জনাব মুহাম্মদ আসাদুজ্জামান', 'রসায়নবিদ', '', 'এসএফসিএল', 'অ্যামোনিয়া, ইউরিয়া এবং পাওয়ার প্লান্টে উৎপাদিত Process Condensate ও Steam Condensate এর মোট পরিমাণ প্রতি ঘন্টায় (৪২+২৯+২৬+৫)=১০২ টন। এই ১০২ টন Process Condensate ও Steam Condensate একত্রে মিলিত হয়ে ইউটিলিটি প্লান্টে সরবরাহ করা হতো। যার তাপমাত্রা প্রায় ৭০° সেঃ। \r\nউচ্চ তাপমাত্রায় Condensate Raw Water Tank এ প্রেরণ করা হলে Raw Water Tank এর Condensate মিশ্রিত পানির তাপমাত্রা বৃদ্ধি পেয়ে সহনীয় মাত্রার চেয়ে বেশি হয়ে যেত। Raw Water Tank থেকে মিশ্রিত পানি Activated Carbon Filter হয়ে Cation, Anion এবং Mixed Bed Polisher এর রেজিন Damage হয়ে যাওয়ার সম্ভাবনা থাকায় উল্লিখিত ১০২ টন Condensate Drain করা হতো।\r\nস্থাপিত Cooler এর মাধ্যমে প্রতি ঘন্টায় ১০২ টন Process Condensate ঠান্ডা করে Utility Plant এ Raw Water হিসেবে Raw demi water tank এ প্রেরণ করা হয়। প্রতি ঘন্টায় ১০২ টন Process Condensate পূনঃব্যবহারের ফলে Raw Water Intake হ্রাস পেয়েছে। যার উৎপাদন খরচ ৩৬৭২ টাকা। \r\n\r\nসময়, ভ্রমন ও ব্যয় সাশ্রয়:\r\nউদ্যোগের ফলে \r\n\r\nপ্রতি ঘন্টায় ৩৬৭২ টাকা\r\nদৈনিক ৩,৬৭২*২৪ = ৮৮,১২৮ টাকা\r\nমাসিক ৮৮,১২৮*৩০ = ২,৬৪,৩৮৪ টাকা\r\nবার্ষিক ৩৬৭২*৩৩০ = ২,৯০,৮২,২৪০ টাকা সাশ্রয় হচ্ছে।', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2026-02-24 13:07:06'),
(25, '২০২২-২০২৩', 'টিএসপি কমপ্লেক্স এর এসএ-২ প্ল্যান্টের সেকেন্ড ইকনোমাইজার আউটলেট গ্যাসের তাপমাত্রা নিয়ন্ত্রণ, ডেমি ওয়াটার ড্রেনরোধসহ মাল্টিপল বেনিফিট অর্জন।', 'জনাব সেন সুখেন চন্দ্র', 'মহাব্যবস্থাপক', '', 'টিএসপিসিএল', 'উদ্ভাবনী উদ্যোগের ফলে,\r\nক) ডেমি ওয়াটার ড্রেন করে প্রসেস নিয়ন্ত্রন করতে হয়না বিধায় কারখানার আর্থিকভাবে ব্যাপক সাশ্রয় হচ্ছে। উল্লেখ্য ১ টন ডেমি ওয়াটার তৈরী করতে ৪৭৩ টাকা খরচ হয়।\r\nখ) সেকেন্ড ইকনোমাইজার গ্যাস আউটলেট তাপমাত্রা নিয়ন্ত্রনের জন্য বর্ধিত প্রিহিটেড ডেমি ওয়াটার দানাদার প্ল্যান্টের বয়লারে ব্যবহার করায় সেখানে স্টীম জেনারেশন বৃদ্ধি পেয়েছে এবং ন্যাচারাল গ্যাস (এনজি) এর ব্যবহার হ্রাস পেয়েছে। এতে আর্থিক সাশ্রয় হচ্ছে।\r\nগ) সেকেন্ড ইকনোমাইজার আউটলেট গ্যাসের তাপমাত্রা নিয়ন্ত্রনের ফলে পানিতে সালফার ট্রাই অক্সাইডের অ্যাবজর্পশন বৃদ্ধি পাওয়ায় সালফিউরিক এসিডের উৎপাদন বৃদ্ধি পেয়েছে। এতে কারখানা আর্থিকভাবে লাভবান হচ্ছে।\r\nঘ) সেকেন্ড ইকনোমাইজার আউটলেট গ্যাস তাপমাত্রা নিয়ন্ত্রনের ফলে বায়ুমন্ডলে সালফার-ট্রাই-অক্সাইড গ্যাস নির্গমন হ্রাস পেয়েছে। এতে পরিবেশ দূষণ হ্রাস পেয়েছে।\r\nসময়, ভ্রমন ও ব্যয় সাশ্রয়:\r\nউদ্যোগের ফলে দৈনিক ২০ মেট্রিক টন Drain হওয়া Demi Water সাশ্রয় হচ্ছে যার প্রতি মেট্রিক টন Demi Water উৎপাদনে খরচ হয় ৪৭৬.০০ টাকা । Demi Water পূণঃব্যবহারের ফলে\r\nদৈনিক 		২০*৪৭৬ = ৯,৪৬০ টাকা\r\nমাসিক 		৯,৪৬০*৩০ = ২,৮৬,৮০০ টাকা\r\nবার্ষিক 		২,৮৬,৮০০*১২ = ৩১,২১,৮০০ টাকা সাশ্রয় হচ্ছে।', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2026-02-24 13:08:32');

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fiscal_year`
--
ALTER TABLE `fiscal_year`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fiscal_year` (`fiscal_year`);

--
-- Indexes for table `innovation_tbl`
--
ALTER TABLE `innovation_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `fiscal_year`
--
ALTER TABLE `fiscal_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `innovation_tbl`
--
ALTER TABLE `innovation_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
