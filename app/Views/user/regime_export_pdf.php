<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; margin: 0; padding: 24px; }
    .pdf-wrap { max-width: 760px; margin: 0 auto; }
    .pdf-header { text-align: center; padding-bottom: 18px; border-bottom: 2px solid #d1fae5; margin-bottom: 28px; }
    .pdf-header h1 { margin: 0; font-size: 28px; color: #064e3b; }
    .pdf-header p { margin: 8px 0 0; color: #065f46; font-size: 14px; }
    .user-summary, .programme-card { margin-bottom: 24px; }
    .user-summary h2, .programme-card h2 { margin: 0 0 12px; font-size: 18px; color: #0f5132; }
    .user-summary-grid, .programme-detail-grid { display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .summary-item, .detail-item { padding: 14px 16px; background: #f0fdf4; border-radius: 14px; border: 1px solid rgba(16, 185, 129, 0.15); }
    .summary-item strong, .detail-item strong { display: block; color: #134e4a; font-size: 13px; margin-bottom: 6px; }
    .summary-item span, .detail-item span { display: block; color: #475569; font-size: 13px; }
    .programme-card { page-break-inside: avoid; }
    .programme-card-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; margin-bottom: 14px; }
    .programme-card-header h2 { font-size: 20px; margin-bottom: 6px; }
    .programme-meta { font-size: 12px; color: #475569; }
    .status-badge { display: inline-block; padding: 6px 12px; border-radius: 999px; background: #d1fae5; color: #115e59; font-size: 11px; font-weight: 700; }
    .programme-meta span { display: inline-block; margin-right: 10px; }
    .meal-table { width: 100%; border-collapse: collapse; margin-top: 14px; }
    .meal-table th, .meal-table td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; }
    .meal-table th { background: #ecfdf5; color: #0f5132; font-size: 12px; }
    .meal-section { margin-top: 16px; }
    .meal-section h3 { margin: 0 0 10px; font-size: 16px; color: #0f5132; }
    .meal-badge { display: inline-block; padding: 4px 10px; border-radius: 999px; background: #dcfce7; color: #166534; font-size: 11px; margin-left: 8px; }
    .empty-state { font-size: 13px; color: #475569; padding: 14px 16px; background: #f8fafc; border: 1px dashed #c7d2fe; border-radius: 14px; }
    .footer-note { margin-top: 30px; padding-top: 16px; border-top: 1px solid #e2e8f0; color: #475569; font-size: 12px; }
  </style>
</head>
<body>
  <div class="pdf-wrap">
    <div class="pdf-header">
      <img src="<?= base_url('assets/images/logo.svg') ?>" alt="NutriPlan Logo" style="width: 80px; height: 80px; margin-bottom: 10px;">
      <h1>Rapport des régimes NutriPlan</h1>
      <p>Résumé personnalisé et détails des programmes enregistrés</p>
    </div>

    <section class="user-summary">
      <h2>Informations du client</h2>
      <div class="user-summary-grid">
        <div class="summary-item">
          <strong>Nom</strong>
          <span><?= esc($user['name'] ?? $user['email'] ?? 'Utilisateur') ?></span>
        </div>
        <div class="summary-item">
          <strong>Email</strong>
          <span><?= esc($user['email'] ?? 'N/A') ?></span>
        </div>
        <div class="summary-item">
          <strong>Objectif</strong>
          <span><?= esc($body['objectif_label'] ?? 'Non défini') ?></span>
        </div>
        <div class="summary-item">
          <strong>Poids / Taille</strong>
          <span><?= esc($body['poids'] ?? '--') ?> kg / <?= esc($body['taille'] ?? '--') ?> m</span>
        </div>
      </div>
    </section>

    <?php if (!empty($programmes)): ?>
      <?php foreach ($programmes as $programme): ?>
        <section class="programme-card">
          <div class="programme-card-header">
            <div>
              <h2><?= esc($programme['regime_nom'] ?? 'Programme') ?></h2>
              <div class="programme-meta">
                <span>Durée: <?= esc($programme['duree_jours'] ?? 'N/A') ?> jours</span>
                <span>Prix payé: <?= esc(number_format((float) ($programme['prix_paye'] ?? 0), 0, ',', ' ')) ?> Ar</span>
                <span>IMC <?= esc($programme['imc_min'] ?? '--') ?> - <?= esc($programme['imc_max'] ?? '--') ?></span>
              </div>
            </div>
            <span class="status-badge"><?= esc(((int) ($programme['id_statusRegime'] ?? 0)) === 2 ? 'Terminé' : 'Actif') ?></span>
          </div>

          <div class="programme-detail-grid">
            <div class="detail-item">
              <strong>Date de début</strong>
              <span><?= esc($programme['date_debut'] ?? 'N/A') ?></span>
            </div>
            <div class="detail-item">
              <strong>Date de fin</strong>
              <span><?= esc($programme['date_fin'] ?? 'N/A') ?></span>
            </div>
            <div class="detail-item">
              <strong>Variation visée</strong>
              <span><?= esc($programme['variation_poids'] ?? 'N/A') ?> kg</span>
            </div>
            <div class="detail-item">
              <strong>Statut</strong>
              <span><?= esc(((int) ($programme['id_statusRegime'] ?? 0)) === 2 ? 'Terminé' : 'Actif') ?></span>
            </div>
          </div>

          <?php if (!empty($programme['compositions'])): ?>
            <?php foreach ($programme['compositions'] as $mealType => $items): ?>
              <div class="meal-section">
                <h3><?= esc(ucwords(str_replace('_', ' ', strtolower($mealType)))) ?> <span class="meal-badge"><?= count($items) ?> aliments</span></h3>
                <table class="meal-table">
                  <thead>
                    <tr>
                      <th>Aliment</th>
                      <th>Quantité (g)</th>
                      <th>Calories</th>
                      <th>Protéines</th>
                      <th>Glucides</th>
                      <th>Lipides</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($items as $item): ?>
                      <tr>
                        <td><?= esc($item['aliment_nom'] ?? 'N/A') ?></td>
                        <td><?= esc($item['quantite_g'] ?? 0) ?></td>
                        <td><?= esc($item['calories'] ?? 0) ?></td>
                        <td><?= esc($item['proteines'] ?? 0) ?> g</td>
                        <td><?= esc($item['glucides'] ?? 0) ?> g</td>
                        <td><?= esc($item['lipides'] ?? 0) ?> g</td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">Aucune composition disponible pour ce programme.</div>
          <?php endif; ?>
        </section>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">Aucun programme acheté pour le moment.</div>
    <?php endif; ?>

    <div class="footer-note">Document généré le <?= date('d/m/Y H:i') ?> - NutriPlan</div>
  </div>
</body>
</html>
