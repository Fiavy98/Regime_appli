# Projet Régime Alimentaire — Todo liste complète
**Application de régimes alimentaires personnalisés · CodeIgniter + PHP + MySQL**

---

## Vue d'ensemble

| | Eddy | Tsinjo | Liantsoa |
|---|---|---|---|
| **Domaine** | Auth · Profil · IMC · Objectif | Régimes · Sport · PDF | Wallet · Gold · Admin |


---

## Base de données — Toutes les tables

	- Table user 
		- id
		- name
		- genre enum (Homme,Femme)
		- age
		- email
		- psswd
		- role (admin,user,visiteur) user default
	- Table userBody
		- id
		- id_user
		- taille
		- poids
		- id_objectif
		- date
	- Table objectif
		- id not auto_increment
		- name 
	- Table userPortefeuile
		- id
		- id_user
		- montant
	- Table MvntPrortefeuile
		- id
		- id_userPortefeuile
		- type enum (credit,debit)
		- montant
		- date
	- Table Code
		- id
		- code
		- montant
		- date_expiration
		- utilise : (true/false)
	- Table statusRegime
		- id not auto_increment
		- libel (en cours,termine,annule)
	- Table categorie
		- id
		- libele
	- Table aliment : liste des alimens
		- id
		- nom
		- id_categorie 
		- calories_pour_100g
		- proteines_g
		- glucides_g
		- lipides_g
	- Table programmeRegime 
		- id
		- nom
		- id_objectif 
		- variation_poids
		- imc_min
		- imc_max
		- duree_jours
		- prix
	- Table programmeAliment : composition du régime
		- id
		- id_programmeRegime
		- id_aliment
		- quantite_g
		- type_repas
			- PETIT_DEJEUNER
			- DEJEUNER
			- DINER
			- COLLATION

	- Table userProgramme
		- id
		- id_user
		- id_programmeRegime
		- date_debut
		- date_fin
		- prix_paye
		- id_statusRegime 
	- Table : achatRegime
		- id
		- id_user
		- prix_total
		- reduction_appliquee
		- est_gold_utilise (true/false)
		- date_achat
	- Table : achatRegimeDetail
		- id
		- id_achatRegime
		- id_programmeRegime
		- prix_unitaire
	- Table sports
		- id
		- name
		- category
	- Table activiteSportive
		- id
		- id_sport
		- id_objectif
		- niveau enum (FAIBLE / MOYEN / ELEVE)
		- calories_brulees_par_heure
		- duree_minute
	- Table userSport
		- id
		- id_user
		- id_activiteSportive
		- start_date
		- end_date
		- id_statusRegime
	-Table imcHistory
		- id
		- id_user
		- poids
		- imc
		- date

	- Table userGold
		- id
		- id_user
		- prixGold
---

## Eddy — Authentification · Profil · IMC · Objectif

> Socle de l'application. Les autres membres dépendent de `id_user` en session, de l'IMC calculé et de `id_objectif` pour filtrer régimes et activités.

---

### F1 — Gestion des rôles (Visiteur / User / Admin)

**Objectif :** Contrôle d'accès selon le rôle stocké dans la session. Le visiteur voit uniquement les pages publiques. L'user accède à son dashboard. L'admin accède au back office.

**Tables utilisées :** `user` (champ `role`)

**Fichiers :** `AuthController.php` · `UserModel.php`

**Tâches :**

- Créer un middleware CodeIgniter qui vérifie `$_SESSION['role']` avant chaque contrôleur
- Page d'accueil publique avec présentation de l'application et calcul IMC rapide (sans inscription) → View : `home.php`
- Aperçu régimes pour visiteur : nom, objectif, durée, prix — sans détail des aliments → View : `regimes_preview.php`
- Aperçu activités sportives pour visiteur : nom, niveau, calories brûlées — sans programme personnalisé
- Redirection automatique après login selon le rôle (`/dashboard` pour user, `/admin` pour admin)

