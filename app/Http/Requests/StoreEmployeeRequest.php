<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email',
            'department_id' => 'required|integer|exists:departments,id',
            'Salary' => 'required|decimal:2|min:0',
            'joining_date' => 'required|date|before_or_equal:today',
            'is_active' => 'required|boolean',

            'project_ids' => 'nullable|array',
            'project_ids.*' => 'integer|exists:projects,id',

            'project_roles' => 'nullable|array',
            'project_roles.*' => 'integer|exists:role_master,id',
        ];
    }
}
