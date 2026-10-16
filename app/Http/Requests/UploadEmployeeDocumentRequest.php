<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadEmployeeDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authentication and employee authorization are handled
        // separately in the controller for this example.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'document' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Please select a document.',
            'document.mimes' => 'Only PDF, DOC, and DOCX documents are allowed.',
            'document.max' => 'The document must not exceed 10 MB.',
        ];
    }
}
