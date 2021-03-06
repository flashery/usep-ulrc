-- MySQL dump 10.13  Distrib 5.6.45, for Linux (x86_64)
--
-- Host: localhost    Database: urlc
-- ------------------------------------------------------
-- Server version	5.6.45

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
-- Table structure for table `bib_marc_tag`
--

DROP TABLE IF EXISTS `bib_marc_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bib_marc_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bib_id` int(11) NOT NULL,
  `marc_tag_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=447 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bib_marc_tag`
--

LOCK TABLES `bib_marc_tag` WRITE;
/*!40000 ALTER TABLE `bib_marc_tag` DISABLE KEYS */;
INSERT INTO `bib_marc_tag` VALUES (281,14,10,'2019-08-17 04:18:50','2019-08-17 04:18:50',NULL),(282,14,11,'2019-08-17 04:18:50','2019-08-17 04:18:50',NULL),(283,14,12,'2019-08-17 04:18:50','2019-08-17 04:18:50',NULL),(284,14,13,'2019-08-17 04:18:50','2019-08-17 04:18:50',NULL),(285,14,14,'2019-08-17 04:18:50','2019-08-17 04:18:50',NULL),(286,14,15,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(287,14,16,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(288,14,17,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(289,14,18,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(290,14,19,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(291,14,20,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(292,14,10,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(293,14,11,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(294,14,12,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(295,14,13,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(296,14,14,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(297,14,15,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(298,14,16,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(299,14,17,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(300,14,18,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(301,14,19,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(302,14,20,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(303,14,10,'2019-08-17 04:18:51','2019-08-17 04:18:51',NULL),(401,15,10,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(402,15,11,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(403,15,12,'2019-08-17 04:29:36','2019-08-17 04:29:36','1123123131'),(404,15,13,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(405,15,14,'2019-08-17 04:29:36','2019-08-17 04:29:36','asdadadad'),(406,15,15,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(407,15,16,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(408,15,17,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(409,15,18,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(410,15,18,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(411,15,19,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(412,15,20,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(413,15,20,'2019-08-17 04:29:36','2019-08-17 04:29:36',NULL),(436,16,10,'2019-08-17 04:30:35','2019-08-17 04:30:35',NULL),(437,16,11,'2019-08-17 04:30:35','2019-08-17 04:30:35',NULL),(438,16,12,'2019-08-17 04:30:35','2019-08-17 04:30:35','1123123123123123'),(439,16,13,'2019-08-17 04:30:35','2019-08-17 04:30:35',NULL),(440,16,14,'2019-08-17 04:30:35','2019-08-17 04:30:35','asdadasdada'),(441,16,15,'2019-08-17 04:30:35','2019-08-17 04:30:35',NULL),(442,16,16,'2019-08-17 04:30:35','2019-08-17 04:30:35',NULL),(443,16,17,'2019-08-17 04:30:35','2019-08-17 04:30:35',NULL),(444,16,18,'2019-08-17 04:30:36','2019-08-17 04:30:36',NULL),(445,16,19,'2019-08-17 04:30:36','2019-08-17 04:30:36',NULL),(446,16,20,'2019-08-17 04:30:36','2019-08-17 04:30:36',NULL);
/*!40000 ALTER TABLE `bib_marc_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bib_marc_tag_values`
--

DROP TABLE IF EXISTS `bib_marc_tag_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bib_marc_tag_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bib_marc_tag_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bib_marc_tag_values`
--

LOCK TABLES `bib_marc_tag_values` WRITE;
/*!40000 ALTER TABLE `bib_marc_tag_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `bib_marc_tag_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bib_subjects`
--

DROP TABLE IF EXISTS `bib_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bib_subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bib_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bib_subjects`
--

LOCK TABLES `bib_subjects` WRITE;
/*!40000 ALTER TABLE `bib_subjects` DISABLE KEYS */;
INSERT INTO `bib_subjects` VALUES (1,16,15,NULL,NULL),(2,16,14,NULL,NULL);
/*!40000 ALTER TABLE `bib_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bib_volumes`
--

DROP TABLE IF EXISTS `bib_volumes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bib_volumes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bib_id` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bib_volumes`
--

LOCK TABLES `bib_volumes` WRITE;
/*!40000 ALTER TABLE `bib_volumes` DISABLE KEYS */;
INSERT INTO `bib_volumes` VALUES (4,1,'1','2019-08-14 13:00:11','2019-08-14 13:00:11'),(5,2,'1','2019-08-14 13:15:19','2019-08-14 13:15:19'),(6,3,'1','2019-08-14 13:15:27','2019-08-14 13:15:27'),(7,4,'1','2019-08-14 13:15:35','2019-08-14 13:15:35'),(8,5,'1','2019-08-14 13:16:51','2019-08-14 13:16:51'),(9,6,'1','2019-08-14 13:16:57','2019-08-14 13:16:57'),(10,7,'1','2019-08-14 13:17:00','2019-08-14 13:17:00'),(11,8,'14','2019-08-14 13:17:09','2019-08-17 02:50:46'),(19,9,'2','2019-08-17 02:58:19','2019-08-17 02:58:19'),(20,9,'3','2019-08-17 02:58:19','2019-08-17 02:58:19'),(21,9,'4','2019-08-17 02:58:19','2019-08-17 02:58:19'),(22,9,'34','2019-08-17 02:58:27','2019-08-17 02:58:27'),(24,10,'a','2019-08-17 04:01:08','2019-08-17 04:01:08'),(26,16,'2','2019-08-17 04:30:16','2019-08-17 04:30:16'),(27,16,'3','2019-08-17 04:30:16','2019-08-17 04:30:16');
/*!40000 ALTER TABLE `bib_volumes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bibs`
--

DROP TABLE IF EXISTS `bibs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bibs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `views` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bibs`
--

LOCK TABLES `bibs` WRITE;
/*!40000 ALTER TABLE `bibs` DISABLE KEYS */;
INSERT INTO `bibs` VALUES (15,NULL,'2019-08-17 04:19:13','2019-08-17 04:19:13'),(16,NULL,'2019-08-17 04:29:41','2019-08-17 04:29:41');
/*!40000 ALTER TABLE `bibs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (11,21,'Business Communications and Critical Thinking',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(13,23,'Bachelor of Elementary Education',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(14,24,'Sample Course COT',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(15,25,'Sample Course SAC',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(19,20,'General Education Subjects',NULL,'2019-08-14 11:59:19','2019-08-14 11:59:19'),(20,29,'BSIT',NULL,'2019-08-14 12:00:44','2019-08-14 12:00:44'),(21,29,'BSCS',NULL,'2019-08-14 12:01:00','2019-08-14 12:01:00'),(22,29,'BLIS',NULL,'2019-08-14 12:01:14','2019-08-14 12:01:14'),(23,29,'MIT',NULL,'2019-08-14 12:01:28','2019-08-14 12:01:28'),(24,29,'MSLIS',NULL,'2019-08-14 12:01:38','2019-08-14 12:01:38');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (20,'General Subjects','https://cdn.filestackcontent.com/ZRHhETRyY7YqUl98FeAu',NULL,'2019-08-03 18:54:45','2019-08-14 11:31:27'),(26,'College of Education','https://cdn.filestackcontent.com/oJLyUJFYSIiPHRsmTVhG',NULL,'2019-08-14 11:38:46','2019-08-14 11:38:46'),(27,'College of Arts and Sciences','https://cdn.filestackcontent.com/z520KsGXSWWWsoAKeroD',NULL,'2019-08-14 11:49:31','2019-08-14 11:49:31'),(28,'College of Engineering','https://cdn.filestackcontent.com/Bf1DP6b7QjCYsM7KvJ6o',NULL,'2019-08-14 11:49:51','2019-08-14 11:49:51'),(29,'College of Info. and Computing','https://cdn.filestackcontent.com/aAQJ7FpS16sa2wCg69QI',NULL,'2019-08-14 11:50:36','2019-08-14 11:50:36'),(30,'College of Bus. and Admin.','https://cdn.filestackcontent.com/qCMWBhozTwyYqKWfYbvK',NULL,'2019-08-14 11:51:07','2019-08-14 11:51:07'),(31,'College of Technology','https://cdn.filestackcontent.com/0AwLWMuUQxSjzf3445N5',NULL,'2019-08-14 11:51:20','2019-08-14 11:51:20'),(32,'School of Applied and Econ.','https://cdn.filestackcontent.com/4TRuW9WXQZGop6Ei3pMK',NULL,'2019-08-14 11:52:18','2019-08-14 11:52:18');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marc_tags`
--

DROP TABLE IF EXISTS `marc_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marc_tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `marc_tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `non_marc_tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_as_default` tinyint(1) NOT NULL,
  `sequence_number` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marc_tags`
--

LOCK TABLES `marc_tags` WRITE;
/*!40000 ALTER TABLE `marc_tags` DISABLE KEYS */;
INSERT INTO `marc_tags` VALUES (10,'016','Accession Number',1,NULL,'2019-08-14 12:46:21','2019-08-14 12:46:21'),(11,'020','ISBN',1,NULL,'2019-08-14 12:46:31','2019-08-14 12:46:35'),(12,'082','Call Number',1,NULL,'2019-08-14 12:46:50','2019-08-14 12:46:50'),(13,'100','Personal Name Main Entry',1,NULL,'2019-08-14 12:47:24','2019-08-14 12:47:24'),(14,'245','Title Statement',1,NULL,'2019-08-14 12:47:36','2019-08-14 12:47:36'),(15,'250','Edition',1,NULL,'2019-08-14 12:47:44','2019-08-14 12:47:44'),(16,'260','Imprint',1,NULL,'2019-08-14 12:47:52','2019-08-14 12:47:52'),(17,'300','General Description',1,NULL,'2019-08-14 12:48:11','2019-08-14 12:48:11'),(18,'520','Summary',1,NULL,'2019-08-14 12:48:20','2019-08-14 12:49:13'),(19,'650','Topical Subject',1,NULL,'2019-08-14 12:48:43','2019-08-14 12:48:43'),(20,'700','Personal Name Subject Entry',1,NULL,'2019-08-14 12:49:09','2019-08-14 12:49:14');
/*!40000 ALTER TABLE `marc_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_06_12_110930_create_courses_table',2),(4,'2019_06_12_120849_create_departments_table',2),(5,'2019_06_12_123731_create_subjects_table',2),(6,'2019_06_29_022248_create_settings_tbl',3),(10,'2019_06_29_072541_create_marc_tags_tbl',4),(11,'2019_06_29_074002_create_bib_marc_tags_tbl',4),(12,'2019_06_29_074044_create_bibs_tbl',4),(13,'2019_07_13_021203_create_search_histories_table',5),(14,'2019_08_01_142039_create_table_bib_subjects',6),(15,'2019_08_07_124452_create_bib_volumes_table',7),(16,'2019_08_11_134739_create_bib_marc_tag_values',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_histories`
--

DROP TABLE IF EXISTS `search_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `search_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `keywords` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_histories`
--

LOCK TABLES `search_histories` WRITE;
/*!40000 ALTER TABLE `search_histories` DISABLE KEYS */;
INSERT INTO `search_histories` VALUES (1,1,'asadasdad','2019-07-12 18:33:36','2019-07-12 18:33:36'),(2,1,'Information','2019-07-14 01:03:29','2019-07-14 01:03:29'),(3,1,'Technology','2019-07-14 01:05:46','2019-07-14 01:05:46'),(4,1,'Technol','2019-07-14 02:04:24','2019-07-14 02:04:24'),(5,1,'Technol','2019-07-14 02:04:25','2019-07-14 02:04:25'),(6,1,'Technol','2019-07-14 02:04:26','2019-07-14 02:04:26'),(7,1,'Technol','2019-07-14 02:04:27','2019-07-14 02:04:27'),(8,1,'Technol','2019-07-14 02:04:27','2019-07-14 02:04:27'),(9,1,'Technol','2019-07-14 02:04:27','2019-07-14 02:04:27'),(10,1,'Tech','2019-07-14 02:04:29','2019-07-14 02:04:29'),(11,1,'Te','2019-07-14 02:04:31','2019-07-14 02:04:31'),(12,1,'Te','2019-07-14 02:04:32','2019-07-14 02:04:32'),(13,1,'Te','2019-07-14 02:04:32','2019-07-14 02:04:32'),(14,1,'Te','2019-07-14 02:04:32','2019-07-14 02:04:32'),(15,1,'asdasd','2019-07-14 02:22:47','2019-07-14 02:22:47'),(16,1,'tech','2019-07-14 04:16:54','2019-07-14 04:16:54'),(17,1,'Ullam vitae quibusdam','2019-07-31 04:11:59','2019-07-31 04:11:59'),(18,3,'IT managers','2019-08-14 12:57:49','2019-08-14 12:57:49');
/*!40000 ALTER TABLE `search_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_welcome` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `about_content` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (7,21,11,'1112','Subject CBA',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(8,22,12,'1112','Subject CAS',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(9,23,13,'1112','Subject COED',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(10,24,14,'1112','Subject COT',NULL,'2019-08-03 18:54:45','2019-08-03 18:54:45'),(11,29,22,'LIS111','Intro. to LIS',NULL,'2019-08-14 12:07:47','2019-08-14 12:07:47'),(12,20,19,'GE111','Purposive Communication',NULL,'2019-08-14 12:08:41','2019-08-14 12:08:41'),(14,29,20,'CS 111','Programming 1',NULL,'2019-08-14 12:11:29','2019-08-14 12:11:29'),(15,29,20,'IT 121','IT Fundamentals 2',NULL,'2019-08-14 12:11:50','2019-08-14 12:11:50'),(16,29,20,'CS 210','Programming 3',NULL,'2019-08-14 12:12:13','2019-08-14 12:12:13'),(17,29,20,'IT 210','IT Fundamentals 3',NULL,'2019-08-14 12:12:34','2019-08-14 12:12:34'),(18,29,20,'CS 212','Data Structures',NULL,'2019-08-14 12:12:53','2019-08-14 12:12:53'),(19,29,20,'IT 320','Project Management',NULL,'2019-08-14 12:13:49','2019-08-14 12:13:49'),(20,29,20,'IT 321','Software Management',NULL,'2019-08-14 12:14:06','2019-08-14 12:14:06'),(21,29,20,'IT 322','Web-Based Programming',NULL,'2019-08-14 12:14:28','2019-08-14 12:14:28'),(22,29,20,'IT 323','Network Technologies',NULL,'2019-08-14 12:15:16','2019-08-14 12:15:16'),(23,29,20,'IT 324','Multimedia Design and Dev.',NULL,'2019-08-14 12:16:26','2019-08-14 12:16:26'),(24,29,20,'IT 110','IT Fundamentals 1',NULL,'2019-08-14 13:16:33','2019-08-14 13:16:33'),(25,20,19,'teasd','asdasd','adasd','2019-08-17 04:35:52','2019-08-17 04:35:52');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `gender` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Yves Gonzaga','yves2regun@gmail.com',NULL,'Male',1,'asdasdasdadas','https://cdn.filestackcontent.com/soRTqEKlQJaoGaSv5rGL','$2y$10$VeThvN3fgrKnhDU2YUv74uGqlb3CiV05cQwU8MCtMUqLlAEJUEqAe',NULL,'2019-06-11 23:53:53','2019-06-23 23:24:55'),(2,'Eula','eula.nabong@usep.edu.ph',NULL,NULL,NULL,NULL,NULL,'$2y$10$YRd/mAOtvF/jSCz0.dW70OQdXDNDw0gAXjvChSCDFtad6u6AdtxbS',NULL,'2019-06-24 00:33:49','2019-06-24 00:33:49'),(3,'Annacel Delima','delima.annacel@yahoo.com',NULL,NULL,NULL,NULL,NULL,'$2y$10$s7GVtFG8QzDA6XW.O1VQb.Kw0vegN2maREa.GGBa872sT1CxT//aO',NULL,'2019-08-13 14:15:42','2019-08-13 14:15:42');
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

-- Dump completed on 2019-08-17 20:37:40
