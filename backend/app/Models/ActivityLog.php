<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Store an audit trail for important application activities.
//
// Record events such as:
// - project created
// - project updated
// - document uploaded
// - project submitted
// - project reviewed
// - revision requested
// - project resubmitted
// - project approved
// - project rejected
//
// Store the responsible user,
// previous status,
// new status,
// action,
// description,
// and timestamp.
//
// This model supports the required approval/log history feature.
class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'description',
    ];

    /**
     * Relationship to project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relationship to acting user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
