<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Represent a revision request made by a Penilai.
//
// Store:
// - project ID
// - related assessment ID
// - requester/assessor ID
// - revision notes
// - resolution timestamp
// - creation timestamp
//
// Define relationships to:
// - Project
// - Assessment
// - User
//
// Preserve every revision request so applicants and assessors
// can see the complete revision history.
class Revision extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'assessment_id',
        'requester_id',
        'notes',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
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
     * Relationship to assessment.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    /**
     * Relationship to requester user (Penilai).
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Check if revision is resolved.
     */
    public function isResolved(): bool
    {
        return $this->resolved_at !== null;
    }
}
