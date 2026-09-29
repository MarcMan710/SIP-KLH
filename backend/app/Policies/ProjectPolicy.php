<?php

namespace App\Policies;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;

// Control authorization for project operations.
//
// Rules should include:
// - Pemohon can view their own projects.
// - Pemohon can edit their own draft projects.
// - Pemohon cannot edit submitted projects.
// - Pemohon cannot access another user's projects.
// - Penilai can access projects that require assessment.
// - Only authorized users can submit projects.
//
// Keep authorization rules centralized rather than
// duplicating them across controllers.
class ProjectPolicy
{
    /**
     * Determine whether the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the specific project.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->isPenilai()) {
            // Assessors can view projects that are submitted, under review, revision required, approved, or rejected
            return $project->status !== ProjectStatus::DRAFT;
        }

        return $project->user_id === $user->id;
    }

    /**
     * Determine whether the user can create projects.
     */
    public function create(User $user): bool
    {
        return $user->isPemohon();
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->id !== $project->user_id) {
            return false;
        }

        return $project->isEditable();
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        if ($user->id !== $project->user_id) {
            return false;
        }

        return $project->isDraft();
    }

    /**
     * Determine whether the user can submit the project.
     */
    public function submit(User $user, Project $project): bool
    {
        if ($user->id !== $project->user_id) {
            return false;
        }

        return $project->isDraft();
    }

    /**
     * Determine whether the user can resubmit the project after revision.
     */
    public function resubmit(User $user, Project $project): bool
    {
        if ($user->id !== $project->user_id) {
            return false;
        }

        return $project->status === ProjectStatus::REVISION_REQUIRED;
    }
}
