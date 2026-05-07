<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiviteSportiveModel extends Model
{
    protected $table = 'activiteSportive';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_sport', 'id_objectif',
        'niveau', 'calories_brulees_par_heure',
        'duree_minute'
    ];
}