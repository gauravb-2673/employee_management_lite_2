<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMaster extends Model
{
    protected $table = 'role_master';

    protected $fillable = [
        'role_name',
    ];
}
