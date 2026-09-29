<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

// Handle retrieval of project activity and assessment history.
//
// Provide optimized, paginated history queries.
//
// Apply authorization before returning records.
//
// Support filtering by:
// - project
// - user
// - action
// - status
// - date range
//
// Because history can become very large,
// avoid loading the entire history table into memory.
class HistoryService
{
    /**
     * Get paginated audit logs for a project.
     */
    public function getProjectHistory(Project $project, int $perPage = 20): LengthAwarePaginator
    {
        return $project->activityLogs()
            ->with('user:id,name,email,role')
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Get paginated assessment history (for assessors or general view).
     */
    public function getAssessmentHistory(User $user, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Assessment::query()
            ->with(['assessor:id,name,email', 'project:id,project_number,name,status,user_id']);

        if ($user->isPenilai()) {
            // Assessors can filter by their own assessments or all assessments
            if (! empty($filters['assessor_id'])) {
                $query->where('assessor_id', $filters['assessor_id']);
            }
        } elseif ($user->isPemohon()) {
            // Pemohon only sees assessments on their own projects
            $query->whereHas('project', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if (! empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (! empty($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        return $query->latest('id')->paginate($perPage);
    }
}
