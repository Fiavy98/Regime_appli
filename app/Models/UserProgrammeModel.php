<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProgrammeModel extends Model
{
    protected $table = 'userProgramme';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user', 'id_programmeRegime',
        'date_debut', 'date_fin',
        'prix_paye', 'id_statusRegime'
    ];
}