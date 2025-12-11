/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.5-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: reddit
-- ------------------------------------------------------
-- Server version	11.8.5-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CF675F31B` (`author_id`),
  KEY `IDX_9474526C4B89032C` (`post_id`),
  KEY `IDX_9474526CBF2AF943` (`parent_comment_id`),
  CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_9474526CBF2AF943` FOREIGN KEY (`parent_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `FK_9474526CF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `comment` VALUES
(4,'Cool comme projet !','2025-12-10 10:34:18',29,4,NULL,'1505478-69394caae8173.png');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20251121105707','2025-11-21 10:57:43',746),
('DoctrineMigrations\\Version20251204135357','2025-12-04 13:55:18',42),
('DoctrineMigrations\\Version20251205104218','2025-12-05 10:43:20',280),
('DoctrineMigrations\\Version20251208104439','2025-12-08 10:44:53',37),
('DoctrineMigrations\\Version20251208131806','2025-12-08 13:18:21',35),
('DoctrineMigrations\\Version20251210100559','2025-12-10 10:06:16',145);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `subreddit_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A8A6C8DF675F31B` (`author_id`),
  KEY `IDX_5A8A6C8D31DBE174` (`subreddit_id`),
  CONSTRAINT `FK_5A8A6C8D31DBE174` FOREIGN KEY (`subreddit_id`) REFERENCES `subreddit` (`id`),
  CONSTRAINT `FK_5A8A6C8DF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `post` VALUES
(4,'Projet reddit','Site Type Reedit : \r\nCréer un site où les utilisateurs peuvent créer des posts et les commentés. Le site doit inclure les fonctionnalités suivantes : \r\n\r\n-Les utilisateurs peuvent uploader des images ou fichier pour s\'en servir dans les commentaires.\r\n-Les utilisateurs auteurs d\'un post ou d\'un commentaire peut supprimer son post ou son commentaire.\r\n-Les administrateurs peuvent gérer les utilisateurs, supprimer des posts ou des commentaires.\r\n-Gestion des rôles : Les utilisateurs peuvent chercher un sujet post pour le trouver, les admin les voient tous.\r\n-Envoi d\'un mail lors de l\'inscription pour que l\'utilisateur soit devienne \"confirmé\" et puisse poster un post ou répondre à un commentaire.','2025-12-10 10:29:01',23,3),
(5,'Test','Test','2025-12-10 10:37:44',29,4),
(6,'Test2','Test2','2025-12-10 10:38:30',29,4),
(7,'Test3','Test3','2025-12-10 10:39:34',29,4);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `subreddit`
--

DROP TABLE IF EXISTS `subreddit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subreddit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `rules` longtext NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D84B1B125E237E06` (`name`),
  KEY `IDX_D84B1B12B03A8386` (`created_by_id`),
  CONSTRAINT `FK_D84B1B12B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subreddit`
--

LOCK TABLES `subreddit` WRITE;
/*!40000 ALTER TABLE `subreddit` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `subreddit` VALUES
(3,'TP Reddit','Création d\'un site style reddit en php symfony','- Php symfony',23,'2025-12-10 10:08:37'),
(4,'Test','Test','Test',29,'2025-12-10 10:37:18');
/*!40000 ALTER TABLE `subreddit` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `subreddit_moderators`
--

DROP TABLE IF EXISTS `subreddit_moderators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subreddit_moderators` (
  `subreddit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`subreddit_id`,`user_id`),
  KEY `IDX_9BAD11CD31DBE174` (`subreddit_id`),
  KEY `IDX_9BAD11CDA76ED395` (`user_id`),
  CONSTRAINT `FK_9BAD11CD31DBE174` FOREIGN KEY (`subreddit_id`) REFERENCES `subreddit` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_9BAD11CDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subreddit_moderators`
--

LOCK TABLES `subreddit_moderators` WRITE;
/*!40000 ALTER TABLE `subreddit_moderators` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `subreddit_moderators` VALUES
(3,23);
/*!40000 ALTER TABLE `subreddit_moderators` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `avatar_name` varchar(255) DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL,
  `activation_token` varchar(255) DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `user` VALUES
(3,'admin@reddit.fr','[\"ROLE_ADMIN\"]','$2y$13$AXc288egRNwk2TGlMjzm1ODDKABhytJ7MjWIb8P2w1kf47rHo1E2K','Admin',NULL,1,NULL,NULL,NULL,NULL),
(23,'user@gmail.com','[\"ROLE_USER\"]','$2y$13$ljzzKoP7OsdC8gmTMDefN.O6eLsD/gAL.7xH2Qqxi1nk75tAxHbeu','User',NULL,1,'b13a3366619ef3d5437dd6ff50ae435ab4aa11bb8519821b8475305ecf56c4cb',NULL,NULL,NULL),
(29,'mathis@gmail.com','[\"ROLE_USER\"]','$2y$13$35NJFNUAFTsBGGf9h.K5Ju0jjxCn98qd4SrFIiSzM3i.ESiuNOuPG','Mathis',NULL,1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A108564A76ED395` (`user_id`),
  KEY `IDX_5A1085644B89032C` (`post_id`),
  CONSTRAINT `FK_5A1085644B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_5A108564A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-12-11  9:27:05
