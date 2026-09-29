<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Represent documents uploaded as part of an application.
//
// Store document metadata rather than exposing
// the physical storage path directly.
//
// Information may include:
// - project ID
// - document type
// - original filename
// - stored filename/path
// - MIME type
// - file size
// - uploader
// - upload timestamp
//
// Define relationships:
// - Document belongs to Project.
// - Document belongs to User who uploaded it.
//
// Do not place complicated file-upload logic inside the model.
// File handling should be delegated to DocumentService.
class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'document_type',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'uploaded_at' => 'datetime',
        ];
    }

    /**
     * Relationship to project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relationship to uploader user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
