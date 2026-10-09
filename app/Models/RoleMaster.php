<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleMaster extends Model
{
    use HasFactory;
    protected $table = 'role_master';

    protected $fillable = [
        'role_name',
    ];
}
