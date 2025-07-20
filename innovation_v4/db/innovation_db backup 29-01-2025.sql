-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 06:50 AM
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
(17, 'চেয়ারম্যান (গ্রেট-১)', 'Chairman (Grade-1)'),
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
(155, 'ড্রাফটসম্যানশিপ', 'Draftsmanship'),
(156, 'ক্রয় শিপিং অফিসার', 'Purchase Shipping Officer'),
(157, 'সহকারী সুপারিনটেনডেন্ট', 'Assistant Superintendent'),
(158, 'বাণিজ্যিক কর্মকর্তা', 'Commercial officer'),
(159, 'এস্টেট অফিসার', 'Estate officer');

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
(1, '২০২৪-২০২৫');

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
(1, '২০১৮-১৯', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'জনাব মোঃ হাইয়ুল কাইয়ুম', 'চেয়ারম্যান, বিসিআইসি', '', 'বিসিআইসি প্র: কা:', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', 'বাস্তবায়িত', 'যোগ্য', 'অপ্রত্যাশিত', '', '', '2022-07-26 11:24:46'),
(2, '২০১৮-১৯', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'জনাব মোঃ মোহাদ্দেস হোসেন', 'উপ প্রধান রসায়নবিদ', '', 'জেএফসিএল', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-26 11:24:46'),
(3, '২০১৮-১৯', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', 'জনাব চৌধুরী মোহাম্মদ হারুন', 'মহাব্যবস্থাপক (অপারেশন)', '', 'টিএসপিসিএল', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', '2022-07-26 11:24:46'),
(4, '২০১৮-১৯', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '', 'বিসিআইসি প্র: কা:', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', 'চলমান', 'বিশেষায়িত', '', '', '', '2022-07-26 11:24:46'),
(5, '২০১৯-২০', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', '', 'বিসিআইসি প্র: কা:', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-26 14:47:10'),
(6, '২০১৯-২০', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা', 'জনাব শাহীন মাহমুদ', 'উপ-প্রধান প্রকৌশলী (রসায়ন)', '', 'টিএসপিসিএল', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা', 'বাস্তবায়িত', 'যোগ্য', '', '', '', '2022-07-30 17:29:54'),
(7, '২০১৯-২০', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'মোহাম্মদ সোহরাব হোসেন', 'উপ-প্রধান রসায়নবিদ', '', 'ইউজিএসএফএল', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', '2022-07-30 17:36:17'),
(8, '২০১৯-২০', 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', 'ড. এম এম এ কাদের', 'ব্যবস্থাপনা পরিচালক', '৫৬২০-১', '', 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', 'চলমান', 'যোগ্য', 'প্রত্যাশিত', '', '', '2022-11-15 15:37:43'),
(10, '২০২০-২১', 'শুষ্ক মৌসুমে ওয়াসার পানির সাহায্যে ডেমি পানি উৎপাদন', 'সনাতন চন্দ্র দে', 'উপ-প্রধান রসায়নবিদ', '', 'টিএসপিসিএল', 'শুষ্ক মৌসুমে ওয়াসার পানির সাহায্যে ডেমি পানি উৎপাদন', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2022-12-14 09:57:44'),
(11, '২০২১-২২', 'এসএফসিএল এর অ্যামোনিয়া বোতলিং স্টেশনে অ্যামোনিয়া ভেসেলের ইনলেট লাইন মডিফিকেশন', 'জনাব গোপাল চন্দ্র ঘোষ', 'অতিরিক্ত প্রধান রসায়নবিদ', '', 'এসএফসিএল', 'এসএফসিএল এর অ্যামোনিয়া বোতলিং স্টেশনে অ্যামোনিয়া ভেসেলের ইনলেট লাইন মডিফিকেশন', 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', '2022-12-14 10:01:51');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `fiscal_year`
--
ALTER TABLE `fiscal_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `innovation_tbl`
--
ALTER TABLE `innovation_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
