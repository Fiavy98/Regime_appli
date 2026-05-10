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
use App\Models\UserModel;
use App\Models\AchatRegimeModel;
use App\Models\UserGoldModel;
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

    public function regimes()
    {
        $selectedId = (int) $this->request->getGet('id');
        $programmeModel = new ProgrammeRegimeModel();
        $objectifModel = new ObjectifModel();
        $alimentModel = new AlimentModel();
        $compositionModel = new ProgrammeAlimentModel();

        $programmes = $programmeModel
            ->select('programmeRegime.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = programmeRegime.id_objectif', 'left')
            ->orderBy('programmeRegime.nom', 'ASC')
            ->findAll();

        if ($selectedId <= 0 && !empty($programmes)) {
            $selectedId = (int) $programmes[0]['id'];
        }

        $programme = null;
        foreach ($programmes as $item) {
            if ((int) $item['id'] === $selectedId) {
                $programme = $item;
                break;
            }
        }

        $compositions = [];
        if ($selectedId > 0) {
            $compositions = $compositionModel
                ->select('programmeAliment.*, aliment.nom, aliment.calories_pour_100g, aliment.proteines_g, aliment.glucides_g, aliment.lipides_g')
                ->join('aliment', 'aliment.id = programmeAliment.id_aliment', 'left')
                ->where('programmeAliment.id_programmeRegime', $selectedId)
                ->orderBy('programmeAliment.type_repas', 'ASC')
                ->findAll();
        }

        return view('admin/regimes', [
            'programmes' => $programmes,
            'programme' => $programme,
            'meals' => $this->groupMeals($compositions),
            'objectifs' => $objectifModel->orderBy('name', 'ASC')->findAll(),
            'aliments' => $alimentModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function createProgramme()
    {
        $nom = trim((string) $this->request->getPost('nom'));
        $idObjectif = (int) $this->request->getPost('id_objectif');
        $variationPoids = (float) $this->request->getPost('variation_poids');
        $imcMin = (float) $this->request->getPost('imc_min');
        $imcMax = (float) $this->request->getPost('imc_max');
        $dureeJours = (int) $this->request->getPost('duree_jours');
        $prix = (float) $this->request->getPost('prix');

        if ($nom === '' || $idObjectif <= 0 || $dureeJours <= 0 || $prix <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Données de programme invalides.');
        }

        $programmeModel = new ProgrammeRegimeModel();
        $programmeModel->insert([
            'nom' => $nom,
            'id_objectif' => $idObjectif,
            'variation_poids' => $variationPoids,
            'imc_min' => $imcMin,
            'imc_max' => $imcMax,
            'duree_jours' => $dureeJours,
            'prix' => $prix,
        ]);

        $insertId = $programmeModel->getInsertID();
        return redirect()->to('/admin/regimes?id=' . ($insertId > 0 ? $insertId : ''))->with('admin_success', 'Programme créé avec succès.');
    }

    public function createComposition()
    {
        $programmeId = (int) $this->request->getPost('id_programmeRegime');
        $alimentId = (int) $this->request->getPost('id_aliment');
        $quantite = (float) $this->request->getPost('quantite_g');
        $typeRepas = trim((string) $this->request->getPost('type_repas'));

        if ($programmeId <= 0 || $alimentId <= 0 || $quantite <= 0 || $typeRepas === '') {
            return redirect()->to('/admin/regimes?id=' . $programmeId)->with('admin_error', 'Données de composition invalides.');
        }

        $compositionModel = new ProgrammeAlimentModel();
        $compositionModel->insert([
            'id_programmeRegime' => $programmeId,
            'id_aliment' => $alimentId,
            'quantite_g' => $quantite,
            'type_repas' => $typeRepas,
        ]);

        return redirect()->to('/admin/regimes?id=' . $programmeId)->with('admin_success', 'Composition ajoutée avec succès.');
    }

    private function groupMeals(array $rows): array
    {
        $groups = [
            'PETIT_DEJEUNER' => [],
            'DEJEUNER' => [],
            'DINER' => [],
            'COLLATION' => [],
        ];

        foreach ($rows as $row) {
            $type = $row['type_repas'] ?? 'COLLATION';
            if (!isset($groups[$type])) {
                $groups[$type] = [];
            }

            $quantity = (float) ($row['quantite_g'] ?? 0);
            $factor = $quantity > 0 ? $quantity / 100 : 0;
            $row['calories'] = round(((float) ($row['calories_pour_100g'] ?? 0)) * $factor);
            $row['proteines'] = round(((float) ($row['proteines_g'] ?? 0)) * $factor, 1);
            $row['glucides'] = round(((float) ($row['glucides_g'] ?? 0)) * $factor, 1);
            $row['lipides'] = round(((float) ($row['lipides_g'] ?? 0)) * $factor, 1);

            $groups[$type][] = $row;
        }

        return $groups;
    }

    public function sports()
    {
        $niveau = trim((string) $this->request->getGet('niveau'));
        $activiteModel = new ActiviteSportiveModel();

        $query = $activiteModel
            ->select('activiteSportive.*, sports.name as sport_name, sports.category as category, objectif.name as objectif_label')
            ->join('sports', 'sports.id = activiteSportive.id_sport', 'left')
            ->join('objectif', 'objectif.id = activiteSportive.id_objectif', 'left')
            ->orderBy('activiteSportive.niveau', 'ASC');

        if ($niveau !== '') {
            $query->where('activiteSportive.niveau', $niveau);
        }

        return view('admin/sports', [
            'activites' => $query->findAll(),
            'niveau' => $niveau,
        ]);
    }

    public function createCategorie()
    {
        return redirect()->to('/admin')->with('admin_error', 'Gestion des catégories non disponible pour le moment.');
    }

    public function updateCategorie()
    {
        return redirect()->to('/admin')->with('admin_error', 'Gestion des catégories non disponible pour le moment.');
    }

    public function deleteCategorie()
    {
        return redirect()->to('/admin')->with('admin_error', 'Gestion des catégories non disponible pour le moment.');
    }

    public function createAliment()
    {
        return redirect()->to('/admin')->with('admin_error', 'Gestion des aliments non disponible pour le moment.');
    }

    public function updateAliment()
    {
        return redirect()->to('/admin')->with('admin_error', 'Gestion des aliments non disponible pour le moment.');
    }

    public function deleteAliment()
    {
        return redirect()->to('/admin')->with('admin_error', 'Gestion des aliments non disponible pour le moment.');
    }

    public function updateProgramme()
    {
        return redirect()->to('/admin/regimes')->with('admin_error', 'Mise à jour de programme non disponible pour le moment.');
    }

    public function deleteProgramme()
    {
        return redirect()->to('/admin/regimes')->with('admin_error', 'Suppression de programme non disponible pour le moment.');
    }

    public function updateComposition()
    {
        return redirect()->to('/admin/regimes')->with('admin_error', 'Mise à jour de composition non disponible pour le moment.');
    }

    public function deleteComposition()
    {
        return redirect()->to('/admin/regimes')->with('admin_error', 'Suppression de composition non disponible pour le moment.');
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
            
            // Inscriptions du mois — on utilise la table userBody car la table user ne contient pas de colonne de date
            $inscriptionsCount = $db->table('userBody')
                ->where('MONTH(date)', date('m', strtotime($month)))
                ->where('YEAR(date)', date('Y', strtotime($month)))
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

