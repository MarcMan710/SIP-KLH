<?php

namespace App\Http\Requests\Document;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

// Validate uploaded project documents.
//
// Validate:
// - required file
// - allowed file format
// - maximum file size
// - document type
//
// Reject unsupported or potentially unsafe files.
//
// Ensure the authenticated user has permission
// to upload the document to the specified project.
//
// The technical test specifically identifies file-format
// and file-size validation as an additional feature.
class UploadDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $project && $this->user()?->can('update', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240', // 10MB maximum
            ],
            'document_type' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'A document file is required.',
            'file.mimes' => 'The file must be a PDF, DOC, DOCX, JPG, JPEG, or PNG.',
            'file.max' => 'The file may not be greater than 10MB.',
        ];
    }
}
