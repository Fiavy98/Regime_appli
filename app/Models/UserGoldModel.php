<?php

namespace App\Models;

use CodeIgniter\Model;

class UserGoldModel extends Model
{
    protected $table = 'userGold';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'prix_payé', 'date_achat'];
    protected $useTimestamps = false;
    
    public function hasGold(int $userId): bool
    {
        return $this->where('id_user', $userId)->countAllResults() > 0;
    }
    
    public function getGoldInfo(int $userId): ?array
    {
        return $this->where('id_user', $userId)->first();
    }
    
    public function subscribe(int $userId, float $prix): bool
    {
        // Vérifier si déjà gold
        if ($this->hasGold($userId)) {
            return false;
        }
        
        return $this->insert([
            'id_user' => $userId,
            'prix_payé' => $prix,
            'date_achat' => date('Y-m-d H:i:s')
        ]);
    }
}