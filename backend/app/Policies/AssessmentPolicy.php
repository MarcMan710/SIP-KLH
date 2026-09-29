<?php

namespace App\Policies;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;

// Control assessor permissions.
//
// Only Penilai users can:
// - assess applications
// - request revisions
// - approve applications
// - reject applications
//
// Prevent Pemohon users from directly changing
// assessment status.
class AssessmentPolicy
{
    /**
     * Determine whether the user can assess the project.
     */
    public function assess(User $user, Project $project): bool
    {
        if (! $user->isPenilai()) {
            return false;
        }

        // Assessors can assess projects that are submitted, under review, or resubmitted
        return in_array($project->status, [
            ProjectStatus::SUBMITTED,
            ProjectStatus::UNDER_REVIEW,
            ProjectStatus::RESUBMITTED,
        ], true);
    }

    /**
     * Determine whether the user can request revision.
     */
    public function requestRevision(User $user, Project $project): bool
    {
        return $this->assess($user, $project);
    }

    /**
     * Determine whether the user can approve the project.
     */
    public function approve(User $user, Project $project): bool
    {
        return $this->assess($user, $project);
    }

    /**
     * Determine whether the user can reject the project.
     */
    public function reject(User $user, Project $project): bool
    {
        return $this->assess($user, $project);
    }
}
