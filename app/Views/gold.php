<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir Gold - NutriPlan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
    <style>
        .gold-card { 
            background: linear-gradient(135deg, #FFD700, #FFA500); 
            border-radius: 30px;
            transition: transform 0.3s;
        }
        .gold-card:hover { transform: translateY(-5px); }
        .benefit-item { border-left: 4px solid #FFD700; padding-left: 15px; margin: 20px 0; }
        .price-badge { font-size: 48px; font-weight: bold; color: #FFD700; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
    </style>
</head>
<body class="user-page">
    <div class="app-shell">
        <?= view('partials/user_sidebar', [
            'active' => 'gold',
            'userName' => session()->get('nom') ?? 'Utilisateur',
            'userEmail' => session()->get('email') ?? 'Compte actif',
            'walletAmount' => $solde ?? 0,
            'hasGold' => $hasGold ?? false,
            'badgeLabel' => ($hasGold ?? false) ? 'Gold' : 'Standard'
        ]) ?>

        <main class="content">
            <?php if (session()->getFlashdata('wallet_success')): ?>
                <div class="success"><?= session()->getFlashdata('wallet_success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('wallet_error')): ?>
                <div class="error"><?= session()->getFlashdata('wallet_error') ?></div>
            <?php endif; ?>

            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <?php if ($hasGold): ?>
                            <div class="panel text-center">
                                <i class="fas fa-crown fa-3x mb-3" style="color: #FFD700;"></i>
                                <h4>Vous êtes déjà membre Gold !</h4>
                                <p>Profitez de vos avantages : -15% sur tous les régimes.</p>
                                <a href="<?= base_url('/wallet') ?>" class="btn btn-outline">Voir mon portefeuille</a>
                            </div>
                        <?php else: ?>
                            <div class="panel gold-card mb-4">
                                <div class="card-body text-center p-5">
                                    <i class="fas fa-crown fa-4x mb-3" style="color: #FFF;"></i>
                                    <h2 class="fw-bold">Devenir Membre Gold</h2>
                                    <p class="lead">Débloquez tous les avantages premium</p>
                                    <div class="price-badge my-3"><?= number_format($goldPrice, 0) ?> Ar</div>
                                    <p class="text-muted">Paiement unique - Valable à vie</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel h-100">
                                        <div class="panel-head">
                                            <h5><i class="fas fa-gift text-warning"></i> Avantages Gold</h5>
                                        </div>
                                        <div class="panel-body">
                                            <div class="benefit-item">
                                                <i class="fas fa-percent text-success"></i> <strong>15% de réduction</strong> sur tous les régimes
                                            </div>
                                            <div class="benefit-item">
                                                <i class="fas fa-trophy text-warning"></i> Badge exclusif sur votre profil
                                            </div>
                                            <div class="benefit-item">
                                                <i class="fas fa-chart-line text-info"></i> Accès prioritaire aux nouveaux régimes
                                            </div>
                                            <div class="benefit-item">
                                                <i class="fas fa-file-pdf text-danger"></i> Export PDF illimité
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel h-100">
                                        <div class="panel-head">
                                            <h5><i class="fas fa-wallet"></i> Votre solde</h5>
                                        </div>
                                        <div class="panel-body">
                                            <h3 class="text-primary"><?= number_format($solde, 0) ?> Ar</h3>
                                            
                                            <?php if ($solde >= $goldPrice): ?>
                                                <form action="<?= base_url('/wallet/devenir_gold') ?>" method="POST" class="mt-3">
                                                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" onclick="return confirm('Confirmer l\'achat Gold à <?= number_format($goldPrice, 0) ?> Ar ?')">
                                                        <i class="fas fa-crown"></i> Devenir Gold maintenant
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <div class="error mt-3" style="padding: 12px;">
                                                    Solde insuffisant. Il vous manque <strong><?= number_format($goldPrice - $solde, 0) ?> Ar</strong>.
                                                    <br><a href="<?= base_url('/wallet') ?>" class="alert-link">Rechargez votre portefeuille</a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel mt-4 bg-light">
                                <div class="panel-body text-center">
                                    <h6>Exemple d'économie avec Gold</h6>
                                    <table style="width: 100%; margin-top: 12px;">
                                        <tr>
                                            <td>Régime 30 jours</td>
                                            <td class="text-end">3 000 Ar</td>
                                            <td class="text-end">→</td>
                                            <td class="text-end text-success fw-bold">2 550 Ar</td>
                                        </tr>
                                        <tr>
                                            <td>Régime 45 jours</td>
                                            <td class="text-end">4 500 Ar</td>
                                            <td class="text-end">→</td>
                                            <td class="text-end text-success fw-bold">3 825 Ar</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>