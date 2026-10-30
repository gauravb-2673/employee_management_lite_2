<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryHistories extends Model
{
    protected $table = 'employee_salary_histories';

    protected $fillable = [
        'employee_id',
        'old_salary',
        'new_salary',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'old_salary' => 'decimal:2',
            'new_salary' => 'decimal:2',
        ];
    }



    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
