-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: lms
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
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `author` (
  `BookId` int(10) NOT NULL,
  `Author` varchar(50) NOT NULL,
  PRIMARY KEY (`BookId`,`Author`),
  CONSTRAINT `author_ibfk_1` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (3,'Jay Prakash'),(4,'Jay Prakash'),(5,'x'),(6,'a1'),(7,'Bogart'),(7,'Kenneth'),(8,'Auer'),(8,'Davil J.'),(9,'Rob'),(9,'Williams'),(10,'Deiteil'),(11,'Sharma'),(12,'Barney Stinson'),(13,'Puri'),(14,'Manna'),(15,'Jindal U.C'),(16,'Prasad'),(17,'Aravind Alex'),(17,'Haldar Sibsankar'),(18,'Sandhu'),(18,'Singh');
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book` (
  `BookId` int(10) NOT NULL AUTO_INCREMENT,
  `Title` varchar(50) DEFAULT NULL,
  `Publisher` varchar(50) DEFAULT NULL,
  `Year` varchar(50) DEFAULT NULL,
  `Availability` int(5) DEFAULT NULL,
  PRIMARY KEY (`BookId`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
INSERT INTO `book` VALUES (1,'OS','PEARSON','2006',0),(2,'DBMS','TARGET67','2010',0),(3,'TOC','NITC','2018',4),(4,'TOC','NITC','2018',1),(5,'DAA','y','2014',0),(6,'DSA','X','2010',10),(7,'Discrete Structures','Pearson','2010',10),(8,'Database Processing','Prentice Hall','2013',12),(9,'Computer System Architecture','Prentice Hall','2015',4),(10,'C: How to program','Prentice Hall','2009',3),(11,'Atomic and Nuclear Systems','Pearson India ','2017',13),(12,'The PlayBook','Stinson','2010',12),(13,'General Theory of Relativity','Pearson India ','2012',5),(14,'Heat and Thermodynamics','Pearson','2013',9),(15,'Machine Design','Pearson India ','2012',5),(16,'Nuclear Physics','Pearson India ','1998',7),(17,'Operating System','Pearson India ','1990',7),(18,'Theory of Machines','Pearson','1992',12);
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `M_Id` int(10) NOT NULL AUTO_INCREMENT,
  `RollNo` varchar(50) DEFAULT NULL,
  `Msg` varchar(255) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL,
  PRIMARY KEY (`M_Id`),
  KEY `RollNo` (`RollNo`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` VALUES (1,'b160111cs','Your request for BookId: 1  has been accepted','2018-10-15','23:47:40'),(2,'B160158CS','Your request for BookId: 1  has been accepted','2018-10-15','23:47:50'),(3,'B160158CS','Your request for BookId: 2  has been rejected','2018-10-15','23:47:58'),(4,'b160632cs','Your request for BookId: 3  has been accepted','2018-10-16','16:54:29'),(5,'b160511cs','Your request for BookId: 2  has been accepted','2018-10-16','16:54:58'),(6,'b160511cs','Your request for BookId: 6  has been accepted','2018-10-16','21:56:11'),(7,'b160854cs','Your request for BookId: 6 has been accepted','2018-10-16','22:11:12'),(8,'B160158CS','Your request for renewal of BookId: 1  has been accepted','2018-10-16','22:43:24'),(9,'b160511cs','Your request for return of BookId: 6  has been accepted','2018-10-16','22:44:24'),(10,'B160158CS','Your request for renewal of BookId: 1  has been accepted','2018-10-16','23:05:12'),(11,'B160158CS','Your request for renewal of BookId: 1  has been accepted','2018-10-16','23:09:51'),(12,'b160511cs','Your request for return of BookId: 6  has been accepted','2018-10-16','23:10:27'),(13,'b160511cs','Your request for return of BookId: 2  has been accepted','2018-10-16','23:36:10'),(14,'b160511cs','Your request for issue of BookId: 1  has been rejected','2018-10-16','23:36:20'),(15,'B160158CS','Your request for issue of BookId: 3  has been rejected','2018-10-16','23:43:37'),(16,'B160158CS','Your request for issue of BookId: 6  has been rejected','2018-10-16','23:43:42'),(17,'B160158CS','Your request for issue of BookId: 2  has been accepted','2018-10-16','23:47:31'),(18,'b160632cs','Your request for issue of BookId: 2  has been rejected','2018-10-16','23:47:34'),(19,'B160632CS','Your request for issue of BookId: 7  has been rejected','2018-10-25','23:25:25'),(20,'B160632CS','Your request for issue of BookId: 15  has been accepted','2018-10-25','23:25:27'),(21,'B160632CS','Your request for renewal of BookId: 3  has been accepted','2018-10-25','23:25:44'),(22,'b160632cs','Your request for return of BookId: 3  has been accepted','2018-10-25','23:25:48'),(23,'b160003ch','Your request for issue of BookId: 9  has been accepted','2018-10-25','23:27:46'),(24,'b160011ch','Your request for issue of BookId: 10  has been accepted','2018-10-25','23:27:49'),(25,'b160011ch','Your request for issue of BookId: 17  has been accepted','2018-10-25','23:27:53'),(26,'b160001cs','Your request for issue of BookId: 11  has been accepted','2018-10-25','23:27:57'),(27,'b160001cs','Your request for issue of BookId: 9  has been accepted','2018-10-25','23:30:41'),(28,'b160158cs','Your request for issue of BookId: 9  has been accepted','2018-10-25','23:30:43'),(29,'b160511cs','Your request for issue of BookId: 10  has been accepted','2018-10-25','23:30:46'),(30,'b160158cs','Your request for issue of BookId: 18  has been accepted','2018-10-25','23:30:49'),(31,'b160511cs','Your request for issue of BookId: 11  has been accepted','2018-10-25','23:30:58'),(32,'b160511cs','Your request for issue of BookId: 13  has been accepted','2018-10-25','23:31:01'),(33,'b160003ch','Your request for issue of BookId: 15  has been rejected','2018-10-26','03:04:51');
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recommendations`
--

DROP TABLE IF EXISTS `recommendations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recommendations` (
  `R_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Book_Name` varchar(50) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `RollNo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`R_ID`),
  KEY `RollNo` (`RollNo`),
  CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recommendations`
--

LOCK TABLES `recommendations` WRITE;
/*!40000 ALTER TABLE `recommendations` DISABLE KEYS */;
INSERT INTO `recommendations` VALUES (2,'Book1','Descp1','B160158CS'),(3,'Book2','Descp2','B160158CS'),(5,'Operating System','An operating system (OS) is system software that manages computer hardware and software resources and provides common services for computer programs.','b160001cs'),(7,'Networks ','A computer network, or data network, is a digital telecommunications network which allows nodes to share resources. In computer networks, computing devices exchange data with each other using connections (data links) between nodes.','b160999cs'),(8,'String Theory','In physics, string theory is a theoretical framework in which the point-like particles of particle physics are replaced by one-dimensional objects called strings. It describes how these strings propagate through space and interact with each other.','b160777cs'),(9,'The Theory of Everything','The Theory of Everything','b160777cs');
/*!40000 ALTER TABLE `recommendations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `record`
--

