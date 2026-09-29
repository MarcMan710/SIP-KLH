<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Create the documents table.
//
// Store document metadata and its relationship
// to the project.
//
// Add:
// - project foreign key
// - uploader foreign key
// - document type
// - original filename
// - storage path
// - MIME type
// - file size
// - upload timestamp
//
// Add indexes on project_id and uploaded_at.
//
// Configure foreign-key behavior carefully so that
// deleting projects does not leave orphaned document records.
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('document_type')->default('supporting_document');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->timestamp('uploaded_at')->useCurrent()->index();
            $table->timestamps();

            $table->index('project_id');
            $table->index(['project_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
