-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: csiprojet
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `animal`
--

DROP TABLE IF EXISTS `animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `animal` (
  `id_animal` int(11) NOT NULL AUTO_INCREMENT,
  `dateDeNaissance` date DEFAULT NULL,
  `sexe` varchar(10) DEFAULT NULL,
  `sante` varchar(50) DEFAULT NULL,
  `poids` decimal(10,2) DEFAULT NULL,
  `taille` decimal(10,2) DEFAULT NULL,
  `type` enum('poules','chèvres','autre') NOT NULL,
  PRIMARY KEY (`id_animal`),
  KEY `idx_animal_type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1006 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animal`
--

LOCK TABLES `animal` WRITE;
/*!40000 ALTER TABLE `animal` DISABLE KEYS */;
INSERT INTO `animal` VALUES (1001,'2021-04-15','F','Bonne',2.50,25.00,'poules'),(1002,'2020-08-20','M','Bonne',3.00,28.00,'poules'),(1003,'2022-01-01','F','Moyenne',4.00,45.00,'chèvres'),(1004,'2022-05-01','M','Moyenne',3.50,40.00,'chèvres'),(1005,'2023-09-01','F','Moyenne',1.50,30.00,'autre');
/*!40000 ALTER TABLE `animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `atelier`
--

DROP TABLE IF EXISTS `atelier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `atelier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `complet` tinyint(1) DEFAULT 0,
  `statut` enum('Planifié','En_cours','Terminé','Annulé') NOT NULL,
  `nombrePlaces` int(11) DEFAULT NULL,
  `idWoofer` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_produit` (`id_produit`),
  KEY `idWoofer` (`idWoofer`),
  KEY `idx_atelier_date` (`date`),
  KEY `idx_atelier_statut` (`statut`),
  CONSTRAINT `atelier_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  CONSTRAINT `atelier_ibfk_2` FOREIGN KEY (`idWoofer`) REFERENCES `woofer` (`idWoofer`)
) ENGINE=InnoDB AUTO_INCREMENT=408 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atelier`
--

LOCK TABLES `atelier` WRITE;
/*!40000 ALTER TABLE `atelier` DISABLE KEYS */;
INSERT INTO `atelier` VALUES (401,'Fabrication de fromages artisanaux','Fromages','2023-09-10',1,'Planifié',20,101,NULL),(402,'Initiation à la culture biologique','Légumes','2023-08-05',0,'En_cours',25,102,NULL),(403,'Soins aux animaux de la ferme','Œufs et Lait','2023-05-01',1,'Terminé',15,103,NULL),(404,'Récolte et conservation des légumes','Légumes','2023-07-20',0,'Annulé',35,104,NULL),(405,'Récolte et conservation des légumes','Légumes','2023-10-15',0,'Terminé',30,105,NULL),(406,'Élevage de moutons','Élevage','2025-04-05',0,'Planifié',15,6,NULL),(407,'Élevage de moutons','Élevage','2025-03-29',0,'Planifié',10,8,NULL);
/*!40000 ALTER TABLE `atelier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inscription` (
  `id_inscription` int(11) NOT NULL AUTO_INCREMENT,
  `nom_participant` varchar(100) NOT NULL,
  `email_participant` varchar(150) NOT NULL,
  `telephone_participant` varchar(20) DEFAULT NULL,
  `idAtelier` int(11) NOT NULL,
  PRIMARY KEY (`id_inscription`),
  KEY `idx_inscription_atelier` (`idAtelier`),
  CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`idAtelier`) REFERENCES `atelier` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=507 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscription`
--

