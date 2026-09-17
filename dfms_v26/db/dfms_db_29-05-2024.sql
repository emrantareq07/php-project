-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: sfms_db
-- ------------------------------------------------------
-- Server version 	10.4.24-MariaDB
-- Date: Wed, 29 May 2024 12:01:59 +0200

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
-- Table structure for table `kaliganj_buffer`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kaliganj_buffer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buffer_name` varchar(100) NOT NULL DEFAULT 'kaliganj_buffer',
  `date` date NOT NULL,
  `receive_import` int(11) NOT NULL,
  `receive_factory` int(11) NOT NULL,
  `delivery` int(11) NOT NULL,
  `total_stock` int(11) NOT NULL,
  `pipeline` int(11) NOT NULL,
  `month_id` int(11) NOT NULL,
  `concat_receive` varchar(255) NOT NULL,
  `concat_delivery` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kaliganj_buffer`
--

LOCK TABLES `kaliganj_buffer` WRITE;
/*!40000 ALTER TABLE `kaliganj_buffer` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `kaliganj_buffer` VALUES (20,'kaliganj_buffer','2024-05-29',0,0,0,0,100,202411,'',''),(21,'kaliganj_buffer','2024-06-01',10,0,0,10,90,202412,'10+0+','');
/*!40000 ALTER TABLE `kaliganj_buffer` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kaliganj_buffer` with 2 row(s)
--

--
-- Table structure for table `monthly_demand`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monthly_demand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `office_name` varchar(100) NOT NULL,
  `demand_amount` int(11) NOT NULL,
  `month_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthly_demand`
--

LOCK TABLES `monthly_demand` WRITE;
/*!40000 ALTER TABLE `monthly_demand` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `monthly_demand` VALUES (2,'2024-05-29','kaliganj_buffer',10000,202411),(3,'2024-06-01','kaliganj_buffer',0,202412);
/*!40000 ALTER TABLE `monthly_demand` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `monthly_demand` with 2 row(s)
--

--
-- Table structure for table `pipeline`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `from_office` varchar(50) NOT NULL,
  `to_office` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pipeline`
--

LOCK TABLES `pipeline` WRITE;
/*!40000 ALTER TABLE `pipeline` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `pipeline` VALUES (1,'2024-05-26','mongla_port','kaliganj_buffer',50,'accept'),(2,'2024-05-26','mongla_port','kaliganj_buffer',20,'accept'),(3,'2024-05-26','sfcl','kaliganj_buffer',100,'accept');
/*!40000 ALTER TABLE `pipeline` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pipeline` with 3 row(s)
--

--
-- Table structure for table `stock_mgtm`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_mgtm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buffer_name` varchar(100) NOT NULL,
  `stock_amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_mgtm`
--

LOCK TABLES `stock_mgtm` WRITE;
/*!40000 ALTER TABLE `stock_mgtm` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `stock_mgtm` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_mgtm` with 0 row(s)
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
  `office_type` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'','da39a3ee5e6b4b0d3255bfef95601890afd80709','','','','2024-05-26 05:02:52'),(2,'jfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','','2024-05-27 08:39:41'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','','2022-12-30 16:54:22'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','bcic_hq','','2024-05-25 07:21:42'),(12,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','user@yahoo.com','2024-05-20 09:23:36'),(13,'kaliganj_buffer','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','buffer','kaliganj_buffer@yahoo.com','2024-05-25 07:16:37'),(14,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','bcic_hq','admin@yahoo.com','2024-05-25 14:48:37'),(15,'monglaport','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','port_office','monglaport@yahoo.com','2024-05-27 08:29:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 8 row(s)
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

-- Dump completed on: Wed, 29 May 2024 12:01:59 +0200
