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
<body class="register-page">
  <script>
    window.NutriPlanRegister = {
      checkEmail: <?= json_encode(base_url('/user/check_email')) ?>,
      step1: <?= json_encode(base_url('/register/step1')) ?>,
      step2: <?= json_encode(base_url('/register/step2')) ?>
    };
  </script>
  <div class="register-shell">
    <aside class="register-aside register-aside-step2">
      <div class="register-brand">🌿 NutriPlan</div>
      <div class="register-copy">
        <h1>Presque prêt ! Vos données santé</h1>
        <p>Ces informations permettront de calculer votre IMC et de vous proposer le régime le plus adapté.</p>
      </div>
      <div class="formula-card">
        <div class="formula-label">FORMULE IMC</div>
        <strong>Poids (kg) ÷ Taille² (m)</strong>
        <p>Exemple : 70 ÷ (1.75)² = 22.9 ☑ Normal</p>
      </div>
    </aside>

    <main class="register-main">
      <div class="register-panel">
        <div class="step-indicator">
          <span class="step-label step-label-strong">Étape 2 sur 2</span>
          <a class="register-back-link" href="<?= base_url('/register/step1') ?>">Retour à l'étape 1</a>
        </div>
        <h2 class="register-title">Vos données physiques</h2>
        <p class="register-subtitle">Ces données sont confidentielles et utilisées uniquement pour votre programme</p>

        <form id="register-step2" class="register-form">
          <div class="field-grid">
            <div class="field">
              <label class="form-label">Taille *</label>
              <input class="input" type="number" step="0.01" name="taille" placeholder="1.75" required>
              <small class="form-error" data-error="taille"></small>
            </div>
            <div class="field">
              <label class="form-label">Poids *</label>
              <input class="input" type="number" step="0.1" name="poids" placeholder="70" required>
              <small class="form-error" data-error="poids"></small>
            </div>
          </div>

          <div class="imc-preview">
            <div class="imc-preview-label">VOTRE IMC CALCULÉ</div>
            <strong>22.9</strong>
            <span>☑ Poids Normal</span>
            <small>70 ÷ (1.75 × 1.75) = 22.86</small>
            <div class="imc-bars">
              <span class="bar blue"></span>
              <span class="bar green"></span>
              <span class="bar yellow"></span>
              <span class="bar red"></span>
            </div>
          </div>

          <button class="btn btn-primary register-submit" type="submit">Terminer et choisir mon objectif ✓</button>
          <small class="form-error" data-error="general"></small>
        </form>
      </div>
    </main>
  </div>

  <script src="<?= base_url('assets/js/register.js') ?>"></script>
</body>
</html>
