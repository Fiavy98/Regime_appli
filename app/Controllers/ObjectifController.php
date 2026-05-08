<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\UserBodyModel;

class ObjectifController extends BaseController
{
    public function index()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $objectifModel = new ObjectifModel();
        $userBodyModel = new UserBodyModel();

        $objectifs = $objectifModel->orderBy('id', 'ASC')->findAll();
        $body = $userBodyModel
            ->select('userBody.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = userBody.id_objectif', 'left')
            ->where('id_user', $userId)
            ->orderBy('date', 'DESC')
            ->first();

        return view('objectif', [
            'objectifs' => $objectifs,
            'currentId' => (int) ($body['id_objectif'] ?? 0),
            'currentLabel' => $body['objectif_label'] ?? null,
        ]);
    }

    public function choisir()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $idObjectif = (int) $this->request->getPost('id_objectif');
        if ($idObjectif <= 0) {
            return redirect()->to('/objectif')->with('objectif_error', 'Objectif invalide.');
        }

        $objectif = (new ObjectifModel())->find($idObjectif);
        if (!$objectif) {
            return redirect()->to('/objectif')->with('objectif_error', 'Objectif introuvable.');
        }

        $userBodyModel = new UserBodyModel();
        $body = $userBodyModel
            ->where('id_user', $userId)
            ->orderBy('date', 'DESC')
            ->first();

        if (!$body) {
            return redirect()->to('/dashboard')->with('profil_error', 'Complete ton profil avant de choisir un objectif.');
        }

        $data = [
            'id_objectif' => $idObjectif,
            'date' => date('Y-m-d H:i:s'),
        ];

        $userBodyModel->update($body['id'], $data);

        return redirect()->to('/dashboard/regimes')->with('profil_success', 'Objectif enregistre.');
    }
}
