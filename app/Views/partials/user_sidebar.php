<?php
$active = $active ?? 'dashboard';
$userName = $userName ?? (session()->get('nom') ?? 'Utilisateur');
$userEmail = $userEmail ?? (session()->get('email') ?? 'Compte actif');
$userInitials = strtoupper(substr((string) $userName, 0, 2));
$walletAmount = $walletAmount ?? null;
$badgeLabel = $badgeLabel ?? 'Gold';
$hasGold = $hasGold ?? false;
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
    <?php if ($hasGold): ?>
      <span class="badge" style="background: linear-gradient(135deg, #FFD700, #FFA500); color: #333;">
        👑 <?= esc($badgeLabel) ?>
      </span>
    <?php else: ?>
      <span class="badge">⭐ Standard</span>
    <?php endif; ?>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-title">Mon espace</span>
    <a class="sidebar-link <?= $active === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('/dashboard') ?>">📊 Mon profil & IMC</a>
    <a class="sidebar-link <?= $active === 'objectif' ? 'active' : '' ?>" href="<?= base_url('/objectif') ?>">🎯 Choisir mon objectif</a>
    <a class="sidebar-link <?= $active === 'regimes' ? 'active' : '' ?>" href="<?= base_url('/dashboard/regimes') ?>">🥗 Mes régimes</a>
    <a class="sidebar-link <?= $active === 'sports' ? 'active' : '' ?>" href="<?= base_url('/dashboard/sports') ?>">🏃 Activités sportives</a>
    <a class="sidebar-link <?= $active === 'gold' ? 'active' : '' ?>" href="<?= base_url('/gold') ?>">👑 Abonnement Gold</a>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-title">Paiement</span>
    <a class="sidebar-link <?= $active === 'wallet' ? 'active' : '' ?>" href="<?= base_url('/wallet') ?>">👛 Mon portefeuille</a>
    <a class="sidebar-link <?= $active === 'gold' ? 'active' : '' ?>" href="<?= base_url('/gold') ?>">👑 Abonnement Gold</a>
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