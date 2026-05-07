<?php

namespace App\Models;

use CodeIgniter\Model;

class UserGoldModel extends Model
{
    protected $table = 'userGold';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user', 'id_gold', 'date_achat'
    ];
}