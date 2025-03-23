drop database CsiProjet;
CREATE DATABASE CsiProjet;
use CsiProjet;
-- Création des tableau dans un premier temps
CREATE TABLE Personne (
                          id_personne INT PRIMARY KEY,
                          nom VARCHAR(100) NOT NULL,
                          prenom VARCHAR(100) NOT NULL,
                          email VARCHAR(150) UNIQUE NOT NULL,
                          telephone VARCHAR(20),
                          mot_de_passe VARCHAR(255) NOT NULL,
                          role ENUM('woofer', 'Responsable', 'participant') NOT NULL
);

CREATE TABLE Woofer (
                        idWoofer INT  PRIMARY KEY,
                        dateArrivee DATE NOT NULL,
                        dateFin DATE,
                        typeDeMission VARCHAR(100),
                        statut ENUM('Actif', 'Suspendu', 'Terminé') NOT NULL,
                        constraint check_date CHECK ( dateFin>= Woofer.dateArrivee)
);

CREATE TABLE Responsable (
                             idResponsable INT  PRIMARY KEY,
                             role ENUM('woofer', 'Responsable', 'participant') NOT NULL
);

CREATE TABLE Produit (
                         id_produit INT PRIMARY KEY,
                         nom VARCHAR(100) NOT NULL,
                         date_peremption DATE,
                         categorie VARCHAR(100),
                         quantite_stock INT,
                         unite VARCHAR(20),
                         prix_unitaire DECIMAL(10,2),
                         etat ENUM('En_stock', 'En_rupture', 'Périmé') NOT NULL
);



CREATE TABLE Atelier (
                         id INT  PRIMARY KEY,
                         nom VARCHAR(100) NOT NULL,
                         categorie VARCHAR(100),
                         date DATE NOT NULL,
                         complet BOOLEAN DEFAULT FALSE,
                         statut ENUM('Planifié', 'En_cours', 'Terminé', 'Annulé') NOT NULL,
                         nombrePlaces INT,
                         idWoofer INT,
                         id_produit INT,
                         FOREIGN KEY (id_produit) REFERENCES Produit(id_produit),
                         FOREIGN KEY (idWoofer) REFERENCES Woofer(idWoofer)
);


CREATE TABLE Participe (
                           idParticipe INT PRIMARY KEY,
                           id_personne INT NOT NULL,
                           idAtelier INT NOT NULL,
                           FOREIGN KEY (id_personne) REFERENCES Personne(id_personne),
                           FOREIGN KEY (idAtelier) REFERENCES Atelier(id)
);


CREATE TABLE Inscription (
                             id_inscription INT PRIMARY KEY,
                             nom_participant VARCHAR(100) NOT NULL,
                             email_participant VARCHAR(150) NOT NULL,
                             telephone_participant VARCHAR(20),
                             idAtelier INT NOT NULL,
                             FOREIGN KEY (idAtelier) REFERENCES Atelier(id)
);


CREATE TABLE Tache (
                       id_tache INT PRIMARY KEY,
                       description TEXT,
                       statut VARCHAR(50),
                       dateDebut DATE,
                       dateFin DATE,
                       priorite INT,
                       tacheAssignee ENUM('Jardinage', 'Elevage', 'Vente', 'Entretien'),
                       idWoofer INT,
                       FOREIGN KEY (idWoofer) REFERENCES Woofer(idWoofer)
);


CREATE TABLE Vente (
                       id_vente INT  PRIMARY KEY,
                       quantite_vendu INT,
                       date DATE,
                       montant DECIMAL(10,2),
                       idProduit INT,
                       idWoofer INT,
                       FOREIGN KEY (idProduit) REFERENCES Produit(id_produit),
                       FOREIGN KEY (idWoofer) REFERENCES Woofer(idWoofer)
);


CREATE TABLE Stock (
                       id INT PRIMARY KEY,
                       quantite INT,
                       idProduit INT,
                       FOREIGN KEY (idProduit) REFERENCES Produit(id_produit)
);


