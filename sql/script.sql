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