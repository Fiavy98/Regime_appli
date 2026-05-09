<?= view('partials/public_header', [
  'title' => 'NutriPlan - Accueil',
  'bodyClass' => 'home-page',
  'nav' => [
    ['label' => 'Régimes', 'href' => '#regimes'],
    ['label' => 'IMC', 'href' => '#imc'],
    ['label' => 'Comment ça marche', 'href' => '#how-it-works'],
    ['label' => 'Tarifs', 'href' => '#tarifs'],
    ['label' => 'Gold', 'href' => '#gold'],
  ],
  'action' => ['label' => 'Se connecter', 'href' => base_url('/login'), 'class' => 'btn btn-outline'],
]) ?>

    <section class="hero home-hero">
      <div class="hero-copy">
        <div class="pill">✨ Application de nutrition personnalisée</div>
        <h1 class="hero-title">Votre régime taillé sur <span>mesure</span></h1>
        <p class="hero-desc">
          Calculez votre IMC, choisissez votre objectif et recevez un programme alimentaire et sportif 100% adapté à votre profil.
        </p>
        <div class="nav-actions hero-actions">
          <a class="btn btn-primary" href="<?= base_url('/register/step1') ?>">Commencer gratuitement →</a>
          <a class="btn btn-outline" href="#imc">Calculer mon IMC</a>
        </div>
        <div class="hero-stats">
          <div>
            <strong>1200+</strong>
            <span>Utilisateurs actifs</span>
          </div>
          <div>
            <strong>48</strong>
            <span>Programmes disponibles</span>
          </div>
          <div>
            <strong>95%</strong>
            <span>Satisfaction</span>
          </div>
        </div>
      </div>
      <div class="hero-card hero-imc-card">
        <div class="hero-card-top">
          <h3>📊 Votre IMC en direct</h3>
          <span class="floating-badge">🎯 Objectif atteint !</span>
        </div>
        <div class="imc-display">
          <strong>22.9</strong>
          <span>☑ Poids Normal</span>
        </div>
        <div class="imc-bars">
          <span class="bar blue"></span>
          <span class="bar green"></span>
          <span class="bar yellow"></span>
          <span class="bar red"></span>
        </div>
        <div class="imc-labels">
          <span>Maigre</span>
          <span>Normal</span>
          <span>Surpoids</span>
          <span>Obésité</span>
        </div>
        <div class="mini-card mini-note">
          ⚡ -3.2 kg ce mois
        </div>
      </div>
    </section>

    <section class="section imc-section" id="imc">
      <div class="section-heading section-heading-inline">
        <div>
          <h2 class="section-title">Calculateur IMC</h2>
          <p class="section-sub">Testez votre indice de masse corporelle directement sur la page d'accueil.</p>
        </div>
      </div>
        <div class="imc-tool">
          <form class="form-card imc-form" data-imc-form>
          <div class="field-grid">
            <div class="field">
              <label class="form-label">Taille (m)</label>
              <input class="input" type="number" step="0.01" min="0" name="taille" placeholder="1.75" required>
            </div>
            <div class="field">
              <label class="form-label">Poids (kg)</label>
              <input class="input" type="number" step="0.1" min="0" name="poids" placeholder="70" required>
            </div>
          </div>
          <button class="btn btn-primary" type="submit">Calculer mon IMC</button>
        </form>
        <div class="hero-card imc-result-card" data-imc-result-card>
          <span class="pill">Résultat IMC</span>
          <div class="imc-result-value" data-imc-result>---</div>
          <div class="imc-badge imc-badge-neutral" data-imc-status>En attente de calcul</div>
          <p>Le calcul est effectué en temps réel dans ton navigateur.</p>
        </div>
      </div>
    </section>

    <section class="section" id="how-it-works">
      <div class="section-heading">
        <div>
          <h2 class="section-title">Comment ça marche ?</h2>
          <p class="section-sub">En 4 étapes simples, obtenez votre programme personnalisé</p>
        </div>
      </div>
      <div class="steps-grid">
        <article class="step-card">
          <div class="step-number">1</div>
          <div class="step-emoji">📝</div>
          <h3>Créez votre profil</h3>
          <p>Renseignez votre taille, poids, âge et genre pour un profil complet.</p>
        </article>
        <article class="step-card">
          <div class="step-number">2</div>
          <div class="step-emoji">📊</div>
          <h3>Calculez votre IMC</h3>
          <p>Notre système calcule automatiquement votre indice de masse corporelle.</p>
        </article>
        <article class="step-card">
          <div class="step-number">3</div>
          <div class="step-emoji">🎯</div>
          <h3>Choisissez un objectif</h3>
          <p>Perte de poids, prise de masse ou atteindre l’IMC idéal.</p>
        </article>
        <article class="step-card">
          <div class="step-number">4</div>
          <div class="step-emoji">🥗</div>
          <h3>Recevez votre régime</h3>
          <p>Programme alimentaire et sportif adapté à votre profil exact.</p>
        </article>
      </div>
    </section>

    <section class="section" id="regimes">
      <div class="section-heading section-heading-inline">
        <div>
          <h2 class="section-title">Exemples de régimes</h2>
          <p class="section-sub">Aperçu — Inscrivez-vous pour accéder aux détails complets</p>
        </div>
        <a class="btn btn-outline" href="<?= base_url('/regimes') ?>">Voir tous les régimes</a>
      </div>
      <div class="showcase-grid">
        <article class="showcase-card showcase-green">
          <span>PERTE DE POIDS</span>
          <h3>Régime Minceur Plus</h3>
          <p>Programme 4 semaines</p>
          <div class="showcase-meta">
            <span>📅 28 jours</span>
            <span>📉 -2 à -3 kg</span>
            <span>⚡ 1 400 kcal/j</span>
          </div>
          <div class="showcase-bottom">
            <strong>6 000 Ar</strong>
            <span class="badge-soft">Populaire</span>
          </div>
          <div class="showcase-note">🔒 Connectez-vous pour voir la composition</div>
        </article>
        <article class="showcase-card showcase-blue">
          <span>PRISE DE MASSE</span>
          <h3>Régime Musclage</h3>
          <p>Programme 8 semaines</p>
          <div class="showcase-meta">
            <span>📅 56 jours</span>
            <span>📈 +3 à +5 kg</span>
            <span>⚡ 2 800 kcal/j</span>
          </div>
          <div class="showcase-bottom">
            <strong>10 000 Ar</strong>
            <span class="badge-soft">Nouveau</span>
          </div>
          <div class="showcase-note">🔒 Connectez-vous pour voir la composition</div>
        </article>
        <article class="showcase-card showcase-darkgreen">
          <span>IMC IDÉAL</span>
          <h3>Équilibre Parfait</h3>
          <p>Programme 2 semaines</p>
          <div class="showcase-meta">
            <span>📅 14 jours</span>
            <span>⚖ Équilibre</span>
            <span>⚡ 2 000 kcal/j</span>
          </div>
          <div class="showcase-bottom">
            <strong>3 000 Ar</strong>
            <span class="badge-soft">Recommandé</span>
          </div>
          <div class="showcase-note">🔒 Connectez-vous pour voir la composition</div>
        </article>
      </div>
    </section>

    <section class="section" id="tarifs">
      <div class="section-heading section-heading-inline">
        <div>
          <h2 class="section-title">Tarifs</h2>
          <p class="section-sub">Choisissez la formule qui correspond à votre rythme.</p>
        </div>
      </div>
      <div class="pricing-grid">
        <article class="pricing-card">
          <span class="pricing-tag">Découverte</span>
          <h3>Gratuit</h3>
          <strong>0 Ar</strong>
          <p>Accès aux étapes d’inscription, calcul IMC et aperçu des régimes.</p>
        </article>
        <article class="pricing-card pricing-card-featured">
          <span class="pricing-tag">Standard</span>
          <h3>Programme complet</h3>
          <strong>Selon le régime</strong>
          <p>Régimes personnalisés, détails alimentaires et activités sportives adaptées.</p>
        </article>
        <article class="pricing-card">
          <span class="pricing-tag">Gold</span>
          <h3>Réduction -15%</h3>
          <strong>Unique</strong>
          <p>Réduction sur tous les régimes, export PDF et accès prioritaire aux nouveautés.</p>
        </article>
      </div>
    </section>

    <section class="section gold-section" id="gold">
      <div class="gold-panel">
        <div class="gold-emoji">👑</div>
        <h2>Passez à Gold</h2>
        <p>Un paiement unique pour profiter de 15% de réduction sur tous les régimes</p>
        <div class="gold-points">
          <span>✅ -15% sur tous les régimes</span>
          <span>✅ Accès prioritaire aux nouveautés</span>
          <span>✅ Export PDF illimité</span>
          <span>✅ Badge exclusif Gold</span>
        </div>
        <a class="btn btn-primary gold-cta" href="<?= base_url('/register/step1') ?>">Découvrir Gold →</a>
      </div>
    </section>

<?= view('partials/public_footer', [
  'footer' => '© 2025 NutriPlan — Application de nutrition personnalisée',
  'script' => base_url('assets/js/public.js'),
]) ?>
