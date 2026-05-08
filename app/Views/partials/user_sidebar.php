<?php
$active = $active ?? 'dashboard';
$userName = $userName ?? (session()->get('nom') ?? 'Utilisateur');
$userEmail = $userEmail ?? (session()->get('email') ?? 'Compte actif');
$userInitials = strtoupper(substr((string) $userName, 0, 2));
$walletAmount = $walletAmount ?? null;
$badgeLabel = $badgeLabel ?? 'Gold';
?>
<aside class="sidebar user-sidebar">
  <div class="sidebar-header">
    <div class="brand">
      <span>🌿 Nutri</span>
      <strong>Plan</strong>
    </div>
    <div class="avatar"><?= esc($userInitials) ?></div>
    <div class="user-info">
      <strong><?= esc($userName) ?></strong>
      <span><?= esc($userEmail) ?></span>
    </div>
    <span class="badge"><?= esc($badgeLabel) ?></span>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-title">Mon espace</span>
    <a class="sidebar-link <?= $active === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('/dashboard') ?>">📊 Mon profil & IMC</a>
    <a class="sidebar-link <?= $active === 'objectif' ? 'active' : '' ?>" href="<?= base_url('/objectif') ?>">🎯 Choisir mon objectif</a>
    <a class="sidebar-link <?= $active === 'regimes' ? 'active' : '' ?>" href="<?= base_url('/dashboard/regimes') ?>">🥗 Mes régimes</a>
    <a class="sidebar-link <?= $active === 'sports' ? 'active' : '' ?>" href="<?= base_url('/dashboard/sports') ?>">🏃 Activités sportives</a>
    <a class="sidebar-link" href="#">📄 Exporter PDF</a>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-title">Paiement</span>
    <a class="sidebar-link" href="#">👛 Mon portefeuille</a>
    <a class="sidebar-link" href="#">👑 Abonnement Gold</a>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-title">Compte</span>
    <a class="sidebar-link" href="#">⚙️ Paramètres</a>
    <a class="sidebar-link logout" href="<?= base_url('/logout') ?>">🚪 Déconnexion</a>
  </div>

  <div class="wallet">
    <span>Solde wallet</span>
    <strong><?= $walletAmount !== null ? number_format((float) $walletAmount, 0, ',', ' ') . ' Ar' : '---' ?></strong>
  </div>
</aside>
