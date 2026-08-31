-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 12:08 PM
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
-- Database: `training_certificate_gen_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `authority_tbl`
--

CREATE TABLE `authority_tbl` (
  `id` int(11) NOT NULL,
  `batch` varchar(50) NOT NULL,
  `training_title` longtext NOT NULL,
  `organized_by` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `name1` varchar(255) NOT NULL,
  `designation1` varchar(100) NOT NULL,
  `office1` varchar(255) NOT NULL,
  `ministry1` varchar(100) NOT NULL,
  `signature1` varchar(255) NOT NULL,
  `name2` varchar(255) NOT NULL,
  `designation2` varchar(100) NOT NULL,
  `office2` varchar(100) NOT NULL,
  `ministry2` varchar(100) NOT NULL,
  `signature2` varchar(255) DEFAULT NULL,
  `active_status` varchar(10) NOT NULL,
  `tr_link_status` varchar(50) NOT NULL,
  `name3` varchar(255) DEFAULT NULL,
  `designation3` varchar(100) DEFAULT NULL,
  `office3` varchar(100) DEFAULT NULL,
  `ministry3` varchar(100) DEFAULT NULL,
  `signature3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `exam_date` date NOT NULL,
  `start_time` time(6) NOT NULL,
  `end_time` time(6) NOT NULL,
  `active_exam` varchar(100) NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authority_tbl`
--

INSERT INTO `authority_tbl` (`id`, `batch`, `training_title`, `organized_by`, `start_date`, `end_date`, `name1`, `designation1`, `office1`, `ministry1`, `signature1`, `name2`, `designation2`, `office2`, `ministry2`, `signature2`, `active_status`, `tr_link_status`, `name3`, `designation3`, `office3`, `ministry3`, `signature3`, `created_at`, `updated_at`, `exam_date`, `start_time`, `end_time`, `active_exam`) VALUES
(1, '2', 'Computer Troubleshooting', 'by ICT Division, BCIC', '2026-03-29', '2026-03-31', 'Md. Delwar Hossain', 'Senior GM', 'ICT Division, BCIC', 'MoInd', 'uploads/69cb9a7a13322_68b678ba583e0_signature 300_80.jpg', 'Md. Fazlur Rahman', 'Chairman', 'BCIC', 'MoInd', 'uploads/69cb9a7a134d0_68b678f221253_signature 300_80.jpg', 'active', 'Inactive', NULL, NULL, NULL, NULL, NULL, '2026-03-29 03:38:17', '2026-03-31 09:57:14', '2026-03-30', '16:00:00.000000', '16:15:00.000000', 'active'),
(4, '1', 'Web Portal Training', 'ICT Division, BCIC', '2026-01-26', '2026-01-27', 'S1', 'GM (ICT)', 'BCIC', 'BCIC', 'uploads/697701c9da281_images.jpg', 'S2', 'Dir (Tech)', 'BCIC', 'BCIC', 'uploads/697701c9da6e0_images.jpg', 'Inactive', 'Inactive', NULL, NULL, NULL, NULL, NULL, '2026-01-25 23:55:21', '2026-03-08 18:00:00', '0000-00-00', '00:00:00.000000', '00:00:00.000000', 'inactive'),
(5, '3', 'Faltu Training', 'by ICT Division, BCIC', '2026-03-31', '2026-04-01', 'Mr. Md. Solaiman', 'Project Director (PD)1', 'Bangladesh Chemical Industries Corporation', 'Under Ministry of Industries', 'uploads/69cb9b483beb7_68b682abec2eb_signature 300_80.jpg', 'Fazlur Rahman', 'Chairman', 'BCIC', 'MoInd', 'uploads/69cb9b483c03f_68bd4c457cdd8_signature1_1756627932.jpg', 'Inactive', 'active', NULL, NULL, NULL, NULL, NULL, '2026-03-31 10:00:40', '2026-03-31 10:00:40', '0000-00-00', '00:00:00.000000', '00:00:00.000000', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_set`
--

