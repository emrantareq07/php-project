-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: dfms_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Wed, 01 Jan 2025 07:43:43 +0100

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `afccl`
--

LOCK TABLES `afccl` WRITE;
/*!40000 ALTER TABLE `afccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `afccl` VALUES (1,'afccl','Urea',202501,'2025-01-01',3000,11,10,100,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `afccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `afccl` with 1 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bisf`
--

LOCK TABLES `bisf` WRITE;
/*!40000 ALTER TABLE `bisf` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `bisf` VALUES (1,'bisf','sanitary',202412,'2024-12-26',100,1,1,80,'','580000','580000',0,0,0),(2,'bisf','insulator',202412,'2024-12-26',200,2,2,80,'','580000','580000',0,0,0),(3,'bisf','refractories',202412,'2024-12-26',100,3,3,55,'','580000','580000',0,0,0),(4,'bisf','sanitary',202412,'2024-12-29',30,1,1,100,'33333333333jjjj','580000','580000',0,0,0),(5,'bisf','sanitary',202412,'2024-12-31',30,1,1,100,'','580000','580000',0,0,0),(6,'bisf','insulator',202412,'2024-12-31',40,2,2,100,'','580000','580000',0,0,0),(7,'bisf','refractories',202412,'2024-12-31',10,3,3,100,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `bisf` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `bisf` with 7 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cccl`
--

LOCK TABLES `cccl` WRITE;
/*!40000 ALTER TABLE `cccl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cccl` VALUES (1,'cccl','Cement',202412,'2024-12-26',30,9,9,100,'fffffffffffffffffffffffffffffffff','580000','580000',0,0,0);
/*!40000 ALTER TABLE `cccl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cccl` with 1 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cufl`
--

LOCK TABLES `cufl` WRITE;
/*!40000 ALTER TABLE `cufl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cufl` VALUES (1,'cufl','Urea',202412,'2024-12-26',200,5,5,22,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `cufl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cufl` with 1 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dapfcl`
--

LOCK TABLES `dapfcl` WRITE;
/*!40000 ALTER TABLE `dapfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dapfcl` VALUES (1,'dapfcl','DAP',202412,'2024-12-26',40,8,8,100,'','580000','580000',0,0,0);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gpfplc`
--

LOCK TABLES `gpfplc` WRITE;
/*!40000 ALTER TABLE `gpfplc` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `gpfplc` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `gpfplc` with 0 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jfcl`
--

LOCK TABLES `jfcl` WRITE;
/*!40000 ALTER TABLE `jfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `jfcl` VALUES (1,'jfcl','Urea',202412,'2024-12-26',190,7,7,80,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `jfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `jfcl` with 1 row(s)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpml`
--

LOCK TABLES `kpml` WRITE;
/*!40000 ALTER TABLE `kpml` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `kpml` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kpml` with 0 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthly_target`
--

LOCK TABLES `monthly_target` WRITE;
/*!40000 ALTER TABLE `monthly_target` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `monthly_target` VALUES (1,'bisf','sanitary',20,'2024-12-26','2024-07-01','2025-06-30'),(2,'bisf','insulator',111,'2024-12-26','2024-07-01','2025-06-30'),(3,'bisf','refractories',2220,'2024-12-26','2024-07-01','2025-06-30'),(4,'sfcl','Urea',30000,'2024-12-26','2024-07-01','2025-06-30'),(5,'cufl','Urea',0,'2024-12-26','2024-07-01','2025-06-30'),(6,'tspcl','TSP',0,'2024-12-26','2024-07-01','2025-06-30'),(7,'jfcl','Urea',0,'2024-12-26','2024-07-01','2025-06-30'),(8,'dapfcl','DAP',2000,'2024-12-26','2024-07-01','2025-06-30'),(9,'cccl','Cement',20000,'2024-12-26','2024-07-01','2025-06-30'),(10,'sfcl','Urea',0,'2025-01-01','2024-07-01','2025-06-30'),(11,'afccl','Urea',0,'2025-01-01','2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `monthly_target` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `monthly_target` with 11 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sfcl`
--

LOCK TABLES `sfcl` WRITE;
/*!40000 ALTER TABLE `sfcl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sfcl` VALUES (1,'sfcl','Urea',202412,'2024-12-26',100,4,4,80,'','580000','580000',0,0,0),(2,'sfcl','Urea',202412,'2024-12-29',40,4,4,100,'ee','580000','580000',0,0,0),(3,'sfcl','Urea',202501,'2025-01-01',40,10,4,100,'','580000','580000',0,0,0);
/*!40000 ALTER TABLE `sfcl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sfcl` with 3 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_table`
--

LOCK TABLES `target_table` WRITE;
/*!40000 ALTER TABLE `target_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `target_table` VALUES (1,'bisf','sanitary',60,'2024-07-01','2025-06-30'),(2,'bisf','insulator',555,'2024-07-01','2025-06-30'),(3,'bisf','refractories',2147483647,'2024-07-01','2025-06-30'),(4,'sfcl','Urea',240000,'2024-07-01','2025-06-30'),(5,'cufl','Urea',0,'2024-07-01','2025-06-30'),(6,'tspcl','TSP',0,'2024-07-01','2025-06-30'),(7,'jfcl','Urea',0,'2024-07-01','2025-06-30'),(8,'dapfcl','DAP',3000,'2024-07-01','2025-06-30'),(9,'cccl','Cement',2555,'2024-07-01','2025-06-30'),(10,'afccl','Urea',0,'2024-07-01','2025-06-30');
/*!40000 ALTER TABLE `target_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `target_table` with 10 row(s)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ugsf`
--

LOCK TABLES `ugsf` WRITE;
/*!40000 ALTER TABLE `ugsf` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `ugsf` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `ugsf` with 0 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'sfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-01 08:59:48'),(2,'jfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-02 08:59:54'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','','2024-12-29 04:57:15'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','','','2024-05-28 08:21:58'),(12,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','user@yahoo.com','2024-05-28 08:22:05'),(13,'cufl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','cufl@yahoo.com','2024-12-02 10:15:26'),(17,'tspcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','tspcl.md@bcic.gov.bd','2024-12-01 08:57:43'),(19,'dapfcl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','dap@yahoo.com','2024-12-29 04:57:19'),(20,'gpfplc','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','urea','gpfplc@yahoo.com','2024-12-29 04:57:28'),(21,'bisf','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','','2024-12-22 10:46:15'),(22,'cccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','','2024-12-22 10:46:15'),(23,'ugsf','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','non-urea','','2024-12-22 10:46:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 12 row(s)
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

-- Dump completed on: Wed, 01 Jan 2025 07:43:43 +0100
