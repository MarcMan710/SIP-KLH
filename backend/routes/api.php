<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RevisionController;
use Illuminate\Support\Facades\Route;

// Define all REST API endpoints.
//
// Group routes under an API version such as /api/v1.
//
// Public routes:
// - register
// - login
//
// Authenticated routes:
// - logout
// - current user
// - dashboard
// - projects
// - documents
// - assessments
// - revisions
// - history
//
// Apply Sanctum authentication middleware.
//
// Apply role/permission middleware where necessary.
//
// Keep endpoint naming RESTful and consistent.
//
// Use route model binding where appropriate.
//
// Do not place business logic directly inside this file.
Route::prefix('v1')->group(function () {
    // Public authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    // Protected routes requiring Sanctum token
    Route::middleware('auth:sanctum')->group(function () {
        // Authenticated user
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });

        // Dashboard endpoints (role-protected)
        Route::prefix('dashboard')->group(function () {
            Route::get('pemohon', [DashboardController::class, 'pemohon'])->middleware('role:PEMOHON');
            Route::get('penilai', [DashboardController::class, 'penilai'])->middleware('role:PENILAI');
        });

        // Project management endpoints
        Route::get('projects', [ProjectController::class, 'index']);
        Route::post('projects', [ProjectController::class, 'store'])->middleware('role:PEMOHON');
        Route::get('projects/{project}', [ProjectController::class, 'show']);
        Route::put('projects/{project}', [ProjectController::class, 'update']);
        Route::delete('projects/{project}', [ProjectController::class, 'destroy']);
        Route::post('projects/{project}/submit', [ProjectController::class, 'submit'])->middleware('role:PEMOHON');

        // Document management endpoints
        Route::get('projects/{project}/documents', [DocumentController::class, 'index']);
        Route::post('projects/{project}/documents', [DocumentController::class, 'store'])->middleware('role:PEMOHON');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy']);

        // Assessment endpoints (Penilai role)
        Route::get('assessments', [AssessmentController::class, 'index'])->middleware('role:PENILAI');
        Route::get('projects/{project}/assessment', [AssessmentController::class, 'show']);
        Route::post('projects/{project}/assessment', [AssessmentController::class, 'review'])->middleware('role:PENILAI');
        Route::post('projects/{project}/approve', [AssessmentController::class, 'approve'])->middleware('role:PENILAI');
        Route::post('projects/{project}/reject', [AssessmentController::class, 'reject'])->middleware('role:PENILAI');

        // Revision endpoints
        Route::get('projects/{project}/revisions', [RevisionController::class, 'index']);
        Route::post('projects/{project}/revision', [RevisionController::class, 'store'])->middleware('role:PENILAI');
        Route::post('projects/{project}/resubmit', [RevisionController::class, 'resubmit'])->middleware('role:PEMOHON');

        // Activity and Assessment History endpoints
        Route::get('projects/{project}/history', [HistoryController::class, 'projectHistory']);
        Route::get('assessment-history', [HistoryController::class, 'assessmentHistory']);
    });
});
