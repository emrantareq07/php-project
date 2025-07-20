-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: dfms_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Mon, 13 Jan 2025 10:01:56 +0100

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
  `factory_name` varchar(255) NOT NULL DEFAULT 'afccl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '528000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '528000	',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `afccl`
--

LOCK TABLES `afccl` WRITE;
/*!40000 ALTER TABLE `afccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `afccl` VALUES (1,'afccl','Urea',202412,'2024-12-31',0,12,8,0,'today dailly 0.and yearlu cumulative till date 0.','528000','528000	',0,0,0),(2,'afccl','Urea',202501,'2025-01-01',0,13,8,0,'','528000','528000	',0,0,0),(3,'afccl','Urea',202501,'2025-01-02',0,13,8,0,'','528000','528000	',0,0,0),(4,'afccl','Urea',202501,'2025-01-03',0,13,8,0,'','528000','528000	',0,0,0),(5,'afccl','Urea',202501,'2025-01-04',0,13,8,0,'','528000','528000	',0,0,0),(6,'afccl','Urea',202501,'2025-01-05',0,13,8,0,'','528000','528000	',0,0,0),(7,'afccl','Urea',202501,'2025-01-06',0,13,8,0,'','528000','528000	',0,0,0),(8,'afccl','Urea',202501,'2025-01-07',0,13,8,0,'','528000','528000	',0,0,0),(9,'afccl','Urea',202501,'2025-01-08',0,13,8,0,'','528000','528000	',0,0,0),(10,'afccl','Urea',202501,'2025-01-09',0,13,8,0,'','528000','528000	',0,0,0),(11,'afccl','Urea',202501,'2025-01-10',0,13,8,0,'','528000','528000	',0,0,0),(12,'afccl','Urea',202501,'2025-01-11',0,13,8,0,'','528000','528000	',0,0,0);
/*!40000 ALTER TABLE `afccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `afccl` with 12 row(s)
--

--
-- Table structure for table `bisf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bisf` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'bisf',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '580000',
  `monthly_target` float NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  `sanitary_installed_capacity` varchar(50) NOT NULL DEFAULT '3400',
  `insulator_installed_capacity` varchar(50) NOT NULL DEFAULT '1700',
  `refractories_installed_capacity` varchar(50) NOT NULL DEFAULT '1700',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bisf`
--

LOCK TABLES `bisf` WRITE;
/*!40000 ALTER TABLE `bisf` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `bisf` VALUES (1,'bisf','sanitary',202412,'2024-12-31',371.73999023438,24,1,0,'today daily 1.64.and yearly cumulative till date 371.74.','580000','580000',0,0,0,'3400','1700','1700'),(2,'bisf','insulator',202412,'2024-12-31',0,25,2,0,'today daily 0.and yearly cumulative till date 0.','580000','580000',0,0,0,'3400','1700','1700'),(3,'bisf','refractories',202412,'2024-12-31',87.470001220703,26,3,0,'today daily 0.20 and yearly cumulative till date 87.47.','580000','580000',0,0,0,'3400','1700','1700'),(4,'bisf','sanitary',202501,'2025-01-01',1.61,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(5,'bisf','insulator',202501,'2025-01-01',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(6,'bisf','refractories',202501,'2025-01-01',0.05,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(7,'bisf','sanitary',202501,'2025-01-02',1.66,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(8,'bisf','insulator',202501,'2025-01-02',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(9,'bisf','refractories',202501,'2025-01-02',0.02,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(10,'bisf','sanitary',202501,'2025-01-03',0.32,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(11,'bisf','insulator',202501,'2025-01-03',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(12,'bisf','refractories',202501,'2025-01-03',0.02,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(13,'bisf','sanitary',202501,'2025-01-04',1.18,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(14,'bisf','insulator',202501,'2025-01-04',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(15,'bisf','refractories',202501,'2025-01-04',0.02,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(16,'bisf','sanitary',202501,'2025-01-05',1.62,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(17,'bisf','insulator',202501,'2025-01-05',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(18,'bisf','refractories',202501,'2025-01-05',0.02,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(19,'bisf','sanitary',202501,'2025-01-06',2.52,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(20,'bisf','insulator',202501,'2025-01-06',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(21,'bisf','refractories',202501,'2025-01-06',0.02,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(22,'bisf','sanitary',202501,'2025-01-07',0.94,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(23,'bisf','insulator',202501,'2025-01-07',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(24,'bisf','refractories',202501,'2025-01-07',0.02,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(25,'bisf','sanitary',202501,'2025-01-08',0,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(26,'bisf','insulator',202501,'2025-01-08',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(27,'bisf','refractories',202501,'2025-01-08',0.2,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(28,'bisf','sanitary',202501,'2025-01-09',1.12,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(29,'bisf','insulator',202501,'2025-01-09',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(30,'bisf','refractories',202501,'2025-01-09',0.2,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(31,'bisf','sanitary',202501,'2025-01-10',1.55,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(32,'bisf','insulator',202501,'2025-01-10',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(33,'bisf','refractories',202501,'2025-01-10',0.2,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700'),(34,'bisf','sanitary',202501,'2025-01-11',1.6,1,1,0,'','580000','580000',0,0,0,'3400','1700','1700'),(35,'bisf','insulator',202501,'2025-01-11',0,2,2,0,'','580000','580000',0,0,0,'3400','1700','1700'),(36,'bisf','refractories',202501,'2025-01-11',0.2,3,3,0,'','580000','580000',0,0,0,'3400','1700','1700');
/*!40000 ALTER TABLE `bisf` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `bisf` with 36 row(s)
--

--
-- Table structure for table `cccl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cccl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'cccl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '190000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '190000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cccl`
--

LOCK TABLES `cccl` WRITE;
/*!40000 ALTER TABLE `cccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cccl` VALUES (1,'cccl','Cement',202412,'2024-12-31',0,20,12,0,'today daily 0.and yearly cumulative till date 0.','190000','190000',0,0,0),(2,'cccl','Cement',202501,'2025-01-01',0,21,12,0,'','190000','190000',0,0,0),(3,'cccl','Cement',202501,'2025-01-02',0,21,12,0,'','190000','190000',0,0,0),(4,'cccl','Cement',202501,'2025-01-03',0,21,12,0,'','190000','190000',0,0,0),(5,'cccl','Cement',202501,'2025-01-04',0,21,12,0,'','190000','190000',0,0,0),(6,'cccl','Cement',202501,'2025-01-05',0,21,12,0,'','190000','190000',0,0,0),(7,'cccl','Cement',202501,'2025-01-06',0,21,12,0,'','190000','190000',0,0,0),(8,'cccl','Cement',202501,'2025-01-07',0,21,12,0,'','190000','190000',0,0,0),(9,'cccl','Cement',202501,'2025-01-08',0,21,12,0,'','190000','190000',0,0,0),(10,'cccl','Cement',202501,'2025-01-09',0,21,12,0,'','190000','190000',0,0,0),(11,'cccl','Cement',202501,'2025-01-10',0,21,12,0,'','190000','190000',0,0,0),(12,'cccl','Cement',202501,'2025-01-11',0,21,12,0,'','190000','190000',0,0,0);
/*!40000 ALTER TABLE `cccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cccl` with 12 row(s)
--

--
-- Table structure for table `cufl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cufl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'cufl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '561000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '561000	',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cufl`
--

LOCK TABLES `cufl` WRITE;
/*!40000 ALTER TABLE `cufl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cufl` VALUES (1,'cufl','Urea',202412,'2024-12-31',42563,10,7,0,'today dailly 443.and yearlu cumulative till date 42563.','561000','561000	',0,0,0),(2,'cufl','Urea',202501,'2025-01-01',1174,11,7,0,'','561000','561000	',0,0,0),(3,'cufl','Urea',202501,'2025-01-02',1175,11,7,0,'','561000','561000	',0,0,0),(4,'cufl','Urea',202501,'2025-01-03',907,11,7,0,'','561000','561000	',0,0,0),(5,'cufl','Urea',202501,'2025-01-04',0,11,7,0,'','561000','561000	',0,0,0),(6,'cufl','Urea',202501,'2025-01-05',0,11,7,0,'','561000','561000	',0,0,0),(7,'cufl','Urea',202501,'2025-01-06',0,11,7,0,'','561000','561000	',0,0,0),(8,'cufl','Urea',202501,'2025-01-07',0,11,7,0,'','561000','561000	',0,0,0),(9,'cufl','Urea',202501,'2025-01-08',0,11,7,0,'','561000','561000	',0,0,0),(10,'cufl','Urea',202501,'2025-01-09',0,11,7,0,'','561000','561000	',0,0,0),(11,'cufl','Urea',202501,'2025-01-10',0,11,7,0,'','561000','561000	',0,0,0),(12,'cufl','Urea',202501,'2025-01-11',0,11,7,0,'','561000','561000	',0,0,0);
/*!40000 ALTER TABLE `cufl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cufl` with 12 row(s)
--

--
-- Table structure for table `dapfcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dapfcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'dapfcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '528000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '528000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dapfcl`
--

LOCK TABLES `dapfcl` WRITE;
/*!40000 ALTER TABLE `dapfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dapfcl` VALUES (1,'dapfcl','DAP',202412,'2024-12-31',29957,16,10,0,'today daily 0.and yearly cumulative till date 29957.','528000','528000',0,0,0),(2,'dapfcl','DAP',202501,'2025-01-01',0,17,10,0,'','528000','528000',0,0,0),(3,'dapfcl','DAP',202501,'2025-01-02',0,17,10,0,'','528000','528000',0,0,0),(4,'dapfcl','DAP',202501,'2025-01-03',0,17,10,0,'','528000','528000',0,0,0),(5,'dapfcl','DAP',202501,'2025-01-04',0,17,10,0,'','528000','528000',0,0,0),(6,'dapfcl','DAP',202501,'2025-01-05',0,17,10,0,'','528000','528000',0,0,0),(7,'dapfcl','DAP',202501,'2025-01-06',0,17,10,0,'','528000','528000',0,0,0),(8,'dapfcl','DAP',202501,'2025-01-07',0,17,10,0,'','528000','528000',0,0,0),(9,'dapfcl','DAP',202501,'2025-01-08',0,17,10,0,'','528000','528000',0,0,0),(10,'dapfcl','DAP',202501,'2025-01-09',0,17,10,0,'','528000','528000',0,0,0),(11,'dapfcl','DAP',202501,'2025-01-10',0,17,10,0,'','528000','528000',0,0,0),(12,'dapfcl','DAP',202501,'2025-01-11',0,17,10,0,'','528000','528000',0,0,0);
/*!40000 ALTER TABLE `dapfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dapfcl` with 12 row(s)
--

--
-- Table structure for table `gpfplc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpfplc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'gpfplc',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '924000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '924000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gpfplc`
--

LOCK TABLES `gpfplc` WRITE;
/*!40000 ALTER TABLE `gpfplc` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `gpfplc` VALUES (1,'gpfplc','Urea',202412,'2024-12-31',473575,4,4,0,'today dailly 1503.and yearlu cumulative till date 473575.','924000','924000',0,0,0),(2,'gpfplc','Urea',202501,'2025-01-01',2188,5,4,0,'','924000','924000',0,0,0),(3,'gpfplc','Urea',202501,'2025-01-02',2157,5,4,0,'','924000','924000',0,0,0),(4,'gpfplc','Urea',202501,'2025-01-03',1923,5,4,0,'','924000','924000',0,0,0),(5,'gpfplc','Urea',202501,'2025-01-04',2593,5,4,0,'','924000','924000',0,0,0),(6,'gpfplc','Urea',202501,'2025-01-05',2805,5,4,0,'','924000','924000',0,0,0),(7,'gpfplc','Urea',202501,'2025-01-06',2812,5,4,0,'','924000','924000',0,0,0),(8,'gpfplc','Urea',202501,'2025-01-07',2837,5,4,0,'','924000','924000',0,0,0),(9,'gpfplc','Urea',202501,'2025-01-08',2821,5,4,0,'','924000','924000',0,0,0),(10,'gpfplc','Urea',202501,'2025-01-09',2807,5,4,0,'','924000','924000',0,0,0),(11,'gpfplc','Urea',202501,'2025-01-10',2829,5,4,0,'','924000','924000',0,0,0),(12,'gpfplc','Urea',202501,'2025-01-11',2827,5,4,0,'','924000','924000',0,0,0);
/*!40000 ALTER TABLE `gpfplc` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `gpfplc` with 12 row(s)
--

--
-- Table structure for table `jfcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jfcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'jfcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '561000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '561000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jfcl`
--

LOCK TABLES `jfcl` WRITE;
/*!40000 ALTER TABLE `jfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `jfcl` VALUES (1,'jfcl','Urea',202412,'2024-12-31',0,8,6,0,'	today dailly 0.and yearlu cumulative till date 0.','561000','561000',0,0,0),(2,'jfcl','Urea',202501,'2025-01-01',0,9,6,0,'','561000','561000',0,0,0),(3,'jfcl','Urea',202501,'2025-01-02',0,9,6,0,'','561000','561000',0,0,0),(4,'jfcl','Urea',202501,'2025-01-03',0,9,6,0,'','561000','561000',0,0,0),(5,'jfcl','Urea',202501,'2025-01-04',0,9,6,0,'','561000','561000',0,0,0),(6,'jfcl','Urea',202501,'2025-01-05',0,9,6,0,'','561000','561000',0,0,0),(7,'jfcl','Urea',202501,'2025-01-06',0,9,6,0,'','561000','561000',0,0,0),(8,'jfcl','Urea',202501,'2025-01-07',0,9,6,0,'','561000','561000',0,0,0),(9,'jfcl','Urea',202501,'2025-01-08',0,9,6,0,'','561000','561000',0,0,0),(10,'jfcl','Urea',202501,'2025-01-09',0,9,6,0,'','561000','561000',0,0,0),(11,'jfcl','Urea',202501,'2025-01-10',0,9,6,0,'','561000','561000',0,0,0),(12,'jfcl','Urea',202501,'2025-01-11',0,9,6,0,'','561000','561000',0,0,0);
/*!40000 ALTER TABLE `jfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `jfcl` with 12 row(s)
--

--
-- Table structure for table `kpml`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpml` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'kpml',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '30000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '30000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpml`
--

LOCK TABLES `kpml` WRITE;
/*!40000 ALTER TABLE `kpml` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `kpml` VALUES (1,'kpml','Paper',202412,'2024-12-31',482.78,18,11,0,'today daily 0.and yearly cumulative till date 482.78.','30000','30000',0,0,0),(2,'kpml','Paper',202501,'2025-01-01',0,19,11,0,'','30000','30000',0,0,0),(3,'kpml','Paper',202501,'2025-01-02',0,19,11,0,'','30000','30000',0,0,0),(4,'kpml','Paper',202501,'2025-01-03',0,19,11,0,'','30000','30000',0,0,0),(5,'kpml','Paper',202501,'2025-01-04',0,19,11,0,'','30000','30000',0,0,0),(6,'kpml','Paper',202501,'2025-01-05',0,19,11,0,'','30000','30000',0,0,0),(7,'kpml','Paper',202501,'2025-01-06',0,19,11,0,'','30000','30000',0,0,0),(8,'kpml','Paper',202501,'2025-01-07',0,19,11,0,'','30000','30000',0,0,0),(9,'kpml','Paper',202501,'2025-01-08',9.28,19,11,0,'','30000','30000',0,0,0),(10,'kpml','Paper',202501,'2025-01-09',18.54,19,11,0,'','30000','30000',0,0,0),(11,'kpml','Paper',202501,'2025-01-10',26.42,19,11,0,'','30000','30000',0,0,0),(12,'kpml','Paper',202501,'2025-01-11',24.59,19,11,0,'','30000','30000',0,0,0);
/*!40000 ALTER TABLE `kpml` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kpml` with 12 row(s)
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
  `target` double NOT NULL,
  `target_date` date NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthly_target`
--

LOCK TABLES `monthly_target` WRITE;
/*!40000 ALTER TABLE `monthly_target` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `monthly_target` VALUES (1,'bisf','sanitary',106,'2025-01-01','2024-07-01','2025-06-30'),(2,'bisf','insulator',76,'2025-01-01','2024-07-01','2025-06-30'),(3,'bisf','refractories',18,'2025-01-01','2024-07-01','2025-06-30'),(4,'gpfplc','Urea',0,'2024-12-31','2024-07-01','2025-06-30'),(5,'gpfplc','Urea',76000,'2025-01-01','2024-07-01','2025-06-30'),(6,'sfcl','Urea',0,'2024-12-31','2024-07-01','2025-06-30'),(7,'sfcl','Urea',43000,'2025-01-01','2024-07-01','2025-06-30'),(8,'jfcl','Urea',0,'2024-12-31','2024-07-01','2025-06-30'),(9,'jfcl','Urea',27000,'2025-01-01','2024-07-01','2025-06-30'),(10,'cufl','Urea',0,'2024-12-31','2024-07-01','2025-06-30'),(11,'cufl','Urea',31000,'2025-01-01','2024-07-01','2025-06-30'),(12,'afccl','Urea',0,'2024-12-31','2024-07-01','2025-06-30'),(13,'afccl','Urea',21000,'2025-01-01','2024-07-01','2025-06-30'),(14,'tspcl','TSP',0,'2024-12-31','2024-07-01','2025-06-30'),(15,'tspcl','TSP',9000,'2025-01-01','2024-07-01','2025-06-30'),(16,'dapfcl','DAP',0,'2024-12-31','2024-07-01','2025-06-30'),(17,'dapfcl','DAP',12700,'2025-01-01','2024-07-01','2025-06-30'),(18,'kpml','Paper',0,'2024-12-31','2024-07-01','2025-06-30'),(19,'kpml','Paper',420,'2025-01-01','2024-07-01','2025-06-30'),(20,'cccl','Cement',0,'2024-12-31','2024-07-01','2025-06-30'),(21,'cccl','Cement',0,'2025-01-01','2024-07-01','2025-06-30'),(22,'ugsf','Sheet Glass',0,'2024-12-31','2024-07-01','2025-06-30'),(23,'ugsf','Sheet Glass',0,'2025-01-01','2024-07-01','2025-06-30'),(24,'bisf','sanitary',0,'2024-12-31','2024-07-01','2025-06-30'),(25,'bisf','insulator',0,'2024-12-31','2024-07-01','2025-06-30'),(26,'bisf','refractories',0,'2024-12-31','2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `monthly_target` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `monthly_target` with 26 row(s)
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_urea`
--

LOCK TABLES `report_urea` WRITE;
/*!40000 ALTER TABLE `report_urea` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `report_urea` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `report_urea` with 0 row(s)
--

--
-- Table structure for table `sfcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sfcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'sfcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sfcl`
--

LOCK TABLES `sfcl` WRITE;
/*!40000 ALTER TABLE `sfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sfcl` VALUES (1,'sfcl','Urea',202412,'2024-12-31',126507,6,5,0,'today daily 1140.and yearly cumulative till date 126507.','580000','580000',0,0,0),(2,'sfcl','Urea',202501,'2025-01-01',1139,7,5,0,'','580000','580000',0,0,0),(3,'sfcl','Urea',202501,'2025-01-02',1140,7,5,0,'','580000','580000',0,0,0),(4,'sfcl','Urea',202501,'2025-01-03',1140,7,5,0,'','580000','580000',0,0,0),(5,'sfcl','Urea',202501,'2025-01-04',1140,7,5,0,'','580000','580000',0,0,0),(6,'sfcl','Urea',202501,'2025-01-05',1139,7,5,0,'','580000','580000',0,0,0),(7,'sfcl','Urea',202501,'2025-01-06',1140,7,5,0,'','580000','580000',0,0,0),(8,'sfcl','Urea',202501,'2025-01-07',1140,7,5,0,'','580000','580000',0,0,0),(9,'sfcl','Urea',202501,'2025-01-08',1139,7,5,0,'','580000','580000',0,0,0),(10,'sfcl','Urea',202501,'2025-01-09',1140,7,5,0,'','580000','580000',0,0,0),(11,'sfcl','Urea',202501,'2025-01-10',1139,7,5,0,'','580000','580000',0,0,0),(12,'sfcl','Urea',202501,'2025-01-11',1140,7,5,0,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `sfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sfcl` with 12 row(s)
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
  `target` double NOT NULL,
  `fiscalstart` date NOT NULL,
  `fiscalend` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_table`
--

LOCK TABLES `target_table` WRITE;
/*!40000 ALTER TABLE `target_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `target_table` VALUES (1,'bisf','sanitary',1260,'2024-07-01','2025-06-30'),(2,'bisf','insulator',900,'2024-07-01','2025-06-30'),(3,'bisf','refractories',210,'2024-07-01','2025-06-30'),(4,'gpfplc','Urea',900000,'2024-07-01','2025-06-30'),(5,'sfcl','Urea',500000,'2024-07-01','2025-06-30'),(6,'jfcl','Urea',315000,'2024-07-01','2025-06-30'),(7,'cufl','Urea',350000,'2024-07-01','2025-06-30'),(8,'afccl','Urea',240000,'2024-07-01','2025-06-30'),(9,'tspcl','TSP',110000,'2024-07-01','2025-06-30'),(10,'dapfcl','DAP',150000,'2024-07-01','2025-06-30'),(11,'kpml','Paper',5000,'2024-07-01','2025-06-30'),(12,'cccl','Cement',0,'2024-07-01','2025-06-30'),(13,'ugsf','Sheet Glass',0,'2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `target_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `target_table` with 13 row(s)
--

--
-- Table structure for table `tspcl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tspcl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'tspcl',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '100000',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '100000',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tspcl`
--

LOCK TABLES `tspcl` WRITE;
/*!40000 ALTER TABLE `tspcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tspcl` VALUES (1,'tspcl','TSP',202412,'2024-12-31',20690,14,9,0,'today daily 300.and yearly cumulative till date 20690.','100000','100000',0,0,0),(2,'tspcl','TSP',202501,'2025-01-01',310,15,9,0,'','100000','100000',0,0,0),(3,'tspcl','TSP',202501,'2025-01-02',305,15,9,0,'','100000','100000',0,0,0),(4,'tspcl','TSP',202501,'2025-01-03',300,15,9,0,'','100000','100000',0,0,0),(5,'tspcl','TSP',202501,'2025-01-04',320,15,9,0,'','100000','100000',0,0,0),(6,'tspcl','TSP',202501,'2025-01-05',310,15,9,0,'','100000','100000',0,0,0),(7,'tspcl','TSP',202501,'2025-01-06',320,15,9,0,'','100000','100000',0,0,0),(8,'tspcl','TSP',202501,'2025-01-07',310,15,9,0,'','100000','100000',0,0,0),(9,'tspcl','TSP',202501,'2025-01-08',320,15,9,0,'','100000','100000',0,0,0),(10,'tspcl','TSP',202501,'2025-01-09',300,15,9,0,'','100000','100000',0,0,0),(11,'tspcl','TSP',202501,'2025-01-10',310,15,9,0,'','100000','100000',0,0,0),(12,'tspcl','TSP',202501,'2025-01-11',320,15,9,0,'','100000','100000',0,0,0);
/*!40000 ALTER TABLE `tspcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tspcl` with 12 row(s)
--

--
-- Table structure for table `ugsf`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ugsf` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL DEFAULT 'ugsf',
  `product_produce` varchar(50) NOT NULL,
  `month_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `daily` double NOT NULL,
  `month_code` int(100) NOT NULL,
  `year_code` int(100) NOT NULL,
  `plant_load` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `installed_capacity` varchar(50) NOT NULL DEFAULT '18.67',
  `attain_capacity` varchar(50) NOT NULL DEFAULT '18.67',
  `monthly_target` int(11) NOT NULL,
  `monthly_due` int(11) NOT NULL,
  `previous_day_prod` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ugsf`
--

LOCK TABLES `ugsf` WRITE;
/*!40000 ALTER TABLE `ugsf` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `ugsf` VALUES (1,'ugsf','Sheet Glass',202501,'2024-12-31',0,22,13,0,'today daily 0.and yearly cumulative till date 0.','18.67','18.67',0,0,0),(2,'ugsf','Sheet Glass',202501,'2025-01-01',0,22,13,0,'','18.67','18.67',0,0,0),(3,'ugsf','Sheet Glass',202501,'2025-01-02',0,23,13,0,'','18.67','18.67',0,0,0),(4,'ugsf','Sheet Glass',202501,'2025-01-03',0,23,13,0,'','18.67','18.67',0,0,0),(5,'ugsf','Sheet Glass',202501,'2025-01-04',0,23,13,0,'','18.67','18.67',0,0,0),(6,'ugsf','Sheet Glass',202501,'2025-01-05',0,23,13,0,'','18.67','18.67',0,0,0),(7,'ugsf','Sheet Glass',202501,'2025-01-06',0,23,13,0,'','18.67','18.67',0,0,0),(8,'ugsf','Sheet Glass',202501,'2025-01-07',0,23,13,0,'','18.67','18.67',0,0,0),(9,'ugsf','Sheet Glass',202501,'2025-01-08',0,23,13,0,'','18.67','18.67',0,0,0),(10,'ugsf','Sheet Glass',202501,'2025-01-09',0,23,13,0,'','18.67','18.67',0,0,0),(11,'ugsf','Sheet Glass',202501,'2025-01-10',0,23,13,0,'','18.67','18.67',0,0,0),(12,'ugsf','Sheet Glass',202501,'2025-01-11',0,23,13,0,'','18.67','18.67',0,0,0);
/*!40000 ALTER TABLE `ugsf` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `ugsf` with 12 row(s)
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

-- Dump completed on: Mon, 13 Jan 2025 10:01:56 +0100
