<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseClaims extends Model
{

    use HasFactory;
    // An ExpenseClaim model with casts (amount, expense_date, reviewed_at) and relationships: a claim belongs to an employee and to the user who reviewed it; an employee has many claims.

    protected $table = 'expense_claims';

    protected $fillable = [
        'employee_id',
        'category',
        'amount',
        'expense_date',
        'description',
        'receipt_path',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
            'reviewed_at' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
