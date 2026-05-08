<?php
$selectedProgramme = $programme ?? null;
$selectedMeals = $meals ?? [];
$programmeCount = is_array($programmes ?? null) ? count($programmes) : 0;
$mealCount = 0;
foreach ($selectedMeals as $items) {
    $mealCount += is_array($items) ? count($items) : 0;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Admin Regimes</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-page">
  <div class="app-shell">
    <?= view('admin/_sidebar', ['active' => 'regimes']) ?>

    <main class="content">
      <section class="admin-header">
        <div>
          <h1>Gestion des regimes</h1>
          <p>Créer les programmes, leurs compositions et suivre les détails en un seul endroit.</p>
        </div>
        <form method="get" action="<?= base_url('/admin/regimes') ?>">
          <select class="input admin-select" name="id" onchange="this.form.submit()">
            <?php foreach ($programmes as $item): ?>
              <option value="<?= esc($item['id']) ?>" <?= $selectedProgramme && (int) $item['id'] === (int) $selectedProgramme['id'] ? 'selected' : '' ?>>
                <?= esc($item['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
      </section>

      <?php if (session()->getFlashdata('admin_error')): ?>
        <div class="admin-alert error"><?= esc(session()->getFlashdata('admin_error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('admin_success')): ?>
        <div class="admin-alert success"><?= esc(session()->getFlashdata('admin_success')) ?></div>
      <?php endif; ?>

      <section class="admin-stats">
        <article class="stat-card">
          <div>
            <span>Programmes</span>
            <strong><?= number_format($programmeCount, 0, ',', ' ') ?></strong>
            <small>Disponibles</small>
          </div>
          <div class="stat-icon">🥗</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Repas détaillés</span>
            <strong><?= number_format($mealCount, 0, ',', ' ') ?></strong>
            <small>Pour le programme sélectionné</small>
          </div>
          <div class="stat-icon">📋</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Prix affiché</span>
            <strong><?= number_format((float) ($selectedProgramme['prix'] ?? 0), 0, ',', ' ') ?> Ar</strong>
            <small>Programme actuel</small>
          </div>
          <div class="stat-icon">💰</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Durée</span>
            <strong><?= esc($selectedProgramme['duree_jours'] ?? '--') ?></strong>
            <small>Jours</small>
          </div>
          <div class="stat-icon">⏳</div>
        </article>
      </section>

      <section class="admin-panels admin-duo">
        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3>Créer un programme</h3>
              <p>Ajoute un régime, son objectif et ses paramètres nutritionnels.</p>
            </div>
          </div>

          <form method="post" action="<?= base_url('/admin/programmes/create') ?>" class="admin-form-stack">
            <div class="admin-form-grid">
              <div>
                <label class="form-label">Nom</label>
                <input class="input" type="text" name="nom" placeholder="Programme minceur..." required>
              </div>
              <div>
                <label class="form-label">Objectif</label>
                <select class="input" name="id_objectif" required>
                  <option value="">Sélectionner</option>
                  <?php foreach (($objectifs ?? []) as $objectif): ?>
                    <option value="<?= esc($objectif['id']) ?>"><?= esc($objectif['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="form-label">Variation poids (kg)</label>
                <input class="input" type="number" step="0.1" name="variation_poids" placeholder="-5">
              </div>
              <div>
                <label class="form-label">IMC min</label>
                <input class="input" type="number" step="0.1" name="imc_min" placeholder="18">
              </div>
              <div>
                <label class="form-label">IMC max</label>
                <input class="input" type="number" step="0.1" name="imc_max" placeholder="25">
              </div>
              <div>
                <label class="form-label">Durée (jours)</label>
                <input class="input" type="number" name="duree_jours" placeholder="30" required>
              </div>
              <div>
                <label class="form-label">Prix</label>
                <input class="input" type="number" step="0.01" name="prix" placeholder="50000" required>
              </div>
            </div>
            <div class="code-form-actions">
              <button class="btn btn-primary" type="submit">Créer le programme</button>
            </div>
          </form>
        </div>

        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3>Ajouter une composition</h3>
              <p>Lie un aliment à un programme et précise le repas.</p>
            </div>
          </div>

          <form method="post" action="<?= base_url('/admin/compositions/create') ?>" class="admin-form-stack">
            <div class="admin-form-grid">
              <div>
                <label class="form-label">Programme</label>
                <select class="input" name="id_programmeRegime" required>
                  <option value="">Sélectionner</option>
                  <?php foreach ($programmes as $item): ?>
                    <option value="<?= esc($item['id']) ?>" <?= $selectedProgramme && (int) $item['id'] === (int) $selectedProgramme['id'] ? 'selected' : '' ?>>
                      <?= esc($item['nom']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="form-label">Aliment</label>
                <select class="input" name="id_aliment" required>
                  <option value="">Sélectionner</option>
                  <?php foreach (($aliments ?? []) as $aliment): ?>
                    <option value="<?= esc($aliment['id']) ?>">
                      <?= esc($aliment['nom']) ?><?= !empty($aliment['categorie_label']) ? ' - ' . esc($aliment['categorie_label']) : '' ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="form-label">Quantité (g)</label>
                <input class="input" type="number" step="0.1" name="quantite_g" placeholder="150" required>
              </div>
              <div>
                <label class="form-label">Repas</label>
                <select class="input" name="type_repas" required>
                  <option value="PETIT_DEJEUNER">Petit déjeuner</option>
                  <option value="DEJEUNER">Déjeuner</option>
                  <option value="DINER">Dîner</option>
                  <option value="COLLATION">Collation</option>
                </select>
              </div>
            </div>
            <div class="code-form-actions">
              <button class="btn btn-primary" type="submit">Ajouter la composition</button>
            </div>
          </form>
        </div>
      </section>

      <section class="admin-panels">
        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3><?= esc($selectedProgramme['nom'] ?? 'Aucun programme') ?></h3>
              <p><?= esc($selectedProgramme['objectif_label'] ?? 'Objectif non renseigné') ?></p>
            </div>
            <div class="panel-badges">
              <span class="tag"><?= esc($selectedProgramme['duree_jours'] ?? '--') ?> jours</span>
              <span class="tag">IMC <?= esc($selectedProgramme['imc_min'] ?? '--') ?> - <?= esc($selectedProgramme['imc_max'] ?? '--') ?></span>
            </div>
          </div>

          <div class="summary-grid">
            <div class="summary-card">
              <span>Variation de poids</span>
              <strong><?= esc($selectedProgramme['variation_poids'] ?? '--') ?> kg</strong>
            </div>
            <div class="summary-card">
              <span>Prix public</span>
              <strong><?= number_format((float) ($selectedProgramme['prix'] ?? 0), 0, ',', ' ') ?> Ar</strong>
            </div>
            <div class="summary-card">
              <span>Objectif</span>
              <strong><?= esc($selectedProgramme['objectif_label'] ?? '--') ?></strong>
            </div>
          </div>
        </div>
      </section>

      <section class="admin-panels">
        <?php foreach ($selectedMeals as $type => $items): ?>
          <div class="admin-panel-card">
            <div class="panel-header">
              <div>
                <h3><?= esc(str_replace('_', ' ', strtolower($type))) ?></h3>
                <p><?= count($items) ?> aliment(s)</p>
              </div>
            </div>
            <div class="meal-list">
              <?php if (empty($items)): ?>
                <div class="empty-state">Aucun aliment pour ce repas.</div>
              <?php endif; ?>
              <?php foreach ($items as $item): ?>
                <div class="meal-item">
                  <div>
                    <strong><?= esc($item['nom'] ?? '') ?></strong>
                    <small><?= esc($item['quantite_g'] ?? 0) ?> g</small>
                  </div>
                  <div class="meal-macros">
                    <span><?= esc($item['calories'] ?? 0) ?> kcal</span>
                    <span>P: <?= esc($item['proteines'] ?? 0) ?>g</span>
                    <span>G: <?= esc($item['glucides'] ?? 0) ?>g</span>
                    <span>L: <?= esc($item['lipides'] ?? 0) ?>g</span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </section>

      <section class="admin-panels">
        <div class="admin-panel-card">
          <div class="panel-header">
            <div>
              <h3>Autres programmes</h3>
              <p>Sélection rapide.</p>
            </div>
          </div>
          <div class="program-list">
            <?php foreach ($programmes as $item): ?>
              <a class="program-item <?= $selectedProgramme && (int) $item['id'] === (int) $selectedProgramme['id'] ? 'active' : '' ?>" href="<?= base_url('/admin/regimes?id=' . $item['id']) ?>">
                <strong><?= esc($item['nom']) ?></strong>
                <span><?= esc($item['objectif_label'] ?? '--') ?></span>
                <small><?= esc($item['duree_jours'] ?? '--') ?> jours - <?= number_format((float) ($item['prix'] ?? 0), 0, ',', ' ') ?> Ar</small>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
