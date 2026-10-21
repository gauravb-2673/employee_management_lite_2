<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the employee into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'salary' => $this->salary,
            'joining_date' => $this->joining_date,
            'department_id' => $this->department_id,
            'is_active' => $this->is_active,

            'department' => $this->whenLoaded(
                'department',
                fn() => $this->department
                    ? [
                        'id' => $this->department->id,
                        'name' => $this->department->department_name,
                    ]
                    : null
            ),

            'projects' => $this->whenLoaded(
                'projects',
                fn() => $this->projects->map(fn($project) => [
                    'id' => $project->id,
                    'name' => $project->project_name,
                    'role' => $project->pivot->role ?? null,
                ])->values()
            ),
        ];
    }
}
