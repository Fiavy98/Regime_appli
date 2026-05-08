<?= view('partials/public_header', [
  'title' => 'NutriPlan - Régimes',
  'bodyClass' => 'public-page regimes-page',
  'nav' => [
    ['label' => 'Accueil', 'href' => base_url('/')],
    ['label' => 'Régimes', 'href' => base_url('/regimes')],
    ['label' => 'Sports', 'href' => base_url('/sports')],
  ],
  'action' => ['label' => 'Se connecter', 'href' => base_url('/login'), 'class' => 'btn btn-outline'],
]) ?>

    <section class="public-hero public-hero-regimes">
      <div>
        <div class="pill">🥗 Aperçu public</div>
        <h1 class="public-title">Découvrez nos régimes</h1>
        <p class="public-subtitle">
          Un aperçu clair des programmes disponibles avant inscription. Connectez-vous pour voir les détails complets et acheter.
        </p>
      </div>
      <a class="btn btn-primary" href="<?= base_url('/register/step1') ?>">Commencer gratuitement →</a>
    </section>

    <section class="section section-wide">
      <div class="showcase-grid preview-grid">
        <?php foreach ($regimes as $regime): ?>
          <article class="showcase-card preview-card">
            <span><?= esc($regime['objectif']) ?></span>
            <h3><?= esc($regime['name']) ?></h3>
            <p>Programme <?= esc($regime['duree']) ?></p>
            <div class="showcase-meta">
              <span>📅 <?= esc($regime['duree']) ?></span>
              <span>💰 <?= esc($regime['prix']) ?> Ar</span>
              <span>👁 Aperçu public</span>
            </div>
            <div class="showcase-note">Connectez-vous pour voir la composition détaillée.</div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

<?= view('partials/public_footer', [
  'footer' => 'Créez un compte pour accéder aux régimes détaillés.',
]) ?>
