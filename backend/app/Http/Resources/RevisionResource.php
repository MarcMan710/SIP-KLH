<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisionResource extends JsonResource
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
            'assessment_id' => $this->assessment_id,
            'requester_id' => $this->requester_id,
            'notes' => $this->notes,
            'resolved_at' => $this->resolved_at?->toIso8601String(),
            'is_resolved' => $this->isResolved(),
            'created_at' => $this->created_at?->toIso8601String(),
            'requester' => new UserResource($this->whenLoaded('requester')),
            'assessment' => new AssessmentResource($this->whenLoaded('assessment')),
        ];
    }
}