DROP TABLE IF EXISTS `record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `record` (
  `RollNo` varchar(50) NOT NULL,
  `BookId` int(10) NOT NULL,
  `Date_of_Issue` date DEFAULT NULL,
  `Due_Date` date DEFAULT NULL,
  `Date_of_Return` date DEFAULT NULL,
  `Dues` int(10) DEFAULT NULL,
  `Renewals_left` int(10) DEFAULT NULL,
  `Time` time DEFAULT NULL,
  PRIMARY KEY (`RollNo`,`BookId`),
  KEY `BookId` (`BookId`),
  CONSTRAINT `record_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`),
  CONSTRAINT `record_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `record`
--

LOCK TABLES `record` WRITE;
/*!40000 ALTER TABLE `record` DISABLE KEYS */;
INSERT INTO `record` VALUES ('b160001cs',3,NULL,NULL,NULL,NULL,NULL,'13:18:12'),('b160001cs',9,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160001cs',11,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160001cs',12,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160003ch',9,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160003ch',14,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160011ch',10,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160011ch',17,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160111cs',1,'2018-10-15','2018-12-14',NULL,NULL,1,'13:14:20'),('B160158CS',1,'2018-10-15','2020-04-12',NULL,NULL,0,'13:14:20'),('B160158CS',2,'2018-10-16','2018-12-15',NULL,NULL,1,'13:14:20'),('b160158cs',3,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160158cs',4,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160158cs',7,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160158cs',9,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160158cs',17,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160158cs',18,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160511cs',2,'2018-10-16','2018-12-15','2018-10-16',-60,1,'13:14:20'),('b160511cs',6,'2018-10-16','2018-12-15','2018-10-16',-60,1,'13:14:20'),('b160511cs',7,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160511cs',10,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160511cs',11,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160511cs',13,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('b160511cs',17,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160511cs',18,NULL,NULL,NULL,NULL,NULL,'13:14:20'),('b160632cs',3,'2018-07-16','2018-11-14','2018-10-25',-20,0,'13:14:20'),('B160632CS',15,'2018-10-25','2018-12-24',NULL,NULL,1,'13:14:20'),('B160632CS',17,NULL,NULL,NULL,NULL,NULL,'13:17:31'),('b160854cs',6,'2018-10-16','2019-04-14',NULL,NULL,1,'13:14:20');
/*!40000 ALTER TABLE `record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `renew`
--

DROP TABLE IF EXISTS `renew`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renew` (
  `RollNo` varchar(50) NOT NULL,
  `BookId` int(10) NOT NULL,
  PRIMARY KEY (`RollNo`,`BookId`),
  KEY `BookId` (`BookId`),
  CONSTRAINT `renew_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`),
  CONSTRAINT `renew_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `renew`
--

LOCK TABLES `renew` WRITE;
/*!40000 ALTER TABLE `renew` DISABLE KEYS */;
INSERT INTO `renew` VALUES ('b160001cs',11),('b160158cs',2),('b160158cs',9),('b160158cs',18),('b160511cs',11);
/*!40000 ALTER TABLE `renew` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return`
--

DROP TABLE IF EXISTS `return`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return` (
  `RollNo` varchar(50) NOT NULL,
  `BookId` int(10) NOT NULL,
  PRIMARY KEY (`RollNo`,`BookId`),
  KEY `BookId` (`BookId`),
  CONSTRAINT `return_ibfk_1` FOREIGN KEY (`RollNo`) REFERENCES `user` (`RollNo`),
  CONSTRAINT `return_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return`
--

LOCK TABLES `return` WRITE;
/*!40000 ALTER TABLE `return` DISABLE KEYS */;
INSERT INTO `return` VALUES ('b160003ch',9),('b160158cs',1),('b160158cs',18),('b160511cs',10),('b160511cs',13);
/*!40000 ALTER TABLE `return` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `RollNo` varchar(50) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `EmailId` varchar(50) DEFAULT NULL,
  `MobNo` bigint(11) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`RollNo`),
  UNIQUE KEY `EmailId` (`EmailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('ADMIN','admin','Admin',NULL,'admin@nitc.ac.in',123456789,'admin'),('b160001cs','John','Student','GEN','john@gmail.com',9876543210,'b160001cs'),('b160002cs','Adam','Student','ST','adam@yahoo.com',7845965212,'b160002cs'),('b160003ch','Alice','Student','OBC','alice@hotmail.com',4512451245,'b160003ch'),('b160004me','Abbot','Student','GEN','abbot@gmail.com',6352416352,'b160004me'),('b160005ce','bale','Student','SC','bale@gmail.com',96685747485,'b160005ce'),('b160006cs','Bob','Student','GEN','bob@gmail.com',4141415263,'b160006cs'),('b160007cs','Goku','Student','GEN','goku@yahoo.com',4545451212,'b160007cs'),('b160008cs','Ben','Student','GEN','ben10@hotmail.com',6352416345,'b160008cs'),('b160009cs','Ash','Student','GEN','ash@yahoo.com',7845124578,'b160009cs'),('b160010cs','Harry','Student','GEN','harry@hotmail.com',4578457845,'b160010cs'),('b160011ch','Gwen','Student','GEN','gwen@yahoo.com',9685747474,'b160011ch'),('b160012me','Kevin','Student','ST','kevin11@hotmail.com',1242425163,'b160012me'),('b160013ee','Max','Student','OBC','max@gmail.com',9685748574,'b160013ee'),('B160111Cs','Bharat','Student','GEN','bharat@gmail.com',123456,'abcd'),('B160158CS','MANU','Student','GEN','manu@gmail.com',8365917597,'manu'),('b160257me','Eve','Student','GEN','eve@gmail.com',5451525356,'b160257me'),('b160321ec','Joey','Student','SC','joey@yahoo.com',9898982020,'b160321ec'),('b160412cs','Barney','Student','ST','legendary@gmail.com',9695989192,'b160412cs'),('b160423cs','Rachel','Student','GEN','rachel@gmail.com',7475787671,'b160423cs'),('B160511CS','MALHAR','Student','OBC','malhar@gmail.com',9756153859,'abcd'),('B160632CS','KENIL','Student','GEN','kenilshah081198@gmail.com',9892506094,'1234'),('b160777cs','Sheldon','Student','GEN','smartestpersoninroom@gmail.com',9696969696,'b160777cs'),('b160854cs','Ram Prabhu','Student','SC','ram@nitc.ac.in',968599,'1234'),('b160951cs','Misty','Student','SC','watermaster@hotmail.com',4145424847,'b160951cs'),('b160999cs','Chandler','Student','OBC','sarcasticlord@hotmail.com',9494959694,'b160999cs');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 13:37:52
