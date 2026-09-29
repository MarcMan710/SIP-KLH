<?php

namespace App\Http\Requests\Assessment;

use App\Enums\AssessmentAction;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

// Validate an assessor's evaluation request.
//
// Accept only supported assessment actions,
// such as approval or rejection.
//
// Require appropriate notes when necessary.
//
// Ensure the target project is currently in a state
// that can be assessed.
//
// Only Penilai users should be able to submit assessments.
class AssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $project && $this->user()?->can('assess', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action' => ['required', new Enum(AssessmentAction::class)],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
