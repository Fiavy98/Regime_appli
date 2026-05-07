<?php

namespace App\Models;

use CodeIgniter\Model;

class UserBodyModel extends Model
{
    protected $table = 'userBody';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user', 'taille', 'poids', 'id_objectif', 'date'
    ];
}