<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Regimes</title>
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

    <section class="section">
      <h2 class="section-title">Apercu des regimes</h2>
      <p class="section-sub">Uniquement les informations publiques (sans details des aliments).</p>
      <div class="card-grid">
        <?php foreach ($regimes as $regime): ?>
          <div class="card">
            <div class="pill"><?= esc($regime['objectif']) ?></div>
            <h4><?= esc($regime['name']) ?></h4>
            <small>Duree: <?= esc($regime['duree']) ?></small>
            <small>Prix: <?= esc($regime['prix']) ?></small>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <footer class="footer">
      Cree un compte pour voir les details et acheter un regime.
    </footer>
  </div>
</body>
</html>
