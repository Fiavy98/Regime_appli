<?php

namespace App\Controllers;

use App\Models\AchatRegimeDetailModel;
use App\Models\AchatRegimeModel;
use App\Models\ProgrammeRegimeModel;
use App\Models\UserGoldModel;
use App\Models\UserProgrammeModel;
use App\Models\UserSportModel;
use App\Models\ImcHistoryModel;
use App\Models\UserBodyModel;
use App\Models\UserModel;
use App\Models\UserPortefeuileModel;
use App\Models\MvntPrortefeuileModel;
use App\Services\UserDashboardService;

class UserController extends BaseController
{
    public function profil()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $data = (new UserDashboardService())->getProfileData($userId);

        return view('profil', [
            'body' => $data['body'],
            'imc' => $data['imc'],
            'imcLabel' => $data['imcLabel'],
            'objectifLabel' => $data['objectifLabel'],
            'walletAmount' => $data['walletAmount'],
            'historyLabels' => $data['historyLabels'],
            'historyValues' => $data['historyValues'],
            'currentProgramme' => $data['currentProgramme'],
            'progressPercent' => $data['progressPercent'],
            'progressDays' => $data['progressDays'],
            'progressTotal' => $data['progressTotal'],
            'mealCounts' => $data['mealCounts'],
            'activeRegimesCount' => $data['activeRegimesCount'],
            'completedRegimesCount' => $data['completedRegimesCount'],
            'badgeLabel' => $data['badgeLabel'],
            'weightDelta' => $data['weightDelta'],
            'userEmail' => $data['userEmail'],
        ]);
    }

    public function updatePoids()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $poids = (float) $this->request->getPost('poids');
        if ($poids <= 0) {
            return redirect()->to('/dashboard')->with('profil_error', 'Poids invalide.');
        }

        $userBodyModel = new UserBodyModel();
        $imcHistoryModel = new ImcHistoryModel();

        $body = $userBodyModel
            ->where('id_user', $userId)
            ->orderBy('date', 'DESC')
            ->first();

        if (!$body || (float) $body['taille'] <= 0) {
            return redirect()->to('/dashboard')->with('profil_error', 'Taille introuvable.');
        }

        $now = date('Y-m-d H:i:s');
        $taille = (float) $body['taille'];
        $imc = $poids / ($taille * $taille);

        $userBodyModel->update($body['id'], [
            'poids' => $poids,
            'date' => $now,
        ]);

        $imcHistoryModel->insert([
            'id_user' => $userId,
            'poids' => $poids,
            'imc' => $imc,
            'date' => $now,
        ]);

        return redirect()->to('/dashboard')->with('profil_success', 'Poids mis a jour.');
    }

    public function regimes()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $data = (new UserDashboardService())->getRegimeData($userId, (int) ($this->request->getGet('id') ?? 0));

        return view('user/regime_detail', [
            'programmes' => $data['programmes'],
            'programme' => $data['programme'],
            'meals' => $data['meals'],
            'wallet' => $data['wallet'],
            'hasGold' => $data['hasGold'],
            'isPurchased' => $data['isPurchased'],
            'discount' => $data['discount'],
            'finalPrice' => $data['finalPrice'],
            'objectifLabel' => $data['objectifLabel'],
            'userEmail' => $data['userEmail'],
            'badgeLabel' => $data['badgeLabel'],
        ]);
    }

    public function exportRegimesPdf()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $data = (new UserDashboardService())->getRegimeExportData($userId);
        $fpdfPath = dirname(__DIR__, 2) . '/Autre/fpdf186/fpdf.php';

        if (! file_exists($fpdfPath)) {
            throw new \RuntimeException('Bibliotheque FPDF introuvable: ' . $fpdfPath);
        }

        require_once $fpdfPath;

        $pdf = new class extends \FPDF {
            public function Footer(): void
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->SetTextColor(102, 102, 102);
                $this->Cell(0, 10, utf8_decode('NutriPlan • Page ' . $this->PageNo() . ' / {nb}'), 0, 0, 'C');
            }
        };

        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 22);
        $pdf->SetMargins(16, 16, 16);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(15, 118, 110);
        $pdf->Cell(0, 12, utf8_decode('NutriPlan'), 0, 1, 'C', true);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode('Rapport des régimes personnalisés'), 0, 1, 'C', true);

        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(15, 118, 110);
        $pdf->SetFillColor(237, 249, 237);
        $pdf->Cell(0, 9, utf8_decode('Informations du client'), 0, 1, 'L', true);

        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->SetDrawColor(209, 250, 229);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(50, 8, utf8_decode('Nom'), 1, 0, 'L', true);
        $pdf->Cell(0, 8, utf8_decode(trim($data['user']['name'] ?? $data['user']['email'] ?? 'Utilisateur')), 1, 1, 'L', true);
        $pdf->Cell(50, 8, utf8_decode('Email'), 1, 0, 'L', true);
        $pdf->Cell(0, 8, utf8_decode($data['user']['email'] ?? 'N/A'), 1, 1, 'L', true);
        $pdf->Cell(50, 8, utf8_decode('Objectif'), 1, 0, 'L', true);
        $pdf->Cell(0, 8, utf8_decode($data['body']['objectif_label'] ?? 'Non défini'), 1, 1, 'L', true);
        $pdf->Cell(50, 8, utf8_decode('Poids / Taille'), 1, 0, 'L', true);
        $pdf->Cell(0, 8, utf8_decode(($data['body']['poids'] ?? '--') . ' kg / ' . ($data['body']['taille'] ?? '--') . ' m'), 1, 1, 'L', true);

        $pdf->Ln(6);

        if (empty($data['programmes'])) {
            $pdf->SetFont('Arial', 'I', 11);
            $pdf->SetTextColor(102, 102, 102);
            $pdf->Cell(0, 8, utf8_decode('Aucun programme acheté pour le moment.'), 0, 1, 'C');
        } else {
            foreach ($data['programmes'] as $programme) {
                if ($pdf->GetY() > 230) {
                    $pdf->AddPage();
                }

                $pdf->SetFillColor(235, 245, 235);
                $pdf->SetDrawColor(167, 243, 208);
                $pdf->SetLineWidth(0.3);
                $pdf->SetFont('Arial', 'B', 13);
                $pdf->SetTextColor(13, 70, 52);
                $pdf->Cell(0, 10, utf8_decode($programme['regime_nom'] ?? 'Programme'), 1, 1, 'L', true);

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor(30, 41, 59);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetDrawColor(209, 250, 229);

                $pdf->Cell(45, 8, utf8_decode('Durée'), 1, 0, 'L', true);
                $pdf->Cell(45, 8, utf8_decode('Prix payé'), 1, 0, 'L', true);
                $pdf->Cell(50, 8, utf8_decode('IMC cible'), 1, 0, 'L', true);
                $pdf->Cell(0, 8, utf8_decode('Statut'), 1, 1, 'L', true);

                $pdf->Cell(45, 8, utf8_decode((string) ((int) ($programme['duree_jours'] ?? 0)) . ' jours'), 1, 0, 'L');
                $pdf->Cell(45, 8, utf8_decode(number_format((float) ($programme['prix_paye'] ?? 0), 0, ',', ' ') . ' Ar'), 1, 0, 'L');
                $pdf->Cell(50, 8, utf8_decode((string) ($programme['imc_min'] ?? '--') . ' - ' . ($programme['imc_max'] ?? '--')), 1, 0, 'L');
                $pdf->Cell(0, 8, utf8_decode(((int) ($programme['id_statusRegime'] ?? 0)) === 2 ? 'Terminé' : 'Actif'), 1, 1, 'L');

                $pdf->Ln(2);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetTextColor(15, 118, 110);
                $pdf->Cell(0, 8, utf8_decode('Objectifs et suivi'), 0, 1, 'L');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor(30, 41, 59);
                $pdf->Cell(45, 8, utf8_decode('Variation visée'), 1, 0, 'L', true);
                $pdf->Cell(85, 8, utf8_decode('Période'), 1, 0, 'L', true);
                $pdf->Cell(0, 8, utf8_decode('État'), 1, 1, 'L', true);

                $pdf->Cell(45, 8, utf8_decode((string) ($programme['variation_poids'] ?? '--') . ' kg'), 1, 0, 'L');
                $pdf->Cell(85, 8, utf8_decode(($programme['date_debut'] ?? 'N/A') . ' au ' . ($programme['date_fin'] ?? 'N/A')), 1, 0, 'L');
                $pdf->Cell(0, 8, utf8_decode('En cours de suivi'), 1, 1, 'L');

                $pdf->Ln(4);

                if (!empty($programme['compositions'])) {
                    foreach ($programme['compositions'] as $mealType => $items) {
                        if ($pdf->GetY() > 240) {
                            $pdf->AddPage();
                        }

                        $pdf->SetFont('Arial', 'B', 11);
                        $pdf->SetTextColor(15, 118, 110);
                        $pdf->SetFillColor(236, 253, 245);
                        $pdf->Cell(0, 8, utf8_decode(ucwords(str_replace('_', ' ', strtolower($mealType))) . ' (' . count($items) . ' aliments)'), 0, 1, 'L', true);

                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->SetTextColor(15, 118, 110);
                        $pdf->SetFillColor(235, 245, 235);
                        $pdf->Cell(60, 8, utf8_decode('Aliment'), 1, 0, 'L', true);
                        $pdf->Cell(28, 8, utf8_decode('Quantité'), 1, 0, 'C', true);
                        $pdf->Cell(22, 8, utf8_decode('Calories'), 1, 0, 'C', true);
                        $pdf->Cell(20, 8, utf8_decode('Prot.'), 1, 0, 'C', true);
                        $pdf->Cell(20, 8, utf8_decode('Gluc.'), 1, 0, 'C', true);
                        $pdf->Cell(20, 8, utf8_decode('Lip.'), 1, 1, 'C', true);

                        $pdf->SetFont('Arial', '', 10);
                        $rowFill = false;
                        foreach ($items as $item) {
                            if ($pdf->GetY() > 265) {
                                $pdf->AddPage();
                                $pdf->SetFont('Arial', 'B', 10);
                                $pdf->SetTextColor(15, 118, 110);
                                $pdf->SetFillColor(235, 245, 235);
                                $pdf->Cell(60, 8, utf8_decode('Aliment'), 1, 0, 'L', true);
                                $pdf->Cell(28, 8, utf8_decode('Quantité'), 1, 0, 'C', true);
                                $pdf->Cell(22, 8, utf8_decode('Calories'), 1, 0, 'C', true);
                                $pdf->Cell(20, 8, utf8_decode('Prot.'), 1, 0, 'C', true);
                                $pdf->Cell(20, 8, utf8_decode('Gluc.'), 1, 0, 'C', true);
                                $pdf->Cell(20, 8, utf8_decode('Lip.'), 1, 1, 'C', true);
                                $pdf->SetFont('Arial', '', 10);
                            }

                            $pdf->SetFillColor($rowFill ? 245 : 255, $rowFill ? 252 : 255, $rowFill ? 244 : 255);
                            $pdf->Cell(60, 7, utf8_decode($item['aliment_nom'] ?? 'N/A'), 'LR', 0, 'L', true);
                            $pdf->Cell(28, 7, utf8_decode((string) ($item['quantite_g'] ?? 0)), 'LR', 0, 'C', true);
                            $pdf->Cell(22, 7, utf8_decode((string) ($item['calories'] ?? 0)), 'LR', 0, 'C', true);
                            $pdf->Cell(20, 7, utf8_decode((string) ($item['proteines'] ?? 0)), 'LR', 0, 'C', true);
                            $pdf->Cell(20, 7, utf8_decode((string) ($item['glucides'] ?? 0)), 'LR', 0, 'C', true);
                            $pdf->Cell(20, 7, utf8_decode((string) ($item['lipides'] ?? 0)), 'LR', 1, 'C', true);
                            $rowFill = ! $rowFill;
                        }

                        $pdf->Cell(0, 0, '', 'T', 1);
                        $pdf->Ln(4);
                    }
                } else {
                    $pdf->SetFont('Arial', 'I', 10);
                    $pdf->SetTextColor(102, 102, 102);
                    $pdf->Cell(0, 7, utf8_decode('Aucune composition disponible pour ce programme.'), 0, 1, 'L');
                    $pdf->Ln(4);
                }

                $pdf->Ln(2);
            }
        }

        $pdf->SetY(-22);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->SetTextColor(102, 102, 102);
        $pdf->Cell(0, 10, utf8_decode('Document généré le ' . date('d/m/Y H:i') . ' - NutriPlan'), 0, 0, 'C');

        $fileName = 'NutriPlan_Regimes_' . date('Ymd_His') . '.pdf';
        $pdfOutput = $pdf->Output('S');

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setBody($pdfOutput);
    }

    public function purchaseRegime()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $programmeId = (int) $this->request->getPost('id_programmeRegime');
        if ($programmeId <= 0) {
            return redirect()->to('/dashboard/regimes')->with('profil_error', 'Programme invalide.');
        }

        $programme = (new ProgrammeRegimeModel())->find($programmeId);
        if (!$programme) {
            return redirect()->to('/dashboard/regimes')->with('profil_error', 'Programme introuvable.');
        }

        $walletModel = new UserPortefeuileModel();
        $wallet = $walletModel->where('id_user', $userId)->first();
        $balance = $wallet ? (float) $wallet['montant'] : 0.0;

        $hasGold = (bool) (new UserGoldModel())->where('id_user', $userId)->first();
        $price = (float) $programme['prix'];
        $discount = $hasGold ? $price * 0.15 : 0.0;
        $finalPrice = $price - $discount;

        if ($balance < $finalPrice) {
            return redirect()->to('/dashboard/regimes?id=' . $programmeId)->with('profil_error', 'Solde insuffisant.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $achatId = (new AchatRegimeModel())->insert([
            'id_user' => $userId,
            'prix_total' => $finalPrice,
            'reduction_appliquee' => $discount,
            'est_gold_utilise' => $hasGold ? 1 : 0,
            'date_achat' => date('Y-m-d H:i:s'),
        ], true);

        (new AchatRegimeDetailModel())->insert([
            'id_achatRegime' => $achatId,
            'id_programmeRegime' => $programmeId,
            'prix_unitaire' => $finalPrice,
        ]);

        $dateDebut = date('Y-m-d');
        $dateFin = date('Y-m-d', strtotime('+' . (int) $programme['duree_jours'] . ' days'));

        (new UserProgrammeModel())->insert([
            'id_user' => $userId,
            'id_programmeRegime' => $programmeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'prix_paye' => $finalPrice,
            'id_statusRegime' => 1,
        ]);

        if ($wallet) {
            $walletModel->update($wallet['id'], [
                'montant' => $balance - $finalPrice,
            ]);
        }

        $mvntModel = new MvntPrortefeuileModel();
        $mvntModel->addTransaction($userId, 'debit', $finalPrice);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/dashboard/regimes?id=' . $programmeId)->with('profil_error', 'Achat impossible.');
        }

        return redirect()->to('/dashboard/regimes?id=' . $programmeId)->with('profil_success', 'Achat effectue.');
    }

    public function sports()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $data = (new UserDashboardService())->getSportsData($userId, (string) ($this->request->getGet('niveau') ?? ''));

        return view('user/sports', [
            'niveau' => $data['niveau'],
            'activites' => $data['activites'],
            'objectifLabel' => $data['objectifLabel'],
            'userEmail' => $data['userEmail'],
            'badgeLabel' => $data['badgeLabel'],
        ]);
    }

    public function startSport()
    {
        $userId = (int) (session()->get('id_user') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $activiteId = (int) $this->request->getPost('id_activite');
        if ($activiteId <= 0) {
            return redirect()->to('/dashboard/sports')->with('profil_error', 'Activite invalide.');
        }

        (new UserSportModel())->insert([
            'id_user' => $userId,
            'id_activiteSportive' => $activiteId,
            'start_date' => date('Y-m-d'),
            'end_date' => null,
            'id_statusRegime' => 1,
        ]);

        return redirect()->to('/dashboard/sports')->with('profil_success', 'Activite demarree.');
    }

    public function registerStep1()
    {
        return view('register_step1');
    }

    public function registerStep2()
    {
        if (!session()->has('register')) {
            return redirect()->to('/register/step1');
        }

        return view('register_step2');
    }

    public function checkEmail()
    {
        $email = trim((string) $this->request->getPost('email'));
        $userModel = new UserModel();

        $exists = false;
        if ($email !== '') {
            $exists = (bool) $userModel->where('email', $email)->first();
        }

        return $this->response->setJSON([
            'disponible' => !$exists,
        ]);
    }

    public function storeStep1()
    {
        $payload = [
            'name' => trim((string) $this->request->getPost('name')),
            'email' => trim((string) $this->request->getPost('email')),
            'genre' => trim((string) $this->request->getPost('genre')),
            'age' => (int) $this->request->getPost('age'),
            'password' => (string) $this->request->getPost('password'),
            'password_confirm' => (string) $this->request->getPost('password_confirm'),
        ];

        $errors = [];
        if ($payload['name'] === '') {
            $errors['name'] = 'Nom obligatoire.';
        }
        if ($payload['email'] === '' || !filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalide.';
        }
        if (!in_array($payload['genre'], ['Homme', 'Femme'], true)) {
            $errors['genre'] = 'Genre invalide.';
        }
        if ($payload['age'] < 5 || $payload['age'] > 120) {
            $errors['age'] = 'Age invalide.';
        }
        if (strlen($payload['password']) < 8) {
            $errors['password'] = 'Mot de passe trop court.';
        }
        if ($payload['password'] !== $payload['password_confirm']) {
            $errors['password_confirm'] = 'Confirmation differente.';
        }

        $userModel = new UserModel();
        if ($payload['email'] !== '' && $userModel->where('email', $payload['email'])->first()) {
            $errors['email'] = 'Email deja utilise.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors,
            ]);
        }

        session()->set('register', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'genre' => $payload['genre'],
            'age' => $payload['age'],
            'password' => $payload['password'],
        ]);

        return $this->response->setJSON([
            'success' => true,
            'redirect' => base_url('/register/step2'),
        ]);
    }

    public function storeStep2()
    {
        $register = session()->get('register');
        if (!$register) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['general' => 'Etape 1 manquante.'],
            ]);
        }

        $taille = (float) $this->request->getPost('taille');
        $poids = (float) $this->request->getPost('poids');

        $errors = [];
        if ($taille <= 0) {
            $errors['taille'] = 'Taille invalide.';
        }
        if ($poids <= 0) {
            $errors['poids'] = 'Poids invalide.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors,
            ]);
        }

        $now = date('Y-m-d H:i:s');
        $userModel = new UserModel();
        $userBodyModel = new UserBodyModel();
        $imcHistoryModel = new ImcHistoryModel();
        $walletModel = new UserPortefeuileModel();

        $userId = $userModel->insert([
            'name' => $register['name'],
            'email' => $register['email'],
            'genre' => $register['genre'],
            'age' => $register['age'],
            'psswd' => password_hash($register['password'], PASSWORD_BCRYPT),
            'role' => 'user',
        ], true);

        $userBodyModel->insert([
            'id_user' => $userId,
            'taille' => $taille,
            'poids' => $poids,
            'id_objectif' => null,
            'date' => $now,
        ]);

        $imc = $poids / ($taille * $taille);
        $imcHistoryModel->insert([
            'id_user' => $userId,
            'poids' => $poids,
            'imc' => $imc,
            'date' => $now,
        ]);

        $walletModel->insert([
            'id_user' => $userId,
            'montant' => 0,
        ]);

        session()->remove('register');
        session()->set([
            'id_user' => $userId,
            'role' => 'user',
            'nom' => $register['name'],
            'email' => $register['email'],
        ]);

        return $this->response->setJSON([
            'success' => true,
            'redirect' => base_url('/dashboard'),
        ]);
    }

}
