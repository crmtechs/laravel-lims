<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class FormUpdateRequest extends FormRequest
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
            'status' => ['required', Rule::in(array_keys(config('dropdowns.document_status_list')))],
            'assigned_user_id' => 'nullable|exists:users,uuid',
            'revision' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'revision.required' => 'Please enter revision.',
            'document_name.required' => 'Please enter document name.',
            'publish_date.required' => 'Please enter publish date.',
            'status.required' => 'Please select status.',
            'assigned_user_id.required' => 'Please select assigned user.',
        ];
    }
}
