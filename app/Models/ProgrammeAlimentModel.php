<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgrammeAlimentModel extends Model
{
    protected $table = 'programmeAliment';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_programmeRegime', 'id_aliment',
        'quantite_g', 'type_repas'
    ];
}