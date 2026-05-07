<?php

namespace App\Models;

use CodeIgniter\Model;

class AlimentModel extends Model
{
    protected $table = 'aliment';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom', 'id_categorie',
        'calories_pour_100g', 'proteines_g',
        'glucides_g', 'lipides_g'
    ];
}