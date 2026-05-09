<?php

namespace App\Models;

use CodeIgniter\Model;

class MvntPrortefeuileModel extends Model
{
    protected $table = 'MvntPrortefeuile';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_userPortefeuile', 'type', 'montant', 'date'];
    protected $useTimestamps = false;
    
    public function getHistorique(int $userId, int $limit = 50): array
    {
        return $this->select('MvntPrortefeuile.*, userPortefeuile.id_user')
            ->join('userPortefeuile', 'userPortefeuile.id = MvntPrortefeuile.id_userPortefeuile')
            ->where('userPortefeuile.id_user', $userId)
            ->orderBy('MvntPrortefeuile.date', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    public function addTransaction(int $userId, string $type, float $montant): bool
    {
        $portefeuilleModel = new UserPortefeuileModel();
        $portefeuille = $portefeuilleModel->where('id_user', $userId)->first();
        
        if (!$portefeuille) {
            return false;
        }
        
        return $this->insert([
            'id_userPortefeuile' => $portefeuille['id'],
            'type' => $type,
            'montant' => $montant,
            'date' => date('Y-m-d H:i:s')
        ]);
    }
}