**Droits visiteur (non inscrit) :**
- Peut voir : page d'accueil, calcul IMC rapide, aperçu régimes, aperçu sports
- Ne peut pas : s'inscrire à un régime, acheter, accéder au wallet, voir ses données, exporter PDF, accéder aux suggestions personnalisées

**Droits user (inscrit) :**
- Peut faire : compléter profil, voir IMC + historique, choisir objectif, voir régimes personnalisés, acheter régime, utiliser portefeuille, utiliser code, voir activité sportive, exporter PDF, souscrire Gold

**Droits admin :**
- Peut faire : CRUD régimes, aliments, sports, codes, gérer prix, voir statistiques

---

### F2 — Inscription (2 étapes)

**Objectif :** Créer le compte utilisateur en deux étapes séparées. Bouton "Suivant" traité par AJAX — pas de rechargement de page. Affichage des erreurs en temps réel.

**Tables utilisées :** `user` · `userBody` · `imcHistory` · `userPortefeuile`

**Fichiers :** `UserController.php` · `UserModel.php` · `UserBodyModel.php` · `ImcHistoryModel.php`

**Views :** `register_step1.php` · `register_step2.php`

#### Étape 1 — Identité

- Formulaire : nom, email, genre (Homme/Femme), âge, mot de passe 
- Validation AJAX email unique : `POST /user/check_email` → retourne JSON `{disponible: true/false}`
- Validation mot de passe : min 8 caractères, confirmation en live
- Indicateur de progression visuel (étape 1/2)
- Stockage temporaire en session PHP avant confirmation finale

#### Étape 2 — Santé

- Formulaire : taille (m), poids (kg)
- À la validation : insertion dans `user` + `userBody` (id_user, taille, poids, date=now)
- Calcul IMC immédiat = poids ÷ taille² → insert `imcHistory` (id_user, poids, imc, date=now)
- Création portefeuille vide : insert `userPortefeuile` (id_user, montant=0)

---

### F3 — Login / Logout

**Objectif :** Authentification par email + mot de passe hashé. Démarrage de session PHP. Logout détruit la session.

**Tables utilisées :** `user` (email, psswd, role)

**Fichiers :** `AuthController.php` · `UserModel.php`

**Views :** `login.php`

**Tâches :**

- Hash bcrypt à l'inscription via `password_hash()`, vérification via `password_verify()` au login
- Démarrer session : `$_SESSION['id_user']`, `$_SESSION['role']`, `$_SESSION['nom']`
- Message d'erreur générique (ne pas révéler si c'est l'email ou le mot de passe qui est incorrect)
- Route logout : détruire session + redirection vers `home.php`

---

### F4 — Profil + Calcul IMC + Historique

**Objectif :** Permettre à l'utilisateur de voir ses informations, calculer son IMC, mettre à jour son poids et suivre l'évolution dans le temps via un graphique.

**Tables utilisées :** `user` · `userBody` · `imcHistory`

**Fichiers :** `UserController.php` · `UserModel.php` · `ImcHistoryModel.php`

**Views :** `profil.php`

**Tâches :**

- Récupérer le dernier enregistrement `userBody` de l'user (ORDER BY date DESC LIMIT 1) pour poids + taille actuels
- Calculer IMC = poids ÷ taille² et afficher l'interprétation colorée :
  - < 18.5 → Maigre
  - 18.5 – 24.9 → Normal ✅
  - 25 – 29.9 → Surpoids
  - ≥ 30 → Obésité
- Lire `imcHistory` WHERE id_user=? ORDER BY date ASC → passer en JSON à Chart.js pour le graphique d'évolution
- Formulaire mise à jour poids → recalcul IMC → insert `imcHistory` + update `userBody`

---

### F5 — Choix d'objectif

**Objectif :** L'utilisateur choisit son objectif parmi 3 options. Ce choix sert de filtre pour les suggestions de régimes (Tsinjo) et activités sportives (Tsinjo).

**Tables utilisées :** `objectif` · `userBody` (update `id_objectif`)

