<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Create the assessments table.
//
// Store:
// - project foreign key
// - assessor foreign key
// - assessment status/action
// - notes
// - assessment timestamp
// - timestamps
//
// Add indexes for project_id, assessor_id,
// status/action, and created_at.
//
// Preserve multiple assessment records for the same project
// so the application can display historical evaluations.
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('assessor_id')->constrained('users')->cascadeOnDelete();
            $table->string('action')->index();
            $table->text('notes')->nullable();
            $table->timestamp('assessed_at')->useCurrent()->index();
            $table->timestamps();

            $table->index('project_id');
            $table->index('assessor_id');
            $table->index('created_at');
            $table->index(['project_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
