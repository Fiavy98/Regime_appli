<?php
$selectedProgramme = $programme ?? null;
$selectedMeals = $meals ?? [];
$walletAmount = (float) ($wallet['montant'] ?? 0);
$basePrice = (float) ($selectedProgramme['prix'] ?? 0);
$isPurchased = (bool) ($isPurchased ?? false);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Regime</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
</head>
<body class="user-page">
  <div class="app-shell">
    <?= view('partials/user_sidebar', [
      'active' => 'regimes',
      'userName' => session()->get('nom') ?? 'Utilisateur',
      'userEmail' => $userEmail ?? session()->get('email') ?? 'Compte actif',
      'walletAmount' => $walletAmount,
      'badgeLabel' => $badgeLabel ?? ($hasGold ? 'Gold' : 'Standard'),
    ]) ?>

    <main class="content">
      <?php if (session()->getFlashdata('profil_error')): ?>
        <div class="error"><?= esc(session()->getFlashdata('profil_error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('profil_success')): ?>
        <div class="success"><?= esc(session()->getFlashdata('profil_success')) ?></div>
      <?php endif; ?>

      <section class="regime-hero panel">
        <div>
          <span class="pill">Programme nutrition</span>
          <h2><?= esc($selectedProgramme['nom'] ?? 'Aucun programme') ?></h2>
          <p><?= esc($selectedProgramme['objectif_label'] ?? 'Objectif non renseigne') ?></p>
          <?php if (!empty($objectifLabel)): ?>
            <div class="objectif-current">Objectif actif: <strong><?= esc($objectifLabel) ?></strong></div>
          <?php endif; ?>
          <div class="regime-tags">
            <span class="tag">IMC <?= esc($selectedProgramme['imc_min'] ?? '--') ?> - <?= esc($selectedProgramme['imc_max'] ?? '--') ?></span>
            <span class="tag"><?= esc($selectedProgramme['duree_jours'] ?? '--') ?> jours</span>
            <span class="tag">Variation <?= esc($selectedProgramme['variation_poids'] ?? '--') ?> kg</span>
          </div>
        </div>

        <form method="get" action="<?= base_url('/dashboard/regimes') ?>" class="regime-switcher">
          <label class="form-label" for="program-select">Changer de programme</label>
          <select id="program-select" class="input" name="id" onchange="this.form.submit()">
            <?php foreach ($programmes as $item): ?>
              <option value="<?= esc($item['id']) ?>" <?= $selectedProgramme && (int) $item['id'] === (int) $selectedProgramme['id'] ? 'selected' : '' ?>>
                <?= esc($item['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
      </section>

      <section class="user-cards">
        <div class="card">
          <span>Prix normal</span>
          <strong><?= number_format($basePrice, 0, ',', ' ') ?> Ar</strong>
          <small>Prix public du programme</small>
        </div>
        <div class="card">
          <span>Prix Gold</span>
          <strong><?= $hasGold ? number_format($finalPrice, 0, ',', ' ') . ' Ar' : '—' ?></strong>
          <small><?= $hasGold ? 'Réduction -15% appliquée' : 'Disponible après Gold' ?></small>
        </div>
        <div class="card">
          <span>Prix final</span>
          <strong><?= number_format((float) ($finalPrice ?? $basePrice), 0, ',', ' ') ?> Ar</strong>
          <small>A payer pour acheter</small>
        </div>
        <div class="card">
          <span>Wallet</span>
          <strong><?= number_format($walletAmount, 0, ',', ' ') ?> Ar</strong>
          <small>Solde disponible</small>
        </div>
      </section>

      <section class="user-grid">
        <div class="panel">
          <div class="panel-head">
            <h3>Composition du programme</h3>
            <span class="tag success"><?= count($selectedMeals) ?> repas</span>
          </div>

          <div class="meal-stack">
            <?php foreach ($selectedMeals as $type => $items): ?>
              <div class="meal-card">
                <h4><?= esc(str_replace('_', ' ', strtolower($type))) ?></h4>
                <?php if (empty($items)): ?>
                  <p class="panel-note">Aucun aliment pour ce repas.</p>
                <?php endif; ?>
                <?php foreach ($items as $item): ?>
                  <div class="meal-item">
                    <div>
                      <strong><?= esc($item['nom'] ?? '') ?></strong>
                      <small><?= esc($item['quantite_g'] ?? 0) ?> g</small>
                    </div>
                    <div class="meal-macros">
                      <span><?= esc($item['calories'] ?? 0) ?> kcal</span>
                      <span>P: <?= esc($item['proteines'] ?? 0) ?>g</span>
                      <span>G: <?= esc($item['glucides'] ?? 0) ?>g</span>
                      <span>L: <?= esc($item['lipides'] ?? 0) ?>g</span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="panel">
          <div class="panel-head">
            <h3><?= $isPurchased ? 'Achat effectue' : 'Passer a l\'achat' ?></h3>
            <span class="tag">Solde: <?= number_format($walletAmount, 0, ',', ' ') ?> Ar</span>
          </div>

          <?php if ($isPurchased): ?>
            <div class="purchase-success-box">
              <div class="purchase-success-icon">✓</div>
              <div>
                <h4>Achat effectue</h4>
                <p>Vous avez deja achete ce programme. Bon courage pour votre suivi !</p>
              </div>
            </div>
          <?php else: ?>
            <p class="panel-note">La reduction Gold est appliquee automatiquement si ton compte est actif.</p>

            <div class="price-box">
              <div class="price-row">
                <span>Prix public</span>
                <strong><?= number_format($basePrice, 0, ',', ' ') ?> Ar</strong>
              </div>
              <div class="price-row">
                <span>Reduction</span>
                <strong>- <?= number_format((float) ($discount ?? 0), 0, ',', ' ') ?> Ar</strong>
              </div>
              <div class="price-total">
                <span>Prix final</span>
                <strong><?= number_format((float) ($finalPrice ?? 0), 0, ',', ' ') ?> Ar</strong>
              </div>
            </div>

            <form method="post" action="<?= base_url('/dashboard/regimes/purchase') ?>" class="purchase-form">
              <input type="hidden" name="id_programmeRegime" value="<?= esc($selectedProgramme['id'] ?? 0) ?>">
              <button class="btn btn-primary full" type="submit" <?= empty($selectedProgramme) ? 'disabled' : '' ?>>Acheter ce regime</button>
            </form>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
