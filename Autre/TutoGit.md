# Guide Git — Travail en équipe (Projet Régime Alimentaire)

## Objectif

Ce document explique clairement comment travailler en groupe de 3 avec Git et GitHub pour le projet :

- Régime alimentaire personnalisé
- CodeIgniter + PHP + MySQL

Le professeur regarde principalement :

- la branche `main`
- les commits réguliers
- les merges
- l'évolution du projet

Donc il faut :

✅ travailler progressivement  
✅ faire plusieurs commits  
✅ push régulièrement  
✅ merge dans `main` petit à petit  

---

# 1. Structure des branches

Le projet utilisera les branches suivantes :

```bash
main
```

```bash
develop
```

```bash
feature/eddy-auth
feature/tsinjo-regime
feature/liantsoa-wallet
```

---

# 2. Rôle de chaque branche

| Branche | Rôle |
|---|---|
| `main` | Version finale et stable du projet |
| `develop` | Branche de regroupement avant `main` |
| `feature/...` | Branche personnelle de développement |

---

# 3. Répartition des tâches

## Eddy

Travaille sur :

- Authentification
- Inscription
- Login
- Profil
- IMC
- Objectif

Branche :

```bash
feature/eddy-auth
```

---

## Tsinjo

Travaille sur :

- Régimes
- Détails aliments
- Sports
- PDF

Branche :

```bash
feature/tsinjo-regime
```

---

## Liantsoa

Travaille sur :

- Wallet
- Gold
- Admin
- Codes prépayés

Branche :

```bash
feature/liantsoa-wallet
```

---

# 4. Première installation Git

## Étape 1 — Cloner le projet

Chaque membre fait :

```bash
git clone URL_DU_PROJET
```

Exemple :

```bash
git clone https://github.com/groupe/regime-app.git
```

---

## Étape 2 — Entrer dans le dossier

```bash
cd regime-app
```

---

## Étape 3 — Créer sa branche

### Eddy

```bash
git checkout -b feature/eddy-auth
```

### Tsinjo

```bash
git checkout -b feature/tsinjo-regime
```

### Liantsoa

```bash
git checkout -b feature/liantsoa-wallet
```

---

## Étape 4 — Push la branche sur GitHub

Exemple :

```bash
git push -u origin feature/eddy-auth
```

---

# 5. Méthode de travail quotidienne

## IMPORTANT

Le professeur ne veut PAS :

❌ un seul commit à la fin  
❌ un seul push à la fin  
❌ tout envoyer le dernier jour  

Il veut voir :

✅ plusieurs commits  
✅ plusieurs push  
✅ évolution progressive du projet  

---

# 6. Quand faire un commit ?

## Règle simple

Faire un commit quand :

- une petite fonctionnalité fonctionne
- un bug est corrigé
- une vue est terminée
- un formulaire fonctionne
- une requête SQL marche

---

# 7. Exemples réels de bons commits

## Mauvais commit

```bash
git commit -m "Projet terminé"
```

---

## Bons commits

```bash
git commit -m "Ajout formulaire login"
```

```bash
git commit -m "Ajout verification mot de passe"
```

```bash
git commit -m "Ajout calcul IMC"
```

```bash
git commit -m "Correction bug session"
```

```bash
git commit -m "Ajout affichage regimes"
```

---

# 8. Quand faire un push ?

Faire un push :

- après plusieurs commits importants
- quand une fonctionnalité fonctionne
- avant d'arrêter de travailler
- avant un merge

---

# 9. Cycle complet de travail

## Exemple Eddy

### Étape 1 — Modifier les fichiers

Exemple :

- login.php
- AuthController.php
- UserModel.php

---

### Étape 2 — Vérifier les fichiers modifiés

```bash
git status
```

---

### Étape 3 — Ajouter les fichiers

```bash
git add .
```

---

### Étape 4 — Faire un commit

```bash
git commit -m "Ajout fonctionnalite login"
```

---

### Étape 5 — Push sur GitHub

```bash
git push
```

---

# 10. Avant de commencer à travailler chaque jour

Toujours faire :

```bash
git pull origin develop
```

Pourquoi ?

Pour récupérer le travail des autres membres.

---

# 11. Workflow complet recommandé

## Schéma simple

```text
feature/eddy-auth
        ↓
     develop
        ↓
      main
```

Même chose pour les autres membres.

---

# 12. Quand merge dans develop ?

Quand une fonctionnalité fonctionne correctement.

