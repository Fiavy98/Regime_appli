<?php
$objectifCards = [
    1 => [
        'title' => 'Prise de masse',
        'desc' => 'Programmes plus denses pour augmenter le poids de façon contrôlée.',
        'accent' => 'masse',
        'emoji' => '💪',
    ],
    2 => [
        'title' => 'Perte de poids',
        'desc' => 'Suggestions plus légères pour réduire la masse grasse.',
        'accent' => 'perte',
        'emoji' => '🔥',
    ],
    3 => [
        'title' => 'IMC idéal',
        'desc' => 'Équilibre entre alimentation et activité pour stabiliser la forme.',
        'accent' => 'equilibre',
        'emoji' => '⚖️',
    ],
];
?>
<?= view('partials/public_header', [
  'title' => 'NutriPlan - Choix d\'objectif',
  'bodyClass' => 'public-page objective-page',
  'nav' => [
    ['label' => 'Accueil', 'href' => base_url('/')],
    ['label' => 'Régimes', 'href' => base_url('/dashboard/regimes')],
    ['label' => 'Sports', 'href' => base_url('/dashboard/sports')],
  ],
  'action' => ['label' => 'Profil', 'href' => base_url('/dashboard'), 'class' => 'btn btn-outline'],
]) ?>

    <section class="public-hero objective-hero">
      <div>
        <div class="pill">🎯 F5 - Choix d'objectif</div>
        <h1 class="public-title">Choisis ton objectif</h1>
        <p class="public-subtitle">
          Ce choix filtre ensuite les régimes et les activités sportives proposés par Tsinjo.
        </p>
      </div>
      <div class="objective-summary hero-card">
        <span class="pill">Objectif actuel</span>
        <strong><?= esc($currentLabel ?? 'Aucun') ?></strong>
        <p>Sélectionne une carte pour mettre à jour ton profil et recevoir des suggestions adaptées.</p>
        <a class="btn btn-outline" href="<?= base_url('/dashboard/regimes') ?>">Voir les régimes</a>
      </div>
    </section>

    <?php if (session()->getFlashdata('objectif_error')): ?>
      <section class="section section-wide">
        <div class="error"><?= esc(session()->getFlashdata('objectif_error')) ?></div>
      </section>
    <?php endif; ?>

    <section class="section section-wide">
      <div class="objectif-grid objective-cards">
        <?php foreach ($objectifs as $objectif): ?>
          <?php $id = (int) $objectif['id']; ?>
          <form method="post" action="<?= base_url('/objectif/choisir') ?>" class="objectif-form">
            <input type="hidden" name="id_objectif" value="<?= esc($id) ?>">
            <button type="submit" class="objectif-card <?= $currentId === $id ? 'selected' : '' ?>">
              <span class="objectif-emoji"><?= esc($objectifCards[$id]['emoji'] ?? '🎯') ?></span>
              <div class="objectif-pill <?= esc($objectifCards[$id]['accent'] ?? 'equilibre') ?>"><?= esc($objectifCards[$id]['title'] ?? $objectif['name']) ?></div>
              <h3><?= esc($objectifCards[$id]['title'] ?? $objectif['name']) ?></h3>
              <p><?= esc($objectifCards[$id]['desc'] ?? '') ?></p>
              <span class="objectif-note"><?= $currentId === $id ? 'Sélectionné' : 'Choisir cet objectif' ?></span>
            </button>
          </form>
        <?php endforeach; ?>
      </div>
    </section>
<?= view('partials/public_footer', [
  'footer' => '',
]) ?>
