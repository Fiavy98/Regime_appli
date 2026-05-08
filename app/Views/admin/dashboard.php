<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
</head>
<body>
  <div class="page-wrap">
    <header class="topbar">
      <div class="logo">NutriPlan Admin</div>
      <nav class="nav-links">
        <a href="<?= base_url('/admin') ?>">Dashboard</a>
      </nav>
      <div class="nav-actions">
        <a class="btn btn-outline" href="<?= base_url('/logout') ?>">Logout</a>
      </div>
    </header>

    <section class="section">
      <h2 class="section-title">Back office admin</h2>
      <p class="section-sub">Acces reserve au role admin.</p>
      <div class="card">
        <h4>Gestion</h4>
        <small>CRUD regimes, aliments, sports, codes et statistiques.</small>
      </div>
    </section>
  </div>
</body>
</html>
