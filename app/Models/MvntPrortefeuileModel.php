<?php

namespace App\Models;

use CodeIgniter\Model;

class MvntPrortefeuileModel extends Model
{
    protected $table = 'MvntPrortefeuile';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_userPortefeuile', 'type', 'montant', 'date'
    ];
}