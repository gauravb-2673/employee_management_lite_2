<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'employee_id' => 'required|integer|exists:employee,id',
            'category' => 'required|string|max:100',
            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'decimal:2',
            ],
            'expense_date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:100',
            'receipt_path' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,png',
                'max:2048',
            ],
            'status' => 'required|string|max:100',
            'reviewed_at' => 'required|date',
            'rejection_reason' => 'required|string|max:100',

        ];
    }
}
