-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: todolist_db
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
-- Table structure for table `chairman`
--

DROP TABLE IF EXISTS `chairman`;
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
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chairman`
--

LOCK TABLES `chairman` WRITE;
/*!40000 ALTER TABLE `chairman` DISABLE KEYS */;
INSERT INTO `chairman` VALUES (17,'2024-09-15','16:04','test 44','','2324234','5435','https://chatgpt.com/c/66e2ae4e-e7a0-8009-9336-9fdc2e7fc5d4','','Conference Room','Complete','September','2024-09-15'),(18,'2024-09-15','19:00','Test 2','','fdsf','dsf','','','Conference Room','Complete','September','2024-09-15'),(19,'2024-09-15','18:06','Test 444','','444','44444','','','Conference Room','Complete','September','2024-09-15'),(20,'2024-09-16','11:02','Test ','','2324234','5435','','','Conference Room','Complete','September','2024-09-15'),(21,'2024-09-18','15:00','test1','','345865','1234','','','Conference Room','Complete','September','2024-09-18'),(22,'2024-12-25','17:00','test 2','Dir PIff','','','','','Conference Room','Complete','December','2024-09-18'),(23,'2024-09-18','17:00','ttest','','','','','','Conference Room','Complete','September','2024-09-18'),(24,'2024-09-18','17:00','testv 33','','','','','','Conference Room','Complete','September','2024-09-18'),(25,'2024-09-18','17:00','test 55','','','','','','Conference Room','Complete','September','2024-09-18'),(26,'2024-09-19','12:00','About Lean Management System Online Meeting.','Dir (PI)','843 5828 3298','bcic','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-19'),(27,'2024-09-19','16:00','২০২৪-২০২৫ থেকে ২০২৬-২০২৭ অর্থবছর পর্যন্ত সময়ের জন্য বিসিআইসি প্রধান কার্যালয়ে সার্বক্ষণিক টেকনিশিয়ান রেখে অধিযাচন অনুযায়ী মালামাল সরবরাহ এবং','','','','','','Conference Room','Complete','September','2024-09-19'),(28,'2024-09-19','17:00','ফেডারেশন অব বিসিআইসি অফিসার্স ওয়েলফেয়ার এসোসিয়েশন এর সভাপতি জনাব মোঃ দেলোয়ার হোসেনের নেতৃত্বে বিসিআইসি প্রধান কার্যালয়','পরিচালক (বাণিজ্যিক)','345865','1234','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-19'),(29,'2024-09-19','16:12','JFCL board','Dir TE','','','','চেয়ারম্যান, বিসিআইসি','Board Room','Complete','September','2024-09-19'),(30,'2024-09-19','17:14','Test ','পরিচালক (বাণিজ্যিক)','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-19'),(31,'2024-09-22','11:29','শিল্প মন্ত্রণালয় এবং এর অধীন দপ্তর/সংস্থার অংশগ্রহণে ইনোভেশন প্রদর্শনী (শোকেসিং) অনুষ্ঠিত ১৫ মে ২০২৪ খ্রিস্টাব্দ, স্থান: শিল্প মন্ত্রণালয়ের ৩য় তলার লবি।','Dir PI','345865','12345','','Chairman BCIC','Conference Room','Complete','September','2024-09-22'),(32,'2024-09-22','13:13','TSPCL ','Dir PRE','843 5828 3298','bcic','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Board Room','Complete','September','2024-09-22'),(33,'2024-09-25','10:38','fdf','Dir PI ff','','','','Chairman BCIC','Conference Room','Complete','September','2024-09-25'),(34,'2024-09-26','10:39','fd','Dir PRE','','','','চেয়ারম্যান, বিসিআইসি','Board Room','Complete','September','2024-09-25'),(35,'2024-09-30','14:23','aaaaaaaaa','aaaaaaaaaaaa','345865','1234','','aaaaaaaaaaa','Board Room','Complete','September','2024-09-25'),(36,'2024-09-26','14:50','aaaaaaaaaa','aaaaaaaaaaaaaaaa','345865','1234','','aaa','Seminar Hall','Complete','September','2024-09-25'),(37,'2024-09-26','14:51','assssssss','ssssssssss','','1234','','ssssssssss','Seminar Hall','Complete','September','2024-09-25'),(38,'2024-09-25','14:52','sssssss','ssssssssss','','','','ssssssssss','Board Room','Complete','September','2024-09-25'),(39,'2024-09-25','14:53','aaaaaaaaaaa','hhhhhhhhhhhh','345865','bcic','','Chairman BCIC','Board Room','Complete','September','2024-09-25'),(40,'2024-09-25','15:44','wwwwwwwwwwwwww','পরিচালক (বাণিজ্যিক)','11111111111','111111111','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-25'),(41,'2024-10-08','13:38','JFCL BoaRD','dir TE f','','','','Chairman BCIC','Conference Room','Complete','October','2024-10-08'),(42,'2025-11-05','09:49','BCIC Board Meeting','Dir PI','843 5828 3298','123','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman ','Conference Room (4th Floor)','Complete','November','2025-11-05'),(43,'2025-11-05','09:50','SFCL Board Meeting','DIR FIn','8435828365','123','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','Chairman BCIC','Board Room (4th Floor)','Complete','November','2025-11-05'),(44,'2025-11-05','10:13','AFCCL board meeting','Dir PI','8435828365','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Conference Room (4th Floor)','Complete','November','2025-11-05'),(45,'2025-11-05','10:19','CUFL Bord meeting','Dir PRE','8435828365','1234','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Complete','November','2025-11-05'),(46,'2025-11-06','10:20','GPFPLC Board Meeting','পরিচালক (বাণিজ্যিক)','','','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Conference Room (4th Floor)','Complete','November','2025-11-05'),(47,'2025-11-11','09:49','BCIC Board Meeting','Dir PI','','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Board Room (4th Floor)','Complete','November','2025-11-11'),(48,'2025-11-11','09:49','SFCL Board Meeting','dir TE f','345865','bcic','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Conference Room (4th Floor)','Complete','November','2025-11-11'),(49,'2025-11-11','14:34','CUFL Board Meeting','dir TE f','','1234','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','Chairman BCIC','Conference Room (4th Floor)','Complete','November','2025-11-11'),(50,'2025-11-11','15:03','AFCCL Board Meeting','Dir Com','','','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','চেয়ারম্যান, বিসিআইসি','Board Room (4th Floor)','Complete','November','2025-11-11'),(51,'2025-11-24','09:54','BCIC Board Meeting ','Dir PI','','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Complete','November','2025-11-24'),(52,'2025-11-24','09:54','SFCL Board Meeting','Dir PRE','','1234','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','চেয়ারম্যান, বিসিআইসি','Board Room (4th Floor)','Complete','November','2025-11-24'),(53,'2025-11-25','09:55','AFCCL Meeting','Dir Com','843 5828 3298','bcic','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','Chairman BCIC f','Conference Room (4th Floor)','Complete','November','2025-11-24'),(54,'2025-11-24','09:56','JFCL Board Meeting ','Dir PRE','','','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','মোঃ দেলোয়ার হোসেন, ঊর্ধ্বতন মহাব্যবস্থাপক, আইসিটি বিভাগ','Conference Room (4th Floor)','Complete','November','2025-11-24'),(55,'2025-11-25','10:18','SFCL Board Meeting','পরিচালক (বাণিজ্যিক)','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Complete','November','2025-11-25'),(56,'2025-11-25','11:58','JFCL Board Meeting ','পরিচালক (বাণিজ্যিক)','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Complete','November','2025-11-25'),(57,'2025-11-26','14:41','BCIC Board Meeting','Dir PI','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Board Room (4th Floor)','Complete','November','2025-11-26'),(58,'2025-11-26','12:41','SFCL Board Meeting','Dir PRE','8435828365','1234','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','Chairman BCIC f','Conference Room (4th Floor)','Complete','November','2025-11-26'),(59,'2025-11-26','15:42','JFCL Board Meeting ','Dir Com','8435828365','12345','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Conference Room (4th Floor)','Complete','November','2025-11-26'),(60,'2025-11-27','14:15','BCIC Board Meeting','Dir PRE','345865','12345','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','Chairman BCIC f','Board Room (4th Floor)','Incomplete','November','2025-11-26'),(61,'2025-11-27','11:36','test','Dir PI','345865','12345','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','মোঃ দেলোয়ার হোসেন, ঊর্ধ্বতন মহাব্যবস্থাপক, আইসিটি বিভাগ','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(62,'2025-11-27','11:47','CUFL Board Meeting','পরিচালক (বাণিজ্যিক)','345865','bcic','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(63,'2025-11-27','11:48','JFCL Board Meeting','Dir PRE','','','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(64,'2025-11-27','11:51','AFCCL Board Meeting ','Dir Com','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(65,'2025-11-27','11:51','AFCCL Board Meeting ','Dir Com','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(66,'2025-11-27','11:55','SFCL B','Dir PRE','8435828365','1234','https://us02web.zoom.us/j/81073703007?pwd=1uKK4r7DcahWBYaSQNpLaQDWsaMNZx.1','Chairman BCIC f','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(69,'2025-11-27','12:03','DLCL ','Dir PRE','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(70,'2025-11-27','12:04','NBPM ','পরিচালক (বাণিজ্যিক)','3458656','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','চেয়ারম্যান, বিসিআইসি','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(71,'2025-11-27','12:05','KHBM board Meeting','Dir PI','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Conference Room (4th Floor)','Incomplete','November','2025-11-27'),(72,'2025-11-28','12:08','Novertise Board Meeting','Dir PI','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','Chairman BCIC','Conference Room (4th Floor)','Incomplete','November','2025-11-27');
/*!40000 ALTER TABLE `chairman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dir_com`
--

DROP TABLE IF EXISTS `dir_com`;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dir_com`
--

