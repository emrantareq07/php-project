-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: transport_db
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
-- Table structure for table `vehicle_tbl`
--

DROP TABLE IF EXISTS `vehicle_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_status` varchar(50) DEFAULT NULL,
  `reg_no` varchar(20) DEFAULT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL,
  `vehicle_source` varchar(100) DEFAULT NULL,
  `sourcing_buying_year` year(4) DEFAULT NULL,
  `driven_km` varchar(10) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_designation` varchar(100) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `driver_appt_type` varchar(50) DEFAULT NULL,
  `yearofimpairment` year(4) DEFAULT NULL,
  `causeofimpairment` varchar(50) DEFAULT NULL,
  `repair_status` varchar(50) DEFAULT NULL,
  `action_taken` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reg_no` (`reg_no`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_tbl`
--

LOCK TABLES `vehicle_tbl` WRITE;
/*!40000 ALTER TABLE `vehicle_tbl` DISABLE KEYS */;
INSERT INTO `vehicle_tbl` VALUES (2,'ব্যবহৃত','g-4354','মাইক্রোবাস','Gp',2020,'100','S','S','samsu','স্থায়ী',2009,'','অযোগ্য','নিলাম',''),(4,'ব্যবহৃত','h-998','পাজেরো','cufl',2020,'200','ASHIKUR RAHMAN','DS','samsu','আউটসোর্সিং',2001,'dsd','যোগ্য','নিলাম','bvnb'),(5,'ব্যবহার অনুপযোগী','gg','মাইক্রোবাস','Gp',2010,'300','RAKIBUL HASAN','S','samsu','স্থায়ী',2003,'dsd','যোগ্য','বিক্রয়',''),(6,'ব্যবহার অনুপযোগী','p-909','জিপ','',1992,'','','','','',2010,'old','অযোগ্য','নিলাম',''),(7,'ব্যবহৃত','6777','পাজেরো','sfcl',2010,'500','MD. SHAHIN ALI','DS','samsu','আউটসোর্সিং',2025,'','যোগ্য','অন্যান্য',''),(8,'ব্যবহার অনুপযোগী','8000','মাইক্রোবাস','',2000,'','','','','',2010,'old','যোগ্য','বিক্রয়','');
/*!40000 ALTER TABLE `vehicle_tbl` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 13:37:55
