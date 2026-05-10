<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des codes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
      <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-page">
  <div class="app-shell">
    <?= view('admin/_sidebar', ['active' => 'codes']) ?>
    
    <main class="content">
      <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-ticket-alt"></i> Gestion des codes prépayés</h2>
                <hr>
                
                <?php if (session()->getFlashdata('admin_success')): ?>
                    <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('admin_success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('admin_error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('admin_error') ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="row">
            <!-- Formulaire ajout code -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plus"></i> Nouveau code</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/codes/create') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label>Code (optionnel - généré auto)</label>
                                <input type="text" name="code" class="form-control" placeholder="LAISSEZ VIDE POUR AUTO">
                            </div>
                            <div class="mb-3">
                                <label>Montant (Ar) *</label>
                                <input type="number" name="montant" class="form-control" required step="100" min="100">
                            </div>
                            <div class="mb-3">
                                <label>Date d'expiration</label>
                                <input type="date" name="date_expiration" class="form-control" value="<?= date('Y-m-d', strtotime('+12 months')) ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Créer le code</button>
                        </form>
                    </div>
                </div>
                
                <!-- Génération en masse -->
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-layer-group"></i> Génération multiple</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/codes/generate') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label>Nombre de codes (1-100)</label>
                                <input type="number" name="nombre" class="form-control" required min="1" max="100">
                            </div>
                            <div class="mb-3">
                                <label>Montant unitaire (Ar)</label>
                                <input type="number" name="montant" class="form-control" required step="500" min="500">
                            </div>
                            <div class="mb-3">
                                <label>Validité (mois)</label>
                                <input type="number" name="validite_mois" class="form-control" value="12" required>
                            </div>
                            <button type="submit" class="btn btn-secondary w-100">Générer</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Liste des codes -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Codes existants</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Montant</th>
                                    <th>Expiration</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($codes as $code): ?>
                                <tr>
                                    <td><?= $code['id'] ?></td>
                                    <td><code><?= $code['code'] ?></code></td>
                                    <td><?= number_format($code['montant'], 0) ?> Ar</td>
                                    <td><?= date('d/m/Y', strtotime($code['date_expiration'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $code['statut_class'] ?>">
                                            <?= $code['statut_label'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $code['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $code['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </main>
  </div>

  <?php foreach ($codes as $code): ?>
    <!-- Modal Édition -->
    <div class="modal fade" id="editModal<?= $code['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?= base_url('admin/codes/update') ?>" method="POST" class="modal-content">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= $code['id'] ?>">
          <div class="modal-header">
            <h5>Modifier le code <?= $code['code'] ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Montant</label>
              <input type="number" name="montant" class="form-control" value="<?= $code['montant'] ?>" required>
            </div>
            <div class="mb-3">
              <label>Date expiration</label>
              <input type="date" name="date_expiration" class="form-control" value="<?= $code['date_expiration'] ?>" required>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" name="utilise" value="1" class="form-check-input" <?= $code['utilise'] ? 'checked' : '' ?>>
              <label class="form-check-label">Marquer comme utilisé</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Suppression -->
    <div class="modal fade" id="deleteModal<?= $code['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?= base_url('admin/codes/delete') ?>" method="POST" class="modal-content">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= $code['id'] ?>">
          <div class="modal-header">
            <h5>Supprimer le code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p>Supprimer le code <strong><?= $code['code'] ?></strong> ?</p>
            <?php if ($code['utilise']): ?>
              <div class="alert alert-warning">Ce code a déjà été utilisé !</div>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger" <?= $code['utilise'] ? 'disabled' : '' ?>>Supprimer</button>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
