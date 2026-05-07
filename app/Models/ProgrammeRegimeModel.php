<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgrammeRegimeModel extends Model
{
    protected $table = 'programmeRegime';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom', 'id_objectif', 'variation_poids',
        'imc_min', 'imc_max', 'duree_jours', 'prix'
    ];
}