-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2024 at 07:45 PM
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
-- Database: `pmis_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

CREATE TABLE `award` (
  `id` int(11) NOT NULL,
  `auto_id` int(11) NOT NULL,
  `award_or_penalty` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `award`
--

INSERT INTO `award` (`id`, `auto_id`, `award_or_penalty`) VALUES
(1, 1, 'Special Increment'),
(2, 1, 'Special Promotion'),
(3, 1, 'Cash Award'),
(4, 1, 'Commendation Certificate'),
(5, 1, 'Appreciation Letter\r\n'),
(6, 1, 'Innovation'),
(7, 1, 'NIS'),
(8, 2, 'Temporary Suspensions'),
(9, 2, 'Increment Held-up'),
(10, 2, 'Demotion'),
(11, 2, 'Warning'),
(12, 2, 'Show cause');

-- --------------------------------------------------------

--
-- Table structure for table `award_and_penalty`
--

CREATE TABLE `award_and_penalty` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `given_date` date NOT NULL,
  `status` enum('Award','Penalty','','') NOT NULL,
  `nature` enum('Special Increment','Special Promotion','Cash Award','Commendation Certificate','Appreciation Letter','Innovation','NIS','Temporary Suspensions','Increment Held-up','Demotion','Warning','Show cause') NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `ground_or_subject` text NOT NULL,
  `special_increment` int(100) NOT NULL,
  `special_promotion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `award_and_penalty`
--

