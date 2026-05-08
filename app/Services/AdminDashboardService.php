<?php

namespace App\Services;

use App\Models\AchatRegimeDetailModel;
use App\Models\AchatRegimeModel;
use App\Models\CodeModel;
use App\Models\UserBodyModel;
use App\Models\UserGoldModel;
use App\Models\UserModel;

class AdminDashboardService
{
    public function getDashboardData(string $period = 'month'): array
    {
        if (!in_array($period, ['month', 'quarter', 'year'], true)) {
            $period = 'month';
        }

        $ranges = $this->buildPeriodRanges($period);
        $start = $ranges['current_start'];
        $end = $ranges['current_end'];
        $prevStart = $ranges['previous_start'];
        $prevEnd = $ranges['previous_end'];

        $userModel = new UserModel();
        $userBodyModel = new UserBodyModel();
        $achatModel = new AchatRegimeModel();
        $achatDetailModel = new AchatRegimeDetailModel();
        $userGoldModel = new UserGoldModel();
        $codeModel = new CodeModel();

        $usersTotal = $userModel->countAllResults();
        $usersCurrent = $userBodyModel
            ->where('date >=', $start)
            ->where('date <=', $end)
            ->countAllResults();
        $usersPrev = $userBodyModel
            ->where('date >=', $prevStart)
            ->where('date <=', $prevEnd)
            ->countAllResults();

        $revenusCurrent = $this->sumBetween('achatRegime', 'prix_total', 'date_achat', $start, $end);
        $revenusPrev = $this->sumBetween('achatRegime', 'prix_total', 'date_achat', $prevStart, $prevEnd);

        $goldCurrent = $userGoldModel
            ->where('date_achat >=', $start)
            ->where('date_achat <=', $end)
            ->countAllResults();
        $goldPrev = $userGoldModel
            ->where('date_achat >=', $prevStart)
            ->where('date_achat <=', $prevEnd)
            ->countAllResults();

        $regimesCurrent = $achatDetailModel
            ->join('achatRegime', 'achatRegime.id = achatRegimeDetail.id_achatRegime', 'left')
            ->where('achatRegime.date_achat >=', $start)
            ->where('achatRegime.date_achat <=', $end)
            ->countAllResults();
        $regimesPrev = $achatDetailModel
            ->join('achatRegime', 'achatRegime.id = achatRegimeDetail.id_achatRegime', 'left')
            ->where('achatRegime.date_achat >=', $prevStart)
            ->where('achatRegime.date_achat <=', $prevEnd)
            ->countAllResults();

        $growthUsers = $this->growthPercent($usersCurrent, $usersPrev);
        $growthRevenus = $this->growthPercent($revenusCurrent, $revenusPrev);
        $growthGold = $this->growthPercent($goldCurrent, $goldPrev);
        $growthRegimes = $this->growthPercent($regimesCurrent, $regimesPrev);

        $months = $this->buildMonthlyRanges(8);
        $inscriptions = [];
        $ventes = [];
        $monthLabels = [];
        foreach ($months as $month) {
            $monthLabels[] = $month['label'];
            $inscriptions[] = $userBodyModel
                ->where('date >=', $month['start'])
                ->where('date <=', $month['end'])
                ->countAllResults();
            $ventes[] = $achatModel
                ->where('date_achat >=', $month['start'])
                ->where('date_achat <=', $month['end'])
                ->countAllResults();
        }

        $db = \Config\Database::connect();
        $ventesObjectif = $db->table('objectif')
            ->select('objectif.id, objectif.name as objectif, COUNT(DISTINCT achatRegime.id) as total')
            ->join('programmeRegime', 'programmeRegime.id_objectif = objectif.id', 'left')
            ->join('achatRegimeDetail', 'achatRegimeDetail.id_programmeRegime = programmeRegime.id', 'left')
            ->join(
                'achatRegime',
                'achatRegime.id = achatRegimeDetail.id_achatRegime AND achatRegime.date_achat >= ' . $db->escape($start) . ' AND achatRegime.date_achat <= ' . $db->escape($end),
                'left',
                false
            )
            ->groupBy('objectif.id')
            ->orderBy('objectif.id', 'ASC')
            ->get()->getResultArray();

        $totalObjectiveSales = array_sum(array_map(static function ($row) {
            return (int) ($row['total'] ?? 0);
        }, $ventesObjectif));

        $achatRows = $db->table('achatRegime')
            ->select('achatRegime.id, user.name as user_name, achatRegime.prix_total, achatRegime.est_gold_utilise, achatRegime.date_achat, MIN(programmeRegime.nom) as regime_nom, MIN(programmeRegime.id) as programme_id')
            ->join('user', 'user.id = achatRegime.id_user', 'left')
            ->join('achatRegimeDetail', 'achatRegimeDetail.id_achatRegime = achatRegime.id', 'left')
            ->join('programmeRegime', 'programmeRegime.id = achatRegimeDetail.id_programmeRegime', 'left')
            ->groupBy('achatRegime.id')
            ->orderBy('achatRegime.date_achat', 'DESC')
            ->limit(3)
            ->get()->getResultArray();

        $codes = $codeModel
            ->orderBy('date_expiration', 'DESC')
            ->findAll(3);

        return [
            'period' => $period,
            'usersTotal' => $usersTotal,
            'usersCurrent' => $usersCurrent,
            'revenusCurrent' => $revenusCurrent,
            'goldCurrent' => $goldCurrent,
            'regimesCurrent' => $regimesCurrent,
            'growthUsers' => $growthUsers,
            'growthRevenus' => $growthRevenus,
            'growthGold' => $growthGold,
            'growthRegimes' => $growthRegimes,
            'monthLabels' => json_encode($monthLabels),
            'inscriptions' => json_encode($inscriptions),
            'ventes' => json_encode($ventes),
            'ventesObjectif' => $ventesObjectif,
            'totalObjectiveSales' => $totalObjectiveSales,
            'ventesObjectifLabels' => json_encode(array_map(static function ($row) {
                return $row['objectif'] ?? 'Autre';
            }, $ventesObjectif)),
            'ventesObjectifValues' => json_encode(array_map(static function ($row) {
                return (int) ($row['total'] ?? 0);
            }, $ventesObjectif)),
            'achats' => $achatRows,
            'codes' => $this->formatCodeRows($codes),
        ];
    }

