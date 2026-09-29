<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Create the revisions table.
//
// Store each revision request separately.
//
// Include:
// - project foreign key
// - assessment foreign key
// - requester/assessor foreign key
// - revision notes
// - resolved_at
// - timestamps
//
// Add indexes for project_id and created_at.
//
// Preserve historical revision requests instead of
// updating a single revision record repeatedly.
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('assessment_id')->nullable()->constrained('assessments')->nullOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->text('notes');
            $table->timestamp('resolved_at')->nullable()->index();
            $table->timestamps();

            $table->index('project_id');
            $table->index('created_at');
            $table->index(['project_id', 'resolved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
