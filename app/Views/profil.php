<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Profil</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
</head>
<body class="user-page">
  <?php
    $currentProgramme = $currentProgramme ?? null;
    $mealCounts = $mealCounts ?? [];
    $progressPercent = (int) ($progressPercent ?? 0);
    $progressDays = (int) ($progressDays ?? 0);
    $progressTotal = (int) ($progressTotal ?? 0);
    $activeRegimesCount = (int) ($activeRegimesCount ?? 0);
    $completedRegimesCount = (int) ($completedRegimesCount ?? 0);
    $weightDelta = $weightDelta ?? null;
  ?>
  <div class="app-shell">
    <?= view('partials/user_sidebar', [
      'active' => 'dashboard',
      'userName' => session()->get('nom') ?? 'Jean Dupont',
      'userEmail' => $userEmail ?? session()->get('email') ?? 'jean@exemple.com',
      'walletAmount' => $walletAmount ?? null,
      'badgeLabel' => $badgeLabel ?? 'Standard',
    ]) ?>

    <main class="content">
      <section class="user-hero">
        <div>
          <span class="pill">Votre IMC actuel</span>
          <h2><?= $imc !== null ? number_format($imc, 1) : '---' ?></h2>
          <p><?= esc($imcLabel) ?> - <?= !empty($objectifLabel) ? 'Objectif : ' . esc($objectifLabel) : 'Aucun objectif sélectionné' ?></p>
        </div>
        <div class="progress">
          <span>Progression vers objectif</span>
          <div class="progress-bar"><span style="width: <?= esc($progressPercent) ?>%"></span></div>
          <strong><?= $currentProgramme ? esc($progressPercent) . '% atteint' : 'Aucun programme actif' ?></strong>
          <small>
            <?= $currentProgramme
              ? esc($currentProgramme['programme_nom'] ?? 'Programme') . ' - jour ' . esc($progressDays) . ' / ' . esc($progressTotal)
              : 'Choisis un objectif pour afficher le suivi.' ?>
          </small>
        </div>
      </section>

      <?php if (session()->getFlashdata('profil_error')): ?>
        <div class="error"><?= esc(session()->getFlashdata('profil_error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('profil_success')): ?>
        <div class="success"><?= esc(session()->getFlashdata('profil_success')) ?></div>
      <?php endif; ?>

      <section class="user-cards">
        <div class="card">
          <span>Poids actuel</span>
          <strong><?= $body ? esc($body['poids']) : '---' ?> kg</strong>
          <small>
            <?= $weightDelta === null
              ? 'Dernier relevé enregistré'
              : (($weightDelta > 0 ? '+' : '') . number_format((float) $weightDelta, 1, ',', ' ') . ' kg depuis le dernier suivi') ?>
          </small>
        </div>
        <div class="card">
          <span>Taille</span>
          <strong><?= $body ? esc($body['taille']) : '---' ?> m</strong>
          <small><?= $body ? 'Mesure enregistrée' : 'Aucune mesure disponible' ?></small>
        </div>
        <div class="card">
          <span>Objectif</span>
          <strong><?= esc($objectifLabel ?? 'Aucun') ?></strong>
          <small><?= $currentProgramme ? esc($currentProgramme['programme_nom'] ?? 'Programme') : 'Choisi via la page objectif' ?></small>
        </div>
        <div class="card">
          <span>Regimes actifs</span>
          <strong><?= esc($activeRegimesCount) ?></strong>
          <small><?= esc($completedRegimesCount) ?> terminés</small>
        </div>
      </section>

      <section class="user-grid">
        <div class="panel">
          <div class="panel-head">
            <h3>Regime en cours</h3>
            <span class="tag success"><?= $currentProgramme ? 'En cours' : 'Aucun' ?></span>
          </div>
          <?php if ($currentProgramme): ?>
            <h4><?= esc($currentProgramme['programme_nom'] ?? 'Programme actif') ?></h4>
            <div class="progress-line">
              <span style="width: <?= esc($progressPercent) ?>%"></span>
            </div>
            <div class="panel-meta">
              <span>Progression</span>
              <strong><?= esc($progressDays) ?> / <?= esc($progressTotal) ?> jours</strong>
            </div>
            <div class="mini-grid">
              <div class="mini-card">Petit dej.<br><small><?= (int) ($mealCounts['PETIT_DEJEUNER'] ?? 0) ?> aliments</small></div>
              <div class="mini-card">Dejeuner<br><small><?= (int) ($mealCounts['DEJEUNER'] ?? 0) ?> aliments</small></div>
              <div class="mini-card">Diner<br><small><?= (int) ($mealCounts['DINER'] ?? 0) ?> aliments</small></div>
              <div class="mini-card">Collation<br><small><?= (int) ($mealCounts['COLLATION'] ?? 0) ?> aliments</small></div>
            </div>
            <a class="btn btn-outline full" href="<?= base_url('/dashboard/regimes') ?>">Voir le detail complet</a>
          <?php else: ?>
            <p class="panel-note">Aucun programme n’est encore actif. Choisis un objectif pour recevoir ton régime.</p>
            <a class="btn btn-primary full" href="<?= base_url('/objectif') ?>">Choisir un objectif</a>
          <?php endif; ?>
        </div>

        <div class="panel">
          <div class="panel-head">
            <h3>Evolution de l'IMC</h3>
            <select class="input">
              <option>3 derniers mois</option>
              <option>6 derniers mois</option>
              <option>12 derniers mois</option>
            </select>
          </div>
          <canvas id="imcChart" height="160"></canvas>
          <small class="panel-note">Integrer Chart.js ici</small>
        </div>
      </section>

      <section class="user-grid">
        <div class="panel">
          <h3>Mettre a jour le poids</h3>
          <form class="profile-form" method="post" action="<?= base_url('/profil/update') ?>">
            <input class="input" type="number" step="0.1" name="poids" placeholder="Poids (kg)" required>
            <button class="btn btn-primary" type="submit">Enregistrer</button>
          </form>
        </div>
      </section>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = <?= $historyLabels ?? '[]' ?>;
    const values = <?= $historyValues ?? '[]' ?>;
    const ctx = document.getElementById('imcChart');

    if (ctx) {
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: 'IMC',
            data: values,
            borderRadius: 10,
            backgroundColor: 'rgba(22, 163, 74, 0.6)',
          }],
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
          },
          scales: {
            y: { beginAtZero: false },
          },
        },
      });
    }
  </script>
</body>
</html>