    public function getCodes(): array
    {
        $codes = (new CodeModel())
            ->orderBy('date_expiration', 'DESC')
            ->findAll();

        return $this->formatCodeRows($codes);
    }

    public function groupMeals(array $rows): array
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

    private function buildPeriodRanges(string $period): array
    {
        $now = new \DateTimeImmutable('now');

        if ($period === 'year') {
            $currentStart = $now->setDate((int) $now->format('Y'), 1, 1)->setTime(0, 0, 0);
            $currentEnd = $now;
            $previousStart = $currentStart->modify('-1 year');
            $previousEnd = $currentEnd->modify('-1 year');
        } elseif ($period === 'quarter') {
            $currentStart = $now->modify('first day of -2 months')->setTime(0, 0, 0);
            $currentEnd = $now;
            $previousStart = $currentStart->modify('-3 months');
            $previousEnd = $currentStart->modify('-1 day')->setTime(23, 59, 59);
        } else {
            $currentStart = $now->modify('first day of this month')->setTime(0, 0, 0);
            $currentEnd = $now;
            $previousStart = $currentStart->modify('-1 month');
            $previousEnd = $currentStart->modify('-1 day')->setTime(23, 59, 59);
        }

        return [
            'current_start' => $currentStart->format('Y-m-d H:i:s'),
            'current_end' => $currentEnd->format('Y-m-d H:i:s'),
            'previous_start' => $previousStart->format('Y-m-d H:i:s'),
            'previous_end' => $previousEnd->format('Y-m-d H:i:s'),
        ];
    }

    private function buildMonthlyRanges(int $months): array
    {
        $now = new \DateTimeImmutable('first day of this month');
        $ranges = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = $now->modify("-{$i} months");
            $monthEnd = $monthStart->modify('last day of this month')->setTime(23, 59, 59);
            $ranges[] = [
                'label' => $monthStart->format('M'),
                'start' => $monthStart->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                'end' => $monthEnd->format('Y-m-d H:i:s'),
            ];
        }

        return $ranges;
    }

    private function growthPercent(float $current, float $previous): int
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }

    private function sumBetween(string $table, string $column, string $dateColumn, string $start, string $end): float
    {
        $row = \Config\Database::connect()
            ->table($table)
            ->selectSum($column, 'total')
            ->where($dateColumn . ' >=', $start)
            ->where($dateColumn . ' <=', $end)
            ->get()
            ->getRowArray();

        return (float) ($row['total'] ?? 0);
    }

    private function formatCodeRows(array $codes): array
    {
        $nowDate = date('Y-m-d');

        return array_map(static function ($code) use ($nowDate) {
            $status = 'Disponible';
            if ((int) ($code['utilise'] ?? 0) === 1) {
                $status = 'Utilise';
            } elseif (!empty($code['date_expiration']) && $code['date_expiration'] < $nowDate) {
                $status = 'Expire';
            }

            $code['status_label'] = $status;
            return $code;
        }, $codes);
    }
}
