<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

// Control access to project documents.
//
// Verify that:
// - Pemohon owns the project.
// - Penilai has legitimate access to the project.
// - users cannot access documents belonging to
//   unrelated projects.
//
// Apply authorization before download,
// deletion, or modification operations.
class DocumentPolicy
{
    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        $project = $document->project;

        if ($user->isPenilai()) {
            return ! $project->isDraft();
        }

        return $project->user_id === $user->id;
    }

    /**
     * Determine whether the user can upload documents to the project.
     */
    public function upload(User $user, Document $document): bool
    {
        $project = $document->project;

        if ($user->id !== $project->user_id) {
            return false;
        }

        return $project->isEditable();
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        $project = $document->project;

        if ($user->id !== $project->user_id) {
            return false;
        }

        return $project->isEditable();
    }
}
