# 👥 2. Pour les autres membres

Chaque membre récupère le projet avec :

```bash
git clone https://github.com/Fiavy98/Regime_appli.git
cd Regime_appli
```

---

# 🌿 3. Création de la branche DEVELOP (IMPORTANT)

👉 À faire une seule fois dans le projet (par un membre ou responsable Git)

```bash
git checkout -b develop
git push -u origin develop
```

---

# 🧑‍💻 4. Travail quotidien (feature branch)

## Mise à jour avant de commencer

```bash
git pull origin main
```

ou mieux :

```bash
git pull origin develop
```

---

## Création de la branche de travail

```bash
git checkout -b feature/M2-auth
```

---

## Travail + commits progressifs

### Exemple commit 1

```bash
git add .
git commit -m "[AUTH] Ajout structure login page"
```

### Exemple commit 2

```bash
git add .
git commit -m "[AUTH] Ajout formulaire login"
```

---

## Push de la feature

```bash
git push origin feature/M2-auth
```

---

# 🔄 5. Intégration dans DEVELOP (merge)

Quand la fonctionnalité est terminée :

```bash
git checkout develop
git pull origin develop
git merge feature/M2-auth
git push origin develop
```

👉 Maintenant tout le monde peut récupérer le travail avec :

```bash
git pull origin develop
```

---

# 🧠 6. Comment les autres voient le travail

## Important

❌ `git push feature/*` ne suffit pas pour que tout le monde voie le code

✔ Il faut MERGER dans `develop`

---

## Après merge dans develop

Les autres membres font :

```bash
git pull origin develop
```

---

# 🏁 7. Livraison finale vers MAIN

Quand tout est stable :

```bash
git checkout main
git pull origin main
git merge develop
git push origin main
```

---

# 📌 8. Résumé simple

## Workflow complet

```text
feature/* → develop → main
```

---

## Commandes essentielles

### Développement

```bash
git checkout -b feature/xxx
git add .
git commit -m "message"
git push origin feature/xxx
```

---

### Intégration

```bash
git checkout develop
git merge feature/xxx
git push origin develop
```

---

### Livraison

```bash
git checkout main
git merge develop
git push origin main
```

---

# ⚠️ Règles importantes

* ❌ Ne jamais coder directement sur `main`
* ❌ Ne pas push uniquement des features sans merge
* ✔ Toujours faire des commits fréquents
* ✔ Toujours tester avant merge
* ✔ Toujours pull avant de commencer

---

# 🎯 Conclusion

Si vous respectez ce workflow :

✔ projet organisé
✔ travail visible entre membres
✔ historique propre pour le professeur
✔ zéro confusion Git