CREATE TABLE Animal (
                        id_animal INT PRIMARY KEY,
                        dateDeNaissance DATE,
                        sexe VARCHAR(10),
                        sante VARCHAR(50),
                        poids DECIMAL(10,2),
                        taille DECIMAL(10,2),
                        type ENUM('poules', 'chèvres', 'autre') NOT NULL
);


CREATE TABLE Planter (
                         id INT PRIMARY KEY,
                         nom ENUM('maïs', 'herbe', 'autre') NOT NULL,
                         quantite INT,
                         date_planation DATE,
                         date_recolte DATE,
                         unite_mesure ENUM('g', 'kg', 'tonnes') NOT NULL,
                         idStock INT,
                         FOREIGN KEY (idStock) REFERENCES Stock(id)
);
-- Inserer les données du modèle relationnel en extenion


INSERT INTO Personne (id_personne, nom, prenom, email, telephone, mot_de_passe, role)
VALUES
    (1, 'Dupont', 'Jean', 'jean.dupont@example.com', '0601020304', 'mdpJean1', 'participant'),
    (2, 'Martin', 'Claire', 'claire.martin@example.com', '0602030405', 'mdpClaire2', 'woofer'),
    (3, 'Durand', 'Luc', 'luc.durand@example.com', '0603040506', 'mdpl.uc3', 'Responsable'),
    (4, 'Bernard', 'Alice', 'alice.bernard@example.com', '0604050607', 'mdpAlice4', 'participant'),
    (5, 'Petit', 'Emma', 'emma.petit@example.com', '0605060708', 'mdpEmma5', 'woofer');


INSERT INTO Woofer (idWoofer, dateArrivee, dateFin, typeDeMission, statut)
VALUES
    (101, '2025-04-10', '2025-12-31', 'Plantation et récolte', 'Actif'),
    (102, '2023-02-05', '2023-11-30', 'Plantation et récolte', 'Suspendu'),
    (103, '2024-03-15', '2024-10-15', 'Alimentation', 'Terminé'),
    (104, '2024-04-01', '2025-09-15', 'Vente des produits', 'Suspendu'),
    (105, '2024-05-20', '2025-12-01', 'Culture et entretien des plantes', 'Actif');


ALTER TABLE Responsable MODIFY role VARCHAR(100);


INSERT INTO Responsable (idResponsable, role)
VALUES
    (201, 'Gestion des stocks'),
    (202, 'Gestion des ventes'),
    (203, 'Gestion des woofers'),
    (204, 'Gestion des ateliers'),
    (205, 'Gestion des utilisateurs');

INSERT INTO Atelier (id, nom, categorie, date, complet, statut, nombrePlaces, idWoofer)
VALUES
    (401, 'Fabrication de fromages artisanaux', 'Fromages', '2023-09-10', TRUE, 'Planifié', 20, 101),
    (402, 'Initiation à la culture biologique', 'Légumes', '2023-08-05', FALSE, 'En_cours', 25, 102),
    (403, 'Soins aux animaux de la ferme', 'Œufs et Lait', '2023-05-01', TRUE, 'Terminé', 15, 103),
    (404, 'Récolte et conservation des légumes', 'Légumes', '2023-07-20', FALSE, 'Annulé', 35, 104),
    (405, 'Récolte et conservation des légumes', 'Légumes', '2023-10-15', FALSE, 'Terminé', 30, 105);

INSERT INTO Participe (idParticipe, id_personne, idAtelier)
VALUES
    (301, 1, 401),
    (302, 4, 402),
    (303, 3, 403),
    (304, 5, 404),
    (305, 2, 405);

INSERT INTO Inscription (id_inscription, nom_participant, email_participant, telephone_participant, idAtelier)
VALUES
    (501, 'Lefèvre', 'lefevre@exemple.fr', '0611122233', 401),
    (502, 'Morel', 'morel@example.fr', '0611324455', 401),
    (503, 'Bernier', 'dernier@exemple.fr', '0611324455', 403),
    (504, 'Garnier', 'garnier@exemple.fr', '0667861091', 405),
    (505, 'Rousseau', 'rousseau@exemple.fr', '0611526677', 401);

