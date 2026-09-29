<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'action' => 'PROJECT_CREATED',
            'old_status' => null,
            'new_status' => 'DRAFT',
            'description' => fake()->sentence(),
        ];
    }
}
