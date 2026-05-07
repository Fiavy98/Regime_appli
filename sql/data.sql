INSERT INTO user (name, genre, age, email, psswd, role) VALUES
('Rakoto Aina', 'Homme', 25, 'aina@gmail.com', 'pass123', 'user'),
('Rasoanaivo Fara', 'Femme', 22, 'fara@gmail.com', 'pass123', 'user'),
('Andry Solo', 'Homme', 30, 'andry@gmail.com', 'pass123', 'admin'),
('Hery Tiana', 'Homme', 28, 'hery@gmail.com', 'pass123', 'user'),
('Lala Kely', 'Femme', 20, 'lala@gmail.com', 'pass123', 'visiteur');

INSERT INTO objectif (id, name) VALUES
(1, 'Augmenter poids'),
(2, 'Reduire poids'),
(3, 'IMC ideal');

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
