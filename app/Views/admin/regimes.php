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
<body>
  <div class="page-wrap">
    <header class="topbar">
      <div class="logo">NutriPlan Admin</div>
      <nav class="nav-links">
        <a href="<?= base_url('/admin') ?>">Dashboard</a>
        <a href="<?= base_url('/admin/regimes') ?>">Regimes</a>
        <a href="<?= base_url('/admin/sports') ?>">Sports</a>
        <a href="<?= base_url('/admin/codes') ?>">Codes</a>
      </nav>
      <div class="nav-actions">
        <a class="btn btn-outline" href="<?= base_url('/logout') ?>">Logout</a>
      </div>
    </header>

    <section class="section admin-hero">
      <div>
        <span class="admin-kicker">Back office</span>
        <h2 class="section-title">Gestion des regimes</h2>
        <p class="section-sub">Programmes, aliments, categories et compositions.</p>
      </div>
      <div class="admin-hero-badge">CRUD avance</div>
    </section>

    <section class="section">
      <?php if (session()->getFlashdata('admin_error')): ?>
        <div class="admin-alert error"><?= esc(session()->getFlashdata('admin_error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('admin_success')): ?>
        <div class="admin-alert success"><?= esc(session()->getFlashdata('admin_success')) ?></div>
      <?php endif; ?>
    </section>

    <section class="section admin-grid">
      <div class="admin-panel">
        <div class="admin-panel-header">
          <h3>Categories</h3>
          <p>Creer et modifier les categories d'aliments.</p>
        </div>
        <form class="admin-form" method="post" action="<?= base_url('/admin/categories/create') ?>">
          <input class="input" type="text" name="libele" placeholder="Libele" required>
          <button class="btn btn-primary" type="submit">Ajouter</button>
        </form>
        <div class="admin-table">
          <div class="admin-row admin-row-head">
            <span>ID</span>
            <span>Libele</span>
            <span>Actions</span>
          </div>
          <?php foreach ($categories as $categorie): ?>
            <div class="admin-row">
              <span>#<?= esc($categorie['id']) ?></span>
              <form class="admin-inline" method="post" action="<?= base_url('/admin/categories/update') ?>">
                <input type="hidden" name="id" value="<?= esc($categorie['id']) ?>">
                <input class="input" type="text" name="libele" value="<?= esc($categorie['libele']) ?>" required>
                <button class="btn btn-outline" type="submit">Modifier</button>
              </form>
              <form method="post" action="<?= base_url('/admin/categories/delete') ?>">
                <input type="hidden" name="id" value="<?= esc($categorie['id']) ?>">
                <button class="btn btn-danger" type="submit">Supprimer</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="admin-panel">
        <div class="admin-panel-header">
          <h3>Aliments</h3>
          <p>Gestion des valeurs nutritionnelles.</p>
        </div>
        <form class="admin-form admin-form-grid" method="post" action="<?= base_url('/admin/aliments/create') ?>">
          <input class="input" type="text" name="nom" placeholder="Nom" required>
          <select class="input" name="id_categorie" required>
            <option value="">Categorie</option>
            <?php foreach ($categories as $categorie): ?>
              <option value="<?= esc($categorie['id']) ?>"><?= esc($categorie['libele']) ?></option>
            <?php endforeach; ?>
          </select>
          <input class="input" type="number" step="0.1" name="calories_pour_100g" placeholder="Calories /100g" required>
          <input class="input" type="number" step="0.1" name="proteines_g" placeholder="Proteines (g)" required>
          <input class="input" type="number" step="0.1" name="glucides_g" placeholder="Glucides (g)" required>
          <input class="input" type="number" step="0.1" name="lipides_g" placeholder="Lipides (g)" required>
          <button class="btn btn-primary" type="submit">Ajouter</button>
        </form>
        <div class="admin-table">
          <div class="admin-row admin-row-head">
            <span>Nom</span>
            <span>Categorie</span>
            <span>Macros</span>
            <span>Actions</span>
          </div>
          <?php foreach ($aliments as $aliment): ?>
            <div class="admin-row">
              <form class="admin-inline admin-inline-wide" method="post" action="<?= base_url('/admin/aliments/update') ?>">
                <input type="hidden" name="id" value="<?= esc($aliment['id']) ?>">
                <input class="input" type="text" name="nom" value="<?= esc($aliment['nom']) ?>" required>
                <select class="input" name="id_categorie" required>
                  <?php foreach ($categories as $categorie): ?>
                    <option value="<?= esc($categorie['id']) ?>" <?= $categorie['id'] == $aliment['id_categorie'] ? 'selected' : '' ?>>
                      <?= esc($categorie['libele']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="admin-macros">
                  <input class="input" type="number" step="0.1" name="calories_pour_100g" value="<?= esc($aliment['calories_pour_100g']) ?>" required>
                  <input class="input" type="number" step="0.1" name="proteines_g" value="<?= esc($aliment['proteines_g']) ?>" required>
                  <input class="input" type="number" step="0.1" name="glucides_g" value="<?= esc($aliment['glucides_g']) ?>" required>
                  <input class="input" type="number" step="0.1" name="lipides_g" value="<?= esc($aliment['lipides_g']) ?>" required>
                </div>
                <button class="btn btn-outline" type="submit">Modifier</button>
              </form>
              <form method="post" action="<?= base_url('/admin/aliments/delete') ?>">
                <input type="hidden" name="id" value="<?= esc($aliment['id']) ?>">
                <button class="btn btn-danger" type="submit">Supprimer</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="admin-panel">
        <div class="admin-panel-header">
          <h3>Programmes</h3>
          <p>Parametrer les regimes et leurs contraintes.</p>
        </div>
        <form class="admin-form admin-form-grid" method="post" action="<?= base_url('/admin/programmes/create') ?>">
          <input class="input" type="text" name="nom" placeholder="Nom" required>
          <select class="input" name="id_objectif" required>
            <option value="">Objectif</option>
            <?php foreach ($objectifs as $objectif): ?>
              <option value="<?= esc($objectif['id']) ?>"><?= esc($objectif['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <input class="input" type="number" step="0.1" name="variation_poids" placeholder="Variation poids" required>
          <input class="input" type="number" step="0.1" name="imc_min" placeholder="IMC min" required>
          <input class="input" type="number" step="0.1" name="imc_max" placeholder="IMC max" required>
          <input class="input" type="number" name="duree_jours" placeholder="Duree (jours)" required>
          <input class="input" type="number" step="0.1" name="prix" placeholder="Prix" required>
          <button class="btn btn-primary" type="submit">Ajouter</button>
        </form>
        <div class="admin-table">
          <div class="admin-row admin-row-head">
            <span>Nom</span>
            <span>Objectif</span>
            <span>IMC</span>
            <span>Prix</span>
            <span>Actions</span>
          </div>
          <?php foreach ($programmes as $programme): ?>
            <div class="admin-row">
              <form class="admin-inline admin-inline-wide" method="post" action="<?= base_url('/admin/programmes/update') ?>">
                <input type="hidden" name="id" value="<?= esc($programme['id']) ?>">
                <input class="input" type="text" name="nom" value="<?= esc($programme['nom']) ?>" required>
                <select class="input" name="id_objectif" required>
                  <?php foreach ($objectifs as $objectif): ?>
                    <option value="<?= esc($objectif['id']) ?>" <?= $objectif['id'] == $programme['id_objectif'] ? 'selected' : '' ?>>
                      <?= esc($objectif['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="admin-macros">
                  <input class="input" type="number" step="0.1" name="imc_min" value="<?= esc($programme['imc_min']) ?>" required>
                  <input class="input" type="number" step="0.1" name="imc_max" value="<?= esc($programme['imc_max']) ?>" required>
                  <input class="input" type="number" name="duree_jours" value="<?= esc($programme['duree_jours']) ?>" required>
                  <input class="input" type="number" step="0.1" name="prix" value="<?= esc($programme['prix']) ?>" required>
                </div>
                <input class="input" type="number" step="0.1" name="variation_poids" value="<?= esc($programme['variation_poids']) ?>" required>
                <button class="btn btn-outline" type="submit">Modifier</button>
              </form>
              <form method="post" action="<?= base_url('/admin/programmes/delete') ?>">
                <input type="hidden" name="id" value="<?= esc($programme['id']) ?>">
                <button class="btn btn-danger" type="submit">Supprimer</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="admin-panel">
        <div class="admin-panel-header">
          <h3>Composition des programmes</h3>
          <p>Associer aliments et types de repas.</p>
        </div>
        <form class="admin-form admin-form-grid" method="post" action="<?= base_url('/admin/compositions/create') ?>">
          <select class="input" name="id_programmeRegime" required>
            <option value="">Programme</option>
            <?php foreach ($programmes as $programme): ?>
              <option value="<?= esc($programme['id']) ?>"><?= esc($programme['nom']) ?></option>
            <?php endforeach; ?>
          </select>
          <select class="input" name="id_aliment" required>
            <option value="">Aliment</option>
            <?php foreach ($aliments as $aliment): ?>
              <option value="<?= esc($aliment['id']) ?>"><?= esc($aliment['nom']) ?></option>
            <?php endforeach; ?>
          </select>
          <input class="input" type="number" step="0.1" name="quantite_g" placeholder="Quantite (g)" required>
          <select class="input" name="type_repas" required>
            <option value="">Type repas</option>
            <option value="PETIT_DEJEUNER">PETIT_DEJEUNER</option>
            <option value="DEJEUNER">DEJEUNER</option>
            <option value="DINER">DINER</option>
            <option value="COLLATION">COLLATION</option>
          </select>
          <button class="btn btn-primary" type="submit">Ajouter</button>
        </form>
        <div class="admin-table">
          <div class="admin-row admin-row-head">
            <span>Programme</span>
            <span>Aliment</span>
            <span>Quantite</span>
            <span>Type repas</span>
            <span>Actions</span>
          </div>
          <?php foreach ($compositions as $composition): ?>
            <div class="admin-row">
              <form class="admin-inline admin-inline-wide" method="post" action="<?= base_url('/admin/compositions/update') ?>">
                <input type="hidden" name="id" value="<?= esc($composition['id']) ?>">
                <select class="input" name="id_programmeRegime" required>
                  <?php foreach ($programmes as $programme): ?>
                    <option value="<?= esc($programme['id']) ?>" <?= $programme['id'] == $composition['id_programmeRegime'] ? 'selected' : '' ?>>
                      <?= esc($programme['nom']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <select class="input" name="id_aliment" required>
                  <?php foreach ($aliments as $aliment): ?>
                    <option value="<?= esc($aliment['id']) ?>" <?= $aliment['id'] == $composition['id_aliment'] ? 'selected' : '' ?>>
                      <?= esc($aliment['nom']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <input class="input" type="number" step="0.1" name="quantite_g" value="<?= esc($composition['quantite_g']) ?>" required>
                <select class="input" name="type_repas" required>
                  <?php foreach (['PETIT_DEJEUNER', 'DEJEUNER', 'DINER', 'COLLATION'] as $typeRepas): ?>
                    <option value="<?= esc($typeRepas) ?>" <?= $typeRepas === $composition['type_repas'] ? 'selected' : '' ?>>
                      <?= esc($typeRepas) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn-outline" type="submit">Modifier</button>
              </form>
              <form method="post" action="<?= base_url('/admin/compositions/delete') ?>">
                <input type="hidden" name="id" value="<?= esc($composition['id']) ?>">
                <button class="btn btn-danger" type="submit">Supprimer</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </div>
</body>
</html>
