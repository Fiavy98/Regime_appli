<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Accueil</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
</head>
<body>
  <div class="page-wrap">
    <header class="topbar">
      <div class="logo">NutriPlan</div>
      <nav class="nav-links">
        <a href="<?= base_url('/') ?>">Accueil</a>
        <a href="<?= base_url('/regimes') ?>">Regimes</a>
        <a href="<?= base_url('/sports') ?>">Sports</a>
      </nav>
      <div class="nav-actions">
        <a class="btn btn-outline" href="<?= base_url('/login') ?>">Se connecter</a>
      </div>
    </header>

    <section class="hero">
      <div>
        <div class="pill">IMC rapide, sans inscription</div>
        <h1 class="hero-title">Ton plan, ton rythme, ton <span>equilibre</span></h1>
        <p class="hero-desc">
          Calcule ton IMC et decouvre un apercu des regimes et activites adaptes.
          Inscris-toi pour des recommandations personnalisees.
        </p>
        <div class="nav-actions">
          <a class="btn btn-primary" href="<?= base_url('/login') ?>">Se connecter</a>
          <a class="btn btn-outline" href="<?= base_url('/register/step1') ?>">S'inscrire</a>
        </div>
      </div>
      <div class="hero-card">
        <h3>Calcul IMC</h3>
        <form class="imc-form" data-imc-form>
          <input class="input" type="number" step="0.01" name="taille" placeholder="Taille (m)" required>
          <input class="input" type="number" step="0.1" name="poids" placeholder="Poids (kg)" required>
          <button class="btn btn-primary" type="submit">Calculer</button>
        </form>
        <div class="imc-result">
          <strong data-imc-result>---</strong>
          <span data-imc-status>---</span>
        </div>
      </div>
    </section>

    <section class="section">
      <h2 class="section-title">Ce que tu peux faire en visiteur</h2>
      <p class="section-sub">Apercu public des regimes et sports disponibles.</p>
      <div class="card-grid">
        <div class="card">
          <h4>Regimes</h4>
          <small>Nom, objectif, duree, prix</small>
          <a class="btn btn-outline" href="<?= base_url('/regimes') ?>">Voir l'apercu</a>
        </div>
        <div class="card">
          <h4>Sports</h4>
          <small>Nom, niveau, calories brulees</small>
          <a class="btn btn-outline" href="<?= base_url('/sports') ?>">Voir l'apercu</a>
        </div>
        <div class="card">
          <h4>Compte</h4>
          <small>Inscription en 2 etapes et suivi IMC</small>
          <a class="btn btn-outline" href="<?= base_url('/register/step1') ?>">Commencer</a>
        </div>
      </div>
    </section>

    <footer class="footer">
      NutriPlan - Apercu public. Connecte-toi pour un tableau de bord complet.
    </footer>
  </div>

  <script src="<?= base_url('assets/js/public.js') ?>"></script>
</body>
</html>
