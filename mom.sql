-- MySQL dump 10.13  Distrib 5.1.66, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: mom
-- ------------------------------------------------------
-- Server version	5.1.66-0ubuntu0.11.10.3-log

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
-- Table structure for table `mom_current_repo`
--

DROP TABLE IF EXISTS `mom_current_repo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mom_current_repo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `code` varchar(50) NOT NULL DEFAULT '0',
  `color` varchar(30) NOT NULL DEFAULT '0',
  `size` varchar(30) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `ctime` int(11) unsigned NOT NULL,
  `ret` tinyint(4) NOT NULL DEFAULT '0',
  `base_price` int(10) unsigned NOT NULL DEFAULT '0',
  `wholesale_price` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mom_current_repo`
--

LOCK TABLES `mom_current_repo` WRITE;
/*!40000 ALTER TABLE `mom_current_repo` DISABLE KEYS */;
/*!40000 ALTER TABLE `mom_current_repo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mom_form_trace`
--

DROP TABLE IF EXISTS `mom_form_trace`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mom_form_trace` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_time` int(10) unsigned NOT NULL,
  `total_price` int(11) NOT NULL DEFAULT '0',
  `payed_price` int(11) NOT NULL DEFAULT '0',
  `last_price` int(11) NOT NULL DEFAULT '0',
  `options` varchar(21800) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mom_form_trace`
--

LOCK TABLES `mom_form_trace` WRITE;
/*!40000 ALTER TABLE `mom_form_trace` DISABLE KEYS */;
INSERT INTO `mom_form_trace` VALUES (1,22,1371803815,0,0,0,'');
/*!40000 ALTER TABLE `mom_form_trace` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mom_forms`
--

DROP TABLE IF EXISTS `mom_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mom_forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL DEFAULT '0',
  `buyer` varchar(50) NOT NULL DEFAULT '0',
  `phone` varchar(50) NOT NULL DEFAULT '0',
  `address` varchar(100) NOT NULL DEFAULT '0',
  `ctime` int(11) unsigned NOT NULL,
  `ps` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mom_forms`
--

LOCK TABLES `mom_forms` WRITE;
/*!40000 ALTER TABLE `mom_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `mom_forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mom_key_info`
--

DROP TABLE IF EXISTS `mom_key_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mom_key_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL,
  `key_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `key_real_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mom_key_info`
--

LOCK TABLES `mom_key_info` WRITE;
/*!40000 ALTER TABLE `mom_key_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `mom_key_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mom_out_products`
--

DROP TABLE IF EXISTS `mom_out_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mom_out_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repo_id` varchar(100) NOT NULL,
  `form_id` int(10) unsigned NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `ctime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mom_out_products`
--

LOCK TABLES `mom_out_products` WRITE;
/*!40000 ALTER TABLE `mom_out_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `mom_out_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mom_table_info`
--

DROP TABLE IF EXISTS `mom_table_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mom_table_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `table_real_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mom_table_info`
--

LOCK TABLES `mom_table_info` WRITE;
/*!40000 ALTER TABLE `mom_table_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `mom_table_info` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-03 21:21:09
