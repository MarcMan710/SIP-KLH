<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Create the users table.
//
// Include:
// - primary key
// - name
// - unique email
// - hashed password
// - user role
// - timestamps
//
// Add an index to fields commonly used for authentication
// and filtering.
//
// Configure the schema for PostgreSQL.
//
// Ensure role values cannot contain invalid application roles
// when appropriate.
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default(UserRole::PEMOHON->value)->index();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['email', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
