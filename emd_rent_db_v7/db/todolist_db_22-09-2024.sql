-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: todolist_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Sun, 22 Sep 2024 12:39:28 +0200

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
-- Table structure for table `chairman`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chairman` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `focal_point` varchar(150) NOT NULL,
  `zoom_id` varchar(100) NOT NULL,
  `zoom_passcode` varchar(100) NOT NULL,
  `zoom_link` varchar(255) NOT NULL,
  `president` varchar(150) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chairman`
--

LOCK TABLES `chairman` WRITE;
/*!40000 ALTER TABLE `chairman` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `chairman` VALUES (17,'2024-09-15','16:04','test 44','','2324234','5435','https://chatgpt.com/c/66e2ae4e-e7a0-8009-9336-9fdc2e7fc5d4','','Conference Room','Complete','September','2024-09-15'),(18,'2024-09-15','19:00','Test 2','','fdsf','dsf','','','Conference Room','Complete','September','2024-09-15'),(19,'2024-09-15','18:06','Test 444','','444','44444','','','Conference Room','Complete','September','2024-09-15'),(20,'2024-09-16','11:02','Test ','','2324234','5435','','','Conference Room','Complete','September','2024-09-15'),(21,'2024-09-18','15:00','test1','','345865','1234','','','Conference Room','Complete','September','2024-09-18'),(22,'2024-12-09','17:00','test 2','Dir PI','','','','','Conference Room','Complete','December','2024-09-18'),(23,'2024-09-18','17:00','ttest','','','','','','Conference Room','Complete','September','2024-09-18'),(24,'2024-09-18','17:00','testv 33','','','','','','Conference Room','Complete','September','2024-09-18'),(25,'2024-09-18','17:00','test 55','','','','','','Conference Room','Complete','September','2024-09-18'),(26,'2024-09-19','12:00','About Lean Management System Online Meeting.','Dir (PI)','843 5828 3298','bcic','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-19'),(27,'2024-09-19','16:00','২০২৪-২০২৫ থেকে ২০২৬-২০২৭ অর্থবছর পর্যন্ত সময়ের জন্য বিসিআইসি প্রধান কার্যালয়ে সার্বক্ষণিক টেকনিশিয়ান রেখে অধিযাচন অনুযায়ী মালামাল সরবরাহ এবং','','','','','','Conference Room','Complete','September','2024-09-19'),(28,'2024-09-19','17:00','ফেডারেশন অব বিসিআইসি অফিসার্স ওয়েলফেয়ার এসোসিয়েশন এর সভাপতি জনাব মোঃ দেলোয়ার হোসেনের নেতৃত্বে বিসিআইসি প্রধান কার্যালয়','পরিচালক (বাণিজ্যিক)','345865','1234','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-19'),(29,'2024-09-19','16:12','JFCL board','Dir TE','','','','চেয়ারম্যান, বিসিআইসি','Board Room','Complete','September','2024-09-19'),(30,'2024-09-19','17:14','Test ','পরিচালক (বাণিজ্যিক)','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-19'),(31,'2024-09-22','11:29','শিল্প মন্ত্রণালয় এবং এর অধীন দপ্তর/সংস্থার অংশগ্রহণে ইনোভেশন প্রদর্শনী (শোকেসিং) অনুষ্ঠিত ১৫ মে ২০২৪ খ্রিস্টাব্দ, স্থান: শিল্প মন্ত্রণালয়ের ৩য় তলার লবি।','Dir PI','345865','12345','','Chairman BCIC','Conference Room','Incomplete','September','2024-09-22'),(32,'2024-09-22','13:13','TSPCL ','Dir PRE','843 5828 3298','bcic','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Board Room','Incomplete','September','2024-09-22');
/*!40000 ALTER TABLE `chairman` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `chairman` with 16 row(s)
--

--
-- Table structure for table `dir_com`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dir_com` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `focal_point` varchar(150) NOT NULL,
  `zoom_id` varchar(100) NOT NULL,
  `zoom_passcode` varchar(100) NOT NULL,
  `zoom_link` varchar(255) NOT NULL,
  `president` varchar(150) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dir_com`
--

LOCK TABLES `dir_com` WRITE;
/*!40000 ALTER TABLE `dir_com` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dir_com` VALUES (1,'2024-09-18','10:00','SFCL test 1','Dir Techinal','','','','Chairman BCIC','Conference Room','Complete','September','2024-09-19'),(2,'2024-09-22','10:38','JFCL Board Meeting','Dir FIn','','1234','','চেয়ারম্যান, বিসিআইসি','Conference Room','Incomplete','September','2024-09-22'),(3,'2024-09-22','10:39','AFCCL Board','Dir Com','345865','1234','','Chairman BCIC','Board Room','Incomplete','September','2024-09-22'),(4,'2024-09-22','10:40','SFCL','Dir PI','8435828365','12345','','Chairman BCIC gf','Board Room','Incomplete','September','2024-09-22'),(5,'2024-09-23','10:43','CUFL','dir TE fr','','','','Chairman BCIC f','Conference Room','Complete','September','2024-09-22'),(6,'2024-09-22','11:28','Dap','Dir PI','','','','Chairman BCIC','Conference Room','Incomplete','September','2024-09-22');
/*!40000 ALTER TABLE `dir_com` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dir_com` with 6 row(s)
--

--
-- Table structure for table `dir_fin`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dir_fin` (
  `id` int(10) NOT NULL DEFAULT 0,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `focal_point` varchar(150) NOT NULL,
  `zoom_id` varchar(100) NOT NULL,
  `zoom_passcode` varchar(100) NOT NULL,
  `zoom_link` varchar(255) NOT NULL,
  `president` varchar(150) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dir_fin`
--

LOCK TABLES `dir_fin` WRITE;
/*!40000 ALTER TABLE `dir_fin` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `dir_fin` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dir_fin` with 0 row(s)
--

--
-- Table structure for table `dir_pi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dir_pi` (
  `id` int(10) NOT NULL DEFAULT 0,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `focal_point` varchar(150) NOT NULL,
  `zoom_id` varchar(100) NOT NULL,
  `zoom_passcode` varchar(100) NOT NULL,
  `zoom_link` varchar(255) NOT NULL,
  `president` varchar(150) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dir_pi`
--

LOCK TABLES `dir_pi` WRITE;
/*!40000 ALTER TABLE `dir_pi` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `dir_pi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dir_pi` with 0 row(s)
--

--
-- Table structure for table `dir_pr`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dir_pr` (
  `id` int(10) NOT NULL DEFAULT 0,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `focal_point` varchar(150) NOT NULL,
  `zoom_id` varchar(100) NOT NULL,
  `zoom_passcode` varchar(100) NOT NULL,
  `zoom_link` varchar(255) NOT NULL,
  `president` varchar(150) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dir_pr`
--

LOCK TABLES `dir_pr` WRITE;
/*!40000 ALTER TABLE `dir_pr` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `dir_pr` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dir_pr` with 0 row(s)
--

--
-- Table structure for table `dir_te`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dir_te` (
  `id` int(10) NOT NULL DEFAULT 0,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `focal_point` varchar(150) NOT NULL,
  `zoom_id` varchar(100) NOT NULL,
  `zoom_passcode` varchar(100) NOT NULL,
  `zoom_link` varchar(255) NOT NULL,
  `president` varchar(150) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dir_te`
--

LOCK TABLES `dir_te` WRITE;
/*!40000 ALTER TABLE `dir_te` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `dir_te` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dir_te` with 0 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `division`
--

LOCK TABLES `division` WRITE;
/*!40000 ALTER TABLE `division` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `division` VALUES (1,'Personnel Division'),(2,'Accounts Division'),(3,'Commercial Division'),(4,'Technical Division'),(5,'MTS Division'),(6,'Chairman Secretariat'),(7,'Operation Division'),(8,'PRD'),(9,'PID'),(10,'RPD'),(11,'Marketing Division'),(12,'Audit Division'),(13,'Purchase Division'),(14,'Finance Division'),(15,'MIS Division'),(16,'Director (Com.)'),(17,'Director (Fin.)'),(18,'Director (P&I)'),(19,'Director (T&E)'),(20,'Director (Prod.)'),(21,'ICT Division'),(25,'Director (T&E)'),(26,'Director (P&I)'),(47,'AFCCL'),(48,'SFCL'),(49,'JFCL'),(50,'BISFL'),(51,'CUFL'),(52,'GPUFP'),(53,'GPFPLC'),(54,'DAPFCL'),(55,'TSPCL'),(56,'KPML'),(57,'UGSFL'),(58,'CCCL'),(59,'CCC'),(60,'34 Buffer Project'),(61,'13 Buffer Project'),(62,'UF-85 Project'),(63,'Chemical Godown, Shampur'),(64,'KNM & KHBM'),(65,'EMD'),(66,'Administration Division'),(67,'Senior General Manager (Admin)'),(68,'Planning Division'),(69,'Production Division'),(72,'HSET Division'),(73,'MTS (MECH)'),(74,'CSD'),(75,'BCIC College '),(76,'Legal Affairs Department'),(77,'Board & Co-ordination Department'),(78,'Company Affairs'),(79,'PDD'),(80,'ISHD'),(81,'Construction Division'),(82,'Forest Division');
/*!40000 ALTER TABLE `division` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `division` with 57 row(s)
--

--
-- Table structure for table `office`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office`
--

LOCK TABLES `office` WRITE;
/*!40000 ALTER TABLE `office` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `office` VALUES (1,'Chairman Secretariat'),(2,'Director (Commercial)'),(3,'Director (Finance)'),(4,'Director (P&R)'),(5,'Director (T&E)'),(6,'Director (P&I)'),(7,'CSD');
/*!40000 ALTER TABLE `office` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `office` with 7 row(s)
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
INSERT INTO `users` VALUES (1,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','buffer','Chairman Secretariat','','2024-08-12 10:37:20'),(2,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','Director (Commercial)','','2024-08-19 08:12:04'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','','','2024-06-01 17:19:24'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','bcic_hq','ICT Division','','2024-08-14 04:01:26'),(12,'bcic_mkt','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','bcic_hq','','user@yahoo.com','2024-06-01 06:48:45'),(14,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','bcic_hq','','admin@yahoo.com','2024-05-25 14:48:37'),(15,'mongla_port','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','port_office','','monglaport@yahoo.com','2024-05-31 18:39:31'),(17,'csd','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','CSD','','2024-08-15 11:05:13'),(20,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Chairman Secretariat','emrantareq09@gmail.com','2024-08-18 08:56:30'),(21,'admin2','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','CSD','emrantareq09@gmail.com','2024-08-18 10:25:51'),(22,'dir_fin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Director (Finance)','','2024-09-18 10:43:49'),(23,'dir_pr','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Director (P&R)','','2024-09-18 10:44:37');
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

-- Dump completed on: Sun, 22 Sep 2024 12:39:28 +0200
