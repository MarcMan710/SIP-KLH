<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Represent users who access the application.
//
// Store basic account information such as:
// - name
// - email
// - password
// - role
//
// Define relationships:
// - User has many Projects as a Pemohon.
// - User has many Assessments as a Penilai.
// - User has many ActivityLogs.
//
// Integrate Laravel Sanctum so authenticated users
// can access protected REST API endpoints.
//
// Hide sensitive authentication fields from API responses.
//
// Add appropriate casts and mass-assignment protection.
//
// The role determines whether the user can access
// applicant or assessor functionality.
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * User has many Projects as a Pemohon.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    /**
     * User has many Assessments as a Penilai.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'assessor_id');
    }

    /**
     * User has many Documents uploaded.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    /**
     * User has many ActivityLogs.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    /**
     * Check if user is Pemohon.
     */
    public function isPemohon(): bool
    {
        return $this->role === UserRole::PEMOHON;
    }

    /**
     * Check if user is Penilai.
     */
    public function isPenilai(): bool
    {
        return $this->role === UserRole::PENILAI;
    }
}
