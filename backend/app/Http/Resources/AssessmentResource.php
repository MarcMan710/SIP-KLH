<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// Define the API response for assessment information.
//
// Include:
// - assessment ID
// - assessor
// - action/status
// - notes
// - assessment date
//
// Include project information only when necessary.
//
// Keep the response consistent across assessment endpoints.
class AssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'assessor_id' => $this->assessor_id,
            'action' => $this->action?->value ?? (string) $this->action,
            'action_label' => method_exists($this->action, 'label') ? $this->action->label() : null,
            'notes' => $this->notes,
            'assessed_at' => $this->assessed_at?->toIso8601String() ?? $this->created_at?->toIso8601String(),
            'assessor' => new UserResource($this->whenLoaded('assessor')),
            'project' => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}
