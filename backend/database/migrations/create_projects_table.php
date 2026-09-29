<?php

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Create the projects table.
//
// Include:
// - primary key
// - applicant/user foreign key
// - unique project/application number
// - project name
// - description
// - status
// - submitted_at
// - timestamps
//
// Add foreign-key constraints.
//
// Add indexes for:
// - user_id
// - status
// - project number
// - created_at
//
// These indexes are important because the technical test
// expects the system to handle large amounts of project data.
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('project_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default(ProjectStatus::DRAFT->value)->index();
            $table->timestamp('submitted_at')->nullable()->index();
            $table->timestamps();

            $table->index('user_id');
            $table->index('project_number');
            $table->index('created_at');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