LOCK TABLES `dir_com` WRITE;
/*!40000 ALTER TABLE `dir_com` DISABLE KEYS */;
INSERT INTO `dir_com` VALUES (1,'2024-09-18','10:00','SFCL test 1','Dir Techinal','','','','Chairman BCIC','Conference Room','Complete','September','2024-09-19'),(2,'2024-09-22','10:38','JFCL Board Meeting','Dir FIn','','1234','','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-22'),(3,'2024-09-22','10:39','AFCCL Board','Dir Com','345865','1234','','Chairman BCIC','Board Room','Complete','September','2024-09-22'),(4,'2024-09-22','10:40','SFCL','Dir PI','8435828365','12345','','Chairman BCIC gf','Board Room','Complete','September','2024-09-22'),(5,'2024-09-23','10:43','CUFL','dir TE fr','','','','Chairman BCIC f','Conference Room','Complete','September','2024-09-22'),(6,'2024-09-22','11:28','Dap','Dir PI','','','','Chairman BCIC','Conference Room','Complete','September','2024-09-22'),(7,'2024-09-28','14:12','aaaaaaaaaa','aaaaaaaaa','1111','1111','','চেয়ারম্যান, বিসিআইসি','Conference Room','Complete','September','2024-09-25'),(8,'2024-09-25','14:13','aaa','aaa','345865','1234','https://us02web.zoom.us/j/84358283298?pwd=QzW1yGwaNVJmQpb1boOKbzufkGViif.1','aaa','Conference Room','Complete','September','2024-09-25'),(9,'2024-09-25','14:13','bbbb','bbbbbb','','','','bbbbbbb','Conference Room','Complete','September','2024-09-25'),(10,'2024-09-25','14:53','aaa','aaaaaa','','','','aaaaaaa','Board Room','Complete','September','2024-09-25'),(11,'2024-09-25','14:54','hhhhh','hhh','1111111111111','1234','','hhhh','Conference Room','Complete','September','2024-09-25'),(12,'2024-09-25','14:54','aaaaaaaaaaaa','aaaaaaaaa','','','','aaaaaaaaaaaaaaaaa','Board Room','Complete','September','2024-09-25'),(13,'2024-09-29','10:05','BORD BCIC','Dir Com','','','','Chairman BCIC','Board Room','Complete','September','2024-09-29'),(14,'2024-10-08','13:39','JFCL board','Dir PI','','','','Chairman BCIC f','Conference Room','Complete','October','2024-10-08');
/*!40000 ALTER TABLE `dir_com` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dir_fin`
--

DROP TABLE IF EXISTS `dir_fin`;
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
/*!40000 ALTER TABLE `dir_fin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dir_pi`
--

DROP TABLE IF EXISTS `dir_pi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dir_pi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `focal_point` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zoom_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zoom_passcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zoom_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `president` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `place` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `month` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dir_pi`
--

LOCK TABLES `dir_pi` WRITE;
/*!40000 ALTER TABLE `dir_pi` DISABLE KEYS */;
/*!40000 ALTER TABLE `dir_pi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dir_pr`
--

DROP TABLE IF EXISTS `dir_pr`;
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
/*!40000 ALTER TABLE `dir_pr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dir_te`
--

DROP TABLE IF EXISTS `dir_te`;
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
/*!40000 ALTER TABLE `dir_te` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `division`
--

DROP TABLE IF EXISTS `division`;
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
INSERT INTO `division` VALUES (1,'Personnel Division'),(2,'Accounts Division'),(3,'Commercial Division'),(4,'Technical Division'),(5,'MTS Division'),(6,'Chairman Secretariat'),(7,'Operation Division'),(8,'PRD'),(9,'PID'),(10,'RPD'),(11,'Marketing Division'),(12,'Audit Division'),(13,'Purchase Division'),(14,'Finance Division'),(15,'MIS Division'),(16,'Director (Com.)'),(17,'Director (Fin.)'),(18,'Director (P&I)'),(19,'Director (T&E)'),(20,'Director (Prod.)'),(21,'ICT Division'),(25,'Director (T&E)'),(26,'Director (P&I)'),(47,'AFCCL'),(48,'SFCL'),(49,'JFCL'),(50,'BISFL'),(51,'CUFL'),(52,'GPUFP'),(53,'GPFPLC'),(54,'DAPFCL'),(55,'TSPCL'),(56,'KPML'),(57,'UGSFL'),(58,'CCCL'),(59,'CCC'),(60,'34 Buffer Project'),(61,'13 Buffer Project'),(62,'UF-85 Project'),(63,'Chemical Godown, Shampur'),(64,'KNM & KHBM'),(65,'EMD'),(66,'Administration Division'),(67,'Senior General Manager (Admin)'),(68,'Planning Division'),(69,'Production Division'),(72,'HSET Division'),(73,'MTS (MECH)'),(74,'CSD'),(75,'BCIC College '),(76,'Legal Affairs Department'),(77,'Board & Co-ordination Department'),(78,'Company Affairs'),(79,'PDD'),(80,'ISHD'),(81,'Construction Division'),(82,'Forest Division');
/*!40000 ALTER TABLE `division` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ict`
--

DROP TABLE IF EXISTS `ict`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ict` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `focal_point` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zoom_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zoom_passcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zoom_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `president` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `place` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `month` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ict`
--

LOCK TABLES `ict` WRITE;
/*!40000 ALTER TABLE `ict` DISABLE KEYS */;
INSERT INTO `ict` VALUES (1,'2025-05-29','14:30','মতবিনিময় সভা','','','','','মোঃ দেলোয়ার হোসেন, ঊর্ধ্বতন মহাব্যবস্থাপক, আইসিটি বিভাগ','ICT Lab (3rd Floor)','Complete','May','2025-05-28 06:07:01','2025-05-28 06:07:01');
/*!40000 ALTER TABLE `ict` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_table`
--

DROP TABLE IF EXISTS `log_table`;
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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
INSERT INTO `log_table` VALUES (1,'','','user','127.0.0.1','2024-09-26 12:38:14',63694,'0000-00-00 00:00:00'),(2,'','','user','127.0.0.1','2024-09-26 12:41:45',99223,'0000-00-00 00:00:00'),(3,'','','user','127.0.0.1','2024-09-26 12:46:40',91371,'0000-00-00 00:00:00'),(4,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-09-26 12:47:55',15566,'0000-00-00 00:00:00'),(5,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-09-26 12:48:21',34079,'0000-00-00 00:00:00'),(6,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2024-09-26 12:48:36',30364,'0000-00-00 00:00:00'),(7,'dir_fin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-09-26 14:10:27',69175,'0000-00-00 00:00:00'),(8,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2024-09-26 14:10:47',39127,'0000-00-00 00:00:00'),(9,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2024-09-26 16:41:52',95698,'0000-00-00 00:00:00'),(10,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-09-29 10:02:10',55730,'0000-00-00 00:00:00'),(11,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-09-29 10:02:46',44653,'0000-00-00 00:00:00'),(12,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-09-29 10:05:27',64423,'0000-00-00 00:00:00'),(13,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-09-29 10:05:57',67282,'0000-00-00 00:00:00'),(14,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2024-09-29 10:06:51',67383,'0000-00-00 00:00:00'),(15,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-10-01 14:17:51',54553,'0000-00-00 00:00:00'),(16,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-10-03 09:46:05',46051,'0000-00-00 00:00:00'),(17,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-10-03 09:57:06',10331,'0000-00-00 00:00:00'),(18,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-10-08 13:36:30',18117,'0000-00-00 00:00:00'),(19,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-10-08 13:37:34',88214,'0000-00-00 00:00:00'),(20,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-10-08 13:39:42',76230,'0000-00-00 00:00:00'),(21,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-10-08 15:08:53',23414,'0000-00-00 00:00:00'),(22,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2024-11-26 09:33:28',47959,'0000-00-00 00:00:00'),(23,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 09:55:11',73673,'0000-00-00 00:00:00'),(24,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 09:55:33',60365,'0000-00-00 00:00:00'),(25,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 09:57:25',73124,'0000-00-00 00:00:00'),(26,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 09:57:57',57476,'0000-00-00 00:00:00'),(27,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 09:59:42',84391,'0000-00-00 00:00:00'),(28,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 10:04:22',57184,'0000-00-00 00:00:00'),(29,'dir_pr','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 10:29:49',83844,'0000-00-00 00:00:00'),(30,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 10:32:10',99982,'0000-00-00 00:00:00'),(31,'dir_pr','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 10:48:35',51245,'0000-00-00 00:00:00'),(32,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 10:51:03',39110,'0000-00-00 00:00:00'),(33,'test','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 10:51:40',12527,'0000-00-00 00:00:00'),(34,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 10:51:59',58778,'0000-00-00 00:00:00'),(35,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 10:52:39',48875,'0000-00-00 00:00:00'),(36,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 10:53:44',71227,'0000-00-00 00:00:00'),(37,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 12:05:52',24839,'0000-00-00 00:00:00'),(38,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 12:16:46',19070,'0000-00-00 00:00:00'),(39,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 12:16:55',69942,'0000-00-00 00:00:00'),(40,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 12:31:04',41773,'0000-00-00 00:00:00'),(41,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','127.0.0.1','2025-05-28 12:32:48',56428,'0000-00-00 00:00:00'),(42,'dir_pi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 12:38:10',78852,'0000-00-00 00:00:00'),(43,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 12:41:14',18767,'0000-00-00 00:00:00'),(44,'dir_pi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 12:41:29',54000,'0000-00-00 00:00:00'),(45,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 12:56:59',92394,'0000-00-00 00:00:00'),(46,'dir_pi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 12:57:17',87618,'0000-00-00 00:00:00'),(47,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:11:30',40622,'0000-00-00 00:00:00'),(48,'dir_pi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 13:11:53',77350,'0000-00-00 00:00:00'),(49,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 14:27:46',62667,'0000-00-00 00:00:00'),(50,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 14:28:33',35364,'0000-00-00 00:00:00'),(51,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 14:29:48',39705,'0000-00-00 00:00:00'),(52,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-05-28 14:32:22',77125,'0000-00-00 00:00:00'),(53,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-05 09:49:29',89038,'0000-00-00 00:00:00'),(54,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-05 10:13:03',95009,'0000-00-00 00:00:00'),(55,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-11 09:49:02',78528,'0000-00-00 00:00:00'),(56,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-11 14:30:33',78652,'0000-00-00 00:00:00'),(57,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-11 14:32:38',62943,'0000-00-00 00:00:00'),(58,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-24 09:54:33',68876,'0000-00-00 00:00:00'),(59,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-25 09:19:00',24285,'0000-00-00 00:00:00'),(60,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-26 12:41:11',44082,'0000-00-00 00:00:00'),(61,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-27 11:36:23',46419,'0000-00-00 00:00:00'),(62,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-27 11:47:06',64779,'0000-00-00 00:00:00'),(63,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-11-27 12:14:09',45630,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office`
--

DROP TABLE IF EXISTS `office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office`
--

LOCK TABLES `office` WRITE;
/*!40000 ALTER TABLE `office` DISABLE KEYS */;
INSERT INTO `office` VALUES (1,'Chairman Secretariat'),(2,'Director (Commercial)'),(3,'Director (Finance)'),(4,'Director (P&R)'),(5,'Director (T&E)'),(6,'Director (P&I)'),(7,'CSD'),(8,'ICT  Division');
/*!40000 ALTER TABLE `office` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','buffer','Chairman Secretariat','','2024-09-26 06:07:13'),(2,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','Director (Commercial)','','2024-08-19 08:12:04'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','','','2024-06-01 17:19:24'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','bcic_hq','ICT Division','','2024-08-14 04:01:26'),(12,'bcic_mkt','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','bcic_hq','','user@yahoo.com','2024-06-01 06:48:45'),(14,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','bcic_hq','','admin@yahoo.com','2024-05-25 14:48:37'),(15,'mongla_port','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','port_office','','monglaport@yahoo.com','2024-05-31 18:39:31'),(17,'csd','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','CSD','','2024-08-15 11:05:13'),(20,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','ICT  Division','emrantareq09@gmail.com','2025-05-28 04:52:32'),(21,'admin2','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','CSD','emrantareq09@gmail.com','2024-08-18 10:25:51'),(22,'dir_fin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Director (Finance)','','2024-09-18 10:43:49'),(23,'dir_pr','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Director (P&R)','','2024-09-18 10:44:37'),(29,'dir_pi','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Director (P&I)','dir.pi@bcic.gov.bd','2025-05-28 06:37:41');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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
