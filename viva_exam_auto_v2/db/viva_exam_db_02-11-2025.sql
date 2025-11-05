-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: viva_exam_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Sun, 02 Nov 2025 11:16:03 +0100

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
-- Table structure for table `candidates_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidates_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roll_no` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `fathers_name` varchar(255) DEFAULT NULL,
  `mothers_name` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `ssc` varchar(100) DEFAULT NULL,
  `hsc` varchar(100) DEFAULT NULL,
  `honors` varchar(100) DEFAULT NULL,
  `masters` varchar(100) DEFAULT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `written_marks` decimal(5,2) DEFAULT NULL,
  `viva_marks` decimal(5,2) DEFAULT NULL,
  `committe_name` varchar(255) NOT NULL,
  `status` enum('Pending','Passed','Failed','Selected') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidates_tbl`
--

LOCK TABLES `candidates_tbl` WRITE;
/*!40000 ALTER TABLE `candidates_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `candidates_tbl` VALUES (1,'2','test3','ff','gh','Jessore','2025-09-11','4','4','3.50','3','Assistant Programmer',1.00,10.00,'Com-2','Pending','','uploads/2023-05-09-04-29-0f3135b33cf31f79283ac8797b61be5e.jpg','2025-10-27 09:33:54','2025-10-28 04:21:44'),(2,'1','Can 1','ff','fd','Dhaka','2025-10-01','3','4','3','2','Assistant Manager',40.00,10.00,'Com-1','Pending','test','uploads/2023-04-18-08-38-3b9d55bbec6774b329ef98f116b2eba0.jpg','2025-10-27 09:49:08','2025-10-29 08:04:04'),(3,'3','Can 1','ff','sdfs','Dhaka','2025-10-02','3','3','3.47','3','Assistant Engineer (EEE)',50.00,0.00,'Com-4','Pending','gh','uploads/2024-09-15-03-33-8be477f1b498b65adfa25927eaac2068.jpeg','2025-10-27 09:51:18','2025-10-30 10:30:32'),(4,'4','Can 2','dfs','sdf','Gazipur','2025-08-05','4','5','3.15','3','Assistant Manager',60.00,0.00,'Com-4','Pending','yy','uploads/2024-08-13-04-48-22083f06db9095e0fa51537e55041714.png','2025-10-27 09:51:18','2025-10-30 10:30:37');
/*!40000 ALTER TABLE `candidates_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `candidates_tbl` with 4 row(s)
--

--
-- Table structure for table `committee_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `committee_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `committe_name` varchar(255) NOT NULL,
  `ref_no` varchar(100) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `office_ministry` varchar(255) DEFAULT NULL,
  `division` varchar(150) DEFAULT NULL,
  `type` enum('Chairman','Member Secretary','Member') DEFAULT 'Member',
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT '123',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `committee_tbl`
--

LOCK TABLES `committee_tbl` WRITE;
/*!40000 ALTER TABLE `committee_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `committee_tbl` VALUES (26,'2025-10-22','Com-2','36.01.0000.126.25.131.25.727','',NULL,'453','a','SST','MOI','COP','Chairman',NULL,'123','Active','ssss','2025-10-28 04:29:59','2025-10-28 04:29:59'),(33,'2025-10-20','Com-1','36.01.0000.117.19.bb.2024.74','',NULL,'55','z','SST 1','BCIC','ICT','Chairman',NULL,'123','Active','T','2025-10-29 07:53:34','2025-10-29 07:53:34'),(34,'2025-10-20','Com-1','36.01.0000.117.19.bb.2024.74','',NULL,'4344','x','COP','MOI','COP','Member Secretary',NULL,'123','Active','T','2025-10-29 07:53:34','2025-10-29 07:53:34'),(35,'2025-10-20','Com-1','36.01.0000.117.19.bb.2024.74','',NULL,'4344','y','SST','MOI','tt','Member',NULL,'123','Active','T','2025-10-29 07:53:34','2025-10-29 07:53:34'),(42,'2025-10-16','Com-3','36.01.0000.117.19.bb.2024.70','',NULL,'77','l','Assistant Manager','MOI','COPV','Member',NULL,'123','Active','YY','2025-10-29 07:56:18','2025-10-29 07:56:18'),(43,'2025-10-16','Com-3','36.01.0000.117.19.bb.2024.70','',NULL,'77','k','SST 1','MOI','COP','Member Secretary',NULL,'123','Active','YY','2025-10-29 07:56:18','2025-10-29 07:56:18'),(44,'2025-10-16','Com-3','36.01.0000.117.19.bb.2024.70','',NULL,'4344','j','SST','MOI','COPb','Chairman',NULL,'123','Active','YY','2025-10-29 07:56:18','2025-10-29 07:56:18'),(45,'2025-10-17','Com-4','36.01.0000.117.19.bb.2024.70','uploads/Request-148093.pdf',NULL,'554','f','SST 1','MOI','COP','Chairman',NULL,'123','Active','','2025-10-29 07:59:28','2025-10-29 07:59:28'),(46,'2025-10-17','Com-4','36.01.0000.117.19.bb.2024.70','uploads/Request-148093.pdf',NULL,'655','g','SST','MOI','ICT','Member Secretary',NULL,'123','Active','','2025-10-29 07:59:28','2025-10-29 07:59:28'),(47,'2025-10-17','Com-4','36.01.0000.117.19.bb.2024.70','uploads/Request-148093.pdf',NULL,'645','h','Assistant Manager','BCIC','ICT','Member',NULL,'123','Active','','2025-10-29 07:59:28','2025-10-29 07:59:28'),(54,'2025-10-28','Com-5','36.01.0000.126.25.131.25.727','',NULL,'4344','q','SST','MOI','ICT','Member Secretary',NULL,'123','Active','sfs','2025-10-29 09:58:48','2025-10-29 09:58:48'),(55,'2025-10-28','Com-5','36.01.0000.126.25.131.25.727','',NULL,'4344','w','Assistant Manager','BCIC','ICT','Member Secretary',NULL,'123','Active','sfs','2025-10-29 09:58:48','2025-10-29 09:58:48'),(56,'2025-10-28','Com-5','36.01.0000.126.25.131.25.727','',NULL,'55','e','SST 1','BCIC','COP','Member',NULL,'123','Active','sfs','2025-10-29 09:58:48','2025-10-29 09:58:48'),(57,'2025-10-29','Com-6','36.01.0000.117.19.bb.2024.70','',NULL,'4344444','p','Assistant Manager','BCIC','ICT','Chairman',NULL,'123','Active','','2025-10-29 09:59:26','2025-10-29 09:59:26'),(58,'2025-10-29','Com-6','36.01.0000.117.19.bb.2024.70','',NULL,'45368','o','SST 1','BCIC','COP','Member Secretary',NULL,'123','Active','','2025-10-29 09:59:26','2025-10-29 09:59:26'),(59,'2025-10-30','Com-7','36.01.0000.117.19.bb.2024.72','',NULL,'4344','u','SST','MOI','COP','Chairman',NULL,'123','Active','','2025-10-29 10:04:24','2025-10-29 10:04:24'),(60,'2025-10-23','Com-8','','',NULL,'','v','','','','Chairman',NULL,'123','Active','','2025-10-29 10:35:02','2025-10-29 10:35:02'),(61,'2025-10-23','Com-8','','',NULL,'','v','','','','Member',NULL,'123','Active','','2025-10-29 10:35:02','2025-10-29 10:35:02');
/*!40000 ALTER TABLE `committee_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `committee_tbl` with 18 row(s)
--

--
-- Table structure for table `create_exam_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `create_exam_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `marks` decimal(5,2) DEFAULT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `status` enum('Pending','Ongoing','Completed','Cancelled') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `create_exam_tbl`
--

LOCK TABLES `create_exam_tbl` WRITE;
/*!40000 ALTER TABLE `create_exam_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `create_exam_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `create_exam_tbl` with 0 row(s)
--

--
-- Table structure for table `exam_schedule_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_schedule_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `marks` decimal(5,2) NOT NULL,
  `title` text NOT NULL,
  `committe_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_schedule_tbl`
--

LOCK TABLES `exam_schedule_tbl` WRITE;
/*!40000 ALTER TABLE `exam_schedule_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `exam_schedule_tbl` VALUES (1,'2025-10-28','14:00:00',20.00,'সহকারী রসায়নবিদ পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের এসএসসি-৩ কর্তৃক প্রদত্ত নম্বর শীট।','Com-4','2025-10-28 09:42:39','2025-10-29 09:26:43'),(2,'2025-10-25','10:10:00',20.00,'Test','Com-2','2025-10-28 10:32:05','2025-10-28 10:32:05'),(3,'2025-10-29','14:00:00',20.00,'test','Com-5','2025-10-29 09:48:48','2025-10-29 09:48:48');
/*!40000 ALTER TABLE `exam_schedule_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `exam_schedule_tbl` with 3 row(s)
--

--
-- Table structure for table `log_table`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `event_type` enum('login','logout') NOT NULL DEFAULT 'login',
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success',
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `log_table` VALUES (1,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-14 10:01:15',NULL,NULL,'2025-10-14 08:01:15','2025-10-14 08:01:15'),(2,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-14 10:02:54',NULL,NULL,'2025-10-14 08:02:54','2025-10-14 08:02:54'),(3,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-14 10:05:23',NULL,NULL,'2025-10-14 08:05:23','2025-10-14 08:05:23'),(4,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-14 10:05:47',NULL,NULL,'2025-10-14 08:05:47','2025-10-14 08:05:47'),(5,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-14 10:05:55',NULL,NULL,'2025-10-14 08:05:55','2025-10-14 08:05:55'),(6,'user','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-14 14:07:11',NULL,NULL,'2025-10-14 08:07:11','2025-10-14 08:07:11'),(7,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-14 10:08:14',NULL,NULL,'2025-10-14 08:08:14','2025-10-14 08:08:14'),(8,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-14 10:08:21',NULL,NULL,'2025-10-14 08:08:21','2025-10-14 08:08:21'),(9,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-14 14:08:28',NULL,NULL,'2025-10-14 08:08:28','2025-10-14 08:08:28'),(10,'dsfs','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-14 10:08:32',NULL,NULL,'2025-10-14 08:08:32','2025-10-14 08:08:32'),(11,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-14 14:12:42',NULL,NULL,'2025-10-14 08:12:42','2025-10-14 08:12:42'),(12,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-15 12:42:06',NULL,NULL,'2025-10-15 06:42:06','2025-10-15 06:42:06'),(13,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-15 12:57:26',NULL,NULL,'2025-10-15 06:57:26','2025-10-15 06:57:26'),(14,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-15 12:57:34',NULL,NULL,'2025-10-15 06:57:34','2025-10-15 06:57:34'),(15,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-16 09:35:11',NULL,NULL,'2025-10-16 03:35:11','2025-10-16 03:35:11'),(16,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-19 09:58:21',NULL,NULL,'2025-10-19 03:58:21','2025-10-19 03:58:21'),(17,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-19 09:58:28',NULL,NULL,'2025-10-19 03:58:28','2025-10-19 03:58:28'),(18,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-20 09:25:04',NULL,NULL,'2025-10-20 03:25:04','2025-10-20 03:25:04'),(19,'user','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-21 09:18:46',NULL,NULL,'2025-10-21 03:18:46','2025-10-21 03:18:46'),(20,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-21 09:18:53',NULL,NULL,'2025-10-21 03:18:53','2025-10-21 03:18:53'),(21,'sadmin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-21 09:19:01',NULL,NULL,'2025-10-21 03:19:01','2025-10-21 03:19:01'),(22,'emran','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-21 09:19:05',NULL,NULL,'2025-10-21 03:19:05','2025-10-21 03:19:05'),(23,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-21 09:20:08',NULL,NULL,'2025-10-21 03:20:08','2025-10-21 03:20:08'),(24,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-22 10:07:27',NULL,NULL,'2025-10-22 04:07:27','2025-10-22 04:07:27'),(25,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-22 10:07:34',NULL,NULL,'2025-10-22 04:07:35','2025-10-22 04:07:35'),(26,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-23 09:15:15',NULL,NULL,'2025-10-23 03:15:15','2025-10-23 03:15:15'),(27,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 09:42:32',NULL,NULL,'2025-10-26 03:42:32','2025-10-26 03:42:32'),(28,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 09:43:00',NULL,NULL,'2025-10-26 03:43:00','2025-10-26 03:43:00'),(29,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 09:43:05',NULL,NULL,'2025-10-26 03:43:05','2025-10-26 03:43:05'),(30,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 09:45:26',NULL,NULL,'2025-10-26 03:45:26','2025-10-26 03:45:26'),(31,'jfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 09:45:32',NULL,NULL,'2025-10-26 03:45:32','2025-10-26 03:45:32'),(32,'jfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:10:13',NULL,NULL,'2025-10-26 04:10:13','2025-10-26 04:10:13'),(33,'jfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:10:18',NULL,NULL,'2025-10-26 04:10:19','2025-10-26 04:10:19'),(34,'jfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:12:08',NULL,NULL,'2025-10-26 04:12:08','2025-10-26 04:12:08'),(35,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:12:11',NULL,NULL,'2025-10-26 04:12:11','2025-10-26 04:12:11'),(36,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:12:24',NULL,NULL,'2025-10-26 04:12:24','2025-10-26 04:12:24'),(37,'jfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:12:28',NULL,NULL,'2025-10-26 04:12:28','2025-10-26 04:12:28'),(38,'jfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:14:15',NULL,NULL,'2025-10-26 04:14:15','2025-10-26 04:14:15'),(39,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:14:19',NULL,NULL,'2025-10-26 04:14:19','2025-10-26 04:14:19'),(40,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:15:19',NULL,NULL,'2025-10-26 04:15:19','2025-10-26 04:15:19'),(41,'jfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:15:24',NULL,NULL,'2025-10-26 04:15:24','2025-10-26 04:15:24'),(42,'jfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:15:50',NULL,NULL,'2025-10-26 04:15:50','2025-10-26 04:15:50'),(43,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:15:53',NULL,NULL,'2025-10-26 04:15:53','2025-10-26 04:15:53'),(44,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:17:04',NULL,NULL,'2025-10-26 04:17:04','2025-10-26 04:17:04'),(45,'jfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:17:09',NULL,NULL,'2025-10-26 04:17:09','2025-10-26 04:17:09'),(46,'jfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:34:56',NULL,NULL,'2025-10-26 04:34:56','2025-10-26 04:34:56'),(47,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:35:01',NULL,NULL,'2025-10-26 04:35:01','2025-10-26 04:35:01'),(48,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:35:43',NULL,NULL,'2025-10-26 04:35:43','2025-10-26 04:35:43'),(49,'jfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:35:49',NULL,NULL,'2025-10-26 04:35:49','2025-10-26 04:35:49'),(50,'jfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:43:09',NULL,NULL,'2025-10-26 04:43:09','2025-10-26 04:43:09'),(51,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:43:14',NULL,NULL,'2025-10-26 04:43:14','2025-10-26 04:43:14'),(52,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:47:36',NULL,NULL,'2025-10-26 04:47:36','2025-10-26 04:47:36'),(53,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:47:47',NULL,NULL,'2025-10-26 04:47:47','2025-10-26 04:47:47'),(54,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:49:21',NULL,NULL,'2025-10-26 04:49:21','2025-10-26 04:49:21'),(55,'jfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 10:49:25',NULL,NULL,'2025-10-26 04:49:25','2025-10-26 04:49:25'),(56,'jfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 13:43:43',NULL,NULL,'2025-10-26 07:43:43','2025-10-26 07:43:43'),(57,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 13:43:47',NULL,NULL,'2025-10-26 07:43:47','2025-10-26 07:43:47'),(58,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 14:51:15',NULL,NULL,'2025-10-26 08:51:15','2025-10-26 08:51:15'),(59,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 14:51:19',NULL,NULL,'2025-10-26 08:51:19','2025-10-26 08:51:19'),(60,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 16:02:43',NULL,NULL,'2025-10-26 10:02:43','2025-10-26 10:02:43'),(61,'sfcl','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 16:02:49',NULL,NULL,'2025-10-26 10:02:49','2025-10-26 10:02:49'),(62,'sfcl','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 16:04:43',NULL,NULL,'2025-10-26 10:04:43','2025-10-26 10:04:43'),(63,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-26 16:04:47',NULL,NULL,'2025-10-26 10:04:47','2025-10-26 10:04:47'),(64,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 15:58:09',NULL,NULL,'2025-10-27 09:58:09','2025-10-27 09:58:09'),(65,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 15:58:17',NULL,NULL,'2025-10-27 09:58:17','2025-10-27 09:58:17'),(66,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 15:59:02',NULL,NULL,'2025-10-27 09:59:02','2025-10-27 09:59:02'),(67,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 15:59:21',NULL,NULL,'2025-10-27 09:59:21','2025-10-27 09:59:21'),(68,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:00:08',NULL,NULL,'2025-10-27 10:00:08','2025-10-27 10:00:08'),(69,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:00:12',NULL,NULL,'2025-10-27 10:00:12','2025-10-27 10:00:12'),(70,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:00:56',NULL,NULL,'2025-10-27 10:00:56','2025-10-27 10:00:56'),(71,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:00:59',NULL,NULL,'2025-10-27 10:00:59','2025-10-27 10:00:59'),(72,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:03:48',NULL,NULL,'2025-10-27 10:03:48','2025-10-27 10:03:48'),(73,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:03:53',NULL,NULL,'2025-10-27 10:03:53','2025-10-27 10:03:53'),(74,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:06:25',NULL,NULL,'2025-10-27 10:06:25','2025-10-27 10:06:25'),(75,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:07:01',NULL,NULL,'2025-10-27 10:07:01','2025-10-27 10:07:01'),(76,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:07:56',NULL,NULL,'2025-10-27 10:07:56','2025-10-27 10:07:56'),(77,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:08:00',NULL,NULL,'2025-10-27 10:08:00','2025-10-27 10:08:00'),(78,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:08:01',NULL,NULL,'2025-10-27 10:08:01','2025-10-27 10:08:01'),(79,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:08:06',NULL,NULL,'2025-10-27 10:08:06','2025-10-27 10:08:06'),(80,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:08:14',NULL,NULL,'2025-10-27 10:08:14','2025-10-27 10:08:14'),(81,'y','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:08:17',NULL,NULL,'2025-10-27 10:08:17','2025-10-27 10:08:17'),(82,'y','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:40:52',NULL,NULL,'2025-10-27 10:40:52','2025-10-27 10:40:52'),(83,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:40:56',NULL,NULL,'2025-10-27 10:40:57','2025-10-27 10:40:57'),(84,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-27 16:48:48',NULL,NULL,'2025-10-27 10:48:48','2025-10-27 10:48:48'),(85,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 09:53:25',NULL,NULL,'2025-10-28 03:53:25','2025-10-28 03:53:25'),(86,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 09:53:33',NULL,NULL,'2025-10-28 03:53:33','2025-10-28 03:53:33'),(87,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 09:53:37',NULL,NULL,'2025-10-28 03:53:37','2025-10-28 03:53:37'),(88,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:17:03',NULL,NULL,'2025-10-28 04:17:03','2025-10-28 04:17:03'),(89,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:17:15',NULL,NULL,'2025-10-28 04:17:15','2025-10-28 04:17:15'),(90,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:25:47',NULL,NULL,'2025-10-28 04:25:47','2025-10-28 04:25:47'),(91,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:25:51',NULL,NULL,'2025-10-28 04:25:51','2025-10-28 04:25:51'),(92,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:28:14',NULL,NULL,'2025-10-28 04:28:14','2025-10-28 04:28:14'),(93,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-28 10:28:18',NULL,NULL,'2025-10-28 04:28:18','2025-10-28 04:28:18'),(94,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:28:49',NULL,NULL,'2025-10-28 04:28:49','2025-10-28 04:28:49'),(95,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:29:24',NULL,NULL,'2025-10-28 04:29:24','2025-10-28 04:29:24'),(96,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:29:28',NULL,NULL,'2025-10-28 04:29:28','2025-10-28 04:29:28'),(97,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:30:48',NULL,NULL,'2025-10-28 04:30:48','2025-10-28 04:30:48'),(98,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:30:52',NULL,NULL,'2025-10-28 04:30:52','2025-10-28 04:30:52'),(99,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:33:44',NULL,NULL,'2025-10-28 04:33:44','2025-10-28 04:33:44'),(100,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:33:57',NULL,NULL,'2025-10-28 04:33:57','2025-10-28 04:33:57'),(101,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:34:00',NULL,NULL,'2025-10-28 04:34:00','2025-10-28 04:34:00'),(102,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:35:00',NULL,NULL,'2025-10-28 04:35:00','2025-10-28 04:35:00'),(103,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:39:10',NULL,NULL,'2025-10-28 04:39:10','2025-10-28 04:39:10'),(104,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:39:14',NULL,NULL,'2025-10-28 04:39:14','2025-10-28 04:39:14'),(105,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:39:15',NULL,NULL,'2025-10-28 04:39:15','2025-10-28 04:39:15'),(106,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:39:18',NULL,NULL,'2025-10-28 04:39:18','2025-10-28 04:39:18'),(107,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:49:05',NULL,NULL,'2025-10-28 04:49:05','2025-10-28 04:49:05'),(108,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 10:49:09',NULL,NULL,'2025-10-28 04:49:09','2025-10-28 04:49:09'),(109,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:31:24',NULL,NULL,'2025-10-28 05:31:24','2025-10-28 05:31:24'),(110,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:31:31',NULL,NULL,'2025-10-28 05:31:31','2025-10-28 05:31:31'),(111,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:31:32',NULL,NULL,'2025-10-28 05:31:32','2025-10-28 05:31:32'),(112,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:31:36',NULL,NULL,'2025-10-28 05:31:36','2025-10-28 05:31:36'),(113,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:33:11',NULL,NULL,'2025-10-28 05:33:11','2025-10-28 05:33:11'),(114,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:33:29',NULL,NULL,'2025-10-28 05:33:30','2025-10-28 05:33:30'),(115,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:42:06',NULL,NULL,'2025-10-28 05:42:06','2025-10-28 05:42:06'),(116,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:42:09',NULL,NULL,'2025-10-28 05:42:09','2025-10-28 05:42:09'),(117,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:42:10',NULL,NULL,'2025-10-28 05:42:10','2025-10-28 05:42:10'),(118,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:42:13',NULL,NULL,'2025-10-28 05:42:14','2025-10-28 05:42:14'),(119,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:42:14',NULL,NULL,'2025-10-28 05:42:14','2025-10-28 05:42:14'),(120,'y','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-28 11:42:18',NULL,NULL,'2025-10-28 05:42:18','2025-10-28 05:42:18'),(121,'z','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-28 11:42:23',NULL,NULL,'2025-10-28 05:42:23','2025-10-28 05:42:23'),(122,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:42:28',NULL,NULL,'2025-10-28 05:42:28','2025-10-28 05:42:28'),(123,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:44:21',NULL,NULL,'2025-10-28 05:44:21','2025-10-28 05:44:21'),(124,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:44:24',NULL,NULL,'2025-10-28 05:44:25','2025-10-28 05:44:25'),(125,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:47:03',NULL,NULL,'2025-10-28 05:47:03','2025-10-28 05:47:03'),(126,'x','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 11:47:07',NULL,NULL,'2025-10-28 05:47:07','2025-10-28 05:47:07'),(127,'x','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 13:38:43',NULL,NULL,'2025-10-28 07:38:43','2025-10-28 07:38:43'),(128,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 13:38:53',NULL,NULL,'2025-10-28 07:38:53','2025-10-28 07:38:53'),(129,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 14:37:07',NULL,NULL,'2025-10-28 08:37:07','2025-10-28 08:37:07'),(130,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 14:37:15',NULL,NULL,'2025-10-28 08:37:15','2025-10-28 08:37:15'),(131,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 14:38:58',NULL,NULL,'2025-10-28 08:38:58','2025-10-28 08:38:58'),(132,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 14:40:29',NULL,NULL,'2025-10-28 08:40:29','2025-10-28 08:40:29'),(133,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 15:54:32',NULL,NULL,'2025-10-28 09:54:32','2025-10-28 09:54:32'),(134,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-28 15:54:37',NULL,NULL,'2025-10-28 09:54:37','2025-10-28 09:54:37'),(135,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','failed','2025-10-28 15:54:42',NULL,NULL,'2025-10-28 09:54:42','2025-10-28 09:54:42'),(136,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-28 15:54:48',NULL,NULL,'2025-10-28 09:54:48','2025-10-28 09:54:48'),(137,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 12:44:43',NULL,NULL,'2025-10-29 06:44:43','2025-10-29 06:44:43'),(138,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 13:31:39',NULL,NULL,'2025-10-29 07:31:39','2025-10-29 07:31:39'),(139,'a','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 13:31:44',NULL,NULL,'2025-10-29 07:31:44','2025-10-29 07:31:44'),(140,'a','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 13:31:56',NULL,NULL,'2025-10-29 07:31:56','2025-10-29 07:31:56'),(141,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 13:32:00',NULL,NULL,'2025-10-29 07:32:00','2025-10-29 07:32:00'),(142,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 13:39:34',NULL,NULL,'2025-10-29 07:39:34','2025-10-29 07:39:34'),(143,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 13:39:39',NULL,NULL,'2025-10-29 07:39:39','2025-10-29 07:39:39'),(144,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:00:57',NULL,NULL,'2025-10-29 08:00:57','2025-10-29 08:00:57'),(145,'f','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:01:01',NULL,NULL,'2025-10-29 08:01:01','2025-10-29 08:01:01'),(146,'f','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:06:13',NULL,NULL,'2025-10-29 08:06:13','2025-10-29 08:06:13'),(147,'g','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:06:17',NULL,NULL,'2025-10-29 08:06:17','2025-10-29 08:06:17'),(148,'g','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:46:23',NULL,NULL,'2025-10-29 08:46:23','2025-10-29 08:46:23'),(149,'f','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:46:27',NULL,NULL,'2025-10-29 08:46:27','2025-10-29 08:46:27'),(150,'f','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:47:27',NULL,NULL,'2025-10-29 08:47:27','2025-10-29 08:47:27'),(151,'g','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:47:31',NULL,NULL,'2025-10-29 08:47:31','2025-10-29 08:47:31'),(152,'g','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:48:29',NULL,NULL,'2025-10-29 08:48:29','2025-10-29 08:48:29'),(153,'f','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:48:34',NULL,NULL,'2025-10-29 08:48:34','2025-10-29 08:48:34'),(154,'f','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:48:37',NULL,NULL,'2025-10-29 08:48:37','2025-10-29 08:48:37'),(155,'g','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:48:42',NULL,NULL,'2025-10-29 08:48:42','2025-10-29 08:48:42'),(156,'g','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:48:58',NULL,NULL,'2025-10-29 08:48:58','2025-10-29 08:48:58'),(157,'h','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:49:02',NULL,NULL,'2025-10-29 08:49:02','2025-10-29 08:49:02'),(158,'h','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:49:50',NULL,NULL,'2025-10-29 08:49:50','2025-10-29 08:49:50'),(159,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 14:49:54',NULL,NULL,'2025-10-29 08:49:54','2025-10-29 08:49:54'),(160,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:34:45',NULL,NULL,'2025-10-29 09:34:45','2025-10-29 09:34:45'),(161,'f','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:34:49',NULL,NULL,'2025-10-29 09:34:49','2025-10-29 09:34:49'),(162,'f','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:35:34',NULL,NULL,'2025-10-29 09:35:34','2025-10-29 09:35:34'),(163,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:35:40',NULL,NULL,'2025-10-29 09:35:40','2025-10-29 09:35:40'),(164,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:48:56',NULL,NULL,'2025-10-29 09:48:56','2025-10-29 09:48:56'),(165,'f','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:49:01',NULL,NULL,'2025-10-29 09:49:02','2025-10-29 09:49:02'),(166,'f','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:49:18',NULL,NULL,'2025-10-29 09:49:18','2025-10-29 09:49:18'),(167,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:49:23',NULL,NULL,'2025-10-29 09:49:23','2025-10-29 09:49:23'),(168,'admin','logout','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:49:39',NULL,NULL,'2025-10-29 09:49:39','2025-10-29 09:49:39'),(169,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-29 15:49:47',NULL,NULL,'2025-10-29 09:49:47','2025-10-29 09:49:47'),(170,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-10-30 11:06:11',NULL,NULL,'2025-10-30 05:06:11','2025-10-30 05:06:11'),(171,'admin','login','192.168.3.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0','success','2025-11-02 11:27:19',NULL,NULL,'2025-11-02 05:27:20','2025-11-02 05:27:20'),(172,'admin','login','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','success','2025-11-02 15:07:23',NULL,NULL,'2025-11-02 09:07:23','2025-11-02 09:07:23');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `log_table` with 172 row(s)
--

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` enum('admin','user','sadmin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'N','$2y$10$6J5IdNSWBCJOc.4IjlJD8.HvXz90KJEB0xsTjqLnY3feLreJsATza','SFCL..',NULL,'sfcl@yahoo.com','2222','user','2025-10-14 08:00:19','2025-10-29 07:56:05'),(2,'B','$2y$10$JJlrLH/y.Lh5elcETMBNf.i/zmkZL718QER/yNfKK3kWUbtH9VNyO','JFCL',NULL,NULL,NULL,'user','2025-10-26 03:45:21','2025-10-29 07:56:01'),(3,'admin','$2y$10$TdA8pCKlQmdP56Jm2CD.fuEVpKskNFAgsjyzFluSoTwR8u5rc3nry','admin',NULL,NULL,NULL,'admin','2025-10-26 04:29:52','2025-10-26 04:29:52'),(4,'x','$2y$10$wC.v38hMEAxwxvcGBD370.SYkflf13tct1UugkpGpgZD/2LtKd8v.','X','SST',NULL,'453','user','2025-10-27 09:08:59','2025-10-27 09:08:59'),(5,'a','$2y$10$zF1Zk72VGLYZDgFQaCTdduAch7ACtUB0gNaX5upF.XAZwh.YwlYVq','y','SST 1',NULL,'44','user','2025-10-27 09:08:59','2025-10-28 04:28:42'),(6,'j','$2y$10$1WKcAq2ECsRwLqqEmg43keu3FGxUwHqYMFLa0U4yR5zyXHp1IEhT2','j','SST',NULL,'4344','user','2025-10-29 07:43:09','2025-10-29 07:43:09'),(7,'k','$2y$10$7sl1He/oNWjSFk6uCyIVB.OzqJfh1RnvRsLzXxcSMtxGEMaXwS8PC','k','SST 1',NULL,'77','user','2025-10-29 07:43:09','2025-10-29 07:43:09'),(8,'z','$2y$10$JnfcY9FcFRivNp6cd7DIYOfCErtwQUwJ5jxjVPObCw3hYhlGJ5yee','z','SST 1',NULL,'55','user','2025-10-29 07:53:17','2025-10-29 07:53:17'),(9,'f','$2y$10$prHvHIpK95Kyg9S9eo5RNeBtG9KSrHd0zPlHicyzIpPrK7JX6VXgC','f','SST 1',NULL,'554','user','2025-10-29 07:59:28','2025-10-29 07:59:28'),(10,'g','$2y$10$mUFi2l7KiZM2XdaMAguIy.0JdVqNP4YbjSVArkfojME54H5S2SWQa','g','SST',NULL,'655','user','2025-10-29 07:59:28','2025-10-29 07:59:28'),(11,'h','$2y$10$AqUiCAse/ArDvvnqJL7f.e8lqcanehtjicDFBoLaduhaogGQZKuzK','h','Assistant Manager',NULL,'645','user','2025-10-29 07:59:28','2025-10-29 07:59:28'),(12,'p','$2y$10$Vu16voWlHKE0yRiuFFzr7uJQK7gi2b6DDLpjtiwHhZMKI.k0pMq6m','p','Assistant Manager','','4344444','user','2025-10-29 04:59:26','2025-10-29 04:59:26'),(13,'o','$2y$10$ZGxZPFm84snf1yN4ri/WROIvIPqmMz6eyc3F9Z11crfmaMrPAm95S','o','SST 1','','45368','user','2025-10-29 04:59:27','2025-10-29 04:59:27');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 13 row(s)
--

--
-- Table structure for table `viva_marks_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viva_marks_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candidate_id` int(11) NOT NULL,
  `committe_name` varchar(255) NOT NULL,
  `examiner_username` varchar(100) NOT NULL,
  `viva_marks` decimal(5,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_entry` (`candidate_id`,`examiner_username`),
  CONSTRAINT `viva_marks_tbl_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates_tbl` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viva_marks_tbl`
--

LOCK TABLES `viva_marks_tbl` WRITE;
/*!40000 ALTER TABLE `viva_marks_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `viva_marks_tbl` VALUES (1,3,'Com-4','f',10.00,'','2025-10-29 08:47:08','2025-10-29 09:03:57'),(2,4,'Com-4','f',10.00,'','2025-10-29 08:47:15','2025-10-29 09:04:12'),(3,3,'Com-4','g',5.00,'','2025-10-29 08:47:38','2025-10-29 09:04:15'),(4,4,'Com-4','g',5.00,'','2025-10-29 08:47:43','2025-10-29 09:04:17'),(5,3,'Com-4','h',2.00,'','2025-10-29 08:49:05','2025-10-29 09:04:18'),(6,4,'Com-4','h',2.00,'','2025-10-29 08:49:09','2025-10-29 09:04:20');
/*!40000 ALTER TABLE `viva_marks_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `viva_marks_tbl` with 6 row(s)
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

-- Dump completed on: Sun, 02 Nov 2025 11:16:03 +0100
