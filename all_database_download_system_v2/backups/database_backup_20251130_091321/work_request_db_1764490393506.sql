-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: work_request_db
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
-- Table structure for table `work_request_items`
--

DROP TABLE IF EXISTS `work_request_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_request_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `item` varchar(100) NOT NULL,
  `item_desc` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  CONSTRAINT `work_request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `work_request_tbl` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_request_items`
--

LOCK TABLES `work_request_items` WRITE;
/*!40000 ALTER TABLE `work_request_items` DISABLE KEYS */;
INSERT INTO `work_request_items` VALUES (3,2,'CPU','dfg','ff'),(4,3,'CPU','test',''),(5,3,'CPU','test',''),(6,4,'Printer','Plz repair',''),(7,5,'Tubelight','Need New one',''),(8,6,'Civil','Washroom feeting broken ',''),(14,7,'Tubelight','Need New one',''),(15,7,'Fan','test',''),(16,7,'Fan','test','');
/*!40000 ALTER TABLE `work_request_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_request_tbl`
--

DROP TABLE IF EXISTS `work_request_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_request_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `requested_type` enum('Civil','MTS','ICT') NOT NULL,
  `extra_item` text DEFAULT NULL,
  `status` enum('pending','complete') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_request_tbl`
--

LOCK TABLES `work_request_tbl` WRITE;
/*!40000 ALTER TABLE `work_request_tbl` DISABLE KEYS */;
INSERT INTO `work_request_tbl` VALUES (2,'6898-1','test','Sub Assistant Engr.','test','sdf','','test@yahoo.com','Civil','t','pending','2025-08-28 10:28:33','2025-08-28 10:28:33'),(3,'6826-2','test','Sub Assistant Engr.','test','sdf','1312341241243124','test@yahoo.com','ICT','test','pending','2025-08-28 10:33:58','2025-08-28 10:33:58'),(4,'6851-0','test','Sub Assistant Engr.','test','sdf','1312341241243124','','ICT','','pending','2025-08-31 06:03:35','2025-08-31 06:03:35'),(5,'6898-1','MTS test','MTS test','MTS test','MTS test','MTS test','test@yahoo.com','MTS','','pending','2025-08-31 06:04:23','2025-08-31 06:04:23'),(6,'6898-1','Civil test','Civil test','Civil test','Civil test','Civil test','','Civil','','pending','2025-08-31 06:05:02','2025-08-31 06:05:02'),(7,'6898-1','MTS test','MTS test','MTS test','MTS test','MTS test','test@yahoo.com','MTS','','complete','2025-08-31 06:33:55','2025-08-31 06:45:45');
/*!40000 ALTER TABLE `work_request_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_request_tbl_old`
--

DROP TABLE IF EXISTS `work_request_tbl_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_request_tbl_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `requested_type` varchar(100) NOT NULL,
  `requested_item` varchar(255) NOT NULL,
  `item_desc` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `extra_item` text DEFAULT NULL,
  `status` enum('pending','complete') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_request_tbl_old`
--

LOCK TABLES `work_request_tbl_old` WRITE;
/*!40000 ALTER TABLE `work_request_tbl_old` DISABLE KEYS */;
INSERT INTO `work_request_tbl_old` VALUES (1,'6594-6','test','Sub Assistant Engr.','test','test','1312341241243124','test@yahoo.com','','test','test','test','test','pending','2025-08-28 08:56:11','2025-08-28 08:56:11'),(2,'6594-6','test','Sub Assistant Engr.','test','test','1312341241243124','test@yahoo.com','','test','test','test','test','pending','2025-08-28 08:56:55','2025-08-28 08:56:55');
/*!40000 ALTER TABLE `work_request_tbl_old` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 14:13:21
