<?php

namespace App\Controllers;

use App\Models\AlimentModel;
use App\Models\CategorieModel;
use App\Models\ObjectifModel;
use App\Models\ProgrammeAlimentModel;
use App\Models\ProgrammeRegimeModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function regimes()
    {
        $categorieModel = new CategorieModel();
        $alimentModel = new AlimentModel();
        $programmeModel = new ProgrammeRegimeModel();
        $compositionModel = new ProgrammeAlimentModel();
        $objectifModel = new ObjectifModel();

        $categories = $categorieModel->orderBy('libele', 'ASC')->findAll();
        $aliments = $alimentModel
            ->select('aliment.*, categorie.libele as categorie_label')
            ->join('categorie', 'categorie.id = aliment.id_categorie', 'left')
            ->orderBy('aliment.nom', 'ASC')
            ->findAll();

        $objectifs = $objectifModel->orderBy('id', 'ASC')->findAll();
        $programmes = $programmeModel
            ->select('programmeRegime.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = programmeRegime.id_objectif', 'left')
            ->orderBy('programmeRegime.nom', 'ASC')
            ->findAll();

        $compositions = $compositionModel
            ->select('programmeAliment.*, programmeRegime.nom as programme_nom, aliment.nom as aliment_nom')
            ->join('programmeRegime', 'programmeRegime.id = programmeAliment.id_programmeRegime', 'left')
            ->join('aliment', 'aliment.id = programmeAliment.id_aliment', 'left')
            ->orderBy('programmeRegime.nom', 'ASC')
            ->findAll();

        return view('admin/regimes', [
            'categories' => $categories,
            'aliments' => $aliments,
            'objectifs' => $objectifs,
            'programmes' => $programmes,
            'compositions' => $compositions,
        ]);
    }

    public function sports()
    {
        return view('admin/sports');
    }

    public function codes()
    {
        return view('admin/codes');
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

        (new CategorieModel())->delete($id);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Categorie supprimee.');
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

        (new AlimentModel())->delete($id);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Aliment supprime.');
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

        (new ProgrammeRegimeModel())->delete($id);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Programme supprime.');
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

        (new ProgrammeAlimentModel())->delete($id);
        return redirect()->to('/admin/regimes')->with('admin_success', 'Composition supprimee.');
    }
}
