-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: legal_db
-- ------------------------------------------------------
-- Server version 	10.4.28-MariaDB
-- Date: Tue, 27 Feb 2024 09:36:20 +0100

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
-- Table structure for table `case_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(250) NOT NULL,
  `auto_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_type`
--

LOCK TABLES `case_type` WRITE;
/*!40000 ALTER TABLE `case_type` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `case_type` VALUES (2,'রিট পিটিশন',1),(3,'কনটেম্পট পিটিশন',1),(4,'এডমিলারিটি স্যুট',1),(5,'ফার্স্ট আপীল',1),(6,'আরবিট্রেশন এপ্লিকেশন',1),(7,'সিপিএলএ',2),(8,'সিএমপি',2),(9,'সিভিল রিভিউ পিটিশন',2),(10,'ক্রিমিনাল রিভিউ পিটিশন',2),(11,'কনটেম্পট পিটিশন',2),(12,'সিভিল আপীল',2),(13,'মানি স্যুট',3),(14,'মানি এক্সিকিউশন মামলা',3),(15,'অর্থঋণ মামলা',3),(16,'টাইটেল স্যুট',3),(17,'মিসকেস',3),(18,'আরবিট্রেশন এপ্লিকেশন',3),(19,'ট্রান্সফার মিসকেস',3),(20,'মিস আপীল',3),(21,'জিআর',4),(22,'সিআর',4),(23,'দেওয়ানী',4),(24,'বিএলএল',5),(25,'আপীল মামলা',6);
/*!40000 ALTER TABLE `case_type` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `case_type` with 24 row(s)
--

--
-- Table structure for table `court_division`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `court_division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `court_division_name` varchar(100) NOT NULL,
  `auto_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `court_division`
--

LOCK TABLES `court_division` WRITE;
/*!40000 ALTER TABLE `court_division` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `court_division` VALUES (1,'হাইকোর্ট বিভাগ',1),(2,'আপিল বিভাগ',1),(3,'জজ কোর্ট',2),(4,'সিএমএম কোর্ট',2),(5,'শ্রম আদালত',2),(6,'শ্রম আপীল আদালত',2);
/*!40000 ALTER TABLE `court_division` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `court_division` with 6 row(s)
--

--
-- Table structure for table `court_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `court_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `court_type`
--

LOCK TABLES `court_type` WRITE;
/*!40000 ALTER TABLE `court_type` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `court_type` VALUES (1,'উচ্চ আদালত'),(2,'নিম্ন আদালত');
/*!40000 ALTER TABLE `court_type` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `court_type` with 2 row(s)
--

--
-- Table structure for table `legal_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `legal_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `court_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `court_division` varchar(100) NOT NULL,
  `case_type` varchar(200) NOT NULL,
  `case_no` varchar(100) NOT NULL,
  `case_date` date NOT NULL,
  `case_duration` varchar(100) NOT NULL,
  `reason_of_case` text NOT NULL,
  `plaintiff_name` varchar(250) NOT NULL,
  `defendant_name` varchar(250) NOT NULL,
  `lower_name_address` text NOT NULL,
  `case_filing_date` date NOT NULL,
  `hearing_date` date NOT NULL,
  `hearing_result` varchar(100) NOT NULL,
  `next_hearing_result_date` date NOT NULL,
  `case_filing_accept_steps` text NOT NULL,
  `panel_lower_info` text NOT NULL,
  `panel_low_adv_info` text NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_tbl`
--

LOCK TABLES `legal_tbl` WRITE;
/*!40000 ALTER TABLE `legal_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `legal_tbl` VALUES (0,'','','','444','2024-02-05','','vcv','x','y','rr','2024-02-06','2024-02-21','পক্ষে','2024-02-04','fdf','gdfg','dgdsf','77555'),(3,'উচ্চ আদালত','হাইকোর্ট বিভাগ','কনটেম্পট পিটিশন','gfdg','2024-02-13','','cxv','vxcv','vcx','cvx','2024-02-13','2024-02-14','পক্ষে','2024-02-05','vxv','cxv','xv','vxzc'),(5,'নিম্ন আদালত','জজ কোর্ট','মানি স্যুট','435435','2024-02-05','','dsgf','fsf','sdf','saf','2024-02-13','2024-02-15','পক্ষে','2024-02-05','dd','dfs','sdf',''),(6,'উচ্চ আদালত','আপিল বিভাগ','সিপিএলএ','435435','2024-02-02','','aaaaaaaa','fsf','aaa','aa','2024-02-02','2024-01-31','পক্ষে','2024-02-14','aa','aa','aa','aa');
/*!40000 ALTER TABLE `legal_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `legal_tbl` with 4 row(s)
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
INSERT INTO `tblusers` VALUES (2,'abdul','admin','bootstrapfriendly@gmail.com','202cb962ac59075b964b07152d234b70','2020-10-23 16:03:33'),(6,'ramesh','ramesh','ramesh@gmail.com','827ccb0eea8a706c4c34a16891f84e7b',NULL),(7,'ramesh','ramesh2','ramesh@gmail2.com','827ccb0eea8a706c4c34a16891f84e7b',NULL),(0,'user','user','emrantareq09@gmail.com','202cb962ac59075b964b07152d234b70',NULL);
/*!40000 ALTER TABLE `tblusers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblusers` with 4 row(s)
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

-- Dump completed on: Tue, 27 Feb 2024 09:36:20 +0100
