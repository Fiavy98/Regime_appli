<?php

namespace App\Controllers;

use App\Models\UserPortefeuilleModel;
use App\Models\MvntPrortefeuileModel;
use App\Models\CodeModel;
use App\Models\UserGoldModel;

class WalletController extends BaseController
{
    private const GOLD_PRICE = 25000; // Prix de l'abonnement Gold en Ar
    
    public function index()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }
        
        $portefeuilleModel = new UserPortefeuilleModel();
        $mvntModel = new MvntPrortefeuileModel();
        $goldModel = new UserGoldModel();
        
        $solde = $portefeuilleModel->getSolde($userId);
        $historique = $mvntModel->getHistorique($userId);
        $hasGold = $goldModel->hasGold($userId);
        
        return view('wallet', [
            'solde' => $solde,
            'historique' => $historique,
            'hasGold' => $hasGold,
            'goldPrice' => self::GOLD_PRICE
        ]);
    }
    
    /**
     * Appliquer un code de recharge (AJAX)
     */
    public function applyCode()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vous devez être connecté.'
            ]);
        }
        
        $code = trim((string) $this->request->getPost('code'));
        if ($code === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code invalide.'
            ]);
        }
        
        $codeModel = new CodeModel();
        
        // Vérifier le code
        $codeRow = $codeModel
            ->where('code', $code)
            ->where('utilise', 0)
            ->where('date_expiration >=', date('Y-m-d'))
            ->first();
        
        if (!$codeRow) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code invalide, expiré ou déjà utilisé.'
            ]);
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        // Marquer le code comme utilisé
        $codeModel->update($codeRow['id'], ['utilise' => 1]);
        
        // Créditer le portefeuille
        $portefeuilleModel = new UserPortefeuilleModel();
        $montant = (float) $codeRow['montant'];
        $portefeuilleModel->crediter($userId, $montant);
        
        // Ajouter la transaction
        $mvntModel = new MvntPrortefeuileModel();
        $mvntModel->addTransaction($userId, 'credit', $montant);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors du traitement.'
            ]);
        }
        
        $nouveauSolde = $portefeuilleModel->getSolde($userId);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Code appliqué avec succès ! +' . number_format($montant, 0) . ' Ar',
            'nouveau_solde' => $nouveauSolde
        ]);
    }
    
    /**
     * Devenir membre Gold
     */
    public function becomeGold()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }
        
        $goldModel = new UserGoldModel();
        
        // Vérifier s'il est déjà Gold
        if ($goldModel->hasGold($userId)) {
            return redirect()->to('/wallet')->with('wallet_error', 'Vous êtes déjà membre Gold.');
        }
        
        $portefeuilleModel = new UserPortefeuilleModel();
        $solde = $portefeuilleModel->getSolde($userId);
        
        if ($solde < self::GOLD_PRICE) {
            return redirect()->to('/wallet')->with('wallet_error', 'Solde insuffisant. Il vous manque ' . number_format(self::GOLD_PRICE - $solde, 0) . ' Ar.');
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        // Débiter le portefeuille
        $portefeuilleModel->debiter($userId, self::GOLD_PRICE);
        
        // Ajouter la transaction de débit
        $mvntModel = new MvntPrortefeuileModel();
        $mvntModel->addTransaction($userId, 'debit', self::GOLD_PRICE);
        
        // Activer Gold
        $goldModel->subscribe($userId, self::GOLD_PRICE);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return redirect()->to('/wallet')->with('wallet_error', 'Erreur lors de l\'activation Gold.');
        }
        
        return redirect()->to('/wallet')->with('wallet_success', 'Félicitations ! Vous êtes maintenant membre Gold. Profitez de -15% sur tous les régimes.');
    }
    
    /**
     * Page dédiée Gold (informations)
     */
    public function goldInfo()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }
        
        $goldModel = new UserGoldModel();
        $portefeuilleModel = new UserPortefeuilleModel();
        
        return view('gold', [
            'hasGold' => $goldModel->hasGold($userId),
            'solde' => $portefeuilleModel->getSolde($userId),
            'goldPrice' => self::GOLD_PRICE
        ]);
    }
}