**Fichiers :** `ObjectifController.php` · `ObjectifModel.php` · `UserBodyModel.php`

**Views :** `objectif.php`

**Tâches :**

- Lire table `objectif` → afficher 3 cartes cliquables (prise de masse, perte de poids, IMC idéal)
- `POST /objectif/choisir` avec paramètre `id_objectif` → UPDATE `userBody` SET id_objectif=? WHERE id_user=?
- Rediriger vers la page régimes après confirmation

---

## Tsinjo — Régimes · Aliments · Sport · PDF

> Dépend du Eddy pour l'IMC et l'objectif, et du Liantsoa pour la vérification du solde avant achat.

---

### F1 — Affichage des régimes personnalisés

**Objectif :** Afficher uniquement les régimes adaptés à l'utilisateur selon son IMC et son objectif. Afficher le prix normal et le prix Gold. Permettre l'achat.

**Tables utilisées :** `programmeRegime` · `userBody` · `imcHistory` · `userGold` · `userProgramme` · `achatRegime` · `achatRegimeDetail` · `MvntPortefeuile` · `userPortefeuile`

**Fichiers :** `ProgrammeRegimeController.php` · `ProgrammeRegimeModel.php`

**Views :** `regimes.php`

#### Affichage filtré

```sql
SELECT * FROM programmeRegime
WHERE id_objectif = :id_objectif
AND imc_min <= :imc_user
AND imc_max >= :imc_user
```

- Récupérer IMC actuel depuis dernier `imcHistory` de l'user
- Récupérer `id_objectif` depuis dernier `userBody`
- Vérifier si user est Gold : `SELECT * FROM userGold WHERE id_user = :id_user`
- Si Gold : afficher prix normal + prix Gold = prix * 0.85
- Afficher par régime : nom, durée, variation_poids, prix normal, prix Gold (si applicable)

#### Achat régime

- `POST /programme/acheter` avec paramètre `id_programmeRegime`
- Vérifier solde : `userPortefeuile.montant >= prix_final`
- Si user Gold : `prix_paye = prix * 0.85`, `est_gold_utilise = true`
- INSERT `userProgramme` : id_user, id_programmeRegime, date_debut=now, date_fin=now+duree_jours, prix_paye, id_statusRegime=1 (en cours)
- INSERT `achatRegime` (id_user, prix_total, reduction_appliquee, est_gold_utilise, date_achat=now)
- INSERT `achatRegimeDetail` (id_achatRegime, id_programmeRegime, prix_unitaire)
- INSERT `MvntPortefeuile` (type=debit, montant=prix_paye, date=now)
- UPDATE `userPortefeuile` SET montant = montant - prix_paye

---

### F2 — Détail régime (composition par repas)

**Objectif :** Afficher la composition complète du régime, groupée par type de repas, avec les valeurs nutritionnelles calculées selon la quantité.

**Tables utilisées :** `programmeAliment` · `aliment` · `categorie`

**Fichiers :** `ProgrammeRegimeController.php` · `ProgrammeAlimentModel.php` · `AlimentModel.php`

**Views :** `regime_detail.php`

**Tâches :**

```sql
SELECT a.nom, a.calories_pour_100g, a.proteines_g, a.glucides_g, a.lipides_g,
       pa.quantite_g, pa.type_repas, c.libele AS categorie
FROM programmeAliment pa
JOIN aliment a ON pa.id_aliment = a.id
JOIN categorie c ON a.id_categorie = c.id
WHERE pa.id_programmeRegime = :id
ORDER BY pa.type_repas
```

- Grouper les résultats par `type_repas` en PHP
- Calculer calories réelles : `(calories_pour_100g * quantite_g) / 100`
- Afficher total nutritionnel journalier (calories, protéines, glucides, lipides)

---

### F3 — Activités sportives

**Objectif :** Suggérer des activités selon l'objectif de l'user. Permettre de démarrer une activité (insert `userSport`).

**Tables utilisées :** `activiteSportive` · `sports` · `userSport`

