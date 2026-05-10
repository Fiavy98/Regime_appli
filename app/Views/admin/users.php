<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-page">
  <div class="app-shell">
    <?= view('admin/_sidebar', ['active' => 'users']) ?>

    <main class="content">
      <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-users"></i> Gestion des utilisateurs</h2>
                <hr>

                <?php if (session()->getFlashdata('admin_success')): ?>
                    <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('admin_success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('admin_error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('admin_error') ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Utilisateurs inscrits</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Genre</th>
                                    <th>Âge</th>
                                    <th>Rôle</th>
                                    <th>Statut Gold</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= $user['genre'] === 'Homme' ? 'Homme' : 'Femme' ?></td>
                                    <td><?= $user['age'] ?> ans</td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['is_gold']): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-crown"></i> Gold
                                                <?php if ($user['gold_info']): ?>
                                                    (<?= date('d/m/Y', strtotime($user['gold_info']['date_achat'])) ?>)
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Standard</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id'] ?>" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($user['role'] !== 'admin'): ?>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $user['id'] ?>" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
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

  <?php foreach ($users as $user): ?>
    <!-- Modal Édition -->
    <div class="modal fade" id="editModal<?= $user['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?= base_url('admin/users/update') ?>" method="POST" class="modal-content">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= $user['id'] ?>">
          <div class="modal-header">
            <h5>Modifier l'utilisateur <?= htmlspecialchars($user['name']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Rôle</label>
              <select name="role" class="form-control" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Suppression -->
    <div class="modal fade" id="deleteModal<?= $user['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?= base_url('admin/users/delete') ?>" method="POST" class="modal-content">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= $user['id'] ?>">
          <div class="modal-header">
            <h5>Supprimer l'utilisateur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p>Supprimer l'utilisateur <strong><?= htmlspecialchars($user['name']) ?></strong> (<?= htmlspecialchars($user['email']) ?>) ?</p>
            <div class="alert alert-warning">
              <strong>Attention :</strong> Cette action est irréversible et supprimera toutes les données associées à cet utilisateur.
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>