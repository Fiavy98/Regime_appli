<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Portefeuille - NutriPlan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/public.css') ?>">
    <style>
        .wallet-card { 
            border-radius: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
        }
        .gold-badge { 
            background: linear-gradient(135deg, #FFD700, #FFA500); 
            color: #333; 
        }
        .transaction-credit { color: #22c55e; }
        .transaction-debit { color: #ef4444; }
    </style>
</head>
<body class="user-page">
    <div class="app-shell">
        <?= view('partials/user_sidebar', [
            'active' => 'wallet',
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

            <!-- Wallet Card -->
            <div class="panel wallet-card mb-4">
                <div class="card-body text-center p-5">
                    <i class="fas fa-wallet fa-3x mb-3"></i>
                    <h4 class="mb-3">Mon Portefeuille</h4>
                    <h2 class="display-4 fw-bold"><?= number_format($solde, 0) ?> <small class="fs-4">Ar</small></h2>
                    
                    <?php if ($hasGold): ?>
                        <span class="badge gold-badge mt-2 p-2"><i class="fas fa-crown"></i> Membre Gold -15%</span>
                    <?php else: ?>
                        <a href="<?= base_url('/gold') ?>" class="btn btn-outline-light mt-3 btn-sm">
                            <i class="fas fa-crown"></i> Devenir Gold (<?= number_format($goldPrice, 0) ?> Ar)
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Code Recharge -->
            <div class="panel mb-4">
                <div class="panel-head">
                    <h3><i class="fas fa-ticket-alt me-2"></i> Recharger avec un code</h3>
                </div>
                <div class="panel-body">
                    <div class="code-input-group" style="max-width: 400px;">
                        <div class="sport-filters" style="gap: 10px;">
                            <input type="text" id="codeInput" class="input" style="flex: 1;" placeholder="Entrez votre code (ex: CODE1234)" autocomplete="off">
                            <button id="applyCodeBtn" class="btn btn-primary">
                                <i class="fas fa-check"></i> Appliquer
                            </button>
                        </div>
                        <div id="codeMessage" class="mt-2 small"></div>
                    </div>
                </div>
            </div>
            
            <!-- Historique -->
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="fas fa-history me-2"></i> Historique des transactions</h3>
                </div>
                <div class="panel-body p-0">
                    <?php if (empty($historique)): ?>
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-receipt fa-2x mb-2"></i>
                            <p>Aucune transaction pour le moment</p>
                        </div>
                    <?php else: ?>
                        <div class="transaction-list">
                            <?php foreach ($historique as $transaction): ?>
                                <div class="transaction-item">
                                    <div>
                                        <?php if ($transaction['type'] === 'credit'): ?>
                                            <i class="fas fa-arrow-down transaction-credit me-2"></i>
                                            <span class="fw-bold">Crédit</span>
                                        <?php else: ?>
                                            <i class="fas fa-arrow-up transaction-debit me-2"></i>
                                            <span class="fw-bold">Débit</span>
                                        <?php endif; ?>
                                        <span class="text-muted ms-2 small">
                                            <?= date('d/m/Y H:i', strtotime($transaction['date'])) ?>
                                        </span>
                                    </div>
                                    <div class="<?= $transaction['type'] === 'credit' ? 'transaction-credit' : 'transaction-debit' ?> fw-bold">
                                        <?= $transaction['type'] === 'credit' ? '+' : '-' ?>
                                        <?= number_format($transaction['montant'], 0) ?> Ar
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#applyCodeBtn').click(function() {
                const code = $('#codeInput').val().trim();
                if (!code) {
                    $('#codeMessage').html('<div class="error" style="padding: 8px; margin-top: 8px;">Veuillez entrer un code.</div>');
                    return;
                }
                
                $('#applyCodeBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: '<?= base_url("/wallet/appliquer_code") ?>',
                    method: 'POST',
                    data: { code: code },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#codeMessage').html('<div class="success" style="padding: 8px; margin-top: 8px;">' + response.message + '</div>');
                            $('#codeInput').val('');
                            setTimeout(function() { location.reload(); }, 1500);
                        } else {
                            $('#codeMessage').html('<div class="error" style="padding: 8px; margin-top: 8px;">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#codeMessage').html('<div class="error" style="padding: 8px; margin-top: 8px;">Erreur réseau. Réessayez.</div>');
                    },
                    complete: function() {
                        $('#applyCodeBtn').prop('disabled', false).html('<i class="fas fa-check"></i> Appliquer');
                    }
                });
            });
            
            $('#codeInput').keypress(function(e) {
                if (e.which === 13) $('#applyCodeBtn').click();
            });
        });
    </script>
    <style>
        .transaction-list { border-top: 1px solid var(--border); }
        .transaction-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }
        .transaction-item:last-child { border-bottom: none; }
    </style>
</body>
</html>