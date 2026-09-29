<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Create the activity_logs table.
//
// Store the audit history of project activities.
//
// Include:
// - project foreign key
// - user foreign key
// - action
// - old status
// - new status
// - description
// - timestamp
//
// Add indexes on project_id, user_id, action,
// and created_at.
//
// This table is expected to potentially grow very large,
// so its indexing strategy should support history queries efficiently.
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->index();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('description');
            $table->timestamps();

            $table->index('project_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['project_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
