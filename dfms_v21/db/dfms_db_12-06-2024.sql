-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: dfms_db
-- ------------------------------------------------------
-- Server version 	10.4.24-MariaDB
-- Date: Wed, 12 Jun 2024 10:44:45 +0200

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
-- Table structure for table `afccl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `afccl` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(100) NOT NULL DEFAULT 'afccl',
  `month_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `afccl`
--

LOCK TABLES `afccl` WRITE;
/*!40000 ALTER TABLE `afccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `afccl` VALUES (1,'afccl',202312,'2023-06-01',10,20,20,0,''),(3,'afccl',20231,'2023-07-01',10,10,10,0,''),(5,'afccl',202411,'2024-05-28',30,30,40,0,''),(6,'afccl',202411,'2024-06-11',0,30,40,0,'Due to NG ');
/*!40000 ALTER TABLE `afccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `afccl` with 4 row(s)
--

--
-- Table structure for table `cufl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cufl` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cufl`
--

LOCK TABLES `cufl` WRITE;
/*!40000 ALTER TABLE `cufl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `cufl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cufl` with 0 row(s)
--

--
-- Table structure for table `dapfcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dapfcl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) DEFAULT 'dapfcl',
  `month_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `daily` int(11) DEFAULT NULL,
  `monthly` int(11) DEFAULT NULL,
  `yearly` int(11) DEFAULT NULL,
  `plant_load` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dapfcl`
--

LOCK TABLES `dapfcl` WRITE;
/*!40000 ALTER TABLE `dapfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dapfcl` VALUES (1,'dapfcl',202412,'2024-06-12',30,30,30,60,'');
/*!40000 ALTER TABLE `dapfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dapfcl` with 1 row(s)
--

--
-- Table structure for table `gpfplc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpfplc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) DEFAULT 'gpfplc',
  `month_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `daily` int(11) DEFAULT NULL,
  `monthly` int(11) DEFAULT NULL,
  `yearly` int(11) DEFAULT NULL,
  `plant_load` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gpfplc`
--

LOCK TABLES `gpfplc` WRITE;
/*!40000 ALTER TABLE `gpfplc` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `gpfplc` VALUES (1,'gpfplc',202412,'2024-06-11',1763,1763,1763,0,'');
/*!40000 ALTER TABLE `gpfplc` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `gpfplc` with 1 row(s)
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jfcl`
--

LOCK TABLES `jfcl` WRITE;
/*!40000 ALTER TABLE `jfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `jfcl` VALUES (1,'jfcl',20231,'2023-07-04',60,60,60,80,''),(2,'jfcl',202411,'2024-05-20',10,10,70,0,''),(3,'jfcl',202412,'2024-06-12',0,0,70,0,'Due to NG Gas\'s Shortage');
/*!40000 ALTER TABLE `jfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `jfcl` with 3 row(s)
--

--
-- Table structure for table `kafco`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kafco` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kafco`
--

LOCK TABLES `kafco` WRITE;
/*!40000 ALTER TABLE `kafco` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `kafco` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kafco` with 0 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sfcl`
--

LOCK TABLES `sfcl` WRITE;
/*!40000 ALTER TABLE `sfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sfcl` VALUES (45,'sfcl',202411,'2024-05-19',30,30,30,60,''),(46,'sfcl',202411,'2024-05-28',0,30,30,0,''),(47,'sfcl',202411,'2024-05-29',30,60,60,60,''),(48,'sfcl',202412,'2024-06-11',30,30,90,60,'');
/*!40000 ALTER TABLE `sfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sfcl` with 4 row(s)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_table`
--

LOCK TABLES `target_table` WRITE;
/*!40000 ALTER TABLE `target_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `target_table` VALUES (5,'sfcl',400000,'2023-07-01','2024-06-30'),(6,'jfcl',400000,'2023-07-01','2024-06-30'),(7,'jfcl',0,'2024-07-01','2025-06-30'),(8,'afccl',400000,'2023-07-01','2024-06-30'),(9,'tici',0,'2023-07-01','2024-06-30'),(12,'sfcl',0,'2024-07-01','2025-06-30'),(13,'tspcl',0,'2023-07-01','2024-06-30'),(14,'dapfcl',0,'2023-07-01','2024-06-30'),(15,'gpfplc',0,'2023-07-01','2024-06-30');
/*!40000 ALTER TABLE `target_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `target_table` with 9 row(s)
--

--
-- Table structure for table `tici`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tici` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tici',
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `monthly` int(10) NOT NULL,
  `yearly` int(10) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tici`
--

LOCK TABLES `tici` WRITE;
/*!40000 ALTER TABLE `tici` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `tici` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tici` with 0 row(s)
--

--
-- Table structure for table `tspcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tspcl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) DEFAULT 'tspcl',
  `month_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `daily` int(11) DEFAULT NULL,
  `monthly` int(11) DEFAULT NULL,
  `yearly` int(11) DEFAULT NULL,
  `plant_load` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tspcl`
--

LOCK TABLES `tspcl` WRITE;
/*!40000 ALTER TABLE `tspcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tspcl` VALUES (1,'tspcl',202412,'2024-05-28',30,30,30,0,'');
/*!40000 ALTER TABLE `tspcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tspcl` with 1 row(s)
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
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'sfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','2022-12-30 16:12:35'),(2,'jfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','2023-02-21 16:20:41'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','2022-12-30 16:54:22'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','','2024-05-28 08:21:58'),(12,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','user@yahoo.com','2024-05-28 08:22:05'),(13,'cufl','6116afedcb0bc31083935c1c262ff4c9','user','cufl@yahoo.com','2024-05-23 09:39:24'),(17,'tspcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','tspcl.md@bcic.gov.bd','2024-06-12 04:42:45'),(18,'tspcl','7c4a8d09ca3762af61e59520943dc26494f8941b','user','tspcl@yahoo.com','2024-06-12 04:47:48'),(19,'dapfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','dap@yahoo.com','2024-06-12 06:15:58'),(20,'gpfplc','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','gpfplc@yahoo.com','2024-06-12 08:14:29');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 10 row(s)
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

-- Dump completed on: Wed, 12 Jun 2024 10:44:46 +0200
