CREATE DATABASE IF NOT EXISTS regime_app;
USE regime_app;

-- =========================
-- TABLE USER
-- =========================
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    genre ENUM('Homme','Femme') NOT NULL,
    age INT,
    email VARCHAR(150) UNIQUE NOT NULL,
    psswd VARCHAR(255) NOT NULL,
    role ENUM('admin','user','visiteur') DEFAULT 'user'
);

-- =========================
-- TABLE OBJECTIF
-- =========================
CREATE TABLE objectif (
    id INT PRIMARY KEY,
    name ENUM(
        'Augmenter poids',
        'Reduire poids',
        'IMC ideal'
    ) NOT NULL
);

-- =========================
-- TABLE USER BODY
-- =========================
CREATE TABLE userBody (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    taille DECIMAL(5,2),
    poids DECIMAL(5,2),
    id_objectif INT,
    date DATE,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_objectif) REFERENCES objectif(id)
);

-- =========================
-- TABLE USER PORTEFEUILLE
-- =========================
CREATE TABLE userPortefeuile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    montant DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (id_user) REFERENCES user(id)
);

-- =========================
-- TABLE MOUVEMENT PORTEFEUILLE
-- =========================
CREATE TABLE MvntPrortefeuile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_userPortefeuile INT NOT NULL,
    type ENUM('credit','debit'),
    montant DECIMAL(10,2),
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_userPortefeuile) REFERENCES userPortefeuile(id)
);

-- =========================
-- TABLE CODE
-- =========================
CREATE TABLE Code (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) UNIQUE,
    montant DECIMAL(10,2),
    date_expiration DATE,
    utilise BOOLEAN DEFAULT FALSE
);

-- =========================
-- TABLE STATUS REGIME
-- =========================
CREATE TABLE statusRegime (
    id INT PRIMARY KEY,
    libel ENUM('en cours','termine','annule')
);

-- =========================
-- TABLE CATEGORIE
-- =========================
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libele VARCHAR(100)
);

-- =========================
-- TABLE ALIMENT
-- =========================
CREATE TABLE aliment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150),
    id_categorie INT,
    calories_pour_100g DECIMAL(6,2),
    proteines_g DECIMAL(6,2),
    glucides_g DECIMAL(6,2),
    lipides_g DECIMAL(6,2),
    FOREIGN KEY (id_categorie) REFERENCES categorie(id)
);

-- =========================
-- TABLE PROGRAMME REGIME
-- =========================
CREATE TABLE programmeRegime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150),
    id_objectif INT,
    variation_poids DECIMAL(5,2),
    imc_min DECIMAL(5,2),
    imc_max DECIMAL(5,2),
    duree_jours INT,
    prix DECIMAL(10,2),
    FOREIGN KEY (id_objectif) REFERENCES objectif(id)
);

-- =========================
-- TABLE PROGRAMME ALIMENT
-- =========================
CREATE TABLE programmeAliment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_programmeRegime INT,
    id_aliment INT,
    quantite_g DECIMAL(6,2),
    type_repas ENUM('PETIT_DEJEUNER','DEJEUNER','DINER','COLLATION'),
    FOREIGN KEY (id_programmeRegime) REFERENCES programmeRegime(id),
    FOREIGN KEY (id_aliment) REFERENCES aliment(id)
);

-- =========================
-- TABLE USER PROGRAMME
-- =========================
CREATE TABLE userProgramme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_programmeRegime INT,
    date_debut DATE,
    date_fin DATE,
    prix_paye DECIMAL(10,2),
    id_statusRegime INT,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_programmeRegime) REFERENCES programmeRegime(id),
    FOREIGN KEY (id_statusRegime) REFERENCES statusRegime(id)
);

-- =========================
-- TABLE ACHAT REGIME
-- =========================
CREATE TABLE achatRegime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    prix_total DECIMAL(10,2),
    reduction_appliquee DECIMAL(10,2),
    est_gold_utilise BOOLEAN DEFAULT FALSE,
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id)
);

-- =========================
-- TABLE ACHAT REGIME DETAIL
-- =========================
CREATE TABLE achatRegimeDetail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_achatRegime INT,
    id_programmeRegime INT,
    prix_unitaire DECIMAL(10,2),
    FOREIGN KEY (id_achatRegime) REFERENCES achatRegime(id),
    FOREIGN KEY (id_programmeRegime) REFERENCES programmeRegime(id)
);

