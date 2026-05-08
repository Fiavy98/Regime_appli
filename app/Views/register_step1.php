<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Inscription (1/2)</title>
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
      <div class="step-indicator">
        <span class="step-dot active"></span>
        <span class="step-dot"></span>
        <span class="step-label">Etape 1/2</span>
      </div>

      <div class="form-card">
        <h2 class="section-title">Creer un compte</h2>
        <p class="section-sub">Renseigne ton identite pour commencer.</p>

        <form id="register-step1" class="form-grid">
          <div>
            <label class="form-label">Nom complet</label>
            <input class="input" type="text" name="name" required>
            <small class="form-error" data-error="name"></small>
          </div>
          <div>
            <label class="form-label">Email</label>
            <input class="input" type="email" name="email" required>
            <small class="form-error" data-error="email"></small>
          </div>
          <div>
            <label class="form-label">Genre</label>
            <select class="input" name="genre" required>
              <option value="">Selectionner</option>
              <option value="Homme">Homme</option>
              <option value="Femme">Femme</option>
            </select>
            <small class="form-error" data-error="genre"></small>
          </div>
          <div>
            <label class="form-label">Age</label>
            <input class="input" type="number" name="age" min="5" max="120" required>
            <small class="form-error" data-error="age"></small>
          </div>
          <div>
            <label class="form-label">Mot de passe</label>
            <input class="input" type="password" name="password" minlength="8" required>
            <small class="form-error" data-error="password"></small>
          </div>
          <div>
            <label class="form-label">Confirmer le mot de passe</label>
            <input class="input" type="password" name="password_confirm" minlength="8" required>
            <small class="form-error" data-error="password_confirm"></small>
          </div>
          <button class="btn btn-primary" type="submit">Suivant</button>
        </form>
      </div>
    </section>
  </div>

  <script src="<?= base_url('assets/js/register.js') ?>"></script>
</body>
</html>
