<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPortefeuilleModel extends Model
{
    protected $table = 'userPortefeuile';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'montant'];
    protected $useTimestamps = false;
    
    public function getSolde(int $userId): float
    {
        $result = $this->where('id_user', $userId)->first();
        return $result ? (float) $result['montant'] : 0.0;
    }
    
    public function crediter(int $userId, float $montant): bool
    {
        $portefeuille = $this->where('id_user', $userId)->first();
        
        if (!$portefeuille) {
            return $this->insert([
                'id_user' => $userId,
                'montant' => $montant
            ]);
        }
        
        return $this->update($portefeuille['id'], [
            'montant' => $portefeuille['montant'] + $montant
        ]);
    }
    
    public function debiter(int $userId, float $montant): bool
    {
        $portefeuille = $this->where('id_user', $userId)->first();
        
        if (!$portefeuille || $portefeuille['montant'] < $montant) {
            return false;
        }
        
        return $this->update($portefeuille['id'], [
            'montant' => $portefeuille['montant'] - $montant
        ]);
    }
}