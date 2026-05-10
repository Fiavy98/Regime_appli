<?php
$active = $active ?? 'dashboard';
?>
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-badge">⚙️ Admin</div>
  </div>
  <div class="sidebar-section">
    <span class="sidebar-title">Tableau de bord</span>
    <a class="sidebar-link <?= $active === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('/admin') ?>">📊 Dashboard</a>
  </div>
  <div class="sidebar-section">
    <span class="sidebar-title">Gestion</span>
    <a class="sidebar-link <?= $active === 'regimes' ? 'active' : '' ?>" href="<?= base_url('/admin/regimes') ?>">🥗 Régimes</a>
    <a class="sidebar-link <?= $active === 'sports' ? 'active' : '' ?>" href="<?= base_url('/admin/sports') ?>">🏃 Sports</a>
    <a class="sidebar-link <?= $active === 'codes' ? 'active' : '' ?>" href="<?= base_url('/admin/codes') ?>">🎟️ Codes prépayés</a>
  </div>
  <div class="sidebar-section">
    <span class="sidebar-title">Utilisateurs</span>
    <a class="sidebar-link <?= $active === 'users' ? 'active' : '' ?>" href="<?= base_url('/admin/users') ?>">👥 Utilisateurs</a>
    <a class="sidebar-link <?= $active === 'gold' ? 'active' : '' ?>" href="<?= base_url('/admin/gold') ?>">👑 Abonnés Gold</a>
  </div>
  <div class="sidebar-section">
    <span class="sidebar-title">Paramètres</span>
    <a class="sidebar-link" href="#">⚙️ Prix & Config.</a>
    <a class="sidebar-link logout" href="<?= base_url('/logout') ?>">🚪 Déconnexion</a>
  </div>
</aside>
