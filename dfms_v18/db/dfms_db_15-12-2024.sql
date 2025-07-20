-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: dfms_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Sun, 15 Dec 2024 11:50:11 +0100

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
-- Table structure for table `cufl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cufl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'sfcl',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cufl`
--

LOCK TABLES `cufl` WRITE;
/*!40000 ALTER TABLE `cufl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cufl` VALUES (50,'cufl',20246,'2024-12-01',200,200,200,80,'','580000','580000',0,0,0),(51,'cufl',20246,'2024-12-02',100,300,300,80,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `cufl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cufl` with 2 row(s)
--

--
-- Table structure for table `jfcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jfcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'jfcl',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jfcl`
--

LOCK TABLES `jfcl` WRITE;
/*!40000 ALTER TABLE `jfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `jfcl` VALUES (45,'jfcl',202411,'2024-05-19',30,30,30,60,'','580000','580000',0,0,0),(46,'jfcl',202411,'2024-05-28',0,30,30,0,'','580000','580000',0,0,0),(47,'jfcl',202411,'2024-05-29',30,60,60,60,'','580000','580000',0,0,0),(48,'jfcl',202412,'2024-06-11',30,30,90,60,'','580000','580000',0,0,0),(49,'jfcl',20246,'2024-12-01',100,100,190,80,'Runing','580000','580000',0,0,0),(50,'jfcl',20246,'2024-12-02',100,200,290,80,'test','580000','580000',0,0,0);
/*!40000 ALTER TABLE `jfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `jfcl` with 6 row(s)
--

--
-- Table structure for table `report_urea`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_urea` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `daily` varchar(10) NOT NULL,
  `monthly` varchar(10) NOT NULL,
  `yearly` varchar(10) NOT NULL,
  `production_target` varchar(30) NOT NULL,
  `due` varchar(30) NOT NULL,
  `plant_load` varchar(5) NOT NULL,
  `stock_on_date` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_urea`
--

LOCK TABLES `report_urea` WRITE;
/*!40000 ALTER TABLE `report_urea` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `report_urea` VALUES (18,'SFCL','2022-03-03','10','100','1000','','','',''),(45,'SFCL','2022-03-30','4','62','94','','','',''),(46,'SFCL','2022-03-31','2','64','96','','','',''),(47,'SFCL','2022-04-01','10','10','106','','','',''),(48,'SFCL','2022-07-01','10','10','10','','','','');
/*!40000 ALTER TABLE `report_urea` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `report_urea` with 5 row(s)
--

--
-- Table structure for table `sfcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sfcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'sfcl',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sfcl`
--

LOCK TABLES `sfcl` WRITE;
/*!40000 ALTER TABLE `sfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sfcl` VALUES (45,'sfcl',202411,'2024-05-19',30,30,30,60,'','580000','580000',0,0,0),(46,'sfcl',202411,'2024-05-28',0,30,30,0,'','580000','580000',0,0,0),(47,'sfcl',202411,'2024-05-29',30,60,60,60,'','580000','580000',0,0,0),(48,'sfcl',202412,'2024-06-11',30,30,90,60,'','580000','580000',0,0,0),(49,'sfcl',20246,'2024-12-01',100,100,190,80,'Runing','580000','580000',0,0,0),(50,'sfcl',20246,'2024-12-02',100,200,290,80,'Less production due to process inherent problem in urea plant.','580000','580000',2000,0,0),(51,'sfcl',20246,'2024-12-03',200,400,490,80,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `sfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sfcl` with 7 row(s)
--

--
-- Table structure for table `shutdown_cause`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shutdown_cause` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(25) NOT NULL,
  `cause` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shutdown_cause`
--

LOCK TABLES `shutdown_cause` WRITE;
/*!40000 ALTER TABLE `shutdown_cause` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `shutdown_cause` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `shutdown_cause` with 0 row(s)
--

--
-- Table structure for table `target_table`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `target_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL,
  `target` int(100) NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_table`
--

LOCK TABLES `target_table` WRITE;
/*!40000 ALTER TABLE `target_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `target_table` VALUES (5,'sfcl',400000,'2023-07-01','2024-06-30'),(6,'jfcl',400000,'2023-07-01','2024-06-30'),(16,'sfcl',0,'2024-07-01','2025-06-30'),(17,'jfcl',500000,'2024-07-01','2025-06-30'),(18,'cufl',0,'2024-07-01','2025-06-30'),(19,'tspcl',0,'2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `target_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `target_table` with 6 row(s)
--

--
-- Table structure for table `tspcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tspcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tspcl`
--

LOCK TABLES `tspcl` WRITE;
/*!40000 ALTER TABLE `tspcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tspcl` VALUES (50,'tspcl',20246,'2024-12-01',200,200,200,80,'','580000','580000',0,0,0),(51,'tspcl',20246,'2024-12-02',100,300,300,80,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `tspcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tspcl` with 2 row(s)
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
  `user_type` varchar(100) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'sfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-01 08:59:48'),(2,'jfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-02 08:59:54'),(3,'afccl','6116afedcb0bc31083935c1c262ff4c9','user','urea','','2024-12-01 08:50:51'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','','','2024-05-28 08:21:58'),(12,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','user@yahoo.com','2024-05-28 08:22:05'),(13,'cufl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','cufl@yahoo.com','2024-12-02 10:15:26'),(17,'tspcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','tspcl.md@bcic.gov.bd','2024-12-01 08:57:43'),(19,'dapfcl','6116afedcb0bc31083935c1c262ff4c9','user','non-urea','dap@yahoo.com','2024-12-01 08:50:27'),(20,'gpfplc','4f7a386d9ac4efbd3dbefd74113bc766','user','urea','gpfplc@yahoo.com','2024-12-01 08:50:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 9 row(s)
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

-- Dump completed on: Sun, 15 Dec 2024 11:50:11 +0100
