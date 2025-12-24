-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: blrr_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Thu, 13 Nov 2025 11:27:47 +0100

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
-- Table structure for table `accounts`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `accounts` VALUES (1,'chairman13','0000-00-00','হিসাব নিয়ন্ত্রক','Chairman Secretariat','','','1114','2025-10-16','test','ICT Division','test1','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 08:30:19','2025-10-16 08:30:19'),(2,'chairman19','0000-00-00','হিসাব নিয়ন্ত্রক','Chairman Secretariat','','','567567','2025-11-03','hala','dgd','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:31','2025-11-03 07:51:31');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `accounts` with 2 row(s)
--

--
-- Table structure for table `chairman`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chairman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) NOT NULL,
  `d_number` int(11) NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chairman`
--

LOCK TABLES `chairman` WRITE;
/*!40000 ALTER TABLE `chairman` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `chairman` VALUES (1,'chairman1','2025-09-24','চেয়ারম্যান','',1,'fsf','11','2025-09-24','test','ICT Division','test 2','','','2025-09-24','ff','','হার্ডকপি','Pending','0000-00-00 00:00:00','2025-09-24 03:45:26','2025-09-24 02:17:46'),(2,'chairman2','2025-09-24','চেয়ারম্যান','',2,'','2','2025-09-24','test','test','test 2','','পরিচালক (বাণিজ্যিক)','2025-09-24','','','হার্ডকপি','','0000-00-00 00:00:00','2025-09-24 06:24:00','2025-09-24 02:24:00'),(3,'chairman3','2025-09-24','চেয়ারম্যান','',3,'','44','2025-09-24','test','ICT Division','test1','','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ)','2025-09-24','','','হার্ডকপি','','0000-00-00 00:00:00','2025-09-24 06:56:21','2025-09-24 02:56:21'),(4,'chairman4','2025-09-24','চেয়ারম্যান','',4,'','789','2025-09-24','789','ICT Division 789','789','tt','পরিচালক (অর্থ)','2025-09-24','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-12 03:30:26','2025-09-24 03:46:45'),(5,'chairman5','2025-10-08','চেয়ারম্যান','',5,'tt','007','2025-10-08','MOI','ICT Divisionb','মতবিনিময় সভা','পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (অর্থ)','পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (অর্থ)','2025-10-08','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-08 10:09:15','2025-10-08 00:04:59'),(6,'chairman6','2025-10-08','চেয়ারম্যান','',6,'','006','2025-10-08','MOI','test2','meeting','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক)','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক)','2025-10-08','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-08 02:16:47','2025-10-08 00:41:08'),(7,'chairman7','2025-10-08','চেয়ারম্যান','',7,'','555','2025-10-08','MOI','ICT Division','meeeee','পরিচালক (অর্থ)','পরিচালক (অর্থ)','2025-10-08','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-08 07:34:03','2025-10-08 02:17:53'),(8,'ict11','0000-00-00','চেয়ারম্যান','ICT Division',0,'','222','2025-10-09','test','test','test 2','','','0000-00-00','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-09 06:22:21','2025-10-09 06:22:21'),(9,'chairman9','2025-10-09','চেয়ারম্যান','',8,'667','333645','2025-10-09','ICTD','ICT Division','ICTD','','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক)','2025-10-09','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-16 05:31:03','2025-10-09 06:44:05'),(13,'chairman13','2025-10-16','চেয়ারম্যান','',9,'','1114','2025-10-16','test','ICT Division','test1','','হিসাব নিয়ন্ত্রক','2025-10-16','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-16 08:30:19','2025-10-16 08:30:19'),(14,'chairman14','2025-10-16','চেয়ারম্যান','',10,'','3335','2025-10-16','hala','test','test 2','','পিএস','2025-10-16','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-16 08:31:07','2025-10-16 08:31:07'),(15,'chairman15','2025-10-16','চেয়ারম্যান','',11,'','33364','2025-10-16','MOI','test','test 2','','কর্মচারী প্রধান','2025-10-16','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-16 08:34:15','2025-10-16 08:34:15'),(16,'chairman16','2025-10-16','চেয়ারম্যান','',12,'','444','2025-10-16','MOI','test','test 2','','এমআইএস বিভাগ','2025-10-16','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-16 08:36:26','2025-10-16 08:36:26'),(17,'chairman17','2025-10-20','চেয়ারম্যান','',13,'','654545','2025-10-20','hala','ICT Division','test1','','পরিচালক (বাণিজ্যিক)','2025-10-20','','','হার্ডকপি','','0000-00-00 00:00:00','2025-10-20 04:50:17','2025-10-20 04:50:17'),(18,'chairman18','2025-11-03','চেয়ারম্যান','',14,'fsf','453453','2025-11-03','test','test','মতবিনিময় সভা','','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ)','2025-11-03','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-03 07:50:57','2025-11-03 07:50:57'),(19,'chairman19','2025-11-03','চেয়ারম্যান','',15,'','567567','2025-11-03','hala','dgd','sdfs','','হিসাব নিয়ন্ত্রক','2025-11-03','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-03 07:51:31','2025-11-03 07:51:31'),(20,'chairman20','2026-01-02','চেয়ারম্যান','',1,'','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ),কর্মচারী প্রধান,ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন),পিএস','2025-11-03','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(21,'chairman21','2026-01-15','চেয়ারম্যান','',2,'','98789','2025-11-03','hala','ICT Divisionb','test 2','','পরিচালক (অর্থ)','2025-11-03','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-03 07:52:59','2025-11-03 07:52:59'),(22,'chairman22','2025-11-03','চেয়ারম্যান','',16,'','43534','2025-11-03','MOI','ICT Divisiong','moi','','পরিচালক (কারিগরি ও প্রকৌশল)','2025-11-03','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-12 09:55:42','2025-11-03 07:53:25'),(23,'chairman23','2025-11-12','চেয়ারম্যান','',17,'','65656','2025-11-12','hala','ggg dfgdf fdf','test1','','পিএস','2025-11-12','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-12 10:11:07','2025-11-12 06:57:42'),(24,'chairman24','2025-11-12','চেয়ারম্যান','',18,'','65656','2025-11-12','fdsf','ICT Divisionb  mm','মতবিনিময় সভা','test','কর্মচারী প্রধান','2025-11-12','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-12 10:13:13','2025-11-12 10:11:22'),(25,'chairman25','2025-11-13','চেয়ারম্যান','',19,'fsf','333','2025-11-13','hala','test','test 2','fsdf','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ)','2025-11-13','dd','d','হার্ডকপি','','0000-00-00 00:00:00','2025-11-13 03:18:11','2025-11-13 03:17:46'),(26,'chairman26','2025-11-13','চেয়ারম্যান','',20,'dsfsf','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','dd dsfsf','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ),কর্মচারী প্রধান,ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন),পিএস','2025-11-13','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:19:25'),(27,'chairman27','2025-11-13','চেয়ারম্যান','',21,'','444 5','2025-11-13','ICTD ff','ICT Divisionb fv','ICTD','nnmm','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ),ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','2025-11-13','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-13 07:16:59','2025-11-13 03:33:56'),(28,'chairman28','2025-11-13','চেয়ারম্যান','',22,'','444675765','2025-11-13','MOI','ICT Divisionb','মতবিনিময় সভা','ttt','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','2025-11-13','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-13 07:31:08','2025-11-13 07:19:18'),(29,'chairman29','2025-11-13','চেয়ারম্যান','',23,'','4353453','2025-11-13','ICTD ff','ICT Divisionb','মতবিনিময় সভা','dfdf hhhh','পরিচালক (বাণিজ্যিক)','2025-11-13','','','হার্ডকপি','','0000-00-00 00:00:00','2025-11-13 08:29:11','2025-11-13 07:31:32');
/*!40000 ALTER TABLE `chairman` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `chairman` with 26 row(s)
--

--
-- Table structure for table `chairmanfile`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chairmanfile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) NOT NULL,
  `section_dept` varchar(100) NOT NULL,
  `d_number` int(11) NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sign_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_dropfile` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_sign_date` date NOT NULL,
  `time` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chairmanfile`
--

