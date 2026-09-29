<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_number' => 'PRJ-'.now()->format('Ymd').'-'.strtoupper(Str::random(5)),
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => ProjectStatus::DRAFT,
            'submitted_at' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => ProjectStatus::DRAFT, 'submitted_at' => null]);
    }

    public function submitted(): static
    {
        return $this->state(fn () => ['status' => ProjectStatus::SUBMITTED, 'submitted_at' => now()]);
    }

    public function underReview(): static
    {
        return $this->state(fn () => ['status' => ProjectStatus::UNDER_REVIEW, 'submitted_at' => now()->subDay()]);
    }

    public function revisionRequired(): static
    {
        return $this->state(fn () => ['status' => ProjectStatus::REVISION_REQUIRED, 'submitted_at' => now()->subDays(2)]);
    }

    public function approved(): static
    {
        return $this->state(fn () => ['status' => ProjectStatus::APPROVED, 'submitted_at' => now()->subDays(3)]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => ProjectStatus::REJECTED, 'submitted_at' => now()->subDays(4)]);
    }
}
