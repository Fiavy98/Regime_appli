<?php
$codes = $codes ?? [];
$totalCodes = count($codes);
$usedCodes = 0;
$expiredCodes = 0;
foreach ($codes as $code) {
    if (($code['status_label'] ?? '') === 'Utilise') {
        $usedCodes++;
    }
    if (($code['status_label'] ?? '') === 'Expire') {
        $expiredCodes++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Admin Codes</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-page">
  <div class="app-shell">
    <?= view('admin/_sidebar', ['active' => 'codes']) ?>

    <main class="content">
      <section class="admin-header">
        <div>
          <h1>Gestion des codes</h1>
          <p>Créer, suivre et supprimer les codes prépayés.</p>
        </div>
        <div class="admin-actions">
          <a class="btn btn-primary" href="#code-form">+ Créer un code</a>
        </div>
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
            <span>Total codes</span>
            <strong><?= number_format($totalCodes, 0, ',', ' ') ?></strong>
            <small>En base</small>
          </div>
          <div class="stat-icon">🔑</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Utilisés</span>
            <strong><?= number_format($usedCodes, 0, ',', ' ') ?></strong>
            <small>Déjà consommés</small>
          </div>
          <div class="stat-icon">✅</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Expirés</span>
            <strong><?= number_format($expiredCodes, 0, ',', ' ') ?></strong>
            <small>À renouveler</small>
          </div>
          <div class="stat-icon">⏰</div>
        </article>
        <article class="stat-card">
          <div>
            <span>Disponibles</span>
            <strong><?= number_format(max(0, $totalCodes - $usedCodes - $expiredCodes), 0, ',', ' ') ?></strong>
            <small>Prêts à l'emploi</small>
          </div>
          <div class="stat-icon">🎟️</div>
        </article>
      </section>

      <section class="admin-panels codes-layout">
        <div class="admin-panel-card code-create-card">
          <div class="panel-header">
            <div>
              <h3>Créer un code</h3>
              <p>Le bouton en haut ancre directement vers ce formulaire.</p>
            </div>
          </div>

          <form id="code-form" method="post" action="<?= base_url('/admin/codes/create') ?>" class="code-form">
            <input type="hidden" name="id" value="">
            <div class="admin-form-grid">
              <div>
                <label class="form-label">Code</label>
                <input class="input" type="text" name="code" placeholder="CODE2026...">
              </div>
              <div>
                <label class="form-label">Montant</label>
                <input class="input" type="number" step="0.01" min="1" name="montant" placeholder="5000" required>
              </div>
              <div>
                <label class="form-label">Expiration</label>
                <input class="input" type="date" name="date_expiration">
              </div>
              <div>
                <label class="form-label">État</label>
                <select class="input" name="utilise">
                  <option value="0">Disponible</option>
                  <option value="1">Utilisé</option>
                </select>
              </div>
            </div>
            <div class="code-form-actions">
              <button id="code-submit" class="btn btn-primary" type="submit">Créer le code</button>
              <button id="code-reset" class="btn btn-outline" type="button">Réinitialiser</button>
            </div>
          </form>
        </div>

        <div class="admin-panel-card code-list-card">
          <div class="panel-header">
            <div>
              <h3>Codes existants</h3>
              <p>Recherche en temps réel et pagination compacte.</p>
            </div>
          </div>

          <div class="code-toolbar">
            <input id="code-search" class="input" type="search" placeholder="Rechercher un code...">
          </div>

          <div class="code-table-wrap">
            <table class="code-table">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Montant</th>
                  <th>Expiration</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="code-table-body">
                <?php foreach ($codes as $code): ?>
                  <tr
                    class="code-row"
                    data-code-row
                    data-code-search="<?= esc(strtolower((string) ($code['code'] ?? '')), 'attr') ?>"
                    data-code-id="<?= esc($code['id'] ?? 0, 'attr') ?>"
                    data-code-value="<?= esc($code['code'] ?? '', 'attr') ?>"
                    data-code-amount="<?= esc($code['montant'] ?? 0, 'attr') ?>"
                    data-code-expiration="<?= esc($code['date_expiration'] ?? '', 'attr') ?>"
                    data-code-used="<?= esc((int) ($code['utilise'] ?? 0), 'attr') ?>"
                  >
                    <td><strong><?= esc($code['code'] ?? '') ?></strong></td>
                    <td><?= number_format((float) ($code['montant'] ?? 0), 0, ',', ' ') ?> Ar</td>
                    <td><?= !empty($code['date_expiration']) ? date('d M Y', strtotime($code['date_expiration'])) : '--' ?></td>
                    <td>
                      <span class="code-badge <?= $code['status_label'] === 'Utilise' ? 'success' : ($code['status_label'] === 'Expire' ? 'danger' : '') ?>">
                        <?= esc($code['status_label'] ?? 'Disponible') ?>
                      </span>
                    </td>
                    <td>
                      <div class="code-actions">
                        <button
                          class="icon-btn edit"
                          type="button"
                          data-code-edit
                          data-code-id="<?= esc($code['id'] ?? 0, 'attr') ?>"
                          data-code-value="<?= esc($code['code'] ?? '', 'attr') ?>"
                          data-code-amount="<?= esc($code['montant'] ?? 0, 'attr') ?>"
                          data-code-expiration="<?= esc($code['date_expiration'] ?? '', 'attr') ?>"
                          data-code-used="<?= esc((int) ($code['utilise'] ?? 0), 'attr') ?>"
                        >✏️</button>
                        <form method="post" action="<?= base_url('/admin/codes/delete') ?>">
                          <input type="hidden" name="id" value="<?= esc($code['id'] ?? 0) ?>">
                          <button class="icon-btn danger" type="submit">🗑️</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div id="code-empty" class="empty-state code-empty" hidden>Aucun code ne correspond à cette recherche.</div>

          <div class="code-pagination">
            <button id="code-prev" class="btn btn-outline btn-xs" type="button">Précédent</button>
            <span id="code-page-info" class="code-page-info">1 / 1</span>
            <button id="code-next" class="btn btn-outline btn-xs" type="button">Suivant</button>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    (() => {
      const rows = Array.from(document.querySelectorAll('[data-code-row]'));
      const search = document.getElementById('code-search');
      const prevBtn = document.getElementById('code-prev');
      const nextBtn = document.getElementById('code-next');
      const pageInfo = document.getElementById('code-page-info');
      const empty = document.getElementById('code-empty');
      const perPage = 8;
      let filteredRows = rows.slice();
      let page = 1;

      const form = document.getElementById('code-form');
      const submit = document.getElementById('code-submit');
      const reset = document.getElementById('code-reset');

      const setEditMode = (mode) => {
        submit.textContent = mode ? 'Mettre à jour le code' : 'Créer le code';
        form.action = mode ? '<?= base_url('/admin/codes/update') ?>' : '<?= base_url('/admin/codes/create') ?>';
      };

      const resetForm = () => {
        form.reset();
        form.querySelector('[name="id"]').value = '';
        setEditMode(false);
      };

      const applyPagination = () => {
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / perPage));
        if (page > totalPages) {
          page = totalPages;
        }

        const start = (page - 1) * perPage;
        const end = start + perPage;
        rows.forEach((row) => {
          row.hidden = true;
        });
        filteredRows.slice(start, end).forEach((row) => {
          row.hidden = false;
        });

        empty.hidden = filteredRows.length > 0;
        pageInfo.textContent = `${page} / ${totalPages}`;
        prevBtn.disabled = page <= 1;
        nextBtn.disabled = page >= totalPages;
      };

      const applySearch = () => {
        const term = (search.value || '').trim().toLowerCase();
        filteredRows = rows.filter((row) => (row.dataset.codeSearch || '').includes(term));
        page = 1;
        applyPagination();
      };

      search.addEventListener('input', applySearch);
      prevBtn.addEventListener('click', () => {
        if (page > 1) {
          page -= 1;
          applyPagination();
        }
      });
      nextBtn.addEventListener('click', () => {
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / perPage));
        if (page < totalPages) {
          page += 1;
          applyPagination();
        }
      });

      document.querySelectorAll('[data-code-edit]').forEach((button) => {
        button.addEventListener('click', () => {
          form.querySelector('[name="id"]').value = button.dataset.codeId || '';
          form.querySelector('[name="code"]').value = button.dataset.codeValue || '';
          form.querySelector('[name="montant"]').value = button.dataset.codeAmount || '';
          form.querySelector('[name="date_expiration"]').value = button.dataset.codeExpiration || '';
          form.querySelector('[name="utilise"]').value = button.dataset.codeUsed || '0';
          setEditMode(true);
          form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
      });

      reset.addEventListener('click', resetForm);
      applySearch();
    })();
  </script>
</body>
</html>
