<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;



class UpdateEmployeeRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('employees', 'email')->ignore($this->employee)],
            'department_id' => 'required|integer|exists:departments,id',
            'salary' => 'required|decimal:2|min:0',
            'joining_date' => 'required|date',
            'is_active' => 'required|boolean',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'integer|exists:projects,id',
            'project_roles' => 'nullable|array',
            'project_roles.*' => 'integer|exists:role_master,id',
        ];
    }
    public function messages(): array
    {
        return [
            // Name validation messages
            'name.required' => 'Please enter the employee name.',
            'name.string' => 'The employee name must be text.',
            'name.max' => 'The employee name cannot exceed 100 characters.',

            // Email validation messages
            'email.required' => 'Please enter the employee email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already assigned to an employee.',

            // Department validation messages
            'department_id.required' => 'Please select a department.',
            'department_id.integer' => 'The selected department is invalid.',
            'department_id.exists' => 'The selected department does not exist.',

            // Salary validation messages
            'salary.required' => 'Please enter the employee salary.',
            'salary.decimal' => 'Custom validation message: The salary must have exactly two decimal places.',
            'salary.min' => 'The salary cannot be negative.',

            // Joining date validation messages
            'joining_date.required' => 'Please select the employee joining date.',
            'joining_date.date' => 'Please enter a valid joining date.',
            'joining_date.before_or_equal' => 'The joining date cannot be in the future.',

            // Employment status validation messages
            'is_active.required' => 'Please specify whether the employee is active.',
            'is_active.boolean' => 'The employee status must be active or inactive.',

            // Project validation messages
            'project_ids.array' => 'The selected projects must be provided as a list.',
            'project_ids.*.integer' => 'Each selected project must have a valid ID.',
            'project_ids.*.exists' => 'One of the selected projects does not exist.',

            // Project role validation messages
            'project_roles.array' => 'Project roles must be provided as a list.',
            'project_roles.*.integer' => 'Each project role must have a valid ID.',
            'project_roles.*.exists' => 'One of the selected project roles does not exist.',
        ];
    }
}
