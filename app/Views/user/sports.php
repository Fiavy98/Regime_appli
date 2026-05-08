<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Sports</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
</head>
<body class="user-page">
  <div class="app-shell">
    <?= view('partials/user_sidebar', [
      'active' => 'sports',
      'userName' => session()->get('nom') ?? 'Utilisateur',
      'userEmail' => $userEmail ?? session()->get('email') ?? 'Compte actif',
      'badgeLabel' => $badgeLabel ?? 'Standard',
    ]) ?>

    <main class="content">
      <?php if (session()->getFlashdata('profil_error')): ?>
        <div class="error"><?= esc(session()->getFlashdata('profil_error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('profil_success')): ?>
        <div class="success"><?= esc(session()->getFlashdata('profil_success')) ?></div>
      <?php endif; ?>

      <section class="sports-header panel">
        <div>
          <span class="pill">Activites sportives</span>
          <h2>Recommandations adaptees a ton niveau</h2>
          <p>Choisis une intensite et lance une activite directement depuis ton espace.</p>
          <?php if (!empty($objectifLabel)): ?>
            <div class="objectif-current">Objectif actif: <strong><?= esc($objectifLabel) ?></strong></div>
          <?php endif; ?>
        </div>
        <div class="sport-filters">
          <a class="filter <?= $niveau === 'FAIBLE' ? 'active' : '' ?>" href="<?= base_url('/dashboard/sports?niveau=FAIBLE') ?>">Faible</a>
          <a class="filter <?= $niveau === 'MOYEN' ? 'active' : '' ?>" href="<?= base_url('/dashboard/sports?niveau=MOYEN') ?>">Moyen</a>
          <a class="filter <?= $niveau === 'ELEVE' ? 'active' : '' ?>" href="<?= base_url('/dashboard/sports?niveau=ELEVE') ?>">Eleve</a>
          <a class="filter <?= $niveau === '' ? 'active' : '' ?>" href="<?= base_url('/dashboard/sports') ?>">Tous</a>
        </div>
      </section>

      <section class="sport-grid">
        <?php if (empty($activites)): ?>
          <div class="panel">
            <p class="panel-note">Aucune activite disponible pour ce filtre.</p>
          </div>
        <?php endif; ?>

        <?php foreach ($activites as $activite): ?>
          <article class="sport-card">
            <div class="sport-top">
              <span class="chip"><?= esc($activite['category'] ?? 'Sport') ?></span>
              <span class="level <?= strtolower($activite['niveau'] ?? '') ?>"><?= esc($activite['niveau'] ?? '') ?></span>
            </div>
            <h3><?= esc($activite['sport_name'] ?? '') ?></h3>
            <p><?= esc($activite['objectif_label'] ?? 'Objectif non renseigne') ?></p>
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
            <form method="post" action="<?= base_url('/dashboard/sports/start') ?>">
              <input type="hidden" name="id_activite" value="<?= esc($activite['id'] ?? 0) ?>">
              <button class="btn btn-outline full" type="submit">Commencer</button>
            </form>
          </article>
        <?php endforeach; ?>
      </section>
    </main>
  </div>
</body>
</html>
