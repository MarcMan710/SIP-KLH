<?php

namespace Database\Factories;

use App\Enums\AssessmentAction;
use App\Models\Assessment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'assessor_id' => User::factory()->penilai(),
            'action' => AssessmentAction::REVIEW,
            'notes' => fake()->paragraph(),
            'assessed_at' => now(),
        ];
    }
}
