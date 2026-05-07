<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSportModel extends Model
{
    protected $table = 'userSport';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user', 'id_activiteSportive',
        'start_date', 'end_date',
        'id_statusRegime'
    ];
}