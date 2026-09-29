<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

// Validate a revision request.
//
// Require a clear revision note explaining
// what the Pemohon needs to correct.
//
// Ensure the project is currently under review.
//
// Only Penilai users can request revisions.
//
// The request should trigger a status transition
// to REVISION_REQUIRED.
class RevisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $project && $this->user()?->can('requestRevision', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notes' => ['required', 'string', 'min:5', 'max:5000'],
        ];
    }
}
