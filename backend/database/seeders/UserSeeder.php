<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Create development/test users.
//
// Create sample:
// - Pemohon accounts
// - Penilai accounts
//
// Use safe test passwords.
//
// Assign roles explicitly.
//
// This allows the application to be demonstrated
// quickly during the technical-test review.
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password123');

        // Main Demo Pemohon Account
        User::updateOrCreate(
            ['email' => 'pemohon@sipklh.id'],
            [
                'name' => 'Budi Pemohon (PT Maju Mundur)',
                'password' => $password,
                'role' => UserRole::PEMOHON,
            ]
        );

        // Main Demo Penilai Account
        User::updateOrCreate(
            ['email' => 'penilai@sipklh.id'],
            [
                'name' => 'Siti Penilai (Tim Penilai KLH)',
                'password' => $password,
                'role' => UserRole::PENILAI,
            ]
        );

        // Additional demo users
        User::updateOrCreate(
            ['email' => 'pemohon2@sipklh.id'],
            [
                'name' => 'Ahmad Pemohon (CV Karya Lestari)',
                'password' => $password,
                'role' => UserRole::PEMOHON,
            ]
        );

        User::updateOrCreate(
            ['email' => 'penilai2@sipklh.id'],
            [
                'name' => 'Dr. Bambang (Assessor Senior)',
                'password' => $password,
                'role' => UserRole::PENILAI,
            ]
        );
    }
}