LOCK TABLES `chairmanfile` WRITE;
/*!40000 ALTER TABLE `chairmanfile` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `chairmanfile` VALUES (1,'','2025-10-01','হিসাব নিয়ন্ত্রক','','',1,'','','2025-10-02','','','test1','','চেয়ারম্যান','0000-00-00','','jgh\'r','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:39:47','2025-10-12 05:39:47'),(2,'','2025-10-01','কর্মচারী প্রধান','','',2,'','','2025-10-02','','','test 2','','চেয়ারম্যান','0000-00-00','','jgh\'r','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:40:18','2025-10-12 05:40:18'),(3,'','2025-10-03','ক্রয় বিভাগ','','',3,'','','2025-10-10','','','test1','','চেয়ারম্যান','0000-00-00','','jgh\\\'r','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:50:11','2025-10-12 05:50:11'),(4,'','2025-10-02','পরিচালক (বাণিজ্যিক)','','',4,'','','2025-10-04','','','ICTD','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:50:45','2025-10-12 05:50:45'),(5,'','2025-10-04','এমআইএস বিভাগ','','',4,'','','2025-10-08','','','d','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:51:15','2025-10-12 05:51:15'),(6,'','2025-10-02','পিএস','','',5,'','','2025-10-18','','','test1','','চেয়ারম্যান','0000-00-00','','hjj\'g','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:51:48','2025-10-12 05:51:48'),(7,'','2025-09-30','জনসংযোগ বিভাগ','','',5,'','','2025-10-09','','','ICTD','','চেয়ারম্যান','0000-00-00','','hjj\'g','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:52:01','2025-10-12 05:52:01'),(8,'','2025-10-01','ক্রয় বিভাগ','','',6,'','','2025-10-07','','','মতবিনিময় সভা','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 05:57:26','2025-10-12 05:57:26'),(9,'','2025-10-01','পিএস','','',7,'','','2025-10-02','','','test 2','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 06:11:35','2025-10-12 06:11:35'),(10,'','2025-10-02','ক্রয় বিভাগ','','',8,'','','2025-10-11','','','sdfsdfs','','চেয়ারম্যান','0000-00-00','','jgh\'r','','0000-00-00','0000-00-00 00:00:00','2025-10-12 06:13:41','2025-10-12 06:13:41'),(11,'','2025-10-02','ক্রয় বিভাগ','','',9,'','','2025-10-11','','','dfasd','','চেয়ারম্যান','0000-00-00','','dfs','','0000-00-00','0000-00-00 00:00:00','2025-10-12 06:15:11','2025-10-12 06:15:11'),(12,'','2025-10-02','এমটিএস বিভাগ','','',10,'','','2025-10-18','','','SFCL','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 06:16:31','2025-10-12 06:16:31'),(13,'','2025-10-01','ক্রয় বিভাগ','','',11,'','','2025-10-11','','','ICTD','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:03:47','2025-10-12 07:03:47'),(14,'','2025-10-01','ক্রয় বিভাগ','','',12,'','','2025-10-08','','','fdsfs','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:06:40','2025-10-12 07:06:40'),(15,'','2025-09-30','পরিচালক (বাণিজ্যিক)','','',13,'','','2025-10-09','','','sfs','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:09:16','2025-10-12 07:09:16'),(16,'','2025-10-01','চেয়ারম্যান','','',14,'','','2025-10-04','','','sdfs','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:10:30','2025-10-12 07:10:30'),(17,'','2025-10-01','পরিচালক (বাণিজ্যিক)','','',15,'','','2025-10-04','','','sdf','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:11:18','2025-10-12 07:11:18'),(18,'','2025-10-01','পরিচালক (উৎপাদন ও গবেষণা)','','',16,'','','2025-10-02','','','sgdfsd','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:13:22','2025-10-12 07:13:22'),(19,'','2025-10-03','পিএস','','',17,'','','2025-10-02','','','dfg','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:15:06','2025-10-12 07:15:06'),(20,'','2025-10-02','পরিচালক (অর্থ)','','',18,'','','2025-10-03','','','test1','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:19:56','2025-10-12 07:19:56'),(21,'','2025-10-01','এমআইএস বিভাগ','','',21,'','','2025-10-15','','','sffgf dedd','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:39:54','2025-10-12 07:22:53'),(22,'','2025-10-01','ক্রয় বিভাগ','','',20,'','','2025-10-09','','','test1','','চেয়ারম্যান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:33:20','2025-10-12 07:33:20'),(23,'','2025-10-02','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','','',22,'','','2025-10-01','','','sdfsdf','','পিএস','0000-00-00','','ds','','0000-00-00','0000-00-00 00:00:00','2025-10-12 07:56:16','2025-10-12 07:40:06'),(24,'','2025-10-23','পরিচালক (বাণিজ্যিক)','','',23,'','','2025-10-22','','','test 123fgd','','পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ)','0000-00-00','','fhfhgfhfg','','0000-00-00','0000-00-00 00:00:00','2025-10-23 06:27:45','2025-10-23 06:27:15'),(25,'','2025-10-23','হিসাব নিয়ন্ত্রক','','',24,'','','2025-10-24','','','hdfgdfg','','পরিচালক (অর্থ)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-10-23 06:28:47','2025-10-23 06:28:47'),(26,'','2025-10-23','সাধারণ কর্মশাখা','','',25,'','','2025-10-23','','','সংস্থার কার নং-১২-৮১১৬ মেরামত সংক্রান্ত।','','চেয়ারম্যান,পরিচালক (বাণিজ্যিক)','0000-00-00','','আলোচনা করুন।','','0000-00-00','0000-00-00 00:00:00','2025-10-23 07:57:18','2025-10-23 07:56:32'),(27,'','2025-10-23','চেয়ারম্যান','হিসাব নিয়ন্ত্রক','',26,'','','2025-10-24','','','াাাাাাাা','','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-03 08:17:06','2025-10-23 08:00:26'),(29,'','2025-11-03','0','হিসাব নিয়ন্ত্রক','test',27,'','','2025-11-03','','','fsf','','পরিচালক (অর্থ)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-03 08:00:18','2025-11-03 08:00:18'),(30,'','2025-11-03','চেয়ারম্যান','কর্মচারী প্রধান','RNT',28,'','','2025-11-03','','','RNT','','কর্মচারী প্রধান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-03 08:16:59','2025-11-03 08:02:31'),(31,'','2025-11-03','চেয়ারম্যান','পিএস','testbnmmbn',29,'','','2025-11-03','','','test1','','পরিচালক (বাণিজ্যিক)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-03 08:17:44','2025-11-03 08:17:29'),(32,'','2026-01-22','চেয়ারম্যান','কর্মচারী প্রধান','RNT',1,'','','2025-11-03','','','ICTD','','পরিচালক (অর্থ)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-03 08:23:33','2025-11-03 08:23:33'),(33,'','2026-01-22','চেয়ারম্যান','পরিচালক (অর্থ)','sdfs',2,'','','2025-11-03','','','sdfs','','পরিচালক (অর্থ)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-03 08:24:14','2025-11-03 08:24:14'),(34,'','2025-11-03','চেয়ারম্যান','পরিচালক (অর্থ)','sdf',30,'','','2025-11-03','','','fsd','','পরিচালক (অর্থ)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-03 08:24:34','2025-11-03 08:24:34'),(35,'','2025-11-04','চেয়ারম্যান','কর্মচারী প্রধান','প্রশাসন-২',31,'','','2025-11-04','','','বিভিন্ন কমিটিতে কর্মকর্তা মনোনয়ন।','','কর্মচারী প্রধান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-04 04:02:55','2025-11-04 04:02:55'),(36,'','2025-11-04','চেয়ারম্যান','ক্রয় বিভাগ','প্রশাসন-৩',32,'','','2025-11-05','','','জনাব মো: ইমরান হোসেন, সহকারী রসায়নবিদ এর বিরদ্ধে বিভাগীয় মামলা রুজুকরণ।','','পরিচালক (বাণিজ্যিক)','0000-00-00','','','','2025-11-04','0000-00-00 00:00:00','2025-11-04 06:53:20','2025-11-04 04:06:42'),(37,'','2025-11-06','চেয়ারম্যান','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','প্রকল্প বাস্তবায়ন বিভাগ',33,'','','2025-11-06','','','২০২৫ অর্থ বছরের সংশোধিত বাজেট প্রক্কলন।','','পরিচালক (অর্থ)','0000-00-00','','','','2025-11-06','0000-00-00 00:00:00','2025-11-06 03:29:20','2025-11-06 03:29:20'),(38,'','2026-01-07','চেয়ারম্যান','পরিচালক (বাণিজ্যিক)','LOCAL',3,'','','2025-11-09','','ক্রয় বিভাগ','test1','','হিসাব নিয়ন্ত্রক','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-09 04:16:17','2025-11-09 04:16:17'),(39,'','2025-11-09','চেয়ারম্যান','পরিচালক (বাণিজ্যিক)','LOCAL',34,'','','2025-11-09','','ক্রয় বিভাগ','মতবিনিময় সভা','','কর্মচারী প্রধান','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-09 04:18:07','2025-11-09 04:18:07'),(40,'','2025-11-09','চেয়ারম্যান','পরিচালক (বাণিজ্যিক)','Electrical',35,'','','2025-11-09','','এমআইএস বিভাগ','meeeee','','পরিচালক (অর্থ)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-09 04:40:45','2025-11-09 04:40:45'),(41,'','2025-11-09','চেয়ারম্যান','পরিচালক (বাণিজ্যিক)','ক্রয়',36,'','','2025-11-09','','হিসাব নিয়ন্ত্রক','সার ghgfh','','পরিচালক (বাণিজ্যিক)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-09 06:43:01','2025-11-09 06:31:53'),(42,'','2025-11-09','চেয়ারম্যান','পরিচালক (বাণিজ্যিক)','প্রশাসন-২',37,'','','2025-11-09','','','সিলেকশন কমিটি-ে২ (এসএসসি-২) এ শিল্প মন্ত্রণালয়ের প্রতিনিধি মনোনয়্ন','','পরিচালক (বাণিজ্যিক)','0000-00-00','','আলোচনা করুন।','','2025-11-09','0000-00-00 00:00:00','2025-11-09 08:40:25','2025-11-09 08:40:25'),(43,'','2025-11-12','চেয়ারম্যান','পরিচালক (অর্থ)','প্রশাসন-৩',38,'','','2025-11-12','','কর্মচারী প্রধান','বিভাগীয় মামলা রুজুকরণ প্রসঙ্গে।','','কর্মচারী প্রধান','0000-00-00','','আলোচনা করুন','','0000-00-00','0000-00-00 00:00:00','2025-11-12 03:51:09','2025-11-12 03:51:09'),(44,'','2025-11-12','চেয়ারম্যান','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','',39,'','','2025-11-12','','প্রকল্প বাস্তবায়ন বিভাগ','বববববব','','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','0000-00-00','','','','0000-00-00','0000-00-00 00:00:00','2025-11-12 03:53:00','2025-11-12 03:53:00'),(45,'','2025-11-13','প্রকল্প বাস্তবায়ন বিভাগ','পরিচালক (বাণিজ্যিক)','dd',40,'','','2025-11-13','','হিসাব নিয়ন্ত্রক','ICTD dd','','পরিচালক (বাণিজ্যিক),পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','0000-00-00','','ddb dfd','','0000-00-00','0000-00-00 00:00:00','2025-11-13 08:16:24','2025-11-13 08:03:47');
/*!40000 ALTER TABLE `chairmanfile` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `chairmanfile` with 44 row(s)
--

--
-- Table structure for table `cop`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cop`
--

LOCK TABLES `cop` WRITE;
/*!40000 ALTER TABLE `cop` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cop` VALUES (3,'chairman14','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','333','2025-03-06','test','','test2\'s','','','0000-00-00','ytr\'f','jgh\'r','হার্ডকপি','pending','0000-00-00 00:00:00','2025-03-06 05:24:34','2025-03-06 05:24:34'),(4,'chairman13','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','333','2025-03-06','test','','test\"h','','','0000-00-00','tte\'f','jgh\'r','হার্ডকপি','pending','0000-00-00 00:00:00','2025-03-06 05:24:55','2025-03-06 05:24:55'),(5,'chairman15','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','333','2025-04-29','test','111','11','','','0000-00-00','111','111','হার্ডকপি','pending','0000-00-00 00:00:00','2025-04-29 10:48:40','2025-04-29 10:48:40'),(6,'dirpi5','0000-00-00','কর্মচারী প্রধান','Director (P&I)','','','111','2025-09-22','MOI','','nn','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:02:41','2025-09-22 09:02:41'),(7,'chairman3','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','444','2025-09-22','werw','test','sf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:01:33','2025-09-22 10:01:33'),(8,'chairman5','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','777','2025-09-22','dfsf','sdfs','dfsf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:03:17','2025-09-22 10:03:17'),(9,'chairman7','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','666','2025-09-22','test','ICT Division','dfd','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:11:03','2025-09-22 10:11:03'),(10,'chairman11','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','11144','2025-10-16','test','ICT Divisionb','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 08:19:31','2025-10-16 08:19:31'),(11,'chairman12','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','3338','2025-10-16','test','ICT Division','test 2','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 08:22:58','2025-10-16 08:22:58'),(12,'chairman15','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','33364','2025-10-16','MOI','test','test 2','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 08:34:15','2025-10-16 08:34:15'),(13,'chairman20','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(15,'chairman24','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','65656','2025-11-12','fdsf','ICT Divisionb  mm','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-12 10:13:13','2025-11-12 10:13:13'),(17,'chairman26','0000-00-00','কর্মচারী প্রধান','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32');
/*!40000 ALTER TABLE `cop` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cop` with 13 row(s)
--

--
-- Table structure for table `designation_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designation_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designation_old`
--

LOCK TABLES `designation_old` WRITE;
/*!40000 ALTER TABLE `designation_old` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `designation_old` VALUES (1,'Chairman'),(2,'Director(T&E)'),(3,'Director_Commercial'),(5,'Director_finance'),(6,'Director(P&R)'),(7,'Director(P&I)'),(8,'Secretary'),(9,'COP'),(10,'Divisional Head(Marketing)'),(11,'Divisional Head(Audit)'),(12,'Divisional Head(EMD)'),(13,'Divisional Head(Saksha)'),(14,'Divisional Head(Law)'),(15,'Divisional Head(Company Affairs)'),(16,'Divisional Head(Purchase)'),(17,'Divisional Head(ICT)');
/*!40000 ALTER TABLE `designation_old` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `designation_old` with 16 row(s)
--

--
-- Table structure for table `dircom`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dircom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dircom`
--

LOCK TABLES `dircom` WRITE;
/*!40000 ALTER TABLE `dircom` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dircom` VALUES (1,'chairman2','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','2','2025-09-24','test','test','test 2','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-24 06:24:00','2025-09-24 02:24:00'),(2,'chairman3','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','44','2025-09-24','test','ICT Division','test1','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-24 06:56:21','2025-09-24 02:56:21'),(4,'chairman4','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','789','2025-09-24','789','ICT Division 789','789','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-24 10:31:03','2025-09-24 10:31:03'),(10,'chairman6','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','006','2025-10-08','MOI','test2','meeting','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-08 06:16:47','2025-10-08 06:16:47'),(26,'chairman9','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','333645','2025-10-09','ICTD','ICT Division','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 05:31:03','2025-10-16 05:31:03'),(27,'chairman17','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','654545','2025-10-20','hala','ICT Division','test1','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-20 04:50:17','2025-10-20 04:50:17'),(28,'chairman18','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','453453','2025-11-03','test','test','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:50:57','2025-11-03 07:50:57'),(29,'chairman20','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(31,'chairman25','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','333','2025-11-13','hala','test','test 2','','','0000-00-00','dd','d','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:18:11','2025-11-13 03:18:11'),(33,'chairman26','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32'),(42,'chairman27','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','444 5','2025-11-13','ICTD ff','ICT Divisionb fv','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 07:16:59','2025-11-13 07:16:59'),(47,'chairman29','0000-00-00','পরিচালক (বাণিজ্যিক)','Chairman Secretariat','','','4353453','2025-11-13','ICTD ff','ICT Divisionb','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 08:29:11','2025-11-13 08:29:11');
/*!40000 ALTER TABLE `dircom` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dircom` with 12 row(s)
--

--
-- Table structure for table `dirfin`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dirfin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dirfin`
--

LOCK TABLES `dirfin` WRITE;
/*!40000 ALTER TABLE `dirfin` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dirfin` VALUES (1,'chairman3','2025-10-08','পরিচালক (অর্থ)','Chairman Secretariat','৪','','44','2025-09-24','test','ICT Division','test1','','আইসিটি বিভাগ','2025-10-08','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-24 06:56:21','2025-09-24 02:56:21'),(2,'chairman4','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','২','','789','2025-09-24','789','ICT Division 789','789','','আইসিটি বিভাগ','2025-10-08','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-24 10:33:47','2025-09-24 10:33:47'),(16,'chairman7','2025-10-08','পরিচালক (অর্থ)','Chairman Secretariat','১','','555','2025-10-08','','','meeeee','','','2025-10-08','','','','complete','0000-00-00 00:00:00','2025-10-08 09:13:36','2025-10-08 07:34:03'),(17,'chairman5','2025-10-08','পরিচালক (অর্থ)','Chairman Secretariat','7','','007','2025-10-08','MOI','ICT Divisionb','মতবিনিময় সভা','','আইসিটি বিভাগ','2025-10-09','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-10-08 10:09:15','2025-10-08 10:09:15'),(31,'chairman4','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','789','2025-09-24','789','ICT Division 789','789','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-12 03:30:26','2025-10-12 03:30:26'),(32,'chairman10','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','33356576','2025-10-16','test','test','test1','','','0000-00-00','ff','b','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 08:18:25','2025-10-16 08:18:25'),(33,'chairman18','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','453453','2025-11-03','test','test','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:50:57','2025-11-03 07:50:57'),(34,'chairman20','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(35,'chairman21','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','98789','2025-11-03','hala','ICT Divisionb','test 2','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:52:59','2025-11-03 07:52:59'),(37,'chairman25','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','333','2025-11-13','hala','test','test 2','','','0000-00-00','dd','d','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:18:11','2025-11-13 03:18:11'),(39,'chairman26','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32'),(48,'chairman27','0000-00-00','পরিচালক (অর্থ)','Chairman Secretariat','','','444 5','2025-11-13','ICTD ff','ICT Divisionb fv','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 07:16:59','2025-11-13 07:16:59');
/*!40000 ALTER TABLE `dirfin` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dirfin` with 12 row(s)
--

--
-- Table structure for table `dirpi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dirpi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dirpi`
--

LOCK TABLES `dirpi` WRITE;
/*!40000 ALTER TABLE `dirpi` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dirpi` VALUES (1,'chairman1','2025-10-08','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','১','','111','2025-09-22','test','ICT Division','sdfs','','','2025-10-08','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-22 09:37:32','2025-09-22 09:37:32'),(2,'chairman2','2025-10-09','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','৩','','111','2025-09-22','test','ICT Division','sdfs','r','আইসিটি বিভাগ','2025-10-09','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-22 09:37:32','2025-09-22 09:37:32'),(3,'chairman1','2025-10-09','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','৪','','111','2025-09-22','test','ICT Division','fsdf','th','আইসিটি বিভাগ','2025-10-10','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-22 09:38:44','2025-09-22 09:38:44'),(4,'dirte6','2025-09-22','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Director (T&E)','1','','345','2025-09-22','test','ICT Division','dsfs','','আইসিটি বিভাগ','2025-09-23','ww','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-22 09:48:37','2025-09-22 09:48:37'),(5,'chairman2','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','333','2025-09-22','test','ICT Division','sfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:55:37','2025-09-22 09:55:37'),(6,'chairman3','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','444','2025-09-22','werw','test','sf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:01:33','2025-09-22 10:01:33'),(10,'chairman7','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','666','2025-09-22','test','ICT Division','dfd','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:11:03','2025-09-22 10:11:03'),(11,'chairman3','2025-10-09','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','2','','44','2025-09-24','test','ICT Division','test1','test1','আইসিটি বিভাগ','2025-10-09','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-24 06:56:21','2025-09-24 02:56:21'),(22,'chairman6','2025-10-08','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','১','','006','2025-10-08','MOI','test2','meeting','','','2025-10-08','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-10-08 06:16:47','2025-10-08 06:16:47'),(36,'chairman9','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','333645','2025-10-09','ICTD','ICT Division','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 05:31:03','2025-10-16 05:31:03'),(37,'chairman18','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','453453','2025-11-03','test','test','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:50:57','2025-11-03 07:50:57'),(38,'chairman20','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(40,'chairman25','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','333','2025-11-13','hala','test','test 2','','','0000-00-00','dd','d','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:18:11','2025-11-13 03:18:11'),(42,'chairman26','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32'),(51,'chairman27','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','444 5','2025-11-13','ICTD ff','ICT Divisionb fv','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 07:16:59','2025-11-13 07:16:59'),(53,'chairman28','0000-00-00','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','Chairman Secretariat','','','444675765','2025-11-13','MOI','ICT Divisionb','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 07:31:08','2025-11-13 07:31:08');
/*!40000 ALTER TABLE `dirpi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dirpi` with 16 row(s)
--

--
-- Table structure for table `dirpr`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dirpr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dirpr`
--

LOCK TABLES `dirpr` WRITE;
/*!40000 ALTER TABLE `dirpr` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dirpr` VALUES (2,'chairman7','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','fds','2025-01-15','test','f','মহান স্বাধীনতা ও জাতীয় দিবস-২০২৪ উপলক্ষে বিসিআইসি’র চেয়ারম্যান (গ্রেড-১) পক্ষ থেকে বীর মুক্তিযোদ্ধাদের শুভেচ্ছা উপহার প্রদান। ','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-01-20 07:01:33','2025-01-20 07:01:33'),(9,'chairman14','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','333','2025-03-06','test','','test2\'s','','','0000-00-00','ytr\'f','jgh\'r','হার্ডকপি','pending','0000-00-00 00:00:00','2025-03-06 05:24:34','2025-03-06 05:24:34'),(10,'chairman13','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','333','2025-03-06','test','','test\"h','','','0000-00-00','tte\'f','jgh\'r','হার্ডকপি','pending','0000-00-00 00:00:00','2025-03-06 05:24:55','2025-03-06 05:24:55'),(11,'chairman15','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','333','2025-04-29','test','111','11','','','0000-00-00','111','111','হার্ডকপি','pending','0000-00-00 00:00:00','2025-04-29 10:48:40','2025-04-29 10:48:40'),(12,'chairman2','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','222','2025-09-22','test','ICT Division','tttt','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 06:29:42','2025-09-22 06:29:42'),(13,'dirpi4','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Director (P&I)','','','65656','2025-09-22','hala','','b','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:00:12','2025-09-22 09:00:12'),(14,'chairman4','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','dd','2025-09-22','dd','','dd','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:03:33','2025-09-22 09:03:33'),(15,'chairman1','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','111','2025-09-22','test','ICT Division','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:37:32','2025-09-22 09:37:32'),(16,'chairman2','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','111','2025-09-22','test','ICT Division','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:37:32','2025-09-22 09:37:32'),(17,'chairman1','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','111','2025-09-22','test','ICT Division','fsdf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:38:44','2025-09-22 09:38:44'),(18,'chairman3','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','444','2025-09-22','werw','test','sf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:01:33','2025-09-22 10:01:33'),(19,'chairman4','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','555','2025-09-22','test','','fdsf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:02:53','2025-09-22 10:02:53'),(22,'chairman7','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','666','2025-09-22','test','ICT Division','dfd','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:11:03','2025-09-22 10:11:03'),(23,'chairman3','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','44','2025-09-24','test','ICT Division','test1','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-24 06:56:21','2025-09-24 02:56:21'),(33,'chairman6','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','006','2025-10-08','MOI','test2','meeting','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-08 06:16:47','2025-10-08 06:16:47'),(34,'chairman5','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','007','2025-10-08','MOI','ICT Divisionb','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-08 10:09:15','2025-10-08 10:09:15'),(48,'chairman9','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','333645','2025-10-09','ICTD','ICT Division','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 05:31:03','2025-10-16 05:31:03'),(49,'chairman18','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','453453','2025-11-03','test','test','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:50:57','2025-11-03 07:50:57'),(50,'chairman20','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(52,'chairman25','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','333','2025-11-13','hala','test','test 2','','','0000-00-00','dd','d','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:18:11','2025-11-13 03:18:11'),(54,'chairman26','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32'),(63,'chairman27','0000-00-00','পরিচালক (উৎপাদন ও গবেষণা)','Chairman Secretariat','','','444 5','2025-11-13','ICTD ff','ICT Divisionb fv','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 07:16:59','2025-11-13 07:16:59');
/*!40000 ALTER TABLE `dirpr` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dirpr` with 22 row(s)
--

--
-- Table structure for table `dirte`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dirte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dirte`
--

LOCK TABLES `dirte` WRITE;
/*!40000 ALTER TABLE `dirte` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dirte` VALUES (1,'chairman2','2025-09-22','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','1','','222','2025-09-22','test','ICT Division','tttt','','আইসিটি বিভাগ','2025-09-22','','','হার্ডকপি','complete','0000-00-00 00:00:00','2025-09-22 06:29:42','2025-09-22 06:29:42'),(2,'ict15','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','ICT Division','','','333','2025-09-22','test','ICT Division','ffgg','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 06:35:11','2025-09-22 06:35:11'),(3,'chairman1','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','111','2025-09-22','test','ICT Division','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:37:32','2025-09-22 09:37:32'),(4,'chairman2','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','111','2025-09-22','test','ICT Division','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:37:32','2025-09-22 09:37:32'),(5,'chairman1','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','111','2025-09-22','test','ICT Division','fsdf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:38:44','2025-09-22 09:38:44'),(6,'','2025-09-22','পরিচালক (বাণিজ্যিক)','','1','11','345','2025-09-22','test','ICT Division','dsfs','','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','2025-09-22','ww','','হার্ডকপি','','0000-00-00 00:00:00','2025-09-22 09:48:37','2025-09-22 09:48:37'),(7,'chairman3','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','444','2025-09-22','werw','test','sf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:01:33','2025-09-22 10:01:33'),(11,'chairman7','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','666','2025-09-22','test','ICT Division','dfd','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:11:03','2025-09-22 10:11:03'),(12,'chairman3','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','44','2025-09-24','test','ICT Division','test1','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-24 06:56:21','2025-09-24 02:56:21'),(23,'chairman6','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','006','2025-10-08','MOI','test2','meeting','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-08 06:16:47','2025-10-08 06:16:47'),(24,'chairman5','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','007','2025-10-08','MOI','ICT Divisionb','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-08 10:09:15','2025-10-08 10:09:15'),(38,'chairman9','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','333645','2025-10-09','ICTD','ICT Division','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 05:31:03','2025-10-16 05:31:03'),(39,'chairman18','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','453453','2025-11-03','test','test','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:50:57','2025-11-03 07:50:57'),(40,'chairman20','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(44,'chairman22','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','43534','2025-11-03','MOI','ICT Divisiong','moi','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-12 09:55:42','2025-11-12 09:55:42'),(46,'chairman25','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','333','2025-11-13','hala','test','test 2','','','0000-00-00','dd','d','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:18:11','2025-11-13 03:18:11'),(48,'chairman26','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32'),(57,'chairman27','0000-00-00','পরিচালক (কারিগরি ও প্রকৌশল)','Chairman Secretariat','','','444 5','2025-11-13','ICTD ff','ICT Divisionb fv','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 07:16:59','2025-11-13 07:16:59');
/*!40000 ALTER TABLE `dirte` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dirte` with 18 row(s)
--

--
-- Table structure for table `division`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division` varchar(100) NOT NULL,
  `division_bn` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `table_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=337 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `division`
--

LOCK TABLES `division` WRITE;
/*!40000 ALTER TABLE `division` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `division` VALUES (1,'Chairman Secretariat','চেয়ারম্যান','chairman'),(2,'All Director','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ)',''),(3,'All Director & Sr.GM','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ),ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)',''),(4,'All Director & COP & Sr. GM & PS','পরিচালক (পরিকল্পনা ও বাস্তবায়ন),পরিচালক (কারিগরি ও প্রকৌশল),পরিচালক (উৎপাদন ও গবেষণা),পরিচালক (বাণিজ্যিক),পরিচালক (অর্থ),কর্মচারী প্রধান,ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন),পিএস',''),(5,'Director (Commercial)','পরিচালক (বাণিজ্যিক)','dircom'),(6,'Director (Finance)','পরিচালক (অর্থ)','dirfin'),(7,'Director (P&I)','পরিচালক (পরিকল্পনা ও বাস্তবায়ন)','dirpi'),(8,'Director (T&E)','পরিচালক (কারিগরি ও প্রকৌশল)','dirte'),(9,'Director (P&R)','পরিচালক (উৎপাদন ও গবেষণা)','dirpr'),(10,'Senior General Manager (Admin)','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','srgmadmin'),(11,'Personnel Division ','কর্মচারী প্রধান','cop'),(12,'Accounts Division','হিসাব নিয়ন্ত্রক','accounts'),(13,'Purchase Division','ক্রয় বিভাগ','purchase'),(14,'PS','পিএস','ps'),(15,'MIS Division','এমআইএস বিভাগ','mis'),(16,'MTS Division','এমটিএস বিভাগ','mts'),(19,'PRD','জনসংযোগ বিভাগ','prd'),(20,'PID','প্রকল্প বাস্তবায়ন বিভাগ','pid'),(21,'ICT Division','আইসিটি বিভাগ','ict'),(22,'RPD','গবেষণা ও উৎপাদনশৈলী বিভাগ','rpd'),(23,'Marketing Division','বিপণন বিভাগ','mkt'),(24,'Audit Division','নিরীক্ষা বিভাগ','audit'),(25,'Finance Division','অর্থ বিভাগ','finance'),(65,'EMD','এস্টেট ম্যানেজমেন্ট ডিপার্টমেন্ট (ইএমডি)','emd'),(68,'Planning Division','পরিকল্পনা বিভাগ','planing'),(69,'Production Division','উৎপাদন বিভাগ','production'),(74,'CSD','সাধারণ কর্মশাখা','csd'),(76,'Legal Affairs Department','আইন উপ-বিভাগ','legal'),(77,'Board & Co-ordination Department','বোর্ড ও সমন্বয় উপ-বিভাগ','board'),(78,'Company Affairs','কোম্পানী উপ-বিভাগ','comaffairs'),(79,'PDD','প্রজেক্ট ডিজাইন ডিভিশন','pdd'),(81,'Construction Division','নির্মাণ বিভাগ','construction'),(335,'','সিনিয়র সচিব',''),(336,'','সচিব','');
/*!40000 ALTER TABLE `division` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `division` with 34 row(s)
--

--
-- Table structure for table `ict`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ict` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ict`
--

LOCK TABLES `ict` WRITE;
/*!40000 ALTER TABLE `ict` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `ict` VALUES (1,'dirpi4','0000-00-00','আইসিটি বিভাগ','Director (P&I)','','','345','2025-09-22','test','ICT Division','dsfs','','','0000-00-00','ww','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 09:51:42','2025-09-22 09:51:42'),(3,'dirfin2','0000-00-00','আইসিটি বিভাগ','Director (Finance)','','','789','2025-09-24','','','789','','','0000-00-00','','','','pending','0000-00-00 00:00:00','2025-10-08 09:25:03','2025-10-08 09:25:03'),(4,'dirfin17','0000-00-00','আইসিটি বিভাগ','Director (Finance)','','','007','0000-00-00','','','মতবিনিময় সভা','','','0000-00-00','','','','pending','0000-00-00 00:00:00','2025-10-08 10:20:53','2025-10-08 10:20:53'),(5,'dirfin1','0000-00-00','আইসিটি বিভাগ','Director (Finance)','','','44','0000-00-00','','','test1','','','0000-00-00','','','','pending','0000-00-00 00:00:00','2025-10-08 10:27:25','2025-10-08 10:27:25'),(7,'dirpi2','0000-00-00','আইসিটি বিভাগ','Director (P&I)','','','111','0000-00-00','','','sdfs','','','0000-00-00','','','','pending','0000-00-00 00:00:00','2025-10-09 04:45:30','2025-10-09 04:45:30'),(9,'dirpi3','0000-00-00','আইসিটি বিভাগ','Director (P&I)','','','111','0000-00-00','','','fsdf','','','0000-00-00','','','','pending','0000-00-00 00:00:00','2025-10-09 04:51:53','2025-10-09 04:51:53'),(10,'dirpi11','0000-00-00','আইসিটি বিভাগ','Director (P&I)','','','44','0000-00-00','','','test1','','','0000-00-00','','','','pending','0000-00-00 00:00:00','2025-10-09 05:03:28','2025-10-09 05:03:28'),(11,'ict11','2025-10-09','আইসিটি বিভাগ','','1','11','222','2025-10-09','test','test','test 2','','চেয়ারম্যান','2025-10-09','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-09 06:22:21','2025-10-09 06:22:21');
/*!40000 ALTER TABLE `ict` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `ict` with 8 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `log_table` VALUES (1,'user','','user','::1','2025-01-06 14:49:50',28728,'0000-00-00 00:00:00'),(2,'user','','user','::1','2025-01-06 14:52:58',67449,'0000-00-00 00:00:00'),(3,'user','','user','::1','2025-01-06 14:53:30',49138,'0000-00-00 00:00:00'),(4,'user','','user','::1','2025-01-06 14:53:38',21100,'0000-00-00 00:00:00'),(5,'user','','user','::1','2025-01-06 14:54:17',20331,'0000-00-00 00:00:00'),(6,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 14:59:36',79522,'0000-00-00 00:00:00'),(7,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:25:00',22454,'0000-00-00 00:00:00'),(8,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:25:28',91091,'0000-00-00 00:00:00'),(9,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:25:55',96309,'0000-00-00 00:00:00'),(10,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:26:15',77806,'0000-00-00 00:00:00'),(11,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:50:43',93851,'0000-00-00 00:00:00'),(12,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:53:42',76674,'0000-00-00 00:00:00'),(13,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:01:11',77603,'0000-00-00 00:00:00'),(14,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:01:51',97765,'0000-00-00 00:00:00'),(15,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:02:02',15570,'0000-00-00 00:00:00'),(16,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:02:32',26785,'0000-00-00 00:00:00'),(17,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:03:03',61281,'0000-00-00 00:00:00'),(18,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:03:51',93973,'0000-00-00 00:00:00'),(19,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:04:38',95677,'0000-00-00 00:00:00'),(20,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:11:41',47811,'0000-00-00 00:00:00'),(21,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:12:17',56998,'0000-00-00 00:00:00'),(22,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:13:51',63313,'0000-00-00 00:00:00'),(23,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:14:57',10723,'0000-00-00 00:00:00'),(24,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:15:07',59710,'0000-00-00 00:00:00'),(25,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:17:03',31215,'0000-00-00 00:00:00'),(26,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:17:27',73207,'0000-00-00 00:00:00'),(27,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:17:43',19113,'0000-00-00 00:00:00'),(28,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:18:38',90477,'0000-00-00 00:00:00'),(29,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:19:43',43986,'0000-00-00 00:00:00'),(30,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:19:52',36671,'0000-00-00 00:00:00'),(31,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:20:07',58888,'0000-00-00 00:00:00'),(32,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:20:58',32747,'0000-00-00 00:00:00'),(33,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:21:21',41402,'0000-00-00 00:00:00'),(34,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:30:12',38714,'0000-00-00 00:00:00'),(35,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:31:07',73591,'0000-00-00 00:00:00'),(36,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:31:44',60576,'0000-00-00 00:00:00'),(37,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:33:46',78342,'0000-00-00 00:00:00'),(38,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','127.0.0.1','2025-01-06 16:35:59',98276,'0000-00-00 00:00:00'),(39,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:36:56',78911,'0000-00-00 00:00:00'),(40,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:17:08',17962,'0000-00-00 00:00:00'),(41,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:27:56',89400,'0000-00-00 00:00:00'),(42,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:28:23',57951,'0000-00-00 00:00:00'),(43,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:41:14',82359,'0000-00-00 00:00:00'),(44,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:42:26',49531,'0000-00-00 00:00:00'),(45,'user','ef62465e68ec3a10bb03b3c3a10a885e57116df6','user','127.0.0.1','2025-01-07 09:45:00',74480,'0000-00-00 00:00:00'),(46,'rnt','ef62465e68ec3a10bb03b3c3a10a885e57116df6','user','127.0.0.1','2025-01-07 09:45:18',86248,'0000-00-00 00:00:00'),(47,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 11:21:06',62662,'0000-00-00 00:00:00'),(48,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 11:40:16',71390,'0000-00-00 00:00:00'),(49,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','127.0.0.1','2025-01-07 15:24:34',38839,'0000-00-00 00:00:00'),(50,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 15:26:12',90884,'0000-00-00 00:00:00'),(51,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 16:33:37',88252,'0000-00-00 00:00:00'),(52,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-12 15:35:52',92423,'0000-00-00 00:00:00'),(53,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-12 16:09:29',25602,'0000-00-00 00:00:00'),(54,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-13 14:53:48',57208,'0000-00-00 00:00:00'),(55,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-01-14 15:04:10',95288,'0000-00-00 00:00:00'),(56,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-14 16:17:02',60726,'0000-00-00 00:00:00'),(57,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-01-14 16:30:21',54633,'0000-00-00 00:00:00'),(58,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-14 16:31:04',83650,'0000-00-00 00:00:00'),(59,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-01-14 16:31:16',62057,'0000-00-00 00:00:00'),(60,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-14 16:31:40',38623,'0000-00-00 00:00:00'),(61,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-15 09:16:34',57502,'0000-00-00 00:00:00'),(62,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-16 10:16:08',25411,'0000-00-00 00:00:00'),(63,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-16 10:16:26',49140,'0000-00-00 00:00:00'),(64,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-01-16 10:40:37',91574,'0000-00-00 00:00:00'),(65,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-16 10:51:22',35068,'0000-00-00 00:00:00'),(66,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-16 11:19:07',23052,'0000-00-00 00:00:00'),(67,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-16 11:21:36',81991,'0000-00-00 00:00:00'),(68,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-16 11:23:40',32421,'0000-00-00 00:00:00'),(69,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-19 13:01:21',20675,'0000-00-00 00:00:00'),(70,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-19 13:01:33',55034,'0000-00-00 00:00:00'),(71,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-19 16:08:45',22483,'0000-00-00 00:00:00'),(72,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-20 12:24:10',71557,'0000-00-00 00:00:00'),(73,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-20 13:03:35',58946,'0000-00-00 00:00:00'),(74,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-20 13:04:26',43560,'0000-00-00 00:00:00'),(75,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-20 14:09:30',90482,'0000-00-00 00:00:00'),(76,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-21 09:37:45',16564,'0000-00-00 00:00:00'),(77,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-21 14:21:19',10113,'0000-00-00 00:00:00'),(78,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-02-17 16:33:41',48325,'0000-00-00 00:00:00'),(79,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-02-18 10:10:13',65181,'0000-00-00 00:00:00'),(80,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-02-24 10:07:52',42712,'0000-00-00 00:00:00'),(81,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-02-24 10:08:13',20581,'0000-00-00 00:00:00'),(82,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-03-03 14:53:23',18014,'0000-00-00 00:00:00'),(83,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-03-06 10:38:14',38371,'0000-00-00 00:00:00'),(84,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-03-06 15:09:30',68090,'0000-00-00 00:00:00'),(85,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-03-20 15:09:16',82253,'0000-00-00 00:00:00'),(86,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-04-29 16:47:33',57587,'0000-00-00 00:00:00'),(87,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-04-29 16:48:03',45171,'0000-00-00 00:00:00'),(88,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-04-29 16:49:36',37863,'0000-00-00 00:00:00'),(89,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-06 10:23:22',78166,'0000-00-00 00:00:00'),(90,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:14:34',73209,'0000-00-00 00:00:00'),(91,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:21:17',31185,'0000-00-00 00:00:00'),(92,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:22:29',92522,'0000-00-00 00:00:00'),(93,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:23:12',60499,'0000-00-00 00:00:00'),(94,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:26:25',15749,'0000-00-00 00:00:00'),(95,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:27:18',22615,'0000-00-00 00:00:00'),(96,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:29:01',71242,'0000-00-00 00:00:00'),(97,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:30:17',35941,'0000-00-00 00:00:00'),(98,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:30:45',78117,'0000-00-00 00:00:00'),(99,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:34:35',52229,'0000-00-00 00:00:00'),(100,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:35:30',95337,'0000-00-00 00:00:00'),(101,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:36:18',28300,'0000-00-00 00:00:00'),(102,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:38:00',37518,'0000-00-00 00:00:00'),(103,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-09-18 13:14:37',20304,'0000-00-00 00:00:00'),(104,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:14:46',25281,'0000-00-00 00:00:00'),(105,'dircom','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:15:43',61941,'0000-00-00 00:00:00'),(106,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:18:03',52278,'0000-00-00 00:00:00'),(107,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:22:36',18061,'0000-00-00 00:00:00'),(108,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:23:43',37689,'0000-00-00 00:00:00'),(109,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:29:41',22021,'0000-00-00 00:00:00'),(110,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:29:48',79876,'0000-00-00 00:00:00'),(111,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:30:04',16036,'0000-00-00 00:00:00'),(112,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:33:05',90753,'0000-00-00 00:00:00'),(113,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 13:33:16',45226,'0000-00-00 00:00:00'),(114,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 16:38:02',41985,'0000-00-00 00:00:00'),(115,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 16:38:21',99721,'0000-00-00 00:00:00'),(116,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-18 16:39:23',50413,'0000-00-00 00:00:00'),(117,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 10:22:30',81661,'0000-00-00 00:00:00'),(118,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 10:42:06',24293,'0000-00-00 00:00:00'),(119,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 10:46:29',71171,'0000-00-00 00:00:00'),(120,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 10:47:47',72590,'0000-00-00 00:00:00'),(121,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 10:48:34',80426,'0000-00-00 00:00:00'),(122,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:00:50',75668,'0000-00-00 00:00:00'),(123,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:18:30',53777,'0000-00-00 00:00:00'),(124,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:20:43',99114,'0000-00-00 00:00:00'),(125,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:21:01',65445,'0000-00-00 00:00:00'),(126,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:23:42',48604,'0000-00-00 00:00:00'),(127,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:24:35',76785,'0000-00-00 00:00:00'),(128,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:25:09',63081,'0000-00-00 00:00:00'),(129,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:26:03',43831,'0000-00-00 00:00:00'),(130,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 11:42:13',71923,'0000-00-00 00:00:00'),(131,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 12:05:08',67709,'0000-00-00 00:00:00'),(132,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 12:14:51',92719,'0000-00-00 00:00:00'),(133,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 12:23:47',37461,'0000-00-00 00:00:00'),(134,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 12:29:21',51388,'0000-00-00 00:00:00'),(135,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 12:30:14',76531,'0000-00-00 00:00:00'),(136,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 12:32:28',40155,'0000-00-00 00:00:00'),(137,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 13:19:57',73448,'0000-00-00 00:00:00'),(138,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 13:33:12',12794,'0000-00-00 00:00:00'),(139,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 13:50:15',27385,'0000-00-00 00:00:00'),(140,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 13:51:22',26701,'0000-00-00 00:00:00'),(141,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 14:55:56',40221,'0000-00-00 00:00:00'),(142,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 14:58:59',44245,'0000-00-00 00:00:00'),(143,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 14:59:50',36635,'0000-00-00 00:00:00'),(144,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:03:19',93672,'0000-00-00 00:00:00'),(145,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:07:14',82477,'0000-00-00 00:00:00'),(146,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:37:05',13863,'0000-00-00 00:00:00'),(147,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:38:31',72887,'0000-00-00 00:00:00'),(148,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:41:21',87167,'0000-00-00 00:00:00'),(149,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:48:01',48296,'0000-00-00 00:00:00'),(150,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:50:42',63054,'0000-00-00 00:00:00'),(151,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:55:19',20234,'0000-00-00 00:00:00'),(152,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:57:40',69007,'0000-00-00 00:00:00'),(153,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 15:57:47',30433,'0000-00-00 00:00:00'),(154,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 16:00:37',33909,'0000-00-00 00:00:00'),(155,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 16:02:27',28447,'0000-00-00 00:00:00'),(156,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-22 16:34:24',28377,'0000-00-00 00:00:00'),(157,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-23 11:40:23',30246,'0000-00-00 00:00:00'),(158,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-23 12:56:02',30058,'0000-00-00 00:00:00'),(159,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-23 12:58:54',94483,'0000-00-00 00:00:00'),(160,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-23 13:02:57',91961,'0000-00-00 00:00:00'),(161,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-23 15:35:58',42349,'0000-00-00 00:00:00'),(162,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-23 15:36:06',41301,'0000-00-00 00:00:00'),(163,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-24 11:14:00',45327,'0000-00-00 00:00:00'),(164,'dircom','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-24 12:57:00',84201,'0000-00-00 00:00:00'),(165,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-09-24 12:57:57',85686,'0000-00-00 00:00:00'),(166,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-09-28 09:21:34',56510,'0000-00-00 00:00:00'),(167,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 09:55:27',60184,'0000-00-00 00:00:00'),(168,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 09:59:01',11058,'0000-00-00 00:00:00'),(169,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 09:59:09',31599,'0000-00-00 00:00:00'),(170,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 09:59:20',59745,'0000-00-00 00:00:00'),(171,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 09:59:39',52508,'0000-00-00 00:00:00'),(172,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 09:59:49',98638,'0000-00-00 00:00:00'),(173,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 13:14:00',64599,'0000-00-00 00:00:00'),(174,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 13:21:55',62312,'0000-00-00 00:00:00'),(175,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 13:23:58',64698,'0000-00-00 00:00:00'),(176,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 13:59:53',72732,'0000-00-00 00:00:00'),(177,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 15:16:59',20997,'0000-00-00 00:00:00'),(178,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 15:18:19',72129,'0000-00-00 00:00:00'),(179,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 15:29:40',64863,'0000-00-00 00:00:00'),(180,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 15:45:12',15971,'0000-00-00 00:00:00'),(181,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 16:06:41',51846,'0000-00-00 00:00:00'),(182,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 16:09:27',29418,'0000-00-00 00:00:00'),(183,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 16:27:42',53074,'0000-00-00 00:00:00'),(184,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-08 16:37:54',10422,'0000-00-00 00:00:00'),(185,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 09:50:30',74507,'0000-00-00 00:00:00'),(186,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 09:57:32',93769,'0000-00-00 00:00:00'),(187,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 12:06:22',57824,'0000-00-00 00:00:00'),(188,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 12:20:43',74831,'0000-00-00 00:00:00'),(189,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 12:37:49',42852,'0000-00-00 00:00:00'),(190,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 12:46:40',96661,'0000-00-00 00:00:00'),(191,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 12:47:15',25025,'0000-00-00 00:00:00'),(192,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 12:47:40',96080,'0000-00-00 00:00:00'),(193,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-10-09 15:52:16',42985,'0000-00-00 00:00:00'),(194,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-09 15:52:30',79569,'0000-00-00 00:00:00'),(195,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-10-09 15:58:46',38415,'0000-00-00 00:00:00'),(196,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-12 09:11:44',97994,'0000-00-00 00:00:00'),(197,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-12 09:39:06',91137,'0000-00-00 00:00:00'),(198,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-13 09:39:27',89825,'0000-00-00 00:00:00'),(199,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-16 10:29:21',43966,'0000-00-00 00:00:00'),(200,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.38','2025-10-16 11:24:58',87032,'0000-00-00 00:00:00'),(201,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-16 14:18:03',92688,'0000-00-00 00:00:00'),(202,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-20 10:49:33',22974,'0000-00-00 00:00:00'),(203,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-23 12:23:14',51043,'0000-00-00 00:00:00'),(204,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-23 12:34:57',84905,'0000-00-00 00:00:00'),(205,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-23 13:53:49',34020,'0000-00-00 00:00:00'),(206,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-23 15:01:42',95431,'0000-00-00 00:00:00'),(207,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-23 15:01:49',72076,'0000-00-00 00:00:00'),(208,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-10-28 09:35:45',34630,'0000-00-00 00:00:00'),(209,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-28 11:50:25',43812,'0000-00-00 00:00:00'),(210,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-29 09:13:18',41511,'0000-00-00 00:00:00'),(211,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-10-29 09:44:05',31769,'0000-00-00 00:00:00'),(212,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-03 11:42:33',84094,'0000-00-00 00:00:00'),(213,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-04 09:47:15',90216,'0000-00-00 00:00:00'),(214,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-04 09:57:47',55239,'0000-00-00 00:00:00'),(215,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-04 10:01:07',66718,'0000-00-00 00:00:00'),(216,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-04 10:20:18',54082,'0000-00-00 00:00:00'),(217,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-06 09:24:17',44600,'0000-00-00 00:00:00'),(218,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-06 09:28:22',19276,'0000-00-00 00:00:00'),(219,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-06 10:23:14',37894,'0000-00-00 00:00:00'),(220,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-09 09:40:19',72142,'0000-00-00 00:00:00'),(221,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-09 10:08:27',40351,'0000-00-00 00:00:00'),(222,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-09 10:51:57',19919,'0000-00-00 00:00:00'),(223,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-09 12:28:56',34995,'0000-00-00 00:00:00'),(224,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-09 12:29:37',74205,'0000-00-00 00:00:00'),(225,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-09 12:35:47',56153,'0000-00-00 00:00:00'),(226,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-09 14:37:09',39017,'0000-00-00 00:00:00'),(227,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-09 16:06:12',82117,'0000-00-00 00:00:00'),(228,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-10 09:38:10',14513,'0000-00-00 00:00:00'),(229,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-11 15:20:54',63110,'0000-00-00 00:00:00'),(230,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-12 09:46:33',76380,'0000-00-00 00:00:00'),(231,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-12 09:56:37',73071,'0000-00-00 00:00:00'),(232,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-12 12:56:52',89310,'0000-00-00 00:00:00'),(233,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-12 13:13:27',88046,'0000-00-00 00:00:00'),(234,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','192.168.1.62','2025-11-12 14:13:08',68849,'0000-00-00 00:00:00'),(235,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-12 15:49:33',22997,'0000-00-00 00:00:00'),(236,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-12 16:49:32',48180,'0000-00-00 00:00:00'),(237,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-13 09:16:55',64217,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `log_table` with 237 row(s)
--

--
-- Table structure for table `ps`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ps`
--

LOCK TABLES `ps` WRITE;
/*!40000 ALTER TABLE `ps` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `ps` VALUES (1,'chairman7','0000-00-00','পিএস','Chairman Secretariat','','','666','2025-09-22','test','ICT Division','dfd','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:11:03','2025-09-22 10:11:03'),(2,'chairman14','0000-00-00','পিএস','Chairman Secretariat','','','3335','2025-10-16','hala','test','test 2','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-10-16 08:31:07','2025-10-16 08:31:07'),(3,'chairman20','0000-00-00','পিএস','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(10,'chairman23','0000-00-00','পিএস','Chairman Secretariat','','','65656','2025-11-12','hala','ggg dfgdf fdf','test1','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-12 10:11:07','2025-11-12 10:11:07'),(12,'chairman26','0000-00-00','পিএস','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32');
/*!40000 ALTER TABLE `ps` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `ps` with 5 row(s)
--

--
-- Table structure for table `purchase_old`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_old` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `to_l` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destibution` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dest_date` date NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `server_time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `edit_count` int(100) NOT NULL,
  `rri_id` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_old`
--

LOCK TABLES `purchase_old` WRITE;
/*!40000 ALTER TABLE `purchase_old` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `purchase_old` VALUES (1,'2014-11-27','Purchase','Chairman','300','Dy. Officer (Rahim)','2014-11-30','purchase','2014-11-27 11:38:00','2014-11-27 07:01:00',2,11);
/*!40000 ALTER TABLE `purchase_old` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `purchase_old` with 1 row(s)
--

--
-- Table structure for table `srgmadmin`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `srgmadmin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attention` varchar(255) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `send_date` date NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `distribution_date` date NOT NULL,
  `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `time` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `srgmadmin`
--

LOCK TABLES `srgmadmin` WRITE;
/*!40000 ALTER TABLE `srgmadmin` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `srgmadmin` VALUES (2,'chairman7','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','fds','2025-01-15','test','f','মহান স্বাধীনতা ও জাতীয় দিবস-২০২৪ উপলক্ষে বিসিআইসি’র চেয়ারম্যান (গ্রেড-১) পক্ষ থেকে বীর মুক্তিযোদ্ধাদের শুভেচ্ছা উপহার প্রদান। ','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-01-20 07:01:33','2025-01-20 07:01:33'),(4,'chairman9','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','333','2025-01-20','test','','thyfgdh','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-01-20 07:11:25','2025-01-20 07:11:25'),(7,'chairman14','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','333','2025-03-06','test','','test2\'s','','','0000-00-00','ytr\'f','jgh\'r','হার্ডকপি','pending','0000-00-00 00:00:00','2025-03-06 05:24:34','2025-03-06 05:24:34'),(8,'chairman13','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','333','2025-03-06','test','','test\"h','','','0000-00-00','tte\'f','jgh\'r','হার্ডকপি','pending','0000-00-00 00:00:00','2025-03-06 05:24:55','2025-03-06 05:24:55'),(9,'chairman15','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','333','2025-04-29','test','111','11','','','0000-00-00','111','111','হার্ডকপি','pending','0000-00-00 00:00:00','2025-04-29 10:48:40','2025-04-29 10:48:40'),(10,'chairman3','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','444','2025-09-22','werw','test','sf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:01:33','2025-09-22 10:01:33'),(11,'chairman5','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','777','2025-09-22','dfsf','sdfs','dfsf','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:03:17','2025-09-22 10:03:17'),(13,'chairman7','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','666','2025-09-22','test','ICT Division','dfd','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-09-22 10:11:03','2025-09-22 10:11:03'),(16,'chairman20','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','3343','2025-11-03','test 26','ICT Divisionb','sdfs','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-03 07:51:58','2025-11-03 07:51:58'),(18,'chairman26','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','534534','2025-11-13','MOI','ICT Division','মতবিনিময় সভা','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 03:33:32','2025-11-13 03:33:32'),(27,'chairman27','0000-00-00','ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)','Chairman Secretariat','','','444 5','2025-11-13','ICTD ff','ICT Divisionb fv','ICTD','','','0000-00-00','','','হার্ডকপি','pending','0000-00-00 00:00:00','2025-11-13 07:16:59','2025-11-13 07:16:59');
/*!40000 ALTER TABLE `srgmadmin` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `srgmadmin` with 11 row(s)
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
  `office` varchar(100) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `office_title` varchar(100) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','ICT Division','bcic_hq','division','sadmin','2024-09-05 09:23:44'),(24,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Chairman Secretariat','chairman','chairman','user','2024-09-05 07:58:40'),(26,'jsaim','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Chairman Secretariat','chairman','chairman','user','2025-01-14 10:31:26'),(27,'moja','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Chairman Secretariat','chairman','chairman','user','2025-01-14 10:31:31'),(30,'dirpi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Director (P&I)','dirpi','director','user','2024-09-05 07:58:53'),(32,'emran','40bd001563085fc35165329ea1ff5c5ecbdbbeef','ICT Division','ict','division','user','2024-09-05 09:24:08'),(34,'dirfin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Director (Finance)','dirfin','director','user','2024-09-08 05:55:36'),(35,'accounts','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Accounts Division','accounts','division','user','2024-09-08 06:38:34'),(36,'cop','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Personnel Division ','cop','division','user','2024-09-08 07:03:27'),(37,'dirte','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Director (T&E)','dirte','director','user','2024-09-08 08:39:17'),(38,'ps','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Chairman Secretariat','ps','division','user','2024-09-10 06:30:52'),(39,'srgm','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Senior General Manager (Admin)','srgmadmin','division','user','2024-09-10 06:32:51'),(40,'dirpr','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Director (P&R)','dirpr','director','user','2024-09-10 06:33:37'),(41,'dircom','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Director (Commercial)','dircom','director','user','2024-09-10 06:34:22'),(42,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','ICT Division','ict','division','user','2024-09-10 06:38:56'),(43,'ict','51eac6b471a284d3341d8c0c63d0f1a286262a18','ICT Division','ict','division','user','2024-09-10 06:40:13'),(44,'masud','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Director (Commercial)','dircom','director','user','2024-09-12 09:45:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 17 row(s)
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

-- Dump completed on: Thu, 13 Nov 2025 11:27:47 +0100
