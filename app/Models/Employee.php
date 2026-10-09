<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'employees';

    protected $fillable = [
        'department_id',
        'name',
        'email',
        'salary',
        'joining_date',
        'is_active',
        'user_id',
    ];


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->using(EmployeeProject::class)
            ->withPivot('role')
            ->withTimestamps();
    }


    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'salary' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }



    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
