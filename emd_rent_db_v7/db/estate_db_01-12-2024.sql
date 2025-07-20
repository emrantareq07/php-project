-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: estate_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Sun, 01 Dec 2024 07:03:07 +0100

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
-- Table structure for table `13buf_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `13buf_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `13buf_tbl`
--

LOCK TABLES `13buf_tbl` WRITE;
/*!40000 ALTER TABLE `13buf_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `13buf_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `13buf_tbl` with 0 row(s)
--

--
-- Table structure for table `34buf_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `34buf_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `34buf_tbl`
--

LOCK TABLES `34buf_tbl` WRITE;
/*!40000 ALTER TABLE `34buf_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `34buf_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `34buf_tbl` with 0 row(s)
--

--
-- Table structure for table `abbankho_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abbankho_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abbankho_tbl`
--

LOCK TABLES `abbankho_tbl` WRITE;
/*!40000 ALTER TABLE `abbankho_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `abbankho_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `abbankho_tbl` with 0 row(s)
--

--
-- Table structure for table `abbankpb_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abbankpb_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abbankpb_tbl`
--

LOCK TABLES `abbankpb_tbl` WRITE;
/*!40000 ALTER TABLE `abbankpb_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `abbankpb_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `abbankpb_tbl` with 0 row(s)
--

--
-- Table structure for table `arjufood_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arjufood_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Nababpur Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arjufood_tbl`
--

LOCK TABLES `arjufood_tbl` WRITE;
/*!40000 ALTER TABLE `arjufood_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `arjufood_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `arjufood_tbl` with 0 row(s)
--

--
-- Table structure for table `bcicsamiti_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bcicsamiti_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bcicsamiti_tbl`
--

LOCK TABLES `bcicsamiti_tbl` WRITE;
/*!40000 ALTER TABLE `bcicsamiti_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `bcicsamiti_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `bcicsamiti_tbl` with 0 row(s)
--

--
-- Table structure for table `bdg_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bdg_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bdg_tbl`
--

LOCK TABLES `bdg_tbl` WRITE;
/*!40000 ALTER TABLE `bdg_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `bdg_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `bdg_tbl` with 0 row(s)
--

--
-- Table structure for table `beximco_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `beximco_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beximco_tbl`
--

LOCK TABLES `beximco_tbl` WRITE;
/*!40000 ALTER TABLE `beximco_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `beximco_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `beximco_tbl` with 0 row(s)
--

--
-- Table structure for table `carbon_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carbon_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carbon_tbl`
--

LOCK TABLES `carbon_tbl` WRITE;
/*!40000 ALTER TABLE `carbon_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `carbon_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `carbon_tbl` with 0 row(s)
--

--
-- Table structure for table `cccl_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cccl_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cccl_tbl`
--

LOCK TABLES `cccl_tbl` WRITE;
/*!40000 ALTER TABLE `cccl_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `cccl_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cccl_tbl` with 0 row(s)
--

--
-- Table structure for table `chainntrad_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chainntrad_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chainntrad_tbl`
--

LOCK TABLES `chainntrad_tbl` WRITE;
/*!40000 ALTER TABLE `chainntrad_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `chainntrad_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `chainntrad_tbl` with 0 row(s)
--

--
-- Table structure for table `company`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) NOT NULL,
  `tenants_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `company` VALUES (1,'abbankho_tbl','AB Bank H.O.','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(2,'abbankpb_tbl','AB Bank Principle Branch','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(3,'poton_tbl','Poton Traders','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(4,'mollik_tbl','Mollik Traders','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(5,'erres_tbl','E. R. Resourses','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(6,'mrtrading_tbl','M. R. Trading','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(7,'motalibasso_tbl','Motalib & Associates','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(8,'romanaent_tbl','Romana Enterprise','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(9,'rafirachi_tbl','Rafi & Rachi','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(10,'oyasistec_tbl','Oyasis Technologies','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(11,'mehedient_tbl','Mehedi Enterprise','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(12,'multiwaysmkt_tbl','Multi-Ways Marketing','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.'),(13,'demano_tbl','Demano Ltd.','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1012'),(14,'beximco_tbl','BEXIMCO','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1013'),(15,'bdg_tbl','Bangladesh Development Group','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1014'),(16,'creativep_tbl','Creative Papers','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1015'),(17,'pp_tbl','Petroleum Products','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1016'),(18,'sadg_tbl','South Asia Dev. Group','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1017'),(19,'gp_tbl','Grameen Phone','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1018'),(20,'carbon_tbl','Carbon Holdings','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1019'),(21,'bcicsamiti_tbl','BCIC Samiti','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1020'),(22,'deshb_tbl','Desh Builders','74-Dilkusha(Medical Center), Dhaka-1000'),(23,'hirajheelh_tbl','Hirajheel Hotel','22-Motijheel, Dhaka-1000'),(24,'cccl_tbl','Chatak Cement Pro','BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),(25,'13buf_tbl','13 Buffer Project','BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),(26,'34buf_tbl','34 Buffer Project','BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),(27,'daycare_tbl','Day Care','BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),(28,'pdb_tbl','BPDB','BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),(29,'fahiment_tbl','Fahim Enterprise','BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),(30,'shamin_tbl','Sharmin Akter','BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.'),(32,'nment_tbl','N M Enterprise','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1031'),(33,'rajobali_tbl','Rajob Ali','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1032'),(34,'arjufood_tbl','Arju Food & Restaurant','Nababpur Road, Chalkbazar, Dhaka.'),(35,'rajuk_tbl','Rajuk','BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1034'),(36,'rajuk148_tbl','Rajuk 148/Ka','BCIC Bhaban-2, 148/ka-Motijheel, Dhaka-1000'),(38,'royelton_tbl','Royelton Lacquer Coating','Chemical Godown Project, Shampur, Kadamtoli.'),(40,'pusti_tbl','Super Oil Rifinary (Pusti)','Shimrail, Narayangonj, Dhaka.'),(41,'kpml_tbl','KPML','BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000.'),(42,'nbpm_tbl','NBPM','BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `company` with 39 row(s)
--

--
-- Table structure for table `creativep_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creativep_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creativep_tbl`
--

LOCK TABLES `creativep_tbl` WRITE;
/*!40000 ALTER TABLE `creativep_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `creativep_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `creativep_tbl` with 0 row(s)
--

--
-- Table structure for table `daycare_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daycare_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daycare_tbl`
--

LOCK TABLES `daycare_tbl` WRITE;
/*!40000 ALTER TABLE `daycare_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `daycare_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `daycare_tbl` with 0 row(s)
--

--
-- Table structure for table `demano_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demano_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demano_tbl`
--

LOCK TABLES `demano_tbl` WRITE;
/*!40000 ALTER TABLE `demano_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `demano_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `demano_tbl` with 0 row(s)
--

--
-- Table structure for table `deshb_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deshb_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT '74-Dilkusha(Medical Center), Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deshb_tbl`
--

LOCK TABLES `deshb_tbl` WRITE;
/*!40000 ALTER TABLE `deshb_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `deshb_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `deshb_tbl` with 0 row(s)
--

--
-- Table structure for table `erres_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `erres_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `erres_tbl`
--

LOCK TABLES `erres_tbl` WRITE;
/*!40000 ALTER TABLE `erres_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `erres_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `erres_tbl` with 0 row(s)
--

--
-- Table structure for table `fahiment_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fahiment_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Nawabpur Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fahiment_tbl`
--

LOCK TABLES `fahiment_tbl` WRITE;
/*!40000 ALTER TABLE `fahiment_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `fahiment_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `fahiment_tbl` with 0 row(s)
--

--
-- Table structure for table `gp_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gp_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gp_tbl`
--

LOCK TABLES `gp_tbl` WRITE;
/*!40000 ALTER TABLE `gp_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `gp_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `gp_tbl` with 0 row(s)
--

--
-- Table structure for table `hirajheelh_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hirajheelh_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT '22-Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hirajheelh_tbl`
--

LOCK TABLES `hirajheelh_tbl` WRITE;
/*!40000 ALTER TABLE `hirajheelh_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `hirajheelh_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `hirajheelh_tbl` with 0 row(s)
--

--
-- Table structure for table `kpml_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpml_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpml_tbl`
--

LOCK TABLES `kpml_tbl` WRITE;
/*!40000 ALTER TABLE `kpml_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `kpml_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kpml_tbl` with 0 row(s)
--

--
-- Table structure for table `leaker_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leaker_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaker_tbl`
--

LOCK TABLES `leaker_tbl` WRITE;
/*!40000 ALTER TABLE `leaker_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `leaker_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `leaker_tbl` with 0 row(s)
--

--
-- Table structure for table `log_table`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `Ip` varchar(100) NOT NULL,
  `login_date_time` datetime NOT NULL,
  `code` int(11) NOT NULL,
  `logout_date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `log_table` VALUES (1,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-12-01 11:53:24',34779,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `log_table` with 1 row(s)
--

--
-- Table structure for table `mehedient_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mehedient_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mehedient_tbl`
--

LOCK TABLES `mehedient_tbl` WRITE;
/*!40000 ALTER TABLE `mehedient_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `mehedient_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `mehedient_tbl` with 0 row(s)
--

--
-- Table structure for table `mollik_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mollik_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mollik_tbl`
--

LOCK TABLES `mollik_tbl` WRITE;
/*!40000 ALTER TABLE `mollik_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `mollik_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `mollik_tbl` with 0 row(s)
--

--
-- Table structure for table `motalibasso_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `motalibasso_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motalibasso_tbl`
--

LOCK TABLES `motalibasso_tbl` WRITE;
/*!40000 ALTER TABLE `motalibasso_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `motalibasso_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `motalibasso_tbl` with 0 row(s)
--

--
-- Table structure for table `mrtrading_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mrtrading_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mrtrading_tbl`
--

LOCK TABLES `mrtrading_tbl` WRITE;
/*!40000 ALTER TABLE `mrtrading_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `mrtrading_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `mrtrading_tbl` with 0 row(s)
--

--
-- Table structure for table `multiwaysmkt_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multiwaysmkt_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multiwaysmkt_tbl`
--

LOCK TABLES `multiwaysmkt_tbl` WRITE;
/*!40000 ALTER TABLE `multiwaysmkt_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `multiwaysmkt_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `multiwaysmkt_tbl` with 0 row(s)
--

--
-- Table structure for table `nbpm_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nbpm_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nbpm_tbl`
--

LOCK TABLES `nbpm_tbl` WRITE;
/*!40000 ALTER TABLE `nbpm_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `nbpm_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `nbpm_tbl` with 0 row(s)
--

--
-- Table structure for table `nment_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nment_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1031.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nment_tbl`
--

LOCK TABLES `nment_tbl` WRITE;
/*!40000 ALTER TABLE `nment_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `nment_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `nment_tbl` with 0 row(s)
--

--
-- Table structure for table `oyasistec_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oyasistec_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oyasistec_tbl`
--

LOCK TABLES `oyasistec_tbl` WRITE;
/*!40000 ALTER TABLE `oyasistec_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `oyasistec_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `oyasistec_tbl` with 0 row(s)
--

--
-- Table structure for table `pdb_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pdb_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/Ka Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdb_tbl`
--

LOCK TABLES `pdb_tbl` WRITE;
/*!40000 ALTER TABLE `pdb_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `pdb_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pdb_tbl` with 0 row(s)
--

--
-- Table structure for table `poton_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poton_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poton_tbl`
--

LOCK TABLES `poton_tbl` WRITE;
/*!40000 ALTER TABLE `poton_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `poton_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `poton_tbl` with 0 row(s)
--

--
-- Table structure for table `pp_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pp_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pp_tbl`
--

LOCK TABLES `pp_tbl` WRITE;
/*!40000 ALTER TABLE `pp_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `pp_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pp_tbl` with 0 row(s)
--

--
-- Table structure for table `pusti_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pusti_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Shimrail, Narayangonj, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pusti_tbl`
--

LOCK TABLES `pusti_tbl` WRITE;
/*!40000 ALTER TABLE `pusti_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `pusti_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pusti_tbl` with 0 row(s)
--

--
-- Table structure for table `rafirachi_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rafirachi_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rafirachi_tbl`
--

LOCK TABLES `rafirachi_tbl` WRITE;
/*!40000 ALTER TABLE `rafirachi_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `rafirachi_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `rafirachi_tbl` with 0 row(s)
--

--
-- Table structure for table `rajobali_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rajobali_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1032.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rajobali_tbl`
--

LOCK TABLES `rajobali_tbl` WRITE;
/*!40000 ALTER TABLE `rajobali_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `rajobali_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `rajobali_tbl` with 0 row(s)
--

--
-- Table structure for table `rajuk148_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rajuk148_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban-2, 148/ka-Motijheel, Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rajuk148_tbl`
--

LOCK TABLES `rajuk148_tbl` WRITE;
/*!40000 ALTER TABLE `rajuk148_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `rajuk148_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `rajuk148_tbl` with 0 row(s)
--

--
-- Table structure for table `rajuk_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rajuk_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1034.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rajuk_tbl`
--

LOCK TABLES `rajuk_tbl` WRITE;
/*!40000 ALTER TABLE `rajuk_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `rajuk_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `rajuk_tbl` with 0 row(s)
--

--
-- Table structure for table `rasheda_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rasheda_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Imamgonj, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rasheda_tbl`
--

LOCK TABLES `rasheda_tbl` WRITE;
/*!40000 ALTER TABLE `rasheda_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `rasheda_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `rasheda_tbl` with 0 row(s)
--

--
-- Table structure for table `romanaent_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `romanaent_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `romanaent_tbl`
--

LOCK TABLES `romanaent_tbl` WRITE;
/*!40000 ALTER TABLE `romanaent_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `romanaent_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `romanaent_tbl` with 0 row(s)
--

--
-- Table structure for table `royelton_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `royelton_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Chemical Godown Project, Shampur, Kadamtoli.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `royelton_tbl`
--

LOCK TABLES `royelton_tbl` WRITE;
/*!40000 ALTER TABLE `royelton_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `royelton_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `royelton_tbl` with 0 row(s)
--

--
-- Table structure for table `sadg_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sadg_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'BCIC Bhaban, 30-31 Dilkusha, C/A Dhaka-1000.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sadg_tbl`
--

LOCK TABLES `sadg_tbl` WRITE;
/*!40000 ALTER TABLE `sadg_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `sadg_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sadg_tbl` with 0 row(s)
--

--
-- Table structure for table `shamin_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shamin_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Nawabpur Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shamin_tbl`
--

LOCK TABLES `shamin_tbl` WRITE;
/*!40000 ALTER TABLE `shamin_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `shamin_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `shamin_tbl` with 0 row(s)
--

--
-- Table structure for table `signeture_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `signeture_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Chemical Godown Project, Shampur, Kadamtoli.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `signeture_tbl`
--

LOCK TABLES `signeture_tbl` WRITE;
/*!40000 ALTER TABLE `signeture_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `signeture_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `signeture_tbl` with 0 row(s)
--

--
-- Table structure for table `taniaent_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taniaent_tbl` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(25) NOT NULL,
  `year` varchar(10) NOT NULL,
  `fiscal_year` varchar(50) NOT NULL,
  `tenants_name` varchar(25) NOT NULL,
  `actual_hr` int(50) NOT NULL,
  `actual_eb` int(50) NOT NULL,
  `actual_wb` int(50) NOT NULL,
  `actual_gb` int(50) NOT NULL,
  `actual_cb` int(50) NOT NULL,
  `payorder_no_hr` varchar(15) NOT NULL,
  `payorder_date_hr` date NOT NULL,
  `payorder_no_eb` varchar(15) NOT NULL,
  `payorder_date_eb` varchar(15) NOT NULL,
  `payorder_no_wb` varchar(15) NOT NULL,
  `payorder_date_wb` varchar(15) NOT NULL,
  `payorder_no_gb` varchar(15) NOT NULL,
  `payorder_date_gb` varchar(15) NOT NULL,
  `payorder_no_cb` varchar(15) NOT NULL,
  `payorder_date_cb` varchar(15) NOT NULL,
  `payorder_no_comb` varchar(15) NOT NULL,
  `payorder_date_comb` varchar(15) NOT NULL,
  `hr_in` int(50) NOT NULL,
  `eb_in` int(50) NOT NULL,
  `wasa_in` int(50) NOT NULL,
  `gb_in` int(50) NOT NULL,
  `cb_in` int(50) NOT NULL,
  `tax5` varchar(20) NOT NULL,
  `challan_no_tax` varchar(15) NOT NULL,
  `date_tax` date NOT NULL,
  `month_tax` varchar(10) NOT NULL,
  `vat15` varchar(20) NOT NULL,
  `challa_no_vat` varchar(15) NOT NULL,
  `date_vat` date NOT NULL,
  `month_vat` varchar(10) NOT NULL,
  `hr_outs` int(50) NOT NULL,
  `eb_outs` int(50) NOT NULL,
  `wasa_outs` int(50) NOT NULL,
  `gb_outs` int(50) NOT NULL,
  `cb_outs` int(50) NOT NULL,
  `all_value` longtext NOT NULL,
  `address` varchar(255) DEFAULT 'Zel Road, Chalkbazar, Dhaka.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taniaent_tbl`
--

LOCK TABLES `taniaent_tbl` WRITE;
/*!40000 ALTER TABLE `taniaent_tbl` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `taniaent_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `taniaent_tbl` with 0 row(s)
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
  `office` varchar(100) NOT NULL,
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
INSERT INTO `users` VALUES (1,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','buffer','Chairman Secretariat','','2024-09-26 00:07:13'),(2,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','Director (Commercial)','','2024-08-19 02:12:04'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','','','2024-06-01 11:19:24'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','bcic_hq','ICT Division','','2024-08-13 22:01:26'),(12,'bcic_mkt','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','bcic_hq','','user@yahoo.com','2024-06-01 00:48:45'),(14,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','bcic_hq','','admin@yahoo.com','2024-05-25 08:48:37'),(15,'mongla_port','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','port_office','','monglaport@yahoo.com','2024-05-31 12:39:31'),(17,'emd','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','EMD','','2024-12-01 05:22:38'),(20,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Chairman Secretariat','emrantareq09@gmail.com','2024-08-18 02:56:30'),(21,'admin2','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','CSD','emrantareq09@gmail.com','2024-08-18 04:25:51'),(22,'dir_fin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Director (Finance)','','2024-09-18 04:43:49'),(23,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','EMD','','2024-10-15 04:06:24');
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

-- Dump completed on: Sun, 01 Dec 2024 07:03:07 +0100
