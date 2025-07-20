-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: pmis_db
-- ------------------------------------------------------
-- Server version 	10.4.24-MariaDB
-- Date: Tue, 06 Feb 2024 10:36:38 +0100

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
-- Table structure for table `award`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_id` int(11) NOT NULL,
  `award_or_penalty` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `award`
--

LOCK TABLES `award` WRITE;
/*!40000 ALTER TABLE `award` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `award` VALUES (1,1,'Special Increment'),(2,1,'Special Promotion'),(3,1,'Cash Award'),(4,1,'Commendation Certificate'),(5,1,'Appreciation Letter\r\n'),(6,1,'Innovation'),(7,1,'NIS'),(8,2,'Temporary Suspensions'),(9,2,'Increment Held-up'),(10,2,'Demotion'),(11,2,'Warning'),(12,2,'Show cause');
/*!40000 ALTER TABLE `award` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `award` with 12 row(s)
--

--
-- Table structure for table `award_and_penalty`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `award_and_penalty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `given_date` date NOT NULL,
  `status` enum('Award','Penalty','','') NOT NULL,
  `nature` enum('Special Increment','Special Promotion','Cash Award','Commendation Certificate','Appreciation Letter','Innovation','NIS','Temporary Suspensions','Increment Held-up','Demotion','Warning','Show cause') NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `ground_or_subject` text NOT NULL,
  `special_increment` int(100) NOT NULL,
  `special_promotion` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `award_and_penalty`
--

