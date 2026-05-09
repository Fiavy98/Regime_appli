<?= view('partials/public_header', [
  'title' => 'NutriPlan - Login',
  'bodyClass' => 'login-page',
  'nav' => [
    ['label' => 'Accueil', 'href' => base_url('/')],
    ['label' => 'Régimes', 'href' => base_url('/regimes')],
    ['label' => 'Sports', 'href' => base_url('/sports')],
  ],
  'action' => ['label' => 'Retour', 'href' => base_url('/'), 'class' => 'btn btn-outline'],
]) ?>

    <section class="login-hero">
      <div class="login-hero-copy">
        <div class="pill">🌿 Espace sécurisé</div>
        <h1>Connectez-vous à votre espace santé</h1>
        <p>
          Retrouvez vos régimes, vos activités sportives et votre suivi IMC dans un espace clair et rapide.
        </p>
        <div class="login-points">
          <span>📊 Suivi IMC</span>
          <span>🥗 Régimes personnalisés</span>
          <span>🏃 Activités suggérées</span>
        </div>
      </div>

      <div class="login-card">
        <h2>Connexion</h2>
        <p>Accès utilisateur ou admin.</p>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('/login') ?>" class="login-form">
          <div class="field">
            <label class="form-label">Adresse email</label>
            <input class="input" type="email" name="email" value="andry@gmail.com" required>
          </div>
          <div class="field">
            <label class="form-label">Mot de passe</label>
            <input class="input" type="password" name="password" value="pass123" required>
          </div>
          <button class="btn btn-primary login-submit" type="submit">Se connecter →</button>
        </form>

        <p class="login-footer">
          Pas encore de compte ? <a href="<?= base_url('/register/step1') ?>">Créer un compte</a>
        </p>
      </div>
    </section>

<?= view('partials/public_footer', [
  'footer' => 'NutriPlan — votre suivi santé personnalisé.',
]) ?>
