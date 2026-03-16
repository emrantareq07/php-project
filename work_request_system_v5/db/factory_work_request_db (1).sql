-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2026 at 09:12 AM
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
-- Database: `factory_work_request_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_action_logs`
--

CREATE TABLE `admin_action_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `admin_name` varchar(100) DEFAULT NULL,
  `admin_emp_id` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `action_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `designation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `designation`) VALUES
(1, 'Assistant Engineer'),
(2, 'Executive Engineer'),
(3, 'Deputy Chief Engineer'),
(4, 'Additional Chief Engineer'),
(5, 'General Manager'),
(6, 'Department Head'),
(7, 'Divisional Head'),
(8, 'Assistant Chemist'),
(9, 'Chemist'),
(10, 'Sr. System Analyst'),
(11, 'Deputy Manager'),
(12, 'Manager'),
(13, 'Deputy General Manager'),
(14, 'Additional Chief Accountant'),
(15, 'Assistant Programmer'),
(16, 'Programmer'),
(17, 'Chairman (Grade-1)'),
(18, 'Director'),
(19, 'Additional Chief Chemist'),
(20, 'Additional Chief Manager'),
(21, 'Accounts Officer'),
(22, 'Chief Engineer'),
(23, 'Assistant Accounts Officer'),
(24, 'Assistant Admin Officer'),
(25, 'Assistant Com. Officer'),
(26, 'Assistant Manager'),
(28, 'Assistant Technical Officer'),
(29, 'Assistant Operation Officer'),
(30, 'Operation Officer'),
(31, 'Technical Officer'),
(32, 'System Analyst'),
(33, 'Managing Director'),
(34, 'Executive Director'),
(35, 'Chief of Personnel'),
(36, 'Controller of Accounts'),
(37, 'Senior General Manager'),
(38, 'Deputy Chief Accountant'),
(39, 'Medical officer'),
(40, 'Senior Medical Officer'),
(41, 'Chief Medical Officer'),
(42, 'Chief Finance Officer'),
(43, 'Chief Auditor'),
(44, 'Project Director'),
(45, 'Addl. Chief Medical Officer'),
(46, 'D.C.O.P'),
(47, 'Principle'),
(48, 'Deputy Chief Medical Officer'),
(49, 'Deputy Chief Auditors'),
(50, 'D.C.F.O'),
(51, 'A.C.O.P'),
(52, 'Assistant Professor'),
(53, 'Senior Librarian'),
(54, 'Head Master'),
(55, 'Senior Technical Officer'),
(56, 'Assistant Chief Accountant'),
(57, 'Additional Chief Finance Officer'),
(58, 'Assistant Chief Auditor'),
(59, 'Computer Operator'),
(60, 'Data Entry Operator'),
(61, 'Senior Officer ICT'),
(62, 'Record Sorter'),
(63, 'Sub Assistant Engineer'),
(64, 'Managing Director (Addl.C.)'),
(65, 'Managing Director (C.C.)'),
(66, 'Officer'),
(67, 'Production Officer'),
(68, 'Assistant Principle Officer'),
(69, 'Executive'),
(70, 'Sub Assistant Chemist'),
(73, 'Senior Officer'),
(74, 'Trainee Engineer'),
(75, 'Generator Operator'),
(76, 'Dev.Exect.Trans'),
(77, 'Assistant Instructor'),
(78, 'Junior Instructor'),
(79, 'Instructor'),
(80, 'Junior Officer'),
(81, 'Production Shift Incharge'),
(82, 'Production Senior Officer'),
(83, 'Production Engineer'),
(84, 'Data Protection Officer'),
(85, 'Junior Engineer'),
(86, 'Project Technology'),
(87, 'Junior Assistant Engineer'),
(88, 'Senior Electrician'),
(89, 'Security Officer'),
(90, 'Trainee Officer'),
(91, 'Machinery Fitter'),
(92, 'Assistant Security Officer'),
(93, 'Forest Officer'),
(94, 'Teacher'),
(95, 'Engineer'),
(96, 'LDA Cum-Typist'),
(97, 'Senior Clark'),
(98, 'Cashier'),
(99, 'Assistant Headmaster'),
(100, 'Assistant Store Keper'),
(101, 'Store Keper'),
(102, 'Assistant Teacher'),
(103, 'Librerian'),
(104, 'Assistant Teacher'),
(105, 'Senior Lecturer'),
(106, 'Skilled Operator (S.O.)-2'),
(107, 'Skilled Operator (S.O.)-1'),
(108, 'High Skilled Operator (HSO)'),
(109, 'Master Operator (MO)'),
(110, 'Sub-Assistant Technical Officer'),
(111, 'Assistant Medical Officer'),
(112, 'Assistant Transport Officer'),
(113, 'Assistant Coordination Officer'),
(114, 'Assistant Personnel Officer'),
(115, 'Personal Officer'),
(116, 'Deputy Chief Chemist'),
(117, 'Lecturer'),
(118, 'Administrative Officer'),
(119, 'Assistant Marketing Officer'),
(120, 'Master Technician'),
(121, 'Process Operator'),
(123, 'High Skilled Technician (HST)'),
(124, 'Store Officer'),
(125, 'Junior Clark'),
(126, 'Modeller'),
(127, 'Assistant Modeller'),
(128, 'Audit Officer'),
(129, 'Stenographer'),
(130, 'Telephone Operator'),
(131, 'Semi Skilled Operator (SSO)'),
(132, 'Semi Skilled Technician (SST)'),
(133, 'Skilled Technician (ST)'),
(134, 'Assistant Cashier'),
(135, 'Accounts Assistant'),
(136, 'Supervisor'),
(137, 'Additional Chief Insurance Officer'),
(138, 'Additional Chief Auditor'),
(139, 'Junior Programmer'),
(140, 'Security Guard'),
(141, 'MLSS'),
(142, 'Office Assistant'),
(143, 'STG.CUM. COMPUTER OPERATOR'),
(144, 'Driver'),
(145, 'Demonstrator'),
(146, 'IMAM'),
(148, 'Electricina'),
(149, 'Purchase Assistant'),
(150, 'Mechanic'),
(151, 'Marketing Assistant'),
(152, 'ST. CUM COMPUTER OPERATOR');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `division` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(16, 'Director (Commercial)'),
(17, 'Director (Finance)'),
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
(73, 'MTS (Mechanical)'),
(74, 'CSD'),
(75, 'BCIC College '),
(76, 'Legal Affairs Department'),
(77, 'Board & Co-ordination Department'),
(78, 'Company Affairs'),
(79, 'PDD'),
(80, 'ISHD'),
(81, 'Construction Division'),
(82, 'Forest Division'),
(83, 'Transport Division'),
(84, 'Branch Office (Chittagong)'),
(85, 'MTS (Electrical)'),
(86, 'test'),
(87, 'test f');

-- --------------------------------------------------------

--
-- Table structure for table `fc_tbl`
--

CREATE TABLE `fc_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `current_date` date DEFAULT NULL,
  `month` varchar(50) NOT NULL,
  `date` text DEFAULT NULL,
  `time_from` text DEFAULT NULL,
  `time_to` text DEFAULT NULL,
  `total_hours` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fc_tbl`
--

INSERT INTO `fc_tbl` (`id`, `emp_id`, `name`, `designation`, `division`, `section`, `current_date`, `month`, `date`, `time_from`, `time_to`, `total_hours`, `remarks`, `created_at`, `updated_at`) VALUES
(1, '4665-5', 'fared', 'Sub Assistant Engr.', 'Administration Division', 'pm', '2026-03-15', '01', '01-01-2026,02-01-2026,03-01-2026,04-01-2026,05-01-2026,06-01-2026,07-01-2026,08-01-2026,09-01-2026,10-01-2026,11-01-2026,12-01-2026,13-01-2026,14-01-2026,15-01-2026,16-01-2026,17-01-2026,18-01-2026,19-01-2026,20-01-2026,21-01-2026,22-01-2026,23-01-2026,24-01-2026,25-01-2026,26-01-2026,27-01-2026,28-01-2026,29-01-2026,30-01-2026,31-01-2026', '02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM', '05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM', '3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3', ',,,,,,,,,,,,,,,,,,,,,,,,,,,,,,', '2026-03-15 08:41:09', '2026-03-15 08:41:09'),
(2, '4665-5', 'fared', 'Sub Assistant Engr.', 'Administration Division', 'pm', '2026-03-15', '02', '01-02-2026,02-02-2026,03-02-2026,04-02-2026,05-02-2026,06-02-2026,07-02-2026,08-02-2026,09-02-2026,10-02-2026,11-02-2026,26-02-2026,27-02-2026,28-02-2026', '02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM,02:00 PM', '05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM,05:00 PM', '3,3,3,3,3,3,3,3,3,3,3,3,3,3', ',,,,,,,,,,,,,', '2026-03-15 08:42:56', '2026-03-15 08:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `log_table`
--

CREATE TABLE `log_table` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `event_type` enum('login','logout') NOT NULL DEFAULT 'login',
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success',
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_table`
--