LOCK TABLES `award_and_penalty` WRITE;
/*!40000 ALTER TABLE `award_and_penalty` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `award_and_penalty` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `award_and_penalty` with 0 row(s)
--

--
-- Table structure for table `basic`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `basic` varchar(25) NOT NULL,
  `scale_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=320 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basic`
--

LOCK TABLES `basic` WRITE;
/*!40000 ALTER TABLE `basic` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `basic` VALUES (1,'78000',1),(2,'66000',2),(3,'68480',2),(4,'71050',2),(5,'73720',2),(6,'76490',2),(7,'56500',3),(8,'58760',3),(9,'61120',3),(10,'63570',3),(11,'66120',3),(12,'68770',3),(13,'71530',3),(14,'74400',3),(15,'50000',4),(16,'52000',4),(17,'54080',4),(18,'56250',4),(19,'58500',4),(20,'60840',4),(21,'63280',4),(22,'65820',4),(23,'68460',4),(24,'71200',4),(25,'43000',5),(26,'44140',5),(27,'46670',5),(28,'49090',5),(29,'51300',5),(30,'53610',5),(31,'44490',5),(32,'46970',5),(33,'49090',5),(34,'51300',5),(35,'53610',5),(36,'56030',5),(37,'58560',5),(38,'61200',5),(39,'63960',5),(40,'66840',5),(41,'69850',5),(42,'35500',6),(43,'37280',6),(44,'39150',6),(45,'41110',6),(46,'43170',6),(47,'45330',6),(48,'47600',6),(49,'49980',6),(50,'52480',6),(51,'55110',6),(52,'57870',6),(53,'60770',6),(54,'63810',6),(55,'67010',6),(56,'29000',7),(57,'30450',7),(58,'31980',7),(59,'33580',7),(60,'35260',7),(61,'37030',7),(62,'38890',7),(63,'40840',7),(64,'42890',7),(65,'45040',7),(66,'47300',7),(67,'49670',7),(68,'52160',7),(69,'54770',7),(70,'57510',7),(71,'60390',7),(72,'63410',7),(73,'23000',8),(74,'24150',8),(75,'25360',8),(76,'26630',8),(77,'27970',8),(78,'29370',8),(79,'30840',8),(80,'32390',8),(81,'34010',8),(82,'35720',8),(83,'37510',8),(84,'39390',8),(85,'41360',8),(86,'43430',8),(87,'45610',8),(88,'47900',8),(89,'50300',8),(90,'52820',8),(91,'55470',8),(92,'22000',9),(93,'23100',9),(94,'24260',9),(95,'25480',9),(96,'26760',9),(97,'28100',9),(98,'29510',9),(99,'30990',9),(100,'32540',9),(101,'34170',9),(102,'35880',9),(103,'37680',9),(104,'39570',9),(105,'41550',9),(106,'43630',9),(107,'45820',9),(108,'48120',9),(109,'50530',9),(110,'53060',9),(111,'16000',10),(112,'16800',10),(113,'17640',10),(114,'18530',10),(115,'19460',10),(116,'20440',10),(117,'21470',10),(118,'22550',10),(119,'23680',10),(120,'24870',10),(121,'26120',10),(122,'27430',10),(123,'28810',10),(124,'30260',10),(125,'31780',10),(126,'33370',10),(127,'35040',10),(128,'36800',10),(129,'38640',10),(130,'12500',11),(131,'13130',11),(132,'13790',11),(133,'14480',11),(134,'15210',11),(135,'15980',11),(136,'16780',11),(137,'17620',11),(138,'18510',11),(139,'19440',11),(140,'20420',11),(141,'21450',11),(142,'22530',11),(143,'23660',11),(144,'24850',11),(145,'26100',11),(146,'27410',11),(147,'28790',11),(148,'30230',11),(149,'11300',12),(150,'11870',12),(151,'12470',12),(152,'13100',12),(153,'13760',12),(154,'14450',12),(155,'15180',12),(156,'15940',12),(157,'16740',12),(158,'17580',12),(159,'18460',12),(160,'19390',12),(161,'20360',12),(162,'21380',12),(163,'22450',12),(164,'23580',12),(165,'24760',12),(166,'26000',12),(167,'27300',12),(168,'11000',13),(169,'11550',13),(170,'12130',13),(171,'12740',13),(172,'13380',13),(173,'14050',13),(174,'14760',13),(175,'15500',13),(176,'16280',13),(177,'17100',13),(178,'17960',13),(179,'18860',13),(180,'19810',13),(181,'20810',13),(182,'21860',13),(183,'22960',13),(184,'24110',13),(185,'25320',13),(186,'26590',13),(187,'10200',14),(188,'10710',14),(189,'11250',14),(190,'11820',14),(191,'12420',14),(192,'13050',14),(193,'13710',14),(194,'14400',14),(195,'15120',14),(196,'15880',14),(197,'16680',14),(198,'17520',14),(199,'18400',14),(200,'19320',14),(201,'20290',14),(202,'21310',14),(203,'22380',14),(204,'23500',14),(205,'24680',14),(206,'9700',15),(207,'10190',15),(208,'10700',15),(209,'11240',15),(210,'11810',15),(211,'12410',15),(212,'13040',15),(213,'13700',15),(214,'14390',15),(215,'15110',15),(216,'15870',15),(217,'16670',15),(218,'17510',15),(219,'18390',15),(220,'19310',15),(221,'20280',15),(222,'21300',15),(223,'22370',15),(224,'23490',15),(225,'9300',16),(226,'9770',16),(227,'10260',16),(228,'10780',16),(229,'11320',16),(230,'11890',16),(231,'12490',16),(232,'13120',16),(233,'13780',16),(234,'14470',16),(235,'15200',16),(236,'15960',16),(237,'16760',16),(238,'17600',16),(239,'18480',16),(240,'19410',16),(241,'20390',16),(242,'21410',16),(243,'22490',16),(244,'9000',17),(245,'9450',17),(246,'9930',17),(247,'10430',17),(248,'10960',17),(249,'11510',17),(250,'12090',17),(251,'12700',17),(252,'13340',17),(253,'14010',17),(254,'14720',17),(255,'15460',17),(256,'16240',17),(257,'17060',17),(258,'17920',17),(259,'18820',17),(260,'19770',17),(261,'20760',17),(262,'21800',17),(263,'8800',18),(264,'9240',18),(265,'9710',18),(266,'10200',18),(267,'10710',18),(268,'11250',18),(269,'11820',18),(270,'12420',18),(271,'13050',18),(272,'13710',18),(273,'14400',18),(274,'15120',18),(275,'15880',18),(276,'16680',18),(277,'17520',18),(278,'18400',18),(279,'19320',18),(280,'20290',18),(281,'21310',18),(282,'8500',19),(283,'8930',19),(284,'9380',19),(285,'9850',19),(286,'10350',19),(287,'10870',19),(288,'11420',19),(289,'12000',19),(290,'12600',19),(291,'13230',19),(292,'13900',19),(293,'14600',19),(294,'15330',19),(295,'16100',19),(296,'16910',19),(297,'17760',19),(298,'18650',19),(299,'19590',19),(300,'20570',19),(301,'8250',20),(302,'8670',20),(303,'9110',20),(304,'9570',20),(305,'10050',20),(306,'10560',20),(307,'11090',20),(308,'11650',20),(309,'12240',20),(310,'12860',20),(311,'13510',20),(312,'14190',20),(313,'14900',20),(314,'15650',20),(315,'16440',20),(316,'17270',20),(317,'18140',20),(318,'19050',20),(319,'20010',20);
/*!40000 ALTER TABLE `basic` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `basic` with 319 row(s)
--

--
-- Table structure for table `basic_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basic_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `employee_type` enum('Officer','Staff','','') NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `seniority_no` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `name_bn` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(100) NOT NULL,
  `sub_cadre_header` varchar(50) NOT NULL,
  `division` varchar(25) NOT NULL,
  `section` varchar(25) NOT NULL,
  `job_status` enum('In Service','PRL','Suspended','Retired','Dead with Services','LPR') NOT NULL,
  `fathersName` varchar(100) NOT NULL,
  `mothersName` varchar(100) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `blood_group` varchar(15) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `nationality` varchar(25) NOT NULL,
  `nid` varchar(13) NOT NULL,
  `quota` varchar(50) NOT NULL,
  `mobile_no` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `home_dist` varchar(25) NOT NULL,
  `maritial_status` varchar(15) NOT NULL,
  `spouse_name` varchar(100) NOT NULL,
  `spouse_home_dist` varchar(25) NOT NULL,
  `spouse_occupation` varchar(100) NOT NULL,
  `spo_present_add` text NOT NULL,
  `spo_parmanent_add` text DEFAULT NULL,
  `present_add` text NOT NULL,
  `permanent_add` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `sign_img_path` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basic_info`
--

LOCK TABLES `basic_info` WRITE;
/*!40000 ALTER TABLE `basic_info` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `basic_info` VALUES (1,'Officer','6595-3',NULL,'MD. ABUL HOSSAIN','মোঃ আবুল হোসেন','Assistant Programmer','','ICT Division','ICT','In Service','MD. MUNIRUL ISLAM','ROKEYA BEGUM','Male','O+','Islam','Bangladeshi','2849960287','Non Qoutas','01735895935','mahossain.bcic@gmail.com','Chapainawabganj','Unmarried','','','','','','Mugda, Dhaka','Gorbary, Deopura, Gomastapur, Chapainawabganj','../images/5e6c80ex__2031.jpg','',NULL,'2024-02-01 09:24:52'),(2,'Officer','3899-2',NULL,'Mr. Mohammad Zakir Hossain','','Chief of Personnel','','Personnel Division','Chief of Personnel (COP)','In Service','','','','','','','','','01718834659','admin@gmail.com','','','','','','',NULL,'','','','',NULL,'2024-02-04 04:55:48'),(3,'Officer','7734-7',NULL,'KANEJ FATEMA CHOWDHURY','','System Analyst','','ICT Division','ICT','In Service','','','','','','','','','01675703923','tuhinkaniz65@gmail.com','','','','','','',NULL,'','','','',NULL,'2024-02-04 05:02:37'),(4,'Officer','6594-6',NULL,'Shaneworn Bhadra ','','Assistant Programmer','','ICT Division','ICT','In Service','','','','','','','','','01878072812','shanewornbhadra@gmail.com','','','','','','',NULL,'','','../images/cabdbf500 pic.jpg','',NULL,'2024-02-05 03:49:38'),(5,'Officer','8118-2',NULL,'Maharun Nasa Marry','মেহেরুন নেছা মেরী','Assistant Accounts Officer','','ICT Division','ICT','In Service','Mizanur Rahman','Parul Begum','Female','O+','Islam','Bangladeshi','1986121332613','Child of Freedom Fighter','01611661313','maharunmarry@gmail.com','Tangail','Married','Hasan Ferdous','Barguna','Serves holder','House-5/6-9, Mirpur-1, BCIC Housing Colony, Dhaka-1216','Barguna ','House-5/6-9, Mirpur-1, BCIC Housing Colony, Dhaka-1216','Dist: Tangail, Vill: Birchari, P.O: Jahidgonj, U.Z: Ghatail, Dist: Tangail','../images/c63f80Tulips.jpg','',NULL,'2024-02-04 05:42:56'),(6,'Officer','7928-5',NULL,'Refat Ullah','','Operation Officer','','ICT Division','ICT','In Service','','','','','','','','','01718834655','emrantareq09@gmail.com','','','','','','',NULL,'','','../images/76ad2eziraf.jpg','',NULL,'2024-02-04 05:21:27');
/*!40000 ALTER TABLE `basic_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `basic_info` with 6 row(s)
--

--
-- Table structure for table `blood_group`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blood_group` (
  `id` int(11) NOT NULL,
  `group_name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blood_group`
--

LOCK TABLES `blood_group` WRITE;
/*!40000 ALTER TABLE `blood_group` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `blood_group` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `blood_group` with 0 row(s)
--

--
-- Table structure for table `board`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `board` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `board`
--

LOCK TABLES `board` WRITE;
/*!40000 ALTER TABLE `board` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `board` VALUES (1,'Barisal'),(2,'Chittagong'),(3,'Cumilla'),(4,'Dhaka'),(5,'Dinajpur'),(6,'Jessore'),(7,'Mymensingh'),(8,'Rajshahi'),(9,'Sylhet'),(10,'Madrasah'),(11,'Technical (BTEB)');
/*!40000 ALTER TABLE `board` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `board` with 11 row(s)
--

--
-- Table structure for table `cadre`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `cadre` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cadre`
--

LOCK TABLES `cadre` WRITE;
/*!40000 ALTER TABLE `cadre` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `cadre` VALUES (1,0,'Administrative'),(2,0,'Technical'),(3,0,'Finance'),(4,0,'Commercial'),(5,0,'Senior GM'),(6,0,'General Manager'),(7,0,'Accounts');
/*!40000 ALTER TABLE `cadre` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `cadre` with 7 row(s)
--

--
-- Table structure for table `childs_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `childs_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(25) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `class` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `childs_info`
--

LOCK TABLES `childs_info` WRITE;
/*!40000 ALTER TABLE `childs_info` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `childs_info` VALUES (1,0,'8118-2','Raiyan Farabi','2022-01-26','Male','','');
/*!40000 ALTER TABLE `childs_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `childs_info` with 1 row(s)
--

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
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=utf8;
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
-- Table structure for table `department`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `department` VALUES (1,'Administration'),(2,'Accounts'),(3,'Commercial'),(4,'Technical'),(5,'MTS/EIP'),(6,'MTS/Mech'),(7,'Operation');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `department` with 7 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designation`
--

LOCK TABLES `designation` WRITE;
/*!40000 ALTER TABLE `designation` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `designation` VALUES (1,'Assistant Engineer'),(2,'Executive Engineer'),(3,'Deputy Chief Engineer'),(4,'Additional Chief Engineer'),(5,'General Manager'),(6,'Departmental Head'),(7,'Divisional Head'),(8,'Assistant Chemist'),(9,'Chemist'),(10,'Sr. System Analyst'),(11,'Deputy Manager'),(12,'Manager'),(13,'Deputy General Manager'),(14,'Addl. Chief Accountant'),(15,'Assistant Programmer'),(16,'Programmer'),(17,'Chairman (Grade-1)'),(18,'Director'),(19,'Addl. Chief Chemist'),(20,'Addl. Chief Manager'),(21,'Accounts Officer'),(22,'GM(MTS)/Chief Engineer(MTS)'),(23,'Assistant Accounts Officer'),(24,'Assistant Admin Officer'),(25,'Assistant Com. Officer'),(26,'Assistant Manager'),(27,'GM(MTS)/Chief Engineer'),(28,'Assistant Technical Officer'),(29,'Assistant Operation Officer'),(30,'Operation Officer'),(31,'Technical Officer'),(32,'System Analyst'),(33,'Managing Director'),(34,'Executive Director'),(35,'Chief of Personnel'),(36,'Controller of Accounts'),(37,'Senior General Manager'),(38,'Deputy General Manager'),(39,'Medical officer'),(40,'Senior Medical Officer'),(41,'Chief Medical Officer'),(42,'Chief Finance Officer'),(43,'Chief Auditor'),(44,'Project Director'),(45,'Addl. Chief Medical Officer'),(46,'D.C.O.P'),(47,'Principle'),(48,'Dy. Chief Medical Officer'),(49,'Dy. Chief Auditors'),(50,'D.C.F.O'),(51,'A.C.O.P'),(52,'Assistant Professor'),(53,'Senior Librarian'),(54,'Head Master'),(55,'Senior Technical Officer'),(56,'Asstt. Chief Accountant'),(57,'A.C.F.O'),(58,'Asstt. Chief Auditor'),(59,'Computer Operator'),(60,'Data Entry Operator'),(61,'Senior Officer ICT');
/*!40000 ALTER TABLE `designation` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `designation` with 61 row(s)
--

--
-- Table structure for table `diploma_course_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diploma_course_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `type` varchar(25) NOT NULL,
  `title` varchar(255) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `grade` varchar(15) NOT NULL,
  `country` varchar(25) NOT NULL,
  `period` varchar(15) NOT NULL,
  `year` int(15) NOT NULL,
  `month_year` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diploma_course_info`
--

LOCK TABLES `diploma_course_info` WRITE;
/*!40000 ALTER TABLE `diploma_course_info` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `diploma_course_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `diploma_course_info` with 0 row(s)
--

--
-- Table structure for table `disciplinary_action`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disciplinary_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `nat_of_offense` varchar(255) NOT NULL,
  `punishment` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `remarks` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinary_action`
--

LOCK TABLES `disciplinary_action` WRITE;
/*!40000 ALTER TABLE `disciplinary_action` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `disciplinary_action` VALUES (1,8,'5620-4','test','Increment Held-up','2023-06-01',''),(2,8,'5620-4','Punishment Transfer','Punishment Transfer','2023-06-22','');
/*!40000 ALTER TABLE `disciplinary_action` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `disciplinary_action` with 2 row(s)
--

--
-- Table structure for table `district`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `district_name` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `district`
--

LOCK TABLES `district` WRITE;
/*!40000 ALTER TABLE `district` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `district` VALUES (1,'Dhaka'),(2,'Faridpur'),(3,'Gazipur'),(4,'Gopalganj'),(5,'Jamalpur'),(6,'Kishoreganj'),(7,'Madaripur'),(8,'Manikganj'),(9,'Munshiganj'),(10,'Mymensingh'),(11,'Narayanganj'),(12,'Narsingdi'),(13,'Netrokona'),(14,'Rajbari'),(15,'Shariatpur'),(16,'Sherpur'),(17,'Tangail'),(18,'Bogra'),(19,'Joypurhat'),(20,'Naogaon'),(21,'Natore'),(22,'Chapainawabganj'),(23,'Pabna'),(24,'Rajshahi'),(25,'Sirajgonj'),(26,'Dinajpur'),(27,'Gaibandha'),(28,'Kurigram'),(29,'Lalmonirhat'),(30,'Nilphamari'),(31,'Panchagarh'),(32,'Rangpur'),(33,'Thakurgaon'),(34,'Barguna'),(35,'Barisal'),(36,'Bhola'),(37,'Jhalokati'),(38,'Patuakhali'),(39,'Pirojpur'),(40,'Bandarban'),(41,'Brahmanbaria'),(42,'Chandpur'),(43,'Chittagong'),(44,'Comilla'),(45,'CoxsBazar'),(46,'Feni'),(47,'Khagrachari'),(48,'Lakshmipur'),(49,'Noakhali'),(50,'Rangamati'),(51,'Habiganj'),(52,'Maulvibazar'),(53,'Sunamganj'),(54,'Sylhet'),(55,'Bagerhat'),(56,'Chuadanga'),(57,'Jessore'),(58,'Jhenaidah'),(59,'Khulna'),(60,'Kushtia'),(61,'Magura'),(62,'Meherpur'),(63,'Narail'),(64,'Satkhira');
/*!40000 ALTER TABLE `district` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `district` with 64 row(s)
--

--
-- Table structure for table `division`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `division`
--

LOCK TABLES `division` WRITE;
/*!40000 ALTER TABLE `division` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `division` VALUES (1,'Personnel Division'),(2,'Accounts Division'),(3,'Commercial Division'),(4,'Technical Division'),(5,'MTS Division'),(6,'Chairman Secretariat'),(7,'Operation Division'),(8,'PRD'),(9,'PID'),(10,'RPD'),(11,'Marketing Division'),(12,'Audit Division'),(13,'Purchase Division'),(14,'Finance Division'),(15,'MIS Division'),(16,'Director (Com.)'),(17,'Director (Fin.)'),(18,'Director (P&I)'),(19,'Director (T&E)'),(20,'Director (Prod.)'),(21,'ICT Division'),(25,'Director (T&E)'),(26,'Director (P&I)'),(47,'AFCCL'),(48,'SFCL'),(49,'JFCL'),(50,'BISFL'),(51,'CUFL'),(52,'GPUFP'),(53,'GPFPLC'),(54,'DAPFCL'),(55,'TSPCL'),(56,'KPML'),(57,'UGSFL'),(58,'CCCL'),(59,'CCC'),(60,'34 Buffer Project'),(61,'13 Buffer Project'),(62,'UF-85 Project'),(63,'Chemical Godown, Shampur');
/*!40000 ALTER TABLE `division` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `division` with 40 row(s)
--

--
-- Table structure for table `edu_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `ssc_exam` varchar(25) NOT NULL,
  `ssc_group_name` varchar(25) NOT NULL,
  `ssc_inst_name` varchar(200) NOT NULL,
  `ssc_board_or_univ` varchar(50) NOT NULL,
  `ssc_div_or_cgpa` varchar(25) NOT NULL,
  `ssc_cgpa_5` varchar(25) NOT NULL,
  `ssc_passing_year` varchar(25) NOT NULL,
  `hsc_exam` varchar(25) NOT NULL,
  `hsc_group_name` varchar(25) NOT NULL,
  `hsc_inst_name` varchar(200) NOT NULL,
  `hsc_board_or_univ` varchar(25) NOT NULL,
  `hsc_div_or_cgpa` varchar(25) NOT NULL,
  `hsc_cgpa_5` varchar(25) NOT NULL,
  `hsc_passing_year` varchar(25) NOT NULL,
  `honors_exam` varchar(25) NOT NULL,
  `honors_group_name` varchar(25) NOT NULL,
  `honors_groupname_others` varchar(50) NOT NULL,
  `honors_inst_name` varchar(200) NOT NULL,
  `honors_univer_others` varchar(250) NOT NULL,
  `honors_board_or_univ` varchar(250) NOT NULL,
  `honors_board_others` varchar(250) NOT NULL,
  `honors_div_or_cgpa` varchar(25) NOT NULL,
  `honors_cgpa_4` varchar(25) NOT NULL,
  `honors_passing_year` varchar(25) NOT NULL,
  `honors_course_duration` varchar(25) NOT NULL,
  `masters` varchar(25) NOT NULL,
  `ms_group_name` varchar(25) NOT NULL,
  `ms_groupname_others` varchar(200) NOT NULL,
  `ms_inst_name` varchar(100) NOT NULL,
  `ms_univer_others` varchar(250) NOT NULL,
  `ms_board_or_univ` varchar(250) NOT NULL,
  `ms_board_others` varchar(250) NOT NULL,
  `ms_div_or_cgpa` varchar(25) NOT NULL,
  `ms_cgpa_4` varchar(25) NOT NULL,
  `ms_passing_year` varchar(25) NOT NULL,
  `ms_course_duration` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edu_info`
--

LOCK TABLES `edu_info` WRITE;
/*!40000 ALTER TABLE `edu_info` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `edu_info` VALUES (1,0,'6595-3','S.S.C','Science','Rahanpur A. B. Govt. High School, Nawabganj','Rajshahi','CGPA (Out of 5)','5','2009','H.S.C','Science','Shah Mokhdum College, Rajshahi','Rajshahi','CGPA (Out of 5)','5','2011','B.Sc Engineering','Electronics & Communicati','','Khulna University','','Khulna University','','CGPA (Out of 4)','3.21','2016','04 Years','','','','','','','','','','',''),(2,5,'8118-2','S.S.C','Humanities','Zia Sar Karkhana College','Cumilla','CGPA (Out of 5)','2.94','2006','H.S.C','Humanities','Zia Sar Karkhana College','Cumilla','CGPA (Out of 5)','3.10','2008','Pass Course','Social Works','','National University','','National University','','2nd Class','','2011','03 Years','M.S.S','Home Economics','','Others','Eden Mahila College','National University','','2nd Class','','2013','01 Years'),(3,4,'6594-6','S.S.C','Science','Palordi Secondary School','Barisal','CGPA (Out of 5)','5.00','2013','H.S.C','Science','Barthi College,','Barisal','CGPA (Out of 5)','5.00','2013','B.Sc Engineering','Computer Science & Engine','','Patuakhali Science And Technology University','','Patuakhali Science And Technology University','','CGPA (Out of 4)','3.745','2019','04 Years','','','','','','','','','','','');
/*!40000 ALTER TABLE `edu_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `edu_info` with 3 row(s)
--

--
-- Table structure for table `emp_bank_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_bank_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `bank_name` varchar(25) NOT NULL,
  `branch_name` varchar(25) NOT NULL,
  `branch_add` varchar(25) NOT NULL,
  `acc_name` varchar(25) NOT NULL,
  `acc_no` varchar(25) NOT NULL,
  `swift_code` varchar(15) NOT NULL,
  `routing_no` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_bank_info`
--

LOCK TABLES `emp_bank_info` WRITE;
/*!40000 ALTER TABLE `emp_bank_info` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `emp_bank_info` VALUES (1,1,'6595-3','Janata Bank Ltd.','Dilkusha Corporate','Dilkusha C/A, Dhaka','MD. ABUL HOSSAIN','0100242916764','','','2024-02-06 00:00:00','2024-02-06 05:19:30');
/*!40000 ALTER TABLE `emp_bank_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `emp_bank_info` with 1 row(s)
--

--
-- Table structure for table `exam`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam`
--

LOCK TABLES `exam` WRITE;
/*!40000 ALTER TABLE `exam` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `exam` VALUES (1,'Select'),(2,'S.S.C'),(3,'S.S.C Vocational'),(4,'O Level/Cambridge'),(5,'S.S.C Equivalent'),(6,'Dakhil Vocational'),(7,'H.S.C'),(8,'Alim'),(9,'Business Management'),(10,'Diploma-in-Engineering'),(11,'A Level/Sr. Cambridge'),(12,'H.S.C or Equivalent'),(13,'Diploma in Medical Technology'),(14,'H.S.C Vovational'),(15,'Alim Vocational'),(16,'Honors'),(17,'B.B.A'),(18,'B.Sc Engineering'),(19,'Fazil'),(20,'M.B.B.S/ D.B.S'),(21,'B.Sc in Agriculture Science'),(22,'Graduation Equivalent'),(23,'Pass Course'),(24,'L.L.M'),(25,'MA'),(26,'MBA'),(27,'M.Com'),(28,'M.S.S'),(29,'M.Sc'),(30,'Masters Equivalent'),(31,'Pass Course'),(32,'Dakhil'),(33,'Eight Pass');
/*!40000 ALTER TABLE `exam` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `exam` with 33 row(s)
--

--
-- Table structure for table `examination`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `examination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `examination` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examination`
--

LOCK TABLES `examination` WRITE;
/*!40000 ALTER TABLE `examination` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `examination` VALUES (1,'S.S.C'),(2,'H.S.C or Equivalent'),(3,'Honors'),(4,'B.B.A'),(5,'B.Sc Engineering'),(6,'Masters'),(7,'Ph.D'),(8,'M.B.B.S/D.B.S');
/*!40000 ALTER TABLE `examination` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `examination` with 8 row(s)
--

--
-- Table structure for table `grade`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grade`
--

LOCK TABLES `grade` WRITE;
/*!40000 ALTER TABLE `grade` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `grade` VALUES (1,'1st'),(2,'2nd'),(3,'3rd'),(4,'4th'),(5,'5th'),(6,'6th'),(7,'7th'),(8,'8th'),(9,'9th'),(10,'10th'),(11,'11th'),(12,'12th'),(13,'13th'),(14,'14th'),(15,'15th'),(16,'16th'),(17,'17th'),(18,'18th'),(19,'19th'),(20,'20th');
/*!40000 ALTER TABLE `grade` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `grade` with 20 row(s)
--

--
-- Table structure for table `incre_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incre_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(15) NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `date_of_incre` date NOT NULL,
  `pres_desig` varchar(25) NOT NULL,
  `pres_grade` varchar(15) NOT NULL,
  `new_desig` varchar(25) NOT NULL,
  `new_grade` varchar(15) NOT NULL,
  `place_of_post` varchar(25) NOT NULL,
  `pay_scale` varchar(15) NOT NULL,
  `pres_basic_pay` varchar(15) NOT NULL,
  `incr_granted` varchar(15) NOT NULL,
  `basic_pay_after_incr` varchar(15) NOT NULL,
  `next_incr_date` date NOT NULL,
  `issue_incr_letter` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incre_info`
--

LOCK TABLES `incre_info` WRITE;
/*!40000 ALTER TABLE `incre_info` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `incre_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `incre_info` with 0 row(s)
--

--
-- Table structure for table `job_desc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_desc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `doj` date NOT NULL,
  `police_verification` enum('Complete','Incomplete','Others','') NOT NULL,
  `doc` date NOT NULL,
  `prl` date NOT NULL,
  `pension_start_date` date DEFAULT NULL,
  `place_of_posting` varchar(100) NOT NULL,
  `d_nothi_id` int(12) NOT NULL,
  `tin_no` int(12) NOT NULL,
  `join_designation` varchar(25) NOT NULL,
  `cadre` varchar(25) NOT NULL,
  `sub_cadre` varchar(25) NOT NULL,
  `sub_cadre_header_ext` varchar(50) NOT NULL,
  `appoint_type` varchar(100) NOT NULL,
  `others` varchar(100) NOT NULL,
  `last_promo_date` date NOT NULL,
  `next_promo_date` date NOT NULL,
  `granted_promo_date` date NOT NULL,
  `nature_of_promo` varchar(100) NOT NULL,
  `last_incr_date` date NOT NULL,
  `next_incr_date` date NOT NULL,
  `grade` varchar(25) NOT NULL,
  `basic` varchar(100) NOT NULL,
  `incr_granted` varchar(100) NOT NULL,
  `basic_after_incr` varchar(100) NOT NULL,
  `scale` varchar(50) NOT NULL,
  `job_status` enum('In Service','PRL','Suspended','Retired','Dead with Services','LPR','Resignation','Deputation') NOT NULL,
  `deputation_org` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_desc`
--

LOCK TABLES `job_desc` WRITE;
/*!40000 ALTER TABLE `job_desc` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `job_desc` VALUES (1,0,'6595-3','1994-09-10','2023-03-19','','0000-00-00','2053-09-09','2054-09-09','BCIC H.O.',487908,2147483647,'Assistant Programmer','Finance','Computer','','Regular','','0000-00-00','2028-03-19','0000-00-00','','2024-07-01','2025-07-01','9th','24260','0','24260','22000-53060','In Service','','2024-02-04 11:38:44','2024-02-06 10:53:09'),(2,4,'6594-6','1997-10-10','2023-03-19','Incomplete','0000-00-00','2056-10-09','2057-10-09','BCIC H.O.',454545,2147483647,'Assistant Programmer','Finance','Computer','','Regular','','0000-00-00','2028-03-19','0000-00-00','','2024-07-01','2025-07-01','9th','24260','0','24260','22000-53060','In Service','','2024-02-05 09:27:23','2024-02-05 09:44:33'),(3,5,'8118-2','1986-12-31','2010-11-25','Complete','0000-00-00','2045-12-30','2046-12-30','BCIC H.O.',481014,2147483647,'Computer Operator','Finance','Computer','','Regular','','2021-09-23','2026-09-23','0000-00-00','Regular','2024-07-01','2025-07-01','10th','16800','0','16800','16000-38640','In Service','','2024-02-05 09:55:04','2024-02-05 14:50:03'),(4,3,'7734-7','1965-03-05','1991-02-27','Complete','0000-00-00','2024-03-04','2025-03-04','BCIC H.O.',241297,22222222,'Data Entry Operator','Finance','Computer','','Regular','','2018-03-22','2023-03-22','0000-00-00','Regular','2024-07-01','2025-07-01','5th','63960','0','63960','43000-69850','In Service','','2024-02-05 10:18:38','2024-02-05 10:19:09');
/*!40000 ALTER TABLE `job_desc` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `job_desc` with 4 row(s)
--

--
-- Table structure for table `leave_mgtm`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_mgtm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(15) NOT NULL,
  `leave_type` varchar(30) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_days` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_mgtm`
--

LOCK TABLES `leave_mgtm` WRITE;
/*!40000 ALTER TABLE `leave_mgtm` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `leave_mgtm` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `leave_mgtm` with 0 row(s)
--

--
-- Table structure for table `loan_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `granted_date` date NOT NULL,
  `type` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `fiscal_year` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_info`
--

LOCK TABLES `loan_info` WRITE;
/*!40000 ALTER TABLE `loan_info` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `loan_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `loan_info` with 0 row(s)
--

--
-- Table structure for table `nominees`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nominees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `relation` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `amount_of_share` float NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_no` varchar(20) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nominees`
--

LOCK TABLES `nominees` WRITE;
/*!40000 ALTER TABLE `nominees` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `nominees` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `nominees` with 0 row(s)
--

--
-- Table structure for table `other_qualification_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `other_qualification_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(15) NOT NULL,
  `degree_name` varchar(50) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `university` varchar(250) NOT NULL,
  `country` varchar(50) NOT NULL,
  `course_duration` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `other_qualification_info`
--

LOCK TABLES `other_qualification_info` WRITE;
/*!40000 ALTER TABLE `other_qualification_info` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `other_qualification_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `other_qualification_info` with 0 row(s)
--

--
-- Table structure for table `passing_year`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `passing_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passing_year`
--

LOCK TABLES `passing_year` WRITE;
/*!40000 ALTER TABLE `passing_year` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `passing_year` VALUES (1,1970),(2,1971),(3,1972),(4,1973),(5,1974),(6,1975),(7,1976),(8,1977),(9,1978),(10,1979),(11,1980),(12,1981),(13,1982),(14,1983),(15,1984),(16,1985),(17,1986),(18,1987),(19,1988),(20,1989),(21,1990),(22,1991),(23,1992),(24,1993),(25,1994),(26,1995),(27,1996),(28,1997),(29,1998),(30,1999),(31,2000),(32,2001),(33,2002),(34,2003),(35,2004),(36,2005),(37,2006),(38,2007),(39,2008),(40,2009),(41,2010),(42,2011),(43,2012),(44,2013),(45,2014),(46,2015),(47,2016),(48,2017),(49,2018),(50,2019),(51,2020),(52,2021),(53,2022),(54,2023);
/*!40000 ALTER TABLE `passing_year` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `passing_year` with 54 row(s)
--

--
-- Table structure for table `pay_scale_2015`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_scale_2015` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scale` varchar(50) NOT NULL,
  `grade_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_scale_2015`
--

LOCK TABLES `pay_scale_2015` WRITE;
/*!40000 ALTER TABLE `pay_scale_2015` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `pay_scale_2015` VALUES (1,'78000',1),(2,'66000-76490',2),(3,'56500-74400',3),(4,'50000-71200',4),(5,'43000-69850',5),(6,'35500-67010',6),(7,'29000-63410',7),(8,'23000-55460',8),(9,'22000-53060',9),(10,'16000-38640',10),(11,'12500-32240',11),(12,'11300-27300',12),(13,'11000-26590',13),(14,'10200-24680',14),(15,'9700-23490',15),(16,'9300-22490',16),(17,'9000-21800',17),(18,'8800-21310',18),(19,'8500-20570',19),(20,'8250-20010',20);
/*!40000 ALTER TABLE `pay_scale_2015` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `pay_scale_2015` with 20 row(s)
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `place_of_posting`
--

LOCK TABLES `place_of_posting` WRITE;
/*!40000 ALTER TABLE `place_of_posting` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `place_of_posting` VALUES (1,'BCIC H.O.'),(2,'TICI'),(3,'SFCL'),(4,'JFCL'),(5,'CUFL'),(6,'AFCCL'),(7,'DAPFCL'),(8,'TSPCL'),(9,'GPFPLC'),(10,'GPUFP'),(11,'CCCL'),(12,'CCC'),(13,'BISFL'),(14,'KHBML/KNML'),(15,'KPML'),(16,'DLCL'),(17,'Chemical Godown'),(18,'13 Buffer Godown Project'),(19,'34 Buffer Godown Project'),(20,'CCCL Project');
/*!40000 ALTER TABLE `place_of_posting` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `place_of_posting` with 20 row(s)
--

--
-- Table structure for table `prof_membership`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prof_membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `mem_no` varchar(25) NOT NULL,
  `type` enum('Associate Member','Member','Fellow') NOT NULL,
  `institute` varchar(50) NOT NULL,
  `country` varchar(25) NOT NULL,
  `validity` varchar(25) NOT NULL,
  `month_year` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prof_membership`
--

LOCK TABLES `prof_membership` WRITE;
/*!40000 ALTER TABLE `prof_membership` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `prof_membership` VALUES (1,0,'6595-3','AM-13180','Associate Member','Bangladesh Computer Society (BCS)','Bangladesh','',''),(2,0,'6595-3','A-21426','Member','The Institution of Engineers, Bangladesh (IEB)','Bangladesh','2020','');
/*!40000 ALTER TABLE `prof_membership` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `prof_membership` with 2 row(s)
--

--
-- Table structure for table `prof_quali_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prof_quali_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prof_quali_info`
--

LOCK TABLES `prof_quali_info` WRITE;
/*!40000 ALTER TABLE `prof_quali_info` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `prof_quali_info` VALUES (1,8,'5620-4','Microsoft Certify / Certification world');
/*!40000 ALTER TABLE `prof_quali_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `prof_quali_info` with 1 row(s)
--

--
-- Table structure for table `promotion_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(15) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `date_of_promot` date NOT NULL,
  `granted_promo_date` date NOT NULL,
  `pres_desig` varchar(25) NOT NULL,
  `pres_grade` varchar(15) NOT NULL,
  `new_desig` varchar(25) NOT NULL,
  `new_grade` varchar(15) NOT NULL,
  `place_of_posting` varchar(25) NOT NULL,
  `pres_scale_of_pay` varchar(15) NOT NULL,
  `pres_basic_pay` varchar(15) NOT NULL,
  `new_scale_of_pay` varchar(15) NOT NULL,
  `new_basic_pay` varchar(15) NOT NULL,
  `nature_of_promo` varchar(100) NOT NULL,
  `issue_promot_letter` varchar(15) NOT NULL,
  `scale` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotion_info`
--

LOCK TABLES `promotion_info` WRITE;
/*!40000 ALTER TABLE `promotion_info` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `promotion_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `promotion_info` with 0 row(s)
--

--
-- Table structure for table `publication`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `books_publication` varchar(255) NOT NULL,
  `journal` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication`
--

LOCK TABLES `publication` WRITE;
/*!40000 ALTER TABLE `publication` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `publication` VALUES (1,6,'5620-1','2010-10-10','BCIC','BCIC','sdfg');
/*!40000 ALTER TABLE `publication` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `publication` with 1 row(s)
--

--
-- Table structure for table `section`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `division_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `section` VALUES (1,6,'Chairman Secretariat'),(2,1,'Chief of Personnel (COP)'),(3,1,'LSA'),(4,1,'RNT'),(5,8,'PRD'),(6,12,'Audit'),(7,13,'Local Purchase'),(8,13,'Foreign Purchase'),(9,11,'Marketing'),(10,11,'Marketing Store'),(11,2,'Salary'),(12,2,'PF'),(13,15,'MIS'),(14,14,'Finance '),(15,21,'ICT'),(16,16,'Director (Com.)'),(17,17,'Director (Fin.)'),(18,18,'Director (P&I)'),(19,19,'Director (T&E)'),(20,20,'Director (Prod.)'),(21,22,'Board of Director'),(22,22,'BCIC'),(23,24,'BCIC H.O.'),(24,1,'Admin'),(25,35,'DLCL'),(26,2,'Accounts'),(27,5,'MTS'),(28,5,'Civil'),(29,40,'GPUFP'),(30,31,'AFCCL'),(31,29,'SFCL'),(32,30,'JFCL'),(33,45,'BISFL'),(34,33,'CUFL'),(35,41,'GPFPLC'),(36,32,'DAPFCL'),(37,34,'TSPCL'),(38,36,'KPML'),(39,37,'UGSFL'),(40,39,'CCCL'),(41,38,'CCC'),(42,42,'34 Buffer Project'),(43,43,'13 Buffer Project'),(44,44,'UF-85 Project'),(45,1,'General Administration'),(46,46,'Law');
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `section` with 46 row(s)
--

--
-- Table structure for table `service_history`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `service_type` enum('Before Joining','After Joining') NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `till_now` varchar(25) NOT NULL,
  `place_of_posting` varchar(25) NOT NULL,
  `org_name` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  `designation` varchar(25) NOT NULL,
  `nature_of_promo` varchar(100) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `scale` varchar(50) NOT NULL,
  `basic` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_history`
--

LOCK TABLES `service_history` WRITE;
/*!40000 ALTER TABLE `service_history` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `service_history` VALUES (1,0,'6595-3','Before Joining','2023-01-15','2023-03-16','','','BASIC Bank','','Assistant Manager','','9th','22000-53060','24260'),(2,0,'6595-3','Before Joining','2021-11-11','2023-01-14','','','B. E. C.','','Assistant Engineer','','9th','22000-53060','22000'),(3,0,'6595-3','Before Joining','2019-05-29','2021-11-11','','','BCC','','Assistant Engineer','','9th','22000-53060','22000'),(4,0,'6595-3','After Joining','2023-03-19','2023-04-01','','GPFPLC','BCIC','','Assistant Programmer','','9th','22000-53060','24260'),(5,0,'6595-3','After Joining','2023-04-02','0000-00-00','','BCIC H.O.','BCIC','','Assistant Programmer','','9th','22000-53060','24260'),(6,0,'6594-6','After Joining','2023-03-19','2023-04-01','','SFCL','','','Assistant Programmer','','9th','22000-53060','24260'),(7,0,'6594-6','After Joining','2023-04-02','0000-00-00','','BCIC H.O.','','','Assistant Programmer','','9th','22000-53060','24260'),(8,0,'8118-2','After Joining','2010-11-25','2012-01-07','','BCIC H.O.','','','Computer Operator','Regular','14th','5200-11235',''),(9,0,'8118-2','After Joining','2010-11-25','2012-01-07','','BCIC H.O.','','','Computer Operator','Regular','14th','5200-11235',''),(10,0,'8118-2','After Joining','2016-04-04','2016-04-24','','BCIC H.O.','','','Computer Operator','Regular','14th','5200-11235',''),(11,0,'8118-2','After Joining','2016-04-25','2016-06-12','','BCIC H.O.','','','Computer Operator','Regular','14th','10200-24680',''),(12,0,'8118-2','After Joining','2016-06-13','2021-09-22','','BCIC H.O.','','','Computer Operator','Regular','13th','11000-26590',''),(13,0,'8118-2','After Joining','2021-09-23','0000-00-00','','BCIC H.O.','','','Assistant Accounts Office','Regular','10th','16000-38640','');
/*!40000 ALTER TABLE `service_history` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `service_history` with 13 row(s)
--

--
-- Table structure for table `subject_graduation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_graduation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_graduation`
--

LOCK TABLES `subject_graduation` WRITE;
/*!40000 ALTER TABLE `subject_graduation` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `subject_graduation` VALUES (1,0,'Select'),(2,0,'Architecture'),(3,0,'Chemical Engineering'),(4,0,'Civil Engineering'),(5,0,'Computer Engineering'),(6,0,'Computer Science'),(7,0,'Computer Science & Engineering'),(8,0,'Computer Science & Information Technology'),(9,0,'Electrical & Electronics Engineering'),(10,0,'Electrical Engineering'),(11,0,'Electronics Engineering'),(12,0,'Electronics & Communication Engineering'),(13,0,'Electronics & Telecommunication Engineering'),(14,0,'Environmental Engineering'),(15,0,'Food and Process Engineering'),(16,0,'Genetic Engineering'),(17,0,'Industrial Engineering'),(18,0,'Information and Communication Engineering'),(19,0,'Information and Communication Technology'),(20,0,'Leather Engineering'),(21,0,'Marine Engineering'),(22,0,'Materials Science & Engineering'),(23,0,'Mechanical Engineering'),(24,0,'Medical Physics & Biomedical Engineering'),(25,0,'Metallurgical Engineering'),(26,0,'Microwave Engineering'),(27,0,'Mineral Engineering'),(28,0,'Mining Engineering'),(29,0,'Naval Architecture & Marine Engineering'),(30,0,'Physical Planning'),(31,0,'Regional Planning'),(32,0,'Software Engineering'),(33,0,'Structural Engineering'),(34,0,'Telecommunication Engineering'),(35,0,'Textile Engineering'),(36,0,'Town Planning'),(37,0,'Urban & Regional Planning'),(38,0,'Water Resource Engineering'),(39,0,'Others'),(40,1,'Accounting'),(41,1,'Agriculture'),(42,1,'Anthropology'),(43,1,'Applied Chemistry'),(44,1,'Applied Mathematics'),(45,1,'Applied Physics'),(46,1,'Archaeology'),(47,1,'Bangla'),(48,1,'Banking'),(49,1,'Biochemistry'),(50,1,'Botany'),(51,1,'Business Administration'),(52,1,'Chemistry'),(53,1,'Clinical Psychology'),(54,1,'Communication Disorders'),(55,1,'Computer Science'),(56,1,'Criminology'),(57,1,'Criminology & Police Science'),(58,1,'Development Studies'),(59,1,'Drama & Music'),(60,1,'Drawing & Painting'),(61,1,'Economics'),(62,1,'Education'),(63,1,'English'),(64,1,'Environmental Science'),(65,1,'Ethics'),(66,1,'Finance'),(67,1,'Finance & Banking'),(68,1,'Fine Arts'),(69,1,'Folklore'),(70,1,'Forestry'),(71,1,'Genetic and Breeding'),(72,1,'Genetic Engineering and Biotechnology'),(73,1,'Geography'),(74,1,'Geography and Environmental Science'),(75,1,'Geology/Geology and Mining'),(76,1,'Government and Politics'),(77,1,'Graphics'),(78,1,'History'),(79,1,'History of Music'),(80,1,'Home Economics'),(81,1,'Industrial Arts'),(82,1,'Information Science and Library Management'),(83,1,'Information Technology'),(84,1,'Industrial Law'),(85,1,'International Relations'),(86,1,'Islamic History and Culture'),(87,1,'Islamic Studies'),(88,1,'Language/Linguistic '),(89,1,'Law/Jurisprudence'),(90,1,'Management'),(91,1,'Marine Science'),(92,1,'Marketing'),(93,1,'Mass Communication & Journalism'),(94,1,'Mathematics'),(95,1,'Medical Technology'),(96,1,'Microbiology'),(97,1,'Pali'),(98,1,'Peace & Conflict'),(99,1,'Persian'),(100,1,'Pharmaceutical Chemistry'),(101,1,'Pharmacy'),(102,1,'Philosophy'),(103,1,'Physics'),(104,1,'Political Science'),(105,1,'Population Science'),(106,1,'Population Science and Human Resource Development (RU)'),(107,1,'Printing and Publication Studies'),(108,1,'Public Administration'),(109,1,'Public Finance'),(110,1,'Sanskrit'),(111,1,'Social Welfare/Social Work'),(112,1,'Social Works'),(113,1,'Sociology'),(114,1,'Soil, Water and Environment Science'),(115,1,'Statistics'),(116,1,'Television, Film and Photography'),(117,1,'Urban Development'),(118,1,'Urdu'),(119,1,'Women and Gender Studies'),(120,1,'Women Studies'),(121,1,'World Religion'),(122,1,'Zoology'),(123,1,'Akaid'),(124,1,'Arabic'),(125,1,'Fikha'),(126,1,'Hadith'),(127,1,'Modern Arabic'),(128,1,'Tafsir'),(129,1,'Accounting and Information Systems'),(130,1,'Banking & Insurance'),(131,1,'Human Resource Management'),(132,1,'International Business'),(133,1,'Management Information Systems'),(134,1,'Organization Strategy and Leadership'),(135,1,'Tourism and Hospitality Management'),(136,1,'Others'),(137,0,'B.S.S');
/*!40000 ALTER TABLE `subject_graduation` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `subject_graduation` with 137 row(s)
--

--
-- Table structure for table `subject_hsc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_hsc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_hsc`
--

LOCK TABLES `subject_hsc` WRITE;
/*!40000 ALTER TABLE `subject_hsc` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `subject_hsc` VALUES (1,'Business Studies'),(2,'General'),(3,'Humanities'),(4,'Science'),(5,'Agriculture Technology'),(6,'Agro-Based Food'),(7,'Architectural Drafting with CAD'),(8,'Architecture and Interior Design Technology'),(9,'Automobile Technology'),(10,'Automotive'),(11,'Building Maintenance'),(12,'Ceramic'),(13,'Chemical Technology'),(14,'Civil Construction'),(15,'Civil Drafting with CAD'),(16,'Civil Technology'),(17,'Computer and Information Technology'),(18,'Computer Science & Technology'),(19,'Data Telecommunication and Network Technology'),(20,'Dress Making'),(21,'Dyening, Printing and Finishing'),(22,'Electrical and Electronics Technology'),(23,'Electrical Maintenance Works'),(24,'Electrical Technology'),(25,'Electronics Technology'),(26,'Environmental Technology'),(27,'Farm Machinery'),(28,'Firm Machinery'),(29,'Fish Culture and Breeding'),(30,'Flower, Fruit and Vegetable Cultivation'),(31,'Food'),(32,'Food Processing and Preservation'),(33,'General Electrical Works'),(34,'General Electronics'),(35,'General Mechanics'),(36,'Glass'),(37,'Glass and Ceramic'),(38,'Instrumentation and Process Control Technology'),(39,'Knitting'),(40,'Library Science'),(41,'Livestock Rearing and Farming'),(42,'Machine Tools Operation'),(43,'Mechanical Drafting with CAD'),(44,'Mechanical Technology'),(45,'Mechatronics Technology'),(46,'Patient Care'),(47,'Plumbing and Pipe Fittings'),(48,'Poultry Rearing and Farming'),(49,'Power Technology'),(50,'Refrigeration and Air Conditioning Technology'),(51,'Refrigeration and Air Conditioning'),(52,'Shrimp Culture and Breeding'),(53,'Survey'),(54,'Telecommunication Technology'),(55,'Textile Technology'),(56,'Weaving'),(57,'Welding and Fabrication'),(58,'Wood Working'),(59,'Other'),(60,'Electro-Medical Technology'),(61,'Garments Design and Pattern Making'),(62,'Graphic Design Technology'),(63,'Information and Communication Technology'),(64,'Jute Technology'),(65,'Marine Technology'),(66,'Mining and Mine Survey Technology'),(67,'Shipbuilding Technology'),(68,'Dental'),(69,'Laboratory'),(70,'Pharmacy'),(71,'Physiotherapy'),(72,'Radiography'),(73,'Radiotherapy');
/*!40000 ALTER TABLE `subject_hsc` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `subject_hsc` with 73 row(s)
--

--
-- Table structure for table `subject_ssc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_ssc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_ssc`
--

LOCK TABLES `subject_ssc` WRITE;
/*!40000 ALTER TABLE `subject_ssc` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `subject_ssc` VALUES (1,'Select',1),(2,'General',1),(3,'Humanities',1),(4,'Science',1),(5,'Agriculture Technology',2),(6,'Agro-Based Food',2),(7,'Architectural Drafting with CAD',2),(8,'Architecture and Interior Design Technology',2),(9,'Automobile Technology',2),(10,'Automotive',2),(11,'Building Maintenance',2),(12,'Ceramic',2),(13,'Chemical Technology',2),(14,'Civil Construction',2),(15,'Civil Drafting with CAD',2),(16,'Civil Technology',2),(17,'Computer and Information Technology',2),(18,'Computer Science & Technology',2),(19,'Data Telecommunication and Network Technology',2),(20,'Dress Making',2),(21,'Dyening, Printing and Finishing',2),(22,'Electrical and Electronics Technology',2),(23,'Electrical Maintenance Works',2),(24,'Electrical Technology',2),(25,'Electronics Technology',2),(26,'Environmental Technology',2),(27,'Farm Machinery',2),(28,'Firm Machinery',2),(29,'Fish Culture and Breeding',2),(30,'Flower, Fruit and Vegetable Cultivation',2),(31,'Food',2),(32,'Food Processing and Preservation',2),(33,'General Electrical Works',2),(34,'General Electronics',2),(35,'General Mechanics',2),(36,'Glass',2),(37,'Glass and Ceramic',2),(38,'Instrumentation and Process Control Technology',2),(39,'Knitting',2),(40,'Library Science',2),(41,'Livestock Rearing and Farming',2),(42,'Machine Tools Operation',2),(43,'Mechanical Drafting with CAD',2),(44,'Mechanical Technology',2),(45,'Mechatronics Technology',2),(46,'Patient Care',2),(47,'Plumbing and Pipe Fittings',2),(48,'Poultry Rearing and Farming',2),(49,'Power Technology',2),(50,'Refrigeration and Air Conditioning Technology',2),(51,'Refrigeration and Air Conditioning',2),(52,'Shrimp Culture and Breeding',2),(53,'Survey',2),(54,'Telecommunication Technology',2),(55,'Textile Technology',2),(56,'Weaving',2),(57,'Welding and Fabrication',2),(58,'Wood Working',2),(60,'Electro-Medical Technology',2),(61,'Garments Design and Pattern Making',2),(62,'Graphic Design Technology',2),(63,'Information and Communication Technology',2),(64,'Jute Technology',2),(65,'Marine Technology',2),(66,'Mining and Mine Survey Technology',2),(67,'Shipbuilding Technology',2),(68,'Dental',3),(69,'Laboratory',3),(70,'Pharmacy',3),(71,'Physiotherapy',3),(72,'Radiography',3),(73,'Radiotherapy',3),(74,'Others',2),(75,'Business Studies',2),(76,'Business Studies',1),(77,'Select',4);
/*!40000 ALTER TABLE `subject_ssc` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `subject_ssc` with 76 row(s)
--

--
-- Table structure for table `subject_ssc_hsc`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_ssc_hsc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_ssc_hsc`
--

LOCK TABLES `subject_ssc_hsc` WRITE;
/*!40000 ALTER TABLE `subject_ssc_hsc` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `subject_ssc_hsc` VALUES (1,1,'Business Studies'),(2,1,'General'),(3,1,'Humanities'),(4,1,'Science'),(5,0,'Agriculture Technology'),(6,0,'Agro-Based Food'),(7,0,'Architectural Drafting with CAD'),(8,0,'Architecture and Interior Design Technology'),(9,0,'Automobile Technology'),(10,0,'Automotive'),(11,0,'Building Maintenance'),(12,0,'Ceramic'),(13,0,'Chemical Technology'),(14,0,'Civil Construction'),(15,0,'Civil Drafting with CAD'),(16,0,'Civil Technology'),(17,0,'Computer and Information Technology'),(18,0,'Computer Science & Technology'),(19,0,'Data Telecommunication and Network Technology'),(20,0,'Dress Making'),(21,0,'Dyening, Printing and Finishing'),(22,0,'Electrical and Electronics Technology'),(23,0,'Electrical Maintenance Works'),(24,0,'Electrical Technology'),(25,0,'Electronics Technology'),(26,0,'Environmental Technology'),(27,0,'Farm Machinery'),(28,0,'Firm Machinery'),(29,0,'Fish Culture and Breeding'),(30,0,'Flower, Fruit and Vegetable Cultivation'),(31,0,'Food'),(32,0,'Food Processing and Preservation'),(33,0,'General Electrical Works'),(34,0,'General Electronics'),(35,0,'General Mechanics'),(36,0,'Glass'),(37,0,'Glass and Ceramic'),(38,0,'Instrumentation and Process Control Technology'),(39,0,'Knitting'),(40,0,'Library Science'),(41,0,'Livestock Rearing and Farming'),(42,0,'Machine Tools Operation'),(43,0,'Mechanical Drafting with CAD'),(44,0,'Mechanical Technology'),(45,0,'Mechatronics Technology'),(46,0,'Patient Care'),(47,0,'Plumbing and Pipe Fittings'),(48,0,'Poultry Rearing and Farming'),(49,0,'Power Technology'),(50,0,'Refrigeration and Air Conditioning Technology'),(51,0,'Refrigeration and Air Conditioning'),(52,0,'Shrimp Culture and Breeding'),(53,0,'Survey'),(54,0,'Telecommunication Technology'),(55,0,'Textile Technology'),(56,0,'Weaving'),(57,0,'Welding and Fabrication'),(58,0,'Wood Working'),(59,1,'Other'),(60,0,'Electro-Medical Technology'),(61,0,'Garments Design and Pattern Making'),(62,0,'Graphic Design Technology'),(63,0,'Information and Communication Technology'),(64,0,'Jute Technology'),(65,0,'Marine Technology'),(66,0,'Mining and Mine Survey Technology'),(67,0,'Shipbuilding Technology'),(68,0,'Dental'),(69,0,'Laboratory'),(70,0,'Pharmacy'),(71,0,'Physiotherapy'),(72,0,'Radiography'),(73,0,'Radiotherapy');
/*!40000 ALTER TABLE `subject_ssc_hsc` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `subject_ssc_hsc` with 73 row(s)
--

--
-- Table structure for table `sub_cadre`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_cadre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadre_id` int(11) NOT NULL,
  `sub_cadre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_cadre`
--

LOCK TABLES `sub_cadre` WRITE;
/*!40000 ALTER TABLE `sub_cadre` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sub_cadre` VALUES (1,1,'Admin'),(2,1,'Security'),(3,1,'Medical'),(4,1,'Education'),(5,1,'Headmaster'),(6,1,'Assistant Headmaster'),(7,2,'Operation'),(8,2,'Engineer'),(9,2,'Engineer(Civil)'),(10,2,'Forest'),(11,7,'Accounts & Audit, C&B, Fin & Ins'),(12,3,'Computer'),(13,4,'Commercial'),(14,5,'Senior GM'),(15,6,'General Manager'),(16,7,'Accounts'),(17,3,'Finance');
/*!40000 ALTER TABLE `sub_cadre` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sub_cadre` with 17 row(s)
--

--
-- Table structure for table `sub_cadre_header`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_cadre_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(50) NOT NULL,
  `sub_cadre_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_cadre_header`
--

LOCK TABLES `sub_cadre_header` WRITE;
/*!40000 ALTER TABLE `sub_cadre_header` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `sub_cadre_header` VALUES (1,'Administrative',0),(2,'Commercial',0),(3,'Grade-1',0),(4,'Accounts',0),(5,'MTS/EIP',0),(6,'MTS/Mech',0),(7,'Technical Officer',0),(8,'Chemical',0),(9,'Chemistry',0),(10,'Production',0),(11,'Instrument',0),(12,'EEE',0),(13,'ME',0),(14,'Civil',0),(15,'Architecture',0),(16,'Forest',0),(17,'MTS',0),(18,'Operation',0),(19,'Security',0),(20,'Grade-2',0),(21,'Power',0),(22,'Technical',0),(23,'Marketing',0),(24,'Purchase',0),(25,'Coordination',0),(26,'PRD',0),(27,'L&W',0),(28,'LSA',0),(29,'Estate',0),(30,'F&S',0),(31,'Sales',0);
/*!40000 ALTER TABLE `sub_cadre_header` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `sub_cadre_header` with 31 row(s)
--

--
-- Table structure for table `summ_rating_sheet_promot`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summ_rating_sheet_promot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(15) NOT NULL,
  `pres_desig` varchar(25) NOT NULL,
  `pres_grade` varchar(15) NOT NULL,
  `prop_desig` varchar(25) NOT NULL,
  `prop_grade` varchar(15) NOT NULL,
  `pres_sal_scale` varchar(15) NOT NULL,
  `prop_sal_scale` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `summ_rating_sheet_promot`
--

LOCK TABLES `summ_rating_sheet_promot` WRITE;
/*!40000 ALTER TABLE `summ_rating_sheet_promot` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `summ_rating_sheet_promot` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `summ_rating_sheet_promot` with 0 row(s)
--

--
-- Table structure for table `training_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `country` varchar(25) NOT NULL,
  `period` varchar(25) NOT NULL,
  `month_year` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_info`
--

LOCK TABLES `training_info` WRITE;
/*!40000 ALTER TABLE `training_info` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `training_info` VALUES (1,0,'8118-2','Local Training','Computer Fundamentals & Its Appli.','TICI','Bangladesh','2 weeks','Jan\'2011'),(2,0,'6595-3','Local Training','CCNA Vendor Certified','CISCO','Bangladesh','','Dec\'18'),(3,0,'6595-3','Local Training','MCSA Vendor Certified','Microsoft','Bangladesh','','March\'20'),(4,0,'6595-3','Local Training','MCSE Vendor Certified','Microsoft','Bangladesh','','March\'20'),(5,0,'6595-3','Local Training','OSP & Network Maintenance','BCC','Bangladesh','03 days','Sep\'19'),(6,0,'6595-3','Local Training','BCS Certified Cyber Security Professional','Bangladesh Computer Society (BCS)','Bangladesh','02 days','Feb\'23');
/*!40000 ALTER TABLE `training_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `training_info` with 6 row(s)
--

--
-- Table structure for table `university_list`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `university_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `university_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `university_list`
--

LOCK TABLES `university_list` WRITE;
/*!40000 ALTER TABLE `university_list` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `university_list` VALUES (1,'University of Dhaka'),(2,'University of Rajshahi'),(3,'Bangladesh Agricultural University'),(4,'Bangladesh University of Engineering & Technology'),(5,'University of Chittagong'),(6,'Jahangirnagar University'),(7,'Islamic University, Bangladesh'),(8,'Shahjalal University of Science & Technology'),(9,'Khulna University'),(10,'National University'),(11,'Bangladesh Open University'),(12,'Bangabandhu Sheikh Mujib Medical University'),(13,'Bangabandhu Sheikh Mujibur Rahman Agricultural University'),(14,'Bangabandhu Sheikh Mujibur Rahman Agricultural University'),(15,'Hajee Mohammad Danesh Science & Technology University'),(16,'Mawlana Bhashani Science & Technology University'),(17,'Patuakhali Science And Technology University'),(18,'Sher-e-Bangla Agricultural University'),(19,'Chittagong University of Engineering & Technology'),(20,'Rajshahi University of Engineering & Technology'),(21,'Khulna University of Engineering & Technology'),(22,'Dhaka University of Engineering & Technology'),(23,'Noakhali Science & Technology University'),(24,'Jagannath University'),(25,'Comilla University'),(26,'Jatiya Kabi Kazi Nazrul Islam University'),(27,'Chittagong Veterinary and Animal Sciences University'),(28,'Sylhet Agricultural University'),(29,'Jessore University of Science & Technology'),(30,'Pabna University of Science and Technology'),(31,'Begum Rokeya University, Rangpur'),(32,'Bangladesh University of Professionals'),(33,'Bangabandhu Sheikh Mujibur Rahman Science & Technology'),(34,'Bangladesh University of Textiles'),(35,'University of Barishal'),(36,'Rangamati Science and Technology University'),(37,'Islamic Arabic University'),(38,'Chittagong Medical University'),(39,'Rajshahi Medical University'),(40,'Rabindra University, Bangladesh'),(41,'Bangabandhu Sheikh Mujibur Rahman Digital University'),(42,'Sheikh Hasina University'),(43,'Khulna Agricultural University'),(44,'Bangamata Sheikh Fojilatunnesa Mujib Science and Technology'),(45,'Sylhet Medical University'),(46,'Bangabandhu Sheikh Mujibur Rahman Aviation And Aerospace University (BSMRAAU)'),(47,'Chandpur Science and Technology University'),(48,'Bangabandhu Sheikh Mujibur Rahman University, Kishoreganj'),(49,'Hobiganj Agricultural University'),(50,'Sheikh Hasina Medical University, Khulna'),(51,'Kurigram Agricultural University'),(52,'Sunamganj Science and Technology University'),(53,'Bangabandhu Sheikh Mujibur Rahman Science & Technology'),(54,'North South University'),(55,'University of Science & Technology Chittagong'),(56,'Independent University, Bangladesh'),(57,'Central Women\'s University'),(58,'International University of Business Agriculture & Technology'),(59,'International Islamic University Chittagong'),(60,'Ahsanullah University of Science and Technology'),(61,'American International University-Bangladesh'),(62,'East West University'),(63,'University of Asia Pacific'),(64,'The People\'s University of Bangladesh '),(65,'Asian University of Bangladesh'),(66,'Dhaka International University'),(67,'Manarat International University'),(68,'BRAC University'),(69,'Bangladesh University '),(70,'Leading University'),(71,'Southeast University '),(72,'Daffodil International University'),(73,'Stamford University Bangladesh'),(74,'State University of Bangladesh'),(75,'City University'),(76,'Prime University'),(77,'Northern University Bangladesh'),(78,'Southern University Bangladesh '),(79,'Green University of Bangladesh'),(80,'World University of Bangladesh'),(81,'Shanto-Mariam University of Creative Technology'),(82,'Eastern University'),(83,'Metropolitan University'),(84,'Uttara University'),(85,'United International University'),(86,'University of South Asia'),(87,'Bangladesh University of Business & Technology'),(88,'Presidency University '),(89,'University of Information Technology & Sciences'),(90,'University of Liberal Arts Bangladesh'),(91,'Atish Dipankar University of Science & Technology'),(92,'Bangladesh Islami University'),(93,'ASA University Bangladesh'),(94,'BGMEA University of Fashion & Technology(BUFT) '),(95,'Notre Dame University Bangladesh'),(96,'Bangladesh Army University of Science and Technology(BAUST), Saidpur'),(97,'Bangladesh Army University of Engineering and Technology (BAUET), Qadirabad'),(98,'Bangladesh Army International University of Science & Technology(BAIUST) ,Comilla'),(99,'Others');
/*!40000 ALTER TABLE `university_list` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `university_list` with 99 row(s)
--

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `employee_type` enum('Officer','Staff','','') NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `division` varchar(25) NOT NULL,
  `place_of_posting` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `role` enum('user','admin','sadmin') NOT NULL,
  `image` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `mobile_no` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nid` varchar(13) NOT NULL,
  `job_confirm_status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'Officer','5620-1','Sunil Chandra Das ','General Manager','Audit Division','BCIC H.O','c7cb8046c46be47a9ba3bfdbed859b96','approved','admin','../images/ffa2bd4125ab20171004_092347.jpg','0000-00-00','01718834659','admin@gmail.com','','approved',NULL,'2024-02-04 05:20:10'),(2,'Officer','5620-0','Md. Tareq Emran','Programmer','MTS/EIP','BCIC H.O','c7cb8046c46be47a9ba3bfdbed859b96','approved','sadmin','image/Dir.(Fin.).jpg','0000-00-00','','','','approved',NULL,'2024-02-04 05:20:13'),(3,'Officer','6595-3','MD. ABUL HOSSAIN','Assistant Programmer','ICT Division','','c7cb8046c46be47a9ba3bfdbed859b96','approved','user','../images/5e6c80ex__2031.jpg','0000-00-00','01735895935','mahossain.bcic@gmail.com','','approved',NULL,'2024-02-06 09:28:03'),(4,'Officer','3899-2','Mr. Mohammad Zakir Hossain','Chief of Personnel','Personnel Division','','','approved','user','','0000-00-00','01718834659','admin@gmail.com','','approved',NULL,'2024-02-04 05:07:51'),(5,'Officer','7734-7','KANEJ FATEMA CHOWDHURY','System Analyst','ICT Division','','','approved','user','','0000-00-00','01675703923','tuhinkaniz65@gmail.com','','approved',NULL,'2024-02-04 05:07:56'),(6,'Officer','6594-6','Shaneworn Bhadra ','Assistant Programmer','ICT Division','','','approved','user','../images/cabdbf500 pic.jpg','0000-00-00','01878072812','shanewornbhadra@gmail.com','','approved',NULL,'2024-02-05 03:49:38'),(7,'Officer','8118-2','Maharun nasa Marry','Assistant Accounts Officer','ICT Division','','','approved','user','../images/c63f80Tulips.jpg','0000-00-00','01611661313','maharunmarry@gmail.com','','approved',NULL,'2024-02-04 05:19:57'),(8,'Officer','7928-5','Refat Ullah','Operation Officer','ICT Division','','','approved','user','../images/76ad2eziraf.jpg','0000-00-00','01718834655','emrantareq09@gmail.com','','approved',NULL,'2024-02-04 05:21:27');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 8 row(s)
--

--
-- Table structure for table `welfare_info`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `welfare_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `granted_date` date NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `fiscal_year` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `welfare_info`
--

LOCK TABLES `welfare_info` WRITE;
/*!40000 ALTER TABLE `welfare_info` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `welfare_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `welfare_info` with 0 row(s)
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

-- Dump completed on: Tue, 06 Feb 2024 10:36:39 +0100
