<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// Define the JSON representation of a project.
//
// Include:
// - project ID
// - project number
// - project name
// - description
// - status
// - applicant information when appropriate
// - submission date
// - timestamps
//
// Include related documents or assessment summaries
// only when they have already been loaded.
//
// Avoid accidental N+1 queries.
class ProjectResource extends JsonResource
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
            'project_number' => $this->project_number,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status?->value ?? (string) $this->status,
            'status_label' => method_exists($this->status, 'label') ? $this->status->label() : null,
            'is_editable' => $this->isEditable(),
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'user' => new UserResource($this->whenLoaded('user')),
            'documents' => DocumentResource::collection($this->whenLoaded('documents')),
            'documents_count' => $this->whenCounted('documents'),
            'assessments' => AssessmentResource::collection($this->whenLoaded('assessments')),
            'latest_assessment' => new AssessmentResource($this->whenLoaded('latestAssessment')),
            'revisions' => RevisionResource::collection($this->whenLoaded('revisions')),
            'latest_revision' => new RevisionResource($this->whenLoaded('latestRevision')),
            'activity_logs' => HistoryResource::collection($this->whenLoaded('activityLogs')),
        ];
    }
}
