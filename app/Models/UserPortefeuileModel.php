<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPortefeuileModel extends Model
{
    protected $table = 'userPortefeuile';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user', 'montant'
    ];
}