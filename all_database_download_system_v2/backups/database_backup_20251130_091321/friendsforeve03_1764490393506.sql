-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: friendsforeve03
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
-- Table structure for table `friend_requests`
--

DROP TABLE IF EXISTS `friend_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `alt_mobile` varchar(50) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `occupation` varchar(191) DEFAULT NULL,
  `jobplace` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friend_requests`
--

LOCK TABLES `friend_requests` WRITE;
/*!40000 ALTER TABLE `friend_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `friend_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `alt_mobile` varchar(50) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `occupation` varchar(191) DEFAULT NULL,
  `jobplace` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `blood_group` varchar(15) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friends`
--

LOCK TABLES `friends` WRITE;
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
INSERT INTO `friends` VALUES (7,'test','01913428714','45634534535','','Govt. Service','Dhaka','zxc','A+','uploads/profile_68e2157ae90ff7.52896517.jpg','2025-10-05 06:51:38','approved'),(8,'sssrrrrr','01913428714','01718834655','emran@yahoo.com','Govt. Service','Dhaka','sdf','A-','uploads/profile_68e219b97e5900.84122994.jpeg','2025-10-05 07:09:45','pending'),(9,'MD. ABUL HOSSAIN','0137896654','90124823','hasan@yahoo.com','sfs','Dhaka','dd','A-','uploads/profile_68e23140ec3a84.28364189.jpeg','2025-10-05 08:50:08','approved'),(10,'x','0137896654','90124823','hasan@yahoo.com','sf','Dhaka','dsf','B-','uploads/profile_68e23179292610.37746645.jpeg','2025-10-05 08:51:05','pending'),(11,'y','0137896654','90124823','hasan44@yahoo.com','Govt. Service','sdf','sdfd','A+','uploads/profile_68e231bf179ca4.02065774.jpg','2025-10-05 08:52:15','approved'),(12,'jjj','0137896654','90124823','hasan44@yahoo.com','sdf','sdf','sd','O+','uploads/profile_68e231f7c80ad0.81082950.jpg','2025-10-05 08:53:11','pending'),(13,'vadro','01378966544','01718834655','hasan44@yahoo.com','Govt. Service','Dhaka','dsfsd','A+','uploads/profile_68e4cda2cc3791.20362953.jpg','2025-10-07 08:21:54','pending');
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;
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
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'emran','1234','2025-09-15'),(2,'user','$2y$10$GaubiISEpwd/0MeHd5EbV.AcbDigstWRJxkiprNcQzie.hE6PC/nW','2025-09-15');
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

-- Dump completed on 2025-11-30 14:13:16
