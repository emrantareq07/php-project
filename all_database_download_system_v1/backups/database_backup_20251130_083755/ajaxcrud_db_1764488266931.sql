-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ajaxcrud_db
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
-- Table structure for table `ajax_todo_tbl`
--

DROP TABLE IF EXISTS `ajax_todo_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ajax_todo_tbl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `place` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `month` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ajax_todo_tbl`
--

LOCK TABLES `ajax_todo_tbl` WRITE;
/*!40000 ALTER TABLE `ajax_todo_tbl` DISABLE KEYS */;
INSERT INTO `ajax_todo_tbl` VALUES (2,'2022-02-08','সকাল ১১:০০ ঘটিকা','জেএফসিএল বোর্ড মিটিং','বোর্ড অফিস কনফারেন্স রুম','incomplete',''),(4,'2022-02-09','55','dfsf','fdsf','incomplete',''),(5,'2022-02-11','4','tsp','c','complete',''),(6,'2022-02-08','5','Test ','fdg','incomplete',''),(9,'2024-08-12','10:00','JFCLvvv b','Conference Room	','incomplete','August'),(11,'2024-08-11','12:10','Test vffd','Board Room','incomplete','August'),(14,'2024-08-13','10:00','AFCCL Board mm','Conference Room	','incomplete','August'),(15,'2024-08-12','15:00','safdsf','Board Room','incomplete','August'),(17,'2024-08-12','16:00','jkljk','Seminar Hall','incomplete','August');
/*!40000 ALTER TABLE `ajax_todo_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crud`
--

DROP TABLE IF EXISTS `crud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crud` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crud`
--

LOCK TABLES `crud` WRITE;
/*!40000 ALTER TABLE `crud` DISABLE KEYS */;
INSERT INTO `crud` VALUES (1,'tareq','emrantareq@yahoo.com','23456','dhaka');
/*!40000 ALTER TABLE `crud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table2`
--

DROP TABLE IF EXISTS `table2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_first_name` varchar(250) NOT NULL,
  `student_last_name` varchar(250) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table2`
--

LOCK TABLES `table2` WRITE;
/*!40000 ALTER TABLE `table2` DISABLE KEYS */;
INSERT INTO `table2` VALUES (1,'John','Smith','Male'),(2,'Peter','Parker','Male'),(4,'Donna','Huber','Male'),(5,'Anastasia','Peterson','Male'),(6,'Ollen','Donald','Male'),(10,'Joseph','Stein','Male'),(11,'Wilson','Fischer','Male'),(12,'Lillie','Kirst','Female'),(13,'James','Whitchurch','Male'),(14,'Timothy','Brewer','Male'),(16,'Sally','Martin','Male'),(17,'Allison','Pinkston','Male'),(18,'Karen','Davis','Male'),(19,'Jaclyn','Rocco','Male'),(20,'Pamela','Boyter','Male'),(21,'Anthony','Alaniz','Male'),(22,'Myrtle','Stiltner','Male'),(23,'Gary','Hernandez','Male'),(24,'Fred','Jeffery','Male'),(25,'Ronald','Stjohn','Male'),(26,'Stephen','Mohamed','Male'),(28,'Michael','Dyer','Male'),(29,'Betty','Beam','Male'),(30,'Anna','Peterson','Female'),(31,'Peter','Stodola','Male'),(32,'Ralph','Jones','Male');
/*!40000 ALTER TABLE `table2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'jamal','tareq@gmail.com','4567890','2022-08-08 18:56:02','2022-08-08 18:56:02','1'),(3,'lalaon hh','f@hggg.com','45678903456','2022-08-08 19:11:20','2023-05-21 10:53:52','1');
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

-- Dump completed on 2025-11-30 13:37:47