INSERT INTO `log_table` (`id`, `username`, `event_type`, `ip_address`, `user_agent`, `status`, `login_time`, `logout_time`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 10:01:15', NULL, NULL, '2025-10-14 08:01:15', '2025-10-14 08:01:15'),
(2, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:02:54', NULL, NULL, '2025-10-14 08:02:54', '2025-10-14 08:02:54'),
(3, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:05:23', NULL, NULL, '2025-10-14 08:05:23', '2025-10-14 08:05:23'),
(4, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:05:47', NULL, NULL, '2025-10-14 08:05:47', '2025-10-14 08:05:47'),
(5, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:05:55', NULL, NULL, '2025-10-14 08:05:55', '2025-10-14 08:05:55'),
(6, 'user', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 14:07:11', NULL, NULL, '2025-10-14 08:07:11', '2025-10-14 08:07:11'),
(7, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 10:08:14', NULL, NULL, '2025-10-14 08:08:14', '2025-10-14 08:08:14'),
(8, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 10:08:21', NULL, NULL, '2025-10-14 08:08:21', '2025-10-14 08:08:21'),
(9, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 14:08:28', NULL, NULL, '2025-10-14 08:08:28', '2025-10-14 08:08:28'),
(10, 'dsfs', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-14 10:08:32', NULL, NULL, '2025-10-14 08:08:32', '2025-10-14 08:08:32'),
(11, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-14 14:12:42', NULL, NULL, '2025-10-14 08:12:42', '2025-10-14 08:12:42'),
(12, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-15 12:42:06', NULL, NULL, '2025-10-15 06:42:06', '2025-10-15 06:42:06'),
(13, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-15 12:57:26', NULL, NULL, '2025-10-15 06:57:26', '2025-10-15 06:57:26'),
(14, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-15 12:57:34', NULL, NULL, '2025-10-15 06:57:34', '2025-10-15 06:57:34'),
(15, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-16 09:35:11', NULL, NULL, '2025-10-16 03:35:11', '2025-10-16 03:35:11'),
(16, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-19 09:58:21', NULL, NULL, '2025-10-19 03:58:21', '2025-10-19 03:58:21'),
(17, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-19 09:58:28', NULL, NULL, '2025-10-19 03:58:28', '2025-10-19 03:58:28'),
(18, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-20 09:25:04', NULL, NULL, '2025-10-20 03:25:04', '2025-10-20 03:25:04'),
(19, 'user', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:18:46', NULL, NULL, '2025-10-21 03:18:46', '2025-10-21 03:18:46'),
(20, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:18:53', NULL, NULL, '2025-10-21 03:18:53', '2025-10-21 03:18:53'),
(21, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:19:01', NULL, NULL, '2025-10-21 03:19:01', '2025-10-21 03:19:01'),
(22, 'emran', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-21 09:19:05', NULL, NULL, '2025-10-21 03:19:05', '2025-10-21 03:19:05'),
(23, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-21 09:20:08', NULL, NULL, '2025-10-21 03:20:08', '2025-10-21 03:20:08'),
(24, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-22 10:07:27', NULL, NULL, '2025-10-22 04:07:27', '2025-10-22 04:07:27'),
(25, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-22 10:07:34', NULL, NULL, '2025-10-22 04:07:35', '2025-10-22 04:07:35'),
(26, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-23 09:15:15', NULL, NULL, '2025-10-23 03:15:15', '2025-10-23 03:15:15'),
(27, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:42:32', NULL, NULL, '2025-10-26 03:42:32', '2025-10-26 03:42:32'),
(28, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:43:00', NULL, NULL, '2025-10-26 03:43:00', '2025-10-26 03:43:00'),
(29, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:43:05', NULL, NULL, '2025-10-26 03:43:05', '2025-10-26 03:43:05'),
(30, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:45:26', NULL, NULL, '2025-10-26 03:45:26', '2025-10-26 03:45:26'),
(31, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 09:45:32', NULL, NULL, '2025-10-26 03:45:32', '2025-10-26 03:45:32'),
(32, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:10:13', NULL, NULL, '2025-10-26 04:10:13', '2025-10-26 04:10:13'),
(33, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:10:18', NULL, NULL, '2025-10-26 04:10:19', '2025-10-26 04:10:19'),
(34, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:08', NULL, NULL, '2025-10-26 04:12:08', '2025-10-26 04:12:08'),
(35, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:11', NULL, NULL, '2025-10-26 04:12:11', '2025-10-26 04:12:11'),
(36, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:24', NULL, NULL, '2025-10-26 04:12:24', '2025-10-26 04:12:24'),
(37, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:12:28', NULL, NULL, '2025-10-26 04:12:28', '2025-10-26 04:12:28'),
(38, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:14:15', NULL, NULL, '2025-10-26 04:14:15', '2025-10-26 04:14:15'),
(39, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:14:19', NULL, NULL, '2025-10-26 04:14:19', '2025-10-26 04:14:19'),
(40, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:19', NULL, NULL, '2025-10-26 04:15:19', '2025-10-26 04:15:19'),
(41, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:24', NULL, NULL, '2025-10-26 04:15:24', '2025-10-26 04:15:24'),
(42, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:50', NULL, NULL, '2025-10-26 04:15:50', '2025-10-26 04:15:50'),
(43, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:15:53', NULL, NULL, '2025-10-26 04:15:53', '2025-10-26 04:15:53'),
(44, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:17:04', NULL, NULL, '2025-10-26 04:17:04', '2025-10-26 04:17:04'),
(45, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:17:09', NULL, NULL, '2025-10-26 04:17:09', '2025-10-26 04:17:09'),
(46, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:34:56', NULL, NULL, '2025-10-26 04:34:56', '2025-10-26 04:34:56'),
(47, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:35:01', NULL, NULL, '2025-10-26 04:35:01', '2025-10-26 04:35:01'),
(48, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:35:43', NULL, NULL, '2025-10-26 04:35:43', '2025-10-26 04:35:43'),
(49, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:35:49', NULL, NULL, '2025-10-26 04:35:49', '2025-10-26 04:35:49'),
(50, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:43:09', NULL, NULL, '2025-10-26 04:43:09', '2025-10-26 04:43:09'),
(51, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:43:14', NULL, NULL, '2025-10-26 04:43:14', '2025-10-26 04:43:14'),
(52, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:47:36', NULL, NULL, '2025-10-26 04:47:36', '2025-10-26 04:47:36'),
(53, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:47:47', NULL, NULL, '2025-10-26 04:47:47', '2025-10-26 04:47:47'),
(54, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:49:21', NULL, NULL, '2025-10-26 04:49:21', '2025-10-26 04:49:21'),
(55, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 10:49:25', NULL, NULL, '2025-10-26 04:49:25', '2025-10-26 04:49:25'),
(56, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 13:43:43', NULL, NULL, '2025-10-26 07:43:43', '2025-10-26 07:43:43'),
(57, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 13:43:47', NULL, NULL, '2025-10-26 07:43:47', '2025-10-26 07:43:47'),
(58, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 14:51:15', NULL, NULL, '2025-10-26 08:51:15', '2025-10-26 08:51:15'),
(59, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 14:51:19', NULL, NULL, '2025-10-26 08:51:19', '2025-10-26 08:51:19'),
(60, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:02:43', NULL, NULL, '2025-10-26 10:02:43', '2025-10-26 10:02:43'),
(61, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:02:49', NULL, NULL, '2025-10-26 10:02:49', '2025-10-26 10:02:49'),
(62, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:04:43', NULL, NULL, '2025-10-26 10:04:43', '2025-10-26 10:04:43'),
(63, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-26 16:04:47', NULL, NULL, '2025-10-26 10:04:47', '2025-10-26 10:04:47'),
(64, 'user', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-27 14:18:37', NULL, NULL, '2025-10-27 08:18:37', '2025-10-27 08:18:37'),
(65, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-27 14:19:09', NULL, NULL, '2025-10-27 08:19:09', '2025-10-27 08:19:09'),
(66, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:42:39', NULL, NULL, '2025-10-29 03:42:39', '2025-10-29 03:42:39'),
(67, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:52:35', NULL, NULL, '2025-10-29 03:52:35', '2025-10-29 03:52:35'),
(68, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:52:39', NULL, NULL, '2025-10-29 03:52:39', '2025-10-29 03:52:39'),
(69, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:55:10', NULL, NULL, '2025-10-29 03:55:10', '2025-10-29 03:55:10'),
(70, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:55:18', NULL, NULL, '2025-10-29 03:55:18', '2025-10-29 03:55:18'),
(71, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:56:19', NULL, NULL, '2025-10-29 03:56:19', '2025-10-29 03:56:19'),
(72, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:56:23', NULL, NULL, '2025-10-29 03:56:23', '2025-10-29 03:56:23'),
(73, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:11', NULL, NULL, '2025-10-29 03:57:11', '2025-10-29 03:57:11'),
(74, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:17', NULL, NULL, '2025-10-29 03:57:17', '2025-10-29 03:57:17'),
(75, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:20', NULL, NULL, '2025-10-29 03:57:20', '2025-10-29 03:57:20'),
(76, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:57:24', NULL, NULL, '2025-10-29 03:57:24', '2025-10-29 03:57:24'),
(77, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:21', NULL, NULL, '2025-10-29 03:59:21', '2025-10-29 03:59:21'),
(78, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:26', NULL, NULL, '2025-10-29 03:59:26', '2025-10-29 03:59:26'),
(79, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:39', NULL, NULL, '2025-10-29 03:59:39', '2025-10-29 03:59:39'),
(80, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 09:59:47', NULL, NULL, '2025-10-29 03:59:47', '2025-10-29 03:59:47'),
(81, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:00:20', NULL, NULL, '2025-10-29 04:00:20', '2025-10-29 04:00:20'),
(82, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:00:29', NULL, NULL, '2025-10-29 04:00:29', '2025-10-29 04:00:29'),
(83, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:19:17', NULL, NULL, '2025-10-29 04:19:17', '2025-10-29 04:19:17'),
(84, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:19:21', NULL, NULL, '2025-10-29 04:19:21', '2025-10-29 04:19:21'),
(85, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:23', NULL, NULL, '2025-10-29 04:26:23', '2025-10-29 04:26:23'),
(86, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:27', NULL, NULL, '2025-10-29 04:26:27', '2025-10-29 04:26:27'),
(87, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:37', NULL, NULL, '2025-10-29 04:26:37', '2025-10-29 04:26:37'),
(88, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:26:41', NULL, NULL, '2025-10-29 04:26:41', '2025-10-29 04:26:41'),
(89, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:57:16', NULL, NULL, '2025-10-29 04:57:16', '2025-10-29 04:57:16'),
(90, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:57:20', NULL, NULL, '2025-10-29 04:57:20', '2025-10-29 04:57:20'),
(91, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:58:17', NULL, NULL, '2025-10-29 04:58:17', '2025-10-29 04:58:17'),
(92, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 10:58:20', NULL, NULL, '2025-10-29 04:58:20', '2025-10-29 04:58:20'),
(93, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:04:36', NULL, NULL, '2025-10-29 05:04:36', '2025-10-29 05:04:36'),
(94, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:04:40', NULL, NULL, '2025-10-29 05:04:40', '2025-10-29 05:04:40'),
(95, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:07:21', NULL, NULL, '2025-10-29 05:07:21', '2025-10-29 05:07:21'),
(96, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:07:25', NULL, NULL, '2025-10-29 05:07:25', '2025-10-29 05:07:25'),
(97, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:10:09', NULL, NULL, '2025-10-29 05:10:09', '2025-10-29 05:10:09'),
(98, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:10:12', NULL, NULL, '2025-10-29 05:10:12', '2025-10-29 05:10:12'),
(99, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:16:27', NULL, NULL, '2025-10-29 05:16:27', '2025-10-29 05:16:27'),
(100, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:16:32', NULL, NULL, '2025-10-29 05:16:32', '2025-10-29 05:16:32'),
(101, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:17:46', NULL, NULL, '2025-10-29 05:17:46', '2025-10-29 05:17:46'),
(102, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:17:50', NULL, NULL, '2025-10-29 05:17:50', '2025-10-29 05:17:50'),
(103, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:17:56', NULL, NULL, '2025-10-29 05:17:56', '2025-10-29 05:17:56'),
(104, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 11:18:06', NULL, NULL, '2025-10-29 05:18:06', '2025-10-29 05:18:06'),
(105, '', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:27:37', NULL, NULL, '2025-10-29 06:27:37', '2025-10-29 06:27:37'),
(106, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:27:44', NULL, NULL, '2025-10-29 06:27:44', '2025-10-29 06:27:44'),
(107, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:29:25', NULL, NULL, '2025-10-29 06:29:25', '2025-10-29 06:29:25'),
(108, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:29:36', NULL, NULL, '2025-10-29 06:29:36', '2025-10-29 06:29:36'),
(109, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:29:43', NULL, NULL, '2025-10-29 06:29:43', '2025-10-29 06:29:43'),
(110, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:30:37', NULL, NULL, '2025-10-29 06:30:37', '2025-10-29 06:30:37'),
(111, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:31:37', NULL, NULL, '2025-10-29 06:31:37', '2025-10-29 06:31:37'),
(112, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:31:44', NULL, NULL, '2025-10-29 06:31:44', '2025-10-29 06:31:44'),
(113, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:38:24', NULL, NULL, '2025-10-29 06:38:24', '2025-10-29 06:38:24'),
(114, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:38:29', NULL, NULL, '2025-10-29 06:38:29', '2025-10-29 06:38:29'),
(115, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:39:28', NULL, NULL, '2025-10-29 06:39:28', '2025-10-29 06:39:28'),
(116, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'failed', '2025-10-29 12:39:42', NULL, NULL, '2025-10-29 06:39:42', '2025-10-29 06:39:42'),
(117, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:39:48', NULL, NULL, '2025-10-29 06:39:49', '2025-10-29 06:39:49'),
(118, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:40:40', NULL, NULL, '2025-10-29 06:40:40', '2025-10-29 06:40:40'),
(119, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-10-29 12:40:44', NULL, NULL, '2025-10-29 06:40:44', '2025-10-29 06:40:44'),
(120, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:20:50', NULL, NULL, '2025-11-02 10:20:50', '2025-11-02 10:20:50'),
(121, '', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:40', NULL, NULL, '2025-11-02 10:22:40', '2025-11-02 10:22:40'),
(122, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:46', NULL, NULL, '2025-11-02 10:22:46', '2025-11-02 10:22:46'),
(123, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:49', NULL, NULL, '2025-11-02 10:22:49', '2025-11-02 10:22:49'),
(124, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:22:53', NULL, NULL, '2025-11-02 10:22:53', '2025-11-02 10:22:53'),
(125, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:31:53', NULL, NULL, '2025-11-02 10:31:53', '2025-11-02 10:31:53'),
(126, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:31:57', NULL, NULL, '2025-11-02 10:31:57', '2025-11-02 10:31:57'),
(127, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:32:47', NULL, NULL, '2025-11-02 10:32:47', '2025-11-02 10:32:47'),
(128, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-02 16:32:51', NULL, NULL, '2025-11-02 10:32:51', '2025-11-02 10:32:51'),
(129, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:42:33', NULL, NULL, '2025-11-03 09:42:33', '2025-11-03 09:42:33'),
(130, '', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:44:01', NULL, NULL, '2025-11-03 09:44:01', '2025-11-03 09:44:01'),
(131, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:44:07', NULL, NULL, '2025-11-03 09:44:07', '2025-11-03 09:44:07'),
(132, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:52:05', NULL, NULL, '2025-11-03 09:52:05', '2025-11-03 09:52:05'),
(133, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:52:09', NULL, NULL, '2025-11-03 09:52:09', '2025-11-03 09:52:09'),
(134, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:54:37', NULL, NULL, '2025-11-03 09:54:37', '2025-11-03 09:54:37'),
(135, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:54:41', NULL, NULL, '2025-11-03 09:54:41', '2025-11-03 09:54:41'),
(136, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:59:23', NULL, NULL, '2025-11-03 09:59:23', '2025-11-03 09:59:23'),
(137, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-03 15:59:28', NULL, NULL, '2025-11-03 09:59:28', '2025-11-03 09:59:28'),
(138, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 13:25:39', NULL, NULL, '2025-11-04 07:25:39', '2025-11-04 07:25:39'),
(139, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:05:18', NULL, NULL, '2025-11-04 08:05:18', '2025-11-04 08:05:18'),
(140, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:05:22', NULL, NULL, '2025-11-04 08:05:22', '2025-11-04 08:05:22'),
(141, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:14:32', NULL, NULL, '2025-11-04 08:14:32', '2025-11-04 08:14:32'),
(142, 'cufl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:14:38', NULL, NULL, '2025-11-04 08:14:38', '2025-11-04 08:14:38'),
(143, 'cufl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:16:09', NULL, NULL, '2025-11-04 08:16:09', '2025-11-04 08:16:09'),
(144, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 14:16:13', NULL, NULL, '2025-11-04 08:16:13', '2025-11-04 08:16:13'),
(145, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:19:29', NULL, NULL, '2025-11-04 09:19:29', '2025-11-04 09:19:29'),
(146, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:20:04', NULL, NULL, '2025-11-04 09:20:04', '2025-11-04 09:20:04'),
(147, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:29:03', NULL, NULL, '2025-11-04 09:29:03', '2025-11-04 09:29:03'),
(148, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 15:29:06', NULL, NULL, '2025-11-04 09:29:06', '2025-11-04 09:29:06'),
(149, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 16:37:34', NULL, NULL, '2025-11-04 10:37:34', '2025-11-04 10:37:34'),
(150, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'success', '2025-11-04 16:37:42', NULL, NULL, '2025-11-04 10:37:42', '2025-11-04 10:37:42'),
(151, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-05 12:13:29', NULL, NULL, '2025-11-05 06:13:29', '2025-11-05 06:13:29'),
(152, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-05 13:37:10', NULL, NULL, '2025-11-05 07:37:10', '2025-11-05 07:37:10'),
(153, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-05 14:09:18', NULL, NULL, '2025-11-05 08:09:18', '2025-11-05 08:09:18'),
(154, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-06 13:03:24', NULL, NULL, '2025-11-06 07:03:24', '2025-11-06 07:03:24'),
(155, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 10:52:53', NULL, NULL, '2025-11-09 04:52:53', '2025-11-09 04:52:53'),
(156, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 10:57:02', NULL, NULL, '2025-11-09 04:57:02', '2025-11-09 04:57:02'),
(157, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 10:57:09', NULL, NULL, '2025-11-09 04:57:09', '2025-11-09 04:57:09'),
(158, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:09:07', NULL, NULL, '2025-11-09 05:09:07', '2025-11-09 05:09:07'),
(159, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:09:10', NULL, NULL, '2025-11-09 05:09:10', '2025-11-09 05:09:10'),
(160, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:11:44', NULL, NULL, '2025-11-09 05:11:44', '2025-11-09 05:11:44'),
(161, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:11:49', NULL, NULL, '2025-11-09 05:11:49', '2025-11-09 05:11:49'),
(162, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:12', NULL, NULL, '2025-11-09 05:18:12', '2025-11-09 05:18:12'),
(163, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:16', NULL, NULL, '2025-11-09 05:18:16', '2025-11-09 05:18:16'),
(164, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:41', NULL, NULL, '2025-11-09 05:18:41', '2025-11-09 05:18:41'),
(165, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:18:45', NULL, NULL, '2025-11-09 05:18:45', '2025-11-09 05:18:45'),
(166, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:40:07', NULL, NULL, '2025-11-09 05:40:07', '2025-11-09 05:40:07'),
(167, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 11:40:14', NULL, NULL, '2025-11-09 05:40:15', '2025-11-09 05:40:15'),
(168, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:27', NULL, NULL, '2025-11-09 08:59:27', '2025-11-09 08:59:27'),
(169, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:31', NULL, NULL, '2025-11-09 08:59:31', '2025-11-09 08:59:31'),
(170, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:40', NULL, NULL, '2025-11-09 08:59:40', '2025-11-09 08:59:40'),
(171, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 14:59:49', NULL, NULL, '2025-11-09 08:59:49', '2025-11-09 08:59:49'),
(172, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:07:02', NULL, NULL, '2025-11-09 09:07:02', '2025-11-09 09:07:02'),
(173, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:07:06', NULL, NULL, '2025-11-09 09:07:06', '2025-11-09 09:07:06'),
(174, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:35:49', NULL, NULL, '2025-11-09 09:35:49', '2025-11-09 09:35:49'),
(175, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:35:55', NULL, NULL, '2025-11-09 09:35:55', '2025-11-09 09:35:55'),
(176, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:37:16', NULL, NULL, '2025-11-09 09:37:16', '2025-11-09 09:37:16'),
(177, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:37:20', NULL, NULL, '2025-11-09 09:37:20', '2025-11-09 09:37:20'),
(178, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:39:22', NULL, NULL, '2025-11-09 09:39:22', '2025-11-09 09:39:22'),
(179, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:39:27', NULL, NULL, '2025-11-09 09:39:27', '2025-11-09 09:39:27'),
(180, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:53:32', NULL, NULL, '2025-11-09 09:53:32', '2025-11-09 09:53:32'),
(181, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:53:40', NULL, NULL, '2025-11-09 09:53:40', '2025-11-09 09:53:40'),
(182, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:55:36', NULL, NULL, '2025-11-09 09:55:36', '2025-11-09 09:55:36'),
(183, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:55:40', NULL, NULL, '2025-11-09 09:55:40', '2025-11-09 09:55:40'),
(184, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:05', NULL, NULL, '2025-11-09 09:56:05', '2025-11-09 09:56:05'),
(185, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:08', NULL, NULL, '2025-11-09 09:56:08', '2025-11-09 09:56:08'),
(186, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:23', NULL, NULL, '2025-11-09 09:56:23', '2025-11-09 09:56:23'),
(187, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 15:56:26', NULL, NULL, '2025-11-09 09:56:26', '2025-11-09 09:56:26'),
(188, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:36:13', NULL, NULL, '2025-11-09 10:36:13', '2025-11-09 10:36:13'),
(189, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:36:19', NULL, NULL, '2025-11-09 10:36:19', '2025-11-09 10:36:19'),
(190, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:42:59', NULL, NULL, '2025-11-09 10:42:59', '2025-11-09 10:42:59'),
(191, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:43:03', NULL, NULL, '2025-11-09 10:43:03', '2025-11-09 10:43:03'),
(192, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:46:39', NULL, NULL, '2025-11-09 10:46:39', '2025-11-09 10:46:39'),
(193, 'cufl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:46:59', NULL, NULL, '2025-11-09 10:46:59', '2025-11-09 10:46:59'),
(194, 'cufl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:47:24', NULL, NULL, '2025-11-09 10:47:24', '2025-11-09 10:47:24'),
(195, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-09 16:47:27', NULL, NULL, '2025-11-09 10:47:27', '2025-11-09 10:47:27'),
(196, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:39:11', NULL, NULL, '2025-11-10 03:39:11', '2025-11-10 03:39:11'),
(197, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:39:53', NULL, NULL, '2025-11-10 03:39:53', '2025-11-10 03:39:53'),
(198, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:39:57', NULL, NULL, '2025-11-10 03:39:57', '2025-11-10 03:39:57'),
(199, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:50:32', NULL, NULL, '2025-11-10 03:50:32', '2025-11-10 03:50:32'),
(200, 'jfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 09:50:38', NULL, NULL, '2025-11-10 03:50:38', '2025-11-10 03:50:38'),
(201, 'jfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 10:07:23', NULL, NULL, '2025-11-10 04:07:23', '2025-11-10 04:07:23'),
(202, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 10:07:27', NULL, NULL, '2025-11-10 04:07:27', '2025-11-10 04:07:27'),
(203, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 10:59:02', NULL, NULL, '2025-11-10 04:59:02', '2025-11-10 04:59:02'),
(204, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:02:52', NULL, NULL, '2025-11-10 05:02:52', '2025-11-10 05:02:52'),
(205, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:03:02', NULL, NULL, '2025-11-10 05:03:02', '2025-11-10 05:03:02'),
(206, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:03:06', NULL, NULL, '2025-11-10 05:03:06', '2025-11-10 05:03:06'),
(207, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:09:33', NULL, NULL, '2025-11-10 05:09:33', '2025-11-10 05:09:33'),
(208, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:09:43', NULL, NULL, '2025-11-10 05:09:43', '2025-11-10 05:09:43'),
(209, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:47:18', NULL, NULL, '2025-11-10 05:47:18', '2025-11-10 05:47:18'),
(210, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 11:47:24', NULL, NULL, '2025-11-10 05:47:24', '2025-11-10 05:47:24');
INSERT INTO `log_table` (`id`, `username`, `event_type`, `ip_address`, `user_agent`, `status`, `login_time`, `logout_time`, `remarks`, `created_at`, `updated_at`) VALUES
(211, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:00:08', NULL, NULL, '2025-11-10 10:00:08', '2025-11-10 10:00:08'),
(212, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-11-10 16:00:33', NULL, NULL, '2025-11-10 10:00:33', '2025-11-10 10:00:33'),
(213, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:22:21', NULL, NULL, '2025-11-10 10:22:21', '2025-11-10 10:22:21'),
(214, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:22:27', NULL, NULL, '2025-11-10 10:22:27', '2025-11-10 10:22:27'),
(215, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:22:42', NULL, NULL, '2025-11-10 10:22:42', '2025-11-10 10:22:42'),
(216, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:26:43', NULL, NULL, '2025-11-10 10:26:43', '2025-11-10 10:26:43'),
(217, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-10 16:26:47', NULL, NULL, '2025-11-10 10:26:47', '2025-11-10 10:26:47'),
(218, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:22:01', NULL, NULL, '2025-11-11 09:22:01', '2025-11-11 09:22:01'),
(219, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:27:27', NULL, NULL, '2025-11-11 09:27:27', '2025-11-11 09:27:27'),
(220, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:27:31', NULL, NULL, '2025-11-11 09:27:31', '2025-11-11 09:27:31'),
(221, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:30:54', NULL, NULL, '2025-11-11 09:30:54', '2025-11-11 09:30:54'),
(222, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:30:57', NULL, NULL, '2025-11-11 09:30:57', '2025-11-11 09:30:57'),
(223, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:50:24', NULL, NULL, '2025-11-11 09:50:24', '2025-11-11 09:50:24'),
(224, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-11 15:50:28', NULL, NULL, '2025-11-11 09:50:28', '2025-11-11 09:50:28'),
(225, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-12 10:45:55', NULL, NULL, '2025-11-12 04:45:55', '2025-11-12 04:45:55'),
(226, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-12 10:49:43', NULL, NULL, '2025-11-12 04:49:43', '2025-11-12 04:49:43'),
(227, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-12 10:49:48', NULL, NULL, '2025-11-12 04:49:48', '2025-11-12 04:49:48'),
(228, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-13 16:28:37', NULL, NULL, '2025-11-13 10:28:37', '2025-11-13 10:28:37'),
(229, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-25 11:22:11', NULL, NULL, '2025-11-25 05:22:12', '2025-11-25 05:22:12'),
(230, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-25 11:22:17', NULL, NULL, '2025-11-25 05:22:17', '2025-11-25 05:22:17'),
(231, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:34:37', NULL, NULL, '2025-11-26 08:34:37', '2025-11-26 08:34:37'),
(232, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:48:31', NULL, NULL, '2025-11-26 08:48:31', '2025-11-26 08:48:31'),
(233, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:48:35', NULL, NULL, '2025-11-26 08:48:35', '2025-11-26 08:48:35'),
(234, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:49:12', NULL, NULL, '2025-11-26 08:49:12', '2025-11-26 08:49:12'),
(235, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:49:17', NULL, NULL, '2025-11-26 08:49:17', '2025-11-26 08:49:17'),
(236, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:53:13', NULL, NULL, '2025-11-26 08:53:13', '2025-11-26 08:53:13'),
(237, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 14:53:18', NULL, NULL, '2025-11-26 08:53:18', '2025-11-26 08:53:18'),
(238, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:44:35', NULL, NULL, '2025-11-26 09:44:35', '2025-11-26 09:44:35'),
(239, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:44:39', NULL, NULL, '2025-11-26 09:44:39', '2025-11-26 09:44:39'),
(240, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:45:18', NULL, NULL, '2025-11-26 09:45:18', '2025-11-26 09:45:18'),
(241, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:45:22', NULL, NULL, '2025-11-26 09:45:22', '2025-11-26 09:45:22'),
(242, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:46:12', NULL, NULL, '2025-11-26 09:46:12', '2025-11-26 09:46:12'),
(243, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:46:15', NULL, NULL, '2025-11-26 09:46:15', '2025-11-26 09:46:15'),
(244, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:46:58', NULL, NULL, '2025-11-26 09:46:58', '2025-11-26 09:46:58'),
(245, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 15:47:03', NULL, NULL, '2025-11-26 09:47:03', '2025-11-26 09:47:03'),
(246, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:20:19', NULL, NULL, '2025-11-26 10:20:19', '2025-11-26 10:20:19'),
(247, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:20:25', NULL, NULL, '2025-11-26 10:20:25', '2025-11-26 10:20:25'),
(248, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:35:07', NULL, NULL, '2025-11-26 10:35:07', '2025-11-26 10:35:07'),
(249, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:35:12', NULL, NULL, '2025-11-26 10:35:12', '2025-11-26 10:35:12'),
(250, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:36:33', NULL, NULL, '2025-11-26 10:36:33', '2025-11-26 10:36:33'),
(251, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:36:37', NULL, NULL, '2025-11-26 10:36:38', '2025-11-26 10:36:38'),
(252, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:36:47', NULL, NULL, '2025-11-26 10:36:47', '2025-11-26 10:36:47'),
(253, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:37:17', NULL, NULL, '2025-11-26 10:37:17', '2025-11-26 10:37:17'),
(254, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:37:55', NULL, NULL, '2025-11-26 10:37:55', '2025-11-26 10:37:55'),
(255, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:38:21', NULL, NULL, '2025-11-26 10:38:21', '2025-11-26 10:38:21'),
(256, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:38:54', NULL, NULL, '2025-11-26 10:38:54', '2025-11-26 10:38:54'),
(257, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-26 16:38:58', NULL, NULL, '2025-11-26 10:38:58', '2025-11-26 10:38:58'),
(258, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:45:59', NULL, NULL, '2025-11-27 08:45:59', '2025-11-27 08:45:59'),
(259, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:46:47', NULL, NULL, '2025-11-27 08:46:47', '2025-11-27 08:46:47'),
(260, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:46:51', NULL, NULL, '2025-11-27 08:46:51', '2025-11-27 08:46:51'),
(261, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:53:22', NULL, NULL, '2025-11-27 08:53:22', '2025-11-27 08:53:22'),
(262, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 14:53:25', NULL, NULL, '2025-11-27 08:53:25', '2025-11-27 08:53:25'),
(263, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:01:45', NULL, NULL, '2025-11-27 09:01:45', '2025-11-27 09:01:45'),
(264, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', '2025-11-27 15:01:48', NULL, NULL, '2025-11-27 09:01:48', '2025-11-27 09:01:48'),
(265, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:01:55', NULL, NULL, '2025-11-27 09:01:55', '2025-11-27 09:01:55'),
(266, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:01:57', NULL, NULL, '2025-11-27 09:01:57', '2025-11-27 09:01:57'),
(267, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:00', NULL, NULL, '2025-11-27 09:02:01', '2025-11-27 09:02:01'),
(268, 'sadmin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:08', NULL, NULL, '2025-11-27 09:02:08', '2025-11-27 09:02:08'),
(269, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:12', NULL, NULL, '2025-11-27 09:02:12', '2025-11-27 09:02:12'),
(270, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:13', NULL, NULL, '2025-11-27 09:02:13', '2025-11-27 09:02:13'),
(271, 'sadmin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-11-27 15:02:17', NULL, NULL, '2025-11-27 09:02:17', '2025-11-27 09:02:17'),
(272, 'admin', 'login', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:24:00', NULL, NULL, '2025-12-01 09:24:00', '2025-12-01 09:24:00'),
(273, 'admin', 'logout', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:29:22', NULL, NULL, '2025-12-01 09:29:22', '2025-12-01 09:29:22'),
(274, 'sfcl', 'login', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:29:35', NULL, NULL, '2025-12-01 09:29:35', '2025-12-01 09:29:35'),
(275, 'sfcl', 'logout', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:32:18', NULL, NULL, '2025-12-01 09:32:18', '2025-12-01 09:32:18'),
(276, 'admin', 'login', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:32:27', NULL, NULL, '2025-12-01 09:32:27', '2025-12-01 09:32:27'),
(277, 'admin', 'logout', '192.168.2.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'success', '2025-12-01 15:37:47', NULL, NULL, '2025-12-01 09:37:47', '2025-12-01 09:37:47'),
(278, 'admin', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:27:20', NULL, NULL, '2025-12-02 05:27:20', '2025-12-02 05:27:20'),
(279, 'admin', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:30:32', NULL, NULL, '2025-12-02 05:30:32', '2025-12-02 05:30:32'),
(280, 'sfcl', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:30:39', NULL, NULL, '2025-12-02 05:30:39', '2025-12-02 05:30:39'),
(281, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 11:36:59', NULL, NULL, '2025-12-02 05:36:59', '2025-12-02 05:36:59'),
(282, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:03:59', NULL, NULL, '2025-12-02 06:03:59', '2025-12-02 06:03:59'),
(283, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:04:03', NULL, NULL, '2025-12-02 06:04:04', '2025-12-02 06:04:04'),
(284, 'sfcl', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:04:05', NULL, NULL, '2025-12-02 06:04:05', '2025-12-02 06:04:05'),
(285, 'admin', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:04:11', NULL, NULL, '2025-12-02 06:04:11', '2025-12-02 06:04:11'),
(286, 'admin', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:30', NULL, NULL, '2025-12-02 06:05:30', '2025-12-02 06:05:30'),
(287, 'sfcl', 'login', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:43', NULL, NULL, '2025-12-02 06:05:43', '2025-12-02 06:05:43'),
(288, 'sfcl', 'logout', '192.168.2.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:49', NULL, NULL, '2025-12-02 06:05:49', '2025-12-02 06:05:49'),
(289, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:05:56', NULL, NULL, '2025-12-02 06:05:56', '2025-12-02 06:05:56'),
(290, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:06:00', NULL, NULL, '2025-12-02 06:06:00', '2025-12-02 06:06:00'),
(291, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:55:06', NULL, NULL, '2025-12-02 06:55:06', '2025-12-02 06:55:06'),
(292, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-02 12:55:16', NULL, NULL, '2025-12-02 06:55:16', '2025-12-02 06:55:16'),
(293, 'admin', 'login', '192.168.3.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 09:25:52', NULL, NULL, '2025-12-03 03:25:52', '2025-12-03 03:25:52'),
(294, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:04:06', NULL, NULL, '2025-12-03 04:04:06', '2025-12-03 04:04:06'),
(295, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:09:12', NULL, NULL, '2025-12-03 04:09:12', '2025-12-03 04:09:12'),
(296, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:09:17', NULL, NULL, '2025-12-03 04:09:17', '2025-12-03 04:09:17'),
(297, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:14', NULL, NULL, '2025-12-03 04:10:14', '2025-12-03 04:10:14'),
(298, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:26', NULL, NULL, '2025-12-03 04:10:26', '2025-12-03 04:10:26'),
(299, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:40', NULL, NULL, '2025-12-03 04:10:40', '2025-12-03 04:10:40'),
(300, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:10:59', NULL, NULL, '2025-12-03 04:10:59', '2025-12-03 04:10:59'),
(301, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:53:13', NULL, NULL, '2025-12-03 04:53:13', '2025-12-03 04:53:13'),
(302, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:53:18', NULL, NULL, '2025-12-03 04:53:18', '2025-12-03 04:53:18'),
(303, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:55:40', NULL, NULL, '2025-12-03 04:55:40', '2025-12-03 04:55:40'),
(304, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 10:55:48', NULL, NULL, '2025-12-03 04:55:48', '2025-12-03 04:55:48'),
(305, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:33:23', NULL, NULL, '2025-12-03 05:33:23', '2025-12-03 05:33:23'),
(306, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:33:30', NULL, NULL, '2025-12-03 05:33:31', '2025-12-03 05:33:31'),
(307, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:38:37', NULL, NULL, '2025-12-03 05:38:37', '2025-12-03 05:38:37'),
(308, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:38:42', NULL, NULL, '2025-12-03 05:38:42', '2025-12-03 05:38:42'),
(309, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:41:36', NULL, NULL, '2025-12-03 05:41:36', '2025-12-03 05:41:36'),
(310, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 11:41:56', NULL, NULL, '2025-12-03 05:41:56', '2025-12-03 05:41:56'),
(311, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:21:05', NULL, NULL, '2025-12-03 06:21:05', '2025-12-03 06:21:05'),
(312, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:21:11', NULL, NULL, '2025-12-03 06:21:11', '2025-12-03 06:21:11'),
(313, 'admin', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:54:28', NULL, NULL, '2025-12-03 06:54:28', '2025-12-03 06:54:28'),
(314, 'sfcl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 12:54:32', NULL, NULL, '2025-12-03 06:54:32', '2025-12-03 06:54:32'),
(315, 'sfcl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 13:10:44', NULL, NULL, '2025-12-03 07:10:44', '2025-12-03 07:10:44'),
(316, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-03 13:10:51', NULL, NULL, '2025-12-03 07:10:51', '2025-12-03 07:10:51'),
(317, 'admin', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', '2025-12-04 10:11:10', NULL, NULL, '2025-12-04 04:11:11', '2025-12-04 04:11:11');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_reports`
--

CREATE TABLE `monthly_reports` (
  `id` int(11) NOT NULL,
  `report_month` date NOT NULL,
  `total_requests` int(11) DEFAULT 0,
  `completed_requests` int(11) DEFAULT 0,
  `pending_requests` int(11) DEFAULT 0,
  `avg_completion_days` decimal(5,2) DEFAULT NULL,
  `top_requester_id` int(11) DEFAULT NULL,
  `top_requester_name` varchar(100) DEFAULT NULL,
  `top_requester_count` int(11) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_attempts`
--

CREATE TABLE `password_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_attempts`
--

INSERT INTO `password_attempts` (`id`, `user_id`, `success`, `ip_address`, `user_agent`, `attempt_time`) VALUES
(1, 1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 06:38:40'),
(2, 1, 0, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 06:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `password_change_logs`
--

CREATE TABLE `password_change_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_logs`
--

CREATE TABLE `password_reset_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reset_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `division_id`, `name`) VALUES
(1, 6, 'Chairman Secretariat'),
(2, 1, 'Chief of Personnel (COP)'),
(3, 1, 'LSA'),
(4, 1, 'RNT'),
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
(23, 24, 'BCIC H.O.'),
(24, 1, 'Administration'),
(25, 35, 'DLCL'),
(26, 2, 'Accounts'),
(27, 5, 'MTS'),
(28, 73, 'Civil'),
(29, 40, 'GPUFP'),
(30, 31, 'AFCCL'),
(31, 29, 'SFCL'),
(32, 30, 'JFCL'),
(33, 45, 'BISFL'),
(34, 33, 'CUFL'),
(35, 41, 'GPFPLC'),
(36, 32, 'DAPFCL'),
(37, 34, 'TSPCL'),
(38, 36, 'KPML'),
(39, 37, 'UGSFL'),
(40, 39, 'CCCL'),
(41, 38, 'CCC'),
(42, 42, '34 Buffer Project'),
(43, 43, '13 Buffer Project'),
(44, 44, 'UF-85 Project'),
(45, 1, 'General Administration'),
(46, 67, 'Legal Affairs'),
(47, 9, 'PID'),
(48, 64, 'KNM & KHBM'),
(49, 13, 'Purchase'),
(51, 67, 'EMD'),
(52, 1, 'O&M Department'),
(53, 7, 'Urea'),
(54, 7, 'Ammonia'),
(55, 7, 'Utility'),
(56, 7, 'Operation'),
(57, 68, 'Planning'),
(58, 69, 'Production'),
(59, 72, 'HSET'),
(60, 3, 'Commercial'),
(61, 3, 'MPIC'),
(62, 3, 'Store'),
(63, 73, 'Plant Maintenance (PM)'),
(64, 73, 'Machinery Maintenance (MM)'),
(65, 73, 'Central Maintenance Workshop(CMW)'),
(66, 73, 'Solid Handling (SHSM)'),
(67, 85, 'Power Plant (PP)'),
(68, 7, 'Bagging'),
(69, 66, 'Security'),
(70, 66, 'School'),
(71, 74, 'CSD'),
(72, 75, 'BCIC College'),
(73, 66, 'Medical Center'),
(74, 14, 'Company Affairs'),
(75, 77, 'Board & Co-ordination'),
(76, 66, 'School & College'),
(77, 79, 'PDD'),
(78, 80, 'ISHD'),
(79, 66, 'Fire & Safety'),
(80, 4, 'Inspection'),
(81, 7, 'Process'),
(82, 85, 'Electrical Maintenance (EM)'),
(83, 1, 'Forest'),
(84, 4, 'Laboratory'),
(85, 4, 'Technical'),
(86, 66, 'Transport'),
(87, 84, 'Branch Office (Chittagong)');

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_logs`
--

INSERT INTO `security_logs` (`id`, `user_id`, `action`, `ip_address`, `user_agent`, `details`, `created_at`) VALUES
(1, 1, 'password_change', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"type\":\"password_change\",\"status\":\"success\"}', '2026-01-01 06:38:40'),
(2, 1, 'password_attempt', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"type\":\"password_change\",\"status\":\"failed\",\"reason\":\"incorrect_password\"}', '2026-01-01 06:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `transport_w_req_tbl`
--

CREATE TABLE `transport_w_req_tbl` (
  `id` int(10) UNSIGNED NOT NULL,
  `work_request_id` int(11) NOT NULL,
  `requester_id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `division` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `departure_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `visiting_place` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `no_of_visitor` int(10) UNSIGNED NOT NULL,
  `visit_purpose` text NOT NULL,
  `reporting_place` varchar(255) NOT NULL,
  `visiting_type` enum('Official','Personal','','') NOT NULL DEFAULT 'Official',
  `v_provide_status` enum('Yes','No','Pending') DEFAULT 'Pending',
  `approval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `driver_name` varchar(150) DEFAULT NULL,
  `vehicle_no` varchar(50) DEFAULT NULL,
  `vehicle_exit_time` time DEFAULT NULL,
  `vehicle_entry_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transport_notes` text NOT NULL,
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transport_w_req_tbl`
--

INSERT INTO `transport_w_req_tbl` (`id`, `work_request_id`, `requester_id`, `emp_id`, `date`, `full_name`, `designation`, `division`, `section`, `contact_no`, `departure_date`, `start_time`, `end_time`, `visiting_place`, `destination`, `no_of_visitor`, `visit_purpose`, `reporting_place`, `visiting_type`, `v_provide_status`, `approval_status`, `driver_name`, `vehicle_no`, `vehicle_exit_time`, `vehicle_entry_time`, `created_at`, `updated_at`, `transport_notes`, `updated_by`) VALUES
(1, 19, 1, '6594-6', '2026-01-12', 'test', 'Assistant Manager', 'ICT Division', 'ICT', '01718834655', '2026-01-13', '10:00:00', '17:00:00', 'Jhinda bazar', 'sylhet', 2, 'Cost estimate commitee', 'Admin building', 'Official', 'Pending', 'Pending', NULL, NULL, NULL, NULL, '2026-01-12 06:35:00', '2026-01-13 09:44:36', '', ''),
(2, 21, 1, '6594-6', '2026-01-12', 'test', 'Assistant Manager', 'ICT Division', 'ICT', '01718834655', '2026-01-13', '08:00:00', '17:00:00', 'Jhinda bazar', 'sylhet', 4, 'gfgf', 'Admin building', 'Official', 'Yes', 'Approved', 'samsu', '223323', '00:00:00', '00:00:00', '2026-01-12 10:31:34', '2026-01-13 10:24:56', '', '11'),
(3, 22, 1, '6594-6', '2026-01-12', 'test', 'Assistant Manager', 'ICT Division', 'ICT', '01718834655', '2026-01-14', '08:00:00', '17:00:00', 'Test', 'test', 5, 'gst', 'Admin building', 'Official', 'Pending', 'Pending', NULL, NULL, NULL, NULL, '2026-01-12 10:38:08', '2026-01-13 09:44:33', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `emp_type` enum('officer','staff','','') NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Stores hashed password using bcrypt/scrypt/argon2',
  `full_name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `role` enum('user','admin','sadmin') DEFAULT 'user',
  `routine_role` enum('section_head','division_head') DEFAULT NULL,
  `addl_role` enum('fc_officer','ot_officer','','') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `emp_type`, `emp_id`, `password`, `full_name`, `designation`, `division`, `section`, `status`, `role`, `routine_role`, `addl_role`, `created_at`, `updated_at`) VALUES
(1, '', '6594-6', '$2y$12$Fda/FmoV/lHjK25lc9c2Re8CXa89jNM4EEbB9bW9gjnCpbflNLa/u', 'test', 'Assistant Manager', 'ICT Division', 'ICT', 'active', 'user', 'section_head', '', '2025-12-07 07:21:56', '2026-01-01 06:38:40'),
(2, '', '6898-1', '$2y$12$8tcWbMoHvLG7dr7AIrrP5.HKKOnkJTo0eRuz.iBdd3x4MwmAlT5oG', 'cufl', 'Assistant Manager', 'HR', 'HR', 'active', 'user', 'section_head', '', '2025-12-07 07:29:53', '2025-12-18 05:08:54'),
(3, '', '6826-2', '$2y$12$HGVol0HIzmpbX47xhlTAAeqD7WyRJ3oQdaCLbTVXV06MprFs.kQaS', 'BCIC H.O', 'Assistant Programmer', 'IT', 'IT', 'active', 'user', 'section_head', '', '2025-12-07 07:53:24', '2025-12-18 05:08:36'),
(4, '', '6851-0', '$2y$12$uq2nMdp2EkK8z9cCMRWqUejfRt3DIy0G3YHne9q/FS2pKmPPb6gW2', 'JFCL', 'Deputy Chief Engineer', 'MTS (Mechanical)', 'Machinery Maintenance (MM)', 'active', 'user', 'section_head', '', '2025-12-07 07:55:57', '2026-01-06 07:43:23'),
(5, '', 'sadmin', '$2y$10$BWyOp9JLrXXzrRUp.2Jreu38rDdVfY.OcBozlvEW8BAY61yOW/3di', 'sadmin', NULL, NULL, NULL, 'active', 'sadmin', NULL, '', '2025-12-07 09:57:34', '2025-12-07 09:57:34'),
(6, '', '5620-0', '$2y$12$CsvVGcIgYWDXI7OvGXSEDeBHfc/c/hzofTFfiMafuNZ53ySTXl/82', 'emran', 'Assistant Programmer', 'MTS (Electrical)', 'Electrical Maintenance (EM)', 'active', 'user', 'section_head', '', '2025-12-18 03:37:49', '2025-12-29 08:21:32'),
(7, 'officer', '4665-5', '$2y$12$vQjPNckNm//I3jtQqKQhZeClZQhJJO8OKHVYFwzfqA4oy/xgSWZW.', 'fared', 'Sub Assistant Engr.', 'Administration Division', 'pm', 'active', 'user', NULL, '', '2025-12-18 03:39:45', '2026-03-12 06:18:34'),
(8, '', '5620-4', '$2y$12$KypWb93MnifYY9wgmr/yL.WB39RQf7HHn8mShefT2xwF3ChYeJ4Fu', 'Kamal', 'Additional Chief Engineer', 'MTS (Electrical)', 'Electrical Maintenance (EM)', 'active', 'user', 'division_head', '', '2025-12-28 09:33:53', '2025-12-29 08:19:39'),
(9, '', '4569-8', '$2y$12$a7tsBKVcXYVxKXyixOWKsOecV8GQySrcTPpro/FlkBrWW82Hwuxou', 'Gias', 'Deputy Chief Engineer', 'MTS (Mechanical)', 'Civil', 'active', 'user', 'section_head', '', '2026-01-01 03:35:29', '2026-01-01 05:15:21'),
(10, '', '4956-9', '$2y$12$LeJXRgRhjFemFhqawz8RfuhY9O45N7Wj97O3NVa7lFllvigD0/HEG', 'Mr. Md. Mahmudul Hasan Chowdhury', 'Deputy Chief Engineer', 'ICT Division', 'ICT', 'active', 'admin', NULL, '', '2026-01-01 05:45:17', '2026-01-01 10:08:23'),
(11, '', '7079-8', '$2y$12$P/jEkmIVW5epJH1oh6XX1u0Me070HnS01CNKHKR57.3IG5addRsQS', 'Transport', 'Executive Engineer', 'Administration Division', 'Transport', 'active', 'user', 'section_head', 'fc_officer', '2026-01-13 06:27:47', '2026-01-13 06:28:19'),
(12, 'officer', '2136-9', '$2y$12$wML4MNPMuDVLqwEtZrU2Qu4azhrvBDRVnhX2kZoNAgB/N8ODJkaqW', 'Tipu', 'Executive Engineer', 'MTS (Mechanical)', 'Plant Maintenance (PM)', 'active', 'user', NULL, '', '2026-02-15 04:55:44', '2026-03-12 06:18:54'),
(13, 'staff', '4563-5', '$2y$12$fgEckmuPQrW2Q.N0JggJIe9L37b9gsZNKZ9LPCrwIKfM4145qqGpe', 'hasan rahman b', 'ST. CUM COMPUTER OPERATOR', 'Technical Division', 'Technical', 'active', 'user', NULL, '', '2026-02-15 05:16:36', '2026-03-12 06:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `factory_name` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` enum('admin','user','sadmin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`id`, `username`, `password`, `full_name`, `factory_name`, `designation`, `email`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'sfcl', '$2y$10$qfxm.dHbgqvVpFyANyLjgui0IX/47onIOW6icpv.ob2b5cQDg6rzK', 'SFCL..', 'SFCL', 'Assistant Manager (Admin)', 'sfcl@yahoo.com', '2222', 'user', '2025-10-14 08:00:19', '2025-11-27 09:02:07'),
(2, 'jfcl', '$2y$10$JJlrLH/y.Lh5elcETMBNf.i/zmkZL718QER/yNfKK3kWUbtH9VNyO', 'JFCL', 'JFCL', NULL, NULL, NULL, 'user', '2025-10-26 03:45:21', '2025-10-26 03:45:21'),
(3, 'admin', '$2y$10$TdA8pCKlQmdP56Jm2CD.fuEVpKskNFAgsjyzFluSoTwR8u5rc3nry', 'admin', 'admin', NULL, NULL, NULL, 'admin', '2025-10-26 04:29:52', '2025-10-26 04:29:52'),
(4, 'cufl', '$2y$10$dn6pkZzdsCFuZmZkPDYed.HetnHL8vy93uQN49Vtr.ui5D8gsvNqm', 'cufl', 'cufl', NULL, NULL, NULL, 'user', '2025-11-04 08:14:17', '2025-11-04 08:14:17'),
(5, 'sadmin', '$2y$10$i6XFeHtV7DpnNXIcljHS5ehs6Y8l.5PZ2BJqUUotXS9bFXGOXVXe6', 'sadmin', NULL, NULL, NULL, NULL, 'sadmin', '2025-11-10 10:22:07', '2025-11-10 10:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_edit_logs`
--

CREATE TABLE `user_edit_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `changes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`changes`)),
  `edited_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_edit_logs`
--

INSERT INTO `user_edit_logs` (`id`, `admin_id`, `user_id`, `changes`, `edited_at`) VALUES
(1, 5, 9, '{\"admin\":\"sadmin\",\"user\":\"4569-8\",\"changes\":{\"emp_id\":\"4569-8\",\"full_name\":\"Gias\",\"designation\":\"Deputy Chief Engineer\",\"division\":\"MTS (Mechanical)\",\"section\":\"Civil\",\"status\":\"active\",\"role\":\"user\",\"routine_role\":\"section_head\"}}', '2026-01-01 05:05:03'),
(2, 5, 10, '{\"admin\":\"sadmin\",\"user\":\"4956-9\",\"changes\":{\"emp_id\":\"4956-9\",\"full_name\":\"Mr. Md. Mahmudul Hasan Chowdhury\",\"designation\":\"Deputy Chief Engineer\",\"division\":\"ICT Division\",\"section\":\"ICT\",\"status\":\"active\",\"role\":\"admin\",\"routine_role\":\"\"}}', '2026-01-01 05:45:43'),
(3, 5, 10, '{\"admin\":\"sadmin\",\"user\":\"4956-9\",\"changes\":{\"emp_id\":\"4956-9\",\"full_name\":\"Mr. Md. Mahmudul Hasan Chowdhury\",\"designation\":\"Deputy Chief Engineer\",\"division\":\"ICT Division\",\"section\":\"ICT\",\"status\":\"inactive\",\"role\":\"admin\",\"routine_role\":\"\"}}', '2026-01-01 10:02:17'),
(4, 5, 4, '{\"admin\":\"sadmin\",\"user\":\"6851-0\",\"changes\":{\"emp_id\":\"6851-0\",\"full_name\":\"JFCL\",\"designation\":\"Deputy Chief Engineer\",\"division\":\"MTS (Mechanical)\",\"section\":\"Machinery Maintenance (MM)\",\"status\":\"active\",\"role\":\"user\",\"routine_role\":\"section_head\"}}', '2026-01-06 06:56:59'),
(5, 5, 11, '{\"admin\":\"sadmin\",\"user\":\"7079-8\",\"changes\":{\"emp_id\":\"7079-8\",\"full_name\":\"Transport\",\"designation\":\"Executive Engineer\",\"division\":\"Administration Division\",\"section\":\"Transport\",\"status\":\"active\",\"role\":\"user\",\"routine_role\":\"section_head\"}}', '2026-01-13 06:28:19'),
(6, 5, 13, '{\"admin\":\"sadmin\",\"user\":\"4563-5\",\"changes\":{\"emp_type\":\"Staff\",\"emp_id\":\"4563-5\",\"full_name\":\"hasan rahman b\",\"designation\":\"ST. CUM COMPUTER OPERATOR\",\"division\":\"Technical Division\",\"section\":\"Technical\",\"status\":\"active\",\"role\":\"user\",\"routine_role\":\"\"}}', '2026-02-15 09:55:27'),
(7, 5, 13, '{\"admin\":\"sadmin\",\"user\":\"4563-5\",\"changes\":{\"emp_type\":\"Staff\",\"emp_id\":\"4563-5\",\"full_name\":\"hasan rahman b\",\"designation\":\"ST. CUM COMPUTER OPERATOR\",\"division\":\"Technical Division\",\"section\":\"Technical\",\"status\":\"active\",\"role\":\"user\",\"routine_role\":\"\"}}', '2026-02-15 09:56:41'),
(8, 5, 13, '{\"admin\":\"sadmin\",\"user\":\"4563-5\",\"changes\":{\"emp_type\":\"Staff\",\"emp_id\":\"4563-5\",\"full_name\":\"hasan rahman b\",\"designation\":\"ST. CUM COMPUTER OPERATOR\",\"division\":\"Technical Division\",\"section\":\"Technical\",\"status\":\"active\",\"role\":\"user\",\"routine_role\":\"\"}}', '2026-02-15 09:57:08'),
(9, 5, 13, '{\"admin\":\"sadmin\",\"user\":\"4563-5\",\"changes\":{\"emp_type\":\"Staff\",\"emp_id\":\"4563-5\",\"full_name\":\"hasan rahman b\",\"designation\":\"ST. CUM COMPUTER OPERATOR\",\"division\":\"Technical Division\",\"section\":\"Technical\",\"status\":\"active\",\"role\":\"user\",\"routine_role\":\"\"}}', '2026-02-15 09:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `work_request_history`
--

CREATE TABLE `work_request_history` (
  `id` int(11) NOT NULL,
  `work_request_id` int(11) NOT NULL,
  `status` enum('normal','urgent','very urgent') NOT NULL,
  `w_com_status` enum('complete','incomplete') NOT NULL,
  `updated_by` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_request_tbl`
--

CREATE TABLE `work_request_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `w_req_type` enum('ICT','Civil','Transport','Electrical','Mechanical') NOT NULL,
  `w_location` varchar(200) NOT NULL,
  `w_description` text NOT NULL,
  `w_com_division` varchar(100) NOT NULL,
  `w_com_section` varchar(100) NOT NULL,
  `w_com_status` enum('complete','incomplete') DEFAULT 'incomplete',
  `status` enum('normal','urgent','very urgent') DEFAULT 'normal',
  `remarks` text DEFAULT NULL,
  `w_com_div_remarks` text NOT NULL,
  `requester_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `division` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_request_tbl`
--

INSERT INTO `work_request_tbl` (`id`, `emp_id`, `date`, `w_req_type`, `w_location`, `w_description`, `w_com_division`, `w_com_section`, `w_com_status`, `status`, `remarks`, `w_com_div_remarks`, `requester_id`, `full_name`, `designation`, `division`, `section`, `created_at`, `updated_at`) VALUES
(8, '', '2025-12-29', 'ICT', 'Technical Building', 'fdsfds fdsf f sdf', 'ICT Division', 'ICT', 'complete', 'normal', 'bb', '', 7, 'fared', 'Sub Assistant Engr.', 'HR', 'pm', '2025-12-29 08:08:15', '2026-01-05 09:48:49'),
(9, '', '2025-12-29', 'Electrical', 'Power station', 'cdsf dsgfdsf dsfsdf', 'MTS (Electrical)', 'Electrical Maintenance (EM)', 'incomplete', 'normal', 'Not perfectly completed', '', 7, 'fared', 'Sub Assistant Engr.', 'HR', 'pm', '2025-12-29 08:20:21', '2026-01-05 10:43:01'),
(10, '', '2025-12-30', 'ICT', 'Audit Division', 'Desktop need repair', 'ICT Division', 'ICT', 'incomplete', 'normal', '', '', 6, 'emran', 'Assistant Programmer', 'MTS (Electrical)', 'Electrical Maintenance (EM)', '2025-12-30 06:26:50', '2026-01-05 10:43:55'),
(11, '', '2025-12-30', 'Civil', 'Technical Building', 'water tape are broken', 'MTS (Mechanical)', 'Civil', 'incomplete', 'normal', '', '', 7, 'fared', 'Sub Assistant Engr.', 'HR', 'pm', '2025-12-30 10:29:49', '2026-01-01 05:07:47'),
(12, '', '2026-01-01', 'Civil', 'Technical Building', 'বিল্ডিং এর জানালা নষ্ট', 'MTS (Mechanical)', 'Civil', 'complete', 'normal', '', '', 1, 'test', 'Assistant Manager', 'ICT Division', 'ICT', '2026-01-01 08:32:15', '2026-01-01 08:41:41'),
(13, '', '2026-01-01', 'ICT', 'Admin Building', 'Do not open PC', 'ICT Division', 'ICT', 'complete', 'normal', 'cxvcv sdfsdfsd', '', 7, 'fared', 'Sub Assistant Engr.', 'HR', 'pm', '2026-01-01 10:11:24', '2026-01-05 09:39:03'),
(14, '', '2026-01-06', 'Mechanical', 'Power station', 'control valve repair', 'MTS (Mechanical)', 'Machinery Maintenance (MM)', 'incomplete', 'normal', '', '', 7, 'fared', 'Sub Assistant Engr.', 'HR', 'pm', '2026-01-06 07:10:36', '2026-01-06 07:17:55'),
(15, '', '2026-01-12', 'Civil', 'Technical Building', 'fdgdfg dsfgfsdfs sdfsdfs', 'MTS (Mechanical)', 'Civil', 'incomplete', 'normal', '', '', 1, 'test', 'Assistant Manager', 'ICT Division', 'ICT', '2026-01-12 06:13:49', '2026-01-12 06:13:49'),
(19, '6594-6', '2026-01-12', 'Transport', 'Transport Request - sylhet', 'Transport request for Cost estimate commitee', 'Transport Division', 'Transport', 'incomplete', 'normal', 'Transport Type: Official', '', 1, 'test', 'Assistant Manager', 'ICT Division', 'ICT', '2026-01-12 06:35:00', '2026-01-12 06:35:00'),
(20, '6594-6', '2026-01-12', 'Electrical', 'Power station dfff', 'fdsf dfsfsdf sdfsdfsd', 'MTS (Electrical)', 'Electrical Maintenance (EM)', 'incomplete', 'normal', '', '', 1, 'test', 'Assistant Manager', 'ICT Division', 'ICT', '2026-01-12 10:27:23', '2026-01-12 10:27:23'),
(21, '6594-6', '2026-01-12', 'Transport', 'Transport Request - sylhet', 'Transport request for gfgf', 'Transport Division', 'Transport', 'incomplete', 'normal', 'Transport Type: Official', '', 1, 'test', 'Assistant Manager', 'ICT Division', 'ICT', '2026-01-12 10:31:34', '2026-01-12 10:31:34'),
(22, '4563-5', '2026-02-15', 'Electrical', 'NO2 Plant', 'Need Necessary Electric bulb', 'MTS (Electrical)', 'Electrical Maintenance (EM)', 'incomplete', 'normal', '', '', 13, 'hasan rahman', 'ST. CUM COMPUTER OPERATOR', 'Technical Division', 'Technical', '2026-02-15 08:48:20', '2026-02-15 08:50:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_action_type` (`action_type`),
  ADD KEY `idx_request` (`request_id`),
  ADD KEY `idx_action_time` (`action_time`);

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
-- Indexes for table `fc_tbl`
--
ALTER TABLE `fc_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_table`
--
ALTER TABLE `log_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_reports`
--
ALTER TABLE `monthly_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_month` (`report_month`),
  ADD KEY `top_requester_id` (`top_requester_id`),
  ADD KEY `idx_report_month` (`report_month`);

--
-- Indexes for table `password_attempts`
--
ALTER TABLE `password_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attempts_user_time` (`user_id`,`attempt_time`),
  ADD KEY `idx_attempts_time` (`attempt_time`);

--
-- Indexes for table `password_change_logs`
--
ALTER TABLE `password_change_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_changed_at` (`changed_at`);

--
-- Indexes for table `password_reset_logs`
--
ALTER TABLE `password_reset_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_reset_at` (`reset_at`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_security_user` (`user_id`),
  ADD KEY `idx_security_action` (`action`),
  ADD KEY `idx_security_time` (`created_at`);

--
-- Indexes for table `transport_w_req_tbl`
--
ALTER TABLE `transport_w_req_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_emp_id` (`emp_id`),
  ADD KEY `idx_division` (`division`),
  ADD KEY `idx_section` (`section`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_edit_logs`
--
ALTER TABLE `user_edit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_edited_at` (`edited_at`);

--
-- Indexes for table `work_request_history`
--
ALTER TABLE `work_request_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_work_request` (`work_request_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `work_request_tbl`
--
ALTER TABLE `work_request_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_req_type` (`w_req_type`),
  ADD KEY `idx_com_status` (`w_com_status`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_division` (`w_com_division`),
  ADD KEY `idx_requester` (`requester_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `fc_tbl`
--
ALTER TABLE `fc_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=318;

--
-- AUTO_INCREMENT for table `monthly_reports`
--
ALTER TABLE `monthly_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_attempts`
--
ALTER TABLE `password_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_change_logs`
--
ALTER TABLE `password_change_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_logs`
--
ALTER TABLE `password_reset_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transport_w_req_tbl`
--
ALTER TABLE `transport_w_req_tbl`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_edit_logs`
--
ALTER TABLE `user_edit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `work_request_history`
--
ALTER TABLE `work_request_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_request_tbl`
--
ALTER TABLE `work_request_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  ADD CONSTRAINT `admin_action_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_action_logs_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `work_request_tbl` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `monthly_reports`
--
ALTER TABLE `monthly_reports`
  ADD CONSTRAINT `monthly_reports_ibfk_1` FOREIGN KEY (`top_requester_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `password_attempts`
--
ALTER TABLE `password_attempts`
  ADD CONSTRAINT `password_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_change_logs`
--
ALTER TABLE `password_change_logs`
  ADD CONSTRAINT `password_change_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_reset_logs`
--
ALTER TABLE `password_reset_logs`
  ADD CONSTRAINT `password_reset_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `password_reset_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD CONSTRAINT `security_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_edit_logs`
--
ALTER TABLE `user_edit_logs`
  ADD CONSTRAINT `user_edit_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_edit_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_request_history`
--
ALTER TABLE `work_request_history`
  ADD CONSTRAINT `work_request_history_ibfk_1` FOREIGN KEY (`work_request_id`) REFERENCES `work_request_tbl` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_request_history_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_request_tbl`
--
ALTER TABLE `work_request_tbl`
  ADD CONSTRAINT `work_request_tbl_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
