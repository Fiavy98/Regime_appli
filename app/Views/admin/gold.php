<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des abonnements Gold - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-page">
  <div class="app-shell">
    <?= view('admin/_sidebar', ['active' => 'gold']) ?>

    <main class="content">
      <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-crown"></i> Gestion des abonnements Gold</h2>
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
            <!-- Formulaire ajout Gold -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-plus"></i> Donner l'abonnement Gold</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/gold/subscribe') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label>Utilisateur</label>
                                <select name="id_user" class="form-control" required>
                                    <option value="">-- Choisir un utilisateur --</option>
                                    <?php foreach ($nonGoldUsers as $user): ?>
                                    <option value="<?= $user['id'] ?>">
                                        <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning w-100" <?= empty($nonGoldUsers) ? 'disabled' : '' ?>>
                                <i class="fas fa-crown"></i> Donner Gold
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Liste des abonnés Gold -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-crown"></i> Abonnés Gold actifs</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date d'abonnement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($goldUsers as $user): ?>
                                <tr>
                                    <td><?= $user['id_user'] ?></td>
                                    <td>
                                        <i class="fas fa-crown text-warning me-2"></i>
                                        <?= htmlspecialchars($user['name']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['date_achat'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#unsubscribeModal<?= $user['id_user'] ?>" title="Retirer Gold">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($goldUsers)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-crown text-warning fa-2x mb-2"></i><br>
                                        Aucun abonné Gold pour le moment
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </main>
  </div>

  <?php foreach ($goldUsers as $user): ?>
    <!-- Modal Retirer Gold -->
    <div class="modal fade" id="unsubscribeModal<?= $user['id_user'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?= base_url('admin/gold/unsubscribe') ?>" method="POST" class="modal-content">
          <?= csrf_field() ?>
          <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
          <div class="modal-header">
            <h5>Retirer l'abonnement Gold</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p>Retirer l'abonnement Gold de <strong><?= htmlspecialchars($user['name']) ?></strong> (<?= htmlspecialchars($user['email']) ?>) ?</p>
            <div class="alert alert-warning">
              <strong>Attention :</strong> L'utilisateur perdra immédiatement l'accès aux fonctionnalités Gold.
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Retirer Gold</button>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>