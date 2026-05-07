<?php

namespace App\Models;

use CodeIgniter\Model;

class SportsModel extends Model
{
    protected $table = 'sports';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name', 'category'
    ];
}