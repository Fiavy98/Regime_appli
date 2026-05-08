<?php

namespace App\Services;

use App\Models\ActiviteSportiveModel;
use App\Models\ImcHistoryModel;
use App\Models\ObjectifModel;
use App\Models\ProgrammeAlimentModel;
use App\Models\ProgrammeRegimeModel;
use App\Models\UserBodyModel;
use App\Models\UserGoldModel;
use App\Models\UserPortefeuileModel;
use App\Models\UserProgrammeModel;

class UserDashboardService
{
    public function getProfileData(int $userId): array
    {
        $userBodyModel = new UserBodyModel();
        $imcHistoryModel = new ImcHistoryModel();
        $walletModel = new UserPortefeuileModel();
        $goldModel = new UserGoldModel();
        $programmeModel = new UserProgrammeModel();
        $compositionModel = new ProgrammeAlimentModel();

        $body = $userBodyModel
            ->select('userBody.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = userBody.id_objectif', 'left')
            ->where('id_user', $userId)
            ->orderBy('date', 'DESC')
            ->first();

        $wallet = $walletModel->where('id_user', $userId)->first();
        $history = $imcHistoryModel
            ->where('id_user', $userId)
            ->orderBy('date', 'ASC')
            ->findAll();

        $imc = null;
        $imcLabel = '---';
        if ($body && (float) $body['taille'] > 0) {
            $imc = (float) $body['poids'] / ((float) $body['taille'] * (float) $body['taille']);
            $imcLabel = $this->labelFromImc($imc);
        }

        $currentProgramme = $programmeModel
            ->select('userProgramme.*, programmeRegime.nom as programme_nom, programmeRegime.duree_jours, programmeRegime.prix, programmeRegime.id_objectif, programmeRegime.variation_poids, objectif.name as objectif_label')
            ->join('programmeRegime', 'programmeRegime.id = userProgramme.id_programmeRegime', 'left')
            ->join('objectif', 'objectif.id = programmeRegime.id_objectif', 'left')
            ->where('userProgramme.id_user', $userId)
            ->orderBy('userProgramme.date_debut', 'DESC')
            ->first();

        $mealCounts = [
            'PETIT_DEJEUNER' => 0,
            'DEJEUNER' => 0,
            'DINER' => 0,
            'COLLATION' => 0,
        ];
        $progressPercent = 0;
        $progressDays = 0;
        $progressTotal = 0;

        if ($currentProgramme) {
            $startTs = strtotime((string) ($currentProgramme['date_debut'] ?? 'now')) ?: time();
            $elapsedDays = (int) floor((time() - $startTs) / 86400) + 1;
            $progressDays = max(1, $elapsedDays);
            $progressTotal = max(1, (int) ($currentProgramme['duree_jours'] ?? 1));
            $progressPercent = min(100, (int) round(($progressDays / $progressTotal) * 100));
            if (!empty($currentProgramme['id_statusRegime']) && (int) $currentProgramme['id_statusRegime'] !== 1) {
                $progressPercent = 100;
            }

            $mealRows = $compositionModel
                ->select('type_repas, COUNT(*) as total')
                ->where('id_programmeRegime', (int) $currentProgramme['id_programmeRegime'])
                ->groupBy('type_repas')
                ->findAll();

            foreach ($mealRows as $row) {
                $type = strtoupper((string) ($row['type_repas'] ?? 'COLLATION'));
                if (isset($mealCounts[$type])) {
                    $mealCounts[$type] = (int) ($row['total'] ?? 0);
                }
            }
        }

        $historyLabels = array_map(static function ($row) {
            return $row['date'];
        }, $history);
        $historyValues = array_map(static function ($row) {
            return (float) $row['imc'];
        }, $history);

        $historyCount = count($history);
        $weightDelta = null;
        if ($historyCount >= 2) {
            $last = (float) $history[$historyCount - 1]['poids'];
            $prev = (float) $history[$historyCount - 2]['poids'];
            $weightDelta = $last - $prev;
        }

        $hasGold = (bool) $goldModel->where('id_user', $userId)->first();

        return [
            'body' => $body,
            'imc' => $imc,
            'imcLabel' => $imcLabel,
            'objectifLabel' => $body['objectif_label'] ?? null,
            'walletAmount' => (float) ($wallet['montant'] ?? 0),
            'historyLabels' => json_encode($historyLabels),
            'historyValues' => json_encode($historyValues),
            'currentProgramme' => $currentProgramme,
            'progressPercent' => $progressPercent,
            'progressDays' => $progressDays,
            'progressTotal' => $progressTotal,
            'mealCounts' => $mealCounts,
            'activeRegimesCount' => $currentProgramme ? 1 : 0,
            'completedRegimesCount' => (new UserProgrammeModel())
                ->where('id_user', $userId)
                ->where('id_statusRegime', 2)
                ->countAllResults(),
            'badgeLabel' => $hasGold ? 'Gold' : 'Standard',
            'weightDelta' => $weightDelta,
            'userEmail' => session()->get('email') ?? 'Compte actif',
            'hasGold' => $hasGold,
        ];
    }

