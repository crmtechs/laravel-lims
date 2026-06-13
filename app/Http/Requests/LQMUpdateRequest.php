<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LQMUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_name' => 'required|string|max:255',
            'document_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'status_id' => 'required|string',
            'assigned_user_id' => 'nullable|exists:users,uuid',
            'file_name' => 'nullable|file|max:10240',
            'revision' => 'required_with:file_name|nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'file_name.required' => 'Please select file.',
            'revision.required' => 'Please enter revision.',
            'document_name.required' => 'Please enter document name.',
            'publish_date.required' => 'Please enter publish date.',
            'status_id.required' => 'Please select status.',
            'assigned_user_id.required' => 'Please select assigned user.',
        ];
    }
}
