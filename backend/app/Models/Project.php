<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// Represent a document application/project.
//
// Store information such as:
// - applicant/user ID
// - project/application number
// - project name
// - description
// - current status
// - submitted timestamp
// - created timestamp
// - updated timestamp
//
// Define relationships:
// - Project belongs to User.
// - Project has many Documents.
// - Project has many Assessments.
// - Project has many Revisions.
// - Project has many ActivityLogs.
//
// Add database scopes for commonly used filters,
// such as status, applicant, and date.
//
// Use appropriate casts for timestamps and status.
//
// This model should represent the application data,
// while workflow logic should remain inside services.
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_number',
        'name',
        'description',
        'status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Relationship to owner (Pemohon).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship to supporting documents.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'project_id');
    }

    /**
     * Relationship to assessments by Penilai.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'project_id');
    }

    /**
     * Latest assessment.
     */
    public function latestAssessment(): HasOne
    {
        return $this->hasOne(Assessment::class, 'project_id')->latestOfMany();
    }

    /**
     * Relationship to revisions requested.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class, 'project_id');
    }

    /**
     * Latest revision.
     */
    public function latestRevision(): HasOne
    {
        return $this->hasOne(Revision::class, 'project_id')->latestOfMany();
    }

    /**
     * Relationship to audit activity logs.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'project_id');
    }

    /**
     * Scope filter by status.
     */
    public function scopeStatus(Builder $query, string|ProjectStatus $status): Builder
    {
        $statusValue = $status instanceof ProjectStatus ? $status->value : $status;

        return $query->where('status', $statusValue);
    }

    /**
     * Scope filter by owner user.
     */
    public function scopeForUser(Builder $query, int|string $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope search by name or project_number.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('project_number', 'like', "%{$search}%");
        });
    }

    /**
     * Check if project is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === ProjectStatus::DRAFT;
    }

    /**
     * Check if project can be edited by applicant.
     */
    public function isEditable(): bool
    {
        return in_array($this->status, [ProjectStatus::DRAFT, ProjectStatus::REVISION_REQUIRED], true);
    }
}