    public function getRegimeData(int $userId, ?int $selectedId = null): array
    {
        $programmeModel = new ProgrammeRegimeModel();
        $compositionModel = new ProgrammeAlimentModel();
        $walletModel = new UserPortefeuileModel();
        $goldModel = new UserGoldModel();
        $userBodyModel = new UserBodyModel();

        $body = $userBodyModel
            ->select('userBody.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = userBody.id_objectif', 'left')
            ->where('id_user', $userId)
            ->orderBy('date', 'DESC')
            ->first();
        $objectifId = (int) ($body['id_objectif'] ?? 0);

        $programmeQuery = $programmeModel
            ->select('programmeRegime.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = programmeRegime.id_objectif', 'left')
            ->orderBy('programmeRegime.nom', 'ASC');
        if ($objectifId > 0) {
            $programmeQuery->where('programmeRegime.id_objectif', $objectifId);
        }
        $programmes = $programmeQuery->findAll();

        if (($selectedId ?? 0) <= 0 && !empty($programmes)) {
            $selectedId = (int) $programmes[0]['id'];
        }
        if (($selectedId ?? 0) > 0 && $objectifId > 0) {
            $selectedProgramme = $programmeModel->find($selectedId);
            if ($selectedProgramme && (int) $selectedProgramme['id_objectif'] !== $objectifId) {
                $selectedId = !empty($programmes) ? (int) $programmes[0]['id'] : 0;
            }
        }

        $programme = null;
        foreach ($programmes as $item) {
            if ((int) $item['id'] === (int) $selectedId) {
                $programme = $item;
                break;
            }
        }

        $compositions = [];
        if (($selectedId ?? 0) > 0) {
            $compositions = $compositionModel
                ->select('programmeAliment.*, aliment.nom, aliment.calories_pour_100g, aliment.proteines_g, aliment.glucides_g, aliment.lipides_g')
                ->join('aliment', 'aliment.id = programmeAliment.id_aliment', 'left')
                ->where('programmeAliment.id_programmeRegime', $selectedId)
                ->orderBy('programmeAliment.type_repas', 'ASC')
                ->findAll();
        }

        $meals = $this->groupMeals($compositions);
        $wallet = $walletModel->where('id_user', $userId)->first();
        $hasGold = (bool) $goldModel->where('id_user', $userId)->first();

        $discountRate = $hasGold ? 0.15 : 0.0;
        $price = $programme ? (float) $programme['prix'] : 0.0;
        $discount = $price * $discountRate;
        $finalPrice = $price - $discount;

        return [
            'programmes' => $programmes,
            'programme' => $programme,
            'meals' => $meals,
            'wallet' => $wallet,
            'hasGold' => $hasGold,
            'discount' => $discount,
            'finalPrice' => $finalPrice,
            'objectifLabel' => $body['objectif_label'] ?? null,
            'userEmail' => session()->get('email') ?? 'Compte actif',
            'badgeLabel' => $hasGold ? 'Gold' : 'Standard',
        ];
    }

    public function getSportsData(int $userId, string $niveau = ''): array
    {
        $niveau = in_array($niveau, ['FAIBLE', 'MOYEN', 'ELEVE'], true) ? $niveau : '';

        $body = (new UserBodyModel())
            ->select('userBody.*, objectif.name as objectif_label')
            ->join('objectif', 'objectif.id = userBody.id_objectif', 'left')
            ->where('id_user', $userId)
            ->orderBy('date', 'DESC')
            ->first();
        $objectifId = (int) ($body['id_objectif'] ?? 0);

        $builder = (new ActiviteSportiveModel())
            ->select('activiteSportive.*, sports.name as sport_name, sports.category, objectif.name as objectif_label')
            ->join('sports', 'sports.id = activiteSportive.id_sport', 'left')
            ->join('objectif', 'objectif.id = activiteSportive.id_objectif', 'left');

        if ($objectifId > 0) {
            $builder->where('activiteSportive.id_objectif', $objectifId);
        }
        if ($niveau !== '') {
            $builder->where('activiteSportive.niveau', $niveau);
        }

        $activites = $builder->orderBy('sports.name', 'ASC')->findAll();

        return [
            'niveau' => $niveau,
            'activites' => $activites,
            'objectifLabel' => $body['objectif_label'] ?? null,
            'userEmail' => session()->get('email') ?? 'Compte actif',
            'badgeLabel' => (bool) (new UserGoldModel())->where('id_user', $userId)->first() ? 'Gold' : 'Standard',
        ];
    }

    private function labelFromImc(float $imc): string
    {
        if ($imc < 18.5) {
            return 'Maigre';
        }

        if ($imc < 25) {
            return 'Normal';
        }

        if ($imc < 30) {
            return 'Surpoids';
        }

        return 'Obesite';
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
}
