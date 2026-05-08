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
<body>
  <div class="page-wrap">
    <header class="topbar">
      <div class="logo">NutriPlan</div>
      <nav class="nav-links">
        <a href="<?= base_url('/dashboard') ?>">Profil</a>
      </nav>
      <div class="nav-actions">
        <a class="btn btn-outline" href="<?= base_url('/logout') ?>">Logout</a>
      </div>
    </header>

    <section class="section profile-hero">
      <div>
        <span class="pill">Espace utilisateur</span>
        <h2 class="section-title">Bonjour <?= esc(session()->get('nom') ?? '') ?></h2>
        <p class="section-sub">Suivi IMC, historique et mise a jour du poids.</p>
      </div>
      <div class="profile-card">
        <div class="profile-imc">
          <span class="profile-label">IMC actuel</span>
          <strong><?= $imc !== null ? number_format($imc, 1) : '---' ?></strong>
          <span class="profile-status"><?= esc($imcLabel) ?></span>
        </div>
        <div class="profile-info">
          <div>
            <span>Taille</span>
            <strong><?= $body ? esc($body['taille']) : '---' ?> m</strong>
          </div>
          <div>
            <span>Poids</span>
            <strong><?= $body ? esc($body['poids']) : '---' ?> kg</strong>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <?php if (session()->getFlashdata('profil_error')): ?>
        <div class="error"><?= esc(session()->getFlashdata('profil_error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('profil_success')): ?>
        <div class="success"><?= esc(session()->getFlashdata('profil_success')) ?></div>
      <?php endif; ?>

      <div class="profile-grid">
        <div class="card">
          <h4>Mettre a jour le poids</h4>
          <form class="profile-form" method="post" action="<?= base_url('/profil/update') ?>">
            <input class="input" type="number" step="0.1" name="poids" placeholder="Poids (kg)" required>
            <button class="btn btn-primary" type="submit">Enregistrer</button>
          </form>
        </div>
        <div class="card">
          <h4>Historique IMC</h4>
          <canvas id="imcChart" height="140"></canvas>
        </div>
      </div>
    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = <?= $historyLabels ?? '[]' ?>;
    const values = <?= $historyValues ?? '[]' ?>;
    const ctx = document.getElementById('imcChart');

    if (ctx) {
      new Chart(ctx, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'IMC',
            data: values,
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22, 163, 74, 0.15)',
            tension: 0.3,
            fill: true,
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
