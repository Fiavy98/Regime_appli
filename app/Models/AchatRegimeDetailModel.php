<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatRegimeDetailModel extends Model
{
    protected $table = 'achatRegimeDetail';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_achatRegime', 'id_programmeRegime',
        'prix_unitaire'
    ];
}