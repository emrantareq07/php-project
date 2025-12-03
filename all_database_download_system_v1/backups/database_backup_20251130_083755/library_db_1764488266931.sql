-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: library_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('sadmin','admin','user') NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'emran','202cb962ac59075b964b07152d234b70','admin','2025-05-20 09:16:38'),(2,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','2025-05-19 18:00:00'),(3,'anjan','202cb962ac59075b964b07152d234b70','admin','2025-05-20 08:52:56');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_table`
--

DROP TABLE IF EXISTS `log_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `Ip` varchar(100) NOT NULL,
  `login_date_time` datetime NOT NULL,
  `code` int(11) NOT NULL,
  `logout_date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
INSERT INTO `log_table` VALUES (1,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 08:51:52',33345,'0000-00-00 00:00:00'),(2,'0001','1234','student','127.0.0.1','2025-05-21 08:59:21',12113,'0000-00-00 00:00:00'),(3,'0001','1234','student','127.0.0.1','2025-05-21 09:10:08',67034,'2025-05-21 09:10:48'),(4,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:11:36',93538,'2025-05-21 09:11:41'),(5,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:33:37',48564,'2025-05-21 09:36:22'),(6,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:38:12',23811,'2025-05-21 09:43:04'),(7,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:43:08',70356,'2025-05-21 10:00:11'),(8,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:43:20',27583,'2025-05-21 10:27:09'),(9,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-21 10:00:20',27016,'2025-05-21 10:00:28'),(10,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-21 10:02:17',86497,'2025-05-21 10:23:22'),(11,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 10:23:26',73911,'2025-05-21 10:32:22'),(12,'0001','1234','student','127.0.0.1','2025-05-21 10:27:18',77771,'2025-05-21 10:29:08'),(13,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 10:29:16',90947,'2025-05-21 10:30:37'),(14,'0006','1234','student','127.0.0.1','2025-05-21 10:32:37',20382,'2025-05-21 13:06:06'),(15,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 10:33:07',81068,'2025-05-21 13:02:08'),(16,'0006','1234','student','127.0.0.1','2025-05-21 13:04:18',47266,'2025-05-21 13:04:29'),(17,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:04:40',22781,'0000-00-00 00:00:00'),(18,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:06:17',24474,'2025-05-21 13:08:54'),(19,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-21 13:08:59',49940,'2025-05-21 13:09:08'),(20,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:09:12',84236,'0000-00-00 00:00:00'),(21,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:39:26',72560,'0000-00-00 00:00:00'),(22,'0001','1234','student','127.0.0.1','2025-05-21 14:06:01',79393,'0000-00-00 00:00:00'),(23,'0001','1234','student','127.0.0.1','2025-05-21 14:06:56',16567,'2025-05-21 14:07:37'),(24,'0001','1234','student','127.0.0.1','2025-05-22 12:26:02',37776,'2025-05-22 12:26:06'),(25,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 12:26:55',36032,'2025-05-22 12:27:28'),(26,'0001','1234','student','127.0.0.1','2025-05-22 12:30:52',31424,'2025-05-22 12:30:56'),(27,'0007','1234','student','127.0.0.1','2025-05-22 12:31:01',89389,'2025-05-22 12:31:04'),(28,'0001','','student','127.0.0.1','2025-05-22 12:55:58',85171,'2025-05-22 12:56:01'),(29,'0007','','student','127.0.0.1','2025-05-22 12:56:11',70201,'2025-05-22 12:56:13'),(30,'0001','1234','student','127.0.0.1','2025-05-22 13:03:42',49112,'2025-05-22 13:03:44'),(31,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 13:06:25',48401,'0000-00-00 00:00:00'),(32,'0006','1234','student','127.0.0.1','2025-05-22 13:07:08',12212,'2025-05-22 13:07:16'),(33,'0005','123','student','127.0.0.1','2025-05-22 13:08:06',96996,'2025-05-22 13:30:58'),(34,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-25 06:52:07',91308,'2025-05-25 08:14:11'),(35,'0001','1234','student','127.0.0.1','2025-05-25 08:14:17',65014,'0000-00-00 00:00:00'),(36,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-26 11:00:52',81172,'0000-00-00 00:00:00'),(37,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-06-04 08:25:13',12996,'2025-06-04 08:41:42'),(38,'0001','1234','student','127.0.0.1','2025-06-04 08:43:26',96090,'0000-00-00 00:00:00'),(39,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-06-18 11:11:21',98311,'0000-00-00 00:00:00'),(40,'anjan','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-06-29 10:18:59',36076,'0000-00-00 00:00:00'),(41,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-07-21 06:23:31',17905,'2025-07-21 06:29:19'),(42,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-07-21 06:29:25',74965,'2025-07-21 10:00:46'),(43,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-07-21 10:00:53',36266,'2025-07-21 10:01:53'),(44,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-07-21 10:01:58',60888,'2025-07-21 10:09:39'),(45,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-07-21 10:09:44',85407,'2025-07-21 10:10:58'),(46,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-07-21 10:11:56',13011,'2025-07-21 10:14:29'),(47,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-07-21 10:14:43',49538,'2025-07-21 10:14:50'),(48,'0001','1234','student','127.0.0.1','2025-07-21 10:14:53',30416,'2025-07-21 10:15:03'),(49,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-07-21 11:04:36',57215,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblauthors`
--

DROP TABLE IF EXISTS `tblauthors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblauthors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AuthorName` varchar(159) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblauthors`
--

LOCK TABLES `tblauthors` WRITE;
/*!40000 ALTER TABLE `tblauthors` DISABLE KEYS */;
INSERT INTO `tblauthors` VALUES (1,'Anuj kumar test','2017-07-08 12:49:09','2025-04-30 03:25:44'),(2,'Chetan Bhagatt','2017-07-08 14:30:23','2017-07-08 15:15:09'),(3,'Anita Desai','2017-07-08 14:35:08',NULL),(4,'HC Verma','2017-07-08 14:35:21',NULL),(5,'R.D. Sharma ','2017-07-08 14:35:36',NULL),(9,'fwdfrwer','2017-07-08 15:22:03',NULL),(10,'Balagurushamy','2025-04-17 06:53:22',NULL),(11,'হুমায়ূন আহমেদ','2025-04-30 06:03:06','2025-04-30 06:04:23'),(12,'আসমা কবির','2025-05-07 04:57:41','2025-05-07 05:00:59'),(13,'Humayan Ahmed','2025-05-20 04:36:26',NULL);
/*!40000 ALTER TABLE `tblauthors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbooks`
--

DROP TABLE IF EXISTS `tblbooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `accession_no` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `edition` varchar(50) NOT NULL,
  `isbn` varchar(100) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_place` varchar(255) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `page_no` int(11) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `call_no` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0,
  `series` varchar(50) NOT NULL,
  `volume` varchar(20) NOT NULL,
  `language` varchar(50) NOT NULL,
  `self_no` varchar(100) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `challan_no` varchar(15) NOT NULL,
  `e_book_link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbooks`
--

LOCK TABLES `tblbooks` WRITE;
/*!40000 ALTER TABLE `tblbooks` DISABLE KEYS */;
INSERT INTO `tblbooks` VALUES (1,'2025-06-19',2358,'Data Structure',5,2,'0','984-32-0591-X','','','',0,'','','','','2025-06-29 08:33:34','2025-06-29 08:49:56',0,'','','','','0000-00-00','','');
/*!40000 ALTER TABLE `tblbooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbooks_old`
--

DROP TABLE IF EXISTS `tblbooks_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbooks_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int(11) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `ISBNNumber` int(11) DEFAULT NULL,
  `BookPrice` int(11) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbooks_old`
--

LOCK TABLES `tblbooks_old` WRITE;
/*!40000 ALTER TABLE `tblbooks_old` DISABLE KEYS */;
INSERT INTO `tblbooks_old` VALUES (1,'PHP And MySql programming',5,1,222333,20,'2017-07-08 20:04:55','2017-07-15 05:54:41'),(3,'physics',6,4,1111,15,'2017-07-08 20:17:31','2017-07-15 06:13:17'),(4,'Data structure',5,2,545435,NULL,'2024-07-31 05:31:20',NULL),(5,'Mircroprocessor',5,9,456321,NULL,'2025-03-18 07:41:44',NULL);
/*!40000 ALTER TABLE `tblbooks_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcategory`
--

DROP TABLE IF EXISTS `tblcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryParent` varchar(200) DEFAULT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcategory`
--

LOCK TABLES `tblcategory` WRITE;
/*!40000 ALTER TABLE `tblcategory` DISABLE KEYS */;
INSERT INTO `tblcategory` VALUES (1,'Romantic','Romantic  ','1','2025-05-17 06:21:17','2025-05-17 06:21:17'),(2,'Management','Management  ','1','2025-05-17 06:21:35','2025-05-17 06:21:35'),(3,'Mathematics','Mathematics  abc','1','2025-05-17 06:21:55','2025-05-17 06:21:55'),(4,'Mathematics','Mathematics  nnnn','1','2025-05-17 06:22:14','2025-05-17 06:22:14'),(5,'Science','Science  Physics','1','2025-05-17 06:23:35','2025-05-17 06:23:35'),(6,'Mathematics','Mathematics  Differential Calculas','1','2025-05-18 05:34:42','2025-05-18 05:34:42'),(7,'Mathematics','Mathematics  Geometry','1','2025-05-18 05:35:06','2025-05-18 05:35:06'),(8,'Bangali Literature ','Bangali Literature   Academic','1','2025-05-20 04:35:39','2025-05-20 04:35:39'),(9,'Bangali Literature ','Bangali Literature   Nobel','1','2025-05-20 04:36:07','2025-05-20 04:36:07');
/*!40000 ALTER TABLE `tblcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcategory_old`
--

DROP TABLE IF EXISTS `tblcategory_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcategory_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcategory_old`
--

LOCK TABLES `tblcategory_old` WRITE;
/*!40000 ALTER TABLE `tblcategory_old` DISABLE KEYS */;
INSERT INTO `tblcategory_old` VALUES (4,'Romantic',1,'2017-07-04 18:35:25','2025-04-30 04:45:34'),(5,'Technology',1,'2017-07-04 18:35:39','2017-07-08 17:13:03'),(6,'Science',1,'2017-07-04 18:35:55','0000-00-00 00:00:00'),(7,'Management',1,'2017-07-04 18:36:16','2025-04-17 08:25:00'),(8,'Dictionary',1,'2025-05-07 04:57:19','0000-00-00 00:00:00'),(9,'Mathematics Differential ',1,'2025-05-17 05:39:46','2025-05-17 05:50:09'),(10,'Mathematics Calculas',1,'2025-05-17 05:39:46','2025-05-17 05:49:55'),(11,'Mathematics',1,'2025-05-17 05:39:46','2025-05-17 05:49:55'),(12,'Mathematics-Discrete',1,'2025-05-17 06:03:21','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `tblcategory_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblissuedbookdetails`
--

DROP TABLE IF EXISTS `tblissuedbookdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblissuedbookdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accession_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `BookId` int(11) DEFAULT NULL,
  `StudentID` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IssuesDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ReturnDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `RetrunStatus` int(1) NOT NULL,
  `fine` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblissuedbookdetails`
--

LOCK TABLES `tblissuedbookdetails` WRITE;
/*!40000 ALTER TABLE `tblissuedbookdetails` DISABLE KEYS */;
INSERT INTO `tblissuedbookdetails` VALUES (1,'1',NULL,'0001','2025-04-30 18:00:00','2025-05-08 05:20:12',1,0),(2,'2',NULL,'0001','2025-04-30 18:00:00','2025-05-06 18:00:00',0,0),(3,'1',NULL,'0001','2025-05-06 18:00:00','2025-05-11 18:00:00',0,0),(4,'11',NULL,'0001','2025-05-17 18:00:00','2025-06-01 18:00:00',0,NULL),(5,'13',NULL,'0001','2025-05-17 18:00:00','2025-06-01 18:00:00',0,NULL),(6,'14',NULL,'0001','2025-05-17 18:00:00','2025-06-01 18:00:00',0,NULL),(7,'17',NULL,'0007','2025-05-19 18:00:00','2025-05-31 18:00:00',1,0),(8,'16',NULL,'0007','2025-05-17 18:00:00','2025-05-19 18:00:00',1,0),(9,'16',NULL,'0007','2025-05-19 18:00:00','2025-06-03 18:00:00',0,0);
/*!40000 ALTER TABLE `tblissuedbookdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblstudents`
--

DROP TABLE IF EXISTS `tblstudents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblstudents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `StudentId` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `FullName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `std_class` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `std_section` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `std_group` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `std_session` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `EmailId` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MobileNumber` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Image` varchar(255) DEFAULT NULL,
  `password_changed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `StudentId` (`StudentId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblstudents`
--

LOCK TABLES `tblstudents` WRITE;
/*!40000 ALTER TABLE `tblstudents` DISABLE KEYS */;
INSERT INTO `tblstudents` VALUES (1,'0001','Jamal','Eleven','A','Science','2024-2025','','0171883465','$2y$10$I6MZF/1UiMRh/YfNAXXQk.Iu16Ki/iI9HYGT4k4/kUUitQwAM8n6G',1,'2025-05-07 05:14:14','2025-05-18 05:52:42','student_images/1746594854_maksuda.jpg',1),(2,'0002','hasan','Twelve','A','O+','2025-2026','','','$2y$10$nLcLloFsLvQXlc9LY/7.8eliKqAWLoy2zGIm2AjMpcHwq/sQBjPd6',1,'2025-05-07 05:16:42','2025-05-20 06:08:05','student_images/1746595002_sarower.jpg',1),(3,'0007','hasan','Eleven','S-1','B+','2025-2026','emran445@yahoo.com','0171883465','$2y$10$C3a00LbKOuNv8oFBsfRqyuqYaTWCcqVR2ZCxrWD7EuNBU0PWV6ReK',1,'2025-05-20 04:42:11','2025-05-20 06:08:05','student_images/1747716131_2023-04-16-06-21-c06eb73f99118202eee4b5ff90c30ec8.jpg',1),(4,'0006','Lalon','Eleven','S-3','O+','2025-2026','','0101124242','$2y$10$w/d4MA13fFJgaTcKONgE/.WNixJXqkVa4o8n.KRrkLAUowYT.Pa/K',1,'2025-05-21 08:30:29','2025-05-21 08:30:51','student_images/1747816229_bcic_logo.png',1),(5,'0005','lala','Eight','S-3','A-','2025-2026','hasan@yahoo.com','5646421346','$2y$10$0yU8eURPztoDHQWTHfagletcmeUayOaNUtnHEyS8lPdAtI7m8J/rG',1,'2025-05-22 10:27:24','2025-05-22 11:08:29','student_images/1747909644_sarower.jpg',0);
/*!40000 ALTER TABLE `tblstudents` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 13:37:52
