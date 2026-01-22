-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: training_certificate_gen_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Tue, 09 Sep 2025 12:41:56 +0200

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
-- Table structure for table `authority_tbl`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authority_tbl`
--

LOCK TABLES `authority_tbl` WRITE;
/*!40000 ALTER TABLE `authority_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `authority_tbl` VALUES (1,'2','Digital Transformation and E-Governance Digital Transformation and E-Governance Digital Transformation and E-Governance','','2025-08-26','2025-09-10','Designation: Additional Secretary (Training)','Designation: Additional Secretary (Training)','Office: Bangladesh Public Administration Training Centre (BPATC)','Office: Bangladesh Public Administration Training Centre (BPATC)','uploads/68b678f221253_signature 300_80.jpg','Name: Engr. Md. Anwar Hossain','Designation: Director (ICT)','Office: Bangladesh Computer Council (BCC)','Ministry: Ministry of Posts, Telecommunications and Information Technology','uploads/68b678f22131b_signature 300_80.jpg','active','','2025-09-02 04:56:18','2025-09-07'),(2,'3','Digital Transformation and E-Governance Digital Transformation & E-Governance Digital Transformation and E-Governance\"','bcic ict division','2025-09-01','2025-09-10','test1','PD1','MOI','MOI','uploads/68b682abec2eb_signature 300_80.jpg','test2','PD2','MOI','MOI','uploads/68b682abec3d5_signature 300_80.jpg','active','','2025-09-02 05:37:47','2025-09-08'),(3,'1','PPR1','bcic ict division','2025-09-07','2025-09-22','Designation: Additional Secretary (Training)','Designation: Additional Secretary (Training)','Office: Bangladesh Public Administration Training Centre (BPATC)','Office: Bangladesh Public Administration Training Centre (BPATC)','uploads/68bd4b78a19aa_signature1_1756627932.jpg','Name: Engr. Md. Anwar Hossain','Designation: Director (ICT)','Office: Bangladesh Computer Council (BCC)','Ministry: Ministry of Posts, Telecommunications and Information Technology','uploads/68bd4b78a1acd_signature1_1756627961.jpg','Inactive','Inactive','2025-09-07 09:08:08','2025-09-09'),(4,'4','তথ্য অধিকার আইন-২০০৯ বিষয়ক প্রশিক্ষণ।','','2025-09-07','2025-09-23','Designation: Additional Secretary (Training)','Designation: Additional Secretary (Training)','Office: Bangladesh Public Administration Training Centre (BPATC)','Office: Bangladesh Public Administration Training Centre (BPATC)','uploads/68bd4c457ccb8_signature2_1756634975.jpg','Name: Engr. Md. Anwar Hossain','Designation: Director (ICT)','Office: Bangladesh Computer Council (BCC)','Ministry: Ministry of Posts, Telecommunications and Information Technology','uploads/68bd4c457cdd8_signature1_1756627932.jpg','Inactive','','2025-09-07 09:11:33','2025-09-07'),(5,'5','তথ্য অধিকার আইন-২০০৯ বিষয়ক প্রশিক্ষণ।','bcic ict division','2025-09-08','2025-09-30','Designation: Additional Secretary (Training)','Designation: Additional Secretary (Training)','Office: Bangladesh Public Administration Training Centre (BPATC)','Office: Bangladesh Public Administration Training Centre (BPATC)','uploads/68be7eda6e1cf_68b678ba583e0_signature 300_80.jpg','Name: Engr. Md. Anwar Hossain','Designation: Director (ICT)','Office: Bangladesh Computer Council (BCC)','Ministry: Ministry of Posts, Telecommunications and Information Technology','uploads/68be7eda6e2e5_68b678f22131b_signature 300_80.jpg','Inactive','','2025-09-08 06:59:38','2025-09-08'),(6,'6','Nothi Management','bcic ict division','2025-09-01','2025-09-13','y1','Designation: Additional Secretary (Training)','MOI','MOI','uploads/68bfff014c64a_68b678ba583e0_signature 300_80.jpg','y2','PD2','MOI','MOI','uploads/68bfff014c72b_68b682abec2eb_signature 300_80.jpg','Inactive','active','2025-09-09 10:18:41','2025-09-09');
/*!40000 ALTER TABLE `authority_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `authority_tbl` with 6 row(s)
--

--
-- Table structure for table `users_tbl`
--

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_id` (`emp_id`),
  UNIQUE KEY `email_id` (`email_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_tbl`
--

LOCK TABLES `users_tbl` WRITE;
/*!40000 ALTER TABLE `users_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users_tbl` VALUES (1,'6594-6','Engr. Md. Anwar Hossain','Sub Assistant Engr.','test','test','BCIC H.O.','ICT','01718834655','test3@yahoo.com','1234','user','active','3','2025-09-01 09:23:53','2025-09-08 09:29:20'),(2,'admin','test','Sub Assistant Engr.','test','sdf','BCIC H.O.','ICT','01718834655','test@yahoo.com','1234','sadmin','active','1','2025-09-01 09:29:36','2025-09-07 09:12:46'),(3,'admin1','PD1','PD1','test','sdf','BCIC H.O.','','01718834655','pd1@yahoo.com','1234','admin','active','2','2025-09-02 03:36:55','2025-09-07 09:12:49'),(4,'admin2','PD2','PD2','test','A','BCIC H.O.','','01718834655','pd2@yahoo.com','1234','user','active','2','2025-09-02 03:37:41','2025-09-07 09:12:53'),(5,'11111','Mr. S. M. Sohel Ahmed','Assistant Manager','Civil test','sdf','BCIC H.O.','ICT  Division','11111111','test4@yahoo.com','1234','user','active','2','2025-09-02 08:22:03','2025-09-08 07:52:32');
/*!40000 ALTER TABLE `users_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users_tbl` with 5 row(s)
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

-- Dump completed on: Tue, 09 Sep 2025 12:41:56 +0200
