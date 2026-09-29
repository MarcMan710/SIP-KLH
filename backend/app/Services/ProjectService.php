<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// Contain project/application business logic.
//
// Handle:
// - project creation
// - project retrieval
// - project updates
// - project deletion
// - project submission
//
// Enforce valid status transitions.
//
// Generate project/application numbers when appropriate.
//
// Create activity logs for important operations.
//
// Use database transactions for operations where multiple
// database changes must succeed or fail together.
//
// Optimize queries with appropriate eager loading,
// filtering, selected columns, and pagination.
class ProjectService
{
    /**
     * Get paginated projects with filters and role-based scoping.
     */
    public function getProjects(User $user, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Project::query()
            ->with(['user:id,name,email'])
            ->withCount('documents');

        // Pemohon can only view their own projects
        if ($user->isPemohon()) {
            $query->forUser($user->id);
        } else {
            // Penilai only views submitted/assessed projects, never drafts of others
            $query->where('status', '!=', ProjectStatus::DRAFT->value);
        }

        // Apply status filter
        if (! empty($filters['status'])) {
            $query->status($filters['status']);
        }

        // Apply search filter (name or project_number)
        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Create a new project in DRAFT state.
     */
    public function createProject(User $user, array $data): Project
    {
        return DB::transaction(function () use ($user, $data) {
            $projectNumber = $this->generateProjectNumber();

            $project = Project::create([
                'user_id' => $user->id,
                'project_number' => $projectNumber,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'status' => ProjectStatus::DRAFT,
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'action' => 'PROJECT_CREATED',
                'old_status' => null,
                'new_status' => ProjectStatus::DRAFT->value,
                'description' => "Project {$project->project_number} was created as DRAFT.",
            ]);

            return $project->load('user');
        });
    }

    /**
     * Update project information while still editable.
     */
    public function updateProject(Project $project, User $user, array $data): Project
    {
        if (! $project->isEditable()) {
            throw new BadRequestHttpException('Project cannot be updated in its current status.');
        }

        return DB::transaction(function () use ($project, $user, $data) {
            $project->update([
                'name' => $data['name'] ?? $project->name,
                'description' => array_key_exists('description', $data) ? $data['description'] : $project->description,
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'action' => 'PROJECT_UPDATED',
                'old_status' => $project->status->value,
                'new_status' => $project->status->value,
                'description' => 'Project details were updated.',
            ]);

            return $project;
        });
    }

    /**
     * Delete draft project.
     */
    public function deleteProject(Project $project): bool
    {
        if (! $project->isDraft()) {
            throw new BadRequestHttpException('Only draft projects can be deleted.');
        }

        return DB::transaction(function () use ($project) {
            return (bool) $project->delete();
        });
    }

    /**
     * Submit draft project for assessment.
     */
    public function submitProject(Project $project, User $user): Project
    {
        if (! $project->isDraft()) {
            throw new BadRequestHttpException('Only draft projects can be submitted.');
        }

        return DB::transaction(function () use ($project, $user) {
            $oldStatus = $project->status->value;

            $project->update([
                'status' => ProjectStatus::SUBMITTED,
                'submitted_at' => now(),
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'action' => 'PROJECT_SUBMITTED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::SUBMITTED->value,
                'description' => "Project {$project->project_number} was submitted for assessment.",
            ]);

            return $project;
        });
    }

    /**
     * Generate unique project number.
     * Format: PRJ-YYYYMMDD-XXXXX
     */
    protected function generateProjectNumber(): string
    {
        $prefix = 'PRJ-'.now()->format('Ymd').'-';

        do {
            $random = strtoupper(Str::random(5));
            $number = $prefix.$random;
        } while (Project::where('project_number', $number)->exists());

        return $number;
    }
}