INSERT INTO `award_and_penalty` (`id`, `user_id`, `emp_id`, `given_date`, `status`, `nature`, `from_date`, `to_date`, `ground_or_subject`, `special_increment`, `special_promotion`) VALUES
(1, 8, '5620-4', '2023-10-11', 'Award', 'Special Promotion', NULL, NULL, 'PHP (recursive acronym for PHP: Hypertext Preprocessor) is a widely-used open source general-purpose scripting language that is especially suited for web development and can be embedded into HTML.', 0, ''),
(2, 0, '5620-4', '2023-06-26', 'Award', 'Innovation', NULL, NULL, '4IR Related', 0, ''),
(19, 0, '5620-11', '2023-08-02', 'Award', 'Special Increment', '0000-00-00', '0000-00-00', 'Special Increment:', 2, ''),
(57, 0, '5620-1', '2000-10-30', 'Award', 'NIS', '0000-00-00', '0000-00-00', 'vv', 0, ''),
(59, 0, '5620-1', '2020-12-30', 'Award', 'Special Increment', '0000-00-00', '0000-00-00', '', 1, ''),
(60, 0, '5620-1', '2020-12-30', 'Award', 'Special Increment', '0000-00-00', '0000-00-00', '', 1, ''),
(63, 0, '5620-11', '2024-01-11', 'Penalty', 'Increment Held-up', '2024-01-11', '2025-12-11', '', 0, ''),
(67, 0, '5620-21', '2024-01-11', 'Penalty', 'Increment Held-up', '2023-12-01', '2024-06-01', '', 0, ''),
(69, 0, '5620-51', '2024-01-11', 'Penalty', 'Increment Held-up', '2023-12-01', '2024-01-11', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `basic`
--

CREATE TABLE `basic` (
  `id` int(11) NOT NULL,
  `basic` varchar(25) NOT NULL,
  `scale_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `basic`
--

INSERT INTO `basic` (`id`, `basic`, `scale_id`) VALUES
(1, '78000', 1),
(2, '66000', 2),
(3, '68480', 2),
(4, '71050', 2),
(5, '73720', 2),
(6, '76490', 2),
(7, '56500', 3),
(8, '58760', 3),
(9, '61120', 3),
(10, '63570', 3),
(11, '66120', 3),
(12, '68770', 3),
(13, '71530', 3),
(14, '74400', 3),
(15, '50000', 4),
(16, '52000', 4),
(17, '54080', 4),
(18, '56250', 4),
(19, '58500', 4),
(20, '60840', 4),
(21, '63280', 4),
(22, '65820', 4),
(23, '68460', 4),
(24, '71200', 4),
(25, '43000', 5),
(26, '44140', 5),
(27, '46670', 5),
(28, '49090', 5),
(29, '51300', 5),
(30, '53610', 5),
(31, '44490', 5),
(32, '46970', 5),
(33, '49090', 5),
(34, '51300', 5),
(35, '53610', 5),
(36, '56030', 5),
(37, '58560', 5),
(38, '61200', 5),
(39, '63960', 5),
(40, '66840', 5),
(41, '69850', 5),
(42, '35500', 6),
(43, '37280', 6),
(44, '39150', 6),
(45, '41110', 6),
(46, '43170', 6),
(47, '45330', 6),
(48, '47600', 6),
(49, '49980', 6),
(50, '52480', 6),
(51, '55110', 6),
(52, '57870', 6),
(53, '60770', 6),
(54, '63810', 6),
(55, '67010', 6),
(56, '29000', 7),
(57, '30450', 7),
(58, '31980', 7),
(59, '33580', 7),
(60, '35260', 7),
(61, '37030', 7),
(62, '38890', 7),
(63, '40840', 7),
(64, '42890', 7),
(65, '45040', 7),
(66, '47300', 7),
(67, '49670', 7),
(68, '52160', 7),
(69, '54770', 7),
(70, '57510', 7),
(71, '60390', 7),
(72, '63410', 7),
(73, '23000', 8),
(74, '24150', 8),
(75, '25360', 8),
(76, '26630', 8),
(77, '27970', 8),
(78, '29370', 8),
(79, '30840', 8),
(80, '32390', 8),
(81, '34010', 8),
(82, '35720', 8),
(83, '37510', 8),
(84, '39390', 8),
(85, '41360', 8),
(86, '43430', 8),
(87, '45610', 8),
(88, '47900', 8),
(89, '50300', 8),
(90, '52820', 8),
(91, '55470', 8),
(92, '22000', 9),
(93, '23100', 9),
(94, '24260', 9),
(95, '25480', 9),
(96, '26760', 9),
(97, '28100', 9),
(98, '29510', 9),
(99, '30990', 9),
(100, '32540', 9),
(101, '34170', 9),
(102, '35880', 9),
(103, '37680', 9),
(104, '39570', 9),
(105, '41550', 9),
(106, '43630', 9),
(107, '45820', 9),
(108, '48120', 9),
(109, '50530', 9),
(110, '53060', 9),
(111, '16000', 10),
(112, '16800', 10),
(113, '17640', 10),
(114, '18530', 10),
(115, '19460', 10),
(116, '20440', 10),
(117, '21470', 10),
(118, '22550', 10),
(119, '23680', 10),
(120, '24870', 10),
(121, '26120', 10),
(122, '27430', 10),
(123, '28810', 10),
(124, '30260', 10),
(125, '31780', 10),
(126, '33370', 10),
(127, '35040', 10),
(128, '36800', 10),
(129, '38640', 10),
(130, '12500', 11),
(131, '13130', 11),
(132, '13790', 11),
(133, '14480', 11),
(134, '15210', 11),
(135, '15980', 11),
(136, '16780', 11),
(137, '17620', 11),
(138, '18510', 11),
(139, '19440', 11),
(140, '20420', 11),
(141, '21450', 11),
(142, '22530', 11),
(143, '23660', 11),
(144, '24850', 11),
(145, '26100', 11),
(146, '27410', 11),
(147, '28790', 11),
(148, '30230', 11),
(149, '11300', 12),
(150, '11870', 12),
(151, '12470', 12),
(152, '13100', 12),
(153, '13760', 12),
(154, '14450', 12),
(155, '15180', 12),
(156, '15940', 12),
(157, '16740', 12),
(158, '17580', 12),
(159, '18460', 12),
(160, '19390', 12),
(161, '20360', 12),
(162, '21380', 12),
(163, '22450', 12),
(164, '23580', 12),
(165, '24760', 12),
(166, '26000', 12),
(167, '27300', 12),
(168, '11000', 13),
(169, '11550', 13),
(170, '12130', 13),
(171, '12740', 13),
(172, '13380', 13),
(173, '14050', 13),
(174, '14760', 13),
(175, '15500', 13),
(176, '16280', 13),
(177, '17100', 13),
(178, '17960', 13),
(179, '18860', 13),
(180, '19810', 13),
(181, '20810', 13),
(182, '21860', 13),
(183, '22960', 13),
(184, '24110', 13),
(185, '25320', 13),
(186, '26590', 13),
(187, '10200', 14),
(188, '10710', 14),
(189, '11250', 14),
(190, '11820', 14),
(191, '12420', 14),
(192, '13050', 14),
(193, '13710', 14),
(194, '14400', 14),
(195, '15120', 14),
(196, '15880', 14),
(197, '16680', 14),
(198, '17520', 14),
(199, '18400', 14),
(200, '19320', 14),
(201, '20290', 14),
(202, '21310', 14),
(203, '22380', 14),
(204, '23500', 14),
(205, '24680', 14),
(206, '9700', 15),
(207, '10190', 15),
(208, '10700', 15),
(209, '11240', 15),
(210, '11810', 15),
(211, '12410', 15),
(212, '13040', 15),
(213, '13700', 15),
(214, '14390', 15),
(215, '15110', 15),
(216, '15870', 15),
(217, '16670', 15),
(218, '17510', 15),
(219, '18390', 15),
(220, '19310', 15),
(221, '20280', 15),
(222, '21300', 15),
(223, '22370', 15),
(224, '23490', 15),
(225, '9300', 16),
(226, '9770', 16),
(227, '10260', 16),
(228, '10780', 16),
(229, '11320', 16),
(230, '11890', 16),
(231, '12490', 16),
(232, '13120', 16),
(233, '13780', 16),
(234, '14470', 16),
(235, '15200', 16),
(236, '15960', 16),
(237, '16760', 16),
(238, '17600', 16),
(239, '18480', 16),
(240, '19410', 16),
(241, '20390', 16),
(242, '21410', 16),
(243, '22490', 16),
(244, '9000', 17),
(245, '9450', 17),
(246, '9930', 17),
(247, '10430', 17),
(248, '10960', 17),
(249, '11510', 17),
(250, '12090', 17),
(251, '12700', 17),
(252, '13340', 17),
(253, '14010', 17),
(254, '14720', 17),
(255, '15460', 17),
(256, '16240', 17),
(257, '17060', 17),
(258, '17920', 17),
(259, '18820', 17),
(260, '19770', 17),
(261, '20760', 17),
(262, '21800', 17),
(263, '8800', 18),
(264, '9240', 18),
(265, '9710', 18),
(266, '10200', 18),
(267, '10710', 18),
(268, '11250', 18),
(269, '11820', 18),
(270, '12420', 18),
(271, '13050', 18),
(272, '13710', 18),
(273, '14400', 18),
(274, '15120', 18),
(275, '15880', 18),
(276, '16680', 18),
(277, '17520', 18),
(278, '18400', 18),
(279, '19320', 18),
(280, '20290', 18),
(281, '21310', 18),
(282, '8500', 19),
(283, '8930', 19),
(284, '9380', 19),
(285, '9850', 19),
(286, '10350', 19),
(287, '10870', 19),
(288, '11420', 19),
(289, '12000', 19),
(290, '12600', 19),
(291, '13230', 19),
(292, '13900', 19),
(293, '14600', 19),
(294, '15330', 19),
(295, '16100', 19),
(296, '16910', 19),
(297, '17760', 19),
(298, '18650', 19),
(299, '19590', 19),
(300, '20570', 19),
(301, '8250', 20),
(302, '8670', 20),
(303, '9110', 20),
(304, '9570', 20),
(305, '10050', 20),
(306, '10560', 20),
(307, '11090', 20),
(308, '11650', 20),
(309, '12240', 20),
(310, '12860', 20),
(311, '13510', 20),
(312, '14190', 20),
(313, '14900', 20),
(314, '15650', 20),
(315, '16440', 20),
(316, '17270', 20),
(317, '18140', 20),
(318, '19050', 20),
(319, '20010', 20);

-- --------------------------------------------------------

--
-- Table structure for table `basic_info`
--

CREATE TABLE `basic_info` (
  `id` int(10) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `seniority_no` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `name_bn` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(100) NOT NULL,
  `sub_cadre_header` varchar(50) NOT NULL,
  `division` varchar(25) NOT NULL,
  `section` varchar(25) NOT NULL,
  `job_status` enum('In Service','PRL','Suspended','Retired','Dead with Services','LPR') NOT NULL,
  `fathersName` varchar(100) NOT NULL,
  `mothersName` varchar(100) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `blood_group` varchar(15) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `nationality` varchar(25) NOT NULL,
  `nid` varchar(13) NOT NULL,
  `quota` varchar(50) NOT NULL,
  `mobile_no` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `home_dist` varchar(25) NOT NULL,
  `maritial_status` varchar(15) NOT NULL,
  `spouse_name` varchar(100) NOT NULL,
  `spouse_home_dist` varchar(25) NOT NULL,
  `spouse_occupation` varchar(100) NOT NULL,
  `spo_present_add` text NOT NULL,
  `spo_parmanent_add` text DEFAULT NULL,
  `present_add` text NOT NULL,
  `permanent_add` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `sign_img_path` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `basic_info`
--

INSERT INTO `basic_info` (`id`, `emp_id`, `seniority_no`, `name`, `name_bn`, `designation`, `sub_cadre_header`, `division`, `section`, `job_status`, `fathersName`, `mothersName`, `gender`, `blood_group`, `religion`, `nationality`, `nid`, `quota`, `mobile_no`, `email`, `home_dist`, `maritial_status`, `spouse_name`, `spouse_home_dist`, `spouse_occupation`, `spo_present_add`, `spo_parmanent_add`, `present_add`, `permanent_add`, `image`, `sign_img_path`, `created_at`, `last_updated`) VALUES
(4, '5620-2', 46, 'salim', '', 'Sr. System Analyst', '', 'ICT Division', 'ICT', 'In Service', 'sabou t', 'dayna', 'Male', '', 'Islam', '', '987654321', '', '1234567890', 'tareq@gmail.com', '', '', '', '', '', '', NULL, 'kaliganj', 'jhenaidah', '946987221.jpg', 'image/', '2023-04-28 12:07:45', '2023-12-10 06:57:51'),
(6, '5620-1', 1, 'Sunil Chandra Das ', 'সুনিল চন্দ্র দাস bb', 'General Manager', 'Chemical', 'Audit Division', 'AFCCL', 'In Service', 'Isahaque Ali', 'Rumisa Begum', 'Male', 'AB+', 'Islam', 'Bd', '2147483647', 'Grand Child of Freedom Fighter', '01718834659', 'admin@gmail.com', 'Jhenaidah', 'Married', 'Irin akter', 'Faridpur', 'Govt. services', 'fddf', 'fddf', 'Kaliganj, Jhenaidha', 'Kaliganj, Jhenaidha', '../images/ffa2bd4125ab20171004_092347.jpg', '../signature/', '2023-04-28 12:07:45', '2024-01-26 17:24:57'),
(7, '5620-3', 8, 'Ali Akkas', '', 'Executive Engineer', '', 'Administration', 'Admin', 'In Service', '', '', 'Male', 'B+', '', '', '0', '', '01557009083', 'admin@webdamn.com', '', '', '', '', '', '', NULL, '', '', '1941336232.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(8, '5620-4', 19, 'Mohammad Shoaib Billal', '', 'Additional Chief Engineer', '', 'Purchase Division', 'MTS', 'In Service', 'Md. Abdul Helal', 'Ripna', 'Male', 'B+', 'Islam', '', '2147483647', 'Non Qoutas', '01915555540', 'emrantareq05@gmail.com', 'Netrokona', 'Married', 'dalia', 'Shariatpur', 'govt. Services', 'sharitpur', 'sharitpur', 'Netrokona', 'Netrokona', '764083998.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(9, '5620-11', 20, 'jamal', '', 'Senior General Manager', '', 'Chairman Secretariat', 'Chairman Secretariat', 'In Service', '', '', 'Male', 'AB', 'Islam', '', '2147483647', 'Non Qoutas', '01718834655', 'ha@yahoo.com', '', 'Married', 'irin akter', 'Mymensingh', 'govt.', 'kjhkh hhhhhhhhhhhh', 'kjhkh hhhhhhhhhhhh', 'jjbkjh', '', '../image/dir tech.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(10, '5620-12', 21, 'Mohammad Shoaib', '', 'Senior General Manager', '', 'Director (Com.)', 'Director (Com.)', 'PRL', 'x', 'y', 'Male', 'B+', 'Array', '', '1234567890', '', '01718834655', 'f@hggg.com', '', '', '', '', '', '', NULL, 'Kaliganj', 'Kaliganj', '../image/bcic.jpg', 'signature/', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(11, '5620-13', 22, 'lalaon', '', 'Assistant Engineer', '', 'Chairman Secretariat', 'Chairman Secretariat', 'In Service', 'j', 'k', 'Male', 'B+', 'Array', '', '1234567890', '', '01718834655', 'admin@gmail.com', '', '', '', '', '', '', NULL, 'jhaenadah', 'jhaenadah', '../image/cropped-bd-govt-logo.png', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(12, '5620-6', 23, 'tajul', '', 'General Manager', '', 'MTS', 'MTS', 'In Service', 'hh', 'ddd', 'Female', 'B+', 'Islam', '', '1234567890', 'Freedom Fighter', '01718834655', 'admin@gmail.com', 'Dhaka', 'Married', 'X', '', 'Govt. services', '', NULL, 'jhenaidah', 'jhenaidah', '../image/Dir.(Fin.).jpg', '', '2023-04-28 12:07:45', '2023-12-07 05:24:29'),
(13, '5620-20', 7, 'hakimi', '', 'Assistant Engineer', '', 'MTS', 'ICT', 'In Service', 'S', 'F', 'Male', 'B+', 'Islam', '', '2147483647', 'Non Qoutas', '01671583637', 'hakimi@yahoo.com', 'Sirajganj', 'Married', 'E', '', 'Govt. services', '', NULL, 'Sirajganj Sadar', 'Sirajganj Sadar', '1123070997.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(14, '5620-21', 2, 'Ashraj', '', '', '', 'MTS', 'MTS', 'In Service', 'x', 'y', 'Male', 'B+', 'Islam', '', '2147483647', 'Non Qoutas', '01913428714', 'ashraj@hotamil.com', 'Rajbari', 'Married', 'E', '', 'Govt. services', '', NULL, 'Rajbari', 'Rajbari', '378419834.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:22:34'),
(15, '5620-22', 4, 'iqbal', '', 'Deputy Chief Engineer', '', 'MTS', 'MTS', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01913428714', 'iqbal@yahoo.com', '', 'Married', '', '', '', '', NULL, '', '', '730021751.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:22:34'),
(16, '5620-25', 8, 'karim', '', 'DCE', '', 'MTS', 'MTS', 'In Service', 'ujjal', 'fatema', 'Male', 'B+', 'Islam', '', '2147483647', 'Non Qoutas', '01671583637', 'f@yahho.com', 'Potiuakhali', 'Married', 'bb', '', 'g', '', NULL, 'Potiuakhali', 'Potiuakhali', '1307012907.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(17, '5620-27', 48, 'Rifat', '', 'Operation Officer', '', 'ICT Division', 'ICT', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01718834655', 'rifat@yahoo.com', '', 'Married', '', '', '', '', NULL, '', '', '1044417219.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(18, '5620-31', 44, 'Harun', '', 'Programmer', '', '', '', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01718834655', 'f@yahho.com', '', 'Married', '', '', '', '', NULL, '', '', '1343853329.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(19, '5620-32', 9, 'Mofiz', '', 'Additional Chief Engineer', '', 'MTS', 'MTS', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '219309349', 'f@yahho.com', '', 'Married', '', '', '', '', NULL, '', '', '2131420836.png', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(20, '5620-33', 53, 'Gamil', '', 'Departmental Head(Accounts)', '', 'Marketing Division', 'Marketing', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '1234567890', 'tareq@yahoo.com', '', 'Married', '', '', '', '', NULL, '', '', '758189086.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(21, '5620-34', 11, 'Kabita', '', 'Senior General Manager', '', 'BCIC H.O.', 'PF', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01671583637', 'emrantareq07@gmail.com', '', 'Married', '', '', '', '', NULL, '', '', '1256556133.jpg', '', '2023-04-28 12:07:45', '2023-12-13 07:04:27'),
(22, '2765-6', 26, 'Mr. Samir Biswas', '', 'Senior General Manager', '', 'Administration', 'Admin', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01718834655', 'admin@gmail.com', '', 'Married', '', '', '', '', NULL, '', '', '1154341319.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(23, '2847-2', 44, 'Mr. A.B.M.Ferdouse', '', 'Senior General Manager', '', 'BCIC H.O.', 'ICT', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01671583637', 'ferdous@gmail.com', '', 'Married', '', '', '', '', NULL, '', '', '82974121.jpg', '', '2023-04-28 12:07:45', '2023-12-13 07:08:48'),
(24, '3899-2 ', 3, 'Mr. Mohammad Zakir Hossain ', '', 'Chief of Personnel', '', 'Personal Division', 'COP', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01671583637', 'admin@gmail.com', '', 'Married', '', '', '', '', NULL, '', '', '1057511772.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:22:34'),
(25, '3900-8 ', 5, 'Mr. Md.Shahidul Islam', '', 'Managing Director', '', 'GPFPLC', 'Foreign Purchase', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01671583637', 'pur@yahoo.com', '', 'Married', '', '', '', '', NULL, '', '', '1284651117.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(26, '3891-9', 6, 'Mr. Mohammed Salim', '', 'Managing Director', '', 'DLCL', 'DLCL', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01913428714', 'ha@yahoo.com', '', 'Married', '', '', '', '', NULL, '', '', '1414415450.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(27, '3884-4', 5, 'Mr. S.M.Sohel Ahmed', '', 'Controller of Accounts', '', 'Accounts', 'Accounts', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '7945621332', 'admin@gmail.com', '', 'Married', '', '', '', '', NULL, '', '', '1948581751.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(28, ' 3910-7', 5, 'Mr. Md.Monzur Reza', '', 'Managing Director', '', 'Marketing Division', 'Marketing', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01671583637', 'ha@yahoo.com', '', 'Married', '', '', '', '', NULL, '', '', '312334623.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(29, '5620-51', 27, 'shohidul Islam', '', 'Senior General Manager', '', 'Purchase Division', 'Marketing', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01915555540', 'admin@webdamn.com', '', 'Married', '', '', '', '', NULL, '', '', '1344214475.(Fin', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(30, '2601-3', 28, 'Mr.Kazi Rafiqul Islam', '', '', '', 'GPUFP', 'GPUFP', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01557009083', 'admin@webdamn.com', '', 'Married', '', '', '', '', NULL, '', '', '1571280253.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(31, '2629-4', 41, 'Mr.Sudip Mazumder', '', 'GM(MTS)/Chief Engineer(MTS)', '', 'MTS', 'MTS', 'In Service', 'x', 'y', 'Male', 'B+', 'Hindu', '', '2147483647', 'Non Qoutas', '01915555540', 'emrantareq05@gmail.com', 'Magura', 'Married', 'zz', '', 'Housewife', '', NULL, 'Dhaka', 'Magura', '783344712.jpg', '', '2023-04-28 12:07:45', '2023-11-30 09:20:23'),
(32, '3156-7', 31, 'Ms.Bithi Ahmed', '', 'Deputy General Manager', '', 'Chairman Secretariat', 'Chairman Secretariat', 'In Service', '', '', 'Male', 'B+', '', '', '0', 'Freedom Fighter', '01671583637', 'sa.taapp@gmail.com', '', 'Married', '', '', '', '', NULL, '', '', '1640167405.jpg', '', '2023-04-28 20:38:58', '2023-11-30 09:20:23'),
(33, '3746-5', 36, 'Mr.Mohammad Sarwar Jahan', '', 'Deputy General Manager', '', 'Administration', 'General Administration', 'In Service', 'a', 'b', 'Male', 'B+', 'Islam', '', '2147483647', 'Non Qoutas', '01671583637', 'emrantareq@yahoo.com', 'Jessore', 'Married', 'j', '', 'kk', '', NULL, 'jessore', 'jessore', '1707639976.jpg', '', '2023-04-29 11:09:14', '2023-11-30 09:20:23'),
(36, '6594-6', 24, 'Shenwarn ', '', 'Assistant Programmer', '', 'Accounts', '', 'In Service', '', '', '', '', '', '', '', '', '01718834655', 'f@hggg.com', '', '', '', '', '', '', NULL, '', '', '1262697628.jpg', '', NULL, '2023-11-30 09:20:23'),
(37, '6594-5', 25, 'Abul', '', 'Assistant Programmer', '', 'Administration', 'ICT', 'In Service', '', '', '', '', '', '', '', '', '01718834655', 'dir.te@bcic.gov.bd', '', '', '', '', '', '', NULL, '', '', '190438115.jpg', '', NULL, '2023-11-30 09:20:23'),
(38, '6594-7', NULL, 'Kaniz', '', 'Sr. System Analyst', '', 'ICT Division', 'ICT', 'In Service', '', '', '', '', '', '', '', '', '01557009083', 'f@hggg.com', '', '', '', '', '', '', NULL, '', '', '2013150543.png', '', NULL, '2023-11-30 09:22:02'),
(40, '3728-3', 8, 'Karimuneesa', '', 'Deputy General Manager', '', 'Administration', 'COP', 'In Service', '', '', '', '', '', '', '', '', '01671583637', 'admin@gmail.com', '', '', '', '', '', '', NULL, '', '', '570831898.jpg', '', NULL, '2023-11-30 09:20:23'),
(41, '3833-1', NULL, 'Md. Solaiman', '', 'Deputy General Manager', '', 'Administration', 'COP', 'In Service', '', '', '', '', '', '', '', '', '01718834655', '', '', '', '', '', '', '', NULL, '', '', '', '', NULL, '2023-11-30 09:11:04'),
(42, '3698-8', 30, 'Jainal abedin', '', 'Deputy General Manager', '', 'Administration', 'COP', 'In Service', '', '', '', '', '', '', '', '', '01671583637', '', '', '', '', '', '', '', NULL, '', '', '', '', NULL, '2023-11-30 09:20:23'),
(43, '3778-8', 41, 'Shahin', '', 'Deputy General Manager', '', 'Administration', 'COP', 'In Service', '', '', '', '', '', '', '', '', '01671583637', '', '', '', '', '', '', '', NULL, '', '', '', '', NULL, '2023-11-30 09:20:23'),
(44, '3745-7', NULL, 'Baki', '', 'Deputy General Manager', '', 'Administration', 'SFCL', 'In Service', '', '', '', '', '', '', '', '', '01557009083', '', '', '', '', '', '', '', NULL, '', '', '', '', NULL, '2023-11-30 09:11:50'),
(54, '4101-2', NULL, 'Barik', '', 'Assistant Com. Officer', '', 'RPD', 'PF', 'In Service', '', '', '', '', '', '', '', '', '01718834655', '', '', '', '', '', '', '', NULL, '', '', '', '', NULL, '2023-11-30 08:08:41'),
(58, '6916-1', NULL, 'Colonel Towhidul Islam', '', 'Principle', '', 'BCIC', 'BCIC', 'In Service', '', '', '', '', '', '', '', '', '01718834659', 'Jq@gmail.com', '', '', '', '', '', '', NULL, '', '', '1142277039.jpg', '', NULL, '2023-12-24 09:30:43'),
(79, '6917-9', NULL, 'Mery', '', 'Accounts Officer', '', '', '', 'In Service', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '', '', NULL, '2023-12-28 06:10:59'),
(84, '6919-5', NULL, 'Mery', '', 'Accounts Officer', '', '', '', 'In Service', '', '', '', '', '', '', '5645645645', '', '01671583637', 'emrantareq09@gmail.com', '', '', '', '', '', '', NULL, '', '', '', '', NULL, '2024-01-04 06:30:13'),
(85, '6920-3', NULL, 'emon', '', 'Accounts Officer', '', '', '', 'In Service', 'x', 'y', 'Male', 'A+', 'Islam', 'BD', '6653453453', 'Non Qoutas', '01557009083', 'emrantareq09@gmail.com', 'Tangail', 'Unmarried', '', '', '', '', '', 'tangail', 'tangail', '', '', NULL, '2024-01-04 07:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `blood_group`
--

CREATE TABLE `blood_group` (
  `id` int(11) NOT NULL,
  `group_name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `id` int(11) NOT NULL,
  `board` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`id`, `board`) VALUES
(1, 'Barisal'),
(2, 'Chittagong'),
(3, 'Cumilla'),
(4, 'Dhaka'),
(5, 'Dinajpur'),
(6, 'Jessore'),
(7, 'Mymensingh'),
(8, 'Rajshahi'),
(9, 'Sylhet'),
(10, 'Madrasah'),
(11, 'Technical (BTEB)');

-- --------------------------------------------------------

--
-- Table structure for table `cadre`
--

CREATE TABLE `cadre` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `cadre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cadre`
--

INSERT INTO `cadre` (`id`, `parent_id`, `cadre`) VALUES
(1, 0, 'Administrative'),
(2, 0, 'Technical'),
(3, 0, 'Finance'),
(4, 0, 'Commercial'),
(5, 0, 'Senior GM'),
(6, 0, 'General Manager'),
(7, 0, 'Accounts');

-- --------------------------------------------------------

--
-- Table structure for table `childs_info`
--

CREATE TABLE `childs_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(25) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `class` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `childs_info`
--

INSERT INTO `childs_info` (`id`, `user_id`, `emp_id`, `name`, `dob`, `gender`, `institute`, `class`) VALUES
(2, 8, '5620-4', 'emran ', '2023-05-02', 'Male', '', ''),
(3, 8, '5620-4', 'jamal b', '2023-05-01', 'Male', '', ''),
(10, 0, '', 'Harun', '2023-05-24', 'Male', '', ''),
(11, 0, '', 'Jamil', '2023-05-06', 'Male', '', ''),
(12, 0, '5620-4', 'Harun', '2023-05-05', 'Female', '', ''),
(13, 0, '5620-4', 'salim', '2023-05-10', 'Female', '', ''),
(14, 0, '5620-4', 'Jasim', '1987-03-06', 'Male', '', ''),
(15, 0, '5620-1', 'Foysal he f', '2023-10-11', 'Female', 'BIM ', 'Three'),
(18, 0, '5620-1', 'Foysal', '2023-02-28', 'Female', '', ''),
(19, 0, '5620-1', 'Barik', '2020-12-30', 'Female', 'BIM nnn', 'Two'),
(20, 0, '5620-1', 'fsdfs', '2020-05-05', 'Male', 'BCIC College', 'Two');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`) VALUES
(1, 'AF', 'Afghanistan'),
(2, 'AL', 'Albania'),
(3, 'DZ', 'Algeria'),
(4, 'DS', 'American Samoa'),
(5, 'AD', 'Andorra'),
(6, 'AO', 'Angola'),
(7, 'AI', 'Anguilla'),
(8, 'AQ', 'Antarctica'),
(9, 'AG', 'Antigua and Barbuda'),
(10, 'AR', 'Argentina'),
(11, 'AM', 'Armenia'),
(12, 'AW', 'Aruba'),
(13, 'AU', 'Australia'),
(14, 'AT', 'Austria'),
(15, 'AZ', 'Azerbaijan'),
(16, 'BS', 'Bahamas'),
(17, 'BH', 'Bahrain'),
(18, 'BD', 'Bangladesh'),
(19, 'BB', 'Barbados'),
(20, 'BY', 'Belarus'),
(21, 'BE', 'Belgium'),
(22, 'BZ', 'Belize'),
(23, 'BJ', 'Benin'),
(24, 'BM', 'Bermuda'),
(25, 'BT', 'Bhutan'),
(26, 'BO', 'Bolivia'),
(27, 'BA', 'Bosnia and Herzegovina'),
(28, 'BW', 'Botswana'),
(29, 'BV', 'Bouvet Island'),
(30, 'BR', 'Brazil'),
(31, 'IO', 'British Indian Ocean Territory'),
(32, 'BN', 'Brunei Darussalam'),
(33, 'BG', 'Bulgaria'),
(34, 'BF', 'Burkina Faso'),
(35, 'BI', 'Burundi'),
(36, 'KH', 'Cambodia'),
(37, 'CM', 'Cameroon'),
(38, 'CA', 'Canada'),
(39, 'CV', 'Cape Verde'),
(40, 'KY', 'Cayman Islands'),
(41, 'CF', 'Central African Republic'),
(42, 'TD', 'Chad'),
(43, 'CL', 'Chile'),
(44, 'CN', 'China'),
(45, 'CX', 'Christmas Island'),
(46, 'CC', 'Cocos (Keeling) Islands'),
(47, 'CO', 'Colombia'),
(48, 'KM', 'Comoros'),
(49, 'CG', 'Congo'),
(50, 'CK', 'Cook Islands'),
(51, 'CR', 'Costa Rica'),
(52, 'HR', 'Croatia (Hrvatska)'),
(53, 'CU', 'Cuba'),
(54, 'CY', 'Cyprus'),
(55, 'CZ', 'Czech Republic'),
(56, 'DK', 'Denmark'),
(57, 'DJ', 'Djibouti'),
(58, 'DM', 'Dominica'),
(59, 'DO', 'Dominican Republic'),
(60, 'TP', 'East Timor'),
(61, 'EC', 'Ecuador'),
(62, 'EG', 'Egypt'),
(63, 'SV', 'El Salvador'),
(64, 'GQ', 'Equatorial Guinea'),
(65, 'ER', 'Eritrea'),
(66, 'EE', 'Estonia'),
(67, 'ET', 'Ethiopia'),
(68, 'FK', 'Falkland Islands (Malvinas)'),
(69, 'FO', 'Faroe Islands'),
(70, 'FJ', 'Fiji'),
(71, 'FI', 'Finland'),
(72, 'FR', 'France'),
(73, 'FX', 'France, Metropolitan'),
(74, 'GF', 'French Guiana'),
(75, 'PF', 'French Polynesia'),
(76, 'TF', 'French Southern Territories'),
(77, 'GA', 'Gabon'),
(78, 'GM', 'Gambia'),
(79, 'GE', 'Georgia'),
(80, 'DE', 'Germany'),
(81, 'GH', 'Ghana'),
(82, 'GI', 'Gibraltar'),
(83, 'GK', 'Guernsey'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'IM', 'Isle of Man'),
(101, 'ID', 'Indonesia'),
(102, 'IR', 'Iran (Islamic Republic of)'),
(103, 'IQ', 'Iraq'),
(104, 'IE', 'Ireland'),
(105, 'IL', 'Israel'),
(106, 'IT', 'Italy'),
(107, 'CI', 'Ivory Coast'),
(108, 'JE', 'Jersey'),
(109, 'JM', 'Jamaica'),
(110, 'JP', 'Japan'),
(111, 'JO', 'Jordan'),
(112, 'KZ', 'Kazakhstan'),
(113, 'KE', 'Kenya'),
(114, 'KI', 'Kiribati'),
(115, 'KP', 'Korea, Democratic People\'s Republic of'),
(116, 'KR', 'Korea, Republic of'),
(117, 'XK', 'Kosovo'),
(118, 'KW', 'Kuwait'),
(119, 'KG', 'Kyrgyzstan'),
(120, 'LA', 'Lao People\'s Democratic Republic'),
(121, 'LV', 'Latvia'),
(122, 'LB', 'Lebanon'),
(123, 'LS', 'Lesotho'),
(124, 'LR', 'Liberia'),
(125, 'LY', 'Libyan Arab Jamahiriya'),
(126, 'LI', 'Liechtenstein'),
(127, 'LT', 'Lithuania'),
(128, 'LU', 'Luxembourg'),
(129, 'MO', 'Macau'),
(130, 'MK', 'Macedonia'),
(131, 'MG', 'Madagascar'),
(132, 'MW', 'Malawi'),
(133, 'MY', 'Malaysia'),
(134, 'MV', 'Maldives'),
(135, 'ML', 'Mali'),
(136, 'MT', 'Malta'),
(137, 'MH', 'Marshall Islands'),
(138, 'MQ', 'Martinique'),
(139, 'MR', 'Mauritania'),
(140, 'MU', 'Mauritius'),
(141, 'TY', 'Mayotte'),
(142, 'MX', 'Mexico'),
(143, 'FM', 'Micronesia, Federated States of'),
(144, 'MD', 'Moldova, Republic of'),
(145, 'MC', 'Monaco'),
(146, 'MN', 'Mongolia'),
(147, 'ME', 'Montenegro'),
(148, 'MS', 'Montserrat'),
(149, 'MA', 'Morocco'),
(150, 'MZ', 'Mozambique'),
(151, 'MM', 'Myanmar'),
(152, 'NA', 'Namibia'),
(153, 'NR', 'Nauru'),
(154, 'NP', 'Nepal'),
(155, 'NL', 'Netherlands'),
(156, 'AN', 'Netherlands Antilles'),
(157, 'NC', 'New Caledonia'),
(158, 'NZ', 'New Zealand'),
(159, 'NI', 'Nicaragua'),
(160, 'NE', 'Niger'),
(161, 'NG', 'Nigeria'),
(162, 'NU', 'Niue'),
(163, 'NF', 'Norfolk Island'),
(164, 'MP', 'Northern Mariana Islands'),
(165, 'NO', 'Norway'),
(166, 'OM', 'Oman'),
(167, 'PK', 'Pakistan'),
(168, 'PW', 'Palau'),
(169, 'PS', 'Palestine'),
(170, 'PA', 'Panama'),
(171, 'PG', 'Papua New Guinea'),
(172, 'PY', 'Paraguay'),
(173, 'PE', 'Peru'),
(174, 'PH', 'Philippines'),
(175, 'PN', 'Pitcairn'),
(176, 'PL', 'Poland'),
(177, 'PT', 'Portugal'),
(178, 'PR', 'Puerto Rico'),
(179, 'QA', 'Qatar'),
(180, 'RE', 'Reunion'),
(181, 'RO', 'Romania'),
(182, 'RU', 'Russian Federation'),
(183, 'RW', 'Rwanda'),
(184, 'KN', 'Saint Kitts and Nevis'),
(185, 'LC', 'Saint Lucia'),
(186, 'VC', 'Saint Vincent and the Grenadines'),
(187, 'WS', 'Samoa'),
(188, 'SM', 'San Marino'),
(189, 'ST', 'Sao Tome and Principe'),
(190, 'SA', 'Saudi Arabia'),
(191, 'SN', 'Senegal'),
(192, 'RS', 'Serbia'),
(193, 'SC', 'Seychelles'),
(194, 'SL', 'Sierra Leone'),
(195, 'SG', 'Singapore'),
(196, 'SK', 'Slovakia'),
(197, 'SI', 'Slovenia'),
(198, 'SB', 'Solomon Islands'),
(199, 'SO', 'Somalia'),
(200, 'ZA', 'South Africa'),
(201, 'GS', 'South Georgia South Sandwich Islands'),
(202, 'ES', 'Spain'),
(203, 'LK', 'Sri Lanka'),
(204, 'SH', 'St. Helena'),
(205, 'PM', 'St. Pierre and Miquelon'),
(206, 'SD', 'Sudan'),
(207, 'SR', 'Suriname'),
(208, 'SJ', 'Svalbard and Jan Mayen Islands'),
(209, 'SZ', 'Swaziland'),
(210, 'SE', 'Sweden'),
(211, 'CH', 'Switzerland'),
(212, 'SY', 'Syrian Arab Republic'),
(213, 'TW', 'Taiwan'),
(214, 'TJ', 'Tajikistan'),
(215, 'TZ', 'Tanzania, United Republic of'),
(216, 'TH', 'Thailand'),
(217, 'TG', 'Togo'),
(218, 'TK', 'Tokelau'),
(219, 'TO', 'Tonga'),
(220, 'TT', 'Trinidad and Tobago'),
(221, 'TN', 'Tunisia'),
(222, 'TR', 'Turkey'),
(223, 'TM', 'Turkmenistan'),
(224, 'TC', 'Turks and Caicos Islands'),
(225, 'TV', 'Tuvalu'),
(226, 'UG', 'Uganda'),
(227, 'UA', 'Ukraine'),
(228, 'AE', 'United Arab Emirates'),
(229, 'GB', 'United Kingdom'),
(230, 'US', 'United States'),
(231, 'UM', 'United States minor outlying islands'),
(232, 'UY', 'Uruguay'),
(233, 'UZ', 'Uzbekistan'),
(234, 'VU', 'Vanuatu'),
(235, 'VA', 'Vatican City State'),
(236, 'VE', 'Venezuela'),
(237, 'VN', 'Vietnam'),
(238, 'VG', 'Virgin Islands (British)'),
(239, 'VI', 'Virgin Islands (U.S.)'),
(240, 'WF', 'Wallis and Futuna Islands'),
(241, 'EH', 'Western Sahara'),
(242, 'YE', 'Yemen'),
(243, 'ZR', 'Zaire'),
(244, 'ZM', 'Zambia'),
(245, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(1, 'Administration'),
(2, 'Accounts'),
(3, 'Commercial'),
(4, 'Technical'),
(5, 'MTS/EIP'),
(6, 'MTS/Mech'),
(7, 'Operation');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `designation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `designation`) VALUES
(1, 'Assistant Engineer'),
(2, 'Executive Engineer'),
(3, 'Deputy Chief Engineer'),
(4, 'Additional Chief Engineer'),
(5, 'General Manager'),
(6, 'Departmental Head'),
(7, 'Divisional Head'),
(8, 'Assistant Chemist'),
(9, 'Chemist'),
(10, 'Sr. System Analyst'),
(11, 'Deputy Manager'),
(12, 'Manager'),
(13, 'Deputy General Manager'),
(14, 'Addl. Chief Accountant'),
(15, 'Assistant Programmer'),
(16, 'Programmer'),
(17, 'Chairman (Grade-1)'),
(18, 'Director'),
(19, 'Addl. Chief Chemist'),
(20, 'Addl. Chief Manager'),
(21, 'Accounts Officer'),
(22, 'GM(MTS)/Chief Engineer(MTS)'),
(23, 'Assistant Accounts Officer'),
(24, 'Assistant Admin Officer'),
(25, 'Assistant Com. Officer'),
(26, 'Assistant Manager'),
(27, 'GM(MTS)/Chief Engineer'),
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
(38, 'Deputy General Manager'),
(39, 'Medical officer'),
(40, 'Senior Medical Officer'),
(41, 'Chief Medical Officer'),
(42, 'Chief Finance Officer'),
(43, 'Chief Auditor'),
(44, 'Project Director'),
(45, 'Addl. Chief Medical Officer'),
(46, 'D.C.O.P'),
(47, 'Principle'),
(48, 'Dy. Chief Medical Officer'),
(49, 'Dy. Chief Auditors'),
(50, 'D.C.F.O'),
(51, 'A.C.O.P'),
(52, 'Assistant Professor'),
(53, 'Senior Librarian'),
(54, 'Head Master'),
(55, 'Senior Technical Officer'),
(56, 'Asstt. Chief Accountant'),
(57, 'A.C.F.O'),
(58, 'Asstt. Chief Auditor');

-- --------------------------------------------------------

--
-- Table structure for table `diploma_course_info`
--

CREATE TABLE `diploma_course_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `type` varchar(25) NOT NULL,
  `title` varchar(255) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `grade` varchar(15) NOT NULL,
  `country` varchar(25) NOT NULL,
  `period` varchar(15) NOT NULL,
  `year` int(15) NOT NULL,
  `month_year` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `diploma_course_info`
--

INSERT INTO `diploma_course_info` (`id`, `user_id`, `emp_id`, `type`, `title`, `institute`, `grade`, `country`, `period`, `year`, `month_year`, `created_at`, `updated`) VALUES
(1, 0, '5620-4', 'Local', 'Test 33', 'Honywell h', '5th', 'India', '42 Days', 2020, 'December, 2021', '2023-06-18 00:00:00', '2023-08-02 23:39:08'),
(2, 0, '5620-4', 'Foreign', 'Urea Process ', 'Honywell', '', 'India', '2 weeks', 2020, 'July 2020', '2023-06-22 00:00:00', '2023-06-22 11:45:35'),
(3, 0, '5620-4', 'Local Training', 'PPR', 'NAPD', '5th', 'BD', '42 Days', 2020, 'January 2023', '2023-08-03 00:00:00', '2023-08-03 03:38:04'),
(4, 0, '5620-1', '', 'ddgg', 'BIM nnn', '5th', 'BDbd', '42 days 6', 2020, 'July 2019', '2023-10-04 00:00:00', '2023-12-13 23:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `disciplinary_action`
--

CREATE TABLE `disciplinary_action` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `nat_of_offense` varchar(255) NOT NULL,
  `punishment` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `remarks` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `disciplinary_action`
--

INSERT INTO `disciplinary_action` (`id`, `user_id`, `emp_id`, `nat_of_offense`, `punishment`, `date`, `remarks`) VALUES
(1, 8, '5620-4', 'test', 'Increment Held-up', '2023-06-01', ''),
(2, 8, '5620-4', 'Punishment Transfer', 'Punishment Transfer', '2023-06-22', '');

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `id` int(11) NOT NULL,
  `district_name` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`id`, `district_name`) VALUES
(1, 'Dhaka'),
(2, 'Faridpur'),
(3, 'Gazipur'),
(4, 'Gopalganj'),
(5, 'Jamalpur'),
(6, 'Kishoreganj'),
(7, 'Madaripur'),
(8, 'Manikganj'),
(9, 'Munshiganj'),
(10, 'Mymensingh'),
(11, 'Narayanganj'),
(12, 'Narsingdi'),
(13, 'Netrokona'),
(14, 'Rajbari'),
(15, 'Shariatpur'),
(16, 'Sherpur'),
(17, 'Tangail'),
(18, 'Bogra'),
(19, 'Joypurhat'),
(20, 'Naogaon'),
(21, 'Natore'),
(22, 'Nawabganj'),
(23, 'Pabna'),
(24, 'Rajshahi'),
(25, 'Sirajgonj'),
(26, 'Dinajpur'),
(27, 'Gaibandha'),
(28, 'Kurigram'),
(29, 'Lalmonirhat'),
(30, 'Nilphamari'),
(31, 'Panchagarh'),
(32, 'Rangpur'),
(33, 'Thakurgaon'),
(34, 'Barguna'),
(35, 'Barisal'),
(36, 'Bhola'),
(37, 'Jhalokati'),
(38, 'Patuakhali'),
(39, 'Pirojpur'),
(40, 'Bandarban'),
(41, 'Brahmanbaria'),
(42, 'Chandpur'),
(43, 'Chittagong'),
(44, 'Comilla'),
(45, 'CoxsBazar'),
(46, 'Feni'),
(47, 'Khagrachari'),
(48, 'Lakshmipur'),
(49, 'Noakhali'),
(50, 'Rangamati'),
(51, 'Habiganj'),
(52, 'Maulvibazar'),
(53, 'Sunamganj'),
(54, 'Sylhet'),
(55, 'Bagerhat'),
(56, 'Chuadanga'),
(57, 'Jessore'),
(58, 'Jhenaidah'),
(59, 'Khulna'),
(60, 'Kushtia'),
(61, 'Magura'),
(62, 'Meherpur'),
(63, 'Narail'),
(64, 'Satkhira');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `division` varchar(25) NOT NULL
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
(63, 'Chemical Godown, Shampur');

-- --------------------------------------------------------

--
-- Table structure for table `edu_info`
--

CREATE TABLE `edu_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `ssc_exam` varchar(25) NOT NULL,
  `ssc_group_name` varchar(25) NOT NULL,
  `ssc_inst_name` varchar(200) NOT NULL,
  `ssc_board_or_univ` varchar(50) NOT NULL,
  `ssc_div_or_cgpa` varchar(25) NOT NULL,
  `ssc_cgpa_5` varchar(25) NOT NULL,
  `ssc_passing_year` varchar(25) NOT NULL,
  `hsc_exam` varchar(25) NOT NULL,
  `hsc_group_name` varchar(25) NOT NULL,
  `hsc_inst_name` varchar(200) NOT NULL,
  `hsc_board_or_univ` varchar(25) NOT NULL,
  `hsc_div_or_cgpa` varchar(25) NOT NULL,
  `hsc_cgpa_5` varchar(25) NOT NULL,
  `hsc_passing_year` varchar(25) NOT NULL,
  `honors_exam` varchar(25) NOT NULL,
  `honors_group_name` varchar(25) NOT NULL,
  `honors_groupname_others` varchar(50) NOT NULL,
  `honors_inst_name` varchar(200) NOT NULL,
  `honors_univer_others` varchar(250) NOT NULL,
  `honors_board_or_univ` varchar(250) NOT NULL,
  `honors_board_others` varchar(250) NOT NULL,
  `honors_div_or_cgpa` varchar(25) NOT NULL,
  `honors_cgpa_4` varchar(25) NOT NULL,
  `honors_passing_year` varchar(25) NOT NULL,
  `honors_course_duration` varchar(25) NOT NULL,
  `masters` varchar(25) NOT NULL,
  `ms_group_name` varchar(25) NOT NULL,
  `ms_groupname_others` varchar(200) NOT NULL,
  `ms_inst_name` varchar(100) NOT NULL,
  `ms_univer_others` varchar(250) NOT NULL,
  `ms_board_or_univ` varchar(250) NOT NULL,
  `ms_board_others` varchar(250) NOT NULL,
  `ms_div_or_cgpa` varchar(25) NOT NULL,
  `ms_cgpa_4` varchar(25) NOT NULL,
  `ms_passing_year` varchar(25) NOT NULL,
  `ms_course_duration` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `edu_info`
--

INSERT INTO `edu_info` (`id`, `user_id`, `emp_id`, `ssc_exam`, `ssc_group_name`, `ssc_inst_name`, `ssc_board_or_univ`, `ssc_div_or_cgpa`, `ssc_cgpa_5`, `ssc_passing_year`, `hsc_exam`, `hsc_group_name`, `hsc_inst_name`, `hsc_board_or_univ`, `hsc_div_or_cgpa`, `hsc_cgpa_5`, `hsc_passing_year`, `honors_exam`, `honors_group_name`, `honors_groupname_others`, `honors_inst_name`, `honors_univer_others`, `honors_board_or_univ`, `honors_board_others`, `honors_div_or_cgpa`, `honors_cgpa_4`, `honors_passing_year`, `honors_course_duration`, `masters`, `ms_group_name`, `ms_groupname_others`, `ms_inst_name`, `ms_univer_others`, `ms_board_or_univ`, `ms_board_others`, `ms_div_or_cgpa`, `ms_cgpa_4`, `ms_passing_year`, `ms_course_duration`) VALUES
(1, 6, '5620-1', 'S.S.C', 'Science', 'Naldanga Bhusan Pilot secondary high School', 'Jessore', 'CGPA (Out of 5)', '5.00', '2003', 'H.S.C', 'Science', 'Ramnagar Secnodary High School 12', 'Dhaka', '1st Class', '', '2007', 'B.Sc Engineering', 'Chemical Engineering', '', 'Bangladesh University of Engineering & Technology', '', 'Bangladesh University of Engineering & Technology', '', '1st Class', '', '2013', '04 Years', '', '', '', '', '', '', '', '', '', '', ''),
(2, 8, '5620-4', 'S.S.C', 'Science', 'Ramnagar Secnodary High School', 'Dhaka', 'Passed', '', '1986', 'Business Management', 'Business Studies', 'Ramnagar Secnodary High School 12', 'Jessore', 'CGPA (Out of 5)', '4.99', '1997', 'B.Sc Engineering', 'Chemical Engineering', '', 'Notre Dame University Bangladesh', '', 'Bangladesh Army University of Science and Technology(BAUST), Saidpur', '', 'CGPA (Out of 4)', '3.75', '2001', '04 Years', 'M.Sc', 'Architecture', '', 'Bangladesh Army University of Engineering and Technology (BAUET), Qadirabad', '', 'BGMEA University of Fashion & Technology(BUFT) ', '', 'CGPA (Out of 4)', '3.25', '2002', '01 Years'),
(3, 29, '5620-51', '2', '1', 'Ramnagar Secnodary High School', 'Dhaka', '1st Class', '', '1995', 'H.S.C', 'Science', 'Ramnagar Secnodary High School 1', 'Dhaka', '1st Class', '', '1997', 'Honors', 'ME', '', 'DU', '', 'DU', '', '1st Class', '', '', '04 Years', 'MBA', 'Accounts', '', 'DU', '', '', '', '1st Class', '', '2003', '01 Years'),
(4, 21, '5620-34', 'S.S.C', 'Science', 'Ramnagar Secnodary High School', 'Dhaka', 'CGPA (Out of 5)', '5.00', '1995', 'H.S.C', 'Science', 'Ramnagar Secnodary High School 1', 'Dhaka', 'CGPA (Out of 5)', '5.00', '1997', 'B.Sc Engineering', 'Electrical & Electronics ', '', 'Khulna University of Engineering & Technology', '', 'Khulna University of Engineering & Technology', '', 'CGPA (Out of 4)', '4.00', '2001', '04 Years', 'M.Sc', 'Electrical & Electronics ', '', 'Khulna University of Engineering & Technology', '', 'Khulna University of Engineering & Technology', '', 'CGPA (Out of 4)', '4.00', '2003', '01 Years'),
(5, 19, '5620-32', 'S.S.C', '', 'Ramnagar Secnodary High School', 'Dhaka', 'CGPA (Out of 5)', '5', '1995', 'H.S.C', 'Science', 'Ramnagar Secnodary High School', 'Dhaka', 'CGPA (Out of 5)', '5', '1997', 'Honors', 'Accounts', '', 'DU', '', 'DU', '', 'CGPA (Out of 4)', '5', '2001', '04 Years', 'MBA', 'Accounts', '', '', '', 'DU', '', 'CGPA (Out of 4)', '4', '2003', '01 Years'),
(104, 34, '7733-0', 'S.S.C', 'General', 'Ramnagar Secnodary High School', 'Dhaka', '2nd Class', '', '1984', 'Alim', 'General', 'Ramnagar Secnodary High School 1', 'Barisal', '2nd Class', '', '1985', 'Honors', 'Agriculture', '', 'Hajee Mohammad Danesh Science & Technology University', '', 'Patuakhali Science And Technology University', '', '2nd Class', '', '1983', '03 Years', 'MA', 'Anthropology', '', 'Bangladesh Agricultural University', '', 'Mawlana Bhashani Science & Technology University', '', '2nd Class', '', '1983', '02 Years'),
(106, 35, '7720-9', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `emp_bank_info`
--

CREATE TABLE `emp_bank_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `bank_name` varchar(25) NOT NULL,
  `branch_name` varchar(25) NOT NULL,
  `branch_add` varchar(25) NOT NULL,
  `acc_name` varchar(25) NOT NULL,
  `acc_no` varchar(25) NOT NULL,
  `swift_code` varchar(15) NOT NULL,
  `routing_no` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emp_bank_info`
--

INSERT INTO `emp_bank_info` (`id`, `user_id`, `emp_id`, `bank_name`, `branch_name`, `branch_add`, `acc_name`, `acc_no`, `swift_code`, `routing_no`, `created_at`, `updated`) VALUES
(1, 8, '5620-4', 'Sonali Bank Ltd.', 'Sonali Bank Ltd, Dilkusha', 'Dilkusha C/A, Motijheel, ', 'Md. Tareq Emran', '956788767', '85678595', '15958', '2023-06-17 00:00:00', '2023-08-09 02:56:42'),
(2, 6, '5620-1', 'Sonali Bank Ltd.', 'Sonali Bank Ltd, Dilkusha', 'Dilkusha C/A, Motijheel, ', 'Sunil Chandra Das', '123456789', '4563', '12367899', '2023-09-13 00:00:00', '2023-09-13 12:06:20'),
(3, 23, '2847-2', 'Janata Bank Ltd.', 'Janata Bank Ltd, ', 'Dilkusha Branch', 'Abm ferdous', '678997656778', '85678595', '15958', '2023-09-27 00:00:00', '2023-09-27 17:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`id`, `name`) VALUES
(1, 'Select'),
(2, 'S.S.C'),
(3, 'S.S.C Vocational'),
(4, 'O Level/Cambridge'),
(5, 'S.S.C Equivalent'),
(6, 'Dakhil Vocational'),
(7, 'H.S.C'),
(8, 'Alim'),
(9, 'Business Management'),
(10, 'Diploma-in-Engineering'),
(11, 'A Level/Sr. Cambridge'),
(12, 'H.S.C or Equivalent'),
(13, 'Diploma in Medical Technology'),
(14, 'H.S.C Vovational'),
(15, 'Alim Vocational'),
(16, 'Honors'),
(17, 'B.B.A'),
(18, 'B.Sc Engineering'),
(19, 'Fazil'),
(20, 'M.B.B.S/ D.B.S'),
(21, 'B.Sc in Agriculture Science'),
(22, 'Graduation Equivalent'),
(23, 'Pass Course'),
(24, 'L.L.M'),
(25, 'MA'),
(26, 'MBA'),
(27, 'M.Com'),
(28, 'M.S.S'),
(29, 'M.Sc'),
(30, 'Masters Equivalent'),
(31, 'Pass Course'),
(32, 'Dakhil'),
(33, 'Eight Pass');

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `id` int(11) NOT NULL,
  `examination` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `examination`
--

INSERT INTO `examination` (`id`, `examination`) VALUES
(1, 'S.S.C'),
(2, 'H.S.C or Equivalent'),
(3, 'Honors'),
(4, 'B.B.A'),
(5, 'B.Sc Engineering'),
(6, 'Masters'),
(7, 'Ph.D'),
(8, 'M.B.B.S/D.B.S');

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_year`
--

CREATE TABLE `fiscal_year` (
  `id` int(11) NOT NULL,
  `fiscal_year` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `id` int(11) NOT NULL,
  `grade` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`id`, `grade`) VALUES
(1, '1st'),
(2, '2nd'),
(3, '3rd'),
(4, '4th'),
(5, '5th'),
(6, '6th'),
(7, '7th'),
(8, '8th'),
(9, '9th'),
(10, '10th'),
(11, '11th'),
(12, '12th'),
(13, '13th'),
(14, '14th'),
(15, '15th'),
(16, '16th'),
(17, '17th'),
(18, '18th'),
(19, '19th'),
(20, '20th');

-- --------------------------------------------------------

--
-- Table structure for table `img`
--

CREATE TABLE `img` (
  `id` int(11) NOT NULL,
  `path` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `incre_info`
--

CREATE TABLE `incre_info` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `date_of_incre` date NOT NULL,
  `pres_desig` varchar(25) NOT NULL,
  `pres_grade` varchar(15) NOT NULL,
  `new_desig` varchar(25) NOT NULL,
  `new_grade` varchar(15) NOT NULL,
  `place_of_post` varchar(25) NOT NULL,
  `pay_scale` varchar(15) NOT NULL,
  `pres_basic_pay` varchar(15) NOT NULL,
  `incr_granted` varchar(15) NOT NULL,
  `basic_pay_after_incr` varchar(15) NOT NULL,
  `next_incr_date` date NOT NULL,
  `issue_incr_letter` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `job_desc`
--

CREATE TABLE `job_desc` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `doj` date NOT NULL,
  `police_verification` enum('Complete','Incomplete','Others','') NOT NULL,
  `doc` date NOT NULL,
  `prl` date NOT NULL,
  `pension_start_date` date DEFAULT NULL,
  `place_of_posting` varchar(100) NOT NULL,
  `d_nothi_id` int(12) NOT NULL,
  `tin_no` int(12) NOT NULL,
  `join_designation` varchar(25) NOT NULL,
  `cadre` varchar(25) NOT NULL,
  `sub_cadre` varchar(25) NOT NULL,
  `sub_cadre_header_ext` varchar(50) NOT NULL,
  `appoint_type` varchar(100) NOT NULL,
  `others` varchar(100) NOT NULL,
  `last_promo_date` date NOT NULL,
  `next_promo_date` date NOT NULL,
  `granted_promo_date` date NOT NULL,
  `nature_of_promo` varchar(100) NOT NULL,
  `last_incr_date` date NOT NULL,
  `next_incr_date` date NOT NULL,
  `grade` varchar(25) NOT NULL,
  `basic` varchar(100) NOT NULL,
  `incr_granted` varchar(100) NOT NULL,
  `basic_after_incr` varchar(100) NOT NULL,
  `scale` varchar(50) NOT NULL,
  `job_status` enum('In Service','PRL','Suspended','Retired','Dead with Services','LPR','Resignation','Deputation') NOT NULL,
  `deputation_org` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `job_desc`
--

INSERT INTO `job_desc` (`id`, `user_id`, `emp_id`, `dob`, `doj`, `police_verification`, `doc`, `prl`, `pension_start_date`, `place_of_posting`, `d_nothi_id`, `tin_no`, `join_designation`, `cadre`, `sub_cadre`, `sub_cadre_header_ext`, `appoint_type`, `others`, `last_promo_date`, `next_promo_date`, `granted_promo_date`, `nature_of_promo`, `last_incr_date`, `next_incr_date`, `grade`, `basic`, `incr_granted`, `basic_after_incr`, `scale`, `job_status`, `deputation_org`, `created_at`, `last_update_at`) VALUES
(1, 6, '5620-1', '1987-02-06', '2023-10-10', 'Complete', '0000-00-00', '2046-02-05', '2047-02-05', 'AFCCL', 456345, 2147483647, 'Assistant Engineer', 'Technical', 'Operation', 'Chemical', 'Regular', '', '2021-11-07', '2026-11-07', '2021-11-07', 'Regular', '2023-07-01', '2024-07-01', '8th', '50300', '0', '50300', '23000-55460', 'In Service', '', '2023-10-09 10:58:38', '2024-01-28 00:26:53'),
(2, 8, '5620-4', '1987-02-06', '2016-04-25', '', '0000-00-00', '2046-02-05', '2047-02-05', '', 0, 0, 'Assistant Programmer', 'Finance', 'Computer', '', 'Probationary', '', '2021-11-07', '2026-11-07', '0000-00-00', 'Selection Grade', '2022-07-01', '2023-07-01', '9th', '29000', '1450', '30450', '29000-63410', 'In Service', '', '2023-10-09 10:58:38', '2024-01-14 13:33:42'),
(3, 10, '5620-12', '1970-02-06', '1990-04-20', '', '0000-00-00', '2029-02-05', NULL, 'BISFL', 0, 0, 'Assitant Engineer', 'Senior GM', 'Senior GM', '', 'Regular', '', '2023-01-19', '2028-01-19', '0000-00-00', '', '2023-01-03', '2024-01-03', '7th', '60390', '3020', '63410', '43000-69850', 'PRL', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(4, 12, '5620-6', '1976-06-20', '1998-08-04', '', '0000-00-00', '2035-06-19', NULL, 'GPFPLC', 0, 0, 'Assitant Engineer', 'Technical', 'Engineer(EEE,ME,Inst)', '', 'Regular', '', '2023-01-09', '2028-01-09', '0000-00-00', '', '2023-01-10', '2024-01-10', '2nd', '76320', '3640', '75410', '50000-71200', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(6, 9, '5620-11', '2023-02-02', '2023-02-14', '', '0000-00-00', '2082-02-01', NULL, 'AFCCL', 0, 0, 'Assistant Engineer', 'Finance', 'Computer', '', 'Regular', '', '0000-00-00', '2028-07-30', '0000-00-00', '', '0000-00-00', '2024-07-30', '7th', '69910', '3330', '69910', '35500-67010', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(7, 29, '5620-51', '1987-02-06', '2016-04-25', '', '0000-00-00', '2046-02-05', NULL, 'SFCL', 0, 0, 'Assistant Programmer', 'Senior GM', 'Senior GM', '', 'Regular', '', '2023-03-01', '2028-03-01', '0000-00-00', '', '2023-03-03', '2024-03-03', '4th', '68460', '2740', '71200', '78000', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(8, 31, '2629-4', '1964-12-03', '1988-09-20', '', '0000-00-00', '2023-12-02', NULL, 'BCIC H.O', 0, 0, 'Assitant Engineer', 'General Manager', 'General Manager', '', 'Regular', '', '2021-10-07', '2026-10-07', '0000-00-00', '', '2022-07-01', '2023-07-01', '4th', '68460', '2740', '71200', '66000-76490', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(10, 30, '2601-3', '1965-12-03', '1988-12-20', '', '0000-00-00', '2024-12-02', NULL, 'GPUFP', 0, 0, 'Assitant Engineer', 'General Manager', 'General Manager', '', 'Regular', '', '2021-10-07', '2026-10-07', '0000-00-00', '', '2022-07-01', '2023-07-01', '4th', '68460', '2740', '71200', '66000-76490', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(11, 14, '5620-21', '1987-02-06', '2010-01-11', '', '0000-00-00', '2046-02-05', '2047-02-05', 'SFCL', 0, 0, 'Assistant Programmer', 'Finance', 'Computer', '', 'Regular', '', '2021-10-07', '2026-10-07', '0000-00-00', '', '2022-07-01', '2023-07-01', '6th', '63810', '3190', '67000', '35500-67010', 'In Service', '', '2023-10-09 10:58:38', '2024-01-11 12:56:07'),
(12, 11, '5620-13', '1987-02-06', '2016-04-25', '', '0000-00-00', '2046-02-05', NULL, 'SFCL', 0, 0, 'Assistant Engineer', 'Technical', 'Engineer', '', 'Regular', '', '2021-10-07', '2026-10-07', '0000-00-00', '', '2022-07-01', '2023-07-01', '6th', '55110', '2760', '57870', '35500-67010', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(13, 17, '5620-27', '1990-10-10', '1999-10-12', '', '0000-00-00', '2049-10-09', NULL, 'GPFPLC', 0, 0, 'Assitant Engineer', 'Technical', 'Engineer(EEE,ME,Inst)', '', 'Regular', '', '2010-10-21', '2015-10-21', '0000-00-00', '', '2022-07-01', '2023-07-01', '9th', '34170', '1710', '35880', '43000-69850', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(14, 20, '5620-33', '1990-10-10', '1992-12-12', '', '0000-00-00', '2049-10-09', NULL, 'GPFPLC', 0, 0, 'Deputy Chief Engineer', 'Technical', 'Engineer(EEE,ME,Inst)', '', 'Regular', '', '2021-07-10', '2026-07-10', '0000-00-00', '', '2022-07-01', '2023-07-01', '9th', '34170', '1710', '35880', '35500-67010', 'In Service', '', '2023-10-09 10:58:38', '2023-06-05 00:00:00'),
(15, 8, '7733-0', '1987-02-06', '2016-04-25', 'Complete', '2017-04-25', '2046-02-05', NULL, 'BCIC H.O', 0, 0, 'Assistant Programmer', 'Finance', 'Computer', '', 'Regular', '', '0000-00-00', '2028-08-15', '0000-00-00', 'Regular', '0000-00-00', '2024-08-15', '6th', '55110', '2760', '57870', '35500-67010', 'In Service', '', '2023-10-09 10:58:38', '2023-08-15 00:00:00'),
(17, 35, '7720-9', '1990-11-10', '2010-11-11', 'Complete', '2011-10-10', '2049-11-09', NULL, 'BCIC H.O', 0, 0, 'Assitant Engineer', 'Technical', 'Engineer(EEE,ME,Inst)', '', 'Regular', '', '0000-00-00', '2028-08-30', '0000-00-00', 'Regular', '0000-00-00', '2024-08-30', '6th', '55110', '2760', '57870', '35500-67010', 'In Service', '', '2023-10-09 10:58:38', '2023-08-30 00:00:00'),
(20, 36, '6594-6', '1997-10-10', '2023-03-19', 'Incomplete', '0000-00-00', '2056-10-09', NULL, 'BCIC H.O', 0, 0, 'Assistant Programmer', 'Finance', 'Computer', '', 'Regular', '', '2023-10-07', '2028-10-07', '0000-00-00', 'Regular', '0000-00-00', '0000-00-00', '9th', '34310', '1720', '36030', '35500-67010', 'In Service', '', '2023-10-09 10:58:38', '2023-09-13 00:00:00'),
(21, 23, '2847-2', '1990-02-11', '2000-11-11', 'Complete', '2000-11-11', '2049-02-10', '2050-02-10', 'BCIC H.O.', 34634, 2147483647, 'Assistant Engineer', 'Finance', 'Computer', '', 'Regular', '', '0000-00-00', '2028-12-13', '0000-00-00', 'Regular', '0000-00-00', '2024-12-13', '2nd', '73320', '2750', '76070', '66000-76490', 'In Service', '', '2023-10-09 10:58:38', '2023-09-28 00:00:00'),
(22, 25, '3900-8', '1987-02-06', '2020-10-11', 'Complete', '0000-00-00', '2046-02-05', NULL, 'SFCL', 645646, 2147483647, 'Assistant Engineer', 'Technical', 'Engineer(EEE,ME,Inst)', '', 'Regular', '', '0000-00-00', '2028-11-13', '0000-00-00', 'Regular', '0000-00-00', '2024-11-13', '9th', '34170', '1710', '35880', '9', 'In Service', '', '2023-10-09 10:58:38', '2023-10-05 00:00:00'),
(25, 24, '3899-2', '1990-02-22', '0000-00-00', 'Complete', '0000-00-00', '2049-02-21', NULL, 'BCIC H.O', 645464, 2147483647, 'Assistant Engineer', 'Administrative', 'Admin', '', 'Regular', '', '0000-00-00', '2028-10-05', '0000-00-00', 'Regular', '2023-01-07', '2024-01-07', '5th', '69070', '3110', '72180', '50000-71200', 'In Service', '', '2023-10-09 10:58:38', '2023-10-05 00:00:00'),
(31, 26, '3891-9', '1987-02-06', '2016-04-25', 'Complete', '0000-00-00', '2046-02-05', '2047-02-05', 'BCIC H.O', 252323, 2147483647, 'Assistant Programmer', 'Finance', 'Computer', '', 'Regular', '', '2021-10-17', '2026-10-17', '2021-10-17', 'Regular', '2023-01-07', '2024-01-07', '6th', '63810', '3190', '67000', '35500-67010', 'In Service', '', '2023-10-12 12:27:19', '2023-10-12 00:00:00'),
(32, 15, '5620-22', '1987-02-06', '2016-04-25', 'Complete', '0000-00-00', '2046-02-05', '2047-02-05', 'BCIC H.O.', 436469, 2147483647, 'Assistant Engineer', 'Technical', 'Engineer', '', 'Regular', '', '2021-07-10', '2026-07-10', '0000-00-00', 'Regular', '2023-01-07', '2024-01-07', '6th', '63810', '3190', '67000', '35500-67010', 'In Service', '', '2023-10-12 21:31:27', '2023-10-12 00:00:00'),
(33, 21, '5620-34', '1997-10-13', '2023-01-01', 'Complete', '2023-07-17', '2056-10-12', '2057-10-12', 'BCIC H.O.', 1234, 123456, 'Assistant Manager', 'Finance', 'Computer', '', 'Regular', '', '2023-11-01', '2028-11-01', '2023-11-28', 'Regular', '2023-01-07', '2024-01-07', '2nd', '73720', '2770', '76490', '66000-76490', 'In Service', '', '2023-11-27 11:34:25', '2023-11-27 00:00:00'),
(34, 40, '3728-3', '1978-02-06', '1990-12-20', 'Complete', '0000-00-00', '2037-02-05', '2038-02-05', 'BCIC H.O.', 346456, 2147483647, 'Assistant Manager', 'Administrative', 'Admin', '', 'Regular', '', '0000-00-00', '1995-12-20', '0000-00-00', 'Regular', '2023-01-07', '2024-01-07', '5th', '66840', '3010', '69850', '43000-69850', 'In Service', '', '2023-11-28 10:57:35', '2023-11-28 00:00:00'),
(35, 4, '5620-2', '1987-02-06', '2016-04-25', 'Complete', '2017-12-10', '2046-02-05', '2047-02-05', 'SFCL', 277072, 2147483647, 'Assistant Programmer', 'Finance', 'Computer', '', 'Regular', '', '2021-10-07', '2026-10-07', '0000-00-00', 'Regular', '2023-01-07', '2024-01-07', '6th', '63810', '3190', '67000', '35500-67010', 'In Service', '', '2023-12-10 13:00:02', '2023-12-10 00:00:00'),
(36, 85, '6920-3', '1997-10-10', '2023-03-19', 'Incomplete', '0000-00-00', '2056-10-09', '2057-10-09', 'BCIC H.O.', 6456, 54645645, 'Assistant Programmer', 'Finance', 'Computer', '', 'Regular', '', '0000-00-00', '2028-03-19', '0000-00-00', '', '2024-01-07', '2025-01-07', '9th', '39570', '1980', '41550', '22000-53060', 'In Service', '', '2024-01-04 12:42:51', '2024-01-04 12:42:51'),
(38, 22, '2765-6', '2000-10-06', '2016-02-06', 'Complete', '0000-00-00', '2059-10-05', '2060-10-05', 'CUFL', 345345, 45345345, 'Accounts Officer', 'Administrative', 'Admin', 'Security', 'Regular', '', '0000-00-00', '2021-02-06', '0000-00-00', '', '2024-01-07', '2025-01-07', '9th', '35880', '1800', '37680', '22000-53060', 'In Service', '', '2024-01-11 11:13:03', '2024-01-11 11:13:03'),
(39, 58, '6916-1', '1987-02-06', '2016-04-25', 'Incomplete', '0000-00-00', '2046-02-05', '2047-02-05', 'BCIC H.O.', 444444, 44434343, 'Assistant Programmer', 'Finance', 'Computer', '', 'Regular', '', '0000-00-00', '2021-04-25', '0000-00-00', '', '2024-01-07', '2025-01-07', '9th', '22000', '0', '22000', '22000-53060', 'In Service', '', '2024-01-15 14:33:01', '2024-01-15 15:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `loan_info`
--

CREATE TABLE `loan_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `granted_date` date NOT NULL,
  `type` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `fiscal_year` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_info`
--

INSERT INTO `loan_info` (`id`, `user_id`, `emp_id`, `ref_no`, `granted_date`, `type`, `amount`, `fiscal_year`, `created_at`) VALUES
(1, 0, '', '', '0000-00-00', 'Temporary Suspensions', '', '', '2023-10-04 10:20:55'),
(2, 0, '', '', '0000-00-00', 'Increment Held-up', '', '', '2023-10-04 10:20:55'),
(3, 0, '', '', '0000-00-00', 'Demotion', '', '', '2023-10-04 10:20:55'),
(4, 0, '', '', '0000-00-00', 'Warning', '', '', '2023-10-04 10:20:55'),
(5, 0, '', '', '0000-00-00', 'Show cause\r\n', '', '', '2023-10-04 10:20:55'),
(7, 0, '5620-1', '36.01.0000.12.1223.456.634', '2022-10-04', 'House Building Loan', '700000', '2022-2023', '2023-10-04 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `nominees`
--

CREATE TABLE `nominees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `relation` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `amount_of_share` float NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_no` varchar(20) NOT NULL,
  `branch_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nominees`
--

INSERT INTO `nominees` (`id`, `user_id`, `emp_id`, `name`, `address`, `relation`, `dob`, `amount_of_share`, `bank_name`, `account_no`, `branch_name`) VALUES
(1, 0, '2847-2', 'Foysal', 'chittagong', 'father', '2007-01-30', 100, 'Sonali Bank Ltd', '242342,', 'Dilkhusah,'),
(2, 0, '5620-1', 'emran', 'sd gg', 'father', '1990-06-06', 90, 'son', '552', '345345');

-- --------------------------------------------------------

--
-- Table structure for table `other_qualification_info`
--

CREATE TABLE `other_qualification_info` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `degree_name` varchar(50) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `university` varchar(250) NOT NULL,
  `country` varchar(50) NOT NULL,
  `course_duration` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `other_qualification_info`
--

INSERT INTO `other_qualification_info` (`id`, `emp_id`, `degree_name`, `subject`, `university`, `country`, `course_duration`) VALUES
(8, '5620-1', 'PhD', '“Project Processing Appraisal and Management System (PPS)', 'Okahama', 'BD', '2 years'),
(9, '5620-1', 'F', 'fffq', 'Yokohama', 'JP', '4 Months'),
(10, '5620-1', 'Test', 'Test ', 'TEST', 'BD', '32 days');

-- --------------------------------------------------------

--
-- Table structure for table `passing_year`
--

CREATE TABLE `passing_year` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `passing_year`
--

INSERT INTO `passing_year` (`id`, `year`) VALUES
(1, 1970),
(2, 1971),
(3, 1972),
(4, 1973),
(5, 1974),
(6, 1975),
(7, 1976),
(8, 1977),
(9, 1978),
(10, 1979),
(11, 1980),
(12, 1981),
(13, 1982),
(14, 1983),
(15, 1984),
(16, 1985),
(17, 1986),
(18, 1987),
(19, 1988),
(20, 1989),
(21, 1990),
(22, 1991),
(23, 1992),
(24, 1993),
(25, 1994),
(26, 1995),
(27, 1996),
(28, 1997),
(29, 1998),
(30, 1999),
(31, 2000),
(32, 2001),
(33, 2002),
(34, 2003),
(35, 2004),
(36, 2005),
(37, 2006),
(38, 2007),
(39, 2008),
(40, 2009),
(41, 2010),
(42, 2011),
(43, 2012),
(44, 2013),
(45, 2014),
(46, 2015),
(47, 2016),
(48, 2017),
(49, 2018),
(50, 2019),
(51, 2020),
(52, 2021),
(53, 2022),
(54, 2023);

-- --------------------------------------------------------

--
-- Table structure for table `pay_scale_2015`
--

CREATE TABLE `pay_scale_2015` (
  `id` int(11) NOT NULL,
  `scale` varchar(50) NOT NULL,
  `grade_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pay_scale_2015`
--

INSERT INTO `pay_scale_2015` (`id`, `scale`, `grade_id`) VALUES
(1, '78000', 1),
(2, '66000-76490', 2),
(3, '56500-74400', 3),
(4, '50000-71200', 4),
(5, '43000-69850', 5),
(6, '35500-67010', 6),
(7, '29000-63410', 7),
(8, '23000-55460', 8),
(9, '22000-53060', 9),
(10, '16000-38640', 10),
(11, '12500-32240', 11),
(12, '11300-27300', 12),
(13, '11000-26590', 13),
(14, '10200-24680', 14),
(15, '9700-23490', 15),
(16, '9300-22490', 16),
(17, '9000-21800', 17),
(18, '8800-21310', 18),
(19, '8500-20570', 19),
(20, '8250-20010', 20);

-- --------------------------------------------------------

--
-- Table structure for table `place_of_posting`
--

CREATE TABLE `place_of_posting` (
  `id` int(11) NOT NULL,
  `place_of_posting` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `place_of_posting`
--

INSERT INTO `place_of_posting` (`id`, `place_of_posting`) VALUES
(1, 'BCIC H.O.'),
(2, 'TICI'),
(3, 'SFCL'),
(4, 'JFCL'),
(5, 'CUFL'),
(6, 'AFCCL'),
(7, 'DAPFCL'),
(8, 'TSPCL'),
(9, 'GPFPLC'),
(10, 'GPUFP'),
(11, 'CCCL'),
(12, 'CCC'),
(13, 'BISFL'),
(14, 'KHBML/KNML'),
(15, 'KPML'),
(16, 'DLCL'),
(17, 'Chemical Godown'),
(18, '13 Buffer Godown Project'),
(19, '34 Buffer Godown Project'),
(20, 'CCCL Project');

-- --------------------------------------------------------

--
-- Table structure for table `prof_membership`
--

CREATE TABLE `prof_membership` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `mem_no` varchar(25) NOT NULL,
  `type` enum('Associate Member','Member','Fellow') NOT NULL,
  `institute` varchar(50) NOT NULL,
  `country` varchar(25) NOT NULL,
  `validity` varchar(25) NOT NULL,
  `month_year` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prof_membership`
--

INSERT INTO `prof_membership` (`id`, `user_id`, `emp_id`, `mem_no`, `type`, `institute`, `country`, `validity`, `month_year`) VALUES
(1, 6, '5620-1', '41086', 'Fellow', 'IEB b', 'BD', 'December, 2024', ''),
(2, 6, '5620-1', '9200', 'Member', 'BCS', 'BD', 'December, 2016', ''),
(3, 12, '5620-6', '54657', 'Fellow', 'IEB', 'BD', '23 days', ''),
(4, 33, '3746-5', '6723-2', '', 'Computer Society', 'BD', '20 days', ''),
(5, 8, '5620-4', 'M-41086', 'Member', 'IEB', 'BD', '1 Years', ''),
(6, 8, '5620-4', '7845', 'Fellow', 'IEB', 'bd', '1 Years', ''),
(7, 0, '5620-4', 'F-44587', 'Fellow', 'IEB', 'BD', '1 Years', '');

-- --------------------------------------------------------

--
-- Table structure for table `prof_quali_info`
--

CREATE TABLE `prof_quali_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `qualification` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prof_quali_info`
--

INSERT INTO `prof_quali_info` (`id`, `user_id`, `emp_id`, `qualification`) VALUES
(1, 8, '5620-4', 'Microsoft Certify / Certification world');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_info`
--

CREATE TABLE `promotion_info` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `date_of_promot` date NOT NULL,
  `granted_promo_date` date NOT NULL,
  `pres_desig` varchar(25) NOT NULL,
  `pres_grade` varchar(15) NOT NULL,
  `new_desig` varchar(25) NOT NULL,
  `new_grade` varchar(15) NOT NULL,
  `place_of_posting` varchar(25) NOT NULL,
  `pres_scale_of_pay` varchar(15) NOT NULL,
  `pres_basic_pay` varchar(15) NOT NULL,
  `new_scale_of_pay` varchar(15) NOT NULL,
  `new_basic_pay` varchar(15) NOT NULL,
  `nature_of_promo` varchar(100) NOT NULL,
  `issue_promot_letter` varchar(15) NOT NULL,
  `scale` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promotion_info`
--

INSERT INTO `promotion_info` (`id`, `emp_id`, `ref_no`, `designation`, `date_of_promot`, `granted_promo_date`, `pres_desig`, `pres_grade`, `new_desig`, `new_grade`, `place_of_posting`, `pres_scale_of_pay`, `pres_basic_pay`, `new_scale_of_pay`, `new_basic_pay`, `nature_of_promo`, `issue_promot_letter`, `scale`) VALUES
(1, '5620-4', '36.01.0000.762.01.0.23/1', 'Assistant Engineer', '2021-07-10', '2021-10-15', '', '', '', '', 'SFCL', '', '', '', '', 'Senior Scale', '', '35500-67010'),
(2, '5620-4', '36.01.0000.12.1223.', 'Assistant Engineer', '2023-06-01', '2023-06-03', '', '', '', '', 'JFCL', '', '', '', '', 'Selection Grade', '', '35500-67010'),
(3, '5620-4', '36.01.0000.12.1223.45', 'Additional Chief Engineer', '2023-06-20', '2023-06-06', '', '', '', '', 'BCIC H.O', '', '', '', '', 'Senior Scale', '', '12500-32240'),
(4, '5620-4', '36.01.0000.12.1223.456.63465', 'Deputy Chief Engineer', '2021-10-10', '2021-10-10', '', '', '', '', 'JFCL', '', '', '', '', 'Regular', '', '50000-71200');

-- --------------------------------------------------------

--
-- Table structure for table `publication`
--

CREATE TABLE `publication` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `books_publication` varchar(255) NOT NULL,
  `journal` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `publication`
--

INSERT INTO `publication` (`id`, `user_id`, `emp_id`, `date`, `books_publication`, `journal`, `description`) VALUES
(1, 6, '5620-1', '2010-10-10', 'BCIC', 'BCIC', 'sdfg');

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
(24, 1, 'Admin'),
(25, 35, 'DLCL'),
(26, 2, 'Accounts'),
(27, 5, 'MTS'),
(28, 5, 'Civil'),
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
(46, 46, 'Law');

-- --------------------------------------------------------

--
-- Table structure for table `service_history`
--

CREATE TABLE `service_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `service_type` enum('Before Joining','After Joining') NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `till_now` varchar(25) NOT NULL,
  `place_of_posting` varchar(25) NOT NULL,
  `org_name` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  `designation` varchar(25) NOT NULL,
  `nature_of_promo` varchar(100) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `scale` varchar(50) NOT NULL,
  `basic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_history`
--

INSERT INTO `service_history` (`id`, `user_id`, `emp_id`, `service_type`, `from_date`, `to_date`, `till_now`, `place_of_posting`, `org_name`, `location`, `designation`, `nature_of_promo`, `grade`, `scale`, `basic`) VALUES
(1, 33, '3746-5', 'Before Joining', '2014-05-15', '2016-04-24', '0000-00-00', 'Others', 'Jatiya Mohila Sangstha', 'Meherpur', 'Assistant Programmer', '', '', '22000-53060', ''),
(2, 33, '3746-5', 'After Joining', '2016-04-25', '2021-10-07', '0000-00-00', 'SFCL', 'BCIC', 'Sylhet', 'Assistant Programmer', '', '', '23100-53060', ''),
(3, 33, '3746-5', 'After Joining', '0000-00-00', '2023-04-29', '0000-00-00', 'BCIC H.O', 'BCIC', 'Dhaka', 'Programmer', '', '', '35500-67450', ''),
(4, 8, '5620-4', 'Before Joining', '2019-05-29', '2021-11-11', '0000-00-00', 'CCCL', 'BCC', 'Dhaka', 'Assistant Network Engr.', '', '', '22000', ''),
(5, 8, '5620-4', 'Before Joining', '2021-11-14', '2023-03-18', '0000-00-00', '', 'BEC', 'Dhaka, Motijheel', 'AME', '', '', '22000', ''),
(6, 8, '5620-4', 'After Joining', '2022-10-11', '0000-00-00', '0000-00-00', '', 'BCC', 'Dhaka', 'DCE', '', '', '22000', ''),
(7, 9, '5620-11', 'Before Joining', '2021-02-01', '0000-00-00', '0000-00-00', 'CUFL', 'CUFL', 'Chittagong', 'Deputy Chief Engineer', '', '', '43000-', ''),
(8, 0, '5620-4', 'After Joining', '2022-12-11', '2023-02-11', '0000-00-00', 'JFCL', 'DAPFCL', 'Chittagong', 'Addl. Chief Engr.', '', '', '60000', ''),
(9, 0, '7733-0', 'After Joining', '2000-10-10', '2012-02-21', '0000-00-00', 'GPFPLC', 'DAPFCL', 'Chittagong', 'Deputy Chief Engineer', '', '', '60000', ''),
(10, 0, '7733-0', 'After Joining', '2021-12-30', '0000-00-00', '0000-00-00', 'BCIC H.O.', '', '', 'Additional Chief Engineer', '', '', '43000-69850', ''),
(11, 8, '5620-4', 'After Joining', '2022-02-10', '0000-00-00', '0000-00-00', 'SFCL', 'BCC', 'Dhaka', 'Executive Engineer', '', '', '56500-74400', ''),
(12, 0, '7720-9', 'After Joining', '2022-10-10', '0000-00-00', '0000-00-00', 'BCIC H.O.', 'BEC', 'Dhaka', 'Assistant Engineer', '', '', '23000-55460', ''),
(18, 0, '6916-1', 'After Joining', '2021-10-11', '2022-10-11', '', 'BCIC H.O.', '', '', 'Assistant Engineer', '', '9th', '22000-53060', '22000');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subject_graduation`
--

CREATE TABLE `subject_graduation` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_graduation`
--

INSERT INTO `subject_graduation` (`id`, `exam_id`, `name`) VALUES
(1, 0, 'Select'),
(2, 0, 'Architecture'),
(3, 0, 'Chemical Engineering'),
(4, 0, 'Civil Engineering'),
(5, 0, 'Computer Engineering'),
(6, 0, 'Computer Science'),
(7, 0, 'Computer Science & Engineering'),
(8, 0, 'Computer Science & Information Technology'),
(9, 0, 'Electrical & Electronics Engineering'),
(10, 0, 'Electrical Engineering'),
(11, 0, 'Electronics Engineering'),
(12, 0, 'Electronics & Communication Engineering'),
(13, 0, 'Electronics & Telecommunication Engineering'),
(14, 0, 'Environmental Engineering'),
(15, 0, 'Food and Process Engineering'),
(16, 0, 'Genetic Engineering'),
(17, 0, 'Industrial Engineering'),
(18, 0, 'Information and Communication Engineering'),
(19, 0, 'Information and Communication Technology'),
(20, 0, 'Leather Engineering'),
(21, 0, 'Marine Engineering'),
(22, 0, 'Materials Science & Engineering'),
(23, 0, 'Mechanical Engineering'),
(24, 0, 'Medical Physics & Biomedical Engineering'),
(25, 0, 'Metallurgical Engineering'),
(26, 0, 'Microwave Engineering'),
(27, 0, 'Mineral Engineering'),
(28, 0, 'Mining Engineering'),
(29, 0, 'Naval Architecture & Marine Engineering'),
(30, 0, 'Physical Planning'),
(31, 0, 'Regional Planning'),
(32, 0, 'Software Engineering'),
(33, 0, 'Structural Engineering'),
(34, 0, 'Telecommunication Engineering'),
(35, 0, 'Textile Engineering'),
(36, 0, 'Town Planning'),
(37, 0, 'Urban & Regional Planning'),
(38, 0, 'Water Resource Engineering'),
(39, 0, 'Others'),
(40, 1, 'Accounting'),
(41, 1, 'Agriculture'),
(42, 1, 'Anthropology'),
(43, 1, 'Applied Chemistry'),
(44, 1, 'Applied Mathematics'),
(45, 1, 'Applied Physics'),
(46, 1, 'Archaeology'),
(47, 1, 'Bangla'),
(48, 1, 'Banking'),
(49, 1, 'Biochemistry'),
(50, 1, 'Botany'),
(51, 1, 'Business Administration'),
(52, 1, 'Chemistry'),
(53, 1, 'Clinical Psychology'),
(54, 1, 'Communication Disorders'),
(55, 1, 'Computer Science'),
(56, 1, 'Criminology'),
(57, 1, 'Criminology & Police Science'),
(58, 1, 'Development Studies'),
(59, 1, 'Drama & Music'),
(60, 1, 'Drawing & Painting'),
(61, 1, 'Economics'),
(62, 1, 'Education'),
(63, 1, 'English'),
(64, 1, 'Environmental Science'),
(65, 1, 'Ethics'),
(66, 1, 'Finance'),
(67, 1, 'Finance & Banking'),
(68, 1, 'Fine Arts'),
(69, 1, 'Folklore'),
(70, 1, 'Forestry'),
(71, 1, 'Genetic and Breeding'),
(72, 1, 'Genetic Engineering and Biotechnology'),
(73, 1, 'Geography'),
(74, 1, 'Geography and Environmental Science'),
(75, 1, 'Geology/Geology and Mining'),
(76, 1, 'Government and Politics'),
(77, 1, 'Graphics'),
(78, 1, 'History'),
(79, 1, 'History of Music'),
(80, 1, 'Home Economics'),
(81, 1, 'Industrial Arts'),
(82, 1, 'Information Science and Library Management'),
(83, 1, 'Information Technology'),
(84, 1, 'Industrial Law'),
(85, 1, 'International Relations'),
(86, 1, 'Islamic History and Culture'),
(87, 1, 'Islamic Studies'),
(88, 1, 'Language/Linguistic '),
(89, 1, 'Law/Jurisprudence'),
(90, 1, 'Management'),
(91, 1, 'Marine Science'),
(92, 1, 'Marketing'),
(93, 1, 'Mass Comm. & Journalism'),
(94, 1, 'Mathematics'),
(95, 1, 'Medical Technology'),
(96, 1, 'Microbiology'),
(97, 1, 'Pali'),
(98, 1, 'Peace & Conflict'),
(99, 1, 'Persian'),
(100, 1, 'Pharmaceutical Chemistry'),
(101, 1, 'Pharmacy'),
(102, 1, 'Philosophy'),
(103, 1, 'Physics'),
(104, 1, 'Political Science'),
(105, 1, 'Population Science'),
(106, 1, 'Population Science and Human Resource Development (RU)'),
(107, 1, 'Printing and Publication Studies'),
(108, 1, 'Public Administration'),
(109, 1, 'Public Finance'),
(110, 1, 'Sanskrit'),
(111, 1, 'Social Welfare/Social Work'),
(112, 1, 'Social Works'),
(113, 1, 'Sociology'),
(114, 1, 'Soil, Water and Environment Science'),
(115, 1, 'Statistics'),
(116, 1, 'Television, Film and Photography'),
(117, 1, 'Urban Development'),
(118, 1, 'Urdu'),
(119, 1, 'Women and Gender Studies'),
(120, 1, 'Women Studies'),
(121, 1, 'World Religion'),
(122, 1, 'Zoology'),
(123, 1, 'Akaid'),
(124, 1, 'Arabic'),
(125, 1, 'Fikha'),
(126, 1, 'Hadith'),
(127, 1, 'Modern Arabic'),
(128, 1, 'Tafsir'),
(129, 1, 'Accounting and Information Systems'),
(130, 1, 'Banking & Insurance'),
(131, 1, 'Human Resource Management'),
(132, 1, 'International Business'),
(133, 1, 'Management Information Systems'),
(134, 1, 'Organization Strategy and Leadership'),
(135, 1, 'Tourism and Hospitality Management'),
(136, 1, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `subject_hsc`
--

CREATE TABLE `subject_hsc` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_hsc`
--

INSERT INTO `subject_hsc` (`id`, `name`) VALUES
(1, 'Business Studies'),
(2, 'General'),
(3, 'Humanities'),
(4, 'Science'),
(5, 'Agriculture Technology'),
(6, 'Agro-Based Food'),
(7, 'Architectural Drafting with CAD'),
(8, 'Architecture and Interior Design Technology'),
(9, 'Automobile Technology'),
(10, 'Automotive'),
(11, 'Building Maintenance'),
(12, 'Ceramic'),
(13, 'Chemical Technology'),
(14, 'Civil Construction'),
(15, 'Civil Drafting with CAD'),
(16, 'Civil Technology'),
(17, 'Computer and Information Technology'),
(18, 'Computer Science & Technology'),
(19, 'Data Telecommunication and Network Technology'),
(20, 'Dress Making'),
(21, 'Dyening, Printing and Finishing'),
(22, 'Electrical and Electronics Technology'),
(23, 'Electrical Maintenance Works'),
(24, 'Electrical Technology'),
(25, 'Electronics Technology'),
(26, 'Environmental Technology'),
(27, 'Farm Machinery'),
(28, 'Firm Machinery'),
(29, 'Fish Culture and Breeding'),
(30, 'Flower, Fruit and Vegetable Cultivation'),
(31, 'Food'),
(32, 'Food Processing and Preservation'),
(33, 'General Electrical Works'),
(34, 'General Electronics'),
(35, 'General Mechanics'),
(36, 'Glass'),
(37, 'Glass and Ceramic'),
(38, 'Instrumentation and Process Control Technology'),
(39, 'Knitting'),
(40, 'Library Science'),
(41, 'Livestock Rearing and Farming'),
(42, 'Machine Tools Operation'),
(43, 'Mechanical Drafting with CAD'),
(44, 'Mechanical Technology'),
(45, 'Mechatronics Technology'),
(46, 'Patient Care'),
(47, 'Plumbing and Pipe Fittings'),
(48, 'Poultry Rearing and Farming'),
(49, 'Power Technology'),
(50, 'Refrigeration and Air Conditioning Technology'),
(51, 'Refrigeration and Air Conditioning'),
(52, 'Shrimp Culture and Breeding'),
(53, 'Survey'),
(54, 'Telecommunication Technology'),
(55, 'Textile Technology'),
(56, 'Weaving'),
(57, 'Welding and Fabrication'),
(58, 'Wood Working'),
(59, 'Other'),
(60, 'Electro-Medical Technology'),
(61, 'Garments Design and Pattern Making'),
(62, 'Graphic Design Technology'),
(63, 'Information and Communication Technology'),
(64, 'Jute Technology'),
(65, 'Marine Technology'),
(66, 'Mining and Mine Survey Technology'),
(67, 'Shipbuilding Technology'),
(68, 'Dental'),
(69, 'Laboratory'),
(70, 'Pharmacy'),
(71, 'Physiotherapy'),
(72, 'Radiography'),
(73, 'Radiotherapy');

-- --------------------------------------------------------

--
-- Table structure for table `subject_ssc`
--

CREATE TABLE `subject_ssc` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_ssc`
--

INSERT INTO `subject_ssc` (`id`, `name`, `subject_id`) VALUES
(1, 'Select', 1),
(2, 'General', 1),
(3, 'Humanities', 1),
(4, 'Science', 1),
(5, 'Agriculture Technology', 2),
(6, 'Agro-Based Food', 2),
(7, 'Architectural Drafting with CAD', 2),
(8, 'Architecture and Interior Design Technology', 2),
(9, 'Automobile Technology', 2),
(10, 'Automotive', 2),
(11, 'Building Maintenance', 2),
(12, 'Ceramic', 2),
(13, 'Chemical Technology', 2),
(14, 'Civil Construction', 2),
(15, 'Civil Drafting with CAD', 2),
(16, 'Civil Technology', 2),
(17, 'Computer and Information Technology', 2),
(18, 'Computer Science & Technology', 2),
(19, 'Data Telecommunication and Network Technology', 2),
(20, 'Dress Making', 2),
(21, 'Dyening, Printing and Finishing', 2),
(22, 'Electrical and Electronics Technology', 2),
(23, 'Electrical Maintenance Works', 2),
(24, 'Electrical Technology', 2),
(25, 'Electronics Technology', 2),
(26, 'Environmental Technology', 2),
(27, 'Farm Machinery', 2),
(28, 'Firm Machinery', 2),
(29, 'Fish Culture and Breeding', 2),
(30, 'Flower, Fruit and Vegetable Cultivation', 2),
(31, 'Food', 2),
(32, 'Food Processing and Preservation', 2),
(33, 'General Electrical Works', 2),
(34, 'General Electronics', 2),
(35, 'General Mechanics', 2),
(36, 'Glass', 2),
(37, 'Glass and Ceramic', 2),
(38, 'Instrumentation and Process Control Technology', 2),
(39, 'Knitting', 2),
(40, 'Library Science', 2),
(41, 'Livestock Rearing and Farming', 2),
(42, 'Machine Tools Operation', 2),
(43, 'Mechanical Drafting with CAD', 2),
(44, 'Mechanical Technology', 2),
(45, 'Mechatronics Technology', 2),
(46, 'Patient Care', 2),
(47, 'Plumbing and Pipe Fittings', 2),
(48, 'Poultry Rearing and Farming', 2),
(49, 'Power Technology', 2),
(50, 'Refrigeration and Air Conditioning Technology', 2),
(51, 'Refrigeration and Air Conditioning', 2),
(52, 'Shrimp Culture and Breeding', 2),
(53, 'Survey', 2),
(54, 'Telecommunication Technology', 2),
(55, 'Textile Technology', 2),
(56, 'Weaving', 2),
(57, 'Welding and Fabrication', 2),
(58, 'Wood Working', 2),
(60, 'Electro-Medical Technology', 2),
(61, 'Garments Design and Pattern Making', 2),
(62, 'Graphic Design Technology', 2),
(63, 'Information and Communication Technology', 2),
(64, 'Jute Technology', 2),
(65, 'Marine Technology', 2),
(66, 'Mining and Mine Survey Technology', 2),
(67, 'Shipbuilding Technology', 2),
(68, 'Dental', 3),
(69, 'Laboratory', 3),
(70, 'Pharmacy', 3),
(71, 'Physiotherapy', 3),
(72, 'Radiography', 3),
(73, 'Radiotherapy', 3),
(74, 'Others', 2),
(75, 'Business Studies', 2),
(76, 'Business Studies', 1),
(77, 'Select', 4);

-- --------------------------------------------------------

--
-- Table structure for table `subject_ssc_hsc`
--

CREATE TABLE `subject_ssc_hsc` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_ssc_hsc`
--

INSERT INTO `subject_ssc_hsc` (`id`, `exam_id`, `name`) VALUES
(1, 1, 'Business Studies'),
(2, 1, 'General'),
(3, 1, 'Humanities'),
(4, 1, 'Science'),
(5, 0, 'Agriculture Technology'),
(6, 0, 'Agro-Based Food'),
(7, 0, 'Architectural Drafting with CAD'),
(8, 0, 'Architecture and Interior Design Technology'),
(9, 0, 'Automobile Technology'),
(10, 0, 'Automotive'),
(11, 0, 'Building Maintenance'),
(12, 0, 'Ceramic'),
(13, 0, 'Chemical Technology'),
(14, 0, 'Civil Construction'),
(15, 0, 'Civil Drafting with CAD'),
(16, 0, 'Civil Technology'),
(17, 0, 'Computer and Information Technology'),
(18, 0, 'Computer Science & Technology'),
(19, 0, 'Data Telecommunication and Network Technology'),
(20, 0, 'Dress Making'),
(21, 0, 'Dyening, Printing and Finishing'),
(22, 0, 'Electrical and Electronics Technology'),
(23, 0, 'Electrical Maintenance Works'),
(24, 0, 'Electrical Technology'),
(25, 0, 'Electronics Technology'),
(26, 0, 'Environmental Technology'),
(27, 0, 'Farm Machinery'),
(28, 0, 'Firm Machinery'),
(29, 0, 'Fish Culture and Breeding'),
(30, 0, 'Flower, Fruit and Vegetable Cultivation'),
(31, 0, 'Food'),
(32, 0, 'Food Processing and Preservation'),
(33, 0, 'General Electrical Works'),
(34, 0, 'General Electronics'),
(35, 0, 'General Mechanics'),
(36, 0, 'Glass'),
(37, 0, 'Glass and Ceramic'),
(38, 0, 'Instrumentation and Process Control Technology'),
(39, 0, 'Knitting'),
(40, 0, 'Library Science'),
(41, 0, 'Livestock Rearing and Farming'),
(42, 0, 'Machine Tools Operation'),
(43, 0, 'Mechanical Drafting with CAD'),
(44, 0, 'Mechanical Technology'),
(45, 0, 'Mechatronics Technology'),
(46, 0, 'Patient Care'),
(47, 0, 'Plumbing and Pipe Fittings'),
(48, 0, 'Poultry Rearing and Farming'),
(49, 0, 'Power Technology'),
(50, 0, 'Refrigeration and Air Conditioning Technology'),
(51, 0, 'Refrigeration and Air Conditioning'),
(52, 0, 'Shrimp Culture and Breeding'),
(53, 0, 'Survey'),
(54, 0, 'Telecommunication Technology'),
(55, 0, 'Textile Technology'),
(56, 0, 'Weaving'),
(57, 0, 'Welding and Fabrication'),
(58, 0, 'Wood Working'),
(59, 1, 'Other'),
(60, 0, 'Electro-Medical Technology'),
(61, 0, 'Garments Design and Pattern Making'),
(62, 0, 'Graphic Design Technology'),
(63, 0, 'Information and Communication Technology'),
(64, 0, 'Jute Technology'),
(65, 0, 'Marine Technology'),
(66, 0, 'Mining and Mine Survey Technology'),
(67, 0, 'Shipbuilding Technology'),
(68, 0, 'Dental'),
(69, 0, 'Laboratory'),
(70, 0, 'Pharmacy'),
(71, 0, 'Physiotherapy'),
(72, 0, 'Radiography'),
(73, 0, 'Radiotherapy');

-- --------------------------------------------------------

--
-- Table structure for table `sub_cadre`
--

CREATE TABLE `sub_cadre` (
  `id` int(11) NOT NULL,
  `cadre_id` int(11) NOT NULL,
  `sub_cadre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_cadre`
--

INSERT INTO `sub_cadre` (`id`, `cadre_id`, `sub_cadre`) VALUES
(1, 1, 'Admin'),
(2, 1, 'Security'),
(3, 1, 'Medical'),
(4, 1, 'Education'),
(5, 1, 'Headmaster'),
(6, 1, 'Assistant Headmaster'),
(7, 2, 'Operation'),
(8, 2, 'Engineer'),
(9, 2, 'Engineer(Civil)'),
(10, 2, 'Forest'),
(11, 7, 'Accounts & Audit, C&B, Fin & Ins'),
(12, 3, 'Computer'),
(13, 4, 'Commercial'),
(14, 5, 'Senior GM'),
(15, 6, 'General Manager'),
(16, 7, 'Accounts'),
(17, 3, 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `sub_cadre_header`
--

CREATE TABLE `sub_cadre_header` (
  `id` int(11) NOT NULL,
  `header` varchar(50) NOT NULL,
  `sub_cadre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_cadre_header`
--

INSERT INTO `sub_cadre_header` (`id`, `header`, `sub_cadre_id`) VALUES
(1, 'Administrative', 0),
(2, 'Commercial', 0),
(3, 'Grade-1', 0),
(4, 'Accounts', 0),
(5, 'MTS/EIP', 0),
(6, 'MTS/Mech', 0),
(7, 'Technical Officer', 0),
(8, 'Chemical', 0),
(9, 'Chemistry', 0),
(10, 'Production', 0),
(11, 'Instrument', 0),
(12, 'EEE', 0),
(13, 'ME', 0),
(14, 'Civil', 0),
(15, 'Architecture', 0),
(16, 'Forest', 0),
(17, 'MTS', 0),
(18, 'Operation', 0),
(19, 'Security', 0),
(20, 'Grade-2', 0),
(21, 'Power', 0),
(22, 'Technical', 0),
(23, 'Marketing', 0),
(24, 'Purchase', 0),
(25, 'Coordination', 0),
(26, 'PRD', 0),
(27, 'L&W', 0),
(28, 'LSA', 0),
(29, 'Estate', 0),
(30, 'F&S', 0),
(31, 'Sales', 0);

-- --------------------------------------------------------

--
-- Table structure for table `summ_rating_sheet_promot`
--

CREATE TABLE `summ_rating_sheet_promot` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `pres_desig` varchar(25) NOT NULL,
  `pres_grade` varchar(15) NOT NULL,
  `prop_desig` varchar(25) NOT NULL,
  `prop_grade` varchar(15) NOT NULL,
  `pres_sal_scale` varchar(15) NOT NULL,
  `prop_sal_scale` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `training_info`
--

CREATE TABLE `training_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `country` varchar(25) NOT NULL,
  `period` varchar(25) NOT NULL,
  `month_year` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `training_info`
--

INSERT INTO `training_info` (`id`, `user_id`, `emp_id`, `type`, `title`, `institute`, `country`, `period`, `month_year`) VALUES
(1, 6, '5620-1', 'Local Training', 'Foundation Training ', 'BIM n', 'BD ', '42 Days 2', 'October 2019'),
(2, 6, '5620-1', 'Foreign Training', 'Inspection of GT', 'Honeywell Ltd', 'Nederland', '2 weeks', 'December 2021'),
(3, 8, '5620-4', 'Foreign Training', 'Training About Process Licensors', 'KBR', 'Netherlands', '6 Days', 'July 2020'),
(5, 0, '', 'Local Training', 'Foundation ', 'BIM', 'BD', '42 Days', 'July 2019'),
(6, 0, '5620-4', 'Foreign Training', 'Honywell', 'Honywell', 'dfgd', 'dfgd', 'dgd');

-- --------------------------------------------------------

--
-- Table structure for table `transf_info`
--

CREATE TABLE `transf_info` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `date_of_posting` date NOT NULL,
  `pres_place_of_post` varchar(25) NOT NULL,
  `new_place_of_loc` varchar(25) NOT NULL,
  `date_of_join_at_new_loc` date NOT NULL,
  `division` varchar(25) NOT NULL,
  `designation` varchar(25) NOT NULL,
  `pay_scale` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `university_list`
--

CREATE TABLE `university_list` (
  `id` int(11) NOT NULL,
  `university_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `university_list`
--

INSERT INTO `university_list` (`id`, `university_name`) VALUES
(1, 'University of Dhaka'),
(2, 'University of Rajshahi'),
(3, 'Bangladesh Agricultural University'),
(4, 'Bangladesh University of Engineering & Technology'),
(5, 'University of Chittagong'),
(6, 'Jahangirnagar University'),
(7, 'Islamic University, Bangladesh'),
(8, 'Shahjalal University of Science & Technology'),
(9, 'Khulna University'),
(10, 'National University'),
(11, 'Bangladesh Open University'),
(12, 'Bangabandhu Sheikh Mujib Medical University'),
(13, 'Bangabandhu Sheikh Mujibur Rahman Agricultural University'),
(14, 'Bangabandhu Sheikh Mujibur Rahman Agricultural University'),
(15, 'Hajee Mohammad Danesh Science & Technology University'),
(16, 'Mawlana Bhashani Science & Technology University'),
(17, 'Patuakhali Science And Technology University'),
(18, 'Sher-e-Bangla Agricultural University'),
(19, 'Chittagong University of Engineering & Technology'),
(20, 'Rajshahi University of Engineering & Technology'),
(21, 'Khulna University of Engineering & Technology'),
(22, 'Dhaka University of Engineering & Technology'),
(23, 'Noakhali Science & Technology University'),
(24, 'Jagannath University'),
(25, 'Comilla University'),
(26, 'Jatiya Kabi Kazi Nazrul Islam University'),
(27, 'Chittagong Veterinary and Animal Sciences University'),
(28, 'Sylhet Agricultural University'),
(29, 'Jessore University of Science & Technology'),
(30, 'Pabna University of Science and Technology'),
(31, 'Begum Rokeya University, Rangpur'),
(32, 'Bangladesh University of Professionals'),
(33, 'Bangabandhu Sheikh Mujibur Rahman Science & Technology'),
(34, 'Bangladesh University of Textiles'),
(35, 'University of Barishal'),
(36, 'Rangamati Science and Technology University'),
(37, 'Islamic Arabic University'),
(38, 'Chittagong Medical University'),
(39, 'Rajshahi Medical University'),
(40, 'Rabindra University, Bangladesh'),
(41, 'Bangabandhu Sheikh Mujibur Rahman Digital University'),
(42, 'Sheikh Hasina University'),
(43, 'Khulna Agricultural University'),
(44, 'Bangamata Sheikh Fojilatunnesa Mujib Science and Technology'),
(45, 'Sylhet Medical University'),
(46, 'Bangabandhu Sheikh Mujibur Rahman Aviation And Aerospace University (BSMRAAU)'),
(47, 'Chandpur Science and Technology University'),
(48, 'Bangabandhu Sheikh Mujibur Rahman University, Kishoreganj'),
(49, 'Hobiganj Agricultural University'),
(50, 'Sheikh Hasina Medical University, Khulna'),
(51, 'Kurigram Agricultural University'),
(52, 'Sunamganj Science and Technology University'),
(53, 'Bangabandhu Sheikh Mujibur Rahman Science & Technology'),
(54, 'North South University'),
(55, 'University of Science & Technology Chittagong'),
(56, 'Independent University, Bangladesh'),
(57, 'Central Women\'s University'),
(58, 'International University of Business Agriculture & Technology'),
(59, 'International Islamic University Chittagong'),
(60, 'Ahsanullah University of Science and Technology'),
(61, 'American International University-Bangladesh'),
(62, 'East West University'),
(63, 'University of Asia Pacific'),
(64, 'The People\'s University of Bangladesh '),
(65, 'Asian University of Bangladesh'),
(66, 'Dhaka International University'),
(67, 'Manarat International University'),
(68, 'BRAC University'),
(69, 'Bangladesh University '),
(70, 'Leading University'),
(71, 'Southeast University '),
(72, 'Daffodil International University'),
(73, 'Stamford University Bangladesh'),
(74, 'State University of Bangladesh'),
(75, 'City University'),
(76, 'Prime University'),
(77, 'Northern University Bangladesh'),
(78, 'Southern University Bangladesh '),
(79, 'Green University of Bangladesh'),
(80, 'World University of Bangladesh'),
(81, 'Shanto-Mariam University of Creative Technology'),
(82, 'Eastern University'),
(83, 'Metropolitan University'),
(84, 'Uttara University'),
(85, 'United International University'),
(86, 'University of South Asia'),
(87, 'Bangladesh University of Business & Technology'),
(88, 'Presidency University '),
(89, 'University of Information Technology & Sciences'),
(90, 'University of Liberal Arts Bangladesh'),
(91, 'Atish Dipankar University of Science & Technology'),
(92, 'Bangladesh Islami University'),
(93, 'ASA University Bangladesh'),
(94, 'BGMEA University of Fashion & Technology(BUFT) '),
(95, 'Notre Dame University Bangladesh'),
(96, 'Bangladesh Army University of Science and Technology(BAUST), Saidpur'),
(97, 'Bangladesh Army University of Engineering and Technology (BAUET), Qadirabad'),
(98, 'Bangladesh Army International University of Science & Technology(BAIUST) ,Comilla'),
(99, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `upazilla`
--

CREATE TABLE `upazilla` (
  `id` int(11) NOT NULL,
  `upazilla` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `employee_type` enum('Officer','Staff','','') NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `division` varchar(25) NOT NULL,
  `place_of_posting` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `role` enum('user','admin','sadmin') NOT NULL,
  `image` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `mobile_no` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nid` varchar(13) NOT NULL,
  `job_confirm_status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_type`, `emp_id`, `name`, `designation`, `division`, `place_of_posting`, `password`, `status`, `role`, `image`, `dob`, `mobile_no`, `email`, `nid`, `job_confirm_status`, `created_at`, `last_updated`) VALUES
(1, '', '5620-1', 'Sunil Chandra Das ', 'General Manager', 'Audit Division', 'BCIC H.O', 'c7cb8046c46be47a9ba3bfdbed859b96', 'approved', 'admin', '../images/ffa2bd4125ab20171004_092347.jpg', '0000-00-00', '01718834659', 'admin@gmail.com', '', 'approved', NULL, '2024-01-26 17:24:57'),
(2, '', '5620-0', 'Md. Tareq Emran', 'Programmer', 'MTS/EIP', 'BCIC H.O', 'c7cb8046c46be47a9ba3bfdbed859b96', 'approved', 'sadmin', 'image/Dir.(Fin.).jpg', '0000-00-00', '', '', '', 'approved', NULL, '2023-12-28 03:53:08'),
(3, '', '5620-2', 'Jamal Uddin', 'Deputy Chief Engineer', 'MTS/EIP', 'BCIC H.O', 'c7cb8046c46be47a9ba3bfdbed859b96', 'approved', 'user', 'image/dlcl.jpg', '0000-00-00', '', '', '', 'approved', NULL, '2023-12-28 03:53:11'),
(5, '', '6594-6', 'Shenwarn ', 'Assistant Programmer', 'Accounts', 'BCIC H.O', 'c7cb8046c46be47a9ba3bfdbed859b96', 'approved', 'user', 'image/ss.jpg', '0000-00-00', '', '', '', 'approved', NULL, '2023-12-28 03:53:13'),
(6, '', '6594-5', 'Abul', 'Assistant Admin Officer', 'Administration', 'BCIC H.O', 'c7cb8046c46be47a9ba3bfdbed859b96', 'approved', 'user', 'image/ashraf sir Planing.jpg', '0000-00-00', '', '', '', 'approved', NULL, '2023-12-28 03:53:16'),
(32, 'Officer', '6919-5', 'Mery', 'Accounts Officer', '', 'BCIC H.O', 'c7cb8046c46be47a9ba3bfdbed859b96', 'approved', 'user', 'image/CA_bcic2.jpg', '0000-00-00', '01671583637', 'emrantareq09@gmail.com', '5645645645', 'approved', NULL, '2024-01-04 06:30:13'),
(33, 'Officer', '6920-3', 'emon', 'Accounts Officer', '', 'BCIC H.O', '169ce70d22d516cc17f8367279307b54', 'approved', 'user', 'image/cs.jpg', '0000-00-00', '01557009083', 'emrantareq09@gmail.com', '6653453453', 'approved', NULL, '2024-01-04 09:11:33'),
(41, 'Officer', '5620-8', 'Fajul', 'Accounts Officer', 'Accounts Division', 'BCIC H.O', 'c7cb8046c46be47a9ba3bfdbed859b96', 'pending', 'user', 'image/woodcat.jpg', '0000-00-00', '', '', '', 'pending', NULL, '2024-01-11 03:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `welfare_info`
--

CREATE TABLE `welfare_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `granted_date` date NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `fiscal_year` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `welfare_info`
--

INSERT INTO `welfare_info` (`id`, `user_id`, `emp_id`, `granted_date`, `ref_no`, `type`, `amount`, `fiscal_year`, `created_at`) VALUES
(1, 0, '5620-1', '2023-10-04', '36.01.0000.12.1223.45.2023', 'Illness Grant', '10000', '2022-2023', '2023-10-04 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `award_and_penalty`
--
ALTER TABLE `award_and_penalty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic`
--
ALTER TABLE `basic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_info`
--
ALTER TABLE `basic_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cadre`
--
ALTER TABLE `cadre`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `childs_info`
--
ALTER TABLE `childs_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diploma_course_info`
--
ALTER TABLE `diploma_course_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disciplinary_action`
--
ALTER TABLE `disciplinary_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_info`
--
ALTER TABLE `edu_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_bank_info`
--
ALTER TABLE `emp_bank_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fiscal_year`
--
ALTER TABLE `fiscal_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `img`
--
ALTER TABLE `img`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incre_info`
--
ALTER TABLE `incre_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_desc`
--
ALTER TABLE `job_desc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nominees`
--
ALTER TABLE `nominees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_qualification_info`
--
ALTER TABLE `other_qualification_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passing_year`
--
ALTER TABLE `passing_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_scale_2015`
--
ALTER TABLE `pay_scale_2015`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place_of_posting`
--
ALTER TABLE `place_of_posting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prof_membership`
--
ALTER TABLE `prof_membership`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prof_quali_info`
--
ALTER TABLE `prof_quali_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promotion_info`
--
ALTER TABLE `promotion_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_history`
--
ALTER TABLE `service_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_graduation`
--
ALTER TABLE `subject_graduation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_hsc`
--
ALTER TABLE `subject_hsc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_ssc`
--
ALTER TABLE `subject_ssc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_ssc_hsc`
--
ALTER TABLE `subject_ssc_hsc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_cadre`
--
ALTER TABLE `sub_cadre`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_cadre_header`
--
ALTER TABLE `sub_cadre_header`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `summ_rating_sheet_promot`
--
ALTER TABLE `summ_rating_sheet_promot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_info`
--
ALTER TABLE `training_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transf_info`
--
ALTER TABLE `transf_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `university_list`
--
ALTER TABLE `university_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upazilla`
--
ALTER TABLE `upazilla`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `welfare_info`
--
ALTER TABLE `welfare_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `award`
--
ALTER TABLE `award`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `award_and_penalty`
--
ALTER TABLE `award_and_penalty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `basic`
--
ALTER TABLE `basic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

--
-- AUTO_INCREMENT for table `basic_info`
--
ALTER TABLE `basic_info`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `board`
--
ALTER TABLE `board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cadre`
--
ALTER TABLE `cadre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `childs_info`
--
ALTER TABLE `childs_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `diploma_course_info`
--
ALTER TABLE `diploma_course_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `disciplinary_action`
--
ALTER TABLE `disciplinary_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `edu_info`
--
ALTER TABLE `edu_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `emp_bank_info`
--
ALTER TABLE `emp_bank_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `fiscal_year`
--
ALTER TABLE `fiscal_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `img`
--
ALTER TABLE `img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incre_info`
--
ALTER TABLE `incre_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_desc`
--
ALTER TABLE `job_desc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `loan_info`
--
ALTER TABLE `loan_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nominees`
--
ALTER TABLE `nominees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `other_qualification_info`
--
ALTER TABLE `other_qualification_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `passing_year`
--
ALTER TABLE `passing_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `pay_scale_2015`
--
ALTER TABLE `pay_scale_2015`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `place_of_posting`
--
ALTER TABLE `place_of_posting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `prof_membership`
--
ALTER TABLE `prof_membership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `prof_quali_info`
--
ALTER TABLE `prof_quali_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promotion_info`
--
ALTER TABLE `promotion_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `publication`
--
ALTER TABLE `publication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `service_history`
--
ALTER TABLE `service_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_graduation`
--
ALTER TABLE `subject_graduation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `subject_hsc`
--
ALTER TABLE `subject_hsc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `subject_ssc`
--
ALTER TABLE `subject_ssc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `subject_ssc_hsc`
--
ALTER TABLE `subject_ssc_hsc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `sub_cadre`
--
ALTER TABLE `sub_cadre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `sub_cadre_header`
--
ALTER TABLE `sub_cadre_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `summ_rating_sheet_promot`
--
ALTER TABLE `summ_rating_sheet_promot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_info`
--
ALTER TABLE `training_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transf_info`
--
ALTER TABLE `transf_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_list`
--
ALTER TABLE `university_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `upazilla`
--
ALTER TABLE `upazilla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `welfare_info`
--
ALTER TABLE `welfare_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
