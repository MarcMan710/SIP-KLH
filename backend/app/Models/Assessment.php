<?php

namespace App\Models;

use App\Enums\AssessmentAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Represent an assessor's evaluation of a project.
//
// Store:
// - project ID
// - assessor ID
// - assessment status/action
// - assessment notes
// - assessment timestamp
//
// Define relationships:
// - Assessment belongs to Project.
// - Assessment belongs to User as the assessor.
// - Assessment may have Revisions.
//
// Each important assessment action should produce
// a corresponding activity/history record.
//
// Previous assessments should remain available
// rather than being overwritten.
class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'assessor_id',
        'action',
        'notes',
        'assessed_at',
    ];

    protected function casts(): array
    {
        return [
            'action' => AssessmentAction::class,
            'assessed_at' => 'datetime',
        ];
    }

    /**
     * Relationship to project being assessed.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relationship to assessor (User).
     */
    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    /**
     * Relationship to revisions resulting from this assessment.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class, 'assessment_id');
    }
}
