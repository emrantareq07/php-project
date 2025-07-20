-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: dfms_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Tue, 07 Jan 2025 05:52:28 +0100

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
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `afccl`
--

LOCK TABLES `afccl` WRITE;
/*!40000 ALTER TABLE `afccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `afccl` VALUES (1,'afccl','Urea',202407,'2024-07-01',100,10,10,80,'','580000','580000',0,0,0),(2,'afccl','Urea',202407,'2024-07-02',200,10,10,80,'','580000','580000',0,0,0),(3,'afccl','Urea',202407,'2024-07-25',50,10,10,80,'','580000','580000',0,0,0),(4,'afccl','Urea',202408,'2024-08-01',100,11,10,80,'','580000','580000',0,0,0),(5,'afccl','Urea',202408,'2024-08-03',50,11,10,22,'','580000','580000',0,0,0),(6,'afccl','Urea',202409,'2024-09-02',100,12,10,80,'','580000','580000',0,0,0),(7,'afccl','Urea',202410,'2024-10-03',100,13,10,80,'','580000','580000',0,0,0),(8,'afccl','Urea',202507,'2025-07-01',100,27,11,80,'','580000','580000',0,0,0),(9,'afccl','Urea',202412,'2024-12-29',100,14,10,22,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `afccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `afccl` with 9 row(s)
--

--
-- Table structure for table `bisf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bisf` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bisf`
--

LOCK TABLES `bisf` WRITE;
/*!40000 ALTER TABLE `bisf` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `bisf` VALUES (1,'bisf','sanitary',202407,'2024-07-01',100,15,1,80,'','580000','580000',0,0,0),(2,'bisf','insulator',202407,'2024-07-01',100,16,2,80,'','580000','580000',0,0,0),(3,'bisf','refractories',202407,'2024-07-01',200,17,3,80,'','580000','580000',0,0,0),(4,'bisf','sanitary',202407,'2024-07-02',50,15,1,80,'','580000','580000',0,0,0),(5,'bisf','insulator',202407,'2024-07-02',50,16,2,200,'','580000','580000',0,0,0),(6,'bisf','refractories',202407,'2024-07-02',50,17,3,22,'','580000','580000',0,0,0),(7,'bisf','sanitary',202407,'2024-07-25',10,15,1,80,'','580000','580000',0,0,0),(8,'bisf','insulator',202407,'2024-07-25',50,16,2,22,'','580000','580000',0,0,0),(9,'bisf','refractories',202407,'2024-07-25',100,17,3,80,'','580000','580000',0,0,0),(10,'bisf','sanitary',202408,'2024-08-01',100,18,1,80,'','580000','580000',0,0,0),(11,'bisf','insulator',202408,'2024-08-01',100,19,2,80,'','580000','580000',0,0,0),(12,'bisf','refractories',202408,'2024-08-01',100,20,3,80,'','580000','580000',0,0,0),(13,'bisf','sanitary',202408,'2024-08-03',10,18,1,10,'','580000','580000',0,0,0),(14,'bisf','insulator',202408,'2024-08-03',10,19,2,10,'','580000','580000',0,0,0),(15,'bisf','refractories',202408,'2024-08-03',10,20,3,10,'','580000','580000',0,0,0),(16,'bisf','sanitary',202409,'2024-09-02',20,21,1,10,'','580000','580000',0,0,0),(17,'bisf','insulator',202409,'2024-09-02',20,22,2,20,'','580000','580000',0,0,0),(18,'bisf','refractories',202409,'2024-09-02',20,23,3,20,'','580000','580000',0,0,0),(19,'bisf','sanitary',202410,'2024-10-03',30,24,1,10,'','580000','580000',0,0,0),(20,'bisf','insulator',202410,'2024-10-03',30,25,2,22,'','580000','580000',0,0,0),(21,'bisf','refractories',202410,'2024-10-03',30,26,3,22,'','580000','580000',0,0,0),(22,'bisf','sanitary',202507,'2025-07-01',100,28,12,80,'','580000','580000',0,0,0),(23,'bisf','insulator',202507,'2025-07-01',100,29,13,80,'','580000','580000',0,0,0),(24,'bisf','refractories',202507,'2025-07-01',100,30,14,80,'','580000','580000',0,0,0),(25,'bisf','sanitary',202412,'2024-12-29',100,1,1,80,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0),(26,'bisf','insulator',202412,'2024-12-29',0,2,2,0,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0),(27,'bisf','refractories',202412,'2024-12-29',10,3,3,0,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0);
/*!40000 ALTER TABLE `bisf` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `bisf` with 27 row(s)
--

--
-- Table structure for table `cccl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cccl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cccl`
--

LOCK TABLES `cccl` WRITE;
/*!40000 ALTER TABLE `cccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cccl` VALUES (1,'cccl','Cement',202412,'2024-12-26',30,9,9,100,'fffffffffffffffffffffffffffffffff','580000','580000',0,0,0),(2,'cccl','Cement',202412,'2024-12-29',100,9,9,0,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0);
/*!40000 ALTER TABLE `cccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cccl` with 2 row(s)
--

--
-- Table structure for table `cufl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cufl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cufl`
--

LOCK TABLES `cufl` WRITE;
/*!40000 ALTER TABLE `cufl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cufl` VALUES (1,'cufl','Urea',202412,'2024-12-26',200,5,5,22,'','580000','580000',0,0,0),(2,'cufl','Urea',202412,'2024-12-29',200,5,5,80,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0);
/*!40000 ALTER TABLE `cufl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cufl` with 2 row(s)
--

--
-- Table structure for table `dapfcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dapfcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dapfcl`
--

LOCK TABLES `dapfcl` WRITE;
/*!40000 ALTER TABLE `dapfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dapfcl` VALUES (1,'dapfcl','DAP',202412,'2024-12-26',40,8,8,100,'','580000','580000',0,0,0),(2,'dapfcl','DAP',202412,'2024-12-29',0,8,8,0,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0);
/*!40000 ALTER TABLE `dapfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dapfcl` with 2 row(s)
--

--
-- Table structure for table `gpfplc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpfplc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gpfplc`
--

LOCK TABLES `gpfplc` WRITE;
/*!40000 ALTER TABLE `gpfplc` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `gpfplc` VALUES (1,'gpfplc','Urea',202412,'2024-12-29',0,31,15,0,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0);
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
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jfcl`
--

LOCK TABLES `jfcl` WRITE;
/*!40000 ALTER TABLE `jfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `jfcl` VALUES (1,'jfcl','Urea',202412,'2024-12-26',190,7,7,80,'','580000','580000',0,0,0),(2,'jfcl','Urea',202412,'2024-12-29',0,7,7,0,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.','580000','580000',0,0,0);
/*!40000 ALTER TABLE `jfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `jfcl` with 2 row(s)
--

--
-- Table structure for table `kpml`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpml` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpml`
--

LOCK TABLES `kpml` WRITE;
/*!40000 ALTER TABLE `kpml` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `kpml` VALUES (1,'kpml','Paper',202412,'2024-12-29',100,32,16,0,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0);
/*!40000 ALTER TABLE `kpml` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kpml` with 1 row(s)
--

--
-- Table structure for table `monthly_target`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monthly_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL,
  `product_produce` varchar(255) NOT NULL,
  `target` int(100) NOT NULL,
  `target_date` date NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthly_target`
--

LOCK TABLES `monthly_target` WRITE;
/*!40000 ALTER TABLE `monthly_target` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `monthly_target` VALUES (1,'bisf','sanitary',20,'2024-12-26','2024-07-01','2025-06-30'),(2,'bisf','insulator',111,'2024-12-26','2024-07-01','2025-06-30'),(3,'bisf','refractories',2220,'2024-12-26','2024-07-01','2025-06-30'),(4,'sfcl','Urea',30000,'2024-12-26','2024-07-01','2025-06-30'),(5,'cufl','Urea',0,'2024-12-26','2024-07-01','2025-06-30'),(6,'tspcl','TSP',0,'2024-12-26','2024-07-01','2025-06-30'),(7,'jfcl','Urea',0,'2024-12-26','2024-07-01','2025-06-30'),(8,'dapfcl','DAP',2000,'2024-12-26','2024-07-01','2025-06-30'),(9,'cccl','Cement',20000,'2024-12-26','2024-07-01','2025-06-30'),(10,'afccl','Urea',500,'2024-07-01','2024-07-01','2025-06-30'),(11,'afccl','Urea',400000,'2024-08-01','2024-07-01','2025-06-30'),(12,'afccl','Urea',0,'2024-09-02','2024-07-01','2025-06-30'),(13,'afccl','Urea',0,'2024-10-03','2024-07-01','2025-06-30'),(14,'afccl','Urea',500000,'2024-12-30','2024-07-01','2025-06-30'),(15,'bisf','sanitary',0,'2024-07-01','2024-07-01','2025-06-30'),(16,'bisf','insulator',0,'2024-07-01','2024-07-01','2025-06-30'),(17,'bisf','refractories',0,'2024-07-01','2024-07-01','2025-06-30'),(18,'bisf','sanitary',0,'2024-08-01','2024-07-01','2025-06-30'),(19,'bisf','insulator',0,'2024-08-01','2024-07-01','2025-06-30'),(20,'bisf','refractories',0,'2024-08-01','2024-07-01','2025-06-30'),(21,'bisf','sanitary',0,'2024-09-02','2024-07-01','2025-06-30'),(22,'bisf','insulator',0,'2024-09-02','2024-07-01','2025-06-30'),(23,'bisf','refractories',0,'2024-09-02','2024-07-01','2025-06-30'),(24,'bisf','sanitary',0,'2024-10-03','2024-07-01','2025-06-30'),(25,'bisf','insulator',0,'2024-10-03','2024-07-01','2025-06-30'),(26,'bisf','refractories',0,'2024-10-03','2024-07-01','2025-06-30'),(27,'afccl','Urea',2000,'2025-07-01','2025-07-01','2026-06-30'),(28,'bisf','sanitary',0,'2025-07-01','2025-07-01','2026-06-30'),(29,'bisf','insulator',0,'2025-07-01','2025-07-01','2026-06-30'),(30,'bisf','refractories',0,'2025-07-01','2025-07-01','2026-06-30'),(31,'gpfplc','Urea',0,'2024-12-30','2024-07-01','2025-06-30'),(32,'kpml','Paper',0,'2024-12-29','2024-07-01','2025-06-30'),(33,'ugsf','Sheet Glass',0,'2024-12-29','2024-07-01','2025-06-30'),(34,'sfcl','Urea',0,'2025-01-07','2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `monthly_target` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `monthly_target` with 34 row(s)
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
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sfcl`
--

LOCK TABLES `sfcl` WRITE;
/*!40000 ALTER TABLE `sfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sfcl` VALUES (1,'sfcl','Urea',202412,'2024-12-26',100,4,4,80,'','580000','580000',0,0,0),(2,'sfcl','Urea',202412,'2024-12-29',40,4,4,100,'Dap1:this is the most intelisent and important purpose of our country of our countrty of dhakaDap1:this is the most intelisent and important purpose of our country of our countrty of dhakaDap1:this is the most intelisent and important purpose of our country of our countrty of dhakaDap1:this is the most intelisent and important purpose of our country of our countrty of dhaka','580000','580000',0,0,0),(3,'sfcl','Urea',202412,'2024-12-31',100,4,4,80,'','580000','580000',0,0,0),(4,'sfcl','Urea',202501,'2025-01-07',100,34,4,80,'','580000','580000',0,0,0);
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
  `product_produce` varchar(255) NOT NULL,
  `target` int(100) NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_table`
--

LOCK TABLES `target_table` WRITE;
/*!40000 ALTER TABLE `target_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `target_table` VALUES (1,'bisf','sanitary',60,'2024-07-01','2025-06-30'),(2,'bisf','insulator',555,'2024-07-01','2025-06-30'),(3,'bisf','refractories',1000,'2024-07-01','2025-06-30'),(4,'sfcl','Urea',240000,'2024-07-01','2025-06-30'),(5,'cufl','Urea',0,'2024-07-01','2025-06-30'),(6,'tspcl','TSP',0,'2024-07-01','2025-06-30'),(7,'jfcl','Urea',0,'2024-07-01','2025-06-30'),(8,'dapfcl','DAP',3000,'2024-07-01','2025-06-30'),(9,'cccl','Cement',2555,'2024-07-01','2025-06-30'),(10,'afccl','Urea',500000,'2024-07-01','2025-06-30'),(11,'afccl','Urea',1000,'2025-07-01','2026-06-30'),(12,'bisf','sanitary',0,'2025-07-01','2026-06-30'),(13,'bisf','insulator',0,'2025-07-01','2026-06-30'),(14,'bisf','refractories',0,'2025-07-01','2026-06-30'),(15,'gpfplc','Urea',0,'2024-07-01','2025-06-30'),(16,'kpml','Paper',0,'2024-07-01','2025-06-30'),(17,'ugsf','Sheet Glass',0,'2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `target_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `target_table` with 17 row(s)
--

--
-- Table structure for table `tspcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tspcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tspcl`
--

LOCK TABLES `tspcl` WRITE;
/*!40000 ALTER TABLE `tspcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tspcl` VALUES (1,'tspcl','TSP',202412,'2024-12-26',200,6,6,22,'','580000','580000',0,0,0),(2,'tspcl','TSP',202412,'2024-12-29',50,6,6,100,'dddddddddddddddddddddddddddddddddddddddddddd','580000','580000',0,0,0);
/*!40000 ALTER TABLE `tspcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tspcl` with 2 row(s)
--

--
-- Table structure for table `ugsf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ugsf` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(50) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` int(10) NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ugsf`
--

LOCK TABLES `ugsf` WRITE;
/*!40000 ALTER TABLE `ugsf` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `ugsf` VALUES (1,'ugsf','Sheet Glass',202412,'2024-12-29',100,33,17,80,'UGSFL (Glass sheet Factory)- Production stopped on 21/08/2023 due to gas disconnection from Furnace and shutdown of Furnace no.-2.\r\n','580000','580000',0,0,0);
/*!40000 ALTER TABLE `ugsf` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `ugsf` with 1 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'sfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-01 08:59:48'),(2,'jfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-02 08:59:54'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-29 04:57:15'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','','','2024-05-28 08:21:58'),(12,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','user@yahoo.com','2024-05-28 08:22:05'),(13,'cufl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','cufl@yahoo.com','2024-12-02 10:15:26'),(17,'tspcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','tspcl.md@bcic.gov.bd','2024-12-01 08:57:43'),(19,'dapfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','dap@yahoo.com','2024-12-29 04:57:19'),(20,'gpfplc','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','gpfplc@yahoo.com','2024-12-29 04:57:28'),(21,'bisf','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','','2024-12-22 10:46:15'),(22,'cccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','','2024-12-22 10:46:15'),(23,'ugsf','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','','2024-12-22 10:46:15'),(24,'kpml','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','','2024-12-22 10:46:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 13 row(s)
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

-- Dump completed on: Tue, 07 Jan 2025 05:52:28 +0100
