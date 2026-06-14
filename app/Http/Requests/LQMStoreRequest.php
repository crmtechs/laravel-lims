<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class LQMStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_name' => 'required|file|max:10240',
            'revision' => 'required|string|max:255',
            'document_name' => 'required|string|max:255',
            'document_title' => 'nullable|string|max:255',
            'publish_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'status' => ['required', Rule::in(array_keys(config('dropdowns.document_status_list')))],
            'assigned_user_id' => 'required|exists:users,uuid',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'document_name.required' => 'Please enter document name.',
            'document_name.max' => 'Document name should not be greater than 255 characters.',
            'file_name.required' => 'Please select file.',
            'file_name.max' => 'The file size must not exceed 10MB.',
            'revision.required' => 'Please enter revision.',
            'revision.max' => 'Revision should not be greater than 255 characters.',
            'publish_date.required' => 'Please enter publish date.',
            'status.required' => 'Please select status.',
            'assigned_user_id.required' => 'Please select assigned user.',
        ];
    }
}
