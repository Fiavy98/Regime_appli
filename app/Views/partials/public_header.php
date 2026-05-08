<?php
$title = $title ?? 'NutriPlan';
$bodyClass = $bodyClass ?? '';
$nav = $nav ?? [
    ['label' => 'Accueil', 'href' => base_url('/')],
    ['label' => 'Regimes', 'href' => base_url('/regimes')],
    ['label' => 'Sports', 'href' => base_url('/sports')],
];
$action = $action ?? ['label' => 'Se connecter', 'href' => base_url('/login'), 'class' => 'btn btn-outline'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
</head>
<body class="<?= esc($bodyClass) ?>">
  <div class="page-wrap">
    <header class="topbar">
      <div class="logo"> 🌿 Nutri <span>Plan</span></div>
      <nav class="nav-links">
        <?php foreach ($nav as $item): ?>
          <a href="<?= esc($item['href']) ?>"><?= esc($item['label']) ?></a>
        <?php endforeach; ?>
      </nav>
      <div class="nav-actions">
        <a class="<?= esc($action['class'] ?? 'btn btn-outline') ?>" href="<?= esc($action['href'] ?? '#') ?>"><?= esc($action['label'] ?? '') ?></a>
      </div>
    </header>
