-- MySQL dump 10.16  Distrib 10.1.21-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: localhost
-- ------------------------------------------------------
-- Server version	10.1.21-MariaDB

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (15,'Administrator','admin@demo.com','$2y$10$XsqXzDJHyqCRXNWvXzWa7.9EsKddUmiZd51aP.A9WoRfX8l5RekYW',NULL,NULL),(16,'Joko Wandiro','joko_wandiro@yahoo.com','$2y$10$rwcCcsWKUz4njwTQZ4N0KusEoq8RxwAgzchYODrSZsqD4NNAQDMUK','2020-01-10 08:50:20','2020-01-10 08:51:19');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `meta_title` varchar(191) NOT NULL,
  `meta_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (32,'programming','Programming','Lorem ipsum dolor sit amet, hinc appetere ne his, aliquip tincidunt cum cu. Ne pro enim stet possit, et eum habemus detraxit voluptaria. Ut cum utroque ponderum liberavisse. Duo ne graecis consetetur, vis cu perfecto inciderint, nec ei minim munere. Timeam eligendi qui ei, ut mei paulo fabulas concludaturque. Ei sit illum feugiat eloquentiam, populo gubergren argumentum mea at.','2019-11-30 04:26:00','2019-12-03 04:07:09');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` bit(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Comments_Posts_idx` (`post`),
  CONSTRAINT `FK_Comments_Posts` FOREIGN KEY (`post`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,10,'','','Lorem ipsum dolor sit amet, an quem noster sea, cum ei sale adhuc eligendi, mei ad postulant torquatos. Quo cu qualisque signiferumque, everti recteque expetenda id pro, postea mandamus voluptatum ex has. Vel cu purto munere. Has ea ignota denique evertitur. Quo ne augue salutatus, ut mel aliquip aperiri nominati, erat accusam qui ne. At sea omnium expetendis, nobis munere pro ad.','','2019-12-01 09:51:44','2020-01-11 08:15:03'),(2,12,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','','2019-12-02 04:57:13','2020-01-11 08:15:57');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medias`
--

DROP TABLE IF EXISTS `medias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medias`
--

LOCK TABLES `medias` WRITE;
/*!40000 ALTER TABLE `medias` DISABLE KEYS */;
INSERT INTO `medias` VALUES (10,NULL,'1578896517SmH3uCdA.jpg','2020-01-13 06:21:57','2020-01-13 06:21:57'),(11,NULL,'1578896535e5ZUFPOI.jpg','2020-01-13 06:22:15','2020-01-13 06:22:15'),(12,'Bali','1578896555X0vnbCcK.jpg','2020-01-13 06:22:36','2020-01-13 08:08:37'),(13,NULL,'1578896689aynyljoO.jpg','2020-01-13 06:24:49','2020-01-13 06:24:49'),(14,NULL,'1578896766OYKE0VPB.jpg','2020-01-13 06:26:06','2020-01-13 06:26:06');
/*!40000 ALTER TABLE `medias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) NOT NULL DEFAULT '0',
  `order` bigint(20) DEFAULT NULL,
  `menu_id` bigint(20) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Menus_Pages_idx` (`page_id`),
  CONSTRAINT `FK_Menus_Pages` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (155,1,0,NULL,1,'2020-01-16 09:00:32','2020-01-16 09:00:32'),(156,2,0,NULL,1,'2020-01-16 09:00:32','2020-01-16 09:00:32'),(157,1,2,NULL,1,'2020-01-16 09:00:32','2020-01-16 09:00:32'),(158,3,2,NULL,1,'2020-01-16 09:00:32','2020-01-16 09:00:32'),(159,4,0,NULL,1,'2020-01-16 09:00:32','2020-01-16 09:00:32'),(160,3,0,NULL,1,'2020-01-16 09:00:32','2020-01-16 09:00:32');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, in debet quaestio eos. At congue consul efficiendi cum, vix veri detracto eu, ne graeco apeirian vix. Ut quo alterum atomorum reprehendunt, in meliore posidonium cum. Nec et zril graeco, vix eu mundi nobis convenire, ei cum rebum mundi disputando. Falli eloquentiam sea ut, eos an aliquip antiopam. Hendrerit consequuntur usu ad, vix inani error an, odio percipit quo eu.\r\n\r\nOdio simul oporteat id per, eu mel facilis partiendo. Mucius equidem quaerendum ei sea, quod inani vidisse eu pro. Cu facete audire indoctum eum, sit stet putant quodsi ne. Veritus adversarium ea est, reque corpora quo no, ius stet accusamus eu. Ut dissentiet disputationi mea, ex voluptua postulant adipiscing mea, molestie reformidans pri ea. Harum saperet postulant his ut, id suas stet congue his, ne tempor instructior usu.\r\n\r\nVeri probatus omittantur ne cum. Est maluisset disputando ei, ne per quando atomorum perpetua. Quidam virtute pro ex. Sit cu quod probo ignota. Fabellas nominati mel ut, noster prodesset eos an, quis sonet munere ne mei.','2019-11-30 11:40:58','2019-11-30 11:40:58'),(3,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, in debet quaestio eos. At congue consul efficiendi cum, vix veri detracto eu, ne graeco apeirian vix. Ut quo alterum atomorum reprehendunt, in meliore posidonium cum. Nec et zril graeco, vix eu mundi nobis convenire, ei cum rebum mundi disputando. Falli eloquentiam sea ut, eos an aliquip antiopam. Hendrerit consequuntur usu ad, vix inani error an, odio percipit quo eu.\r\n \r\n Odio simul oporteat id per, eu mel facilis partiendo. Mucius equidem quaerendum ei sea, quod inani vidisse eu pro. Cu facete audire indoctum eum, sit stet putant quodsi ne. Veritus adversarium ea est, reque corpora quo no, ius stet accusamus eu. Ut dissentiet disputationi mea, ex voluptua postulant adipiscing mea, molestie reformidans pri ea. Harum saperet postulant his ut, id suas stet congue his, ne tempor instructior usu.\r\n \r\n Veri probatus omittantur ne cum. Est maluisset disputando ei, ne per quando atomorum perpetua. Quidam virtute pro ex. Sit cu quod probo ignota. Fabellas nominati mel ut, noster prodesset eos an, quis sonet munere ne mei.','2019-11-30 11:54:26','2019-11-30 11:54:26'),(4,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, in debet quaestio eos. At congue consul efficiendi cum, vix veri detracto eu, ne graeco apeirian vix. Ut quo alterum atomorum reprehendunt, in meliore posidonium cum. Nec et zril graeco, vix eu mundi nobis convenire, ei cum rebum mundi disputando. Falli eloquentiam sea ut, eos an aliquip antiopam. Hendrerit consequuntur usu ad, vix inani error an, odio percipit quo eu.\r\n\r\nOdio simul oporteat id per, eu mel facilis partiendo. Mucius equidem quaerendum ei sea, quod inani vidisse eu pro. Cu facete audire indoctum eum, sit stet putant quodsi ne. Veritus adversarium ea est, reque corpora quo no, ius stet accusamus eu. Ut dissentiet disputationi mea, ex voluptua postulant adipiscing mea, molestie reformidans pri ea. Harum saperet postulant his ut, id suas stet congue his, ne tempor instructior usu.\r\n\r\nVeri probatus omittantur ne cum. Est maluisset disputando ei, ne per quando atomorum perpetua. Quidam virtute pro ex. Sit cu quod probo ignota. Fabellas nominati mel ut, noster prodesset eos an, quis sonet munere ne mei.','2019-11-30 12:10:31','2019-11-30 12:10:31'),(5,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, in debet quaestio eos. At congue consul efficiendi cum, vix veri detracto eu, ne graeco apeirian vix. Ut quo alterum atomorum reprehendunt, in meliore posidonium cum. Nec et zril graeco, vix eu mundi nobis convenire, ei cum rebum mundi disputando. Falli eloquentiam sea ut, eos an aliquip antiopam. Hendrerit consequuntur usu ad, vix inani error an, odio percipit quo eu.\r\n\r\nOdio simul oporteat id per, eu mel facilis partiendo. Mucius equidem quaerendum ei sea, quod inani vidisse eu pro. Cu facete audire indoctum eum, sit stet putant quodsi ne. Veritus adversarium ea est, reque corpora quo no, ius stet accusamus eu. Ut dissentiet disputationi mea, ex voluptua postulant adipiscing mea, molestie reformidans pri ea. Harum saperet postulant his ut, id suas stet congue his, ne tempor instructior usu.\r\n\r\nVeri probatus omittantur ne cum. Est maluisset disputando ei, ne per quando atomorum perpetua. Quidam virtute pro ex. Sit cu quod probo ignota. Fabellas nominati mel ut, noster prodesset eos an, quis sonet munere ne mei.','2019-11-30 12:30:02','2019-11-30 12:30:02'),(6,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, in debet quaestio eos. At congue consul efficiendi cum, vix veri detracto eu, ne graeco apeirian vix. Ut quo alterum atomorum reprehendunt, in meliore posidonium cum. Nec et zril graeco, vix eu mundi nobis convenire, ei cum rebum mundi disputando. Falli eloquentiam sea ut, eos an aliquip antiopam. Hendrerit consequuntur usu ad, vix inani error an, odio percipit quo eu.\r\n\r\nOdio simul oporteat id per, eu mel facilis partiendo. Mucius equidem quaerendum ei sea, quod inani vidisse eu pro. Cu facete audire indoctum eum, sit stet putant quodsi ne. Veritus adversarium ea est, reque corpora quo no, ius stet accusamus eu. Ut dissentiet disputationi mea, ex voluptua postulant adipiscing mea, molestie reformidans pri ea. Harum saperet postulant his ut, id suas stet congue his, ne tempor instructior usu.\r\n\r\nVeri probatus omittantur ne cum. Est maluisset disputando ei, ne per quando atomorum perpetua. Quidam virtute pro ex. Sit cu quod probo ignota. Fabellas nominati mel ut, noster prodesset eos an, quis sonet munere ne mei.','2019-11-30 12:34:12','2019-11-30 12:34:12'),(7,'Joko Wandiro','joko_wandiro@yahoo.com','afasfafa','2019-12-01 07:16:31','2019-12-01 07:16:31'),(8,'Joko Wandiro','joko_wandiro@yahoo.com','dafafasf','2019-12-01 07:19:14','2019-12-01 07:19:14'),(9,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, an quem noster sea, cum ei sale adhuc eligendi, mei ad postulant torquatos. Quo cu qualisque signiferumque, everti recteque expetenda id pro, postea mandamus voluptatum ex has. Vel cu purto munere. Has ea ignota denique evertitur. Quo ne augue salutatus, ut mel aliquip aperiri nominati, erat accusam qui ne. At sea omnium expetendis, nobis munere pro ad.','2019-12-01 11:00:35','2019-12-01 11:00:35'),(10,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:41:20','2019-12-02 03:41:20'),(11,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:42:43','2019-12-02 03:42:43'),(12,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:44:08','2019-12-02 03:44:08'),(13,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:46:12','2019-12-02 03:46:12'),(14,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:47:46','2019-12-02 03:47:46'),(15,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:48:07','2019-12-02 03:48:07'),(16,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:48:32','2019-12-02 03:48:32'),(17,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:50:29','2019-12-02 03:50:29'),(18,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:51:17','2019-12-02 03:51:17'),(19,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:51:58','2019-12-02 03:51:58'),(20,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:56:57','2019-12-02 03:56:57'),(21,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 03:58:43','2019-12-02 03:58:43'),(22,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 04:00:22','2019-12-02 04:00:22'),(23,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 04:00:48','2019-12-02 04:00:48'),(24,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 04:02:15','2019-12-02 04:02:15'),(25,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 04:02:27','2019-12-02 04:02:27'),(26,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 09:36:35','2019-12-02 09:36:35'),(27,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 09:41:46','2019-12-02 09:41:46'),(28,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 09:42:13','2019-12-02 09:42:13'),(29,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 09:44:21','2019-12-02 09:44:21'),(30,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 09:44:36','2019-12-02 09:44:36'),(31,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 09:44:47','2019-12-02 09:44:47'),(32,'Joko Wandiro','joko_wandiro@yahoo.com','Lorem ipsum dolor sit amet, pri at veri labores probatus, solet semper nec an, soleat verear id vim. An tritani recteque pro, rebum iudico platonem cu sed. Virtute gubergren at mea. Facete disputationi usu ex.','2019-12-02 09:44:51','2019-12-02 09:44:51');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) CHARACTER SET utf8 NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `url` varchar(191) DEFAULT NULL,
  `content` text,
  `id_featured_image` bigint(20) unsigned DEFAULT NULL,
  `meta_title` varchar(191) NOT NULL,
  `meta_description` text NOT NULL,
  `status` bit(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Pages_Medias_idx` (`id_featured_image`),
  CONSTRAINT `FK_Pages_Medias` FOREIGN KEY (`id_featured_image`) REFERENCES `medias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Home','home','<p>Lorem ipsum dolor sit amet, an duo regione adipisci pertinacia, atomorum persequeris no pro. At case populo democritum has. Doctus dolores constituam ne nam. Usu eu perfecto conceptam. Omnesque definitiones mei ne. Nam id mazim vidisse delenit, paulo delenit maluisset nam ad, vidit harum mundi his ea.</p>\r\n<p>Quot atqui tollit mei cu, option discere civibus eam ne, ut per tantas graece quaestio. Tritani discere te est. Ex cum quod falli iisque. Eam et causae delicata, nam an munere referrentur. Enim noster ea has, at alii quot accusam quo.</p>\r\n<p>Iusto diceret impedit vis eu, vero iudicabit id sit, erat persius laboramus cum ei. Sumo eirmod voluptaria vim an, cu ullum dicit abhorreant eam, mea eu noster efficiantur. Facilis denique et quo. Ferri prima fuisset ex pro, ut mutat possit neglegentur pro, dicunt probatus persequeris te mel. Ut usu nibh probatus, nec tritani aliquam at.</p>\r\n<p>His suscipit quaerendum eu, te his facer commune voluptatum. Ea sit inermis ceteros maluisset, utamur inermis alienum vis ne. Est ad corpora evertitur efficiantur. Per duis dicunt consulatu id, duo autem adolescens liberavisse ex. Vim ea delenit expetenda imperdiet, vim te iusto feugait.</p>\r\n<p>Posse pertinax ei vis. Ex vis possim noluisse, et eum soluta iuvaret. Vis ea commodo electram. Duo nullam definitiones ei, esse solum paulo sit no, ad his odio utinam.</p>',NULL,'Home','Lorem ipsum dolor sit amet, an duo regione adipisci pertinacia, atomorum persequeris no pro. At case populo democritum has. Doctus dolores constituam ne nam. Usu eu perfecto conceptam. Omnesque definitiones mei ne. Nam id mazim vidisse delenit, paulo delenit maluisset nam ad, vidit harum mundi his ea.','','2019-11-30 05:54:48','2019-12-03 03:09:53'),(2,'About','about','<p><img class=\"img-responsive\" title=\"Bali\" src=\"../uploads/1578896555X0vnbCcK.jpg\" alt=\"Bali\" /></p>\r\n<p>Lorem ipsum dolor sit amet, an duo regione adipisci pertinacia, atomorum persequeris no pro. At case populo democritum has. Doctus dolores constituam ne nam. Usu eu perfecto conceptam. Omnesque definitiones mei ne. Nam id mazim vidisse delenit, paulo delenit maluisset nam ad, vidit harum mundi his ea.</p>\r\n<p>Quot atqui tollit mei cu, option discere civibus eam ne, ut per tantas graece quaestio. Tritani discere te est. Ex cum quod falli iisque. Eam et causae delicata, nam an munere referrentur. Enim noster ea has, at alii quot accusam quo.</p>\r\n<p>Iusto diceret impedit vis eu, vero iudicabit id sit, erat persius laboramus cum ei. Sumo eirmod voluptaria vim an, cu ullum dicit abhorreant eam, mea eu noster efficiantur. Facilis denique et quo. Ferri prima fuisset ex pro, ut mutat possit neglegentur pro, dicunt probatus persequeris te mel. Ut usu nibh probatus, nec tritani aliquam at.</p>\r\n<p>His suscipit quaerendum eu, te his facer commune voluptatum. Ea sit inermis ceteros maluisset, utamur inermis alienum vis ne. Est ad corpora evertitur efficiantur. Per duis dicunt consulatu id, duo autem adolescens liberavisse ex. Vim ea delenit expetenda imperdiet, vim te iusto feugait.</p>\r\n<p>Posse pertinax ei vis. Ex vis possim noluisse, et eum soluta iuvaret. Vis ea commodo electram. Duo nullam definitiones ei, esse solum paulo sit no, ad his odio utinam.</p>',11,'About','Lorem ipsum dolor sit amet, an duo regione adipisci pertinacia, atomorum persequeris no pro. At case populo democritum has. Doctus dolores constituam ne nam. Usu eu perfecto conceptam. Omnesque definitiones mei ne. Nam id mazim vidisse delenit, paulo delenit maluisset nam ad, vidit harum mundi his ea.','','2019-11-30 05:55:03','2020-01-13 08:21:13'),(3,'Contact','contact','<p>[:contact-form:]</p>',NULL,'Contact','Lorem ipsum dolor sit amet, ei nec tollit consul, dico aliquid cum id. Ferri mutat assum ea pro. Mazim accumsan abhorreant no sed. Duo ei perfecto antiopam expetenda, lorem eloquentiam usu ex.','','2019-11-30 05:56:04','2019-12-03 03:15:03'),(4,'News','news','<p>[:blog:]</p>',NULL,'News','Et eam munere diceret, ei usu nonumy labore, id nec civibus petentium. Quo novum dolores at, sea ludus utamur te, pri te dicat alterum lucilius. Et semper utamur maluisset mei. Et suscipit gloriatur per, malorum patrioque sit et. Cum ut tractatos facilisis signiferumque, dictas definitiones pri id.','','2019-11-30 06:17:03','2019-12-03 03:15:18');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tags`
--

DROP TABLE IF EXISTS `post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_tags` (
  `post_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `FK_PostTags_Tags_idx` (`tag_id`),
  CONSTRAINT `FK_PostTags_Post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_PostTags_Tags` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tags`
--

LOCK TABLES `post_tags` WRITE;
/*!40000 ALTER TABLE `post_tags` DISABLE KEYS */;
INSERT INTO `post_tags` VALUES (10,1),(10,3),(11,2),(12,1);
/*!40000 ALTER TABLE `post_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `url` varchar(191) NOT NULL,
  `category` bigint(20) unsigned NOT NULL,
  `content` text,
  `id_featured_image` bigint(20) unsigned DEFAULT NULL,
  `meta_title` varchar(191) NOT NULL,
  `meta_description` text NOT NULL,
  `published_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` bit(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Posts_Categories_idx` (`category`),
  KEY `FK_Posts_Medias_idx` (`id_featured_image`),
  CONSTRAINT `FK_Posts_Categories` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_Posts_Medias` FOREIGN KEY (`id_featured_image`) REFERENCES `medias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (10,'Hello','hello',32,'<p>Lorem ipsum dolor sit amet, an duo regione adipisci pertinacia, atomorum persequeris no pro. At case populo democritum has. Doctus dolores constituam ne nam. Usu eu perfecto conceptam. Omnesque definitiones mei ne. Nam id mazim vidisse delenit, paulo delenit maluisset nam ad, vidit harum mundi his ea.</p>\r\n<p>&nbsp;</p>\r\n<p>Quot atqui tollit mei cu, option discere civibus eam ne, ut per tantas graece quaestio. Tritani discere te est. Ex cum quod falli iisque. Eam et causae delicata, nam an munere referrentur. Enim noster ea has, at alii quot accusam quo.</p>\r\n<p>&nbsp;</p>\r\n<p>Iusto diceret impedit vis eu, vero iudicabit id sit, erat persius laboramus cum ei. Sumo eirmod voluptaria vim an, cu ullum dicit abhorreant eam, mea eu noster efficiantur. Facilis denique et quo. Ferri prima fuisset ex pro, ut mutat possit neglegentur pro, dicunt probatus persequeris te mel. Ut usu nibh probatus, nec tritani aliquam at.</p>\r\n<p>&nbsp;</p>\r\n<p>His suscipit quaerendum eu, te his facer commune voluptatum. Ea sit inermis ceteros maluisset, utamur inermis alienum vis ne. Est ad corpora evertitur efficiantur. Per duis dicunt consulatu id, duo autem adolescens liberavisse ex. Vim ea delenit expetenda imperdiet, vim te iusto feugait.</p>\r\n<p>&nbsp;</p>\r\n<p>Posse pertinax ei vis. Ex vis possim noluisse, et eum soluta iuvaret. Vis ea commodo electram. Duo nullam definitiones ei, esse solum paulo sit no, ad his odio utinam.</p>',NULL,'Hello','Lorem ipsum dolor sit amet, an duo regione adipisci pertinacia, atomorum persequeris no pro. At case populo democritum has. Doctus dolores constituam ne nam. Usu eu perfecto conceptam. Omnesque definitiones mei ne. Nam id mazim vidisse delenit, paulo delenit maluisset nam ad, vidit harum mundi his ea.','2019-11-29 04:50:00','','2019-11-30 04:50:54','2020-01-13 01:55:42'),(11,'Lorem Ipsum','lorem-ipsum',32,'<p>Lorem ipsum dolor sit amet, propriae consulatu cu vel, eos dignissim omittantur at, cu vis legimus legendos. Mei an habeo assum forensibus, fastidii tincidunt eos cu. Ei ludus aperiam adversarium eam. Nec inani aliquam pertinacia at. Ne magna accumsan mel.</p>\r\n<p>His ea probo commodo. Sonet nobis concludaturque usu eu, nam te verear recusabo conclusionemque. Euripidis incorrupte te eum. Per ridens pericula eu. Ei per tamquam nusquam epicurei. Vis postea aliquip constituam an.</p>\r\n<p>Quo ne tota omittam voluptaria. Aeque dicam definitiones id cum. Eu erant petentium nec, eam prima deserunt an. Nihil placerat an ius. Vim solet dolorem in, ea commodo nominavi his. Cu nisl movet nobis ius.</p>\r\n<p>No tale sensibus interpretaris pro, est ea hinc corpora, novum nullam ullamcorper ius ea. Vel no reque dicta hendrerit. Ei ius prima audire fabellas, at graece alienum vix, has ex doctus salutandi. Sed no labitur maluisset, vis no nibh convenire.</p>\r\n<p>Ius nostro verear efficiendi eu, eam in putant luptatum scripserit, scripta pertinacia democritum mea ei. Ex eum dicant antiopam intellegam. Ut falli phaedrum expetenda vel, ius doctus menandri abhorreant in. Consequat persecuti eos ei, usu in dicant interesset, in detracto vituperatoribus pro. Vix no omnes electram, mel at ludus referrentur theophrastus. Duis omnium pri et.</p>\r\n<p>Id quo probo oporteat. Appareat molestiae conclusionemque sed at, eu ipsum nostrud ius. Augue principes contentiones id mea, ad mei primis utroque. Ferri libris sit ne, cu pro nisl dissentiunt. Quas ferri eu eam, nec ad inermis vivendum. Cu feugiat civibus mei.</p>\r\n<p>Mel te omnes vivendum, te erant ridens propriae eum, eu repudiandae intellegebat his. Ne mea mucius dolorum, ex labore iuvaret expetenda sed, est causae dolorum cu. Causae pertinax aliquando an nam. Explicari neglegentur mediocritatem usu ex. Vim ne possim nominati, ut vel quod minimum. Causae pericula eu his.</p>\r\n<p>Error everti virtute ut vix, quo dicam putant recteque id. Homero dolorum et mel. Eum cu dignissim interpretaris. Pri falli mnesarchum ullamcorper an, novum saepe graeco an nec.</p>\r\n<p>Mazim aliquando no eam. Vim malis partiendo in, eius mundi oportere ea eos, et illud saepe suscipiantur cum. Ad invidunt convenire cum. Ignota noster ocurreret an mei. Veri insolens reprehendunt no nec, ex falli volumus necessitatibus vis. Eum reprimique conclusionemque te. An ius maluisset adolescens.</p>\r\n<p>Vix aliquam feugiat et, iisque albucius recusabo has ea. Et dignissim theophrastus per, ad est posse liberavisse, tantas efficiantur vel te. His ex discere dissentiunt, atqui omnium euripidis vix ex, ei pro partem inciderint. No usu saperet senserit repudiandae, eum platonem erroribus definiebas ex. Ei sale deserunt ocurreret vel.</p>',NULL,'Lorem Ipsum','His ea probo commodo. Sonet nobis concludaturque usu eu, nam te verear recusabo conclusionemque. Euripidis incorrupte te eum. Per ridens pericula eu. Ei per tamquam nusquam epicurei. Vis postea aliquip constituam an.','2019-11-29 07:52:00','','2019-11-30 07:52:21','2020-01-13 01:55:42'),(12,'Dolor sit amet','dolor-sit-amet',32,'<p><img class=\"img-responsive\" src=\"../uploads/1578896535e5ZUFPOI.jpg\" /></p>\r\n<p><img src=\"../uploads/1578896555X0vnbCcK.jpg\" /></p>\r\n<p><img src=\"../uploads/1578896517SmH3uCdA.jpg\" /></p>\r\n<p>Lorem ipsum dolor sit amet, in debet quaestio eos. At congue consul efficiendi cum, vix veri detracto eu, ne graeco apeirian vix. Ut quo alterum atomorum reprehendunt, in meliore posidonium cum. Nec et zril graeco, vix eu mundi nobis convenire, ei cum rebum mundi disputando. Falli eloquentiam sea ut, eos an aliquip antiopam. Hendrerit consequuntur usu ad, vix inani error an, odio percipit quo eu.</p>\r\n<p>Odio simul oporteat id per, eu mel facilis partiendo. Mucius equidem quaerendum ei sea, quod inani vidisse eu pro. Cu facete audire indoctum eum, sit stet putant quodsi ne. Veritus adversarium ea est, reque corpora quo no, ius stet accusamus eu. Ut dissentiet disputationi mea, ex voluptua postulant adipiscing mea, molestie reformidans pri ea. Harum saperet postulant his ut, id suas stet congue his, ne tempor instructior usu.</p>\r\n<p>Veri probatus omittantur ne cum. Est maluisset disputando ei, ne per quando atomorum perpetua. Quidam virtute pro ex. Sit cu quod probo ignota. Fabellas nominati mel ut, noster prodesset eos an, quis sonet munere ne mei.</p>\r\n<p>Ei eos falli maiorum. Qui et quod labitur, menandri voluptatibus mei ei, minim putant mei at. Melius lobortis in eam, nihil molestie quo ei, duo ei autem utamur scribentur. Id semper admodum intellegebat vis. An sit legere alienum, eos omnes veniam ad, id sea quem eirmod. Eleifend dissentiet ut eam. Ad atomorum referrentur mel.</p>\r\n<p>Vix tantas volutpat ad, eros facer utinam mea at, his et iudico saperet repudiandae. Vidisse noluisse eu vix, maiestatis honestatis at pro. Ei mei falli nusquam legendos. Debet offendit adipiscing his ex. No omnes regione epicuri eum, usu affert putent ex. At pri harum tantas forensibus, mea odio case option ne, paulo minimum cum ut.</p>\r\n<p>Id prima augue graecis sed, affert populo vidisse et sed, eros tractatos te ius. Eum libris ponderum dissentiunt in, an viderer posidonium intellegam sed, aeque nostrud in his. No quo cibo ubique posidonium, has unum affert interesset ei, prompta disputando repudiandae ut quo. Et usu solum patrioque, pri an graecis detraxit. Pri ne doming quaerendum, eu vel soluta laoreet vituperata. Et his sonet intellegam disputando, usu id purto exerci. Eam eu solum audiam adipiscing, mei solum augue ignota in.</p>\r\n<p>Dico harum efficiantur et has. Eos ea iusto prodesset. Eam cu summo nobis suscipiantur, et est omnis interpretaris. Mutat accusam vituperatoribus mea ad, quot epicurei in eos. Hinc menandri sadipscing ei quo. An dico deterruisset mea, at fabellas expetendis mei.</p>\r\n<p>Ut hinc agam ullum pro. Sit ad vivendum inciderint, saperet deleniti nec ea, fastidii elaboraret in nec. Convenire deterruisset an mea, ignota detracto omittantur ei nec, in nemore ornatus vel. Pro alii audiam cu, illud fabulas an sed. Ne usu veri altera, an mazim vocent docendi qui, animal discere et cum. Ea eam prima vituperata.</p>\r\n<p>Qui id prompta neglegentur. Delicata praesent cotidieque eum te, ubique qualisque ut his. Ad has minimum evertitur. Tacimates posidonium has id, ad vix erant reformidans. Cum ridens accusata necessitatibus et.</p>\r\n<p>Cu semper nominati maiestatis qui. Qui te eripuit ullamcorper. Vim duis aeque zril ei, fugit meliore tibique nam ad. Per cetero nostrud ut.</p>',11,'Dolor sit amet','Lorem ipsum dolor sit amet, in debet quaestio eos. At congue consul efficiendi cum, vix veri detracto eu, ne graeco apeirian vix. Ut quo alterum atomorum reprehendunt, in meliore posidonium cum. Nec et zril graeco, vix eu mundi nobis convenire, ei cum rebum mundi disputando. Falli eloquentiam sea ut, eos an aliquip antiopam. Hendrerit consequuntur usu ad, vix inani error an, odio percipit quo eu.','2019-11-29 07:53:00','','2019-11-30 07:53:20','2020-01-13 07:59:59');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'homepage','select','1','2019-12-03 01:00:51','2020-01-13 07:19:02'),(2,'logo','file','1578889564pke54F3P.png','2019-12-26 07:37:11','2020-01-13 04:26:04'),(3,'image_size_small_width','text','200','2019-12-26 07:37:11','2020-01-13 07:19:02'),(4,'image_size_small_height','text','200','2019-12-26 07:37:11','2020-01-13 07:19:02'),(5,'image_size_medium_width','text','500','2019-12-26 07:38:26','2020-01-13 07:19:02'),(6,'image_size_medium_height','text','500','2020-01-13 03:14:06','2020-01-13 07:19:02');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `meta_title` varchar(191) NOT NULL,
  `meta_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'php','php','Per eu tale alterum conclusionemque, diam propriae omnesque an sit. Aliquando vituperatoribus id sed, id sit ludus vivendo. Ut suas graeci reprimique eos. Nec aperiam integre et, duis audiam similique cu usu. Nec causae cetero no, illum scripta offendit ex his.','2019-11-30 04:26:11','2019-12-03 04:51:00'),(2,'java','java','Debitis fierent vivendum eum in. Ad usu graeco persius tacimates. An alienum platonem dissentias vel. Sit cibo consulatu in, quo ut eleifend aliquando referrentur.','2019-11-30 04:26:17','2019-12-03 04:51:45'),(3,'html','html','At diam atqui duo. Est et solum semper. Ei homero erroribus persequeris eum, unum nullam antiopam id qui. Eos quis accumsan consequuntur ad, ea probo deserunt per. Veniam appellantur mediocritatem vel ex.','2019-11-30 04:26:23','2019-12-03 04:51:59'),(4,'css','css','Pro vidit augue te, vide habeo malorum mei an. Labores intellegat mea ea. Id mei facer mundi, qui ad aperiam persius. Ea vix iuvaret efficiantur accommodare, at congue graece tamquam duo. Populo mollis vis in, eruditi accommodare voluptatibus at mel, vitae corpora ad qui. Ex has nihil affert abhorreant, cu quo ipsum aliquid dolorem.','2019-11-30 04:26:28','2019-12-03 04:52:16'),(5,'javascript','javascript','Mel te prima solet utinam, ut paulo errem tation pri. Alterum percipit periculis ea eos, pri cu noster erroribus intellegam. Everti epicurei est et, vix ex odio idque, dicunt propriae eligendi ut sit. Has partiendo imperdiet et, et duo facilisi perpetua.','2019-11-30 04:26:42','2019-12-03 04:51:23');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-16 16:08:15
