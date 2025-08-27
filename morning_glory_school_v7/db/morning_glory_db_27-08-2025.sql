-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: morning_glory_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Wed, 27 Aug 2025 11:36:57 +0200

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `categories` VALUES (2,'21 February','2025-06-03 11:38:48'),(3,'Victory Day 1','2025-06-04 04:06:05'),(4,'Independence Day','2025-06-15 04:37:51'),(5,'Class Party','2025-06-19 10:22:36');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `categories` with 4 row(s)
--

--
-- Table structure for table `fee_mgtm`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_mgtm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `reg_no` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `class` varchar(20) DEFAULT NULL,
  `roll_no` int(11) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `fee_types` text DEFAULT NULL,
  `actual_fee` varchar(100) DEFAULT '0.00',
  `due` varchar(100) DEFAULT '0.00',
  `payment` varchar(100) DEFAULT '0.00',
  `remaining_due` varchar(100) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fee_mgtm`
--

LOCK TABLES `fee_mgtm` WRITE;
/*!40000 ALTER TABLE `fee_mgtm` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `fee_mgtm` VALUES (15,'2025-07-15','456','t','ds',0,'sdf','Admission Fee,Tuition Fee,Exam Fee,Library Fee','100,100,100,100','70.00,70.00,90.00,90.00','30,30,10,10','0.00','2025-07-15 10:00:43','2025-07-16 06:44:03'),(16,'2025-07-16','8000','sssrrrrr','test',645,'sdf','Admission Fee,Transport Fee,Exam Fee,Exam Fee,Transport Fee','100,200,400,600,100','60.00,190.00,300.00,400.00,90.00','40,10,100,200,10','0.00','2025-07-16 04:11:12','2025-07-16 06:41:08'),(17,'2025-07-16','456','test33','ds',453,'sdf','Admission Fee,Tuition Fee','100,200','150,360','30,30','0.00','2025-07-16 08:24:09','2025-07-16 08:24:09');
/*!40000 ALTER TABLE `fee_mgtm` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `fee_mgtm` with 3 row(s)
--

--
-- Table structure for table `fee_mgtm_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_mgtm_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reg_no` (`reg_no`),
  KEY `class` (`class`),
  KEY `section` (`section`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fee_mgtm_tbl`
--

