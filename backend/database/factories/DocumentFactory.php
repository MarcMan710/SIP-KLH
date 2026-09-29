<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'document_type' => 'supporting_document',
            'original_name' => fake()->word().'.pdf',
            'file_path' => 'documents/sample.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(100000, 2000000),
            'uploaded_at' => now(),
        ];
    }
}
