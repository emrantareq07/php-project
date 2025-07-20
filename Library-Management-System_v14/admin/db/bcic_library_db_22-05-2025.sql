-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: bcic_library_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Thu, 22 May 2025 13:40:17 +0200

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
-- Table structure for table `admin`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('sadmin','admin','user') NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `admin` VALUES (1,'emran','202cb962ac59075b964b07152d234b70','admin','2025-05-20 09:16:38'),(2,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','2025-05-19 18:00:00'),(3,'anjan','202cb962ac59075b964b07152d234b70','admin','2025-05-20 08:52:56');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `admin` with 3 row(s)
--

--
-- Table structure for table `designation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designation`
--

LOCK TABLES `designation` WRITE;
/*!40000 ALTER TABLE `designation` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `designation` VALUES (1,'Assistant Engineer'),(2,'Executive Engineer'),(3,'Deputy Chief Engineer'),(4,'Additional Chief Engineer'),(5,'General Manager'),(6,'Department Head'),(7,'Divisional Head'),(8,'Assistant Chemist'),(9,'Chemist'),(10,'Sr. System Analyst'),(11,'Deputy Manager'),(12,'Manager'),(13,'Deputy General Manager'),(14,'Additional Chief Accountant'),(15,'Assistant Programmer'),(16,'Programmer'),(17,'Chairman (Grade-1)'),(18,'Director'),(19,'Additional Chief Chemist'),(20,'Additional Chief Manager'),(21,'Accounts Officer'),(22,'Chief Engineer'),(23,'Assistant Accounts Officer'),(24,'Assistant Admin Officer'),(25,'Assistant Com. Officer'),(26,'Assistant Manager'),(28,'Assistant Technical Officer'),(29,'Assistant Operation Officer'),(30,'Operation Officer'),(31,'Technical Officer'),(32,'System Analyst'),(33,'Managing Director'),(34,'Executive Director'),(35,'Chief of Personnel'),(36,'Controller of Accounts'),(37,'Senior General Manager'),(38,'Deputy Chief Accountant'),(39,'Medical officer'),(40,'Senior Medical Officer'),(41,'Chief Medical Officer'),(42,'Chief Finance Officer'),(43,'Chief Auditor'),(44,'Project Director'),(45,'Addl. Chief Medical Officer'),(46,'D.C.O.P'),(47,'Principle'),(48,'Deputy Chief Medical Officer'),(49,'Deputy Chief Auditors'),(50,'D.C.F.O'),(51,'A.C.O.P'),(52,'Assistant Professor'),(53,'Senior Librarian'),(54,'Head Master'),(55,'Senior Technical Officer'),(56,'Assistant Chief Accountant'),(57,'A.C.F.O'),(58,'Assistant Chief Auditor'),(59,'Computer Operator'),(60,'Data Entry Operator'),(61,'Senior Officer ICT'),(62,'Record Sorter'),(63,'Sub Assistant Engineer'),(64,'Managing Director (Addl.C.)'),(65,'Managing Director (C.C.)'),(66,'Officer'),(67,'Production Officer'),(68,'Assistant Principle Officer'),(69,'Executive'),(70,'Sub Assistant Chemist'),(73,'Senior Officer'),(74,'Trainee Engineer'),(75,'Generator Operator'),(76,'Dev.Exect.Trans'),(77,'Assistant Instructor'),(78,'Junior Instructor'),(79,'Instructor'),(80,'Junior Officer'),(81,'Production Shift Incharge'),(82,'Production Senior Officer'),(83,'Production Engineer'),(84,'Data Protection Officer'),(85,'Junior Engineer'),(86,'Project Technology'),(87,'Junior Assistant Engineer'),(88,'Senior Electrician'),(89,'Security Officer'),(90,'Trainee Officer'),(91,'Machinery Fitter'),(92,'Assistant Security Officer'),(93,'Forest Officer'),(94,'Teacher'),(95,'Engineer'),(96,'LDA Cum-Typist'),(97,'Senior Clark'),(98,'Cashier'),(99,'Assistant Headmaster'),(100,'Assistant Store Keper'),(101,'Store Keper'),(102,'Assistant Teacher'),(103,'Librerian'),(104,'Assistant Teacher'),(105,'Senior Lecturer'),(106,'Skilled Operator (S.O.)-2'),(107,'Skilled Operator (S.O.)-1'),(108,'High Skilled Operator (HSO)'),(109,'Master Operator (MO)'),(110,'Sub-Assistant Technical Officer'),(111,'Assistant Medical Officer'),(112,'Assistant Transport Officer'),(113,'Assistant Coordination Officer'),(114,'Assistant Personnel Officer'),(115,'Personal Officer'),(116,'Deputy Chief Chemist'),(117,'Lecturer'),(118,'Administrative Officer'),(119,'Assistant Marketing Officer'),(120,'Master Technician'),(121,'Process Operator'),(123,'High Skilled Technician (HST)'),(124,'Store Officer'),(125,'Junior Clark'),(126,'Modeller'),(127,'Assistant Modeller'),(128,'Audit Officer'),(129,'Stenographer'),(130,'Telephone Operator'),(131,'Semi Skilled Operator (SSO)'),(132,'Semi Skilled Technician (SST)'),(133,'Skilled Technician (ST)'),(134,'Assistant Cashier'),(135,'Accounts Assistant'),(136,'Supervisor'),(137,'A.C.I.O'),(138,'Additional Chief Auditor'),(139,'Junior Programmer'),(140,'Security Guard'),(141,'MLSS'),(142,'Office Assistant'),(143,'STG.CUM. COMPUTER OPERATOR'),(144,'Driver'),(145,'Demonstrator'),(146,'IMAM'),(148,'Electricina'),(149,'Purchase Assistant'),(150,'Mechanic'),(151,'Marketing Assistant'),(152,'ST. CUM COMPUTER OPERATOR');
/*!40000 ALTER TABLE `designation` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `designation` with 147 row(s)
--

--
-- Table structure for table `division`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `division`
--

LOCK TABLES `division` WRITE;
/*!40000 ALTER TABLE `division` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `division` VALUES (1,'Personnel Division'),(2,'Accounts Division'),(3,'Commercial Division'),(4,'Technical Division'),(5,'MTS Division'),(6,'Chairman Secretariat'),(7,'Operation Division'),(8,'PRD'),(9,'PID'),(10,'RPD'),(11,'Marketing Division'),(12,'Audit Division'),(13,'Purchase Division'),(14,'Finance Division'),(15,'MIS Division'),(16,'Director (Commercial)'),(17,'Director (Finance)'),(18,'Director (P&I)'),(19,'Director (T&E)'),(20,'Director (Prod.)'),(21,'ICT Division'),(25,'Director (T&E)'),(26,'Director (P&I)'),(47,'AFCCL'),(48,'SFCL'),(49,'JFCL'),(50,'BISFL'),(51,'CUFL'),(52,'GPUFP'),(53,'GPFPLC'),(54,'DAPFCL'),(55,'TSPCL'),(56,'KPML'),(57,'UGSFL'),(58,'CCCL'),(59,'CCC'),(60,'34 Buffer Project'),(61,'13 Buffer Project'),(62,'UF-85 Project'),(63,'Chemical Godown, Shampur'),(64,'KNM & KHBM'),(65,'EMD'),(66,'Administration Division'),(67,'Senior General Manager (Admin)'),(68,'Planning Division'),(69,'Production Division'),(72,'HSET Division'),(73,'MTS (MECH)'),(74,'CSD'),(75,'BCIC College '),(76,'Legal Affairs Department'),(77,'Board & Co-ordination Department'),(78,'Company Affairs'),(79,'PDD'),(80,'ISHD'),(81,'Construction Division'),(82,'Forest Division'),(83,'Transport Division'),(84,'Branch Office (Chittagong)');
/*!40000 ALTER TABLE `division` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `division` with 59 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `log_table` VALUES (1,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 08:51:52',33345,'0000-00-00 00:00:00'),(2,'0001','1234','student','127.0.0.1','2025-05-21 08:59:21',12113,'0000-00-00 00:00:00'),(3,'0001','1234','student','127.0.0.1','2025-05-21 09:10:08',67034,'2025-05-21 09:10:48'),(4,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:11:36',93538,'2025-05-21 09:11:41'),(5,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:33:37',48564,'2025-05-21 09:36:22'),(6,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:38:12',23811,'2025-05-21 09:43:04'),(7,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:43:08',70356,'2025-05-21 10:00:11'),(8,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 09:43:20',27583,'2025-05-21 10:27:09'),(9,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-21 10:00:20',27016,'2025-05-21 10:00:28'),(10,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-21 10:02:17',86497,'2025-05-21 10:23:22'),(11,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 10:23:26',73911,'2025-05-21 10:32:22'),(12,'0001','1234','student','127.0.0.1','2025-05-21 10:27:18',77771,'2025-05-21 10:29:08'),(13,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 10:29:16',90947,'2025-05-21 10:30:37'),(14,'0006','1234','student','127.0.0.1','2025-05-21 10:32:37',20382,'2025-05-21 13:06:06'),(15,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 10:33:07',81068,'2025-05-21 13:02:08'),(16,'0006','1234','student','127.0.0.1','2025-05-21 13:04:18',47266,'2025-05-21 13:04:29'),(17,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:04:40',22781,'0000-00-00 00:00:00'),(18,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:06:17',24474,'2025-05-21 13:08:54'),(19,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-21 13:08:59',49940,'2025-05-21 13:09:08'),(20,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:09:12',84236,'0000-00-00 00:00:00'),(21,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:38:43',73088,'0000-00-00 00:00:00'),(22,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:50:11',76833,'2025-05-21 13:52:29'),(23,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:52:32',30841,'0000-00-00 00:00:00'),(24,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 13:53:52',23934,'2025-05-21 13:58:15'),(25,'0001','1234','student','127.0.0.1','2025-05-21 14:09:19',70718,'2025-05-21 14:09:21'),(26,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 14:09:25',24060,'2025-05-21 14:09:36'),(27,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-21 14:14:54',79833,'2025-05-21 14:15:01'),(28,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-21 14:15:04',50356,'0000-00-00 00:00:00'),(29,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 05:48:52',45959,'2025-05-22 11:58:14'),(30,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 12:02:51',17665,'0000-00-00 00:00:00'),(31,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-22 12:02:58',52303,'2025-05-22 12:03:01'),(32,'sadmin','202cb962ac59075b964b07152d234b70','sadmin','127.0.0.1','2025-05-22 12:05:21',82653,'0000-00-00 00:00:00'),(33,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 12:08:57',66499,'2025-05-22 12:25:19'),(34,'5620-0','1234','student','127.0.0.1','2025-05-22 13:14:41',24290,'2025-05-22 13:14:42'),(35,'5620-0','1234','student','127.0.0.1','2025-05-22 13:15:58',88889,'0000-00-00 00:00:00'),(36,'5620-0','1234','student','127.0.0.1','2025-05-22 13:16:35',87544,'2025-05-22 13:18:35'),(37,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 13:18:45',49757,'2025-05-22 13:29:06'),(38,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 13:29:11',71378,'2025-05-22 13:29:37'),(39,'emran','202cb962ac59075b964b07152d234b70','admin','127.0.0.1','2025-05-22 13:29:43',97589,'0000-00-00 00:00:00'),(40,'3456-9','123','student','127.0.0.1','2025-05-22 13:38:03',55123,'2025-05-22 13:38:05');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `log_table` with 40 row(s)
--

--
-- Table structure for table `section`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `section` VALUES (1,6,'Chairman Secretariat'),(2,1,'Chief of Personnel (COP)'),(3,1,'LSA'),(4,1,'RNT'),(5,8,'PRD'),(6,12,'Audit'),(7,13,'Local Purchase'),(8,13,'Foreign Purchase'),(9,11,'Marketing'),(10,11,'Marketing Store'),(11,2,'Salary'),(12,2,'PF'),(13,15,'MIS'),(14,14,'Finance '),(15,21,'ICT'),(16,16,'Director (Com.)'),(17,17,'Director (Fin.)'),(18,18,'Director (P&I)'),(19,19,'Director (T&E)'),(20,20,'Director (Prod.)'),(21,22,'Board of Director'),(22,22,'BCIC'),(23,24,'BCIC H.O.'),(24,1,'Administration'),(25,35,'DLCL'),(26,2,'Accounts'),(27,5,'MTS'),(28,5,'Civil'),(29,40,'GPUFP'),(30,31,'AFCCL'),(31,29,'SFCL'),(32,30,'JFCL'),(33,45,'BISFL'),(34,33,'CUFL'),(35,41,'GPFPLC'),(36,32,'DAPFCL'),(37,34,'TSPCL'),(38,36,'KPML'),(39,37,'UGSFL'),(40,39,'CCCL'),(41,38,'CCC'),(42,42,'34 Buffer Project'),(43,43,'13 Buffer Project'),(44,44,'UF-85 Project'),(45,1,'General Administration'),(46,67,'Legal Affairs'),(47,9,'PID'),(48,64,'KNM & KHBM'),(49,13,'Purchase'),(51,67,'EMD'),(52,1,'O&M Department'),(53,7,'Urea'),(54,7,'Ammonia'),(55,7,'Utility'),(56,7,'Operation'),(57,68,'Planning'),(58,69,'Production'),(59,72,'HSET'),(60,3,'Commercial'),(61,3,'MPIC'),(62,3,'Store'),(63,5,'Plant Maintenance (PM)'),(64,5,'Machinery Maintenance (MM)'),(65,5,'Central Maintenance Workshop(CMW)'),(66,5,'Solid Handling (SHSM)'),(67,5,'Power Plant (PP)'),(68,7,'Bagging'),(69,66,'Security'),(70,66,'School'),(71,74,'CSD'),(72,75,'BCIC College'),(73,66,'Medical Center'),(74,14,'Company Affairs'),(75,77,'Board & Co-ordination'),(76,66,'School & College'),(77,79,'PDD'),(78,80,'ISHD'),(79,66,'Fire & Safety'),(80,4,'Inspection'),(81,7,'Process'),(82,5,'Electrical Maintenance (EM)'),(83,1,'Forest'),(84,4,'Laboratory'),(85,4,'Technical'),(86,83,'Transport'),(87,84,'Branch Office (Chittagong)');
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `section` with 86 row(s)
--

--
-- Table structure for table `tblauthors`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblauthors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AuthorName` varchar(159) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblauthors`
--

LOCK TABLES `tblauthors` WRITE;
/*!40000 ALTER TABLE `tblauthors` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblauthors` VALUES (1,'Anuj kumar test','2017-07-08 12:49:09','2025-04-30 03:25:44'),(2,'Chetan Bhagatt','2017-07-08 14:30:23','2017-07-08 15:15:09'),(3,'Anita Desai','2017-07-08 14:35:08',NULL),(4,'HC Verma','2017-07-08 14:35:21',NULL),(5,'R.D. Sharma ','2017-07-08 14:35:36',NULL),(9,'fwdfrwer','2017-07-08 15:22:03',NULL),(10,'Balagurushamy','2025-04-17 06:53:22',NULL),(11,'হুমায়ূন আহমেদ','2025-04-30 06:03:06','2025-04-30 06:04:23'),(12,'আসমা কবির','2025-05-07 04:57:41','2025-05-07 05:00:59'),(13,'Humayan Ahmed','2025-05-20 04:36:26',NULL);
/*!40000 ALTER TABLE `tblauthors` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblauthors` with 10 row(s)
--

--
-- Table structure for table `tblbooks`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `accession_no` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `edition` varchar(50) NOT NULL,
  `isbn` varchar(100) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_place` varchar(255) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `page_no` int(11) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `call_no` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0,
  `series` varchar(50) NOT NULL,
  `volume` varchar(20) NOT NULL,
  `language` varchar(50) NOT NULL,
  `self_no` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbooks`
--

LOCK TABLES `tblbooks` WRITE;
/*!40000 ALTER TABLE `tblbooks` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblbooks` VALUES (1,'2025-04-30',1,'Data Structure',4,3,'1','৬৪৫৬৪৫','','','',0,'','','11','','2025-05-07 05:08:17','2025-05-07 05:24:14',1,'','','',''),(2,'2025-05-02',2,'Algorithm',5,4,'2','6456456','','','',0,'','','11','','2025-05-07 05:10:45','2025-05-07 05:26:07',0,'','','',''),(3,'2025-04-30',3,'Numerical Methods',9,4,'0','8784654456','','','',0,'','','','','2025-05-17 05:51:55','2025-05-17 05:51:55',0,'','','',''),(4,'2025-04-30',4,'Computation Theory',0,1,'0','345345','','','',0,'','','','','2025-05-17 07:07:08','2025-05-17 07:07:08',0,'','','',''),(5,'2025-05-02',5,'Numerical Methods test',9,4,'0','8784654456','','','',0,'','','','','2025-05-17 07:12:32','2025-05-17 07:12:32',0,'','','',''),(6,'2025-05-01',6,'ttrerswf',0,4,'0','4455','','','',0,'','','','','2025-05-17 07:15:26','2025-05-17 07:15:26',0,'','','',''),(7,'2025-05-08',7,'tttttttttttt',2,2,'0','t577645','','','',0,'','','','','2025-05-17 07:50:21','2025-05-17 07:50:21',0,'','','',''),(8,'2025-05-02',8,'Applied Physics',0,5,'0','12345','','','',0,'','','','','2025-05-17 07:52:55','2025-05-17 07:52:55',0,'','','',''),(9,'2025-05-08',9,'yyyyyyyyyyyyyyyyy',4,2,'0','3456','test','test 123','2012',33,'222','111','3','dsfs','2025-05-17 07:56:59','2025-05-17 11:14:15',0,'5','3','BN','55'),(10,'2025-05-09',10,'Procurement and PPA',2,3,'1','523452','','','',0,'','','','','2025-05-17 09:39:06','2025-05-17 09:39:06',0,'1','2','BN','43'),(11,'2025-05-17',11,'yyyyyyyyyyyyyyyyy',4,2,'0','3456','test','test 123','2012',33,'222','111','3','dsfs','2025-05-17 11:22:10','2025-05-18 06:01:14',1,'5','3','BN','55'),(12,'2025-05-17',12,'Algorithm',5,4,'2','6456456','22','222','22',0,'22','22','11','222','2025-05-17 11:23:13','2025-05-17 11:23:13',0,'222','3','222','22'),(13,'2025-05-06',13,'Procurement and PPA',2,3,'1','523452','','','',0,'','Purchase','58','','2025-05-18 05:37:59','2025-05-18 06:01:14',1,'1','2','BN','43'),(14,'2025-05-08',14,'Geometry Mathematics',7,5,'1','3453453','','1','',0,'111','gift','44','','2025-05-18 05:40:24','2025-05-18 06:01:14',1,'','1','BN','43'),(15,'2025-05-07',15,'Geometry Mathematics',7,5,'2','3453453','','1','',0,'111','Purchase','44','','2025-05-18 05:43:10','2025-05-18 05:43:10',0,'','1','BN','43'),(16,'2025-05-01',16,'Procurement and PPA',2,3,'1','523452','','','',0,'','Purchase','58','PPA, PPR, Procurement','2025-05-18 06:43:01','2025-05-20 04:45:43',1,'1','2','BN','43'),(17,'2025-05-20',17,'মেঘের উপর বাড়ি',9,11,'0','6645656','test','dhaka','2022',0,'','','22','বাড়ি','2025-05-20 04:39:36','2025-05-20 04:54:21',0,'','','BN','11');
/*!40000 ALTER TABLE `tblbooks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblbooks` with 17 row(s)
--

--
-- Table structure for table `tblbooks_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbooks_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int(11) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `ISBNNumber` int(11) DEFAULT NULL,
  `BookPrice` int(11) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbooks_old`
--

LOCK TABLES `tblbooks_old` WRITE;
/*!40000 ALTER TABLE `tblbooks_old` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblbooks_old` VALUES (1,'PHP And MySql programming',5,1,222333,20,'2017-07-08 20:04:55','2017-07-15 05:54:41'),(3,'physics',6,4,1111,15,'2017-07-08 20:17:31','2017-07-15 06:13:17'),(4,'Data structure',5,2,545435,NULL,'2024-07-31 05:31:20',NULL),(5,'Mircroprocessor',5,9,456321,NULL,'2025-03-18 07:41:44',NULL);
/*!40000 ALTER TABLE `tblbooks_old` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblbooks_old` with 4 row(s)
--

--
-- Table structure for table `tblcategory`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryParent` varchar(200) DEFAULT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcategory`
--

LOCK TABLES `tblcategory` WRITE;
/*!40000 ALTER TABLE `tblcategory` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblcategory` VALUES (1,'Romantic','Romantic  ','1','2025-05-17 06:21:17','2025-05-17 06:21:17'),(2,'Management','Management  ','1','2025-05-17 06:21:35','2025-05-17 06:21:35'),(3,'Mathematics','Mathematics  abc','1','2025-05-17 06:21:55','2025-05-17 06:21:55'),(4,'Mathematics','Mathematics  nnnn','1','2025-05-17 06:22:14','2025-05-17 06:22:14'),(5,'Science','Science  Physics','1','2025-05-17 06:23:35','2025-05-17 06:23:35'),(6,'Mathematics','Mathematics  Differential Calculas','1','2025-05-18 05:34:42','2025-05-18 05:34:42'),(7,'Mathematics','Mathematics  Geometry','1','2025-05-18 05:35:06','2025-05-18 05:35:06'),(8,'Bangali Literature ','Bangali Literature   Academic','1','2025-05-20 04:35:39','2025-05-20 04:35:39'),(9,'Bangali Literature ','Bangali Literature   Nobel','1','2025-05-20 04:36:07','2025-05-20 04:36:07');
/*!40000 ALTER TABLE `tblcategory` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblcategory` with 9 row(s)
--

--
-- Table structure for table `tblcategory_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcategory_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcategory_old`
--

LOCK TABLES `tblcategory_old` WRITE;
/*!40000 ALTER TABLE `tblcategory_old` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblcategory_old` VALUES (4,'Romantic',1,'2017-07-04 18:35:25','2025-04-30 04:45:34'),(5,'Technology',1,'2017-07-04 18:35:39','2017-07-08 17:13:03'),(6,'Science',1,'2017-07-04 18:35:55','0000-00-00 00:00:00'),(7,'Management',1,'2017-07-04 18:36:16','2025-04-17 08:25:00'),(8,'Dictionary',1,'2025-05-07 04:57:19','0000-00-00 00:00:00'),(9,'Mathematics Differential ',1,'2025-05-17 05:39:46','2025-05-17 05:50:09'),(10,'Mathematics Calculas',1,'2025-05-17 05:39:46','2025-05-17 05:49:55'),(11,'Mathematics',1,'2025-05-17 05:39:46','2025-05-17 05:49:55'),(12,'Mathematics-Discrete',1,'2025-05-17 06:03:21','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `tblcategory_old` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblcategory_old` with 9 row(s)
--

--
-- Table structure for table `tblissuedbookdetails`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblissuedbookdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accession_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `BookId` int(11) DEFAULT NULL,
  `StudentID` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IssuesDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ReturnDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `RetrunStatus` int(1) NOT NULL,
  `fine` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblissuedbookdetails`
--

LOCK TABLES `tblissuedbookdetails` WRITE;
/*!40000 ALTER TABLE `tblissuedbookdetails` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblissuedbookdetails` VALUES (1,'1',NULL,'0001','2025-04-30 18:00:00','2025-05-08 05:20:12',1,0),(2,'2',NULL,'0001','2025-04-30 18:00:00','2025-05-06 18:00:00',0,0),(3,'1',NULL,'0001','2025-05-06 18:00:00','2025-05-11 18:00:00',0,0),(4,'11',NULL,'0001','2025-05-17 18:00:00','2025-06-01 18:00:00',0,NULL),(5,'13',NULL,'0001','2025-05-17 18:00:00','2025-06-01 18:00:00',0,NULL),(6,'14',NULL,'0001','2025-05-17 18:00:00','2025-06-01 18:00:00',0,NULL),(7,'17',NULL,'0007','2025-05-19 18:00:00','2025-05-31 18:00:00',1,0),(8,'16',NULL,'0007','2025-05-17 18:00:00','2025-05-19 18:00:00',1,0),(9,'16',NULL,'0007','2025-05-19 18:00:00','2025-06-03 18:00:00',0,0);
/*!40000 ALTER TABLE `tblissuedbookdetails` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblissuedbookdetails` with 9 row(s)
--

--
-- Table structure for table `tblstudents`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblstudents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `StudentId` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `FullName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `designation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `division` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `section` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pabx` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `EmailId` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MobileNumber` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Image` varchar(255) DEFAULT NULL,
  `password_changed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `StudentId` (`StudentId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblstudents`
--

LOCK TABLES `tblstudents` WRITE;
/*!40000 ALTER TABLE `tblstudents` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tblstudents` VALUES (1,'5620-0','emran test','Programmer','ICT Division','ICT','23342','emran445@yahoo.com','5646421346','$2y$10$fyVY9A3RoB7rQ0PA6cV3buRN78pqSKRhhek8ID7b5tJcCcuzZFsv6',1,'2025-05-22 09:46:47','2025-05-22 11:14:31','student_images/1747907207_sarower.jpg',1),(2,'5621-8','anul','Accounts Assistant','Audit Division','Audit','23342','hasan@yahoo.com','0171883465','$2y$10$1uq7.Sszxg85LeooPJyS5.x2.z7UmXRH70ZDbAFrsjTcUucGzhVHy',1,'2025-05-22 10:10:28','2025-05-22 11:37:43','student_images/1747908628_2023-04-16-06-21-c06eb73f99118202eee4b5ff90c30ec8.jpg',0),(3,'3456-9','lala','Additional Chief Accountant','Accounts Division','Board & Co-ordination','23342','hasan44@yahoo.com','5646421346','$2y$10$yzajvYJpYuM7v1KIRkHbneD5GEN/QxEiSoHu4OFHzNrPnedSM813C',1,'2025-05-22 10:16:12','2025-05-22 11:37:41','student_images/682f0abba295a_WhatsApp Image 2025-05-19 at 10.06.23 AM.jpeg',1);
/*!40000 ALTER TABLE `tblstudents` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tblstudents` with 3 row(s)
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

-- Dump completed on: Thu, 22 May 2025 13:40:18 +0200
