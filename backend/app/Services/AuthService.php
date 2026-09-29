<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Contain authentication business logic.
//
// Handle:
// - user registration
// - password hashing
// - login verification
// - Sanctum token creation
// - logout/token revocation
//
// Keep authentication logic out of controllers.
//
// Ensure privileged roles cannot be self-assigned
// through normal registration.
class AuthService
{
    /**
     * Register a new user. Default role is strictly PEMOHON.
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::PEMOHON, // Privileged roles cannot be self-assigned
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Authenticate user credentials and issue Sanctum token.
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Revoke tokens on logout.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
