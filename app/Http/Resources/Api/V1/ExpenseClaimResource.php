<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseClaimResource extends JsonResource
{
    /**
     * Transform the employee into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'Empname' => $this->whenLoaded(
                'employee',
                fn() => $this->employee
                    ? [
                        'id' => $this->employee->id,
                        'name' => $this->employee->name,
                    ]
                    : null
            ),
            'category' => $this->category,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'description' => $this->description,
            'status' => $this->status,
            'reviewed_by' => $this->reviewed_by,

            'reviewed_at' => $this->reviewed_at,

            'rejection_reason' => $this->rejection_reason,
        ];
    }
}
