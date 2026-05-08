<?= view('partials/public_header', [
  'title' => 'NutriPlan - Sports',
  'bodyClass' => '',
  'nav' => [
    ['label' => 'Accueil', 'href' => base_url('/')],
    ['label' => 'Regimes', 'href' => base_url('/regimes')],
    ['label' => 'Sports', 'href' => base_url('/sports')],
  ],
  'action' => ['label' => 'Se connecter', 'href' => base_url('/login'), 'class' => 'btn btn-outline'],
]) ?>

    <section class="section">
      <h2 class="section-title">Apercu des activites sportives</h2>
      <p class="section-sub">Nom, niveau et calories brulees (apercu visiteur).</p>
      <div class="card-grid">
        <?php foreach ($sports as $sport): ?>
          <div class="card">
            <div class="badge-level"><?= esc($sport['niveau']) ?></div>
            <h4><?= esc($sport['name']) ?></h4>
            <small>Calories: <?= esc($sport['calories']) ?></small>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

<?= view('partials/public_footer', [
  'footer' => 'Inscris-toi pour des recommandations personnalisees.',
]) ?>