-- =========================
-- TABLE SPORTS
-- =========================
CREATE TABLE sports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    category VARCHAR(100)
);

-- =========================
-- TABLE ACTIVITE SPORTIVE
-- =========================
CREATE TABLE activiteSportive (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_sport INT,
    id_objectif INT,
    niveau ENUM('FAIBLE','MOYEN','ELEVE'),
    calories_brulees_par_heure DECIMAL(6,2),
    duree_minute INT,
    FOREIGN KEY (id_sport) REFERENCES sports(id),
    FOREIGN KEY (id_objectif) REFERENCES objectif(id)
);

-- =========================
-- TABLE USER SPORT
-- =========================
CREATE TABLE userSport (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_activiteSportive INT,
    start_date DATE,
    end_date DATE,
    id_statusRegime INT,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_activiteSportive) REFERENCES activiteSportive(id),
    FOREIGN KEY (id_statusRegime) REFERENCES statusRegime(id)
);

-- =========================
-- TABLE IMC HISTORY
-- =========================
CREATE TABLE imcHistory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    poids DECIMAL(5,2),
    imc DECIMAL(5,2),
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id)
);

-- =========================
-- TABLE USER GOLD
-- =========================
CREATE TABLE gold (
    id INT PRIMARY KEY,
    prix DECIMAL(10,2) NOT NULL
);

CREATE TABLE userGold (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT UNIQUE,
    id_gold INT DEFAULT 1,
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_gold) REFERENCES gold(id)
);


-- DATA
INSERT INTO user (name, genre, age, email, psswd, role) VALUES
('Rakoto Aina', 'Homme', 25, 'aina@gmail.com', '$2y$10$W534zCjFlmcz8lylPjBwlu6LChYmienqhUgfgXu4ghyoVLeloSG9m', 'user'),
('Rasoanaivo Fara', 'Femme', 22, 'fara@gmail.com', '$2y$10$W534zCjFlmcz8lylPjBwlu6LChYmienqhUgfgXu4ghyoVLeloSG9m', 'user'),
('Admin Jean', 'Homme', 30, 'admin@gmail.com', '$2y$10$W534zCjFlmcz8lylPjBwlu6LChYmienqhUgfgXu4ghyoVLeloSG9m', 'admin'),
('Hery Tiana', 'Homme', 28, 'hery@gmail.com', '$2y$10$W534zCjFlmcz8lylPjBwlu6LChYmienqhUgfgXu4ghyoVLeloSG9m', 'user'),
('Lala Kely', 'Femme', 20, 'lala@gmail.com', '$2y$10$W534zCjFlmcz8lylPjBwlu6LChYmienqhUgfgXu4ghyoVLeloSG9m', 'visiteur');

INSERT INTO objectif (id, name) VALUES
(1, 'Augmenter poids'),
(2, 'Reduire poids'),
(3, 'IMC ideal');

INSERT INTO userBody (id_user, taille, poids, id_objectif, date) VALUES
(1, 1.75, 70.00, 3, '2026-05-01'),
(2, 1.60, 55.00, 3, '2026-05-01'),
(3, 1.80, 85.00, 1, '2026-05-01'),
(4, 1.78, 90.00, 2, '2026-05-01'),
(5, 1.55, 48.00, 3, '2026-05-01');

INSERT INTO statusRegime (id, libel) VALUES
(1, 'en cours'),
(2, 'termine'),
(3, 'annule');

INSERT INTO categorie (libele) VALUES
('Fruits'),
('Legumes'),
('Viandes'),
('Cereales'),
('Boissons');

INSERT INTO aliment (nom, id_categorie, calories_pour_100g, proteines_g, glucides_g, lipides_g) VALUES
('Riz blanc', 4, 130, 2.5, 28, 0.3),
('Poulet grille', 3, 165, 31, 0, 3.6),
('Banane', 1, 89, 1.1, 23, 0.3),
('Carotte', 2, 41, 0.9, 10, 0.2),
('Lait', 5, 42, 3.4, 5, 1),
('Pomme', 1, 52, 0.3, 14, 0.2),
('Pain complet', 4, 247, 13, 41, 4),
('Oeuf', 3, 155, 13, 1.1, 11),
('Poisson', 3, 206, 22, 0, 12),
('Avoine', 4, 389, 17, 66, 7);

