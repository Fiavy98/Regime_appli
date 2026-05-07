<?php

namespace App\Models;

use CodeIgniter\Model;

class GoldModel extends Model
{
    protected $table = 'gold';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'prix'
    ];
}