Exemple :

- login fonctionne
- wallet fonctionne
- affichage régime fonctionne

---

## Exemple de merge

### Aller dans develop

```bash
git checkout develop
```

---

### Mettre develop à jour

```bash
git pull origin develop
```

---

### Merge la branche personnelle

```bash
git merge feature/eddy-auth
```

---

### Push develop

```bash
git push origin develop
```

---

# 13. Quand merge dans main ?

Quand plusieurs fonctionnalités sont stables.

Par exemple :

✅ login fonctionne  
✅ inscription fonctionne  
✅ wallet fonctionne  
✅ affichage régime fonctionne  

---

## Exemple

```bash
git checkout main
```

```bash
git pull origin main
```

```bash
git merge develop
```

```bash
git push origin main
```

---

# 14. Exemple réel de semaine de travail

# Jour 1

## Tous

```bash
Initialisation projet
Creation base de donnees
Creation structure MVC
```

Push vers `main`

---

# Jour 2

## Eddy

```bash
Ajout login
Ajout register step1
```

---

## Tsinjo

```bash
Ajout table programmeRegime
Ajout page regimes
```

---

## Liantsoa

```bash
Ajout wallet
Ajout recharge code
```

Merge vers `develop`

---

# Jour 3

Tests généraux.

Puis :

```text
merge develop → main
```

---

# 15. Important pour éviter les conflits

## Ne pas modifier les mêmes fichiers en même temps

Exemple :

| Personne | Fichiers principaux |
|---|---|
| Eddy | AuthController.php |
| Tsinjo | ProgrammeRegimeController.php |
| Liantsoa | WalletController.php |

---

# 16. Si Git affiche un conflit

Exemple :

```text
CONFLICT (content)
```

Cela veut dire :

2 personnes ont modifié la même ligne.

Solution :

- discuter
- garder le bon code
- refaire commit

---

# 17. Convention de commits recommandée

## Eddy

```bash
[AUTH] Ajout login
```

```bash
[IMC] Calcul IMC utilisateur
```

---

## Tsinjo

```bash
[REGIME] Affichage regimes
```

```bash
[SPORT] Filtre niveau sportif
```

---

## Liantsoa

```bash
[WALLET] Recharge portefeuille
```

```bash
[ADMIN] CRUD aliments
```

---

# 18. Commandes Git essentielles

## Voir les fichiers modifiés

```bash
git status
```

---

## Ajouter tous les fichiers

```bash
git add .
```

---

## Faire un commit

```bash
git commit -m "message"
```

---

## Push

```bash
git push
```

---

## Récupérer les nouveautés

```bash
git pull
```

---

## Changer de branche

```bash
git checkout nom-branche
```

---

## Créer une branche

```bash
git checkout -b nouvelle-branche
```

---

## Merge

```bash
git merge nom-branche
```

---

# 19. Méthode ultra simple à retenir

## Chaque membre :

```text
1. git pull
2. coder
3. git add .
4. git commit
5. git push
```

---

## Ensuite :

```text
feature → develop → main
```

---

# 20. Structure finale du projet Git

```text
main
│
├── develop
│
├── feature/eddy-auth
├── feature/tsinjo-regime
└── feature/liantsoa-wallet
```

---

# 21. Conseils finaux

## IMPORTANT

✅ Commit souvent  
✅ Push souvent  
✅ Tester avant merge  
✅ Ne pas travailler directement dans main  
✅ Faire des messages de commit clairs  

---

# 22. Exemple de vraie journée de travail

## Eddy travaille sur login

```bash
git pull origin develop
```

```bash
git checkout feature/eddy-auth
```

Coder...

```bash
git add .
```

```bash
git commit -m "Ajout formulaire login"
```

Coder encore...

```bash
git add .
```

```bash
git commit -m "Ajout verification mot de passe"
```

```bash
git push
```

---

# 23. Exemple de merge final

## Le responsable Git fait :

```bash
git checkout develop
```

```bash
git merge feature/eddy-auth
```

```bash
git merge feature/tsinjo-regime
```

```bash
git merge feature/liantsoa-wallet
```

Tests...

Puis :

```bash
git checkout main
```

```bash
git merge develop
```

```bash
git push origin main
```

---

# Fin

Si vous respectez cette méthode :

✅ le professeur verra les commits réguliers  
✅ le projet sera propre  
✅ le travail d'équipe sera clair  
✅ les conflits seront réduits  
✅ la branche main restera stable

