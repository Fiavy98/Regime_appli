<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Admin Sports</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-page">
  <div class="app-shell">
    <?= view('admin/_sidebar', ['active' => 'sports']) ?>

    <main class="content">
      <section class="admin-header">
        <div>
          <h1>Activites sportives</h1>
          <p>Suggestions selon le niveau et l'objectif.</p>
        </div>
        <div class="admin-actions">
          <a class="filter <?= $niveau === 'FAIBLE' ? 'active' : '' ?>" href="<?= base_url('/admin/sports?niveau=FAIBLE') ?>">Faible</a>
          <a class="filter <?= $niveau === 'MOYEN' ? 'active' : '' ?>" href="<?= base_url('/admin/sports?niveau=MOYEN') ?>">Moyen</a>
          <a class="filter <?= $niveau === 'ELEVE' ? 'active' : '' ?>" href="<?= base_url('/admin/sports?niveau=ELEVE') ?>">Eleve</a>
          <a class="filter <?= $niveau === '' ? 'active' : '' ?>" href="<?= base_url('/admin/sports') ?>">Tous</a>
        </div>
      </section>

      <section class="admin-stats">
        <?php
        $totalActivites = is_array($activites ?? null) ? count($activites) : 0;
        $niveaux = [];
        foreach ($activites as $activite) {
            $niveaux[$activite['niveau'] ?? ''] = true;
        }
        ?>
        <article class="stat-card">
          <div>
            <span>Activites</span>
            <strong><?= number_format($totalActivites, 0, ',', ' ') ?></strong>
            <small>Affichees</small>
          </div>
          <div class="stat-icon">🏃</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Niveaux</span>
            <strong><?= number_format(count($niveaux), 0, ',', ' ') ?></strong>
            <small>Disponibles</small>
          </div>
          <div class="stat-icon">📊</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Filtre</span>
            <strong><?= esc($niveau !== '' ? $niveau : 'TOUS') ?></strong>
            <small>Niveau selectionne</small>
          </div>
          <div class="stat-icon">🎯</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Objectif</span>
            <strong>Bien etre</strong>
            <small>Suivi sportif</small>
          </div>
          <div class="stat-icon">✨</div>
        </article>
      </section>

      <section class="sport-grid">
        <?php if (empty($activites)): ?>
          <div class="admin-panel-card">
            <p class="empty-state">Aucune activite ne correspond a ce filtre.</p>
          </div>
        <?php endif; ?>
        <?php foreach ($activites as $activite): ?>
          <article class="sport-card">
            <div class="sport-top">
              <span class="chip"><?= esc($activite['category'] ?? 'Sport') ?></span>
              <span class="level <?= strtolower($activite['niveau'] ?? '') ?>"><?= esc($activite['niveau'] ?? '') ?></span>
            </div>
            <h3><?= esc($activite['sport_name'] ?? '') ?></h3>
            <p>Objectif : <?= esc($activite['objectif_label'] ?? '--') ?></p>
            <div class="sport-meta">
              <div>
                <strong><?= esc($activite['calories_brulees_par_heure'] ?? 0) ?> kcal</strong>
                <small>par heure</small>
              </div>
              <div>
                <strong><?= esc($activite['duree_minute'] ?? 0) ?> min</strong>
                <small>recommande</small>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </section>
    </main>
  </div>
</body>
</html>
