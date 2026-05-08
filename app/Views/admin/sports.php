<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan - Admin Sports</title>
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
        <a href="<?= base_url('/admin/regimes') ?>">Regimes</a>
        <a href="<?= base_url('/admin/sports') ?>">Sports</a>
        <a href="<?= base_url('/admin/codes') ?>">Codes</a>
      </nav>
      <div class="nav-actions">
        <a class="btn btn-outline" href="<?= base_url('/logout') ?>">Logout</a>
      </div>
    </header>

    <section class="section">
      <h2 class="section-title">Gestion des sports</h2>
      <p class="section-sub">CRUD sports et activites sportives (placeholder).</p>
      <div class="card">
        <h4>Sports</h4>
        <small>Cette page sera reliee aux tables sports et activiteSportive.</small>
      </div>
    </section>
  </div>
</body>
</html>
