-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: dfms_db
-- ------------------------------------------------------
-- Server version 	10.4.24-MariaDB
-- Date: Thu, 02 May 2024 11:42:23 +0200

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
-- Table structure for table `admin_login`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_login` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `password` varchar(25) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_login`
--

LOCK TABLES `admin_login` WRITE;
/*!40000 ALTER TABLE `admin_login` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `admin_login` VALUES (1,'emran','123','Md. Tareq Emran'),(2,'tareq','123','Md. Tareq Emran'),(3,'rajon','$2y$10$UCz7x/aaQrQdv1XFab','');
/*!40000 ALTER TABLE `admin_login` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `admin_login` with 3 row(s)
--

--
-- Table structure for table `afccl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `afccl` (
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
  `remarks` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `afccl`
--

LOCK TABLES `afccl` WRITE;
/*!40000 ALTER TABLE `afccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `afccl` VALUES (1,'afccl','2023-06-01','10','20','20','50000','','','','');
/*!40000 ALTER TABLE `afccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `afccl` with 1 row(s)
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
-- Dumping data for table `dapfcl`
--

LOCK TABLES `dapfcl` WRITE;
/*!40000 ALTER TABLE `dapfcl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `dapfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dapfcl` with 0 row(s)
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
  `yearly` int(10) NOT NULL,
  `production_target` int(255) NOT NULL,
  `due` int(255) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jfcl`
--

LOCK TABLES `jfcl` WRITE;
/*!40000 ALTER TABLE `jfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `jfcl` VALUES (1,'jfcl',20231,'2023-07-04',60,60,60,450000,449980,80,''),(2,'jfcl',202410,'2024-04-28',20,20,80,0,0,80,''),(3,'jfcl',202411,'2024-05-02',10,10,90,0,-90,0,'');
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
  `yearly` int(10) NOT NULL,
  `production_target` int(255) NOT NULL,
  `due` int(255) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sfcl`
--

LOCK TABLES `sfcl` WRITE;
/*!40000 ALTER TABLE `sfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sfcl` VALUES (1,'sfcl',20231,'2023-07-01',10,10,10,450000,449990,80,''),(2,'sfcl',20231,'2023-07-04',20,30,30,450000,449970,80,''),(3,'sfcl',20231,'2023-07-05',0,30,30,450000,449970,80,'Due Low NG pressure '),(4,'sfcl',20231,'2023-07-07',20,50,50,450000,449950,80,''),(5,'sfcl',202312,'2023-06-29',20,20,70,450000,449930,80,''),(6,'sfcl',20232,'2023-08-01',12,12,82,450000,449918,80,''),(7,'sfcl',20232,'2023-08-11',20,32,102,450000,449898,80,''),(8,'sfcl',20232,'2023-08-15',10,42,112,450000,449888,80,''),(9,'sfcl',20232,'2023-08-16',20,62,132,450000,449868,80,''),(10,'sfcl',20232,'2023-08-18',20,82,152,450000,449848,80,''),(11,'sfcl',20232,'2023-08-05',20,102,172,450000,449828,80,''),(12,'sfcl',20231,'2023-07-26',10,112,182,450000,449818,80,''),(13,'sfcl',20231,'2023-07-14',10,122,192,450000,449808,80,''),(14,'sfcl',20231,'2023-07-10',10,132,202,450000,449798,80,''),(15,'sfcl',20231,'2023-07-11',20,152,222,450000,449778,80,''),(16,'sfcl',20232,'2023-08-17',10,162,232,450000,449768,80,''),(17,'sfcl',20236,'2023-12-30',30,30,262,450000,449758,80,''),(18,'sfcl',202410,'2024-04-28',20,20,282,0,0,80,''),(32,'sfcl',202411,'2024-05-02',30,30,312,450000,449688,0,'');
/*!40000 ALTER TABLE `sfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sfcl` with 19 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_table`
--

LOCK TABLES `target_table` WRITE;
/*!40000 ALTER TABLE `target_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `target_table` VALUES (5,'sfcl',400000,'2023-07-01','2024-06-30'),(6,'jfcl',0,'2023-07-01','2024-06-30'),(7,'jfcl',0,'2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `target_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `target_table` with 3 row(s)
--

--
-- Table structure for table `tspcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tspcl` (
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
-- Dumping data for table `tspcl`
--

LOCK TABLES `tspcl` WRITE;
/*!40000 ALTER TABLE `tspcl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `tspcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tspcl` with 0 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'sfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','2022-12-30 16:12:35'),(2,'jfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','2023-02-21 16:20:41'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','2022-12-30 16:54:22'),(4,'emran','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','2023-02-21 06:50:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 4 row(s)
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

-- Dump completed on: Thu, 02 May 2024 11:42:23 +0200
