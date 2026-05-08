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
        $data = (new AdminDashboardService())->getDashboardData(
            (string) ($this->request->getGet('period') ?? 'month')
        );

        return view('admin/dashboard', [
            'period' => $data['period'],
            'usersTotal' => $data['usersTotal'],
            'usersCurrent' => $data['usersCurrent'],
            'revenusCurrent' => $data['revenusCurrent'],
            'goldCurrent' => $data['goldCurrent'],
            'regimesCurrent' => $data['regimesCurrent'],
            'growthUsers' => $data['growthUsers'],
            'growthRevenus' => $data['growthRevenus'],
            'growthGold' => $data['growthGold'],
            'growthRegimes' => $data['growthRegimes'],
            'monthLabels' => $data['monthLabels'],
            'inscriptions' => $data['inscriptions'],
            'ventes' => $data['ventes'],
            'ventesObjectif' => $data['ventesObjectif'],
            'totalObjectiveSales' => $data['totalObjectiveSales'],
            'ventesObjectifLabels' => $data['ventesObjectifLabels'],
            'ventesObjectifValues' => $data['ventesObjectifValues'],
            'achats' => $data['achats'],
            'codes' => $data['codes'],
        ]);
    }

    public function regimes()
    {
        $programmeModel = new ProgrammeRegimeModel();
        $objectifModel = new ObjectifModel();
        $compositionModel = new ProgrammeAlimentModel();
        $alimentModel = new AlimentModel();

        $programmes = $programmeModel
            ->select('programmeRegime.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = programmeRegime.id_objectif', 'left')
            ->orderBy('programmeRegime.nom', 'ASC')
            ->findAll();
        $objectifs = $objectifModel->orderBy('id', 'ASC')->findAll();
        $aliments = $alimentModel->select('aliment.*, categorie.libele as categorie_label')
            ->join('categorie', 'categorie.id = aliment.id_categorie', 'left')
            ->orderBy('aliment.nom', 'ASC')
            ->findAll();

        $selectedId = (int) ($this->request->getGet('id') ?? 0);
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

        $grouped = (new AdminDashboardService())->groupMeals($compositions);

        return view('admin/regimes', [
            'programmes' => $programmes,
            'programme' => $programme,
            'meals' => $grouped,
            'objectifs' => $objectifs,
            'aliments' => $aliments,
        ]);
    }

    public function sports()
    {
        $sportsModel = new SportsModel();
        $activiteModel = new ActiviteSportiveModel();
        $objectifModel = new ObjectifModel();

        $niveau = (string) ($this->request->getGet('niveau') ?? '');
        $niveau = in_array($niveau, ['FAIBLE', 'MOYEN', 'ELEVE'], true) ? $niveau : '';

        $builder = $activiteModel
            ->select('activiteSportive.*, sports.name as sport_name, sports.category, objectif.name as objectif_label')
            ->join('sports', 'sports.id = activiteSportive.id_sport', 'left')
            ->join('objectif', 'objectif.id = activiteSportive.id_objectif', 'left');

        if ($niveau !== '') {
            $builder->where('activiteSportive.niveau', $niveau);
        }

        $activites = $builder->orderBy('sports.name', 'ASC')->findAll();

        return view('admin/sports', [
            'niveau' => $niveau,
            'activites' => $activites,
        ]);
    }

    public function codes()
    {
        $codes = (new AdminDashboardService())->getCodes();

        return view('admin/codes', [
            'codes' => $codes,
        ]);
    }

    public function createCode()
    {
        return $this->saveCode();
    }

    public function updateCode()
    {
        return $this->saveCode(true);
    }

    private function saveCode(bool $isUpdate = false)
    {
        $id = (int) $this->request->getPost('id');
        $code = trim((string) $this->request->getPost('code'));
        $montant = (float) $this->request->getPost('montant');
        $dateExpiration = trim((string) $this->request->getPost('date_expiration'));
        $utilise = (int) $this->request->getPost('utilise') === 1 ? 1 : 0;

        if ($code === '') {
            $code = 'CODE' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        }

        if ($montant <= 0) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Montant invalide.');
        }

        if ($dateExpiration === '') {
            $dateExpiration = date('Y-m-d', strtotime('+12 months'));
        }

        try {
            $payload = [
                'code' => $code,
                'montant' => $montant,
                'date_expiration' => $dateExpiration,
                'utilise' => $utilise,
            ];

            $codeModel = new CodeModel();
            if ($isUpdate) {
                if ($id <= 0) {
                    return redirect()->to('/admin/codes')->with('admin_error', 'Code invalide.');
                }
                $codeModel->update($id, $payload);
            } else {
                $codeModel->insert($payload);
            }

            return redirect()->to('/admin/codes')->with('admin_success', $isUpdate ? 'Code mis a jour.' : 'Code cree avec succes.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/codes')->with('admin_error', $isUpdate ? 'Impossible de modifier le code.' : 'Impossible de creer le code. Verifie qu il est unique.');
        }
    }

    public function deleteCode()
    {
        $id = (int) $this->request->getPost('id');
        if ($id <= 0) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Code invalide.');
        }

        try {
            (new CodeModel())->delete($id);
            return redirect()->to('/admin/codes')->with('admin_success', 'Code supprime.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/codes')->with('admin_error', 'Suppression impossible.');
        }
    }

    public function createCategorie()
    {
        $libele = trim((string) $this->request->getPost('libele'));
        if ($libele === '') {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Libele obligatoire.');
        }

        (new CategorieModel())->insert(['libele' => $libele]);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Categorie ajoutee.');
    }

    public function updateCategorie()
    {
        $id = (int) $this->request->getPost('id');
        $libele = trim((string) $this->request->getPost('libele'));

        if ($id <= 0 || $libele === '') {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Categorie invalide.');
        }

        (new CategorieModel())->update($id, ['libele' => $libele]);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Categorie mise a jour.');
    }

    public function deleteCategorie()
    {
        $id = (int) $this->request->getPost('id');
        if ($id <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Categorie invalide.');
        }

        try {
            (new CategorieModel())->delete($id);
            return redirect()->to('/admin/regimes')->with('admin_success', 'Categorie supprimee.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Categorie utilisee par des aliments. Supprime ou reassigne les aliments avant de continuer.');
        }
    }

    public function createAliment()
    {
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'id_categorie' => (int) $this->request->getPost('id_categorie'),
            'calories_pour_100g' => (float) $this->request->getPost('calories_pour_100g'),
            'proteines_g' => (float) $this->request->getPost('proteines_g'),
            'glucides_g' => (float) $this->request->getPost('glucides_g'),
            'lipides_g' => (float) $this->request->getPost('lipides_g'),
        ];

        if ($data['nom'] === '' || $data['id_categorie'] <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Aliment invalide.');
        }

        (new AlimentModel())->insert($data);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Aliment ajoute.');
    }

    public function updateAliment()
    {
        $id = (int) $this->request->getPost('id');
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'id_categorie' => (int) $this->request->getPost('id_categorie'),
            'calories_pour_100g' => (float) $this->request->getPost('calories_pour_100g'),
            'proteines_g' => (float) $this->request->getPost('proteines_g'),
            'glucides_g' => (float) $this->request->getPost('glucides_g'),
            'lipides_g' => (float) $this->request->getPost('lipides_g'),
        ];

        if ($id <= 0 || $data['nom'] === '' || $data['id_categorie'] <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Aliment invalide.');
        }

        (new AlimentModel())->update($id, $data);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Aliment mis a jour.');
    }

    public function deleteAliment()
    {
        $id = (int) $this->request->getPost('id');
        if ($id <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Aliment invalide.');
        }

        try {
            (new AlimentModel())->delete($id);
            return redirect()->to('/admin/regimes')->with('admin_success', 'Aliment supprime.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Aliment utilise dans un programme. Supprime les compositions avant de continuer.');
        }
    }

    public function createProgramme()
    {
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'id_objectif' => (int) $this->request->getPost('id_objectif'),
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'imc_min' => (float) $this->request->getPost('imc_min'),
            'imc_max' => (float) $this->request->getPost('imc_max'),
            'duree_jours' => (int) $this->request->getPost('duree_jours'),
            'prix' => (float) $this->request->getPost('prix'),
        ];

        if ($data['nom'] === '' || $data['id_objectif'] <= 0 || $data['duree_jours'] <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Programme invalide.');
        }

        (new ProgrammeRegimeModel())->insert($data);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Programme ajoute.');
    }

    public function updateProgramme()
    {
        $id = (int) $this->request->getPost('id');
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'id_objectif' => (int) $this->request->getPost('id_objectif'),
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'imc_min' => (float) $this->request->getPost('imc_min'),
            'imc_max' => (float) $this->request->getPost('imc_max'),
            'duree_jours' => (int) $this->request->getPost('duree_jours'),
            'prix' => (float) $this->request->getPost('prix'),
        ];

        if ($id <= 0 || $data['nom'] === '' || $data['id_objectif'] <= 0 || $data['duree_jours'] <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Programme invalide.');
        }

        (new ProgrammeRegimeModel())->update($id, $data);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Programme mis a jour.');
    }

    public function deleteProgramme()
    {
        $id = (int) $this->request->getPost('id');
        if ($id <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Programme invalide.');
        }

        try {
            (new ProgrammeRegimeModel())->delete($id);
            return redirect()->to('/admin/regimes')->with('admin_success', 'Programme supprime.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Programme lie a des achats ou compositions. Supprime les dependances avant de continuer.');
        }
    }

    public function createComposition()
    {
        $data = [
            'id_programmeRegime' => (int) $this->request->getPost('id_programmeRegime'),
            'id_aliment' => (int) $this->request->getPost('id_aliment'),
            'quantite_g' => (float) $this->request->getPost('quantite_g'),
            'type_repas' => trim((string) $this->request->getPost('type_repas')),
        ];

        if ($data['id_programmeRegime'] <= 0 || $data['id_aliment'] <= 0 || $data['quantite_g'] <= 0 || $data['type_repas'] === '') {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Composition invalide.');
        }

        (new ProgrammeAlimentModel())->insert($data);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Composition ajoutee.');
    }

    public function updateComposition()
    {
        $id = (int) $this->request->getPost('id');
        $data = [
            'id_programmeRegime' => (int) $this->request->getPost('id_programmeRegime'),
            'id_aliment' => (int) $this->request->getPost('id_aliment'),
            'quantite_g' => (float) $this->request->getPost('quantite_g'),
            'type_repas' => trim((string) $this->request->getPost('type_repas')),
        ];

        if ($id <= 0 || $data['id_programmeRegime'] <= 0 || $data['id_aliment'] <= 0 || $data['quantite_g'] <= 0 || $data['type_repas'] === '') {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Composition invalide.');
        }

        (new ProgrammeAlimentModel())->update($id, $data);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Composition mise a jour.');
    }

    public function deleteComposition()
    {
        $id = (int) $this->request->getPost('id');
        if ($id <= 0) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Composition invalide.');
        }

        try {
            (new ProgrammeAlimentModel())->delete($id);
            return redirect()->to('/admin/regimes')->with('admin_success', 'Composition supprimee.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/regimes')->with('admin_error', 'Suppression impossible pour le moment.');
        }
    }

}
