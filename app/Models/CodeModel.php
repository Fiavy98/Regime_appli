<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeModel extends Model
{
    protected $table = 'Code';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'code', 'montant', 'date_expiration', 'utilise'
    ];
}