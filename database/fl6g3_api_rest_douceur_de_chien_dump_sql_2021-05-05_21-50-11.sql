-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: h2mysql55.infomaniak.ch	Database: fl6g3_api_rest_douceur_de_chien
-- ------------------------------------------------------
-- Server version 	5.7.32-log
-- Date: Wed, 05 May 2021 21:50:15 +0200

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `absence`
--

DROP TABLE IF EXISTS `absence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `absence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_absence_from` date NOT NULL,
  `date_absence_to` date DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `id_educator` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absence`
--

LOCK TABLES `absence` WRITE;
/*!40000 ALTER TABLE `absence` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `absence` VALUES (1,'2021-01-01','2021-01-07','Vacance de janvier',0,1),(2,'2021-04-05','2021-04-11','Première Vacance d\'avril',0,1),(3,'2021-04-19','2021-04-25','Deuxième Vacance d\'avril',1,1),(4,'2021-05-03','2021-05-09','Déménagement',0,2),(5,'2021-06-07','2021-06-13','Malade',0,3);
/*!40000 ALTER TABLE `absence` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `appoitment`
--

DROP TABLE IF EXISTS `appoitment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `appoitment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime_appoitment` datetime NOT NULL,
  `duration_in_hour` int(11) NOT NULL,
  `note_text` text,
  `note_graphical_serial_id` varchar(45) DEFAULT NULL,
  `summary` text,
  `datetime_deletion` datetime DEFAULT NULL,
  `user_id_customer` int(11) NOT NULL,
  `user_id_educator` int(11) NOT NULL,
  `user_id_deletion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_appoitment_user1_idx` (`user_id_customer`),
  KEY `fk_appoitment_user2_idx` (`user_id_deletion`),
  KEY `fk_appoitment_user3_idx` (`user_id_educator`),
  CONSTRAINT `fk_appoitment_user1` FOREIGN KEY (`user_id_customer`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_appoitment_user2` FOREIGN KEY (`user_id_deletion`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_appoitment_user3` FOREIGN KEY (`user_id_educator`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appoitment`
--

LOCK TABLES `appoitment` WRITE;
/*!40000 ALTER TABLE `appoitment` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `appoitment` VALUES (1,'2020-04-02 09:00:00',2,NULL,'JQ5LD72g',NULL,NULL,4,1,NULL),(2,'2020-05-12 10:00:00',3,NULL,NULL,NULL,NULL,5,2,NULL),(3,'2020-06-22 14:00:00',2,NULL,NULL,NULL,NULL,6,3,NULL);
/*!40000 ALTER TABLE `appoitment` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_serial_id` varchar(45) NOT NULL,
  `type` enum('conditions_inscription','poster') NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_document_user1_idx` (`user_id`),
  CONSTRAINT `fk_document_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document`
--

LOCK TABLES `document` WRITE;
/*!40000 ALTER TABLE `document` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `document` VALUES (1,'ly5uy43256','conditions_inscription',4),(2,'p1yay43ko6','conditions_inscription',5),(3,'V9CUouI8.pdf','poster',6),(4,'mASE47FP','conditions_inscription',4);
/*!40000 ALTER TABLE `document` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `dog`
--

DROP TABLE IF EXISTS `dog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `dog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `breed` varchar(45) NOT NULL,
  `sex` varchar(45) NOT NULL,
  `picture_serial_id` varchar(45) DEFAULT NULL,
  `chip_id` varchar(15) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dog_user_idx` (`user_id`),
  CONSTRAINT `fk_dog_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dog`
--

LOCK TABLES `dog` WRITE;
/*!40000 ALTER TABLE `dog` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dog` VALUES (1,'Paco','Staffy','Mâle',NULL,'123456789112345',4),(2,'Hyron','Staffy','Mâle',NULL,'123451234512345',5),(3,'Jaya','Rhodesian Ridgeback','Femelle','fYPxlcOc','123123123123123',6);
/*!40000 ALTER TABLE `dog` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `schedule_override`
--

DROP TABLE IF EXISTS `schedule_override`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `schedule_override` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_schedule_override` date NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `id_educator` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule_override`
--

LOCK TABLES `schedule_override` WRITE;
/*!40000 ALTER TABLE `schedule_override` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `schedule_override` VALUES (1,'2021-04-15',0,1),(2,'2021-02-11',0,1),(3,'2021-03-27',1,1),(4,'2021-05-17',0,2),(5,'2021-06-19',0,3);
/*!40000 ALTER TABLE `schedule_override` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `time_slot`
--

DROP TABLE IF EXISTS `time_slot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `time_slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_day` tinyint(4) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `id_weekly_schedule` int(11) DEFAULT NULL,
  `id_schedule_override` int(11) DEFAULT NULL,
  `id_educator` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Tbl_Time_Slot_Tbl_Schedule_idx` (`id_weekly_schedule`),
  KEY `fk_Tbl_Time_Slot_Tbl_Schedule_Override1_idx` (`id_schedule_override`),
  CONSTRAINT `fk_Tbl_Time_Slot_Tbl_Schedule` FOREIGN KEY (`id_weekly_schedule`) REFERENCES `weekly_schedule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tbl_Time_Slot_Tbl_Schedule_Override1` FOREIGN KEY (`id_schedule_override`) REFERENCES `schedule_override` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_slot`
--

LOCK TABLES `time_slot` WRITE;
/*!40000 ALTER TABLE `time_slot` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `time_slot` VALUES (1,2,'08:00:00','10:00:00',0,1,NULL,1),(2,3,'13:00:00','15:00:00',0,1,NULL,1),(3,4,'17:00:00','19:00:00',0,1,NULL,1),(4,5,'20:00:00','21:00:00',1,1,NULL,1),(5,5,'14:00:00','16:00:00',0,NULL,1,1),(6,5,'13:00:00','15:00:00',1,NULL,2,1),(7,5,'09:00:00','11:00:00',0,2,NULL,2),(8,6,'12:00:00','14:00:00',0,2,NULL,2),(9,7,'18:00:00','20:00:00',0,2,NULL,2),(10,2,'07:00:00','09:00:00',0,NULL,2,2),(11,4,'10:00:00','12:00:00',0,3,NULL,3),(12,5,'15:00:00','16:00:00',0,3,NULL,3),(13,6,'17:00:00','19:00:00',0,3,NULL,3),(14,7,'11:00:00','13:00:00',0,NULL,3,3);
/*!40000 ALTER TABLE `time_slot` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `address` varchar(45) NOT NULL,
  `api_token` varchar(45) NOT NULL,
  `code_role` varchar(10) NOT NULL,
  `password_hash` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `user` VALUES (1,'borel@eduge.ch','Jonathan','Borel-Jaquet','0772345212','Route de Frontenex 99 1208 Genève','e8e08012c93cce830cb19ff8e2977504','2','$2y$10$8H2s458tYmy72XFrEw3DlOBvgn60NKo0itM/KVd6HVzd0gj4nmQPG'),(2,'ackermangue@eduge.ch','Gawen','Ackermangue','0781355282','Chemin des Charmilles 12 1202 Genève','16a58476139a21dac6d5a4cdb07441b1','2',NULL),(3,'merguez@eduge.ch','David','Merguez','0714248272','Chemin des Charmilles 11 1202 Genève','d51c9d05c1399ca18eace53a84ab00c6','2',NULL),(4,'burger@eduge.ch','Flo','Burger','0791924210','Route de Satigny 07 1228 Genève','3f71973da617595f8410114d3d5009e1','1',NULL),(5,'uber@eduge.ch','Fabian','Uber','0761735282','Route de Frontenex 89 1208 Genève','97685e1a159431e00f29c62f18078ab8','1','$2y$10$8H2s458tYmy72XFrEw3DlOBvgn60NKo0itM/KVd6HVzd0gj4nmQPG'),(6,'alfiero@eduge.ch','Elena','Alfiero','0721567812','Route de Frontenex 97 1208 Genève','aba594eba312f2063afca65e28eb2e78','1',NULL),(7,'donetallo@eduge.ch','Daniel','Donatallo','0771235496','Route des Acacias 45 1245 Genève','035e3a1a1fc175d8ebb053b0c43823c0','1',NULL),(8,'alex@eduge.ch','Alexis','Lapon','0793786791','Arthur la brose 12 1268 Genève','02be925988a198b70591004242725841','1',NULL),(9,'eric@eduge.ch','Eric','Dubois','0745761298','Chemin du soleil 127 1248 Genève','ca52362648a6ee06cc5733a1b31416aa','1',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `weekly_schedule`
--

DROP TABLE IF EXISTS `weekly_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `weekly_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_valid_from` date NOT NULL,
  `date_valid_to` date DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `id_educator` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weekly_schedule`
--

LOCK TABLES `weekly_schedule` WRITE;
/*!40000 ALTER TABLE `weekly_schedule` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `weekly_schedule` VALUES (1,'2021-03-29','2021-05-09',0,1),(2,'2021-08-01','2021-08-31',0,1),(3,'2021-09-01','2021-09-30',1,1),(4,'2021-10-01',NULL,0,1),(5,'2021-04-26','2021-06-06',0,2),(6,'2021-05-31','2021-07-11',0,3);
/*!40000 ALTER TABLE `weekly_schedule` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for view `dates`
--

DROP TABLE IF EXISTS `dates`;
/*!50001 DROP VIEW IF EXISTS `dates`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`fl6g3_brljq`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dates` AS select ((curdate() + interval 365 day) - interval `numbers`.`number` day) AS `date` from `numbers` */;

--
-- Table structure for view `digits`
--

DROP TABLE IF EXISTS `digits`;
/*!50001 DROP VIEW IF EXISTS `digits`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`fl6g3_brljq`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `digits` AS select 0 AS `digit` union all select 1 AS `1` union all select 2 AS `2` union all select 3 AS `3` union all select 4 AS `4` union all select 5 AS `5` union all select 6 AS `6` union all select 7 AS `7` union all select 8 AS `8` union all select 9 AS `9` */;

--
-- Table structure for view `numbers`
--

DROP TABLE IF EXISTS `numbers`;
/*!50001 DROP VIEW IF EXISTS `numbers`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`fl6g3_brljq`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `numbers` AS select ((`ones`.`digit` + (`tens`.`digit` * 10)) + (`hundreds`.`digit` * 100)) AS `number` from ((`digits` `ones` join `digits` `tens`) join `digits` `hundreds`) */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Wed, 05 May 2021 21:50:15 +0200
