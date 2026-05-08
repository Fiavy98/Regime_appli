<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Login</title>
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
        <a class="btn btn-outline" href="<?= base_url('/') ?>">Retour</a>
      </div>
    </header>

    <div class="login-wrap">
      <h2>Connexion</h2>
      <p>Acces utilisateur ou admin.</p>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="error"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('/login') ?>">
        <input class="input" type="email" name="email" placeholder="Email" required>
        <input class="input" type="password" name="password" placeholder="Mot de passe" required>
        <button class="btn btn-primary" type="submit">Se connecter</button>
      </form>
    </div>
  </div>
</body>
</html>