INSERT INTO Tache (id_tache, description, statut, dateDebut, dateFin, priorite, tacheAssignee, idWoofer)
VALUES
    (601, 'Nourrir les poules, nettoyer le poulailler, collecter les œufs et vérifier la santé des animaux.', 'A faire', '2025-06-01', '2025-06-02', 8, 'Elevage', 101),
    (602, 'Arroser les plants, désherber la zone, surveiller les parasites et préparer la récolte.', 'En cours', '2024-05-15', '2025-05-16', 9, 'Jardinage', 101),
    (603, 'Préparer le matériel, guider les participants pendant l’atelier et promouvoir les fromages de la ferme.', 'Terminé', '2024-07-01', '2024-07-02', 6, 'Entretien', 103),
    (604, 'Enregistrer les quantités de lait produites, vérifier les stocks et signaler les besoins en conservation.', 'En cours', '2024-06-10', '2025-06-12', 7, 'Vente', 105),
    (605, 'Orienter les clients, présenter les produits et enregistrer les ventes dans l’application.', 'En cours', '2024-07-15', '2025-07-16', 7, 'Vente', 104);

INSERT INTO Produit (id_produit, nom, date_peremption, categorie, quantite_stock, unite, prix_unitaire, etat)
VALUES
    (801, 'Œufs frais', '2026-04-15', 'Produits d''élevage', 120, 'Douzaine', 3.50, 'En_stock'),
    (802, 'Lait cru de chèvre', '2025-06-25', 'Produits laitiers', 50, 'Litre', 2.00, 'En_rupture'),
    (803, 'Fromage de chèvre', '2025-06-01', 'Produits transformés', 30, 'Unité', 8.00, 'En_stock'),
    (804, 'Savon au lait de chèvre', '2025-06-01', 'Produits dérivés', 45, 'Unité', 5.50, 'Périmé'),
    (805, 'Légumes biologiques', '2025-05-30', 'Produits dérivés', 80, 'Kilogramme', 2.80, 'Périmé');

INSERT INTO Vente (id_vente, quantite_vendu, date, montant, idProduit, idWoofer)
VALUES
    (701, 30, '2023-06-05', 120.00, 801, 101),
    (702, 50, '2023-06-06', 400.00, 802, 101),
    (703, 10, '2023-06-05', 120.00, 803, 103),
    (704, 12, '2023-06-06', 95.00, 804, 105),
    (705, 14, '2023-06-05', 95.50, 805, 104);

INSERT INTO Stock (id, quantite, idProduit)
VALUES
    (901, 50, 801),
    (902, 200, 802),
    (903, 100, 803),
    (904, 150, 804),
    (905, 80, 805);

INSERT INTO Animal (id_animal, dateDeNaissance, sexe, sante, poids, taille, type)
VALUES
    (1001, '2021-04-15', 'F', 'Bonne', 2.5, 25, 'poules'),
    (1002, '2020-08-20', 'M', 'Bonne', 3.0, 28, 'poules'),
    (1003, '2022-01-01', 'F', 'Moyenne', 4.0, 45, 'chèvres'),
    (1004, '2022-05-01', 'M', 'Moyenne', 3.5, 40, 'chèvres'),
    (1005, '2023-09-01', 'F', 'Moyenne', 1.5, 30, 'autre');


INSERT INTO Planter (id, nom, quantite, date_planation, date_recolte, unite_mesure, idStock)
VALUES
    (1101, 'herbe', 100, '2023-04-01', '2023-09-15', 'kg', 901),
    (1102, 'maïs', 150, '2023-03-15', '2023-08-30', 'kg', 903),
    (1103, 'autre', 200, '2023-05-01', '2023-10-01', 'g', 901),
    (1104, 'maïs', 120, '2024-04-20', '2024-08-20', 'kg', 904),
    (1105, 'herbe', 130, '2024-02-10', '2024-07-10', 'kg', 905);


-- commencer les tests et les requetes


-- Trigger pour mettre à jour le stock
DELIMITER //
CREATE TRIGGER after_insert_vente
    AFTER INSERT ON Vente
    FOR EACH ROW
BEGIN
    UPDATE Stock
    SET quantite = quantite - NEW.quantite_vendu
    WHERE idProduit = NEW.idProduit;
END; //
DELIMITER ;