CREATE TABLE `evaluation_set` (
  `id` int(11) NOT NULL,
  `batch` int(11) NOT NULL,
  `evaluation_question_name` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `evaluation_date` date NOT NULL,
  `evaluation_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_set`
--

INSERT INTO `evaluation_set` (`id`, `batch`, `evaluation_question_name`, `option_a`, `option_b`, `option_c`, `option_d`, `evaluation_date`, `evaluation_status`) VALUES
(1, 2, 'বিষয়বস্তুর উপর প্রশিক্ষকের জ্ঞান', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(2, 2, 'বিষয় উপস্থাপনার দক্ষতা', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(3, 2, 'হার্ডওয়‍্যার ট্রাবলশুটিং ডেমো দেওয়ার দক্ষতা', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(4, 2, 'বাস্তব উদাহরণ ব্যবহারের সক্ষমতা', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(5, 2, 'প্রশিক্ষণ উপকরণ (স্লাইড/নোট) এর মান', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(6, 2, 'প্রশ্নের উত্তর দেওয়ার দক্ষতা', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(7, 2, 'সময় ব্যবস্থাপনা', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(8, 2, 'প্রশিক্ষণার্থীদের সাথে যোগাযোগ', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(9, 2, 'ল্যাব/প্র্যাকটিক্যাল সেশন পরিচালনা', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active'),
(10, 2, 'সমস্যা সমাধানে গাইড করার দক্ষতা', 'খারাপ', 'মোটামুটি', 'ভালো', 'চমৎকার', '2026-03-30', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `question_set`
--

CREATE TABLE `question_set` (
  `id` int(11) NOT NULL,
  `batch` int(11) NOT NULL,
  `question_name` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_set`
--

INSERT INTO `question_set` (`id`, `batch`, `question_name`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 2, 'নিচের কোনটি ইনপুট ডিভাইস?', 'Printer', 'Monitor', 'Keyboard', 'Speaker', 'C'),
(2, 2, 'কম্পিউটারের স্থায়ী মেমোরি কোনটি?', 'RAM', 'Cache', 'ROM', 'Register', 'C'),
(3, 2, 'কম্পিউটারের মস্তিষ্ক বলা হয় কোনটিকে?', 'RAM', 'CPU', 'Hard Disk', 'Monitor', 'B'),
(4, 2, 'অপারেটিং সিস্টেম কী?', 'হার্ডওয়্যার', 'সফটওয়্যার', 'ডিভাইস', 'নেটওয়ার্ক', 'B'),
(5, 2, 'Windows কী ধরনের সফটওয়্যার?', 'Application', 'Utility', 'System', 'Antivirus', 'C'),
(6, 2, 'নিচের কোনটি হার্ডওয়্যার?', 'Linux', 'Photoshop', 'Printer', 'MS Excel', 'C'),
(7, 2, 'Antivirus সফটওয়্যার ব্যবহৃত হয়—', 'ফাইল তৈরি করতে', 'ভাইরাস দূর করতে', 'ইন্টারনেট চালাতে', 'প্রিন্ট করতে', 'B'),
(8, 2, 'ই-মেইল ব্যবহৃত হয়—', 'গান শোনার জন্য', 'বার্তা পাঠাতে', 'ছবি আঁকতে', 'হিসাব করতে', 'B'),
(9, 2, 'LAN এর পূর্ণরূপ কী?', 'Local Area Network', 'Large Area Network', 'Long Area Network', 'Logical Area Network', 'A'),
(10, 2, 'Virus কী?', 'হার্ডওয়্যার', 'সফটওয়্যার', 'ক্ষতিকর প্রোগ্রাম', 'অপারেটিং সিস্টেম', 'C');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `place_of_posting` varchar(100) DEFAULT NULL,
  `office` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `batch` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `serial_no` varchar(255) NOT NULL,
  `question_all` text NOT NULL,
  `answer_all` text NOT NULL,
  `remarks` longtext NOT NULL,
  `feedback` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `place_of_posting`, `office`, `mobile_no`, `email_id`, `password`, `role`, `status`, `batch`, `created_at`, `updated_at`, `serial_no`, `question_all`, `answer_all`, `remarks`, `feedback`) VALUES
(1, '5620-0', 'sadmin', NULL, NULL, NULL, 'BCIC', 'BCIC', '01718834655', 'test@yahoo.com', '1234', 'sadmin', 'active', '', '2026-01-22 08:28:08', '2026-01-22 08:28:08', '', '', '', '', ''),
(2, '5692-9', 'Mohammad Azim Uddin', 'Deputy Manager (Administration)', 'Administration', 'LSA', 'DAPFCL', 'DAPFCL', '01515680819', 'azimlawcu@gmail.com', '$2y$10$HAPfVPlN/psx.adZslVmD.SubZZKdfUTS3tcNwberU1tEQcPF9RCC', 'user', 'active', '1', '2026-01-26 05:58:01', '2026-02-05 09:59:41', 'BCIC-ICT-DIVISION-B1-2', '', '', '', ''),
(3, '5619-2', 'Mohammad Saiful Islam', 'Programmer', NULL, NULL, 'TSP Complex Limited', NULL, '01885406868', 'saiful.on@gmail.com', '$2y$10$lswDmd6wsQFFcrVmM3G.E.LhcRwpjMXbnkjotfL17NL4LjkWSheZa', 'user', 'active', '1', '2026-01-26 05:59:15', '2026-02-05 09:59:55', 'BCIC-ICT-DIVISION-B1-3', '', '', '', ''),
(4, '5053-4', 'Naba Krishna Sardar', 'Additional Chief Chemist', NULL, NULL, 'DAPFCL', NULL, '01717658284', 'nabokrishna@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:20', '2026-02-05 10:00:02', 'BCIC-ICT-DIVISION-B1-4', '', '', '', ''),
(5, '5911-3', 'Seema Khatun', 'Assistant manager (com)', 'Purchase', '', 'BCIC', '', '01943146857', 'seema725571@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:28', '2026-03-29 10:15:27', 'BCIC-ICT-DIVISION-B1-5', '', '', '', ''),
(6, '6769-4', 'Kazi Mahtab-ul-Islam', 'Sub Assistant Chemist', NULL, NULL, 'Chhatak Cement Company Limited', NULL, '01676339522', 'mahtabshawon@gmail.com', '$2y$10$2RZ6Bujh6dtUQocHJtbMd.aTVTSPGzriXuStGp/xlvGtzfBZSMdHq', 'user', 'active', '1', '2026-01-26 06:01:20', '2026-02-05 10:00:13', 'BCIC-ICT-DIVISION-B1-6', '', '', '', ''),
(7, '5726-5', 'Samiran Marma', 'Deputy Manager (Admin)', 'Administration', 'MD Office', 'CUFL', 'Chittagong Urea Fertilizer Limited', '01784607581', 'marmasamiran@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:01:43', '2026-02-05 10:00:18', 'BCIC-ICT-DIVISION-B1-7', '', '', '', ''),
(8, '5753-9', 'RAJIB KUMAR PAUL', 'Deputy Manager(Administration)', 'Administration', '', 'Chhatak Cement Company Limited', '', '01842710472', 'paulrajib15du@gmail.com', '$2y$10$ZMMDGKmiZu6NCd7eDErK0OzcHZ3m1NSimibRihWsDH2APSnus4E3e', 'user', 'active', '1', '2026-01-26 06:01:45', '2026-02-05 10:00:23', 'BCIC-ICT-DIVISION-B1-8', '', '', '', ''),
(9, '1412', 'Md. Shakilur Rahman', 'UDA', NULL, NULL, 'TSPCL', NULL, '01834059361', 'shakilurrahman.361@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:02:38', '2026-02-05 10:00:29', 'BCIC-ICT-DIVISION-B1-9', '', '', '', ''),
(10, '5671-3', 'Seikh Shoaibur Rahman', 'Assistant Chief Accountant', 'Accounts', 'COST & Budget and ICT', 'AFCCL', 'AFCCL', '01719385232', 'seikhshoaibur@gmail.com', '$2y$10$GzOHALUfJxzYLAcYXwZDLODVozyyjA5WwSCLfMUyShWj0xCHegK3G', 'user', 'active', '1', '2026-01-26 06:02:46', '2026-02-05 10:00:34', 'BCIC-ICT-DIVISION-B1-10', '', '', '', ''),
(11, '5911-3', 'Seema Khatun', 'Assistant manager (com)', 'Purchase', '', 'BCIC', '', '01943146857', 'seema725571@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:06', '2026-03-29 10:15:27', 'BCIC-ICT-DIVISION-B1-11', '', '', '', ''),
(12, '5911-3', 'Seema Khatun', 'Assistant manager (com)', 'Purchase', '', 'BCIC', '', '01943146857', 'seema725571@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:11', '2026-03-29 10:15:27', 'BCIC-ICT-DIVISION-B1-12', '', '', '', ''),
(13, '5074-0', 'Md. Ziaul Hasan', 'System Analyst', NULL, NULL, 'Jamuna Fertilizer Company Ltd.', NULL, '01717883311', 'ziajfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:36', '2026-02-05 10:00:48', 'BCIC-ICT-DIVISION-B1-13', '', '', '', ''),
(14, '4497-4', 'Mohammad Mahbubur Rahman', 'Additional Chief Engr. (Elect.)', NULL, NULL, 'GPFPLC', NULL, '01684818300', 'm.mahbub.2030@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:01', '2026-02-05 10:00:56', 'BCIC-ICT-DIVISION-B1-14', '', '', '', ''),
(15, '5244-9', 'Rupom Barua', 'Deputy Chief Chemist', 'Technical', 'R&D and QC', 'Karnaphuli Paper Mills Limited', '', '01815062404', 'rupombarua@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:47', '2026-02-05 10:01:02', 'BCIC-ICT-DIVISION-B1-15', '', '', '', ''),
(16, '6681-1', 'MD. ASHFAQUL ISLAM', 'Assistant Programmer', NULL, NULL, 'Ghorashal Polash Fertilizer PLC', NULL, '01774915052', 'fahimkuet09@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:00', '2026-02-05 10:01:07', 'BCIC-ICT-DIVISION-B1-16', '', '', '', ''),
(17, '4603-7', 'Shameem Ahmed', 'Deputy Chief Chemist', NULL, NULL, 'KPML', NULL, '01874463865', 'shameemlyon@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:10', '2026-02-05 10:01:15', 'BCIC-ICT-DIVISION-B1-17', '', '', '', ''),
(18, '3244-1', 'Shambhu Lal Das', 'GM(Operation)', NULL, NULL, 'SFCL/BCIC', NULL, '01735593606', 'shambhubcic@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:35', '2026-02-05 10:01:20', 'BCIC-ICT-DIVISION-B1-18', '', '', '', ''),
(19, '6489-9', 'sharif uddin sikder', 'Programmer', NULL, NULL, ' CUFL', NULL, '01711038836', 'sharifsikderbd24@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:10:36', '2026-02-05 10:01:24', 'BCIC-ICT-DIVISION-B1-19', '', '', '', ''),
(20, '5197-9', 'Md Saiful Islam', 'ACA', NULL, NULL, 'JFCL', NULL, '01721-100107', 'saiful1978jfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:11:33', '2026-02-05 10:01:28', 'BCIC-ICT-DIVISION-B1-20', '', '', '', ''),
(22, '5725-7', 'MD. MOIN UDDIN', 'Deputy Manager (Admin)', 'Administration', 'Recruitment and Training', 'Bangladesh Chemical Industries Corporation', '', '01705303256', 'moindu105@gmail.com', '$2y$10$B.N.8ywbA0eT/MMdp0se.ut6tOljPT9xBB7ZQwnleb4Az5tDIkSPi', 'user', 'active', '2', '2026-03-29 09:51:55', '2026-03-30 10:08:17', 'BCIC-ICT-DIVISION-B2-22', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, C) (3, D) (4, D) (5, B) (6, C) (7, B) (8, D) (9, C) (10, C)', ''),
(23, '5911-3', 'Seema Khatun', 'Assistant Manager (Com)', 'Purchase', '', 'BCIC', '', '01943146857', 'seema@gmail.com', '1234', 'user', 'active', '2', '2026-03-29 09:52:58', '2026-03-31 06:50:03', 'BCIC-ICT-DIVISION-B2-23', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'B\'', '(1, C) (2, C) (3, C) (4, C) (5, B) (6, C) (7, B) (8, B) (9, C) (10, C)', 'sssssssssssssssssssss'),
(24, '7903-8', 'MOHAMMAD SAIFUL ALAM', 'ASSISTANT MANAGER (COMMERCIAL)', 'MARKETING DIVISION', '', 'MARKETING DIVISION', 'BCIC HEAD OFFICE, DHAKA.', '01712083361', 'alam3361@gmail.com', '$2y$10$lymufBoqsg/VFeLNr1gBV.WFJaI7WOcQirn/Gw2vgHQYOeroS8bpO', 'user', 'active', '2', '2026-03-29 09:53:01', '2026-03-30 14:51:12', 'BCIC-ICT-DIVISION-B2-24', '', '', '(1, C) (2, C) (3, C) (4, B) (5, D) (6, D) (7, B) (8, B) (9, B) (10, C)', ''),
(25, '5474-2', 'MEHADI HASSAN BHUIYAN', 'Deputy Manager ( Com)', NULL, NULL, 'BCIC', NULL, '01928593081', 'mehadihassan1985@gmail.com', '$2y$10$c8.EIP/NnK9bAQSsqwIYaeUHNjhFsKfhtWPlQXzgRegX8AkUvSDyK', 'user', 'active', '2', '2026-03-29 09:53:21', '2026-03-30 10:01:50', 'BCIC-ICT-DIVISION-B2-25', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'N\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, C) (3, C) (4, C) (5, B) (6, C) (7, B) (8, B) (9, C) (10, C)', ''),
(26, '5677-0', 'MD. HARUN OR RASHID', 'Deputy Manager (Admin)', 'Personnel Division', 'Admin-1', 'BCIC', '', '01682838341', 'harunduir374@gmail.com', '$2y$10$34JEw63FteSgcV.6pV5rt.47PiazgnH25CLCz8ip0rZKNNwK3U63C', 'user', 'active', '2', '2026-03-29 09:53:46', '2026-03-30 10:01:15', 'BCIC-ICT-DIVISION-B2-26', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'A\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, C) (4, D) (5, C) (6, C) (7, C) (8, D) (9, C) (10, D)', ''),
(27, '5395-9', 'JAHID HASAN', 'Deputy Manager (Commercial)', 'Marketing', '', 'Marketing', 'Bangladesh Chemical Industries Corporation (BCIC)', '+8801756404076', 'jahid5395-9@bcic.gov.bd', '$2y$10$U/F/D7t5Io.vvPsoVRV9oOP4k/iMjpjk90TJ2myjnEKXpHi2j1AHS', 'user', 'active', '2', '2026-03-29 09:53:51', '2026-03-30 10:01:42', 'BCIC-ICT-DIVISION-B2-27', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, D) (4, D) (5, D) (6, D) (7, D) (8, D) (9, D) (10, D)', ''),
(28, '5699-4', 'MUHAMMAD IMRANUR RAHMAN CHOWDHURY', 'DEPUTY MANAGER (COMMERCIAL)', 'PURCHASE', 'G2G', 'PURCHASE DIVISION, HEAD OFFICE, BCIC', 'BCIC HEAD OFFICE', '01738109026', 'CHOWDHURYIMRAN.BCIC@GMAIL.COM', '$2y$10$R4NZX3bU.BBiTeuLggHHOOytAYNV4zGg58vtpJ0UgH3KIzIT/nxpG', 'user', 'active', '2', '2026-03-29 09:54:05', '2026-03-30 10:01:36', 'BCIC-ICT-DIVISION-B2-28', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'A\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, C) (3, C) (4, C) (5, C) (6, C) (7, C) (8, C) (9, C) (10, C)', ''),
(29, '5752-1', 'MD. IMRAN HASAN', 'Assistant Chief Accountant ', 'Finance', '', 'Finance Division, Head Office, BCIC', 'Bangladesh Chemical Industries Corporation (BCIC)', '01747573588', 'imran5752-1@bcic.gov.bd', '$2y$10$Pto3PFztB7H0uvoN74T6kOO9A9bc18j5qUGtrmjCd0PwArA1xJh9i', 'user', 'active', '2', '2026-03-29 09:54:08', '2026-03-30 10:01:18', 'BCIC-ICT-DIVISION-B2-29', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, D) (4, C) (5, D) (6, D) (7, D) (8, D) (9, D) (10, D)', ''),
(30, '5467-6', 'KHADIJA KHANAM', 'Assistant Chief Accountant', NULL, NULL, 'BCIC', NULL, '01717654874', 'khadijakhanam@gmail.com', '$2y$10$qzTuxgwYhRWMVKucOnYYjukRbtUdRlwzu65/XvRyUZeLv4kW5lBBi', 'user', 'active', '2', '2026-03-29 09:54:55', '2026-03-30 10:03:53', 'BCIC-ICT-DIVISION-B2-30', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, C) (3, C) (4, C) (5, C) (6, C) (7, C) (8, C) (9, C) (10, C)', ''),
(31, '5235-7', 'MAKSUDA BEGUM RATNA', 'Deputy Manager', NULL, NULL, 'BCIC', NULL, '01309340028', 'ratnabcic2010@gmail.com', '$2y$10$.7KfZdpNBWUMN4wIYLJB4Omn25rOdhMf1V9Qs2jqJSDbrxwi47.2e', 'user', 'active', '2', '2026-03-29 09:54:57', '2026-03-30 10:01:46', 'BCIC-ICT-DIVISION-B2-31', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, C) (3, D) (4, D) (5, D) (6, D) (7, D) (8, D) (9, D) (10, D)', ''),
(32, '6404-8', 'MOHAMMAD SAIFUL ISLAM', 'Assistant Programmer', 'MIS', 'N/A', 'BCIC', 'Head Office', '+8801722008277', 'msi.mis.bcic.6404.8@gmail.com', '$2y$10$cm/dW7EaAiQ15erMiOwxVe7BUkfDLTlT3iPJsR1vfX8ZVbj4tFovW', 'user', 'active', '2', '2026-03-29 09:54:57', '2026-03-30 10:02:08', 'BCIC-ICT-DIVISION-B2-32', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'B\'', '(1, D) (2, D) (3, D) (4, C) (5, C) (6, D) (7, C) (8, C) (9, D) (10, C)', ''),
(33, '5315-7', 'RAFIZA FARZANA', 'Assistant Admin Officer', 'MIS', '', 'BCIC ', '', '01775094246', 'bcicmis@yahoo.com', '$2y$10$E/lAAUio/CpSvV3vT7UXOOc.MM5YwtQzr9.4A3fMdlW6Qn3kDRZ2q', 'user', 'active', '2', '2026-03-29 09:54:57', '2026-03-30 10:02:37', 'BCIC-ICT-DIVISION-B2-33', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, C) (3, C) (4, C) (5, B) (6, D) (7, B) (8, C) (9, C) (10, C)', ''),
(34, '8143-0', 'ISRAT JAHAN', 'AAO', 'Company Affairs', 'Company Affairs', 'BCIC head office', 'BCIC head office', '01798080764', 'bcic.israt@gmail.com', '$2y$10$EpFNFVkae6hyPbTeJP70MOuWbs7PsidpjUN98mPHwUcTX0z15G3D6', 'user', 'active', '2', '2026-03-29 09:55:31', '2026-03-30 10:03:18', 'BCIC-ICT-DIVISION-B2-34', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'A\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, D) (3, D) (4, C) (5, D) (6, C) (7, C) (8, D) (9, D) (10, C)', ''),
(35, '8057-2', 'MASUMA AKTER', 'Assistant Commercial Officer ', NULL, NULL, 'PRD Division', NULL, '01714894161', 'masumapopi4@gmail.com', '$2y$10$UAHJkzwGVRhmhN74g.dU7OirsA9EjV2onN5jvk125zVnpQTaEVpjS', 'user', 'active', '2', '2026-03-29 09:55:38', '2026-03-30 10:02:18', 'BCIC-ICT-DIVISION-B2-35', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, D) (4, D) (5, D) (6, D) (7, D) (8, D) (9, D) (10, D)', ''),
(36, '5351-2', 'MD. ABDUS SATTAR', 'Deputy Manager (Admin)', 'Personnel Division', 'Disciplinary Section', 'BCIC', '', '01719133524', 'sttr05@gmail.com', '$2y$10$NewfwewNIElFzGLCkts8GuaKndN.lZ5BGGjQvU8QGT93Ktswfeqze', 'user', 'active', '2', '2026-03-29 09:55:53', '2026-03-30 10:01:32', 'BCIC-ICT-DIVISION-B2-36', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, D) (4, C) (5, D) (6, C) (7, C) (8, D) (9, D) (10, D)', ''),
(37, '5849-5', 'MD. SOHEL RANA', 'Deputy Manager (Administration)', 'Personnel Division', 'O&M Department (Admin-2)', 'BCIC Head Office', '', '01725855262', 'sohelbcic20@gmail.com', '$2y$10$ekQUnXDVUfL1xTbLNPMlfOag1OfvE7AJQ15ZlbFOyrMVJd87E1Saq', 'user', 'active', '2', '2026-03-29 09:57:13', '2026-03-30 10:01:15', 'BCIC-ICT-DIVISION-B2-37', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, D) (4, D) (5, D) (6, D) (7, D) (8, D) (9, D) (10, D)', ''),
(38, '8137-2', 'SYED MUTAKABBIR', 'office assistant', 'LSA', '', 'bcic', '', '01722824364', 'mutakabbir19871231@gmail.com', '$2y$10$8NLAfBrneSFHWTPQZkmj0u0d8H5zpn8/iKNkXhs3VjhxNktX3wAuS', 'user', 'active', '2', '2026-03-29 10:03:21', '2026-03-30 10:02:55', 'BCIC-ICT-DIVISION-B2-38', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'A\',\'C\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, D) (3, D) (4, C) (5, C) (6, C) (7, D) (8, C) (9, C) (10, C)', ''),
(39, '6401-4', 'MOMENA BEGUM', 'Administrative Officer', 'Personnel', 'LSA', 'BCIC', 'BCIC', '01760358444', 'momena.bcic@gmail.com', '$2y$10$k/lQi.YEa9c.eEKm0IQRHOQf0ADrnmYtlTvsoKYbErBCGReU0wL5i', 'user', 'active', '2', '2026-03-29 10:05:02', '2026-03-30 10:01:46', 'BCIC-ICT-DIVISION-B2-39', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, C) (2, C) (3, D) (4, C) (5, C) (6, C) (7, D) (8, C) (9, D) (10, C)', ''),
(40, '6389-1', 'MD SHAH NEWAZ ', 'Assistant Commercial Officer', 'BCIC Planning', '', 'BCIC Head Office ', 'Head Office', '01737824008', 'newaz.bcic@gmail.com', '$2y$10$H4XLfnCI.09Xe0EOHKOu6e.LL14n/yPlIH.xaftejZVkWFIOvKhl6', 'user', 'active', '2', '2026-03-30 07:36:14', '2026-03-30 10:01:45', 'BCIC-ICT-DIVISION-B2-40', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, D) (4, D) (5, D) (6, D) (7, D) (8, D) (9, D) (10, D)', ''),
(41, '6543-3', 'MUHAMMAD BODI-UZ-ZAMAN SHAWON MIA', 'Executive Engineer (Chemical)', 'BCIC Planning', '', 'BCIC Head Office', 'BCIC', '01714396596', 'zamancep@gmail.com', '$2y$10$WKgdWcuIQuQNL2Jnacdu1eW6dqjpEo3/Coa9nBqv9Fpuikm9lwDC.', 'user', 'active', '2', '2026-03-30 09:53:37', '2026-03-30 10:01:36', 'BCIC-ICT-DIVISION-B2-41', '\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'10\'', '\'C\',\'C\',\'B\',\'B\',\'C\',\'C\',\'B\',\'B\',\'A\',\'C\'', '(1, D) (2, D) (3, D) (4, D) (5, D) (6, D) (7, D) (8, D) (9, D) (10, D)', ''),
(42, '2002-1', 'ALI', 'Assistant Programmer', NULL, NULL, 'CSD, BCIC', NULL, '01718834657', 'ali@gmail.com', '$2y$10$ifL9dGlN3xZGJy0JVDtz5.63n9KtvdJHj2pAoeouGgi1elr2vSrEW', 'user', 'active', '3', '2026-03-31 10:02:31', '2026-03-31 10:02:31', 'BCIC-ICT-DIVISION-B3-42', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl_old`
--

CREATE TABLE `users_tbl_old` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `place_of_posting` varchar(100) DEFAULT NULL,
  `office` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `batch` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `serial_no` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl_old`
--

INSERT INTO `users_tbl_old` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `place_of_posting`, `office`, `mobile_no`, `email_id`, `password`, `role`, `status`, `batch`, `created_at`, `updated_at`, `serial_no`) VALUES
(1, '5620-0', 'sadmin', NULL, NULL, NULL, 'BCIC', 'BCIC', '01718834655', 'test@yahoo.com', '1234', 'sadmin', 'active', '', '2026-01-22 08:28:08', '2026-01-22 08:28:08', ''),
(4, '1111122', 'test', 'Sub Assistant Engr.', 'Administration', 'Tecnical', 'BCIC H.O.', '', '01718834655', 'cop@bcic.gov.bd', '1234', 'user', 'active', '2', '2026-01-25 09:42:22', '2026-02-05 05:51:50', 'BCIC-ICT-DIVISION-B2-4'),
(7, '5692-9', 'Mohammad Azim Uddin', 'Deputy Manager (Administration)', 'Administration', 'LSA', 'DAPFCL', 'DAPFCL', '01515680819', 'azimlawcu@gmail.com', '$2y$10$HAPfVPlN/psx.adZslVmD.SubZZKdfUTS3tcNwberU1tEQcPF9RCC', 'user', 'active', '1', '2026-01-26 05:58:01', '2026-01-26 06:05:11', 'BCIC-ICT-DIVISION-B1-5'),
(8, '4956-9', 'Md Mamudul Hasan Chowdhury', 'Deputy Chief Engineer', NULL, NULL, 'SFCL', NULL, '01716006200', 'hasan49569@sfcl.gov.bd', '1234', 'user', 'active', '1', '2026-01-26 05:58:28', '2026-01-26 05:58:28', 'BCIC-ICT-DIVISION-B1-8'),
(9, '5619-2', 'Mohammad Saiful Islam', 'Programmer', NULL, NULL, 'TSP Complex Limited', NULL, '01885406868', 'saiful.on@gmail.com', '$2y$10$lswDmd6wsQFFcrVmM3G.E.LhcRwpjMXbnkjotfL17NL4LjkWSheZa', 'user', 'active', '1', '2026-01-26 05:59:15', '2026-01-26 06:01:30', 'BCIC-ICT-DIVISION-B1-9'),
(10, '5053-4', 'Naba Krishna Sardar', 'Additional Chief Chemist', NULL, NULL, 'DAPFCL', NULL, '01717658284', 'nabokrishna@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:20', '2026-01-26 06:00:20', 'BCIC-ICT-DIVISION-B1-10'),
(11, '', 'Kowshik Chowdhury', 'Assistant Programmer', 'CIT', '', 'TICI', '', '01994928756', 'kowshikichowdury.bcic@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:00:28', '2026-01-26 06:01:27', 'BCIC-ICT-DIVISION-B1-11'),
(13, '6769-4', 'Kazi Mahtab-ul-Islam', 'Sub Assistant Chemist', NULL, NULL, 'Chhatak Cement Company Limited', NULL, '01676339522', 'mahtabshawon@gmail.com', '$2y$10$2RZ6Bujh6dtUQocHJtbMd.aTVTSPGzriXuStGp/xlvGtzfBZSMdHq', 'user', 'active', '1', '2026-01-26 06:01:20', '2026-01-26 06:02:15', 'BCIC-ICT-DIVISION-B1-13'),
(14, '5726-5', 'Samiran Marma', 'Deputy Manager (Admin)', 'Administration', 'MD Office', 'CUFL', 'Chittagong Urea Fertilizer Limited', '01784607581', 'marmasamiran@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:01:43', '2026-01-26 06:05:17', 'BCIC-ICT-DIVISION-B1-14'),
(15, '5753-9', 'RAJIB KUMAR PAUL', 'Deputy Manager(Administration)', 'Administration', '', 'Chhatak Cement Company Limited', '', '01842710472', 'paulrajib15du@gmail.com', '$2y$10$ZMMDGKmiZu6NCd7eDErK0OzcHZ3m1NSimibRihWsDH2APSnus4E3e', 'user', 'active', '1', '2026-01-26 06:01:45', '2026-01-27 04:05:22', 'BCIC-ICT-DIVISION-B1-15'),
(16, '1412', 'Md. Shakilur Rahman', 'UDA', NULL, NULL, 'TSPCL', NULL, '01834059361', 'shakilurrahman.361@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:02:38', '2026-01-26 06:02:38', 'BCIC-ICT-DIVISION-B1-16'),
(17, '5671-3', 'Seikh Shoaibur Rahman', 'Assistant Chief Accountant', 'Accounts', 'COST & Budget and ICT', 'AFCCL', 'AFCCL', '01719385232', 'seikhshoaibur@gmail.com', '$2y$10$GzOHALUfJxzYLAcYXwZDLODVozyyjA5WwSCLfMUyShWj0xCHegK3G', 'user', 'active', '1', '2026-01-26 06:02:46', '2026-01-26 06:05:36', 'BCIC-ICT-DIVISION-B1-17'),
(18, '', 'MD. Anamul Hoque Monir', 'Assistant Commercial Officer', NULL, NULL, 'BISFL', NULL, '01737487236', 'mamonir74@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:06', '2026-01-26 06:04:06', 'BCIC-ICT-DIVISION-B1-18'),
(19, '', 'Efdtekhar Uddin Mohammad Aman', 'Additional Chief Chemist', NULL, NULL, 'Training Institute for Chemical Industries', NULL, '01611063963', 'eumaman@live.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:11', '2026-01-26 06:04:11', 'BCIC-ICT-DIVISION-B1-19'),
(20, '5074-0', 'Md. Ziaul Hasan', 'System Analyst', NULL, NULL, 'Jamuna Fertilizer Company Ltd.', NULL, '01717883311', 'ziajfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:04:36', '2026-01-26 06:04:36', 'BCIC-ICT-DIVISION-B1-20'),
(21, '4497-4', 'Mohammad Mahbubur Rahman', 'Additional Chief Engr. (Elect.)', NULL, NULL, 'GPFPLC', NULL, '01684818300', 'm.mahbub.2030@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:01', '2026-01-26 06:07:01', 'BCIC-ICT-DIVISION-B1-21'),
(22, '5244-9', 'Rupom Barua', 'Deputy Chief Chemist', 'Technical', 'R&D and QC', 'Karnaphuli Paper Mills Limited', '', '01815062404', 'rupombarua@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:07:47', '2026-01-26 06:10:48', 'BCIC-ICT-DIVISION-B1-22'),
(23, '6681-1', 'MD. ASHFAQUL ISLAM', 'Assistant Programmer', NULL, NULL, 'Ghorashal Polash Fertilizer PLC', NULL, '01774915052', 'fahimkuet09@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:00', '2026-01-26 06:08:00', 'BCIC-ICT-DIVISION-B1-23'),
(24, '4603-7', 'Shameem Ahmed', 'Deputy Chief Chemist', NULL, NULL, 'KPML', NULL, '01874463865', 'shameemlyon@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:10', '2026-01-26 06:08:10', 'BCIC-ICT-DIVISION-B1-24'),
(25, '3244-1', 'Shambhu Lal Das', 'GM(Operation)', NULL, NULL, 'SFCL/BCIC', NULL, '01735593606', 'shambhubcic@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:08:35', '2026-01-26 06:08:35', 'BCIC-ICT-DIVISION-B1-25'),
(26, '6489-9', 'sharif uddin sikder', 'Programmer', NULL, NULL, ' CUFL', NULL, '01711038836', 'sharifsikderbd24@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:10:36', '2026-01-26 06:10:36', 'BCIC-ICT-DIVISION-B1-26'),
(27, '5197-9', 'Md Saiful Islam', 'ACA', NULL, NULL, 'JFCL', NULL, '01721-100107', 'saiful1978jfcl@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:11:33', '2026-01-26 06:11:33', 'BCIC-ICT-DIVISION-B1-27'),
(28, '', 'Sadman Sakib', 'Sub-Assistant Engineer (Mech.)', NULL, NULL, 'BISFL', NULL, '01830624838', 'saakiib@gmail.com', '1234', 'user', 'active', '1', '2026-01-26 06:16:35', '2026-01-26 06:16:35', 'BCIC-ICT-DIVISION-B1-28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authority_tbl`
--
ALTER TABLE `authority_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_set`
--
ALTER TABLE `evaluation_set`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_set`
--
ALTER TABLE `question_set`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_tbl_old`
--
ALTER TABLE `users_tbl_old`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authority_tbl`
--
ALTER TABLE `authority_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `evaluation_set`
--
ALTER TABLE `evaluation_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `question_set`
--
ALTER TABLE `question_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users_tbl_old`
--
ALTER TABLE `users_tbl_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
