<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Project;
use App\Models\Revision;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevisionFactory extends Factory
{
    protected $model = Revision::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'assessment_id' => Assessment::factory(),
            'requester_id' => User::factory()->penilai(),
            'notes' => fake()->paragraph(),
            'resolved_at' => null,
        ];
    }
}
