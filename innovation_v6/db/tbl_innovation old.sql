-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 09:39 AM
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
-- Table structure for table `tbl_innovation`
--

CREATE TABLE `tbl_innovation` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `place_of_posting` varchar(100) DEFAULT NULL,
  `fiscal_year` varchar(10) DEFAULT NULL,
  `title_of_idea` varchar(255) DEFAULT NULL,
  `idea_imp_date` date DEFAULT NULL,
  `identify_prob_desc` text DEFAULT NULL,
  `prob_sol_plan` text DEFAULT NULL,
  `prob_sol_desc` text DEFAULT NULL,
  `cost` decimal(15,2) DEFAULT NULL,
  `cost_less_desc` text DEFAULT NULL,
  `value_add` text DEFAULT NULL,
  `time_saving` text DEFAULT NULL,
  `cost_effectiveness` text DEFAULT NULL,
  `profitability` text DEFAULT NULL,
  `image_befor_after_inno` longblob DEFAULT NULL,
  `flowchart` longblob DEFAULT NULL,
  `attestration` text DEFAULT NULL,
  `imple_status` enum('বাস্তবায়িত','চলমান') DEFAULT NULL,
  `replicate_eligibility` text DEFAULT NULL,
  `feedback` varchar(100) NOT NULL,
  `service_link` varchar(150) NOT NULL,
  `remarks` text DEFAULT NULL,
  `prize` enum('yes','no','','') NOT NULL DEFAULT 'no',
  `prize_amount` varchar(50) DEFAULT NULL,
  `rank` enum('1st','2nd','3rd','4th','5th') DEFAULT NULL,
  `status` enum('submitted idea','primarily selected','final selected','') NOT NULL DEFAULT 'submitted idea',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_innovation`
--

INSERT INTO `tbl_innovation` (`id`, `emp_id`, `fullname`, `designation`, `email`, `mobile_no`, `place_of_posting`, `fiscal_year`, `title_of_idea`, `idea_imp_date`, `identify_prob_desc`, `prob_sol_plan`, `prob_sol_desc`, `cost`, `cost_less_desc`, `value_add`, `time_saving`, `cost_effectiveness`, `profitability`, `image_befor_after_inno`, `flowchart`, `attestration`, `imple_status`, `replicate_eligibility`, `feedback`, `service_link`, `remarks`, `prize`, `prize_amount`, `rank`, `status`, `created_at`, `updated_at`) VALUES
(1, '', 'জনাব মোঃ হাইয়ুল কাইয়ুম', 'চেয়ারম্যান', NULL, NULL, 'বিসিআইসি প্র: কা:', '২০১৮-২০১৯', 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', NULL, 'গ্রানুলার ডিএপি সার উৎপাদনকালে স্পীলেজ হিসেবে প্রাপ্ত পাউডার ডিএপি সার বিক্রয় ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'অপ্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2022-07-26 05:24:46', '2026-03-10 07:52:08'),
(2, '', 'জনাব মোঃ মোহাদ্দেস হোসেন', 'উপ প্রধান রসায়নবিদ', NULL, NULL, 'জেএফসিএল', '২০১৮-২০১৯', 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', NULL, 'High Pressure Washing Water Pump এর পরিবর্তে Low Capacity’র একটি পোর্টেবল HP Washing Water Pump ব্যবহার করে কারখানার ইউরিয়া উৎপাদন সচল রাখা', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', '', '', '', 'yes', NULL, NULL, 'final selected', '2022-07-26 05:24:46', '2026-03-10 07:52:08'),
(3, '', 'জনাব চৌধুরী মোহাম্মদ হারুন', 'মহাব্যবস্থাপক', NULL, NULL, 'টিএসপিসিএল', '২০১৮-২০১৯', 'সালফিউরিক এসিড প্ল্যান্ট নং -২ এ স্ক্রাবার স্থাপন', NULL, 'সালফিউরিক এসিড প্ল্যান্ট চালু করার পর এর কনভার্টারের বিভিন্ন বেডের তাপমাত্রা ডিজাইন মানে স্থির না হওয়া পর্যন্ত সময়ে অতিমাত্রায় নির্গত SO2, SO3  কে নিয়ন্ত্রণের জন্য বর্তমানে প্রায় সর্বত্রই সালফিউরিক এসিড প্ল্যান্টে স্ক্রাবার ব্যবহৃত হয়ে থাকে। প্ল্যান্ট চালুর শুরুতে অতিমাত্রায় নির্গত SO2, SO3  স্ক্রাবিং প্রক্রিয়ায় কষ্টিক সোডা দ্রবণের সাথে বিক্রিয়া করে সোডিয়াম সালফাইট ও সোডিয়াম সালফেট উৎপন্ন করার ফলে গ্যাস এর নিঃসরন উল্লেখযোগ্য মাত্রায় হ্রাস পেয়েছে। ফলশ্রুতিতে, জনস্বাস্থ্য ও পরিবেশ হুমকিমুক্ত রেখে সালফিউরিক এসিড প্ল্যান্ট চালিয়ে দেশে সার, বিদ্যুৎ, সমরাস্ত্র ও অন্যান্য গুরুত্বপূর্ণ সেক্টরসমূহে সালফিউরিক এসিড সরবরাহ অব্যাহত রাখা সম্ভব হচ্ছে। প্রতিবার প্ল্যান্ট চালুর সময় ৩-৪ ঘন্টা স্ক্রাবার চালু রাখার প্রয়োজন হয়। প্ল্যান্টের সকল প্যারামিটার স্বাভাবিক হলে পরবর্তীতে স্ক্রাবার ছাড়া প্ল্যান্ট চালু রাখা হয়।\r\nTCV:no\r\nFlowchart: আছে। \r\nপুরুস্কার গ্রহনের ছবি নাই', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', 'yes', NULL, NULL, 'final selected', '2022-07-26 05:24:46', '2026-03-10 07:52:08'),
(4, '', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', NULL, NULL, 'বিসিআইসি প্র: কা:', '২০১৮-২০১৯', 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', NULL, 'পোষাকের মাধ্যমে কারখানার নিরাপত্তা নিশ্চিতকরণ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'চলমান', 'বিশেষায়িত', '', '', '', 'yes', NULL, NULL, 'final selected', '2022-07-26 05:24:46', '2026-03-10 07:52:08'),
(5, '', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক (উৎপাদন ও গবেষণা)', NULL, NULL, 'বিসিআইসি প্র: কা:', '২০১৯-২০২০', 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', NULL, 'সংস্থার অধীনস্থ কারখানাসমূহে বিদ্যমান কার্যানুরোধ পত্র (Work-Request Form) এ নতুনত্ব আনয়ন', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', '', '', '', 'yes', NULL, NULL, 'final selected', '2022-07-26 08:47:10', '2026-03-10 07:52:08'),
(6, '', 'জনাব শাহীন মাহমুদ', 'উপ-প্রধান প্রকৌশলী', NULL, NULL, 'টিএসপিসিএল', '২০১৯-২০২০', 'ফসফরিক এসিড প্ল্যান্টে জিরো ডিসচার্জ সিস্টেম চালু করা।', NULL, 'কারখানার ফসফরিক এসিড প্ল্যান্টে উৎপাদিত ২৮.৫% P2O5 ফসফরিক এসিডের মধ্যে অপদ্রব্য হিসেবে ফ্লুসিলিসিক (H2SiF6) এসিড থাকে। এই ২৮.৫% P2O5 ফসফরিক এসিড হতে কনসেনট্রেটেড ফসফরিক এসিড (৪৮.৫% P2O5) তৈরীর সময় ফ্লুসিলিসিক এসিড ফিউম আকারে কনসেনট্রেটরে ভ্যাকুয়াম (720mmHg) সৃষ্টিতে ব্যবহৃত ওয়াশ ওয়াটারের সাথে চলে যাওয়ার কারণে মূলতঃ ড্রেন ওয়াটারের PH কমে যায়। কারখানার ফসফরিক এসিড প্ল্যান্টের কনসেনট্রেটর ইউনিট মডিফিকেশন করে ফ্লুসিলিসিক এসিড (H2SiF6) অপসারণ করাসহ জিরো ডিসচার্জ (ওয়াশ ওয়াটার বার বার রিসাইকেল) সিস্টেম চালু করা হয়েছে এতে কারখানা পরিবেশ বান্ধব হয়েছে।\r\nTCV: নাই। কার্যক্রম চলমান।\r\nFlowchart: পাঠাবে।\r\nপুরুস্কার গ্রহনের ছবি নাই।\r\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', '', '', '', 'yes', NULL, NULL, 'final selected', '2022-07-30 11:29:54', '2026-03-10 07:52:08'),
(7, '', 'মোহাম্মদ সোহরাব হোসেন', 'উপ-প্রধান রসায়নবিদ', NULL, NULL, 'ইউজিএসএফএল', '২০১৯-২০২০', 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', NULL, 'শীট গ্লাস ডেলিভারীতে খড়ের সাথে সামান্য ঘাস ব্যবহার করা', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'বিশেষায়িত', '', '', '', 'yes', NULL, NULL, 'final selected', '2022-07-30 11:36:17', '2026-03-10 07:52:08'),
(8, '৫৬২০-১', 'ড. এম এম এ কাদের', 'ব্যবস্থাপনা পরিচালক', NULL, NULL, '', '২০১৯-২০২০', 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', NULL, 'পরিকল্পিত বনায়নের মাধ্যমে পতিত ভূমি দখলমুক্ত রাখা ও বেদখলকৃত জায়গা দখলমুক্ত করা', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'চলমান', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2022-11-15 09:37:43', '2026-03-10 07:52:08'),
(10, '', 'সনাতন চন্দ্র দে', 'উপ-প্রধান রসায়নবিদ', NULL, NULL, 'টিএসপিসিএল', '২০২০-২০২১', 'শুষ্ক মৌসুমে ওয়াসার পানির সাহায্যে ডেমি পানি উৎপাদন', NULL, 'শুষ্ক মৌসুমে ওয়াসার পানির সাহায্যে ডেমি পানি উৎপাদন', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2022-12-14 03:57:44', '2026-03-10 07:52:08'),
(11, '', 'জনাব গোপাল চন্দ্র ঘোষ', 'অতিরিক্ত প্রধান রসায়নবিদ', NULL, NULL, 'এসএফসিএল', '২০২১-২০২২', 'এসএফসিএল এর অ্যামোনিয়া বোতলিং স্টেশনে অ্যামোনিয়া ভেসেলের ইনলেট লাইন মডিফিকেশন', NULL, 'এসএফসিএল এর অ্যামোনিয়া বোতলিং স্টেশনে অ্যামোনিয়া ভেসেলের ইনলেট লাইন মডিফিকেশন', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2022-12-14 04:01:51', '2026-03-10 07:52:08'),
(19, '', 'জনাব মোঃ মহিউদ্দীন', '', NULL, NULL, 'টিএসপিসিএল', '২০২৩-২০২৪', 'টিআইসিআই এর এক্সপার্ট সার্ভিস বিল এর হিসাব বিষয়ক প্রোগ্রাম।', NULL, 'টিআইসিআই এর এক্সপার্ট সার্ভিস বিল এর হিসাব বিষয়ক প্রোগ্রাম।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2026-02-22 07:14:55', '2026-03-10 07:52:08'),
(20, '', 'ছরোয়ার হোসেন রিপন', 'উপ-প্রধান প্রকৌশলী', NULL, NULL, 'এসএফসিএল', '২০২৩-২০২৪', 'SFCL এর ইউরিয়া প্ল্যান্টের উদ্বাবনী কার্যক্রম হিসেবে PCT এ Purified Process Condensate Cooler হিসেবে পূর্বের Plate type Cooler এর পরিবর্তে Shell & Tube Type স্থাপন।', NULL, 'SFCL এর ইউরিয়া প্ল্যান্টের উদ্বাবনী কার্যক্রম হিসেবে PCT এ Purified Process Condensate Cooler হিসেবে পূর্বের Plate type Cooler এর পরিবর্তে Shell & Tube Type স্থাপন।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2026-02-23 08:36:00', '2026-03-10 07:52:08'),
(21, '', 'জনাব মোঃ শাহীন কামাল', 'পরিচালক', NULL, NULL, 'বিসিআইসি প্র: কা:', '২০২০-২০২১', 'কারখানার Important equipment, instruments এবং pipeline মেরামত সংক্রান্ত Job history সংরক্ষণ করা।', NULL, 'বিসিআইসি’র সকল কারখানার অনাকাঙ্খিত শাট-ডাউন, মেনটেন্যান্স কস্ট, উৎপাদন খরচ, দূর্ঘটনা বেড়ে গেছে ও Attainable capacity এবং Efficiency কমে গেছে। কারখানার গুরুত্বপূর্ণ সকল ইকুইপমেন্ট, ইন্সট্রুমেন্ট ও পাইপলাইন মেরামত সংক্রান্ত জব হিস্টোরি সংরক্ষন করা হচ্ছে, ফলে নিরবিছিন্ন উৎপাদন সম্ভব হচ্ছে এবং কারখানা আর্থিকভাবে লাভবান হচ্ছে।\r\n\r\nTCV:নাই\r\nFlowchart: নাই\r\nছবি: আছে। \r\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2026-02-24 05:49:11', '2026-03-10 07:52:08'),
(22, '', 'জনাব মোহাম্মদ আজিজুল হক ', 'উপ-প্রধান প্রকৌশলী', NULL, NULL, 'এসএফসিএল', '২০২১-২০২২', 'এসএফসিএল এর ইউরিয়া গ্রানুলেশন প্ল্যান্টে Crusher Feed Hopper (T- 662) হতে ০১ (এক) টি Overflow Line স্থাপন।', NULL, 'Crusher Feed Hopper () হতে ০১ (এক)টি Outlet Line স্থাপনের পর Crusher Maintenance কাজের সময় Granulation প্ল্যান্ট বন্ধ করার প্রয়োজন হচ্ছে না। যার ফলে Downtime কমিয়ে উৎপাদনের ধারা অব্যাহত রাখা সম্ভব হচ্ছে।\r\n\r\nসময়, ভ্রমন ও ব্যয় সাশ্রয়ঃ Crusher Feed Hopper হতে Overflow লাইনের স্থাপনের পূর্বে Crusher overload হয়ে ঘন ঘন V- Belt ছিড়ে যেত। বর্তমানে লাইনটি স্থাপনের ফলে V-Belt ছেড়া কমে এসেছে। এতে করে Maintenance কাজ কমে এসেছে। এছাড়া পূর্বে Maintenance কাজের সময় গ্রানুলেশন প্ল্যান্ট বন্ধ করার প্রয়োজন হতো। বর্তমানে Maintenance কাজের সময় গ্রানুলেশন প্ল্যান্ট বন্ধ করার প্রয়োজন হয় না। যার ফলে ডাউনটাইম কমিয়ে উৎপাদনের ধারা অব্যাহত রাখা সম্ভব হচ্ছে। Overflow লাইন স্থাপনের পূর্বে Maintenance কাজের জন্য প্রতি মাসে গড়ে ২ বার ৮-১০ ঘন্টা গ্রানুলেশন প্ল্যান্ট বন্ধ থাকতো এতে করে আনুমানিক ৫০০ মে: টন সার কম উৎপাদন হতো যার বাজার মূল্য ৭০ লক্ষ টাকা। সুতরাং Crusher Hopper হতে Overflow লাইন করার ফলে কারখানার প্রতি মাসে আনুমানিক ৭০ লক্ষ টাকা সাশ্রয় হয়েছে।\r\nFlowchart: আছে\r\nছবি : আছে।\r\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2026-02-24 05:53:32', '2026-03-10 07:52:08'),
(23, '', 'জনাব তরিকুল আলম', 'সহকারী প্রকৌশলী', NULL, NULL, 'জেএফসিএল', '২০২২-২০২৩', '3D Printing Technology ব্যবহারের মাধ্যমে High Voltage Panel (6.6kv) এ ব্যবহৃত অকেজো 86 Lockout Relay সমূহ পুন:ব্যবহার যোগ্য করে তোলা।', NULL, 'জেএফসিএল এর Switch gear রুমের High Voltage Panel সমূহে ব্যবহৃত 86 Lockout Relay গুলো দীর্ঘ দিন ব্যবহার হওয়ার ফলে ভেতরে থাকা পিনিয়ন ভেঙে যায় এবং এই পিনিয়নের কোন Spare কিংবা Dimensional Drawing না থাকায়  সম্পূর্ণ Relay Set পরিবর্তন করে নতুন Relay স্থাপন করা ছাড়া কোন বিকল্প থাকে না। 3D Modeling Software ব্যবহার করে ভেঙে যাওয়া পিনিয়নের একটি 3D Model তৈরি করে তা আধুনিক 3D Printing Technology ব্যবহারের মাধ্যমে অকেজো হয়ে যাওয়া Relay গুলো পুন:ব্যবহার যোগ্য করে তোলা হয়। প্রতিটি 86 lock out Relay  প্রায় ৫১,০০০/- টাকা দরে ক্রয় করা হয় এবং ক্রয় করতে ২ থেকে ২.৫ বছর সময় লাগে। 86 lock out Relay তে ব্যবহৃত pinion মাত্র ২০০ টাকায় 3D printing device এর মাধ্যমে তৈরি করে ব্যবহার করা হচ্ছে ফলে 86 lock out Relay ক্রয় বাবদ বিপুল পরিমাণ সময় ও অর্থ সাশ্রয় হচ্ছে।\r\nসময় ভ্রমন ও ব্যয় সাশ্রয়:\r\nবর্তমানে জেএফসিএল-এর ৭টি Sub Station এর High Voltage Panel গুলোতে সর্বমোট ৫৬টি (Transformer -১৫টি, High Voltage Motor -২৬টি, Busbar -১৪টি) ৮৬ Lockout Relay ব্যবহারে আছে। পিনিয়ন ভেঙে অকেজো হয়ে যাওয়ায় এবং Relay গুলোর কোন Spare Unit না থাকায় ২০২০-২০২১ অর্থ বছরে প্রতিটি Relay প্রায় ৫১০০০/- (৪৫৫.৫৫ এইচ) মূল্যে IOTM পদ্ধতিতে ক্রয় করা হয়। যা ছিল ব্যয় বহুল এবং সময় সাপেক্ষ। উল্লেখিত পদ্ধতিতে 3D Model তৈরি (শুধুমাত্র ১ম বার) এবং 3D Printing Technology ব্যবহার করে পিনিয়নটি তৈরি করে প্রতিটি অকেজো Relay কার্যক্ষম করতে খরচ হবে ২০০ (দুইশত টাকা)। এতে Relay প্রতি জেএফসিএল-এর সাশ্রয় হবে প্রায় ৫০,৮০০ ( পঞ্চাশ হাজার আটশত ) টাকা (১ এইচ =১১১.৮৭ টাকা) ।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2026-02-24 07:04:54', '2026-03-10 07:52:08'),
(24, '', 'জনাব মুহাম্মদ আসাদুজ্জামান', 'রসায়নবিদ', NULL, NULL, 'এসএফসিএল', '২০২২-২০২৩', 'ইউরিয়া ও এ্যামোনিয়া প্ল্যান্ট থেকে সরবরাহকৃত Process Condensate লাইনে ওয়াটার কুলার স্থাপন করা।', NULL, 'অ্যামোনিয়া, ইউরিয়া এবং পাওয়ার প্লান্টে উৎপাদিত Process Condensate ও Steam Condensate এর মোট পরিমাণ প্রতি ঘন্টায় (৪২+২৯+২৬+৫)=১০২ টন। এই ১০২ টন Process Condensate ও Steam Condensate একত্রে মিলিত হয়ে ইউটিলিটি প্লান্টে সরবরাহ করা হতো। যার তাপমাত্রা প্রায় ৭০° সেঃ। \r\nউচ্চ তাপমাত্রায় Condensate Raw Water Tank এ প্রেরণ করা হলে Raw Water Tank এর Condensate মিশ্রিত পানির তাপমাত্রা বৃদ্ধি পেয়ে সহনীয় মাত্রার চেয়ে বেশি হয়ে যেত। Raw Water Tank থেকে মিশ্রিত পানি Activated Carbon Filter হয়ে Cation, Anion এবং Mixed Bed Polisher এর রেজিন Damage হয়ে যাওয়ার সম্ভাবনা থাকায় উল্লিখিত ১০২ টন Condensate Drain করা হতো।\r\nস্থাপিত Cooler এর মাধ্যমে প্রতি ঘন্টায় ১০২ টন Process Condensate ঠান্ডা করে Utility Plant এ Raw Water হিসেবে Raw demi water tank এ প্রেরণ করা হয়। প্রতি ঘন্টায় ১০২ টন Process Condensate পূনঃব্যবহারের ফলে Raw Water Intake হ্রাস পেয়েছে। যার উৎপাদন খরচ ৩৬৭২ টাকা। \r\n\r\nসময়, ভ্রমন ও ব্যয় সাশ্রয়:\r\nউদ্যোগের ফলে \r\n\r\nপ্রতি ঘন্টায় ৩৬৭২ টাকা\r\nদৈনিক ৩,৬৭২*২৪ = ৮৮,১২৮ টাকা\r\nমাসিক ৮৮,১২৮*৩০ = ২,৬৪,৩৮৪ টাকা\r\nবার্ষিক ৩৬৭২*৩৩০ = ২,৯০,৮২,২৪০ টাকা সাশ্রয় হচ্ছে।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'যোগ্য', 'প্রত্যাশিত', '', '', 'yes', NULL, NULL, 'final selected', '2026-02-24 07:07:06', '2026-03-10 07:52:08'),
(25, '', 'জনাব সেন সুখেন চন্দ্র', 'মহাব্যবস্থাপক', NULL, NULL, 'টিএসপিসিএল', '২০২২-২০২৩', 'টিএসপি কমপ্লেক্স এর এসএ-২ প্ল্যান্টের সেকেন্ড ইকনোমাইজার আউটলেট গ্যাসের তাপমাত্রা নিয়ন্ত্রণ, ডেমি ওয়াটার ড্রেনরোধসহ মাল্টিপল বেনিফিট অর্জন।', NULL, 'উদ্ভাবনী উদ্যোগের ফলে,\r\nক) ডেমি ওয়াটার ড্রেন করে প্রসেস নিয়ন্ত্রন করতে হয়না বিধায় কারখানার আর্থিকভাবে ব্যাপক সাশ্রয় হচ্ছে। উল্লেখ্য ১ টন ডেমি ওয়াটার তৈরী করতে ৪৭৩ টাকা খরচ হয়।\r\nখ) সেকেন্ড ইকনোমাইজার গ্যাস আউটলেট তাপমাত্রা নিয়ন্ত্রনের জন্য বর্ধিত প্রিহিটেড ডেমি ওয়াটার দানাদার প্ল্যান্টের বয়লারে ব্যবহার করায় সেখানে স্টীম জেনারেশন বৃদ্ধি পেয়েছে এবং ন্যাচারাল গ্যাস (এনজি) এর ব্যবহার হ্রাস পেয়েছে। এতে আর্থিক সাশ্রয় হচ্ছে।\r\nগ) সেকেন্ড ইকনোমাইজার আউটলেট গ্যাসের তাপমাত্রা নিয়ন্ত্রনের ফলে পানিতে সালফার ট্রাই অক্সাইডের অ্যাবজর্পশন বৃদ্ধি পাওয়ায় সালফিউরিক এসিডের উৎপাদন বৃদ্ধি পেয়েছে। এতে কারখানা আর্থিকভাবে লাভবান হচ্ছে।\r\nঘ) সেকেন্ড ইকনোমাইজার আউটলেট গ্যাস তাপমাত্রা নিয়ন্ত্রনের ফলে বায়ুমন্ডলে সালফার-ট্রাই-অক্সাইড গ্যাস নির্গমন হ্রাস পেয়েছে। এতে পরিবেশ দূষণ হ্রাস পেয়েছে।\r\nসময়, ভ্রমন ও ব্যয় সাশ্রয়:\r\nউদ্যোগের ফলে দৈনিক ২০ মেট্রিক টন Drain হওয়া Demi Water সাশ্রয় হচ্ছে যার প্রতি মেট্রিক টন Demi Water উৎপাদনে খরচ হয় ৪৭৬.০০ টাকা । Demi Water পূণঃব্যবহারের ফলে\r\nদৈনিক 		২০*৪৭৬ = ৯,৪৬০ টাকা\r\nমাসিক 		৯,৪৬০*৩০ = ২,৮৬,৮০০ টাকা\r\nবার্ষিক 		২,৮৬,৮০০*১২ = ৩১,২১,৮০০ টাকা সাশ্রয় হচ্ছে।', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'বাস্তবায়িত', 'yes', 'প্রত্যাশিত', '', '', 'yes', '', '', 'final selected', '2026-02-24 07:08:32', '2026-03-10 07:50:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_innovation`
--
ALTER TABLE `tbl_innovation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_innovation`
--
ALTER TABLE `tbl_innovation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