LOCK TABLES `inscription` WRITE;
/*!40000 ALTER TABLE `inscription` DISABLE KEYS */;
INSERT INTO `inscription` VALUES (501,'Lefèvre','lefevre@exemple.fr','0611122233',401),(502,'Morel','morel@example.fr','0611324455',401),(503,'Bernier','dernier@exemple.fr','0611324455',403),(504,'Garnier','garnier@exemple.fr','0667861091',405),(505,'Rousseau','rousseau@exemple.fr','0611526677',401),(506,'Ibrahima Mbaye','ibrahima@gmail.com','12345666',406);
/*!40000 ALTER TABLE `inscription` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER before_insert_inscription
    BEFORE INSERT ON Inscription
    FOR EACH ROW
BEGIN
    DECLARE places_restantes INT;

    -- Jointure avec Atelier pour récupérer nombrePlaces
    SELECT a.nombrePlaces - COUNT(i.idAtelier)
    INTO places_restantes
    FROM Atelier a
             LEFT JOIN Inscription i ON a.id = i.idAtelier
    WHERE a.id = NEW.idAtelier
    GROUP BY a.id;

    IF places_restantes <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Atelier complet';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `participe`
--

DROP TABLE IF EXISTS `participe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participe` (
  `idParticipe` int(11) NOT NULL AUTO_INCREMENT,
  `id_personne` int(11) NOT NULL,
  `idAtelier` int(11) NOT NULL,
  PRIMARY KEY (`idParticipe`),
  KEY `idx_participe_personne` (`id_personne`),
  KEY `idx_participe_atelier` (`idAtelier`),
  CONSTRAINT `participe_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`),
  CONSTRAINT `participe_ibfk_2` FOREIGN KEY (`idAtelier`) REFERENCES `atelier` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=306 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participe`
--

LOCK TABLES `participe` WRITE;
/*!40000 ALTER TABLE `participe` DISABLE KEYS */;
INSERT INTO `participe` VALUES (301,1,401),(302,4,402),(303,3,403),(304,5,404),(305,2,405);
/*!40000 ALTER TABLE `participe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personne`
--

DROP TABLE IF EXISTS `personne`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personne` (
  `id_personne` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('woofer','Responsable','participant') NOT NULL,
  PRIMARY KEY (`id_personne`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personne`
--

LOCK TABLES `personne` WRITE;
/*!40000 ALTER TABLE `personne` DISABLE KEYS */;
INSERT INTO `personne` VALUES (1,'Dupont','Jean','jean.dupont@example.com','0601020304','mdpJean1','participant'),(2,'Martin','Claire','claire.martin@example.com','0602030405','mdpClaire2','woofer'),(3,'Durand','Luc','luc.durand@example.com','0603040506','mdpl.uc3','Responsable'),(4,'Bernard','Alice','alice.bernard@example.com','0604050607','mdpAlice4','participant'),(5,'Petit','Emma','emma.petit@example.com','0605060708','mdpEmma5','woofer'),(6,'Mbaye','Rassoul','rassoul@gmail.com','12345678','','woofer'),(8,'Diop','Moussa','moussa@gmail.com','12345677','','woofer'),(9,'Dupont','Jean','Jean@gmail.com','12344321','','woofer'),(10,'Dupont','François','fd@gmail.com','12346789','','woofer'),(11,'Le vrai','Jean','jeanlv@gmail.com','12346743','','woofer');
/*!40000 ALTER TABLE `personne` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planter`
--

DROP TABLE IF EXISTS `planter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` enum('maïs','herbe','autre') NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  `date_planation` date DEFAULT NULL,
  `date_recolte` date DEFAULT NULL,
  `unite_mesure` enum('g','kg','tonnes') NOT NULL,
  `idStock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_planter_dates` (`date_planation`,`date_recolte`),
  KEY `idx_planter_stock` (`idStock`),
  CONSTRAINT `planter_ibfk_1` FOREIGN KEY (`idStock`) REFERENCES `stock` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planter`
--

LOCK TABLES `planter` WRITE;
/*!40000 ALTER TABLE `planter` DISABLE KEYS */;
INSERT INTO `planter` VALUES (1101,'herbe',100,'2023-04-01','2023-09-15','kg',901),(1102,'maïs',150,'2023-03-15','2023-08-30','kg',903),(1103,'autre',200,'2023-05-01','2023-10-01','g',901),(1104,'maïs',120,'2024-04-20','2024-08-20','kg',904),(1105,'herbe',130,'2024-02-10','2024-07-10','kg',905);
/*!40000 ALTER TABLE `planter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `date_peremption` date DEFAULT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `quantite_stock` int(11) DEFAULT NULL,
  `unite` varchar(20) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `etat` enum('En_stock','En_rupture','Périmé') NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `idx_produit_etat` (`etat`)
) ENGINE=InnoDB AUTO_INCREMENT=809 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produit`
--

LOCK TABLES `produit` WRITE;
/*!40000 ALTER TABLE `produit` DISABLE KEYS */;
INSERT INTO `produit` VALUES (801,'Œufs frais','2026-04-15','Produits d\'élevage',120,'Douzaine',3.50,'En_stock'),(802,'Lait cru de chèvre','2025-06-25','Produits laitiers',50,'Litre',2.00,'En_rupture'),(803,'Fromage de chèvre','2025-06-01','Produits transformés',29,'Unité',8.00,'En_stock'),(804,'Savon au lait de chèvre','2025-06-01','Produits dérivés',45,'Unité',5.50,'Périmé'),(805,'Légumes biologiques','2025-05-30','Produits dérivés',80,'Kilogramme',2.80,'Périmé'),(806,'Fromage','2025-03-30','Produits laitiers',26,'Kilogramme',10.00,'En_stock'),(807,'Beurre','2025-03-30','Produits laitiers',7,'Unité',3.00,'En_stock'),(808,'Huile','2025-03-30','Produits transformés',98,'Kilogramme',5.00,'En_stock');
/*!40000 ALTER TABLE `produit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responsable`
--

DROP TABLE IF EXISTS `responsable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `responsable` (
  `idResponsable` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idResponsable`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responsable`
--

LOCK TABLES `responsable` WRITE;
/*!40000 ALTER TABLE `responsable` DISABLE KEYS */;
INSERT INTO `responsable` VALUES (201,'Gestion des stocks'),(202,'Gestion des ventes'),(203,'Gestion des woofers'),(204,'Gestion des ateliers'),(205,'Gestion des utilisateurs');
/*!40000 ALTER TABLE `responsable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantite` int(11) DEFAULT NULL,
  `idProduit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_stock_produit` (`idProduit`),
  CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=909 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` VALUES (901,50,801),(902,200,802),(903,29,803),(904,150,804),(905,80,805),(906,26,806),(907,7,807),(908,98,808);
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tache`
--

DROP TABLE IF EXISTS `tache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tache` (
  `id_tache` int(11) NOT NULL AUTO_INCREMENT,
  `description` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `priorite` int(11) DEFAULT NULL,
  `tacheAssignee` enum('Jardinage','Elevage','Vente','Entretien') DEFAULT NULL,
  `idWoofer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tache`),
  KEY `idx_tache_dates` (`dateDebut`,`dateFin`),
  KEY `idx_tache_statut` (`statut`),
  KEY `idx_tache_woofer` (`idWoofer`),
  CONSTRAINT `tache_ibfk_1` FOREIGN KEY (`idWoofer`) REFERENCES `woofer` (`idWoofer`)
) ENGINE=InnoDB AUTO_INCREMENT=606 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tache`
--

LOCK TABLES `tache` WRITE;
/*!40000 ALTER TABLE `tache` DISABLE KEYS */;
INSERT INTO `tache` VALUES (601,'Nourrir les poules, nettoyer le poulailler, collecter les œufs et vérifier la santé des animaux.','A faire','2025-06-01','2025-06-02',8,'Elevage',101),(602,'Arroser les plants, désherber la zone, surveiller les parasites et préparer la récolte.','En cours','2024-05-15','2025-05-16',9,'Jardinage',101),(603,'Préparer le matériel, guider les participants pendant l\'atelier et promouvoir les fromages de la ferme.','Terminé','2024-07-01','2024-07-02',6,'Entretien',103),(604,'Enregistrer les quantités de lait produites, vérifier les stocks et signaler les besoins en conservation.','En cours','2024-06-10','2025-06-12',7,'Vente',105),(605,'Orienter les clients, présenter les produits et enregistrer les ventes dans l\'application.','En cours','2024-07-15','2025-07-16',7,'Vente',104);
/*!40000 ALTER TABLE `tache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vente`
--

DROP TABLE IF EXISTS `vente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vente` (
  `id_vente` int(11) NOT NULL AUTO_INCREMENT,
  `quantite_vendu` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `idProduit` int(11) DEFAULT NULL,
  `idWoofer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_vente`),
  KEY `idWoofer` (`idWoofer`),
  KEY `idx_vente_date` (`date`),
  KEY `idx_vente_produit` (`idProduit`),
  CONSTRAINT `vente_ibfk_1` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`id_produit`),
  CONSTRAINT `vente_ibfk_2` FOREIGN KEY (`idWoofer`) REFERENCES `woofer` (`idWoofer`)
) ENGINE=InnoDB AUTO_INCREMENT=720 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vente`
--

LOCK TABLES `vente` WRITE;
/*!40000 ALTER TABLE `vente` DISABLE KEYS */;
INSERT INTO `vente` VALUES (701,30,'2023-06-05',120.00,801,101),(702,50,'2023-06-06',400.00,802,101),(703,10,'2023-06-05',120.00,803,103),(704,12,'2023-06-06',95.00,804,105),(705,14,'2023-06-05',95.50,805,104),(706,3,'2025-03-24',30.00,806,NULL),(707,7,'2025-03-24',70.00,806,NULL),(708,2,'2025-03-24',20.00,806,NULL),(709,5,'2025-03-24',50.00,806,NULL),(710,1,'2025-03-24',8.00,803,NULL),(711,1,'2025-03-24',10.00,806,NULL),(712,2,'2025-03-24',20.00,806,NULL),(713,1,'2025-03-24',3.00,807,NULL),(714,1,'2025-03-24',3.00,807,NULL),(715,2,'2025-03-24',20.00,806,NULL),(716,1,'2025-03-24',10.00,806,NULL),(717,1,'2025-03-24',3.00,807,NULL),(718,2,'2025-03-24',10.00,808,NULL),(719,1,'2025-03-24',10.00,806,NULL);
/*!40000 ALTER TABLE `vente` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER after_insert_vente
    AFTER INSERT ON Vente
    FOR EACH ROW
BEGIN
    UPDATE Stock
    SET quantite = quantite - NEW.quantite_vendu
    WHERE idProduit = NEW.idProduit;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `woofer`
--

DROP TABLE IF EXISTS `woofer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `woofer` (
  `idWoofer` int(11) NOT NULL AUTO_INCREMENT,
  `dateArrivee` date NOT NULL,
  `dateFin` date DEFAULT NULL,
  `typeDeMission` varchar(100) DEFAULT NULL,
  `statut` enum('Actif','Suspendu','Terminé') NOT NULL,
  PRIMARY KEY (`idWoofer`),
  CONSTRAINT `check_date` CHECK (`dateFin` >= `dateArrivee`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `woofer`
--

LOCK TABLES `woofer` WRITE;
/*!40000 ALTER TABLE `woofer` DISABLE KEYS */;
INSERT INTO `woofer` VALUES (6,'2025-03-25','2025-04-30','Jardinage','Actif'),(8,'2025-03-30','2025-05-02','Vente','Actif'),(9,'2025-03-29','2025-04-26','Entretien','Actif'),(10,'2025-03-28','2025-04-04','Jardinage','Actif'),(11,'2025-03-30','2025-04-06','Jardinage','Actif'),(101,'2025-04-10','2025-12-31','Plantation et récolte','Actif'),(102,'2023-02-05','2023-11-30','Plantation et récolte','Suspendu'),(103,'2024-03-15','2024-10-15','Alimentation','Terminé'),(104,'2024-04-01','2025-09-15','Vente des produits','Suspendu'),(105,'2024-05-20','2025-12-01','Culture et entretien des plantes','Actif');
/*!40000 ALTER TABLE `woofer` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER before_insert_woofer
    BEFORE INSERT ON Woofer
    FOR EACH ROW
BEGIN
    IF NEW.dateFin < NEW.dateArrivee THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Erreur : dateFin doit être >= dateArrivee';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-24  5:21:39
