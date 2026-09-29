<?php

namespace App\Services;

use App\Enums\AssessmentAction;
use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// Contain the assessment workflow.
//
// Handle:
// - starting assessment
// - adding assessment notes
// - requesting revision
// - approving
// - rejecting
//
// Validate the current project status before every transition.
//
// Update project status and create assessment records.
//
// Create activity-log records for every workflow transition.
//
// Use database transactions to keep project status,
// assessment records, and logs synchronized.
//
// Do not allow arbitrary status changes from API clients.
class AssessmentService
{
    /**
     * Get applications requiring assessment for assessors.
     */
    public function getPendingAssessments(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Project::query()
            ->with(['user:id,name,email', 'latestAssessment'])
            ->withCount('documents')
            ->whereIn('status', [
                ProjectStatus::SUBMITTED->value,
                ProjectStatus::UNDER_REVIEW->value,
                ProjectStatus::RESUBMITTED->value,
            ]);

        if (! empty($filters['status'])) {
            $query->status($filters['status']);
        }

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->latest('submitted_at')->paginate($perPage);
    }

    /**
     * Start reviewing an application or save assessment notes.
     */
    public function reviewProject(Project $project, User $assessor, ?string $notes = null): Assessment
    {
        if (! in_array($project->status, [ProjectStatus::SUBMITTED, ProjectStatus::UNDER_REVIEW, ProjectStatus::RESUBMITTED], true)) {
            throw new BadRequestHttpException('Project is not in an assessable state.');
        }

        return DB::transaction(function () use ($project, $assessor, $notes) {
            $oldStatus = $project->status->value;

            $project->update([
                'status' => ProjectStatus::UNDER_REVIEW,
            ]);

            $assessment = Assessment::create([
                'project_id' => $project->id,
                'assessor_id' => $assessor->id,
                'action' => AssessmentAction::REVIEW,
                'notes' => $notes,
                'assessed_at' => now(),
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $assessor->id,
                'action' => 'ASSESSMENT_REVIEW',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::UNDER_REVIEW->value,
                'description' => "Assessor {$assessor->name} started review: ".($notes ?: 'No notes provided.'),
            ]);

            return $assessment->load('assessor');
        });
    }

    /**
     * Approve the project application.
     */
    public function approveProject(Project $project, User $assessor, ?string $notes = null): Assessment
    {
        if (! in_array($project->status, [ProjectStatus::SUBMITTED, ProjectStatus::UNDER_REVIEW, ProjectStatus::RESUBMITTED], true)) {
            throw new BadRequestHttpException('Project cannot be approved in its current status.');
        }

        return DB::transaction(function () use ($project, $assessor, $notes) {
            $oldStatus = $project->status->value;

            $project->update([
                'status' => ProjectStatus::APPROVED,
            ]);

            $assessment = Assessment::create([
                'project_id' => $project->id,
                'assessor_id' => $assessor->id,
                'action' => AssessmentAction::APPROVE,
                'notes' => $notes,
                'assessed_at' => now(),
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $assessor->id,
                'action' => 'PROJECT_APPROVED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::APPROVED->value,
                'description' => "Project was approved by {$assessor->name}. ".($notes ? "Notes: {$notes}" : ''),
            ]);

            return $assessment->load('assessor');
        });
    }

    /**
     * Reject the project application.
     */
    public function rejectProject(Project $project, User $assessor, ?string $notes = null): Assessment
    {
        if (! in_array($project->status, [ProjectStatus::SUBMITTED, ProjectStatus::UNDER_REVIEW, ProjectStatus::RESUBMITTED], true)) {
            throw new BadRequestHttpException('Project cannot be rejected in its current status.');
        }

        return DB::transaction(function () use ($project, $assessor, $notes) {
            $oldStatus = $project->status->value;

            $project->update([
                'status' => ProjectStatus::REJECTED,
            ]);

            $assessment = Assessment::create([
                'project_id' => $project->id,
                'assessor_id' => $assessor->id,
                'action' => AssessmentAction::REJECT,
                'notes' => $notes,
                'assessed_at' => now(),
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $assessor->id,
                'action' => 'PROJECT_REJECTED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::REJECTED->value,
                'description' => "Project was rejected by {$assessor->name}. ".($notes ? "Notes: {$notes}" : ''),
            ]);

            return $assessment->load('assessor');
        });
    }
}
