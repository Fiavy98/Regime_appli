<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-page">
  <div class="app-shell">
    <?= view('admin/_sidebar', ['active' => 'dashboard']) ?>

    <main class="content">
      <div class="admin-header">
        <div>
          <h1>Tableau de bord</h1>
          <p>Vue d'ensemble de l'application</p>
        </div>
        <div class="admin-actions">
          <form method="get" action="<?= base_url('/admin') ?>" class="period-filter">
            <select class="input admin-select" name="period" onchange="this.form.submit()">
              <option value="month" <?= $period === 'month' ? 'selected' : '' ?>>Ce mois</option>
              <option value="quarter" <?= $period === 'quarter' ? 'selected' : '' ?>>3 derniers mois</option>
              <option value="year" <?= $period === 'year' ? 'selected' : '' ?>>Cette annee</option>
            </select>
          </form>
          <button class="btn btn-outline" type="button">Exporter rapport</button>
        </div>
      </div>

      <section class="admin-stats">
        <article class="stat-card">
          <div>
            <span>Utilisateurs total</span>
            <strong><?= number_format((int) $usersTotal, 0, ',', ' ') ?></strong>
            <small>+<?= (int) $growthUsers ?>% ce mois</small>
          </div>
          <div class="stat-icon">👥</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Revenus totaux</span>
            <strong><?= number_format((float) $revenusCurrent, 0, ',', ' ') ?> Ar</strong>
            <small>+<?= (int) $growthRevenus ?>% ce mois</small>
          </div>
          <div class="stat-icon">💰</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Abonnes Gold</span>
            <strong><?= number_format((int) $goldCurrent, 0, ',', ' ') ?></strong>
            <small>+<?= (int) $growthGold ?>% ce mois</small>
          </div>
          <div class="stat-icon">👑</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Regimes vendus</span>
            <strong><?= number_format((int) $regimesCurrent, 0, ',', ' ') ?></strong>
            <small>+<?= (int) $growthRegimes ?>% ce mois</small>
          </div>
          <div class="stat-icon">🥗</div>
        </article>
      </section>

      <section class="admin-panels">
        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3>📊 Inscriptions & ventes mensuelles</h3>
              <p>Evolution sur les 8 derniers mois</p>
            </div>
          </div>
          <div class="chart-placeholder">
            <canvas id="inscriptionsChart" height="180"></canvas>
          </div>
        </div>
        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3>🎯 Ventes par objectif</h3>
              <p>Repartition des regimes</p>
            </div>
          </div>
          <div class="chart-donut">
            <canvas id="objectifChart" height="220"></canvas>
            <ul>
              <?php foreach ($ventesObjectif as $index => $row): ?>
                <?php $percent = ($totalObjectiveSales ?? 0) > 0 ? round(((int) $row['total'] / $totalObjectiveSales) * 100) : 0; ?>
                <li>
                  <span class="dot <?= ['green', 'blue', 'orange', 'red'][$index % 4] ?>"></span>
                  <?= esc($row['objectif'] ?? 'Autre') ?> - <?= (int) $row['total'] ?> (<?= (int) $percent ?>%)
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </section>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Utilisateurs</h5>
                <h2><?= array_sum(array_column($usersByRole, 'count')) ?></h2>
                <small>dont <?= $nbGold ?> Gold</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Revenus totaux</h5>
                <h2><?= number_format($revenusTotaux, 0) ?> Ar</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>Membres Gold</h5>
                <h2><?= $nbGold ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5>Ventes totales</h5>
                <h2><?= count($achats) ?> régimes</h2>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques (avec Chart.js) -->
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">Évolution mensuelle</div>
            <div class="card-body">
                <canvas id="evolutionChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">Top régimes vendus</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <?php foreach ($ventesParRegime as $vente): ?>
                    <tr>
                        <td><?= $vente['nom'] ?></td>
                        <td class="text-end"><?= $vente['nb_ventes'] ?> vendus</td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('evolutionChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($monthLabels) ?>,
        datasets: [
            { label: 'Inscriptions', data: <?= json_encode($inscriptionsData) ?>, borderColor: 'blue', fill: false },
            { label: 'Achats', data: <?= json_encode($achatsData) ?>, borderColor: 'green', fill: false }
        ]
    }
});
</script>
      <section class="admin-panels">
        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3>🧾 Derniers achats</h3>
              <p>Transactions recentes</p>
            </div>
            <a class="btn btn-outline" href="<?= base_url('/admin/regimes') ?>">Voir tout</a>
          </div>
          <div class="table">
            <div class="table-row head">
              <span>Utilisateur</span><span>Regime</span><span>Prix paye</span><span>Gold</span><span>Date</span><span>Actions</span>
            </div>
            <?php foreach ($achats as $achat): ?>
              <div class="table-row">
                <span><?= esc($achat['user_name'] ?? '---') ?></span>
                <span><?= esc($achat['regime_nom'] ?? '---') ?></span>
                <span><?= number_format((float) ($achat['prix_total'] ?? 0), 0, ',', ' ') ?> Ar</span>
                <span class="tag <?= !empty($achat['est_gold_utilise']) ? 'gold' : '' ?>">
                  <?= !empty($achat['est_gold_utilise']) ? 'Oui' : 'Non' ?>
                </span>
                <span><?= !empty($achat['date_achat']) ? date('d M Y', strtotime($achat['date_achat'])) : '--' ?></span>
                <span>
                  <a class="btn btn-outline btn-xs" href="<?= base_url('/admin/regimes?id=' . ($achat['programme_id'] ?? '')) ?>">Voir</a>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3>🎟️ Codes prépayés</h3>
              <p>Gestion rapide des codes</p>
            </div>
            <a class="btn btn-primary" href="<?= base_url('/admin/codes') ?>">+ Creer un code</a>
          </div>
          <div class="table">
            <div class="table-row head">
              <span>Code</span><span>Montant</span><span>Expiration</span><span>Statut</span><span>Actions</span>
            </div>
            <?php foreach ($codes as $code): ?>
              <div class="table-row">
                <span><?= esc($code['code'] ?? '') ?></span>
                <span><?= number_format((float) ($code['montant'] ?? 0), 0, ',', ' ') ?> Ar</span>
                <span><?= !empty($code['date_expiration']) ? date('d M Y', strtotime($code['date_expiration'])) : '--' ?></span>
                <span class="tag <?= $code['status_label'] === 'Utilise' ? 'success' : ($code['status_label'] === 'Expire' ? 'danger' : '') ?>">
                  <?= esc($code['status_label']) ?>
                </span>
                <span>
                  <a class="btn btn-outline btn-xs" href="<?= base_url('/admin/codes') ?>">Modifier</a>
                  <a class="btn btn-outline btn-xs danger" href="<?= base_url('/admin/codes') ?>">Supprimer</a>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = <?= json_encode($monthLabels ?? []) ?>;
    const inscriptions = <?= isset($inscriptionsData) ? json_encode($inscriptionsData) : ($inscriptions ?? '[]') ?>;
    const ventes = <?= isset($achatsData) ? json_encode($achatsData) : ($ventes ?? '[]') ?>;
    const objectifLabels = <?= $ventesObjectifLabels ?? '[]' ?>;
    const objectifValues = <?= $ventesObjectifValues ?? '[]' ?>;
    const chartEl = document.getElementById('inscriptionsChart');
    const objectifChartEl = document.getElementById('objectifChart');

    if (chartEl) {
      new Chart(chartEl, {
        type: 'bar',
        data: {
          labels,
          datasets: [
            {
              label: 'Inscriptions',
              data: inscriptions,
              backgroundColor: 'rgba(34, 197, 94, 0.6)',
              borderRadius: 8,
            },
            {
              label: 'Ventes',
              data: ventes,
              backgroundColor: 'rgba(22, 163, 74, 0.3)',
              borderRadius: 8,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true } },
        },
      });
    }

    if (objectifChartEl) {
      new Chart(objectifChartEl, {
        type: 'doughnut',
        data: {
          labels: objectifLabels,
          datasets: [{
            data: objectifValues,
            backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444'],
            borderWidth: 0,
          }],
        },
        options: {
          responsive: true,
          cutout: '72%',
          plugins: { legend: { display: false } },
        },
      });
    }
  </script>
</body>
</html>
