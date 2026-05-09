<?php

namespace App\Controllers;

use App\Models\AlimentModel;
use App\Models\CategorieModel;
use App\Models\CodeModel;
use App\Models\ObjectifModel;
use App\Models\ProgrammeAlimentModel;
use App\Models\ProgrammeRegimeModel;
use App\Models\SportsModel;
use App\Models\ActiviteSportiveModel;
use App\Services\AdminDashboardService;

class AdminController extends BaseController
{
     public function dashboard()
    {
        $db = \Config\Database::connect();
        
        // Statistiques globales
        $userModel = new UserModel();
        $usersByRole = $userModel->select('role, COUNT(*) as count')->groupBy('role')->findAll();
        
        $achatModel = new AchatRegimeModel();
        $revenusTotaux = $achatModel->selectSum('prix_total')->first()['prix_total'] ?? 0;
        
        $goldModel = new UserGoldModel();
        $nbGold = $goldModel->countAllResults();
        
        // Ventes par régime
        $ventesParRegime = $db->table('achatRegimeDetail')
            ->select('programmeRegime.nom, COUNT(*) as nb_ventes, SUM(achatRegimeDetail.prix_unitaire) as total')
            ->join('programmeRegime', 'programmeRegime.id = achatRegimeDetail.id_programmeRegime')
            ->groupBy('programmeRegime.id')
            ->orderBy('nb_ventes', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
        
        // Graphique mensuel (inscriptions vs achats)
        $monthlyData = $this->getMonthlyStats();
        
        // Données du service existant
        $data = (new AdminDashboardService())->getDashboardData(
            (string) ($this->request->getGet('period') ?? 'month')
        );
        
        return view('admin/dashboard', array_merge($data, [
            'usersByRole' => $usersByRole,
            'revenusTotaux' => $revenusTotaux,
            'nbGold' => $nbGold,
            'ventesParRegime' => $ventesParRegime,
            'monthLabels' => $monthlyData['labels'],
            'inscriptionsData' => $monthlyData['inscriptions'],
            'achatsData' => $monthlyData['achats']
        ]));
    }
    
    private function getMonthlyStats(): array
    {
        $db = \Config\Database::connect();
        $months = [];
        $inscriptions = [];
        $achats = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime("-$i months"));
            
            // Inscriptions du mois
            $inscriptionsCount = $db->table('user')
                ->where('MONTH(created_at)', date('m', strtotime($month)))
                ->where('YEAR(created_at)', date('Y', strtotime($month)))
                ->countAllResults();
            $inscriptions[] = $inscriptionsCount;
            
            // Achats du mois
            $achatsCount = $db->table('achatRegime')
                ->where('MONTH(date_achat)', date('m', strtotime($month)))
                ->where('YEAR(date_achat)', date('Y', strtotime($month)))
                ->countAllResults();
            $achats[] = $achatsCount;
        }
        
        return [
            'labels' => $months,
            'inscriptions' => $inscriptions,
            'achats' => $achats
        ];
    }
    
    /**
     * Gestion des codes prépayés (CRUD complet)
     */
    public function codes()
    {
        $codeModel = new CodeModel();
        $codes = $codeModel->orderBy('id', 'DESC')->findAll();
        
        // Ajouter le statut (expiré) pour l'affichage
        foreach ($codes as &$code) {
            $code['est_expire'] = $code['date_expiration'] < date('Y-m-d');
            $code['statut_label'] = $code['utilise'] ? 'Utilisé' : ($code['est_expire'] ? 'Expiré' : 'Actif');
            $code['statut_class'] = $code['utilise'] ? 'secondary' : ($code['est_expire'] ? 'warning' : 'success');
        }
        
        return view('admin/codes', ['codes' => $codes]);
    }
    
    public function createCode()
    {
        $code = trim((string) $this->request->getPost('code'));
        $montant = (float) $this->request->getPost('montant');
        $dateExpiration = trim((string) $this->request->getPost('date_expiration'));
        
        if ($code === '') {
            $code = 'CODE' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        }
        
        if ($montant <= 0) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Montant invalide.');
        }
        
        if ($dateExpiration === '') {
            $dateExpiration = date('Y-m-d', strtotime('+12 months'));
        }
        
        $codeModel = new CodeModel();
        
        // Vérifier unicité
        if ($codeModel->where('code', $code)->first()) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Ce code existe déjà.');
        }
        
        $codeModel->insert([
            'code' => $code,
            'montant' => $montant,
            'date_expiration' => $dateExpiration,
            'utilise' => 0
        ]);
        
        return redirect()->to('/admin/codes')->with('admin_success', 'Code créé : ' . $code);
    }
    
    public function updateCode()
    {
        $id = (int) $this->request->getPost('id');
        $montant = (float) $this->request->getPost('montant');
        $dateExpiration = trim((string) $this->request->getPost('date_expiration'));
        $utilise = (int) $this->request->getPost('utilise') === 1 ? 1 : 0;
        
        if ($id <= 0 || $montant <= 0) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Données invalides.');
        }
        
        $codeModel = new CodeModel();
        $codeModel->update($id, [
            'montant' => $montant,
            'date_expiration' => $dateExpiration,
            'utilise' => $utilise
        ]);
        
        return redirect()->to('/admin/codes')->with('admin_success', 'Code mis à jour.');
    }
    
    public function deleteCode()
    {
        $id = (int) $this->request->getPost('id');
        if ($id <= 0) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Code invalide.');
        }
        
        $codeModel = new CodeModel();
        $code = $codeModel->find($id);
        
        if ($code && $code['utilise']) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Impossible de supprimer un code déjà utilisé.');
        }
        
        $codeModel->delete($id);
        return redirect()->to('/admin/codes')->with('admin_success', 'Code supprimé.');
    }
    
public function generateCodes()
{
    $nombre = (int) $this->request->getPost('nombre');
    $montant = (float) $this->request->getPost('montant');
    $validiteMois = (int) $this->request->getPost('validite_mois');
    
    if ($nombre <= 0 || $nombre > 100) {
        return redirect()->to('/admin/codes')->with('admin_error', 'Nombre invalide (1-100).');
    }
    
    if ($montant <= 0) {
        return redirect()->to('/admin/codes')->with('admin_error', 'Montant invalide.');
    }
    
    $codeModel = new \App\Models\CodeModel();
    $generated = [];
    
    for ($i = 0; $i < $nombre; $i++) {
        $code = 'CODE' . strtoupper(bin2hex(random_bytes(4)));
        $codeModel->insert([
            'code' => $code,
            'montant' => $montant,
            'date_expiration' => date('Y-m-d', strtotime("+$validiteMois months")),
            'utilise' => 0
        ]);
        $generated[] = $code;
    }
    
    return redirect()->to('/admin/codes')->with('admin_success', $nombre . ' codes générés : ' . implode(', ', $generated));
}
}

