<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusRegimeModel extends Model
{
    protected $table = 'statusRegime';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'libel'
    ];
}