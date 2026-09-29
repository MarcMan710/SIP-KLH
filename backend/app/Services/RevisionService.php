<?php

namespace App\Services;

use App\Enums\AssessmentAction;
use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\Project;
use App\Models\Revision;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// Handle revision workflow.
//
// Responsibilities:
// - create revision requests
// - retrieve revision history
// - allow applicants to update revision-required projects
// - process project resubmission
//
// Preserve previous revision records.
//
// When the applicant resubmits,
// update the project to the appropriate review state.
//
// Create activity logs for revision and resubmission events.
class RevisionService
{
    /**
     * Get revision requests for a project.
     */
    public function getProjectRevisions(Project $project, int $perPage = 20): LengthAwarePaginator
    {
        return $project->revisions()
            ->with(['requester:id,name,email', 'assessment'])
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Create a revision request (Penilai action).
     */
    public function requestRevision(Project $project, User $assessor, string $notes): Revision
    {
        if (! in_array($project->status, [ProjectStatus::SUBMITTED, ProjectStatus::UNDER_REVIEW, ProjectStatus::RESUBMITTED], true)) {
            throw new BadRequestHttpException('Project is not in a reviewable state for revision requests.');
        }

        return DB::transaction(function () use ($project, $assessor, $notes) {
            $oldStatus = $project->status->value;

            $project->update([
                'status' => ProjectStatus::REVISION_REQUIRED,
            ]);

            $assessment = Assessment::create([
                'project_id' => $project->id,
                'assessor_id' => $assessor->id,
                'action' => AssessmentAction::REQUEST_REVISION,
                'notes' => $notes,
                'assessed_at' => now(),
            ]);

            $revision = Revision::create([
                'project_id' => $project->id,
                'assessment_id' => $assessment->id,
                'requester_id' => $assessor->id,
                'notes' => $notes,
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $assessor->id,
                'action' => 'REVISION_REQUESTED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::REVISION_REQUIRED->value,
                'description' => "Revision requested by {$assessor->name}: {$notes}",
            ]);

            return $revision->load(['requester', 'assessment']);
        });
    }

    /**
     * Resubmit a revision-required project (Pemohon action).
     */
    public function resubmitProject(Project $project, User $applicant): Project
    {
        if ($project->status !== ProjectStatus::REVISION_REQUIRED) {
            throw new BadRequestHttpException('Only projects with REVISION_REQUIRED status can be resubmitted.');
        }

        return DB::transaction(function () use ($project, $applicant) {
            $oldStatus = $project->status->value;

            // Mark all pending revisions for this project as resolved
            $project->revisions()
                ->whereNull('resolved_at')
                ->update(['resolved_at' => now()]);

            $project->update([
                'status' => ProjectStatus::UNDER_REVIEW,
                'submitted_at' => now(),
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $applicant->id,
                'action' => 'PROJECT_RESUBMITTED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::UNDER_REVIEW->value,
                'description' => "Project {$project->project_number} was resubmitted after revision.",
            ]);

            return $project;
        });
    }
}
