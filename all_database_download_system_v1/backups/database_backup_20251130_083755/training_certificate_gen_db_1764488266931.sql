-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: training_certificate_gen_db
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
-- Table structure for table `authority_tbl`
--

DROP TABLE IF EXISTS `authority_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authority_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch` varchar(50) NOT NULL,
  `training_title` longtext NOT NULL,
  `organized_by` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `name1` varchar(255) NOT NULL,
  `designation1` varchar(100) NOT NULL,
  `office1` varchar(255) NOT NULL,
  `ministry1` varchar(100) NOT NULL,
  `signature1` varchar(255) NOT NULL,
  `name2` varchar(255) NOT NULL,
  `designation2` varchar(100) NOT NULL,
  `office2` varchar(100) NOT NULL,
  `ministry2` varchar(100) NOT NULL,
  `signature2` varchar(255) DEFAULT NULL,
  `active_status` varchar(10) NOT NULL,
  `tr_link_status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authority_tbl`
--

LOCK TABLES `authority_tbl` WRITE;
/*!40000 ALTER TABLE `authority_tbl` DISABLE KEYS */;
INSERT INTO `authority_tbl` VALUES (2,'1','Departmental Litigation, Disciplinary & Appeal Rules','conducted by Personnel Division, BCIC.','2025-09-16','2025-09-16','Mr. A.N.M. Shariful Alam','Chief of Personnel','Bangladesh Chemical Industries Corporation','Under Ministry of Industries','uploads/68c7939cbdccf_signature.png','Mr. Md. Fazlur Rahman','Chairman','Bangladesh Chemical Industries Corporation','Under Ministry of Industries','uploads/68c7939cbdde4_signature.png','active','active','2025-09-15 04:18:36','2025-09-15');
/*!40000 ALTER TABLE `authority_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_tbl`
--

DROP TABLE IF EXISTS `users_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `place_of_posting` varchar(100) DEFAULT NULL,
  `office` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '1234',
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `batch` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_tbl`
--

LOCK TABLES `users_tbl` WRITE;
/*!40000 ALTER TABLE `users_tbl` DISABLE KEYS */;
INSERT INTO `users_tbl` VALUES (1,'5620-0','emran','Programmer','ICT','ICT','H.O',NULL,'01718834655','test@yahoo.com','1234','sadmin','active','','2025-09-15 03:39:12','2025-09-15 03:39:43'),(2,'','Mr. A.N.M. Shariful Alam','Chief of Personnel','','','BCIC H.O.','','01718956354','cop@bcic.gov.bd','1234','user','active','1','2025-09-15 04:40:24','2025-09-15 04:40:24'),(3,'','Mr. Mohammad Sarwar Jahan','General Manager (Administration)','','','BCIC H.O.','','01716791307','sarwar3746-5@bcic.gov.bd','1234','user','active','1','2025-09-15 05:04:08','2025-09-15 05:04:45'),(8,'','Mr. Mohammad Anwarur Rashid','Deputy General Manager (Administration)','','','BCIC H.O.','','1716956408','anwarurrashid4619-3@bcic.gov.bd','1234','user','active','1','2025-09-15 05:06:37','2025-09-15 05:06:37'),(9,'','Mr. Nazmun Nahar','Deputy General Manager (Administration)','','','BCIC H.O.','','01764377884','nazmunnahar4455-2@bcic.gov.bd','1234','user','active','1','2025-09-15 05:08:18','2025-09-15 05:08:18'),(10,'','Mr. Md. Masud Parvez','Deputy General Manager','','','BCIC H.O.','','01716575709','masud4454-5@bcic.gov.bd','1234','user','active','1','2025-09-15 05:09:43','2025-09-15 05:09:43'),(11,'','Mr. Md. Mushtaq Ahmed','Deputy General Manager','','','BCIC H.O.','','01767841930','mushtaq4664-9@bcic.gov.bd','1234','user','active','1','2025-09-15 05:10:41','2025-09-15 05:10:41'),(12,'','Mr. Md. Alta Masul Islam','Deputy General Manager (Administration)','','','BCIC H.O.','','01632206927','althamasulbcic@gmail.com','1234','user','active','1','2025-09-15 05:12:26','2025-09-15 05:12:26'),(13,'','Mr. Md. Mostakar Rahman','Deputy Chief of Personnel','','','BCIC H.O.','','1767929400','mostaker4725-8@bcic.gov.bd','1234','user','active','1','2025-09-15 05:13:47','2025-09-15 05:13:47'),(14,'','Mr. Serniabat Rezaul Bari','General Manager (Commercial)','','','BCIC H.O.','','01716790877','bary3813-3@bcic.gov.bd','1234','user','active','1','2025-09-15 05:15:29','2025-09-15 05:15:29'),(15,'','Mr. Md. Saiful Alam','General Manager','','','BCIC H.O.','','01734993175','salam4444-6@bcic.gov.bd','1234','user','active','1','2025-09-15 05:17:53','2025-09-15 05:17:53'),(16,'','Mr. Md. Manzoor Reza','Managing Director & Divisional Head (Marketing)','','','BCIC H.O.','','01550042931','monzur3910-7@bcic.gov.bd','1234','user','active','1','2025-09-15 05:20:09','2025-09-15 05:20:09'),(17,'','Mr. S. M. Sohel Ahmed','Controller of Accounts','','','BCIC H.O.','','01817544673','acct.ca@bcic.gov.bd','1234','user','active','1','2025-09-15 05:21:45','2025-09-15 05:21:45'),(18,'','Mr. Md. Ghulam Farooq','Chief Finance Officer','','','BCIC H.O.','','01818542237','faruque3898-4@bcic.gov.bd','1234','user','active','1','2025-09-15 05:23:08','2025-09-15 05:23:08'),(19,'','Mr. Karimun nessa','General Manager (Administration)','','','BCIC H.O.','','01720920766','karumunnesa3728-3@bcic.gov.bd','1234','user','active','1','2025-09-15 05:24:19','2025-09-15 05:24:19'),(20,'','Mr. Mohammad Shamim Rana','Deputy General Manager (Commercial)','','','BCIC H.O.','','01753331331','msrana4524-5@bcic.gov.bd','1234','user','active','1','2025-09-15 05:26:00','2025-09-15 05:26:00'),(21,'','Mr. Md. Abdul Wahhab','General Manager (Production)','','','BCIC H.O.','','01815069168','wahhab3496-7@bcic.gov.bd','1234','user','active','1','2025-09-15 05:27:20','2025-09-15 05:27:20'),(22,'','Mr. Edi Amin Sarker','Chief Engr.(MTS)','','','BCIC H.O.','','01913652664','edi3904-0@bcic.gov.bd','1234','user','active','1','2025-09-15 05:28:48','2025-09-15 05:28:48'),(23,'','Mr. Md. Romisur Rahman','Chemist and Head of Department (PDD)','','','BCIC H.O.','','01729761159','romisur3718-4@bcic.gov.bd','1234','user','active','1','2025-09-15 05:30:12','2025-09-15 05:30:12'),(24,'','Mr. Md. Mokdum Ali','Chief Engineer (Mechanical)','','','BCIC H.O.','','01712396890','mokdum3542-8@bcic.gov.bd','1234','user','active','1','2025-09-15 05:32:39','2025-09-15 05:32:39'),(25,'','Mr. Md. Selim Mahmud','Manager (Admin)','','','BCIC H.O.','','01735264170','selim4724-1@bcic.gov.bd','1234','user','active','1','2025-09-15 05:34:02','2025-09-15 05:34:02'),(26,'','Mr. Md Noor Nabi','General Manager (Com.)','','','BCIC H.O.','','01552446022','nur3960-2@bcic.gov.bd','1234','user','active','1','2025-09-15 05:35:14','2025-09-15 05:35:14'),(27,'','Mr. Md. Mizanur Rahman','General Manager (Administration)','','','BCIC H.O.','','01712508822','mizanur3835-6@bcic.gov.bd','1234','user','active','1','2025-09-15 05:37:20','2025-09-15 05:37:20'),(28,'','Mr. Md. Sajedul Alam','General Manager','','','BCIC H.O.','','01550042961','sajedul3837-2@bcic.gov.bd','1234','user','active','1','2025-09-15 05:38:18','2025-09-15 05:38:18'),(29,'','Mr. Md. Jahangir Kabir','Deputy General Manager (Administration)','','','BCIC H.O.','','01712152997','Jahangir3835-6@bcic.gov.bd','1234','user','active','1','2025-09-15 05:40:22','2025-09-15 05:40:22'),(30,'','Mr. Golam Rabbani','Deputy General Manager (Administration)','','','BCIC H.O.','','01716882561','rabbani4533-6@bcic.gov.bd','1234','user','active','1','2025-09-15 05:41:30','2025-09-15 05:41:30'),(31,'','Mr. Ahsan Quddus Kuntal','General Manager (Administration)','','','BCIC H.O.','','01749931284','ahsanqudduskuntal3725-9@bcic.gov.bd','1234','user','active','1','2025-09-15 05:42:44','2025-09-15 05:42:44'),(32,'','Mr. Mohammad Saiful Islam','	Deputy General Manager (Administration)','','','BCIC H.O.','','01556342178','saiful4464-4@bcic.gov.bd','1234','user','active','1','2025-09-15 05:43:28','2025-09-15 05:43:28'),(33,'','Mr. Md. Asaduzzaman','Deputy General Manager','','','BCIC H.O.','','01883697910','asaduzzaman4458-6@bcic.gov.bd','1234','user','active','1','2025-09-15 05:45:52','2025-09-15 05:45:52'),(34,'','Mr. Md. Golam Mostafa','	General Manager (Accounts & Finance)','','','BCIC H.O.','','01712017512','golammostafa3962-8@bcic.gov.bd','1234','user','active','1','2025-09-15 05:47:06','2025-09-15 05:47:06'),(35,'','Mr. Md. Anwar Hossain','Senior General Manager','','','BCIC H.O.','','01711351403','anwar3929-7@bcic.gov.bd','1234','user','active','1','2025-09-15 05:54:14','2025-09-15 05:54:14');
/*!40000 ALTER TABLE `users_tbl` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 13:37:55
