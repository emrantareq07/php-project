-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: sfms_db
-- ------------------------------------------------------
-- Server version 	10.4.24-MariaDB
-- Date: Sat, 01 Jun 2024 19:28:12 +0200

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kaliganj_buffer`
--

LOCK TABLES `kaliganj_buffer` WRITE;
/*!40000 ALTER TABLE `kaliganj_buffer` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `kaliganj_buffer` VALUES (26,'kaliganj_buffer','2024-05-31',10,10,10,10,440,202411,'10+10+','10+'),(27,'kaliganj_buffer','2024-06-01',20,15,0,45,305,202412,'10+10+10+5+','');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthly_demand`
--

LOCK TABLES `monthly_demand` WRITE;
/*!40000 ALTER TABLE `monthly_demand` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `monthly_demand` VALUES (11,'2024-06-01','kaliganj_buffer',2000,202412),(12,'2024-06-01','shiromoni_buffer',2000,202412);
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pipeline`
--

LOCK TABLES `pipeline` WRITE;
/*!40000 ALTER TABLE `pipeline` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `pipeline` VALUES (1,'2024-05-26','mongla_port','kaliganj_buffer',50,'accept'),(2,'2024-05-26','mongla_port','kaliganj_buffer',20,'accept'),(3,'2024-05-26','sfcl','kaliganj_buffer',200,'accept'),(7,'2024-05-30','mongla_port','kaliganj_buffer',10,'accept'),(8,'2024-05-30','mongla_port','kaliganj_buffer',100,'accept'),(9,'2024-06-01','chittagong_port','shiromoni_buffer',100,'accept'),(10,'2024-05-30','mongla_port','kaliganj_buffer',100,'accept'),(11,'2024-05-30','chittagonj_port','kaliganj_buffer',50,'accept'),(12,'2024-05-30','mongla_port','shiromoni_buffer',100,'accept'),(13,'2024-05-31','monglaport','kaliganj_buffer',50,'accept'),(14,'2024-05-31','mongla_port','shiromoni_buffer',50,'accept'),(15,'2024-05-31','mongla_port','shiromoni_buffer',100,'accept'),(16,'2024-06-01','mongla_port','kaliganj_buffer',50,'accept'),(18,'2024-06-01','mongla_port','shiromoni_buffer',100,'accept'),(19,'2024-06-01','mongla_port','kaliganj_buffer',50,'accept');
/*!40000 ALTER TABLE `pipeline` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pipeline` with 15 row(s)
--

--
-- Table structure for table `shiromoni_buffer`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shiromoni_buffer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buffer_name` varchar(100) NOT NULL DEFAULT 'shiromoni_buffer',
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shiromoni_buffer`
--

LOCK TABLES `shiromoni_buffer` WRITE;
/*!40000 ALTER TABLE `shiromoni_buffer` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `shiromoni_buffer` VALUES (26,'shiromoni_buffer','2024-05-31',20,10,0,30,600,202411,'10+5+10+5+',''),(27,'shiromoni_buffer','2024-06-01',40,35,45,30,475,202412,'10+10+10+5+10+10+10+10+','25+10+10+');
/*!40000 ALTER TABLE `shiromoni_buffer` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `shiromoni_buffer` with 2 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'shiromoni_buffer','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','buffer','','2024-05-30 08:49:12'),(2,'jfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','','2024-05-27 08:39:41'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','','2024-06-01 17:19:24'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','bcic_hq','','2024-05-25 07:21:42'),(12,'bcic_mkt','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','bcic_hq','user@yahoo.com','2024-06-01 06:48:45'),(13,'kaliganj_buffer','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','buffer','kaliganj_buffer@yahoo.com','2024-05-25 07:16:37'),(14,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','bcic_hq','admin@yahoo.com','2024-05-25 14:48:37'),(15,'mongla_port','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','port_office','monglaport@yahoo.com','2024-05-31 18:39:31'),(16,'chittagonj_port','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','port_office','chittagonj_port@yahoo.com','2024-05-30 07:12:50');
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

-- Dump completed on: Sat, 01 Jun 2024 19:28:12 +0200
