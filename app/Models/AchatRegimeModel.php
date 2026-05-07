<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatRegimeModel extends Model
{
    protected $table = 'achatRegime';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user', 'prix_total',
        'reduction_appliquee', 'est_gold_utilise',
        'date_achat'
    ];
}