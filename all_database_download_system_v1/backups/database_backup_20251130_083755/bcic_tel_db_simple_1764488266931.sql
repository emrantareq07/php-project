-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bcic_tel_db_simple
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
-- Table structure for table `designation`
--

DROP TABLE IF EXISTS `designation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designation`
--

LOCK TABLES `designation` WRITE;
/*!40000 ALTER TABLE `designation` DISABLE KEYS */;
INSERT INTO `designation` VALUES (1,'Assitant Engineer'),(2,'Executive Engineer'),(3,'Deputy Chief Engineer'),(4,'Additional Chief Engineer'),(5,'General Manager'),(6,'Departmental Head(MTS/EIP)'),(7,'Departmental Head(MTS/Mech)'),(8,'Departmental Head(Accounts)'),(9,'Departmental Head(Administration)'),(10,'Departmental Head(Commercial)'),(11,'Sr. System Analyst'),(12,'Deputy Manager'),(13,'Manager'),(14,'Deputy General Manager'),(15,'Addl. Chief Accountant'),(16,'Assistant Programmer'),(17,'Programmer'),(18,'Chairman (Grade-1)'),(19,'Director(Com.)'),(20,'Director(Fin.)'),(21,'Director(T&E)'),(22,'Director(P&I)'),(23,'Director(Prod.)'),(24,'Sr. GM(Admin)'),(25,'Accounts Officer'),(26,'GM(MTS)/Chief Engineer(MTS)'),(27,'Assistant Accounts Officer'),(28,'Assistant Admin Officer'),(29,'Assistant Com.Officer'),(30,'Assistant Manager (Admin) '),(31,'Assistant Manager (Com.) '),(32,'Assistant Technical Officer'),(33,'Assistant Operation Officer'),(34,'Operation Officer'),(35,'Technical Officer'),(36,'System Analyst');
/*!40000 ALTER TABLE `designation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `division`
--

DROP TABLE IF EXISTS `division`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `division`
--

LOCK TABLES `division` WRITE;
/*!40000 ALTER TABLE `division` DISABLE KEYS */;
INSERT INTO `division` VALUES (1,'Administration'),(2,'Accounts'),(3,'Commercial'),(4,'Technical'),(5,'MTS'),(6,'Chairman Secretariat'),(7,'Operation'),(8,'PRD'),(9,'Personal Division'),(10,'RPD'),(11,'Marketing Division'),(12,'Audit Division'),(13,'Purchase Division'),(14,'Finance Division'),(15,'MIS'),(16,'Director (Com.)'),(17,'Director (Fin.)'),(18,'Director (P&I)'),(19,'Director (T&E)'),(20,'Director (Prod.)'),(21,'ICT Division'),(22,'Board of Director'),(23,'BCIC'),(24,'BCIC H.O.'),(25,'Director (T&E)'),(26,'Director (P&I)'),(27,'Director (Prod.)');
/*!40000 ALTER TABLE `division` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES (1,6,'Chairman Secretariat'),(2,9,'COP'),(3,9,'LSA'),(4,9,'RNT'),(5,8,'PRD'),(6,12,'Audit'),(7,13,'Local Purchase'),(8,13,'Foreign Purchase'),(9,11,'Marketing'),(10,11,'Marketing Store'),(11,2,'Salary'),(12,2,'PF'),(13,15,'MIS'),(14,14,'Finance '),(15,21,'ICT'),(16,16,'Director (Com.)'),(17,17,'Director (Fin.)'),(18,18,'Director (P&I)'),(19,19,'Director (T&E)'),(20,20,'Director (Prod.)'),(21,22,'Board of Director'),(22,22,'BCIC'),(23,22,'BCIC H.O.');
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tel_tbl`
--

DROP TABLE IF EXISTS `tel_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tel_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(25) NOT NULL,
  `division_name` varchar(25) NOT NULL,
  `section_name` varchar(25) NOT NULL,
  `phone_office` varchar(25) NOT NULL,
  `phone_home` varchar(25) NOT NULL,
  `intercom` varchar(25) NOT NULL,
  `mobile` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `location` varchar(25) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tel_tbl`
--

LOCK TABLES `tel_tbl` WRITE;
/*!40000 ALTER TABLE `tel_tbl` DISABLE KEYS */;
INSERT INTO `tel_tbl` VALUES (1,'5620-0','emran','Programmer','ICT Division','RNT','799','889','998','3809348','emran@yahoo.com','Dhaka','','2022-10-30 06:39:39'),(2,'','tareq','','','LSA','989','89','898','7978','tareq@yahoo.com','Dhaka','','2022-09-12 08:29:11'),(3,'5620-1','Gazi shahinul','Deputy General Manager','Chairman Secretariat','Chairman Secretariat','12345','2344','34','23456','gazi@yahoo.com','Dhaka','1603179140.jpg','2022-10-26 10:19:24'),(4,'5620-2','ABM Ferdous','General Manager','','Cash','986','76q75','7665q','757','fer@yahoo.com','Dhaka','','2022-10-20 03:31:08'),(5,'','Tareq Emran','Programmer','','Cash','345','345','345','344','t@yahoo.com','Dhaka','','2022-09-16 16:08:49'),(6,'','jamal','Deputy Chief Engineer','','Audit','12345','2344','34','23456','fer@yahoo.com','Dhaka','','2022-09-16 17:36:48'),(7,'','kamal','Additional Chief Engineer','','Director(Com.)','12345','2344','34','23456','kamal@yahoo.com','Dhaka','','2022-09-17 09:10:43'),(11,'','জনাব শাহ্‌ মোঃ ইমদাদুল হক','Chairman (Grade-1)','Chairman Secretariat','Chairman Secretariat','০২-২২৩৩৮৪১৫৩  ','০২-২২৩৩৮৪১৫৩  ','০২-২২৩৩৮৪১৫৩  ','০২-২২৩৩৮৪১৫৩  ','chairman.bcic@bcic.gov.bd','Dhaka','528651554.jpg','2022-10-26 10:21:22'),(12,'','জনাব কাজী মোহাম্মদ সাইফুল ইসলাম ','','','','০২-২২৩৩৮২০৯০  ','০২-২২৩৩৮২০৯০  ','০২-২২৩৩৮২০৯০  ','০২-২২৩৩৮২০৯০  ','dir.com@bcic.gov.bd ','Dhaka','','2022-09-18 15:48:31'),(13,'','জনাব মোঃ ওয়াহিদুজজামান','','','','০২-২২৩৩৮৪১৩৫','০২-২২৩৩৮৪১৩৫','০২-২২৩৩৮৪১৩৫','০২-২২৩৩৮৪১৩৫','dir.fin@bcic.gov.bd   ','Dhaka','','2022-09-18 15:48:34'),(14,'','জনাব মোঃ শাহীন কামাল ','Director(Production)','','','০২-২২৩৩৮৪১২৯   ','০২-২২৩৩৮৪১২৯   ','০২-২২৩৩৮৪১২৯   ','০২-২২৩৩৮৪১২৯   ','dir.pr@bcic.gov.bd','Dhaka','362620511.jpg','2022-10-25 07:57:45'),(15,'','জনাব মোঃ মনিরুল ইসলাম','Director(T&E)','Administration','','০২-২২৩৩৮৫৬৯১','০২-২২৩৩৮৫৬৯১','০২-২২৩৩৮৫৬৯১','০২-২২৩৩৮৫৬৯১','dir.te@bcic.gov.bd','Dhaka','2038696506.jpg','2022-10-25 05:40:10'),(16,'','foisal v','Deputy Chief Engineer','Administration','MIS','12345678','4567','345','2345678','f@yahho.com','Dhaka','2091077952.jpg','2022-10-25 06:56:32'),(17,'','Basir uddin','Deputy Chief Engineer','MTS','MIS','22-123456789','22-1234567','1234','12345-123456','ha@yahoo.com','Dhaka','bdlogo2.png','2022-10-24 14:59:32'),(18,'','Monir','Assitant Engineer','Chairman Secretariat','Chairman Secretariat','22-123456789','22-2345676','1245','01891-837827','chair@gmail.com','Dhaka','bdlogo2.png','2022-10-28 16:44:46');
/*!40000 ALTER TABLE `tel_tbl` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 13:37:48
