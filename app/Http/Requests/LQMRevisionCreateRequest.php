<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LQMRevisionCreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file_name' => 'required|file|max:10240',
            'revision' => 'required|string|max:255',
            'change_log' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file_name.required' => 'Please select file.',
            'file_name.file' => 'The uploaded file is invalid.',
            'file_name.max' => 'The file size must not exceed 10MB.',
            'revision.required' => 'Please provide a revision identifier.',
            'revision.string' => 'The revision identifier must be a valid string.',
            'revision.max' => 'The revision identifier may not be greater than 255 characters.',
            'change_log.required' => 'Please provide a change log detailing the updates.',
            'change_log.string' => 'The change log must be a valid string.',
        ];
    }
}
