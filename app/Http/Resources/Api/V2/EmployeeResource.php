<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the employee into the V2 JSON response.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'email' => $this->email,

            'salary' => $this->salary,

            'joining_date' => $this->joining_date?->format('Y-m-d'),

            'is_active' => $this->is_active,

            // V2 returns nested department information.
            'department' => $this->whenLoaded(
                'department',
                fn() => $this->department
                    ? [
                        'id' => $this->department->id,
                        'name' => $this->department->department_name,
                    ]
                    : null
            ),

            // V2 includes projects assigned to the employee.
            'projects' => $this->whenLoaded(
                'projects',
                fn() => $this->projects
                    ->map(fn($project) => [
                        'id' => $project->id,
                        'name' => $project->project_name,

                        // This is the role ID stored on the pivot.
                        'role_id' => $project->pivot->role,
                    ])
                    ->values()
            ),
        ];
    }
}
