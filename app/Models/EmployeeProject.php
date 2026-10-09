<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeProject extends Pivot
{
    protected $table = 'employee_project';

    protected $fillable = [
        'employee_id',
        'project_id',
        'role',
    ];

    public function roleMaster(): BelongsTo
    {
        return $this->belongsTo(RoleMaster::class, 'role', 'id');
    }
}
