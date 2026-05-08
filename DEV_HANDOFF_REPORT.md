# NutriPlan - Rapport de passation

Date : 2026-05-09

## Objectif De Cette Branche

Livrer une application NutriPlan exploitable de bout en bout avec :
- une page d'accueil one-page
- un parcours d'inscription en 2 étapes
- une page de connexion
- un dashboard utilisateur
- la sélection d'objectif
- un dashboard administrateur
- la gestion des régimes et des codes

Ce fichier sert de note de reprise pour les prochains développeurs.

## Ce Qui Est Déjà Fait

### Site Public

- La page d'accueil a été refaite en one-page.
- Le bouton `Commencer gratuitement` mène vers l'étape 1 d'inscription.
- La page `Login` a été remise au même niveau visuel que le reste du projet.
- Les pages publiques `Regimes` et `Sports` ont été harmonisées.
- Un calculateur IMC public a été ajouté sur la page d'accueil.
- Les partials `header` et `footer` publics ont été centralisés.

### Parcours D'Inscription

- Les étapes 1 et 2 sont maintenant en split-screen.
- L'étape 1 récupère les informations d'identité.
- L'étape 2 récupère la taille et le poids, puis calcule l'IMC.
- Les endpoints JS sont injectés depuis PHP, ce qui rend le parcours compatible avec `/index.php/...`.
- Des liens de retour entre les deux étapes ont été ajoutés.

### Espace Utilisateur

- La sidebar utilisateur a été centralisée.
- L'email affiché dans la sidebar vient maintenant de la session.
- Le badge utilisateur est dynamique (`Gold` ou `Standard`).
- La page profil n'est plus statique :
  - l'IMC est calculé à partir des données utilisateur
  - la progression s'appuie sur le `userProgramme` actif
  - le nombre d'aliments par repas est dérivé de `programmeAliment`
  - les régimes actifs et terminés sont calculés depuis la base
- La page d'objectif a été refaite avec des cartes sélectionnables.
- Les pages régimes et sports utilisateur sont filtrées selon l'objectif choisi.

### Espace Administrateur

- La sidebar administrateur a été centralisée.
- Le dashboard admin est dynamique :
  - utilisateurs
  - revenus
  - abonnés Gold
  - régimes vendus
  - graphiques mensuels
  - graphique par objectif
- La page admin des régimes permet :
  - de sélectionner un programme
  - de créer un programme
  - d'ajouter une composition programme/aliment
  - de voir le détail du programme courant
- La page admin des codes a été remplacée par un tableau compact.
- La table des codes propose :
  - recherche
  - pagination
  - édition inline via le formulaire de gauche
  - suppression

### Base De Données / Seeds

- Les données de seed incluent maintenant `programmeAliment`.
- C'est indispensable pour afficher la composition des régimes.

## Ce Qui Fonctionne Aujourd'hui

- `/` affiche la nouvelle page d'accueil.
- `/login` fonctionne avec le nouveau design.
- `/register/step1` et `/register/step2` fonctionnent comme un vrai parcours en 2 étapes.
- `/objectif` met à jour `userBody.id_objectif`.
- `/dashboard` affiche des données utilisateur dynamiques.
- `/dashboard/regimes` affiche un régime avec sa composition lorsque les seeds sont chargés.
- `/dashboard/sports` filtre les activités selon l'objectif.
- `/admin` affiche un dashboard dynamique.
- `/admin/regimes` permet de créer des programmes et des compositions.
- `/admin/codes` permet de créer, modifier, rechercher, paginer et supprimer les codes.

## Revue De Maintenabilité

### Points Positifs

- La duplication a été réduite grâce aux partials partagés :
  - header/footer publics
  - sidebar utilisateur
  - sidebar administrateur
- Les routes sont regroupées de manière lisible dans `app/Config/Routes.php`.
- La majorité des écrans sont maintenant alimentés par les données réelles.
- Les styles principaux sont centralisés dans `public.css` et `admin.css`.

### Points À Améliorer Pour Une Maintenabilité Forte

- `UserController` reste trop centralisé. Il regroupe encore :
  - la logique métier
  - les requêtes SQL
  - la préparation des données pour les vues
  - le parcours d'inscription
  - le calcul IMC
  - l'achat de régime
  - les sports
