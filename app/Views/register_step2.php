<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Inscription (2/2)</title>
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
        <a class="btn btn-outline" href="<?= base_url('/register/step1') ?>">Retour</a>
      </div>
    </header>

    <section class="section">
      <div class="step-indicator">
        <span class="step-dot"></span>
        <span class="step-dot active"></span>
        <span class="step-label">Etape 2/2</span>
      </div>

      <div class="form-card">
        <h2 class="section-title">Infos sante</h2>
        <p class="section-sub">Ajoute ta taille et ton poids.</p>

        <form id="register-step2" class="form-grid">
          <div>
            <label class="form-label">Taille (m)</label>
            <input class="input" type="number" step="0.01" name="taille" required>
            <small class="form-error" data-error="taille"></small>
          </div>
          <div>
            <label class="form-label">Poids (kg)</label>
            <input class="input" type="number" step="0.1" name="poids" required>
            <small class="form-error" data-error="poids"></small>
          </div>
          <button class="btn btn-primary" type="submit">Terminer</button>
          <small class="form-error" data-error="general"></small>
        </form>
      </div>
    </section>
  </div>

  <script src="<?= base_url('assets/js/register.js') ?>"></script>
</body>
</html>
