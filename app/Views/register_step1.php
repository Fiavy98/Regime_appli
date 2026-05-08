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
<body class="register-page">
  <script>
    window.NutriPlanRegister = {
      checkEmail: <?= json_encode(base_url('/user/check_email')) ?>,
      step1: <?= json_encode(base_url('/register/step1')) ?>,
      step2: <?= json_encode(base_url('/register/step2')) ?>
    };
  </script>
  <div class="register-shell">
    <aside class="register-aside register-aside-step1">
      <div class="register-brand">🌿 NutriPlan</div>
      <div class="register-copy">
        <h1>Bienvenue dans votre parcours santé</h1>
        <p>Créez votre profil en 2 étapes rapides et accédez à votre programme personnalisé.</p>
      </div>
      <div class="register-features">
        <div><span>📊</span> Calcul automatique de votre IMC</div>
        <div><span>🥗</span> Régimes adaptés à votre profil</div>
        <div><span>🏃</span> Activités sportives suggérées</div>
        <div><span>📄</span> Export PDF de votre programme</div>
      </div>
    </aside>

    <main class="register-main">
      <div class="register-panel">
        <div class="step-indicator">
          <span class="step-label step-label-strong">Étape 1 sur 2</span>
          <a class="register-back-link" href="<?= base_url('/') ?>">Retour à l'accueil</a>
        </div>
        <h2 class="register-title">Vos informations personnelles</h2>
        <p class="register-subtitle">Commençons par faire connaissance avec vous</p>

        <form id="register-step1" class="register-form">
          <div class="field-grid">
            <div class="field">
              <label class="form-label">Nom complet *</label>
              <input class="input" type="text" name="name" placeholder="Jean Dupont" required>
              <small class="form-error" data-error="name"></small>
            </div>
            <div class="field">
              <label class="form-label">Âge *</label>
              <input class="input" type="number" name="age" min="5" max="120" placeholder="25" required>
              <small class="form-error" data-error="age"></small>
            </div>
          </div>

          <div class="field">
            <label class="form-label">Adresse email *</label>
            <input class="input" type="email" name="email" placeholder="jean@exemple.com" required>
            <small class="form-note">Vérification de disponibilité en temps réel</small>
            <small class="form-error" data-error="email"></small>
          </div>

          <div class="field">
            <label class="form-label">Genre *</label>
            <div class="gender-grid">
              <label class="gender-card">
                <input type="radio" name="genre" value="Homme" required>
                <span>👨</span>
                <strong>Homme</strong>
              </label>
              <label class="gender-card">
                <input type="radio" name="genre" value="Femme" required>
                <span>👩</span>
                <strong>Femme</strong>
              </label>
            </div>
            <small class="form-error" data-error="genre"></small>
          </div>

          <div class="field">
            <label class="form-label">Mot de passe *</label>
            <input class="input" type="password" name="password" placeholder="Minimum 8 caractères" minlength="8" required>
            <small class="form-error" data-error="password"></small>
          </div>

          <div class="field">
            <label class="form-label">Confirmer le mot de passe *</label>
            <input class="input" type="password" name="password_confirm" placeholder="Répétez le mot de passe" minlength="8" required>
            <small class="form-error" data-error="password_confirm"></small>
          </div>

          <button class="btn btn-primary register-submit" type="submit">Continuer →</button>
        </form>
      </div>
    </main>
  </div>

  <script src="<?= base_url('assets/js/register.js') ?>"></script>
</body>
</html>
