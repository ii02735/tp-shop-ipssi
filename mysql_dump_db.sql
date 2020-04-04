-- MySQL dump 10.13  Distrib 8.0.19, for Linux (x86_64)
--
-- Host: localhost    Database: learning
-- ------------------------------------------------------
-- Server version	8.0.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `CATEGORIE`
--

DROP TABLE IF EXISTS `CATEGORIE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `CATEGORIE` (
  `idc` int NOT NULL AUTO_INCREMENT,
  `idp` int NOT NULL,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`idc`),
  UNIQUE KEY `idp` (`idp`),
  CONSTRAINT `CATEGORIE_ibfk_1` FOREIGN KEY (`idp`) REFERENCES `PRODUITS` (`idp`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CATEGORIE`
--

LOCK TABLES `CATEGORIE` WRITE;
/*!40000 ALTER TABLE `CATEGORIE` DISABLE KEYS */;
INSERT INTO `CATEGORIE` VALUES (1,1,'Alimentation'),(6,5,'Alimentation'),(9,8,'Boisson');
/*!40000 ALTER TABLE `CATEGORIE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `INGREDIENTS`
--

DROP TABLE IF EXISTS `INGREDIENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `INGREDIENTS` (
  `idi` int NOT NULL AUTO_INCREMENT,
  `idp` int NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idi`),
  UNIQUE KEY `idp` (`idp`),
  CONSTRAINT `INGREDIENTS_ibfk_1` FOREIGN KEY (`idp`) REFERENCES `PRODUITS` (`idp`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `INGREDIENTS`
--

LOCK TABLES `INGREDIENTS` WRITE;
/*!40000 ALTER TABLE `INGREDIENTS` DISABLE KEYS */;
INSERT INTO `INGREDIENTS` VALUES (1,1,'Levure, farine, pomme'),(5,5,'Lait'),(8,8,'Pomme');
/*!40000 ALTER TABLE `INGREDIENTS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PANIER`
--

DROP TABLE IF EXISTS `PANIER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PANIER` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `idp` int NOT NULL,
  `quantite` int DEFAULT '0',
  `id_client` int NOT NULL,
  PRIMARY KEY (`id_panier`),
  KEY `idp` (`idp`),
  KEY `id_client` (`id_client`),
  CONSTRAINT `PANIER_ibfk_1` FOREIGN KEY (`idp`) REFERENCES `PRODUITS` (`idp`),
  CONSTRAINT `PANIER_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PANIER`
--

LOCK TABLES `PANIER` WRITE;
/*!40000 ALTER TABLE `PANIER` DISABLE KEYS */;
/*!40000 ALTER TABLE `PANIER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PRODUITS`
--

DROP TABLE IF EXISTS `PRODUITS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PRODUITS` (
  `idp` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix` decimal(5,2) NOT NULL,
  PRIMARY KEY (`idp`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PRODUITS`
--

LOCK TABLES `PRODUITS` WRITE;
/*!40000 ALTER TABLE `PRODUITS` DISABLE KEYS */;
INSERT INTO `PRODUITS` VALUES (1,'Tarte aux pommes','Délicieuse tarte aux pommes préparée sur place',4.20),(5,'Lait écrémé complet','Lait écrémé seulement vendu en pack de 6',5.35),(8,'Jus de pomme','Jus de pomme frais',0.86);
/*!40000 ALTER TABLE `PRODUITS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(30) NOT NULL,
  `role` enum('admin','client') NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_uindex` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (18,'yadallee','$2y$10$E1oOuzFYqHnerYefY1BCc.DL8jjYMbv.iOCI6P4krLLJdSbfI5s.u','bilaal','admin','bilaal.yadallee@gmail.com'),(24,'yadallee','$2y$10$naj6TUJlFcnG5Bct9rtkEetlGzXIFK76KFDL/7YwMmxfCY/TYmykW','Muhammad','client','m.yadallee@ecole-ipssi.net'),(25,'ADMIN','$2y$10$daAtry4/TZKMMeoUe.iouObdUQLsT68cNdBFwAZPhMSP4UvOlwHam','admin','admin','admin@shop.net'),(27,'test','$2y$10$42UgEPlfOR9MxrWJt9wWs.elEcTN4kLUDyYs1W/IiEhp36d7jPZ0e','test','client','test@shop.net');
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

-- Dump completed on 2020-03-29 21:28:42