INSERT INTO programmeRegime (nom, id_objectif, variation_poids, imc_min, imc_max, duree_jours, prix) VALUES
('Regime Minceur Express', 1, -5, 18, 25, 30, 50000),
('Prise de Masse Pro', 2, 5, 18, 28, 45, 80000),
('Maintien Equilibre', 3, 0, 18, 27, 60, 60000),
('Fitness Avance', 2, -2, 19, 26, 40, 70000),
('Sante Complete', 3, 0, 18, 30, 90, 90000);

INSERT INTO programmeAliment (id_programmeRegime, id_aliment, quantite_g, type_repas) VALUES
(1, 3, 1, 'PETIT_DEJEUNER'),
(1, 5, 250, 'PETIT_DEJEUNER'),
(1, 1, 150, 'DEJEUNER'),
(1, 2, 120, 'DEJEUNER'),
(1, 4, 100, 'DINER'),
(1, 6, 1, 'COLLATION'),
(2, 7, 120, 'PETIT_DEJEUNER'),
(2, 8, 3, 'PETIT_DEJEUNER'),
(2, 2, 180, 'DEJEUNER'),
(2, 9, 150, 'DEJEUNER'),
(2, 1, 220, 'DINER'),
(2, 5, 250, 'COLLATION'),
(3, 1, 120, 'PETIT_DEJEUNER'),
(3, 6, 1, 'PETIT_DEJEUNER'),
(3, 2, 140, 'DEJEUNER'),
(3, 4, 120, 'DEJEUNER'),
(3, 9, 130, 'DINER'),
(3, 10, 60, 'COLLATION'),
(4, 7, 100, 'PETIT_DEJEUNER'),
(4, 5, 250, 'PETIT_DEJEUNER'),
(4, 2, 160, 'DEJEUNER'),
(4, 9, 120, 'DEJEUNER'),
(4, 8, 2, 'DINER'),
(4, 3, 2, 'COLLATION'),
(5, 1, 130, 'PETIT_DEJEUNER'),
(5, 5, 300, 'PETIT_DEJEUNER'),
(5, 2, 150, 'DEJEUNER'),
(5, 4, 100, 'DEJEUNER'),
(5, 8, 2, 'DINER'),
(5, 6, 1, 'COLLATION');

INSERT INTO sports (name, category) VALUES
('Course a pied', 'Cardio'),
('Musculation', 'Force'),
('Natation', 'Cardio'),
('Cyclisme', 'Endurance'),
('Yoga', 'Bien-etre');

INSERT INTO activiteSportive (id_sport, id_objectif, niveau, calories_brulees_par_heure, duree_minute) VALUES
(1, 1, 'ELEVE', 600, 30),
(2, 2, 'MOYEN', 450, 60),
(3, 2, 'MOYEN', 500, 45),
(4, 1, 'FAIBLE', 300, 40),
(5, 3, 'FAIBLE', 200, 60);

INSERT INTO gold (id, prix) VALUES
(1, 5000);

INSERT INTO userGold (id_user, id_gold) VALUES
(1, 1),
(2, 1);

INSERT INTO userPortefeuile (id_user, montant) VALUES
(1, 10000),
(2, 20000),
(3, 50000),
(4, 15000),
(5, 8000);

INSERT INTO Code (code, montant, date_expiration, utilise) VALUES
('CODE001', 1000, '2026-12-31', FALSE),
('CODE002', 2000, '2026-12-31', FALSE),
('CODE003', 1500, '2026-12-31', FALSE),
('CODE004', 3000, '2026-12-31', FALSE),
('CODE005', 5000, '2026-12-31', FALSE),
('CODE006', 2500, '2026-12-31', FALSE),
('CODE007', 4000, '2026-12-31', FALSE),
('CODE008', 3500, '2026-12-31', FALSE),
('CODE009', 6000, '2026-12-31', FALSE),
('CODE010', 4500, '2026-12-31', FALSE),
('CODE011', 7000, '2026-12-31', FALSE),
('CODE012', 8000, '2026-12-31', FALSE),
('CODE013', 9000, '2026-12-31', FALSE),
('CODE014', 10000, '2026-12-31', FALSE),
('CODE015', 12000, '2026-12-31', FALSE);

INSERT INTO imcHistory (id_user, poids, imc) VALUES
(1, 70, 22.5),
(2, 55, 20.1),
(3, 80, 25.3),
(4, 75, 24.0),
(5, 60, 21.7);