-- trigger pour contrôler les inscriptions
DELIMITER //
DROP TRIGGER IF EXISTS before_insert_inscription;
CREATE TRIGGER before_insert_inscription
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
END; //
DELIMITER ;

-- des requetes
SELECT * FROM Stock WHERE idProduit = 801;


-- index
CREATE INDEX idx_vente_date ON Vente(date);
CREATE INDEX idx_atelier_date ON Atelier(date);

-- Tous les woofers actifs
SELECT * FROM Woofer WHERE statut = 'Actif';

-- Produits périmés
SELECT * FROM Produit WHERE etat = 'Périmé';

-- Ateliers terminés avec leur woofer associé
SELECT A.nom, W.typeDeMission
FROM Atelier A
         JOIN Woofer W ON A.idWoofer = W.idWoofer
WHERE A.statut = 'Terminé';

-- Test dateFin < dateArrivee le contrainte ne marche pas avec CHECK donc j'ai utiliser un trigger
DELIMITER //
CREATE TRIGGER before_insert_woofer
    BEFORE INSERT ON Woofer
    FOR EACH ROW
BEGIN
    IF NEW.dateFin < NEW.dateArrivee THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Erreur : dateFin doit être >= dateArrivee';
    END IF;
END; //
DELIMITER ;

-- Test dateFin < dateArrivee (doit échouer)
INSERT INTO Woofer (idWoofer, dateArrivee, dateFin, typeDeMission, statut)
VALUES (106, '2023-12-01', '2023-11-01', 'Test', 'Actif');

-- Test rôle invalide (doit échouer)
INSERT INTO Personne (id_personne, nom, prenom, email, telephone, mot_de_passe, role)
VALUES (6, 'Test', 'Test', 'test@test.com', '0612345678', 'mdp', 'admin');

-- Avant vente
SELECT quantite FROM Stock WHERE idProduit = 801; -- 50

-- Après vente
INSERT INTO Vente (id_vente, quantite_vendu, date, montant, idProduit, idWoofer)
VALUES (706, 10, '2023-06-07', 35.00, 801, 101);

SELECT quantite FROM Stock WHERE idProduit = 801; -- Doit afficher 40

-- Test inscription à atelier complet (doit échouer)
INSERT INTO Inscription (id_inscription, nom_participant, email_participant, telephone_participant, idAtelier)
VALUES (506, 'Test', 'test@test.com', '0612345678', 401);

-- Participants à l'atelier "Fabrication de fromages"
SELECT P.nom, P.prenom
FROM Personne P
         JOIN Participe Pa ON P.id_personne = Pa.id_personne
         JOIN Atelier A ON Pa.idAtelier = A.id
WHERE A.nom LIKE '%fromages%';

-- Ventes associées aux produits laitiers
SELECT Pr.nom, V.quantite_vendu, V.montant
FROM Vente V
         JOIN Produit Pr ON V.idProduit = Pr.id_produit
WHERE Pr.categorie = 'Produits laitiers';


-- Vérifier que les tâches sont dans les dates de mission du woofer
SELECT T.id_tache, T.dateDebut, W.dateArrivee, W.dateFin
FROM Tache T
         JOIN Woofer W ON T.idWoofer = W.idWoofer
WHERE T.dateDebut NOT BETWEEN W.dateArrivee AND W.dateFin;

-- Stocks vs quantités vendues (doit être positif)
SELECT Pr.nom, S.quantite AS stock, SUM(V.quantite_vendu) AS vendu
FROM Stock S
         JOIN Produit Pr ON S.idProduit = Pr.id_produit
         LEFT JOIN Vente V ON S.idProduit = V.idProduit
GROUP BY Pr.nom, S.quantite
HAVING S.quantite < 0;

-- Tâches assignées à des catégories non prévues (doit retourner vide)
SELECT DISTINCT tacheAssignee FROM Tache
WHERE tacheAssignee NOT IN ('Jardinage', 'Elevage', 'Vente', 'Entretien');

-- États de produit valides (doit retourner vide)
SELECT DISTINCT etat FROM Produit
WHERE etat NOT IN ('En_stock', 'En_rupture', 'Périmé');