- `AdminController` est également trop dense. Il couvre plusieurs sous-domaines qui mériteraient d'être séparés pour rendre le code plus lisible et plus simple à tester.
- Certaines vues contiennent encore de la logique PHP directement dans le HTML. Cela fonctionne, mais cela rend les écrans plus difficiles à maintenir et à faire évoluer.
- Certains écrans embarquent encore du JavaScript inline dans les vues. Pour une base propre à long terme, ce code devrait être déplacé dans des fichiers JS dédiés.
- Plusieurs règles métier restent implicites ou distribuées dans plusieurs fichiers :
  - l'état actif ou inactif d'un régime dépend de `userProgramme`
  - la sélection d'objectif n'active pas automatiquement un régime
- Le projet dépend encore des seeds pour afficher correctement certaines compositions. Sans `programmeAliment`, les blocs de repas restent vides.
- Certaines fonctionnalités doivent encore être vérifiées dans un navigateur réel après import complet de la base de données, notamment les formulaires, les filtres et les actions d'administration.

### Conclusion Maintenabilité

- Le projet est maintenant **cohérent, lisible et exploitable**.
- La maintenabilité est **satisfaisante pour une livraison fonctionnelle**.
- Elle n’est **pas encore au niveau idéal** pour une évolution longue sans dette technique.
- La priorité suivante doit être de **découper les gros contrôleurs**, **extraire les règles métiers répétées** dans des services dédiés, et **sortir la logique de rendu** des vues quand elle devient trop importante.

## TODO Pour La Suite

### Priorité 1

- Vérifier l'application après import de `sql/script.sql` et `sql/data.sql`.
- Confirmer que `programmeAliment` est bien présent dans la base cible.
- Tester `/dashboard/regimes` avec un vrai utilisateur ayant un `userProgramme` actif.
- Tester la mise à jour des codes sur `/admin/codes`.
- Vérifier le fonctionnement avec rewrite URL et avec `/index.php/...`.

### Priorité 2

- Découper `UserController` en sous-services ou en contrôleurs plus petits.
- Découper `AdminController` en classes plus ciblées.
- Extraire les calculs récurrents du dashboard dans des services réutilisables.
- Déplacer le JavaScript inline dans des fichiers dédiés.
- Réduire la duplication des layouts dans les vues utilisateur et administrateur.

### Priorité 3

- Ajouter des tests pour :
  - l'inscription étape 1
  - l'inscription étape 2
  - la sélection d'objectif
  - l'achat d'un régime
  - la création / modification / suppression des codes
- Ajouter une vraie page d'administration des utilisateurs si elle est nécessaire.
- Prévoir une option dédiée si on veut lancer un régime sans achat.

## Dépendances De Données

Les tables importantes pour l'interface actuelle sont :

- `user`
- `userBody`
- `objectif`
- `programmeRegime`
- `programmeAliment`
- `aliment`
- `achatRegime`
- `achatRegimeDetail`
- `userProgramme`
- `userGold`
- `userPortefeuile`
- `Code`
- `activiteSportive`
- `sports`

## Notes D'Exploitation

- L'utilisateur connecté est stocké en session avec :
  - `id_user`
  - `role`
  - `nom`
  - `email`
- L'activation réelle d'un régime se fait à l'achat, pas au choix de l'objectif.
- L'IMC du profil est calculé à partir du dernier enregistrement `userBody`.
- Le calculateur IMC de la page d'accueil est côté navigateur uniquement.

## Fichiers À Reprendre En Premier

- `app/Controllers/UserController.php`
- `app/Controllers/AdminController.php`
- `app/Views/admin/codes.php`
- `app/Views/admin/regimes.php`
- `app/Views/profil.php`
- `public/assets/css/public.css`
- `public/assets/css/admin.css`

## Checklist De Démarrage Rapide

1. Importer `sql/script.sql`.
2. Importer `sql/data.sql`.
3. Lancer `php spark serve`.
4. Ouvrir :
   - `/`
   - `/login`
   - `/register/step1`
   - `/objectif`
   - `/dashboard`
   - `/dashboard/regimes`
   - `/admin`
   - `/admin/regimes`
   - `/admin/codes`

## Note Finale

L'application est suffisamment propre pour être transmise. La prochaine étape logique consiste surtout à découper les gros contrôleurs et à extraire la logique répétée pour améliorer durablement la maintenabilité.