LOCK TABLES `fee_mgtm_tbl` WRITE;
/*!40000 ALTER TABLE `fee_mgtm_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `fee_mgtm_tbl` VALUES (3,'2025-07-31','123','test','Play','1','A','January,February,March,April','100,100,100,100','100,100,100,50',0.00,400.00,350.00,50.00,'1st Term,2nd Term','100,100','100,50',0.00,200.00,150.00,0.00,100.00,100.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,100.00,50.00,50.00,0.00,0.00,0.00,0.00,0.00,0.00,200.00,100.00,100.00,0.00,0.00,0.00,0.00,0.00,0.00,1000.00,750.00,200.00,'2025-07-31 09:02:02','2025-08-04 06:23:01');
/*!40000 ALTER TABLE `fee_mgtm_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `fee_mgtm_tbl` with 1 row(s)
--

--
-- Table structure for table `five_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `five_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `first_term` decimal(5,2) DEFAULT NULL,
  `second_term` decimal(5,2) DEFAULT NULL,
  `third_term` decimal(5,2) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `five_tbl`
--

LOCK TABLES `five_tbl` WRITE;
/*!40000 ALTER TABLE `five_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `five_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `five_tbl` with 0 row(s)
--

--
-- Table structure for table `four_tble`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `four_tble` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `first_term` decimal(5,2) DEFAULT NULL,
  `second_term` decimal(5,2) DEFAULT NULL,
  `third_term` decimal(5,2) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `four_tble`
--

LOCK TABLES `four_tble` WRITE;
/*!40000 ALTER TABLE `four_tble` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `four_tble` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `four_tble` with 0 row(s)
--

--
-- Table structure for table `kg_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kg_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `first_term` decimal(5,2) DEFAULT NULL,
  `second_term` decimal(5,2) DEFAULT NULL,
  `third_term` decimal(5,2) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_tbl`
--

LOCK TABLES `kg_tbl` WRITE;
/*!40000 ALTER TABLE `kg_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `kg_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kg_tbl` with 0 row(s)
--

--
-- Table structure for table `latest_news`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `latest_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `latest_news`
--

LOCK TABLES `latest_news` WRITE;
/*!40000 ALTER TABLE `latest_news` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `latest_news` VALUES (1,'test test test test test test test test te','2025-06-19 10:19:26'),(2,'1','2025-06-19 10:19:37'),(3,'ggggggggggggggggggggggg','2025-06-19 10:19:45'),(4,'	Construction of Steel Structure Buffer Godo','2025-06-22 10:07:02'),(5,'bbbbbbbbbbbbbbbbbbbbbbbbbbbbb','2025-07-02 08:04:44');
/*!40000 ALTER TABLE `latest_news` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `latest_news` with 5 row(s)
--

--
-- Table structure for table `message`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messenger_name` varchar(50) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `message` with 0 row(s)
--

--
-- Table structure for table `notice_board`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice` longtext NOT NULL,
  `filename` varchar(255) NOT NULL,
  `dated` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notice_board`
--

LOCK TABLES `notice_board` WRITE;
/*!40000 ALTER TABLE `notice_board` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `notice_board` VALUES (4,'At the top of index.php, we are including','Scan-295.pdf','0000-00-00','2022-12-13 18:13:12'),(5,'test rr','BCIC PMIS.pdf','2023-12-12','2025-05-26 11:38:12'),(6,'test121','tender notice egp_0001_ea1fWf.pdf','2025-05-28','2025-05-28 10:03:06'),(7,'rrrr','Notice-2_Q01lpp.pdf','2025-06-02','2025-06-16 10:37:22'),(8,'vvvvv','Notice-2.pdf','2025-06-12','2025-06-17 04:03:10'),(9,'564654456poihjijio','01.01.pdf','2025-06-03','2025-06-17 09:43:30'),(10,'bbbbbbbbbbb','Issued_Books_List.pdf','2025-06-03','2025-06-17 10:22:56'),(11,'cbc','certificatepersonel_P4RfZA.pdf','2025-06-12','2025-06-18 10:03:33');
/*!40000 ALTER TABLE `notice_board` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `notice_board` with 8 row(s)
--

--
-- Table structure for table `nursery_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nursery_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `first_term` decimal(5,2) DEFAULT NULL,
  `second_term` decimal(5,2) DEFAULT NULL,
  `third_term` decimal(5,2) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reg_no` (`reg_no`),
  KEY `class` (`class`),
  KEY `section` (`section`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nursery_tbl`
--

LOCK TABLES `nursery_tbl` WRITE;
/*!40000 ALTER TABLE `nursery_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `nursery_tbl` VALUES (1,'',NULL,NULL,NULL,'2025-08-06','234',NULL,'Nursery','5','C',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-06 05:34:28','2025-08-07 10:01:55'),(2,'',NULL,NULL,NULL,'2025-08-06','8000',NULL,'Nursery','645','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-06 05:52:40','2025-08-06 05:52:40'),(4,'',NULL,NULL,NULL,'2025-08-06','555','Inactive','Nursery','64','B',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-06 07:29:31','2025-08-06 07:42:52'),(5,'',NULL,NULL,NULL,'2025-08-06','666','Active','Nursery','1','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-06 09:30:50','2025-08-06 09:30:50'),(7,'',NULL,NULL,NULL,'2025-08-06','456','Active','Nursery','6','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-06 09:51:34','2025-08-06 09:51:34'),(9,'',NULL,NULL,NULL,'2025-08-07','333','Inactive','Nursery','1','B',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-07 08:29:05','2025-08-12 06:06:01');
/*!40000 ALTER TABLE `nursery_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `nursery_tbl` with 6 row(s)
--

--
-- Table structure for table `one_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `one_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `first_term` decimal(5,2) DEFAULT NULL,
  `second_term` decimal(5,2) DEFAULT NULL,
  `third_term` decimal(5,2) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `one_tbl`
--

LOCK TABLES `one_tbl` WRITE;
/*!40000 ALTER TABLE `one_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `one_tbl` VALUES (1,'',NULL,NULL,NULL,'2025-08-12','555','Active','One','7','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-12 05:56:07','2025-08-12 05:56:07');
/*!40000 ALTER TABLE `one_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `one_tbl` with 1 row(s)
--

--
-- Table structure for table `photo_gallary`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo_gallary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category` varchar(200) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `sub_category` varchar(100) NOT NULL,
  `publish_date` date NOT NULL,
  `publish_month` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photo_gallary`
--

LOCK TABLES `photo_gallary` WRITE;
/*!40000 ALTER TABLE `photo_gallary` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `photo_gallary` VALUES (1,'','dir pi.jpg',0,'Pohela Boishakh',0,'','2023-01-24','January','2023-01-25 07:00:28'),(2,'Test','2022-12-29-04-15-5eb68367a89371773eb8f3dd98b7e3d7.jpeg',0,'Victory Day',0,'','2023-01-18','March','2023-01-25 07:05:54'),(3,'test','fsa.jpg',0,'Victory Day',0,'16th December\'2022','2025-05-28','March','2025-05-28 10:40:01'),(5,'yyyy','fcbcbc00-f778-4fcd-be4f-e14f721e6201_removalai_preview.png',3,'',5,'','2025-06-03','October','2025-06-04 05:21:19'),(6,'tttttt','WhatsApp Image 2025-04-10 at 12.16.54 PM.jpeg',0,'',0,'','2025-06-01','August','2025-06-04 05:26:05'),(7,'eeeeeee','maksuda.jpg',3,'Victory Day 1',5,'','2025-06-11','October','2025-06-04 05:29:05'),(8,'uuuu','Addl Sec Nuruzzaman.jpg',3,'Victory Day 1',5,'','2025-06-02','October','2025-06-04 05:41:20'),(9,'rrrrrr','2024-11-25-03-20-6970cb849639f1f42bf7c89f6b73c130.jpeg',3,'Victory Day 1',5,'Victory Day\' 2021','2025-06-02','December','2025-06-04 05:53:17'),(10,'mmmmmmm','13.jpg',3,'Victory Day 1',5,'','2025-06-03','March','2025-06-04 05:55:55'),(12,'cccccc','WhatsApp Image 2025-05-19 at 10.06.23 AM.jpeg',2,'21 February',4,'21 February\' 2020','2020-06-01','January','2025-06-04 08:59:55'),(13,'wwwwwwwwwww','WhatsApp Image 2025-05-27 at 12.40.27 PM.jpeg',2,'21 February',4,'21 February\' 2020','2025-06-02','September','2025-06-04 09:00:28'),(14,'bbbb','WhatsApp Image 2025-06-12 at 9.50.20 PM.jpeg',2,'21 February',4,'21 February\' 2020','2025-06-03','February','2025-06-15 04:36:36'),(15,'ffff','WhatsApp Image 2025-06-12 at 9.50.21 PM.jpeg',4,'Independence Day',6,'Independence Day 2020','2025-06-04','January','2025-06-15 04:39:15'),(17,'fff','WhatsApp Image 2025-06-12 at 9.50.20 PM(1).jpeg',4,'Independence Day',6,'Independence Day 2020','2025-06-13','July','2025-06-15 04:51:37'),(18,'fff','WhatsApp Image 2025-06-12 at 9.50.20 PM.jpeg',4,'Independence Day',6,'Independence Day 2020','2025-06-13','July','2025-06-15 04:51:37'),(19,'fff','WhatsApp Image 2025-06-12 at 9.50.19 PM.jpeg',4,'Independence Day',6,'Independence Day 2020','2025-06-13','July','2025-06-15 04:51:37'),(20,'fff','WhatsApp Image 2025-06-12 at 9.50.18 PM(1).jpeg',4,'Independence Day',6,'Independence Day 2020','2025-06-13','July','2025-06-15 04:51:37'),(21,'fff','WhatsApp Image 2025-06-12 at 9.50.18 PM.jpeg',4,'Independence Day',6,'Independence Day 2020','2025-06-13','July','2025-06-15 04:51:37'),(22,'qqqqq123','masud.jpg',4,'Independence Day',6,'Independence Day 2020','2025-06-03','March','2025-06-15 05:10:05'),(23,'qqqqq123','2023-04-16-06-21-c06eb73f99118202eee4b5ff90c30ec8.jpg',4,'Independence Day',6,'Independence Day 2020','2025-06-03','March','2025-06-15 05:10:05'),(24,'nmnm','2024-08-13-04-48-22083f06db9095e0fa51537e55041714.png',4,'Independence Day',7,'Independence Day 2021','2025-06-09','October','2025-06-15 05:13:31'),(25,'nmnm','2024-09-15-03-33-8be477f1b498b65adfa25927eaac2068.jpeg',4,'Independence Day',7,'Independence Day 2021','2025-06-09','October','2025-06-15 05:13:31'),(26,'wwwwwwwwww','fsa.jpg',2,'21 February',4,'21 February\' 2020','2025-06-09','July','2025-06-19 10:13:52');
/*!40000 ALTER TABLE `photo_gallary` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `photo_gallary` with 23 row(s)
--

--
-- Table structure for table `play_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `play_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `subject` varchar(50) NOT NULL,
  `first_term` varchar(50) NOT NULL,
  `second_term` varchar(50) NOT NULL,
  `third_term` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reg_no` (`reg_no`),
  KEY `class` (`class`),
  KEY `section` (`section`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `play_tbl`
--

LOCK TABLES `play_tbl` WRITE;
/*!40000 ALTER TABLE `play_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `play_tbl` VALUES (2,'2025-08-06','789','Active','Play','33','B',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1,2,3','80,80,90','90,80,60','60,70,90','2025-08-06 06:59:12','2025-08-18 08:20:29'),(5,'2025-08-06','666','Inactive','Play','1','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','2025-08-06 09:30:17','2025-08-06 09:30:50'),(7,'2025-08-07','333','Inactive','Play','1','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','2025-08-07 08:27:59','2025-08-07 08:29:05');
/*!40000 ALTER TABLE `play_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `play_tbl` with 3 row(s)
--

--
-- Table structure for table `scrolling`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scrolling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scrolling_news` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scrolling`
--

LOCK TABLES `scrolling` WRITE;
/*!40000 ALTER TABLE `scrolling` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `scrolling` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `scrolling` with 0 row(s)
--

--
-- Table structure for table `std_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `std_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `std_name` varchar(100) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `section` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `fathers_name` varchar(100) DEFAULT NULL,
  `mothers_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `status` enum('TC','Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `std_tbl`
--

LOCK TABLES `std_tbl` WRITE;
/*!40000 ALTER TABLE `std_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `std_tbl` VALUES (1,'test','123','3','Play','B','2021-01-01','1312341241243124','tewt','tewst','test','uploads/students/student_688883666f4bd.jpg','TC','2025-07-29 08:03:30','2025-08-10 06:30:01'),(2,'test','234','5','Nursery','C','2025-07-29','1312341241243124','tewt','tewst','','uploads/students/student_6892ddfa68b58.jpeg','Active','2025-08-06 04:45:46','2025-08-07 10:01:55'),(3,'test','8000','645','Nursery','A','2025-08-06','1312341241243124','','','','uploads/students/student_6892eda8bd9f8.jpeg','Active','2025-08-06 05:52:40','2025-08-06 05:52:40'),(4,'xx','789','33','Play','B','2025-08-06','1312341241243124','tewt','tewst','','uploads/students/student_689328753e496.png','Active','2025-08-06 06:57:53','2025-08-11 09:17:49'),(5,'xxx','555','7','One','A','2025-07-28','1312341241243124','tewt','tewst','','uploads/students/student_68930414ba1f6.jpeg','Active','2025-08-06 07:28:20','2025-08-12 05:56:07'),(6,'yyy','666','1','Nursery','A','2025-07-29','','','','','uploads/students/student_68932500976eb.jpg','Active','2025-08-06 09:30:17','2025-08-06 09:48:48'),(7,'ttt','456','6','Nursery','A','2025-07-28','1312341241243124','','','','uploads/students/student_68932727047be.jpeg','Active','2025-08-06 09:50:01','2025-08-06 09:57:59'),(8,'fff','333','1','Nursery','B','2025-07-29','','','','','uploads/students/student_6894635ecc57d.jpeg','Inactive','2025-08-07 08:27:10','2025-08-12 06:06:01');
/*!40000 ALTER TABLE `std_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `std_tbl` with 8 row(s)
--

--
-- Table structure for table `student_exam_fees`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_exam_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `roll_no` varchar(20) NOT NULL,
  `std_name` varchar(100) NOT NULL,
  `class` varchar(20) NOT NULL,
  `section` varchar(10) NOT NULL,
  `month` varchar(15) NOT NULL,
  `year` int(11) NOT NULL,
  `exam_fee` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_exam_fees`
--

LOCK TABLES `student_exam_fees` WRITE;
/*!40000 ALTER TABLE `student_exam_fees` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `student_exam_fees` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `student_exam_fees` with 0 row(s)
--

--
-- Table structure for table `subjects`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(50) NOT NULL,
  `class` varchar(100) NOT NULL,
  `sub_code` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `subjects` VALUES (1,'Bangla','Play',0),(2,'English','Play',0),(3,'Math','Play',0),(4,'Social Science','Nursery',1),(5,'Science','One',11),(6,'Math','One',11),(7,'Bangla','KG',2);
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `subjects` with 7 row(s)
--

--
-- Table structure for table `sub_categories`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `sub_category_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sub_categories` VALUES (4,2,'21 February\' 2020','2025-06-04 04:06:16'),(5,3,'Victory Day\' 2021','2025-06-04 04:06:36'),(6,4,'Independence Day 2020','2025-06-15 04:38:15'),(7,4,'Independence Day 2021','2025-06-15 05:12:59'),(8,5,'Class Party 2021','2025-06-19 10:23:35');
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sub_categories` with 5 row(s)
--

--
-- Table structure for table `tblusers`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `Username` varchar(120) DEFAULT NULL,
  `UserEmail` varchar(200) DEFAULT NULL,
  `Password` varchar(250) DEFAULT NULL,
  `RegDate` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblusers`
--

LOCK TABLES `tblusers` WRITE;
/*!40000 ALTER TABLE `tblusers` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblusers` VALUES (2,'abdul','admin','bootstrapfriendly@gmail.com','202cb962ac59075b964b07152d234b70','2020-10-23 16:03:33'),(6,'ramesh','ramesh','ramesh@gmail.com','827ccb0eea8a706c4c34a16891f84e7b',NULL),(7,'ramesh','ramesh2','ramesh@gmail2.com','827ccb0eea8a706c4c34a16891f84e7b',NULL);
/*!40000 ALTER TABLE `tblusers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblusers` with 3 row(s)
--

--
-- Table structure for table `teacher_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_info`
--

LOCK TABLES `teacher_info` WRITE;
/*!40000 ALTER TABLE `teacher_info` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `teacher_info` VALUES (2,'test33','Assistant Manager','2025-06-30','2025-07-06','dd','fsdasdf','dfsfdsdfsa','emran445@yahoo.com','','','','tewt','tewtrr','1751444331_b3.jpg'),(3,'test33','Assistant Manager','2025-06-30','2025-07-06','dd','fsdasdf','dfsfdsdfsa','emran445@yahoo.com','','','','tewt','tewtrr','1751449201_b4.jpg'),(5,'test','test','2025-06-30','2025-07-08','test','test','test','emran445@yahoo.com','','','','test','test','1751449244_WhatsApp Image 2025-07-02 at 1.41.04 PM(1).jpeg'),(6,'rafik','AP','2014-02-28','2025-07-31','test','SUST','2423','emran445@yahoo.com','','','','','test','1756204002_2023-04-26-08-08-834241d19b37b479e7b5ec0f81c555fd.jpg');
/*!40000 ALTER TABLE `teacher_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `teacher_info` with 4 row(s)
--

--
-- Table structure for table `three_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `three_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `first_term` decimal(5,2) DEFAULT NULL,
  `second_term` decimal(5,2) DEFAULT NULL,
  `third_term` decimal(5,2) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `three_tbl`
--

LOCK TABLES `three_tbl` WRITE;
/*!40000 ALTER TABLE `three_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `three_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `three_tbl` with 0 row(s)
--

--
-- Table structure for table `two_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `two_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `first_term` decimal(5,2) DEFAULT NULL,
  `second_term` decimal(5,2) DEFAULT NULL,
  `third_term` decimal(5,2) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `tution_months` varchar(100) DEFAULT NULL,
  `tution_fee_actual` varchar(50) DEFAULT NULL,
  `tution_fee_pay` varchar(50) DEFAULT NULL,
  `tution_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `tution_total_actual` decimal(10,2) DEFAULT NULL,
  `tution_total_pay` decimal(10,2) DEFAULT NULL,
  `tution_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_terms` varchar(100) DEFAULT NULL,
  `exam_fee_actual` varchar(50) DEFAULT NULL,
  `exam_fee_pay` varchar(50) DEFAULT NULL,
  `exam_fee_remaining_due` decimal(10,2) DEFAULT NULL,
  `exam_total_actual` decimal(10,2) DEFAULT NULL,
  `exam_total_pay` decimal(10,2) DEFAULT NULL,
  `exam_total_remaining_due` decimal(10,2) DEFAULT NULL,
  `admission_fee_actual` decimal(10,2) DEFAULT NULL,
  `admission_fee_pay` decimal(10,2) DEFAULT NULL,
  `admission_fee_due` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_actual` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_pay` decimal(10,2) DEFAULT NULL,
  `board_reg_fee_due` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_actual` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_pay` decimal(10,2) DEFAULT NULL,
  `scholarship_fee_due` decimal(10,2) DEFAULT NULL,
  `transfer_fee_actual` decimal(10,2) DEFAULT NULL,
  `transfer_fee_pay` decimal(10,2) DEFAULT NULL,
  `transfer_fee_due` decimal(10,2) DEFAULT NULL,
  `late_fee_actual` decimal(10,2) DEFAULT NULL,
  `late_fee_pay` decimal(10,2) DEFAULT NULL,
  `late_fee_due` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_actual` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_pay` decimal(10,2) DEFAULT NULL,
  `annual_session_fee_due` decimal(10,2) DEFAULT NULL,
  `books_actual` decimal(10,2) DEFAULT NULL,
  `books_pay` decimal(10,2) DEFAULT NULL,
  `books_due` decimal(10,2) DEFAULT NULL,
  `khata_actual` decimal(10,2) DEFAULT NULL,
  `khata_pay` decimal(10,2) DEFAULT NULL,
  `khata_due` decimal(10,2) DEFAULT NULL,
  `diary_actual` decimal(10,2) DEFAULT NULL,
  `diary_pay` decimal(10,2) DEFAULT NULL,
  `diary_due` decimal(10,2) DEFAULT NULL,
  `stationary_printing_actual` decimal(10,2) DEFAULT NULL,
  `stationary_printing_pay` decimal(10,2) DEFAULT NULL,
  `stationary_printing_due` decimal(10,2) DEFAULT NULL,
  `poor_fund_actual` decimal(10,2) DEFAULT NULL,
  `poor_fund_pay` decimal(10,2) DEFAULT NULL,
  `poor_fund_due` decimal(10,2) DEFAULT NULL,
  `others_actual` decimal(10,2) DEFAULT NULL,
  `others_pay` decimal(10,2) DEFAULT NULL,
  `others_due` decimal(10,2) DEFAULT NULL,
  `total_actual` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `total_due` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `two_tbl`
--

LOCK TABLES `two_tbl` WRITE;
/*!40000 ALTER TABLE `two_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `two_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `two_tbl` with 0 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Wed, 27 Aug 2025 11:36:57 +0200
