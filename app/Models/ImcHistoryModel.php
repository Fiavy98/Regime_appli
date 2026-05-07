<?php

namespace App\Models;

use CodeIgniter\Model;

class ImcHistoryModel extends Model
{
    protected $table = 'imcHistory';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user', 'poids', 'imc', 'date'
    ];
}