**Fichiers :** `SportController.php` · `ActiviteSportiveModel.php` · `SportModel.php`

**Views :** `sport.php`

**Tâches :**

```sql
SELECT s.name, s.category, a.niveau, a.calories_brulees_par_heure, a.duree_minute
FROM activiteSportive a
JOIN sports s ON a.id_sport = s.id
WHERE a.id_objectif = :id_objectif
```

- Filtre optionnel par niveau (FAIBLE / MOYEN / ÉLEVÉ) via AJAX → `GET /sport/filtrer?niveau=MOYEN`
- Bouton "Démarrer" → INSERT `userSport` (id_user, id_activiteSportive, start_date=now, id_statusRegime=1)

---

### F4 — Export PDF

**Objectif :** Générer un PDF téléchargeable contenant le régime actif et les activités sportives associées. Utiliser la librairie mPDF (via Composer).

**Tables utilisées :** `userProgramme` · `programmeAliment` · `aliment` · `userSport` · `activiteSportive` · `sports`

**Fichiers :** `PdfController.php`

**Views :** `pdf_template.php` (template HTML → converti en PDF)

**Tâches :**

- Route : `GET /pdf/export` (id_user depuis session)
- Récupérer `userProgramme` actif WHERE id_user=? AND id_statusRegime=1 LIMIT 1
- Récupérer composition du régime (même requête que F2)
- Récupérer `userSport` actif de l'user
- Template HTML → `$mpdf->WriteHTML($html)` → `$mpdf->Output('regime.pdf', 'D')`

---

## Liantsoa — Wallet · Gold · Back Office Admin

### F1 — Portefeuille (solde + historique)

**Objectif :** Afficher le solde actuel et l'historique chronologique de toutes les transactions.

**Tables utilisées :** `userPortefeuile` · `MvntPortefeuile`

**Fichiers :** `WalletController.php` · `UserPortefeuilleModel.php` · `WalletTransactionModel.php`

**Views :** `wallet.php`

**Tâches :**

- Lire `userPortefeuile` WHERE id_user=session → afficher montant actuel
- Lire `MvntPortefeuile` WHERE id_userPortefeuile=? ORDER BY date DESC → liste des transactions
- Afficher badge coloré : vert pour crédit, rouge pour débit

---

### F2 — Recharge par code prépayé

**Objectif :** Entrer un code (type carte Telma), vérifier sa validité, créditer le portefeuille. Traité en AJAX.

**Tables utilisées :** `Code` · `userPortefeuile` · `MvntPortefeuile`

**Fichiers :** `WalletController.php`

**Views :** `wallet.php` (section formulaire code)

**Tâches :**

- `POST AJAX /wallet/appliquer_code` avec paramètre `code` (string)
- Vérifier : `SELECT * FROM Code WHERE code=? AND utilise=false AND date_expiration >= NOW()`
- Si valide :
  - `UPDATE Code SET utilise=true WHERE id=?`
  - `UPDATE userPortefeuile SET montant = montant + :montant WHERE id_user=?`
  - `INSERT MvntPortefeuile (type=credit, montant=code.montant, date=now)`
- Retourner JSON : `{succes: true/false, message: "...", nouveau_solde: X}`

---

### F3 — Abonnement Gold

**Objectif :** Paiement unique pour obtenir le statut Gold. Remise 15% automatique lors des achats de régimes.

**Tables utilisées :** `userGold` · `userPortefeuile` · `MvntPortefeuile`

**Fichiers :** `WalletController.php`

**Views :** `gold.php`

**Tâches :**

- Afficher le prix Gold + avantages sur la page wallet/gold
- Bouton "Devenir Gold" → `POST /wallet/devenir_gold`
- Vérifier solde : `userPortefeuile.montant >= prixGold`
- Si ok :
  - `INSERT userGold (id_user, prixGold)`
  - `UPDATE userPortefeuile SET montant = montant - prixGold`
  - `INSERT MvntPortefeuile (type=debit, montant=prixGold)`
