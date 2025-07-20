-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: rnt_training_db
-- ------------------------------------------------------
-- Server version 	10.4.32-MariaDB
-- Date: Tue, 07 Jan 2025 11:28:33 +0100

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
-- Table structure for table `countries`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `countries` VALUES (1,'AF','Afghanistan'),(2,'AL','Albania'),(3,'DZ','Algeria'),(4,'DS','American Samoa'),(5,'AD','Andorra'),(6,'AO','Angola'),(7,'AI','Anguilla'),(8,'AQ','Antarctica'),(9,'AG','Antigua and Barbuda'),(10,'AR','Argentina'),(11,'AM','Armenia'),(12,'AW','Aruba'),(13,'AU','Australia'),(14,'AT','Austria'),(15,'AZ','Azerbaijan'),(16,'BS','Bahamas'),(17,'BH','Bahrain'),(18,'BD','Bangladesh'),(19,'BB','Barbados'),(20,'BY','Belarus'),(21,'BE','Belgium'),(22,'BZ','Belize'),(23,'BJ','Benin'),(24,'BM','Bermuda'),(25,'BT','Bhutan'),(26,'BO','Bolivia'),(27,'BA','Bosnia and Herzegovina'),(28,'BW','Botswana'),(29,'BV','Bouvet Island'),(30,'BR','Brazil'),(31,'IO','British Indian Ocean Territory'),(32,'BN','Brunei Darussalam'),(33,'BG','Bulgaria'),(34,'BF','Burkina Faso'),(35,'BI','Burundi'),(36,'KH','Cambodia'),(37,'CM','Cameroon'),(38,'CA','Canada'),(39,'CV','Cape Verde'),(40,'KY','Cayman Islands'),(41,'CF','Central African Republic'),(42,'TD','Chad'),(43,'CL','Chile'),(44,'CN','China'),(45,'CX','Christmas Island'),(46,'CC','Cocos (Keeling) Islands'),(47,'CO','Colombia'),(48,'KM','Comoros'),(49,'CG','Congo'),(50,'CK','Cook Islands'),(51,'CR','Costa Rica'),(52,'HR','Croatia (Hrvatska)'),(53,'CU','Cuba'),(54,'CY','Cyprus'),(55,'CZ','Czech Republic'),(56,'DK','Denmark'),(57,'DJ','Djibouti'),(58,'DM','Dominica'),(59,'DO','Dominican Republic'),(60,'TP','East Timor'),(61,'EC','Ecuador'),(62,'EG','Egypt'),(63,'SV','El Salvador'),(64,'GQ','Equatorial Guinea'),(65,'ER','Eritrea'),(66,'EE','Estonia'),(67,'ET','Ethiopia'),(68,'FK','Falkland Islands (Malvinas)'),(69,'FO','Faroe Islands'),(70,'FJ','Fiji'),(71,'FI','Finland'),(72,'FR','France'),(73,'FX','France, Metropolitan'),(74,'GF','French Guiana'),(75,'PF','French Polynesia'),(76,'TF','French Southern Territories'),(77,'GA','Gabon'),(78,'GM','Gambia'),(79,'GE','Georgia'),(80,'DE','Germany'),(81,'GH','Ghana'),(82,'GI','Gibraltar'),(83,'GK','Guernsey'),(84,'GR','Greece'),(85,'GL','Greenland'),(86,'GD','Grenada'),(87,'GP','Guadeloupe'),(88,'GU','Guam'),(89,'GT','Guatemala'),(90,'GN','Guinea'),(91,'GW','Guinea-Bissau'),(92,'GY','Guyana'),(93,'HT','Haiti'),(94,'HM','Heard and Mc Donald Islands'),(95,'HN','Honduras'),(96,'HK','Hong Kong'),(97,'HU','Hungary'),(98,'IS','Iceland'),(99,'IN','India'),(100,'IM','Isle of Man'),(101,'ID','Indonesia'),(102,'IR','Iran (Islamic Republic of)'),(103,'IQ','Iraq'),(104,'IE','Ireland'),(105,'IL','Israel'),(106,'IT','Italy'),(107,'CI','Ivory Coast'),(108,'JE','Jersey'),(109,'JM','Jamaica'),(110,'JP','Japan'),(111,'JO','Jordan'),(112,'KZ','Kazakhstan'),(113,'KE','Kenya'),(114,'KI','Kiribati'),(115,'KP','Korea, Democratic People\'s Republic of'),(116,'KR','Korea, Republic of'),(117,'XK','Kosovo'),(118,'KW','Kuwait'),(119,'KG','Kyrgyzstan'),(120,'LA','Lao People\'s Democratic Republic'),(121,'LV','Latvia'),(122,'LB','Lebanon'),(123,'LS','Lesotho'),(124,'LR','Liberia'),(125,'LY','Libyan Arab Jamahiriya'),(126,'LI','Liechtenstein'),(127,'LT','Lithuania'),(128,'LU','Luxembourg'),(129,'MO','Macau'),(130,'MK','Macedonia'),(131,'MG','Madagascar'),(132,'MW','Malawi'),(133,'MY','Malaysia'),(134,'MV','Maldives'),(135,'ML','Mali'),(136,'MT','Malta'),(137,'MH','Marshall Islands'),(138,'MQ','Martinique'),(139,'MR','Mauritania'),(140,'MU','Mauritius'),(141,'TY','Mayotte'),(142,'MX','Mexico'),(143,'FM','Micronesia, Federated States of'),(144,'MD','Moldova, Republic of'),(145,'MC','Monaco'),(146,'MN','Mongolia'),(147,'ME','Montenegro'),(148,'MS','Montserrat'),(149,'MA','Morocco'),(150,'MZ','Mozambique'),(151,'MM','Myanmar'),(152,'NA','Namibia'),(153,'NR','Nauru'),(154,'NP','Nepal'),(155,'NL','Netherlands'),(156,'AN','Netherlands Antilles'),(157,'NC','New Caledonia'),(158,'NZ','New Zealand'),(159,'NI','Nicaragua'),(160,'NE','Niger'),(161,'NG','Nigeria'),(162,'NU','Niue'),(163,'NF','Norfolk Island'),(164,'MP','Northern Mariana Islands'),(165,'NO','Norway'),(166,'OM','Oman'),(167,'PK','Pakistan'),(168,'PW','Palau'),(169,'PS','Palestine'),(170,'PA','Panama'),(171,'PG','Papua New Guinea'),(172,'PY','Paraguay'),(173,'PE','Peru'),(174,'PH','Philippines'),(175,'PN','Pitcairn'),(176,'PL','Poland'),(177,'PT','Portugal'),(178,'PR','Puerto Rico'),(179,'QA','Qatar'),(180,'RE','Reunion'),(181,'RO','Romania'),(182,'RU','Russian Federation'),(183,'RW','Rwanda'),(184,'KN','Saint Kitts and Nevis'),(185,'LC','Saint Lucia'),(186,'VC','Saint Vincent and the Grenadines'),(187,'WS','Samoa'),(188,'SM','San Marino'),(189,'ST','Sao Tome and Principe'),(190,'SA','Saudi Arabia'),(191,'SN','Senegal'),(192,'RS','Serbia'),(193,'SC','Seychelles'),(194,'SL','Sierra Leone'),(195,'SG','Singapore'),(196,'SK','Slovakia'),(197,'SI','Slovenia'),(198,'SB','Solomon Islands'),(199,'SO','Somalia'),(200,'ZA','South Africa'),(201,'GS','South Georgia South Sandwich Islands'),(202,'ES','Spain'),(203,'LK','Sri Lanka'),(204,'SH','St. Helena'),(205,'PM','St. Pierre and Miquelon'),(206,'SD','Sudan'),(207,'SR','Suriname'),(208,'SJ','Svalbard and Jan Mayen Islands'),(209,'SZ','Swaziland'),(210,'SE','Sweden'),(211,'CH','Switzerland'),(212,'SY','Syrian Arab Republic'),(213,'TW','Taiwan'),(214,'TJ','Tajikistan'),(215,'TZ','Tanzania, United Republic of'),(216,'TH','Thailand'),(217,'TG','Togo'),(218,'TK','Tokelau'),(219,'TO','Tonga'),(220,'TT','Trinidad and Tobago'),(221,'TN','Tunisia'),(222,'TR','Turkey'),(223,'TM','Turkmenistan'),(224,'TC','Turks and Caicos Islands'),(225,'TV','Tuvalu'),(226,'UG','Uganda'),(227,'UA','Ukraine'),(228,'AE','United Arab Emirates'),(229,'GB','United Kingdom'),(230,'US','United States'),(231,'UM','United States minor outlying islands'),(232,'UY','Uruguay'),(233,'UZ','Uzbekistan'),(234,'VU','Vanuatu'),(235,'VA','Vatican City State'),(236,'VE','Venezuela'),(237,'VN','Vietnam'),(238,'VG','Virgin Islands (British)'),(239,'VI','Virgin Islands (U.S.)'),(240,'WF','Wallis and Futuna Islands'),(241,'EH','Western Sahara'),(242,'YE','Yemen'),(243,'ZR','Zaire'),(244,'ZM','Zambia'),(245,'ZW','Zimbabwe');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `countries` with 245 row(s)
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
INSERT INTO `designation` VALUES (1,'Assistant Engineer'),(2,'Executive Engineer'),(3,'Deputy Chief Engineer'),(4,'Additional Chief Engineer'),(5,'General Manager'),(6,'Department Head'),(7,'Divisional Head'),(8,'Assistant Chemist'),(9,'Chemist'),(10,'Sr. System Analyst'),(11,'Deputy Manager'),(12,'Manager'),(13,'Deputy General Manager'),(14,'Additional Chief Accountant'),(15,'Assistant Programmer'),(16,'Programmer'),(17,'Chairman (Grade-1)'),(18,'Director'),(19,'Additional Chief Chemist'),(20,'Additional Chief Manager'),(21,'Accounts Officer'),(22,'Chief Engineer'),(23,'Assistant Accounts Officer'),(24,'Assistant Admin Officer'),(25,'Assistant Com. Officer'),(26,'Assistant Manager (Admin)'),(28,'Assistant Technical Officer'),(29,'Assistant Operation Officer'),(30,'Operation Officer'),(31,'Technical Officer'),(32,'System Analyst'),(33,'Managing Director'),(34,'Executive Director'),(35,'Chief of Personnel'),(36,'Controller of Accounts'),(37,'Senior General Manager'),(38,'Deputy Chief Accountant'),(39,'Medical officer'),(40,'Senior Medical Officer'),(41,'Chief Medical Officer'),(42,'Chief Finance Officer'),(43,'Chief Auditor'),(44,'Project Director'),(45,'Addl. Chief Medical Officer'),(46,'D.C.O.P'),(47,'Principle'),(48,'Deputy Chief Medical Officer'),(49,'Deputy Chief Auditors'),(50,'D.C.F.O'),(51,'A.C.O.P'),(52,'Assistant Professor'),(53,'Senior Librarian'),(54,'Head Master'),(55,'Senior Technical Officer'),(56,'Assistant Chief Accountant'),(57,'A.C.F.O'),(58,'Assistant Chief Auditor'),(59,'Computer Operator'),(60,'Data Entry Operator'),(61,'Senior Officer ICT'),(62,'Record Sorter'),(63,'Sub Assistant Engineer'),(64,'Managing Director (Addl.C.)'),(65,'Managing Director (C.C.)'),(66,'Officer'),(67,'Production Officer'),(68,'Assistant Principle Officer'),(69,'Executive'),(70,'Sub Assistant Chemist'),(73,'Senior Officer'),(74,'Trainee Engineer'),(75,'Generator Operator'),(76,'Dev.Exect.Trans'),(77,'Assistant Instructor'),(78,'Junior Instructor'),(79,'Instructor'),(80,'Junior Officer'),(81,'Production Shift Incharge'),(82,'Production Senior Officer'),(83,'Production Engineer'),(84,'Data Protection Officer'),(85,'Junior Engineer'),(86,'Project Technology'),(87,'Junior Assistant Engineer'),(88,'Senior Electrician'),(89,'Security Officer'),(90,'Trainee Officer'),(91,'Machinery Fitter'),(92,'Assistant Security Officer'),(93,'Forest Officer'),(94,'Teacher'),(95,'Engineer'),(96,'LDA Cum-Typist'),(97,'Senior Clark'),(98,'Cashier'),(99,'Assistant Headmaster'),(100,'Assistant Store Keper'),(101,'Store Keper'),(102,'Assistant Teacher'),(103,'Librerian'),(104,'Assistant Teacher'),(105,'Senior Lecturer'),(106,'Skilled Operator (S.O.)-2'),(107,'Skilled Operator (S.O.)-1'),(108,'High Skilled Operator (HSO)'),(109,'Master Operator (MO)'),(110,'Sub-Assistant Technical Officer'),(111,'Assistant Medical Officer'),(112,'Assistant Transport Officer'),(113,'Assistant Coordination Officer'),(114,'Assistant Personnel Officer'),(115,'Personal Officer'),(116,'Deputy Chief Chemist'),(117,'Lecturer'),(118,'Administrative Officer'),(119,'Assistant Marketing Officer'),(120,'Master Technician'),(121,'Process Operator'),(123,'High Skilled Technician (HST)'),(124,'Store Officer'),(125,'Junior Clark'),(126,'Modeller'),(127,'Assistant Modeller'),(128,'Audit Officer'),(129,'Stenographer'),(130,'Telephone Operator'),(131,'Semi Skilled Operator (SSO)'),(132,'Semi Skilled Technician (SST)'),(133,'Skilled Technician (ST)'),(134,'Assistant Cashier'),(135,'Accounts Assistant'),(136,'Supervisor'),(137,'A.C.I.O'),(138,'Additional Chief Auditor'),(139,'Junior Programmer'),(140,'Security Guard'),(141,'MLSS'),(142,'Office Assistant'),(143,'STG.CUM. COMPUTER OPERATOR'),(144,'Driver'),(145,'Demonstrator'),(146,'IMAM'),(148,'Electricina'),(149,'Purchase Assistant'),(150,'Mechanic'),(151,'Marketing Assistant'),(152,'ST. CUM COMPUTER OPERATOR');
/*!40000 ALTER TABLE `designation` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `designation` with 147 row(s)
--

--
-- Table structure for table `employees`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `place_of_posting` varchar(255) DEFAULT NULL,
  `ref_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `employees` with 0 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `log_table` VALUES (1,'user','','user','::1','2025-01-06 14:49:50',28728,'0000-00-00 00:00:00'),(2,'user','','user','::1','2025-01-06 14:52:58',67449,'0000-00-00 00:00:00'),(3,'user','','user','::1','2025-01-06 14:53:30',49138,'0000-00-00 00:00:00'),(4,'user','','user','::1','2025-01-06 14:53:38',21100,'0000-00-00 00:00:00'),(5,'user','','user','::1','2025-01-06 14:54:17',20331,'0000-00-00 00:00:00'),(6,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 14:59:36',79522,'0000-00-00 00:00:00'),(7,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:25:00',22454,'0000-00-00 00:00:00'),(8,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:25:28',91091,'0000-00-00 00:00:00'),(9,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:25:55',96309,'0000-00-00 00:00:00'),(10,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:26:15',77806,'0000-00-00 00:00:00'),(11,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:50:43',93851,'0000-00-00 00:00:00'),(12,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 15:53:42',76674,'0000-00-00 00:00:00'),(13,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:01:11',77603,'0000-00-00 00:00:00'),(14,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:01:51',97765,'0000-00-00 00:00:00'),(15,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:02:02',15570,'0000-00-00 00:00:00'),(16,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:02:32',26785,'0000-00-00 00:00:00'),(17,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:03:03',61281,'0000-00-00 00:00:00'),(18,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:03:51',93973,'0000-00-00 00:00:00'),(19,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:04:38',95677,'0000-00-00 00:00:00'),(20,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:11:41',47811,'0000-00-00 00:00:00'),(21,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:12:17',56998,'0000-00-00 00:00:00'),(22,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:13:51',63313,'0000-00-00 00:00:00'),(23,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:14:57',10723,'0000-00-00 00:00:00'),(24,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:15:07',59710,'0000-00-00 00:00:00'),(25,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:17:03',31215,'0000-00-00 00:00:00'),(26,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:17:27',73207,'0000-00-00 00:00:00'),(27,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:17:43',19113,'0000-00-00 00:00:00'),(28,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:18:38',90477,'0000-00-00 00:00:00'),(29,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:19:43',43986,'0000-00-00 00:00:00'),(30,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:19:52',36671,'0000-00-00 00:00:00'),(31,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:20:07',58888,'0000-00-00 00:00:00'),(32,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:20:58',32747,'0000-00-00 00:00:00'),(33,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:21:21',41402,'0000-00-00 00:00:00'),(34,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:30:12',38714,'0000-00-00 00:00:00'),(35,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:31:07',73591,'0000-00-00 00:00:00'),(36,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:31:44',60576,'0000-00-00 00:00:00'),(37,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:33:46',78342,'0000-00-00 00:00:00'),(38,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','127.0.0.1','2025-01-06 16:35:59',98276,'0000-00-00 00:00:00'),(39,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-06 16:36:56',78911,'0000-00-00 00:00:00'),(40,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:17:08',17962,'0000-00-00 00:00:00'),(41,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:27:56',89400,'0000-00-00 00:00:00'),(42,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:28:23',57951,'0000-00-00 00:00:00'),(43,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:41:14',82359,'0000-00-00 00:00:00'),(44,'user','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 09:42:26',49531,'0000-00-00 00:00:00'),(45,'user','ef62465e68ec3a10bb03b3c3a10a885e57116df6','user','127.0.0.1','2025-01-07 09:45:00',74480,'0000-00-00 00:00:00'),(46,'rnt','ef62465e68ec3a10bb03b3c3a10a885e57116df6','user','127.0.0.1','2025-01-07 09:45:18',86248,'0000-00-00 00:00:00'),(47,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 11:21:06',62662,'0000-00-00 00:00:00'),(48,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 11:40:16',71390,'0000-00-00 00:00:00'),(49,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','127.0.0.1','2025-01-07 15:24:34',38839,'0000-00-00 00:00:00'),(50,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','127.0.0.1','2025-01-07 15:26:12',90884,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `log_table` with 50 row(s)
--

--
-- Table structure for table `office_order`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `order_attachment` varchar(255) NOT NULL,
  `training_type` varchar(255) NOT NULL,
  `training_title` varchar(255) NOT NULL,
  `t_institute` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_order`
--

LOCK TABLES `office_order` WRITE;
/*!40000 ALTER TABLE `office_order` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `office_order` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `office_order` with 0 row(s)
--

--
-- Table structure for table `place_of_posting`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `place_of_posting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_of_posting` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `place_of_posting`
--

LOCK TABLES `place_of_posting` WRITE;
/*!40000 ALTER TABLE `place_of_posting` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `place_of_posting` VALUES (1,'BCIC H.O'),(2,'TICI'),(3,'SFCL'),(4,'JFCL'),(5,'CUFL'),(6,'AFCCL'),(7,'DAPFCL'),(8,'TSPCL'),(9,'GPFPLC'),(10,'GPUFP'),(11,'CCCL'),(12,'CCC'),(13,'BISFL'),(14,'KHBML'),(15,'KPML'),(16,'DLCL'),(17,'Chemical Godown'),(18,'13 Buffer Godown Project'),(19,'34 Buffer Godown Project'),(20,'CCCL Project'),(21,'BCIC College'),(22,'UFFL'),(23,'PUFFL'),(24,'ZFCL'),(25,'NGFF'),(26,'NBPM'),(27,'UGSFL'),(28,'SPPM'),(29,'KNML'),(30,'KHBML/KNML'),(31,'Branch Office, Chittagong'),(32,'Magura Paper Mill'),(33,'SFP'),(34,'KRC'),(35,'MoInd'),(36,'CHTK/CMT'),(37,'KCCL'),(38,'UMF'),(39,'LIRA');
/*!40000 ALTER TABLE `place_of_posting` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `place_of_posting` with 39 row(s)
--

--
-- Table structure for table `training_list`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_list`
--

LOCK TABLES `training_list` WRITE;
/*!40000 ALTER TABLE `training_list` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `training_list` VALUES (1,'PPR1'),(2,'PPR2'),(3,'Right to Information');
/*!40000 ALTER TABLE `training_list` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `training_list` with 3 row(s)
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
INSERT INTO `users` VALUES (1,'chairman','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','buffer','Chairman Secretariat','','2024-09-26 00:07:13'),(2,'dir_com','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','Director (Commercial)','','2024-08-19 02:12:04'),(3,'afccl','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','factory_office','','','2024-06-01 11:19:24'),(4,'sadmin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','sadmin','bcic_hq','ICT Division','','2024-08-13 22:01:26'),(12,'bcic_mkt','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','bcic_hq','','user@yahoo.com','2024-06-01 00:48:45'),(14,'admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','bcic_hq','','admin@yahoo.com','2024-05-25 08:48:37'),(15,'mongla_port','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','port_office','','monglaport@yahoo.com','2024-05-31 12:39:31'),(17,'emd','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','EMD','','2024-12-01 05:22:38'),(20,'ict','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Chairman Secretariat','emrantareq09@gmail.com','2024-08-18 02:56:30'),(21,'admin2','40bd001563085fc35165329ea1ff5c5ecbdbbeef','admin','','CSD','emrantareq09@gmail.com','2024-08-18 04:25:51'),(22,'dir_fin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','user','','Director (Finance)','','2024-09-18 04:43:49'),(23,'rnt','ef62465e68ec3a10bb03b3c3a10a885e57116df6','user','','RNT Department','','2025-01-07 03:45:08');
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

-- Dump completed on: Tue, 07 Jan 2025 11:28:33 +0100
