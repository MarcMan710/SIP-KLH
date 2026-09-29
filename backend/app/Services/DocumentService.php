<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// Handle document storage and management.
//
// Responsibilities:
// - validate and store uploaded files
// - generate safe storage names
// - save document metadata
// - delete documents when permitted
// - retrieve document metadata
//
// Keep physical storage logic outside controllers.
//
// Use Laravel's filesystem abstraction.
//
// Ensure uploaded documents cannot be used to bypass
// application authorization.
//
// Consider queue processing later if document processing
// becomes expensive.
class DocumentService
{
    /**
     * Upload and store a supporting document for a project.
     */
    public function uploadDocument(Project $project, User $user, UploadedFile $file, ?string $documentType = null): Document
    {
        if (! $project->isEditable()) {
            throw new BadRequestHttpException('Documents cannot be added to a project in its current status.');
        }

        return DB::transaction(function () use ($project, $user, $file, $documentType) {
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType() ?: $file->getMimeType();
            $fileSize = $file->getSize();

            // Store in storage/app/public/documents/{project_id}
            $storedPath = $file->store("documents/{$project->id}", 'public');

            $document = Document::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'document_type' => $documentType ?: 'supporting_document',
                'original_name' => $originalName,
                'file_path' => $storedPath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'uploaded_at' => now(),
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'action' => 'DOCUMENT_UPLOADED',
                'old_status' => $project->status->value,
                'new_status' => $project->status->value,
                'description' => "Uploaded document: {$originalName}",
            ]);

            return $document->load('user');
        });
    }

    /**
     * Delete document and its physical file.
     */
    public function deleteDocument(Document $document, User $user): bool
    {
        $project = $document->project;

        if (! $project->isEditable()) {
            throw new BadRequestHttpException('Documents cannot be deleted from a project in its current status.');
        }

        return DB::transaction(function () use ($document, $project, $user) {
            $docName = $document->original_name;

            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $deleted = $document->delete();

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'action' => 'DOCUMENT_DELETED',
                'old_status' => $project->status->value,
                'new_status' => $project->status->value,
                'description' => "Deleted document: {$docName}",
            ]);

            return $deleted;
        });
    }
}