- Afficher badge Gold dans le profil et la navbar
- La vérification Gold est utilisée par le Tsinjo : `SELECT * FROM userGold WHERE id_user=?`

**Exemple :**

| | Sans Gold | Avec Gold |
|---|---|---|
| Régime 1 mois | 3 000 Ar | 3 000 Ar |
| Remise (-15%) | — | - 450 Ar |
| **Prix final** | **3 000 Ar** | **2 550 Ar** |

---

### F4 — Back Office Admin

**Objectif :** Dashboard statistiques + CRUD complet de toutes les données de référence.

**Fichiers :** `AdminController.php`

#### Dashboard statistiques

**Tables :** `user` · `achatRegime` · `achatRegimeDetail` · `userGold`

**Views :** `admin/dashboard.php`

- Nombre d'utilisateurs par rôle : `SELECT role, COUNT(*) FROM user GROUP BY role`
- Ventes par régime : JOIN `achatRegimeDetail` + `programmeRegime`
- Revenus totaux : `SELECT SUM(prix_total) FROM achatRegime`
- Nombre d'abonnés Gold : `SELECT COUNT(*) FROM userGold`
- Graphique mensuel inscriptions et achats (Chart.js)

#### CRUD Régimes

**Tables :** `programmeRegime` · `programmeAliment` · `aliment` · `categorie`

**Views :** `admin/regimes.php`

- CRUD `programmeRegime` : nom, id_objectif, imc_min, imc_max, duree_jours, prix, variation_poids
- CRUD composition : ajouter/modifier/supprimer aliments dans `programmeAliment` par type_repas
- CRUD `aliment` : nom, id_categorie, calories_pour_100g, proteines_g, glucides_g, lipides_g
- CRUD `categorie`

#### CRUD Activités sportives

**Tables :** `sports` · `activiteSportive`

**Views :** `admin/sports.php`

- CRUD `sports` : nom, catégorie
- CRUD `activiteSportive` : id_sport, id_objectif, niveau, calories_brulees_par_heure, duree_minute

#### Gestion codes prépayés

**Tables :** `Code`

**Views :** `admin/codes.php`

- Générer un nouveau code : code (string unique), montant, date_expiration
- Lister tous les codes avec statut (utilisé / non utilisé / expiré)
- Désactiver un code manuellement

---

## Dépendances entre membres

```
---

## Architecture CodeIgniter (MVC)

```
application/
├── controllers/
│   ├── AuthController.php       (M1)
│   ├── UserController.php       (M1)
│   ├── ObjectifController.php   (M1)
│   ├── ProgrammeRegimeController.php  (M2)
│   ├── SportController.php      (M2)
│   ├── PdfController.php        (M2)
│   ├── WalletController.php     (M3)
│   └── AdminController.php      (M3)
├── models/
│   ├── UserModel.php            (M1)
│   ├── UserBodyModel.php        (M1)
│   ├── ObjectifModel.php        (M1)
│   ├── ImcHistoryModel.php      (M1)
│   ├── ProgrammeRegimeModel.php (M2)
│   ├── ProgrammeAlimentModel.php(M2)
│   ├── AlimentModel.php         (M2)
│   ├── ActiviteSportiveModel.php(M2)
│   ├── SportModel.php           (M2)
│   ├── UserPortefeuilleModel.php(M3)
│   └── WalletTransactionModel.php(M3)
└── views/
    ├── home.php                 (M1)
    ├── login.php                (M1)
    ├── register_step1.php       (M1)
    ├── register_step2.php       (M1)
    ├── profil.php               (M1)
    ├── objectif.php             (M1)
    ├── regimes.php              (M2)
    ├── regime_detail.php        (M2)
    ├── sport.php                (M2)
    ├── pdf_template.php         (M2)
    ├── wallet.php               (M3)
    ├── gold.php                 (M3)
    └── admin/
        ├── dashboard.php        (M3)
        ├── regimes.php          (M3)
        ├── sports.php           (M3)
        └── codes.php            (M3)
```

---

