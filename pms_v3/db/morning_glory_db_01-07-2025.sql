-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: morning_glory_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Tue, 01 Jul 2025 06:08:26 +0200

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
-- Table structure for table `latest_news`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `latest_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `latest_news`
--

LOCK TABLES `latest_news` WRITE;
/*!40000 ALTER TABLE `latest_news` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `latest_news` VALUES (1,'test test test test test test test test te','2025-06-19 10:19:26'),(2,'1','2025-06-19 10:19:37'),(3,'ggggggggggggggggggggggg','2025-06-19 10:19:45'),(4,'	Construction of Steel Structure Buffer Godo','2025-06-22 10:07:02');
/*!40000 ALTER TABLE `latest_news` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `latest_news` with 4 row(s)
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_info`
--

LOCK TABLES `teacher_info` WRITE;
/*!40000 ALTER TABLE `teacher_info` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `teacher_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `teacher_info` with 0 row(s)
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

-- Dump completed on: Tue, 01 Jul 2025 06:08:26 +0200
